<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SolidWP\Performance\Symfony\Component\HttpClient\Exception;

use SolidWP\Performance\Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * Represents a 5xx response.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class ServerException extends \RuntimeException implements ServerExceptionInterface
{
    use HttpExceptionTrait;
}
