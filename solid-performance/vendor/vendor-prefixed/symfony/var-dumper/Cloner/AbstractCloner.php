<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SolidWP\Performance\Symfony\Component\VarDumper\Cloner;

use SolidWP\Performance\Symfony\Component\VarDumper\Caster\Caster;
use SolidWP\Performance\Symfony\Component\VarDumper\Exception\ThrowingCasterException;

/**
 * AbstractCloner implements a generic caster mechanism for objects and resources.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
abstract class AbstractCloner implements ClonerInterface
{
    public static $defaultCasters = [
        '__PHP_Incomplete_Class' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\Caster', 'castPhpIncompleteClass'],

        'SolidWP\Performance\Symfony\Component\VarDumper\Caster\CutStub' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'castStub'],
        'SolidWP\Performance\Symfony\Component\VarDumper\Caster\CutArrayStub' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'castCutArray'],
        'SolidWP\Performance\Symfony\Component\VarDumper\Caster\ConstStub' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'castStub'],
        'SolidWP\Performance\Symfony\Component\VarDumper\Caster\EnumStub' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'castEnum'],

        'Fiber' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\FiberCaster', 'castFiber'],

        'Closure' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castClosure'],
        'Generator' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castGenerator'],
        'ReflectionType' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castType'],
        'ReflectionAttribute' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castAttribute'],
        'ReflectionGenerator' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castReflectionGenerator'],
        'ReflectionClass' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castClass'],
        'ReflectionClassConstant' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castClassConstant'],
        'ReflectionFunctionAbstract' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castFunctionAbstract'],
        'ReflectionMethod' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castMethod'],
        'ReflectionParameter' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castParameter'],
        'ReflectionProperty' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castProperty'],
        'ReflectionReference' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castReference'],
        'ReflectionExtension' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castExtension'],
        'ReflectionZendExtension' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castZendExtension'],

        'Doctrine\Common\Persistence\ObjectManager' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Doctrine\Common\Proxy\Proxy' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DoctrineCaster', 'castCommonProxy'],
        'Doctrine\ORM\Proxy\Proxy' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DoctrineCaster', 'castOrmProxy'],
        'Doctrine\ORM\PersistentCollection' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DoctrineCaster', 'castPersistentCollection'],
        'Doctrine\Persistence\ObjectManager' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],

        'DOMException' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castException'],
        'DOMStringList' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castLength'],
        'DOMNameList' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castLength'],
        'DOMImplementation' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castImplementation'],
        'DOMImplementationList' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castLength'],
        'DOMNode' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castNode'],
        'DOMNameSpaceNode' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castNameSpaceNode'],
        'DOMDocument' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castDocument'],
        'DOMNodeList' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castLength'],
        'DOMNamedNodeMap' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castLength'],
        'DOMCharacterData' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castCharacterData'],
        'DOMAttr' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castAttr'],
        'DOMElement' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castElement'],
        'DOMText' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castText'],
        'DOMTypeinfo' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castTypeinfo'],
        'DOMDomError' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castDomError'],
        'DOMLocator' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castLocator'],
        'DOMDocumentType' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castDocumentType'],
        'DOMNotation' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castNotation'],
        'DOMEntity' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castEntity'],
        'DOMProcessingInstruction' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castProcessingInstruction'],
        'DOMXPath' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DOMCaster', 'castXPath'],

        'XMLReader' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\XmlReaderCaster', 'castXmlReader'],

        'ErrorException' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castErrorException'],
        'Exception' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castException'],
        'Error' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castError'],
        'Symfony\Bridge\Monolog\Logger' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\DependencyInjection\ContainerInterface' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\EventDispatcher\EventDispatcherInterface' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'SolidWP\Performance\Symfony\Component\HttpClient\AmpHttpClient' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castHttpClient'],
        'SolidWP\Performance\Symfony\Component\HttpClient\CurlHttpClient' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castHttpClient'],
        'SolidWP\Performance\Symfony\Component\HttpClient\NativeHttpClient' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castHttpClient'],
        'SolidWP\Performance\Symfony\Component\HttpClient\Response\AmpResponse' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'SolidWP\Performance\Symfony\Component\HttpClient\Response\CurlResponse' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'SolidWP\Performance\Symfony\Component\HttpClient\Response\NativeResponse' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castHttpClientResponse'],
        'Symfony\Component\HttpFoundation\Request' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castRequest'],
        'Symfony\Component\Uid\Ulid' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castUlid'],
        'Symfony\Component\Uid\Uuid' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SymfonyCaster', 'castUuid'],
        'SolidWP\Performance\Symfony\Component\VarDumper\Exception\ThrowingCasterException' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castThrowingCasterException'],
        'SolidWP\Performance\Symfony\Component\VarDumper\Caster\TraceStub' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castTraceStub'],
        'SolidWP\Performance\Symfony\Component\VarDumper\Caster\FrameStub' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castFrameStub'],
        'SolidWP\Performance\Symfony\Component\VarDumper\Cloner\AbstractCloner' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Symfony\Component\ErrorHandler\Exception\SilencedErrorContext' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ExceptionCaster', 'castSilencedErrorContext'],

        'Imagine\Image\ImageInterface' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ImagineCaster', 'castImage'],

        'Ramsey\Uuid\UuidInterface' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\UuidCaster', 'castRamseyUuid'],

        'ProxyManager\Proxy\ProxyInterface' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ProxyManagerCaster', 'castProxy'],
        'PHPUnit_Framework_MockObject_MockObject' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'PHPUnit\Framework\MockObject\MockObject' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'PHPUnit\Framework\MockObject\Stub' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Prophecy\Prophecy\ProphecySubjectInterface' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],
        'Mockery\MockInterface' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\StubCaster', 'cutInternals'],

        'PDO' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\PdoCaster', 'castPdo'],
        'PDOStatement' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\PdoCaster', 'castPdoStatement'],

        'AMQPConnection' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\AmqpCaster', 'castConnection'],
        'AMQPChannel' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\AmqpCaster', 'castChannel'],
        'AMQPQueue' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\AmqpCaster', 'castQueue'],
        'AMQPExchange' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\AmqpCaster', 'castExchange'],
        'AMQPEnvelope' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\AmqpCaster', 'castEnvelope'],

        'ArrayObject' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castArrayObject'],
        'ArrayIterator' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castArrayIterator'],
        'SplDoublyLinkedList' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castDoublyLinkedList'],
        'SplFileInfo' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castFileInfo'],
        'SplFileObject' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castFileObject'],
        'SplHeap' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castHeap'],
        'SplObjectStorage' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castObjectStorage'],
        'SplPriorityQueue' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castHeap'],
        'OuterIterator' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castOuterIterator'],
        'WeakReference' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\SplCaster', 'castWeakReference'],

        'Redis' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RedisCaster', 'castRedis'],
        'RedisArray' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RedisCaster', 'castRedisArray'],
        'RedisCluster' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RedisCaster', 'castRedisCluster'],

        'DateTimeInterface' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DateCaster', 'castDateTime'],
        'DateInterval' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DateCaster', 'castInterval'],
        'DateTimeZone' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DateCaster', 'castTimeZone'],
        'DatePeriod' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DateCaster', 'castPeriod'],

        'GMP' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\GmpCaster', 'castGmp'],

        'MessageFormatter' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\IntlCaster', 'castMessageFormatter'],
        'NumberFormatter' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\IntlCaster', 'castNumberFormatter'],
        'IntlTimeZone' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\IntlCaster', 'castIntlTimeZone'],
        'IntlCalendar' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\IntlCaster', 'castIntlCalendar'],
        'IntlDateFormatter' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\IntlCaster', 'castIntlDateFormatter'],

        'Memcached' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\MemcachedCaster', 'castMemcached'],

        'Ds\Collection' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DsCaster', 'castCollection'],
        'Ds\Map' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DsCaster', 'castMap'],
        'Ds\Pair' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DsCaster', 'castPair'],
        'SolidWP\Performance\Symfony\Component\VarDumper\Caster\DsPairStub' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\DsCaster', 'castPairStub'],

        'CurlHandle' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castCurl'],
        ':curl' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castCurl'],

        ':dba' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castDba'],
        ':dba persistent' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castDba'],

        'GdImage' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castGd'],
        ':gd' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castGd'],

        ':mysql link' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castMysqlLink'],
        ':pgsql large object' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\PgSqlCaster', 'castLargeObject'],
        ':pgsql link' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\PgSqlCaster', 'castLink'],
        ':pgsql link persistent' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\PgSqlCaster', 'castLink'],
        ':pgsql result' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\PgSqlCaster', 'castResult'],
        ':process' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castProcess'],
        ':stream' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castStream'],

        'OpenSSLCertificate' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castOpensslX509'],
        ':OpenSSL X.509' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castOpensslX509'],

        ':persistent stream' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castStream'],
        ':stream-context' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\ResourceCaster', 'castStreamContext'],

        'XmlParser' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\XmlResourceCaster', 'castXml'],
        ':xml' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\XmlResourceCaster', 'castXml'],

        'RdKafka' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castRdKafka'],
        'RdKafka\Conf' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castConf'],
        'RdKafka\KafkaConsumer' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castKafkaConsumer'],
        'RdKafka\Metadata\Broker' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castBrokerMetadata'],
        'RdKafka\Metadata\Collection' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castCollectionMetadata'],
        'RdKafka\Metadata\Partition' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castPartitionMetadata'],
        'RdKafka\Metadata\Topic' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castTopicMetadata'],
        'RdKafka\Message' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castMessage'],
        'RdKafka\Topic' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castTopic'],
        'RdKafka\TopicPartition' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castTopicPartition'],
        'RdKafka\TopicConf' => ['SolidWP\Performance\Symfony\Component\VarDumper\Caster\RdKafkaCaster', 'castTopicConf'],
    ];

    protected $maxItems = 2500;
    protected $maxString = -1;
    protected $minDepth = 1;

    /**
     * @var array<string, list<callable>>
     */
    private $casters = [];

    /**
     * @var callable|null
     */
    private $prevErrorHandler;

    private $classInfo = [];
    private $filter = 0;

    /**
     * @param callable[]|null $casters A map of casters
     *
     * @see addCasters
     */
    public function __construct(array $casters = null)
    {
        if (null === $casters) {
            $casters = static::$defaultCasters;
        }
        $this->addCasters($casters);
    }

    /**
     * Adds casters for resources and objects.
     *
     * Maps resources or objects types to a callback.
     * Types are in the key, with a callable caster for value.
     * Resource types are to be prefixed with a `:`,
     * see e.g. static::$defaultCasters.
     *
     * @param callable[] $casters A map of casters
     */
    public function addCasters(array $casters)
    {
        foreach ($casters as $type => $callback) {
            $this->casters[$type][] = $callback;
        }
    }

    /**
     * Sets the maximum number of items to clone past the minimum depth in nested structures.
     */
    public function setMaxItems(int $maxItems)
    {
        $this->maxItems = $maxItems;
    }

    /**
     * Sets the maximum cloned length for strings.
     */
    public function setMaxString(int $maxString)
    {
        $this->maxString = $maxString;
    }

    /**
     * Sets the minimum tree depth where we are guaranteed to clone all the items.  After this
     * depth is reached, only setMaxItems items will be cloned.
     */
    public function setMinDepth(int $minDepth)
    {
        $this->minDepth = $minDepth;
    }

    /**
     * Clones a PHP variable.
     *
     * @param mixed $var    Any PHP variable
     * @param int   $filter A bit field of Caster::EXCLUDE_* constants
     *
     * @return Data
     */
    public function cloneVar($var, int $filter = 0)
    {
        $this->prevErrorHandler = set_error_handler(function ($type, $msg, $file, $line, $context = []) {
            if (\E_RECOVERABLE_ERROR === $type || \E_USER_ERROR === $type) {
                // Cloner never dies
                throw new \ErrorException($msg, 0, $type, $file, $line);
            }

            if ($this->prevErrorHandler) {
                return ($this->prevErrorHandler)($type, $msg, $file, $line, $context);
            }

            return false;
        });
        $this->filter = $filter;

        if ($gc = gc_enabled()) {
            gc_disable();
        }
        try {
            return new Data($this->doClone($var));
        } finally {
            if ($gc) {
                gc_enable();
            }
            restore_error_handler();
            $this->prevErrorHandler = null;
        }
    }

    /**
     * Effectively clones the PHP variable.
     *
     * @param mixed $var Any PHP variable
     *
     * @return array
     */
    abstract protected function doClone($var);

    /**
     * Casts an object to an array representation.
     *
     * @param bool $isNested True if the object is nested in the dumped structure
     *
     * @return array
     */
    protected function castObject(Stub $stub, bool $isNested)
    {
        $obj = $stub->value;
        $class = $stub->class;

        if (\PHP_VERSION_ID < 80000 ? "\0" === ($class[15] ?? null) : str_contains($class, "@anonymous\0")) {
            $stub->class = get_debug_type($obj);
        }
        if (isset($this->classInfo[$class])) {
            [$i, $parents, $hasDebugInfo, $fileInfo] = $this->classInfo[$class];
        } else {
            $i = 2;
            $parents = [$class];
            $hasDebugInfo = method_exists($class, '__debugInfo');

            foreach (class_parents($class) as $p) {
                $parents[] = $p;
                ++$i;
            }
            foreach (class_implements($class) as $p) {
                $parents[] = $p;
                ++$i;
            }
            $parents[] = '*';

            $r = new \ReflectionClass($class);
            $fileInfo = $r->isInternal() || $r->isSubclassOf(Stub::class) ? [] : [
                'file' => $r->getFileName(),
                'line' => $r->getStartLine(),
            ];

            $this->classInfo[$class] = [$i, $parents, $hasDebugInfo, $fileInfo];
        }

        $stub->attr += $fileInfo;
        $a = Caster::castObject($obj, $class, $hasDebugInfo, $stub->class);

        try {
            while ($i--) {
                if (!empty($this->casters[$p = $parents[$i]])) {
                    foreach ($this->casters[$p] as $callback) {
                        $a = $callback($obj, $a, $stub, $isNested, $this->filter);
                    }
                }
            }
        } catch (\Exception $e) {
            $a = [(Stub::TYPE_OBJECT === $stub->type ? Caster::PREFIX_VIRTUAL : '').'⚠' => new ThrowingCasterException($e)] + $a;
        }

        return $a;
    }

    /**
     * Casts a resource to an array representation.
     *
     * @param bool $isNested True if the object is nested in the dumped structure
     *
     * @return array
     */
    protected function castResource(Stub $stub, bool $isNested)
    {
        $a = [];
        $res = $stub->value;
        $type = $stub->class;

        try {
            if (!empty($this->casters[':'.$type])) {
                foreach ($this->casters[':'.$type] as $callback) {
                    $a = $callback($res, $a, $stub, $isNested, $this->filter);
                }
            }
        } catch (\Exception $e) {
            $a = [(Stub::TYPE_OBJECT === $stub->type ? Caster::PREFIX_VIRTUAL : '').'⚠' => new ThrowingCasterException($e)] + $a;
        }

        return $a;
    }
}
