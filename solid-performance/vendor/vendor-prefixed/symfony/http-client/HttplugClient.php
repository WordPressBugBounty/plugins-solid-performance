<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SolidWP\Performance\Symfony\Component\HttpClient;

use GuzzleHttp\Promise\Promise as GuzzlePromise;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Promise\Utils;
use Http\Client\Exception\NetworkException;
use Http\Client\Exception\RequestException;
use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient as HttplugInterface;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Http\Message\UriFactory;
use Http\Promise\Promise;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Uri;
use SolidWP\Performance\Psr\Http\Message\RequestFactoryInterface;
use SolidWP\Performance\Psr\Http\Message\RequestInterface;
use SolidWP\Performance\Psr\Http\Message\ResponseFactoryInterface;
use SolidWP\Performance\Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use SolidWP\Performance\Psr\Http\Message\StreamFactoryInterface;
use SolidWP\Performance\Psr\Http\Message\StreamInterface;
use SolidWP\Performance\Psr\Http\Message\UriFactoryInterface;
use SolidWP\Performance\Psr\Http\Message\UriInterface;
use SolidWP\Performance\Symfony\Component\HttpClient\Internal\HttplugWaitLoop;
use SolidWP\Performance\Symfony\Component\HttpClient\Response\HttplugPromise;
use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\HttpClientInterface;
use SolidWP\Performance\Symfony\Contracts\HttpClient\ResponseInterface;
use SolidWP\Performance\Symfony\Contracts\Service\ResetInterface;

if (!interface_exists(HttplugInterface::class)) {
    throw new \LogicException('You cannot use "SolidWP\Performance\Symfony\Component\HttpClient\HttplugClient" as the "php-http/httplug" package is not installed. Try running "composer require php-http/httplug".');
}

if (!interface_exists(RequestFactory::class)) {
    throw new \LogicException('You cannot use "SolidWP\Performance\Symfony\Component\HttpClient\HttplugClient" as the "php-http/message-factory" package is not installed. Try running "composer require php-http/message-factory".');
}

/**
 * An adapter to turn a Symfony HttpClientInterface into an Httplug client.
 *
 * Run "composer require nyholm/psr7" to install an efficient implementation of response
 * and stream factories with flex-provided autowiring aliases.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class HttplugClient implements HttplugInterface, HttpAsyncClient, RequestFactory, StreamFactory, UriFactory, ResetInterface
{
    private $client;
    private $responseFactory;
    private $streamFactory;

    /**
     * @var \SplObjectStorage<ResponseInterface, array{RequestInterface, Promise}>|null
     */
    private $promisePool;

    private $waitLoop;

    public function __construct(?HttpClientInterface $client = null, ?ResponseFactoryInterface $responseFactory = null, ?StreamFactoryInterface $streamFactory = null)
    {
        $this->client = $client ?? HttpClient::create();
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory ?? ($responseFactory instanceof StreamFactoryInterface ? $responseFactory : null);
        $this->promisePool = class_exists(Utils::class) ? new \SplObjectStorage() : null;

        if (null === $this->responseFactory || null === $this->streamFactory) {
            if (!class_exists(Psr17Factory::class) && !class_exists(Psr17FactoryDiscovery::class)) {
                throw new \LogicException('You cannot use the "SolidWP\Performance\Symfony\Component\HttpClient\HttplugClient" as no PSR-17 factories have been provided. Try running "composer require nyholm/psr7".');
            }

            try {
                $psr17Factory = class_exists(Psr17Factory::class, false) ? new Psr17Factory() : null;
                $this->responseFactory = $this->responseFactory ?? $psr17Factory ?? Psr17FactoryDiscovery::findResponseFactory();
                $this->streamFactory = $this->streamFactory ?? $psr17Factory ?? Psr17FactoryDiscovery::findStreamFactory();
            } catch (NotFoundException $e) {
                throw new \LogicException('You cannot use the "SolidWP\Performance\Symfony\Component\HttpClient\HttplugClient" as no PSR-17 factories have been found. Try running "composer require nyholm/psr7".', 0, $e);
            }
        }

        $this->waitLoop = new HttplugWaitLoop($this->client, $this->promisePool, $this->responseFactory, $this->streamFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): Psr7ResponseInterface
    {
        try {
            return HttplugWaitLoop::createPsr7Response($this->responseFactory, $this->streamFactory, $this->client, $this->sendPsr7Request($request), true);
        } catch (TransportExceptionInterface $e) {
            throw new NetworkException($e->getMessage(), $request, $e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return HttplugPromise
     */
    public function sendAsyncRequest(RequestInterface $request): Promise
    {
        if (!$promisePool = $this->promisePool) {
            throw new \LogicException(sprintf('You cannot use "%s()" as the "guzzlehttp/promises" package is not installed. Try running "composer require guzzlehttp/promises".', __METHOD__));
        }

        try {
            $response = $this->sendPsr7Request($request, true);
        } catch (NetworkException $e) {
            return new HttplugPromise(new RejectedPromise($e));
        }

        $waitLoop = $this->waitLoop;

        $promise = new GuzzlePromise(static function () use ($response, $waitLoop) {
            $waitLoop->wait($response);
        }, static function () use ($response, $promisePool) {
            $response->cancel();
            unset($promisePool[$response]);
        });

        $promisePool[$response] = [$request, $promise];

        return new HttplugPromise($promise);
    }

    /**
     * Resolves pending promises that complete before the timeouts are reached.
     *
     * When $maxDuration is null and $idleTimeout is reached, promises are rejected.
     *
     * @return int The number of remaining pending promises
     */
    public function wait(?float $maxDuration = null, ?float $idleTimeout = null): int
    {
        return $this->waitLoop->wait(null, $maxDuration, $idleTimeout);
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1'): RequestInterface
    {
        if ($this->responseFactory instanceof RequestFactoryInterface) {
            $request = $this->responseFactory->createRequest($method, $uri);
        } elseif (class_exists(Request::class)) {
            $request = new Request($method, $uri);
        } elseif (class_exists(Psr17FactoryDiscovery::class)) {
            $request = Psr17FactoryDiscovery::findRequestFactory()->createRequest($method, $uri);
        } else {
            throw new \LogicException(sprintf('You cannot use "%s()" as the "nyholm/psr7" package is not installed. Try running "composer require nyholm/psr7".', __METHOD__));
        }

        $request = $request
            ->withProtocolVersion($protocolVersion)
            ->withBody($this->createStream($body))
        ;

        foreach ($headers as $name => $value) {
            $request = $request->withAddedHeader($name, $value);
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function createStream($body = null): StreamInterface
    {
        if ($body instanceof StreamInterface) {
            return $body;
        }

        if (\is_string($body ?? '')) {
            $stream = $this->streamFactory->createStream($body ?? '');
        } elseif (\is_resource($body)) {
            $stream = $this->streamFactory->createStreamFromResource($body);
        } else {
            throw new \InvalidArgumentException(sprintf('"%s()" expects string, resource or StreamInterface, "%s" given.', __METHOD__, get_debug_type($body)));
        }

        if ($stream->isSeekable()) {
            $stream->seek(0);
        }

        return $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function createUri($uri): UriInterface
    {
        if ($uri instanceof UriInterface) {
            return $uri;
        }

        if ($this->responseFactory instanceof UriFactoryInterface) {
            return $this->responseFactory->createUri($uri);
        }

        if (class_exists(Uri::class)) {
            return new Uri($uri);
        }

        if (class_exists(Psr17FactoryDiscovery::class)) {
            return Psr17FactoryDiscovery::findUrlFactory()->createUri($uri);
        }

        throw new \LogicException(sprintf('You cannot use "%s()" as the "nyholm/psr7" package is not installed. Try running "composer require nyholm/psr7".', __METHOD__));
    }

    public function __sleep(): array
    {
        throw new \BadMethodCallException('Cannot serialize '.__CLASS__);
    }

    public function __wakeup()
    {
        throw new \BadMethodCallException('Cannot unserialize '.__CLASS__);
    }

    public function __destruct()
    {
        $this->wait();
    }

    public function reset()
    {
        if ($this->client instanceof ResetInterface) {
            $this->client->reset();
        }
    }

    private function sendPsr7Request(RequestInterface $request, ?bool $buffer = null): ResponseInterface
    {
        try {
            $body = $request->getBody();

            if ($body->isSeekable()) {
                $body->seek(0);
            }

            $options = [
                'headers' => $request->getHeaders(),
                'body' => $body->getContents(),
                'buffer' => $buffer,
            ];

            if ('1.0' === $request->getProtocolVersion()) {
                $options['http_version'] = '1.0';
            }

            return $this->client->request($request->getMethod(), (string) $request->getUri(), $options);
        } catch (\InvalidArgumentException $e) {
            throw new RequestException($e->getMessage(), $request, $e);
        } catch (TransportExceptionInterface $e) {
            throw new NetworkException($e->getMessage(), $request, $e);
        }
    }
}
