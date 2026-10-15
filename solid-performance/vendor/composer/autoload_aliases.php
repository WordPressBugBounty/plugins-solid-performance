<?php

// Functions and constants

namespace {
if(!defined('MB_CASE_UPPER') && defined('SolidWP_Performance_MB_CASE_UPPER')) {
    define('MB_CASE_UPPER', SolidWP_Performance_MB_CASE_UPPER);
}if(!defined('MB_CASE_LOWER') && defined('SolidWP_Performance_MB_CASE_LOWER')) {
    define('MB_CASE_LOWER', SolidWP_Performance_MB_CASE_LOWER);
}if(!defined('MB_CASE_TITLE') && defined('SolidWP_Performance_MB_CASE_TITLE')) {
    define('MB_CASE_TITLE', SolidWP_Performance_MB_CASE_TITLE);
}if(!defined('FILTER_VALIDATE_BOOL') && defined('SolidWP_Performance_FILTER_VALIDATE_BOOL')) {
    define('FILTER_VALIDATE_BOOL', SolidWP_Performance_FILTER_VALIDATE_BOOL);
}if(!defined('MYSQLI_REFRESH_REPLICA') && defined('SolidWP_Performance_MYSQLI_REFRESH_REPLICA')) {
    define('MYSQLI_REFRESH_REPLICA', SolidWP_Performance_MYSQLI_REFRESH_REPLICA);
}
}
namespace Symfony\Component\VarDumper\Dumper {
    if(!function_exists('\\Symfony\\Component\\VarDumper\\Dumper\\esc')){
        function esc(...$args) {
            return \SolidWP\Performance\Symfony\Component\VarDumper\Dumper\esc(...func_get_args());
        }
    }
}


namespace SolidWP\Performance {

    use BrianHenryIE\Strauss\Types\AutoloadAliasInterface;

    /**
     * @see AutoloadAliasInterface
     *
     * @phpstan-type ClassAliasArray array{'type':'class',isabstract:bool,classname:string,namespace?:string,extends:string,implements:array<string>}
     * @phpstan-type InterfaceAliasArray array{'type':'interface',interfacename:string,namespace?:string,extends:array<string>}
     * @phpstan-type TraitAliasArray array{'type':'trait',traitname:string,namespace?:string,use:array<string>}
     * @phpstan-type EnumAliasArray array{'type':'enum',enumname:string,namespace?:string,concrete:string}
     * @phpstan-type AutoloadAliasArray array<string,ClassAliasArray|InterfaceAliasArray|TraitAliasArray|EnumAliasArray>
     */
    class AliasAutoloader
    {
        private string $includeFilePath;

        /**
         * @var AutoloadAliasArray
         */
        private array $autoloadAliases = array (
  'Flintstone\\Cache\\ArrayCache' => 
  array (
    'type' => 'class',
    'classname' => 'ArrayCache',
    'isabstract' => false,
    'namespace' => 'Flintstone\\Cache',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Cache\\ArrayCache',
    'implements' => 
    array (
      0 => 'Flintstone\\Cache\\CacheInterface',
    ),
  ),
  'Flintstone\\Config' => 
  array (
    'type' => 'class',
    'classname' => 'Config',
    'isabstract' => false,
    'namespace' => 'Flintstone',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Config',
    'implements' => 
    array (
    ),
  ),
  'Flintstone\\Database' => 
  array (
    'type' => 'class',
    'classname' => 'Database',
    'isabstract' => false,
    'namespace' => 'Flintstone',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Database',
    'implements' => 
    array (
    ),
  ),
  'Flintstone\\Exception' => 
  array (
    'type' => 'class',
    'classname' => 'Exception',
    'isabstract' => false,
    'namespace' => 'Flintstone',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Exception',
    'implements' => 
    array (
    ),
  ),
  'Flintstone\\Flintstone' => 
  array (
    'type' => 'class',
    'classname' => 'Flintstone',
    'isabstract' => false,
    'namespace' => 'Flintstone',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Flintstone',
    'implements' => 
    array (
    ),
  ),
  'Flintstone\\Formatter\\JsonFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'JsonFormatter',
    'isabstract' => false,
    'namespace' => 'Flintstone\\Formatter',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Formatter\\JsonFormatter',
    'implements' => 
    array (
      0 => 'Flintstone\\Formatter\\FormatterInterface',
    ),
  ),
  'Flintstone\\Formatter\\SerializeFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'SerializeFormatter',
    'isabstract' => false,
    'namespace' => 'Flintstone\\Formatter',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Formatter\\SerializeFormatter',
    'implements' => 
    array (
      0 => 'Flintstone\\Formatter\\FormatterInterface',
    ),
  ),
  'Flintstone\\Line' => 
  array (
    'type' => 'class',
    'classname' => 'Line',
    'isabstract' => false,
    'namespace' => 'Flintstone',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Line',
    'implements' => 
    array (
    ),
  ),
  'Flintstone\\Validation' => 
  array (
    'type' => 'class',
    'classname' => 'Validation',
    'isabstract' => false,
    'namespace' => 'Flintstone',
    'extends' => 'SolidWP\\Performance\\Flintstone\\Validation',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\Exceptions\\FileinfoSupportMissing' => 
  array (
    'type' => 'class',
    'classname' => 'FileinfoSupportMissing',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\Exceptions',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Exceptions\\FileinfoSupportMissing',
    'implements' => 
    array (
      0 => 'League\\Uri\\Contracts\\UriException',
    ),
  ),
  'League\\Uri\\Exceptions\\IdnSupportMissing' => 
  array (
    'type' => 'class',
    'classname' => 'IdnSupportMissing',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\Exceptions',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Exceptions\\IdnSupportMissing',
    'implements' => 
    array (
      0 => 'League\\Uri\\Contracts\\UriException',
    ),
  ),
  'League\\Uri\\Exceptions\\IdnaConversionFailed' => 
  array (
    'type' => 'class',
    'classname' => 'IdnaConversionFailed',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\Exceptions',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Exceptions\\IdnaConversionFailed',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\Exceptions\\SyntaxError' => 
  array (
    'type' => 'class',
    'classname' => 'SyntaxError',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\Exceptions',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Exceptions\\SyntaxError',
    'implements' => 
    array (
      0 => 'League\\Uri\\Contracts\\UriException',
    ),
  ),
  'League\\Uri\\Idna\\Idna' => 
  array (
    'type' => 'class',
    'classname' => 'Idna',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\Idna',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Idna\\Idna',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\Idna\\IdnaInfo' => 
  array (
    'type' => 'class',
    'classname' => 'IdnaInfo',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\Idna',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Idna\\IdnaInfo',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\Exceptions\\TemplateCanNotBeExpanded' => 
  array (
    'type' => 'class',
    'classname' => 'TemplateCanNotBeExpanded',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\Exceptions',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Exceptions\\TemplateCanNotBeExpanded',
    'implements' => 
    array (
      0 => 'League\\Uri\\Contracts\\UriException',
    ),
  ),
  'League\\Uri\\Http' => 
  array (
    'type' => 'class',
    'classname' => 'Http',
    'isabstract' => false,
    'namespace' => 'League\\Uri',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Http',
    'implements' => 
    array (
      0 => 'Psr\\Http\\Message\\UriInterface',
      1 => 'JsonSerializable',
    ),
  ),
  'League\\Uri\\HttpFactory' => 
  array (
    'type' => 'class',
    'classname' => 'HttpFactory',
    'isabstract' => false,
    'namespace' => 'League\\Uri',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\HttpFactory',
    'implements' => 
    array (
      0 => 'Psr\\Http\\Message\\UriFactoryInterface',
    ),
  ),
  'League\\Uri\\Uri' => 
  array (
    'type' => 'class',
    'classname' => 'Uri',
    'isabstract' => false,
    'namespace' => 'League\\Uri',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\Uri',
    'implements' => 
    array (
      0 => 'League\\Uri\\Contracts\\UriInterface',
    ),
  ),
  'League\\Uri\\UriInfo' => 
  array (
    'type' => 'class',
    'classname' => 'UriInfo',
    'isabstract' => false,
    'namespace' => 'League\\Uri',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\UriInfo',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\UriResolver' => 
  array (
    'type' => 'class',
    'classname' => 'UriResolver',
    'isabstract' => false,
    'namespace' => 'League\\Uri',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\UriResolver',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\UriString' => 
  array (
    'type' => 'class',
    'classname' => 'UriString',
    'isabstract' => false,
    'namespace' => 'League\\Uri',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\UriString',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\UriTemplate\\Expression' => 
  array (
    'type' => 'class',
    'classname' => 'Expression',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\UriTemplate',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\UriTemplate\\Expression',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\UriTemplate\\Template' => 
  array (
    'type' => 'class',
    'classname' => 'Template',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\UriTemplate',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\UriTemplate\\Template',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\UriTemplate\\VarSpecifier' => 
  array (
    'type' => 'class',
    'classname' => 'VarSpecifier',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\UriTemplate',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\UriTemplate\\VarSpecifier',
    'implements' => 
    array (
    ),
  ),
  'League\\Uri\\UriTemplate\\VariableBag' => 
  array (
    'type' => 'class',
    'classname' => 'VariableBag',
    'isabstract' => false,
    'namespace' => 'League\\Uri\\UriTemplate',
    'extends' => 'SolidWP\\Performance\\League\\Uri\\UriTemplate\\VariableBag',
    'implements' => 
    array (
    ),
  ),
  'lucatume\\DI52\\App' => 
  array (
    'type' => 'class',
    'classname' => 'App',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\App',
    'implements' => 
    array (
    ),
  ),
  'lucatume\\DI52\\Builders\\CallableBuilder' => 
  array (
    'type' => 'class',
    'classname' => 'CallableBuilder',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\CallableBuilder',
    'implements' => 
    array (
      0 => 'lucatume\\DI52\\Builders\\BuilderInterface',
      1 => 'lucatume\\DI52\\Builders\\ReinitializableBuilderInterface',
    ),
  ),
  'lucatume\\DI52\\Builders\\ClassBuilder' => 
  array (
    'type' => 'class',
    'classname' => 'ClassBuilder',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\ClassBuilder',
    'implements' => 
    array (
      0 => 'lucatume\\DI52\\Builders\\BuilderInterface',
      1 => 'lucatume\\DI52\\Builders\\ReinitializableBuilderInterface',
    ),
  ),
  'lucatume\\DI52\\Builders\\ClosureBuilder' => 
  array (
    'type' => 'class',
    'classname' => 'ClosureBuilder',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\ClosureBuilder',
    'implements' => 
    array (
      0 => 'lucatume\\DI52\\Builders\\BuilderInterface',
    ),
  ),
  'lucatume\\DI52\\Builders\\Factory' => 
  array (
    'type' => 'class',
    'classname' => 'Factory',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\Factory',
    'implements' => 
    array (
    ),
  ),
  'lucatume\\DI52\\Builders\\Parameter' => 
  array (
    'type' => 'class',
    'classname' => 'Parameter',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\Parameter',
    'implements' => 
    array (
    ),
  ),
  'lucatume\\DI52\\Builders\\Resolver' => 
  array (
    'type' => 'class',
    'classname' => 'Resolver',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\Resolver',
    'implements' => 
    array (
    ),
  ),
  'lucatume\\DI52\\Builders\\ValueBuilder' => 
  array (
    'type' => 'class',
    'classname' => 'ValueBuilder',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\ValueBuilder',
    'implements' => 
    array (
      0 => 'lucatume\\DI52\\Builders\\BuilderInterface',
    ),
  ),
  'lucatume\\DI52\\Container' => 
  array (
    'type' => 'class',
    'classname' => 'Container',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\Container',
    'implements' => 
    array (
      0 => 'ArrayAccess',
      1 => 'Psr\\Container\\ContainerInterface',
    ),
  ),
  'lucatume\\DI52\\ContainerException' => 
  array (
    'type' => 'class',
    'classname' => 'ContainerException',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\ContainerException',
    'implements' => 
    array (
      0 => 'Psr\\Container\\ContainerExceptionInterface',
    ),
  ),
  'lucatume\\DI52\\NestedParseError' => 
  array (
    'type' => 'class',
    'classname' => 'NestedParseError',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\NestedParseError',
    'implements' => 
    array (
    ),
  ),
  'lucatume\\DI52\\NotFoundException' => 
  array (
    'type' => 'class',
    'classname' => 'NotFoundException',
    'isabstract' => false,
    'namespace' => 'lucatume\\DI52',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\NotFoundException',
    'implements' => 
    array (
      0 => 'Psr\\Container\\NotFoundExceptionInterface',
    ),
  ),
  'lucatume\\DI52\\ServiceProvider' => 
  array (
    'type' => 'class',
    'classname' => 'ServiceProvider',
    'isabstract' => true,
    'namespace' => 'lucatume\\DI52',
    'extends' => 'SolidWP\\Performance\\lucatume\\DI52\\ServiceProvider',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Elements' => 
  array (
    'type' => 'class',
    'classname' => 'Elements',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Elements',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Entities' => 
  array (
    'type' => 'class',
    'classname' => 'Entities',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Entities',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Exception' => 
  array (
    'type' => 'class',
    'classname' => 'Exception',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Exception',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Parser\\CharacterReference' => 
  array (
    'type' => 'class',
    'classname' => 'CharacterReference',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\CharacterReference',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Parser\\DOMTreeBuilder' => 
  array (
    'type' => 'class',
    'classname' => 'DOMTreeBuilder',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\DOMTreeBuilder',
    'implements' => 
    array (
      0 => 'Masterminds\\HTML5\\Parser\\EventHandler',
    ),
  ),
  'Masterminds\\HTML5\\Parser\\FileInputStream' => 
  array (
    'type' => 'class',
    'classname' => 'FileInputStream',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\FileInputStream',
    'implements' => 
    array (
      0 => 'Masterminds\\HTML5\\Parser\\InputStream',
    ),
  ),
  'Masterminds\\HTML5\\Parser\\ParseError' => 
  array (
    'type' => 'class',
    'classname' => 'ParseError',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\ParseError',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Parser\\Scanner' => 
  array (
    'type' => 'class',
    'classname' => 'Scanner',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\Scanner',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Parser\\StringInputStream' => 
  array (
    'type' => 'class',
    'classname' => 'StringInputStream',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\StringInputStream',
    'implements' => 
    array (
      0 => 'Masterminds\\HTML5\\Parser\\InputStream',
    ),
  ),
  'Masterminds\\HTML5\\Parser\\Tokenizer' => 
  array (
    'type' => 'class',
    'classname' => 'Tokenizer',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\Tokenizer',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Parser\\TreeBuildingRules' => 
  array (
    'type' => 'class',
    'classname' => 'TreeBuildingRules',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\TreeBuildingRules',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Parser\\UTF8Utils' => 
  array (
    'type' => 'class',
    'classname' => 'UTF8Utils',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\UTF8Utils',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Serializer\\HTML5Entities' => 
  array (
    'type' => 'class',
    'classname' => 'HTML5Entities',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Serializer',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Serializer\\HTML5Entities',
    'implements' => 
    array (
    ),
  ),
  'Masterminds\\HTML5\\Serializer\\OutputRules' => 
  array (
    'type' => 'class',
    'classname' => 'OutputRules',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Serializer',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Serializer\\OutputRules',
    'implements' => 
    array (
      0 => 'Masterminds\\HTML5\\Serializer\\RulesInterface',
    ),
  ),
  'Masterminds\\HTML5\\Serializer\\Traverser' => 
  array (
    'type' => 'class',
    'classname' => 'Traverser',
    'isabstract' => false,
    'namespace' => 'Masterminds\\HTML5\\Serializer',
    'extends' => 'SolidWP\\Performance\\Masterminds\\HTML5\\Serializer\\Traverser',
    'implements' => 
    array (
    ),
  ),
  'Detection\\MobileDetect' => 
  array (
    'type' => 'class',
    'classname' => 'MobileDetect',
    'isabstract' => false,
    'namespace' => 'Detection',
    'extends' => 'SolidWP\\Performance\\Detection\\MobileDetect',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Attribute\\AsMonologProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'AsMonologProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Attribute',
    'extends' => 'SolidWP\\Performance\\Monolog\\Attribute\\AsMonologProcessor',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\DateTimeImmutable' => 
  array (
    'type' => 'class',
    'classname' => 'DateTimeImmutable',
    'isabstract' => false,
    'namespace' => 'Monolog',
    'extends' => 'SolidWP\\Performance\\Monolog\\DateTimeImmutable',
    'implements' => 
    array (
      0 => 'JsonSerializable',
    ),
  ),
  'Monolog\\ErrorHandler' => 
  array (
    'type' => 'class',
    'classname' => 'ErrorHandler',
    'isabstract' => false,
    'namespace' => 'Monolog',
    'extends' => 'SolidWP\\Performance\\Monolog\\ErrorHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\ChromePHPFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'ChromePHPFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\ChromePHPFormatter',
    'implements' => 
    array (
      0 => 'Monolog\\Formatter\\FormatterInterface',
    ),
  ),
  'Monolog\\Formatter\\ElasticaFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'ElasticaFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\ElasticaFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\ElasticsearchFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'ElasticsearchFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\ElasticsearchFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\FlowdockFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'FlowdockFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\FlowdockFormatter',
    'implements' => 
    array (
      0 => 'Monolog\\Formatter\\FormatterInterface',
    ),
  ),
  'Monolog\\Formatter\\FluentdFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'FluentdFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\FluentdFormatter',
    'implements' => 
    array (
      0 => 'Monolog\\Formatter\\FormatterInterface',
    ),
  ),
  'Monolog\\Formatter\\GelfMessageFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'GelfMessageFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\GelfMessageFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\GoogleCloudLoggingFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'GoogleCloudLoggingFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\GoogleCloudLoggingFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\HtmlFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'HtmlFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\HtmlFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\JsonFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'JsonFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\JsonFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\LineFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'LineFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\LineFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\LogglyFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'LogglyFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\LogglyFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\LogmaticFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'LogmaticFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\LogmaticFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\LogstashFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'LogstashFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\LogstashFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\MongoDBFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'MongoDBFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\MongoDBFormatter',
    'implements' => 
    array (
      0 => 'Monolog\\Formatter\\FormatterInterface',
    ),
  ),
  'Monolog\\Formatter\\NormalizerFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'NormalizerFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\NormalizerFormatter',
    'implements' => 
    array (
      0 => 'Monolog\\Formatter\\FormatterInterface',
    ),
  ),
  'Monolog\\Formatter\\ScalarFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'ScalarFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\ScalarFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Formatter\\WildfireFormatter' => 
  array (
    'type' => 'class',
    'classname' => 'WildfireFormatter',
    'isabstract' => false,
    'namespace' => 'Monolog\\Formatter',
    'extends' => 'SolidWP\\Performance\\Monolog\\Formatter\\WildfireFormatter',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\AbstractHandler' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractHandler',
    'isabstract' => true,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\AbstractHandler',
    'implements' => 
    array (
      0 => 'Monolog\\ResettableInterface',
    ),
  ),
  'Monolog\\Handler\\AbstractProcessingHandler' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractProcessingHandler',
    'isabstract' => true,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\AbstractProcessingHandler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\ProcessableHandlerInterface',
      1 => 'Monolog\\Handler\\FormattableHandlerInterface',
    ),
  ),
  'Monolog\\Handler\\AbstractSyslogHandler' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractSyslogHandler',
    'isabstract' => true,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\AbstractSyslogHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\AmqpHandler' => 
  array (
    'type' => 'class',
    'classname' => 'AmqpHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\AmqpHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\BrowserConsoleHandler' => 
  array (
    'type' => 'class',
    'classname' => 'BrowserConsoleHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\BrowserConsoleHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\BufferHandler' => 
  array (
    'type' => 'class',
    'classname' => 'BufferHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\BufferHandler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\ProcessableHandlerInterface',
      1 => 'Monolog\\Handler\\FormattableHandlerInterface',
    ),
  ),
  'Monolog\\Handler\\ChromePHPHandler' => 
  array (
    'type' => 'class',
    'classname' => 'ChromePHPHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\ChromePHPHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\CouchDBHandler' => 
  array (
    'type' => 'class',
    'classname' => 'CouchDBHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\CouchDBHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\CubeHandler' => 
  array (
    'type' => 'class',
    'classname' => 'CubeHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\CubeHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\Curl\\Util' => 
  array (
    'type' => 'class',
    'classname' => 'Util',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler\\Curl',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\Curl\\Util',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\DeduplicationHandler' => 
  array (
    'type' => 'class',
    'classname' => 'DeduplicationHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\DeduplicationHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\DoctrineCouchDBHandler' => 
  array (
    'type' => 'class',
    'classname' => 'DoctrineCouchDBHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\DoctrineCouchDBHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\DynamoDbHandler' => 
  array (
    'type' => 'class',
    'classname' => 'DynamoDbHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\DynamoDbHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\ElasticaHandler' => 
  array (
    'type' => 'class',
    'classname' => 'ElasticaHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\ElasticaHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\ElasticsearchHandler' => 
  array (
    'type' => 'class',
    'classname' => 'ElasticsearchHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\ElasticsearchHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\ErrorLogHandler' => 
  array (
    'type' => 'class',
    'classname' => 'ErrorLogHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\ErrorLogHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\FallbackGroupHandler' => 
  array (
    'type' => 'class',
    'classname' => 'FallbackGroupHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\FallbackGroupHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\FilterHandler' => 
  array (
    'type' => 'class',
    'classname' => 'FilterHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\FilterHandler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\ProcessableHandlerInterface',
      1 => 'Monolog\\ResettableInterface',
      2 => 'Monolog\\Handler\\FormattableHandlerInterface',
    ),
  ),
  'Monolog\\Handler\\FingersCrossed\\ChannelLevelActivationStrategy' => 
  array (
    'type' => 'class',
    'classname' => 'ChannelLevelActivationStrategy',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler\\FingersCrossed',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\FingersCrossed\\ChannelLevelActivationStrategy',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\FingersCrossed\\ActivationStrategyInterface',
    ),
  ),
  'Monolog\\Handler\\FingersCrossed\\ErrorLevelActivationStrategy' => 
  array (
    'type' => 'class',
    'classname' => 'ErrorLevelActivationStrategy',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler\\FingersCrossed',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\FingersCrossed\\ErrorLevelActivationStrategy',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\FingersCrossed\\ActivationStrategyInterface',
    ),
  ),
  'Monolog\\Handler\\FingersCrossedHandler' => 
  array (
    'type' => 'class',
    'classname' => 'FingersCrossedHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\FingersCrossedHandler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\ProcessableHandlerInterface',
      1 => 'Monolog\\ResettableInterface',
      2 => 'Monolog\\Handler\\FormattableHandlerInterface',
    ),
  ),
  'Monolog\\Handler\\FirePHPHandler' => 
  array (
    'type' => 'class',
    'classname' => 'FirePHPHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\FirePHPHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\FleepHookHandler' => 
  array (
    'type' => 'class',
    'classname' => 'FleepHookHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\FleepHookHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\FlowdockHandler' => 
  array (
    'type' => 'class',
    'classname' => 'FlowdockHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\FlowdockHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\GelfHandler' => 
  array (
    'type' => 'class',
    'classname' => 'GelfHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\GelfHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\GroupHandler' => 
  array (
    'type' => 'class',
    'classname' => 'GroupHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\GroupHandler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\ProcessableHandlerInterface',
      1 => 'Monolog\\ResettableInterface',
    ),
  ),
  'Monolog\\Handler\\Handler' => 
  array (
    'type' => 'class',
    'classname' => 'Handler',
    'isabstract' => true,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\Handler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\HandlerInterface',
    ),
  ),
  'Monolog\\Handler\\HandlerWrapper' => 
  array (
    'type' => 'class',
    'classname' => 'HandlerWrapper',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\HandlerWrapper',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\HandlerInterface',
      1 => 'Monolog\\Handler\\ProcessableHandlerInterface',
      2 => 'Monolog\\Handler\\FormattableHandlerInterface',
      3 => 'Monolog\\ResettableInterface',
    ),
  ),
  'Monolog\\Handler\\IFTTTHandler' => 
  array (
    'type' => 'class',
    'classname' => 'IFTTTHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\IFTTTHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\InsightOpsHandler' => 
  array (
    'type' => 'class',
    'classname' => 'InsightOpsHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\InsightOpsHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\LogEntriesHandler' => 
  array (
    'type' => 'class',
    'classname' => 'LogEntriesHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\LogEntriesHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\LogglyHandler' => 
  array (
    'type' => 'class',
    'classname' => 'LogglyHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\LogglyHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\LogmaticHandler' => 
  array (
    'type' => 'class',
    'classname' => 'LogmaticHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\LogmaticHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\MailHandler' => 
  array (
    'type' => 'class',
    'classname' => 'MailHandler',
    'isabstract' => true,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\MailHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\MandrillHandler' => 
  array (
    'type' => 'class',
    'classname' => 'MandrillHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\MandrillHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\MissingExtensionException' => 
  array (
    'type' => 'class',
    'classname' => 'MissingExtensionException',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\MissingExtensionException',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\MongoDBHandler' => 
  array (
    'type' => 'class',
    'classname' => 'MongoDBHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\MongoDBHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\NativeMailerHandler' => 
  array (
    'type' => 'class',
    'classname' => 'NativeMailerHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\NativeMailerHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\NewRelicHandler' => 
  array (
    'type' => 'class',
    'classname' => 'NewRelicHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\NewRelicHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\NoopHandler' => 
  array (
    'type' => 'class',
    'classname' => 'NoopHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\NoopHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\NullHandler' => 
  array (
    'type' => 'class',
    'classname' => 'NullHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\NullHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\OverflowHandler' => 
  array (
    'type' => 'class',
    'classname' => 'OverflowHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\OverflowHandler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\FormattableHandlerInterface',
    ),
  ),
  'Monolog\\Handler\\PHPConsoleHandler' => 
  array (
    'type' => 'class',
    'classname' => 'PHPConsoleHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\PHPConsoleHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\ProcessHandler' => 
  array (
    'type' => 'class',
    'classname' => 'ProcessHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\ProcessHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\PsrHandler' => 
  array (
    'type' => 'class',
    'classname' => 'PsrHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\PsrHandler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\FormattableHandlerInterface',
    ),
  ),
  'Monolog\\Handler\\PushoverHandler' => 
  array (
    'type' => 'class',
    'classname' => 'PushoverHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\PushoverHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\RedisHandler' => 
  array (
    'type' => 'class',
    'classname' => 'RedisHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\RedisHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\RedisPubSubHandler' => 
  array (
    'type' => 'class',
    'classname' => 'RedisPubSubHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\RedisPubSubHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\RollbarHandler' => 
  array (
    'type' => 'class',
    'classname' => 'RollbarHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\RollbarHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\RotatingFileHandler' => 
  array (
    'type' => 'class',
    'classname' => 'RotatingFileHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\RotatingFileHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SamplingHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SamplingHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SamplingHandler',
    'implements' => 
    array (
      0 => 'Monolog\\Handler\\ProcessableHandlerInterface',
      1 => 'Monolog\\Handler\\FormattableHandlerInterface',
    ),
  ),
  'Monolog\\Handler\\SendGridHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SendGridHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SendGridHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\Slack\\SlackRecord' => 
  array (
    'type' => 'class',
    'classname' => 'SlackRecord',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler\\Slack',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\Slack\\SlackRecord',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SlackHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SlackHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SlackHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SlackWebhookHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SlackWebhookHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SlackWebhookHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SocketHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SocketHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SocketHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SqsHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SqsHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SqsHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\StreamHandler' => 
  array (
    'type' => 'class',
    'classname' => 'StreamHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\StreamHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SwiftMailerHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SwiftMailerHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SwiftMailerHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SymfonyMailerHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SymfonyMailerHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SymfonyMailerHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SyslogHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SyslogHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SyslogHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SyslogUdp\\UdpSocket' => 
  array (
    'type' => 'class',
    'classname' => 'UdpSocket',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler\\SyslogUdp',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SyslogUdp\\UdpSocket',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\SyslogUdpHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SyslogUdpHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\SyslogUdpHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\TelegramBotHandler' => 
  array (
    'type' => 'class',
    'classname' => 'TelegramBotHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\TelegramBotHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\TestHandler' => 
  array (
    'type' => 'class',
    'classname' => 'TestHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\TestHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\WhatFailureGroupHandler' => 
  array (
    'type' => 'class',
    'classname' => 'WhatFailureGroupHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\WhatFailureGroupHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\ZendMonitorHandler' => 
  array (
    'type' => 'class',
    'classname' => 'ZendMonitorHandler',
    'isabstract' => false,
    'namespace' => 'Monolog\\Handler',
    'extends' => 'SolidWP\\Performance\\Monolog\\Handler\\ZendMonitorHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Logger' => 
  array (
    'type' => 'class',
    'classname' => 'Logger',
    'isabstract' => false,
    'namespace' => 'Monolog',
    'extends' => 'SolidWP\\Performance\\Monolog\\Logger',
    'implements' => 
    array (
      0 => 'Psr\\Log\\LoggerInterface',
      1 => 'Monolog\\ResettableInterface',
    ),
  ),
  'Monolog\\Processor\\GitProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'GitProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\GitProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Processor\\HostnameProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'HostnameProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\HostnameProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Processor\\IntrospectionProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'IntrospectionProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\IntrospectionProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Processor\\MemoryPeakUsageProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'MemoryPeakUsageProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\MemoryPeakUsageProcessor',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Processor\\MemoryProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'MemoryProcessor',
    'isabstract' => true,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\MemoryProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Processor\\MemoryUsageProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'MemoryUsageProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\MemoryUsageProcessor',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Processor\\MercurialProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'MercurialProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\MercurialProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Processor\\ProcessIdProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'ProcessIdProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\ProcessIdProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Processor\\PsrLogMessageProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'PsrLogMessageProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\PsrLogMessageProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Processor\\TagProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'TagProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\TagProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Processor\\UidProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'UidProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\UidProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
      1 => 'Monolog\\ResettableInterface',
    ),
  ),
  'Monolog\\Processor\\WebProcessor' => 
  array (
    'type' => 'class',
    'classname' => 'WebProcessor',
    'isabstract' => false,
    'namespace' => 'Monolog\\Processor',
    'extends' => 'SolidWP\\Performance\\Monolog\\Processor\\WebProcessor',
    'implements' => 
    array (
      0 => 'Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\Registry' => 
  array (
    'type' => 'class',
    'classname' => 'Registry',
    'isabstract' => false,
    'namespace' => 'Monolog',
    'extends' => 'SolidWP\\Performance\\Monolog\\Registry',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\SignalHandler' => 
  array (
    'type' => 'class',
    'classname' => 'SignalHandler',
    'isabstract' => false,
    'namespace' => 'Monolog',
    'extends' => 'SolidWP\\Performance\\Monolog\\SignalHandler',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Test\\TestCase' => 
  array (
    'type' => 'class',
    'classname' => 'TestCase',
    'isabstract' => false,
    'namespace' => 'Monolog\\Test',
    'extends' => 'SolidWP\\Performance\\Monolog\\Test\\TestCase',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Utils' => 
  array (
    'type' => 'class',
    'classname' => 'Utils',
    'isabstract' => false,
    'namespace' => 'Monolog',
    'extends' => 'SolidWP\\Performance\\Monolog\\Utils',
    'implements' => 
    array (
    ),
  ),
  'Niladam\\Uri\\Uri' => 
  array (
    'type' => 'class',
    'classname' => 'Uri',
    'isabstract' => false,
    'namespace' => 'Niladam\\Uri',
    'extends' => 'SolidWP\\Performance\\Niladam\\Uri\\Uri',
    'implements' => 
    array (
      0 => 'Niladam\\Uri\\Concerns\\Stringable',
      1 => 'Niladam\\Uri\\Concerns\\Arrayable',
      2 => 'Niladam\\Uri\\Concerns\\Htmlable',
    ),
  ),
  'Niladam\\Uri\\UriQuery' => 
  array (
    'type' => 'class',
    'classname' => 'UriQuery',
    'isabstract' => false,
    'namespace' => 'Niladam\\Uri',
    'extends' => 'SolidWP\\Performance\\Niladam\\Uri\\UriQuery',
    'implements' => 
    array (
    ),
  ),
  'Niladam\\Uri\\Tests\\UriTest' => 
  array (
    'type' => 'class',
    'classname' => 'UriTest',
    'isabstract' => false,
    'namespace' => 'Niladam\\Uri\\Tests',
    'extends' => 'SolidWP\\Performance\\Niladam\\Uri\\Tests\\UriTest',
    'implements' => 
    array (
    ),
  ),
  'Psr\\Log\\AbstractLogger' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractLogger',
    'isabstract' => true,
    'namespace' => 'Psr\\Log',
    'extends' => 'SolidWP\\Performance\\Psr\\Log\\AbstractLogger',
    'implements' => 
    array (
      0 => 'Psr\\Log\\LoggerInterface',
    ),
  ),
  'Psr\\Log\\InvalidArgumentException' => 
  array (
    'type' => 'class',
    'classname' => 'InvalidArgumentException',
    'isabstract' => false,
    'namespace' => 'Psr\\Log',
    'extends' => 'SolidWP\\Performance\\Psr\\Log\\InvalidArgumentException',
    'implements' => 
    array (
    ),
  ),
  'Psr\\Log\\LogLevel' => 
  array (
    'type' => 'class',
    'classname' => 'LogLevel',
    'isabstract' => false,
    'namespace' => 'Psr\\Log',
    'extends' => 'SolidWP\\Performance\\Psr\\Log\\LogLevel',
    'implements' => 
    array (
    ),
  ),
  'Psr\\Log\\NullLogger' => 
  array (
    'type' => 'class',
    'classname' => 'NullLogger',
    'isabstract' => false,
    'namespace' => 'Psr\\Log',
    'extends' => 'SolidWP\\Performance\\Psr\\Log\\NullLogger',
    'implements' => 
    array (
    ),
  ),
  'Psr\\Log\\Test\\DummyTest' => 
  array (
    'type' => 'class',
    'classname' => 'DummyTest',
    'isabstract' => false,
    'namespace' => 'Psr\\Log\\Test',
    'extends' => 'SolidWP\\Performance\\Psr\\Log\\Test\\DummyTest',
    'implements' => 
    array (
    ),
  ),
  'Psr\\Log\\Test\\LoggerInterfaceTest' => 
  array (
    'type' => 'class',
    'classname' => 'LoggerInterfaceTest',
    'isabstract' => true,
    'namespace' => 'Psr\\Log\\Test',
    'extends' => 'SolidWP\\Performance\\Psr\\Log\\Test\\LoggerInterfaceTest',
    'implements' => 
    array (
    ),
  ),
  'Psr\\Log\\Test\\TestLogger' => 
  array (
    'type' => 'class',
    'classname' => 'TestLogger',
    'isabstract' => false,
    'namespace' => 'Psr\\Log\\Test',
    'extends' => 'SolidWP\\Performance\\Psr\\Log\\Test\\TestLogger',
    'implements' => 
    array (
    ),
  ),
  'Rogervila\\ArrayDiffMultidimensional' => 
  array (
    'type' => 'class',
    'classname' => 'ArrayDiffMultidimensional',
    'isabstract' => false,
    'namespace' => 'Rogervila',
    'extends' => 'SolidWP\\Performance\\Rogervila\\ArrayDiffMultidimensional',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Arrays\\Arr' => 
  array (
    'type' => 'class',
    'classname' => 'Arr',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Arrays',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Arrays\\Arr',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\Config' => 
  array (
    'type' => 'class',
    'classname' => 'Config',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\Config',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\DB' => 
  array (
    'type' => 'class',
    'classname' => 'DB',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\DB',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\Database\\Actions\\EnableBigSqlSelects' => 
  array (
    'type' => 'class',
    'classname' => 'EnableBigSqlSelects',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\Database\\Actions',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\Database\\Actions\\EnableBigSqlSelects',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\Database\\Exceptions\\DatabaseQueryException' => 
  array (
    'type' => 'class',
    'classname' => 'DatabaseQueryException',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\Database\\Exceptions',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\Database\\Exceptions\\DatabaseQueryException',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\Database\\Provider' => 
  array (
    'type' => 'class',
    'classname' => 'Provider',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\Database',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\Database\\Provider',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\From' => 
  array (
    'type' => 'class',
    'classname' => 'From',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\From',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\Having' => 
  array (
    'type' => 'class',
    'classname' => 'Having',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\Having',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\Join' => 
  array (
    'type' => 'class',
    'classname' => 'Join',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\Join',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\JoinCondition' => 
  array (
    'type' => 'class',
    'classname' => 'JoinCondition',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\JoinCondition',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\MetaTable' => 
  array (
    'type' => 'class',
    'classname' => 'MetaTable',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\MetaTable',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\OrderBy' => 
  array (
    'type' => 'class',
    'classname' => 'OrderBy',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\OrderBy',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\RawSQL' => 
  array (
    'type' => 'class',
    'classname' => 'RawSQL',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\RawSQL',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\Select' => 
  array (
    'type' => 'class',
    'classname' => 'Select',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\Select',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\Union' => 
  array (
    'type' => 'class',
    'classname' => 'Union',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\Union',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Clauses\\Where' => 
  array (
    'type' => 'class',
    'classname' => 'Where',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Clauses',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Clauses\\Where',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\JoinQueryBuilder' => 
  array (
    'type' => 'class',
    'classname' => 'JoinQueryBuilder',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\JoinQueryBuilder',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\QueryBuilder' => 
  array (
    'type' => 'class',
    'classname' => 'QueryBuilder',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\QueryBuilder',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Types\\JoinType' => 
  array (
    'type' => 'class',
    'classname' => 'JoinType',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Types',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Types\\JoinType',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Types\\Math' => 
  array (
    'type' => 'class',
    'classname' => 'Math',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Types',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Types\\Math',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Types\\Operator' => 
  array (
    'type' => 'class',
    'classname' => 'Operator',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Types',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Types\\Operator',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Types\\Type' => 
  array (
    'type' => 'class',
    'classname' => 'Type',
    'isabstract' => true,
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Types',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Types\\Type',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\WhereQueryBuilder' => 
  array (
    'type' => 'class',
    'classname' => 'WhereQueryBuilder',
    'isabstract' => false,
    'namespace' => 'StellarWP\\DB\\QueryBuilder',
    'extends' => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\WhereQueryBuilder',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Memoize\\Drivers\\MemoryDriver' => 
  array (
    'type' => 'class',
    'classname' => 'MemoryDriver',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Memoize\\Drivers',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Memoize\\Drivers\\MemoryDriver',
    'implements' => 
    array (
      0 => 'StellarWP\\Memoize\\Contracts\\DriverInterface',
    ),
  ),
  'StellarWP\\Memoize\\Memoizer' => 
  array (
    'type' => 'class',
    'classname' => 'Memoizer',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Memoize',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Memoize\\Memoizer',
    'implements' => 
    array (
      0 => 'StellarWP\\Memoize\\Contracts\\MemoizerInterface',
    ),
  ),
  'StellarWP\\Pipeline\\Pipeline' => 
  array (
    'type' => 'class',
    'classname' => 'Pipeline',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Pipeline',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Pipeline\\Pipeline',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Schema\\Activation' => 
  array (
    'type' => 'class',
    'classname' => 'Activation',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Activation',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Schema\\Builder' => 
  array (
    'type' => 'class',
    'classname' => 'Builder',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Builder',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Schema\\Config' => 
  array (
    'type' => 'class',
    'classname' => 'Config',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Config',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Schema\\Fields\\Collection' => 
  array (
    'type' => 'class',
    'classname' => 'Collection',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema\\Fields',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Fields\\Collection',
    'implements' => 
    array (
      0 => 'ArrayAccess',
      1 => 'Countable',
      2 => 'Iterator',
    ),
  ),
  'StellarWP\\Schema\\Fields\\Contracts\\Field' => 
  array (
    'type' => 'class',
    'classname' => 'Field',
    'isabstract' => true,
    'namespace' => 'StellarWP\\Schema\\Fields\\Contracts',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Fields\\Contracts\\Field',
    'implements' => 
    array (
      0 => 'StellarWP\\Schema\\Fields\\Contracts\\Schema_Interface',
    ),
  ),
  'StellarWP\\Schema\\Fields\\Filters\\Table_FilterIterator' => 
  array (
    'type' => 'class',
    'classname' => 'Table_FilterIterator',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema\\Fields\\Filters',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Fields\\Filters\\Table_FilterIterator',
    'implements' => 
    array (
      0 => 'Countable',
    ),
  ),
  'StellarWP\\Schema\\Full_Activation_Provider' => 
  array (
    'type' => 'class',
    'classname' => 'Full_Activation_Provider',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Full_Activation_Provider',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Schema\\Register' => 
  array (
    'type' => 'class',
    'classname' => 'Register',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Register',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Schema\\Schema' => 
  array (
    'type' => 'class',
    'classname' => 'Schema',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Schema',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Schema\\Tables\\Collection' => 
  array (
    'type' => 'class',
    'classname' => 'Collection',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema\\Tables',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Tables\\Collection',
    'implements' => 
    array (
      0 => 'ArrayAccess',
      1 => 'Countable',
      2 => 'Iterator',
    ),
  ),
  'StellarWP\\Schema\\Tables\\Contracts\\Table' => 
  array (
    'type' => 'class',
    'classname' => 'Table',
    'isabstract' => true,
    'namespace' => 'StellarWP\\Schema\\Tables\\Contracts',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Tables\\Contracts\\Table',
    'implements' => 
    array (
      0 => 'StellarWP\\Schema\\Tables\\Contracts\\Schema_Interface',
    ),
  ),
  'StellarWP\\Schema\\Tables\\Filters\\Group_FilterIterator' => 
  array (
    'type' => 'class',
    'classname' => 'Group_FilterIterator',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema\\Tables\\Filters',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Tables\\Filters\\Group_FilterIterator',
    'implements' => 
    array (
      0 => 'Countable',
    ),
  ),
  'StellarWP\\Schema\\Tables\\Filters\\Needs_Update_FilterIterator' => 
  array (
    'type' => 'class',
    'classname' => 'Needs_Update_FilterIterator',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Schema\\Tables\\Filters',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Schema\\Tables\\Filters\\Needs_Update_FilterIterator',
    'implements' => 
    array (
      0 => 'Countable',
    ),
  ),
  'StellarWP\\SuperGlobals\\SuperGlobals' => 
  array (
    'type' => 'class',
    'classname' => 'SuperGlobals',
    'isabstract' => false,
    'namespace' => 'StellarWP\\SuperGlobals',
    'extends' => 'SolidWP\\Performance\\StellarWP\\SuperGlobals\\SuperGlobals',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Admin\\Admin_Subscriber' => 
  array (
    'type' => 'class',
    'classname' => 'Admin_Subscriber',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Admin',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Admin\\Admin_Subscriber',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Admin\\Resources' => 
  array (
    'type' => 'class',
    'classname' => 'Resources',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Admin',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Admin\\Resources',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Config' => 
  array (
    'type' => 'class',
    'classname' => 'Config',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Config',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Contracts\\Abstract_Subscriber' => 
  array (
    'type' => 'class',
    'classname' => 'Abstract_Subscriber',
    'isabstract' => true,
    'namespace' => 'StellarWP\\Telemetry\\Contracts',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Contracts\\Abstract_Subscriber',
    'implements' => 
    array (
      0 => 'StellarWP\\Telemetry\\Contracts\\Subscriber_Interface',
    ),
  ),
  'StellarWP\\Telemetry\\Core' => 
  array (
    'type' => 'class',
    'classname' => 'Core',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Core',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Data_Providers\\Debug_Data' => 
  array (
    'type' => 'class',
    'classname' => 'Debug_Data',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Data_Providers',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Data_Providers\\Debug_Data',
    'implements' => 
    array (
      0 => 'StellarWP\\Telemetry\\Contracts\\Data_Provider',
    ),
  ),
  'StellarWP\\Telemetry\\Data_Providers\\Null_Data_Provider' => 
  array (
    'type' => 'class',
    'classname' => 'Null_Data_Provider',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Data_Providers',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Data_Providers\\Null_Data_Provider',
    'implements' => 
    array (
      0 => 'StellarWP\\Telemetry\\Contracts\\Data_Provider',
    ),
  ),
  'StellarWP\\Telemetry\\Events\\Event' => 
  array (
    'type' => 'class',
    'classname' => 'Event',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Events',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Events\\Event',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Events\\Event_Subscriber' => 
  array (
    'type' => 'class',
    'classname' => 'Event_Subscriber',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Events',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Events\\Event_Subscriber',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Exit_Interview\\Exit_Interview_Subscriber' => 
  array (
    'type' => 'class',
    'classname' => 'Exit_Interview_Subscriber',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Exit_Interview',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Exit_Interview\\Exit_Interview_Subscriber',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Exit_Interview\\Template' => 
  array (
    'type' => 'class',
    'classname' => 'Template',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Exit_Interview',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Exit_Interview\\Template',
    'implements' => 
    array (
      0 => 'StellarWP\\Telemetry\\Contracts\\Template_Interface',
    ),
  ),
  'StellarWP\\Telemetry\\Last_Send\\Last_Send' => 
  array (
    'type' => 'class',
    'classname' => 'Last_Send',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Last_Send',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Last_Send\\Last_Send',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Last_Send\\Last_Send_Subscriber' => 
  array (
    'type' => 'class',
    'classname' => 'Last_Send_Subscriber',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Last_Send',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Last_Send\\Last_Send_Subscriber',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Opt_In\\Opt_In_Subscriber' => 
  array (
    'type' => 'class',
    'classname' => 'Opt_In_Subscriber',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Opt_In',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Opt_In\\Opt_In_Subscriber',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Opt_In\\Opt_In_Template' => 
  array (
    'type' => 'class',
    'classname' => 'Opt_In_Template',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Opt_In',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Opt_In\\Opt_In_Template',
    'implements' => 
    array (
      0 => 'StellarWP\\Telemetry\\Contracts\\Template_Interface',
    ),
  ),
  'StellarWP\\Telemetry\\Opt_In\\Status' => 
  array (
    'type' => 'class',
    'classname' => 'Status',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Opt_In',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Opt_In\\Status',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Telemetry\\Telemetry' => 
  array (
    'type' => 'class',
    'classname' => 'Telemetry',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Telemetry',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Telemetry\\Telemetry',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Telemetry\\Telemetry_Subscriber' => 
  array (
    'type' => 'class',
    'classname' => 'Telemetry_Subscriber',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry\\Telemetry',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Telemetry\\Telemetry_Subscriber',
    'implements' => 
    array (
    ),
  ),
  'StellarWP\\Telemetry\\Uninstall' => 
  array (
    'type' => 'class',
    'classname' => 'Uninstall',
    'isabstract' => false,
    'namespace' => 'StellarWP\\Telemetry',
    'extends' => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Uninstall',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\CssSelectorConverter' => 
  array (
    'type' => 'class',
    'classname' => 'CssSelectorConverter',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\CssSelectorConverter',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Exception\\ExpressionErrorException' => 
  array (
    'type' => 'class',
    'classname' => 'ExpressionErrorException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Exception\\ExpressionErrorException',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Exception\\InternalErrorException' => 
  array (
    'type' => 'class',
    'classname' => 'InternalErrorException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Exception\\InternalErrorException',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Exception\\ParseException' => 
  array (
    'type' => 'class',
    'classname' => 'ParseException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Exception\\ParseException',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Exception\\ExceptionInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Exception\\SyntaxErrorException' => 
  array (
    'type' => 'class',
    'classname' => 'SyntaxErrorException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Exception\\SyntaxErrorException',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\AbstractNode' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractNode',
    'isabstract' => true,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\AbstractNode',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Node\\NodeInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\AttributeNode' => 
  array (
    'type' => 'class',
    'classname' => 'AttributeNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\AttributeNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\ClassNode' => 
  array (
    'type' => 'class',
    'classname' => 'ClassNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\ClassNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\CombinedSelectorNode' => 
  array (
    'type' => 'class',
    'classname' => 'CombinedSelectorNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\CombinedSelectorNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\ElementNode' => 
  array (
    'type' => 'class',
    'classname' => 'ElementNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\ElementNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\FunctionNode' => 
  array (
    'type' => 'class',
    'classname' => 'FunctionNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\FunctionNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\HashNode' => 
  array (
    'type' => 'class',
    'classname' => 'HashNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\HashNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\NegationNode' => 
  array (
    'type' => 'class',
    'classname' => 'NegationNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\NegationNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\PseudoNode' => 
  array (
    'type' => 'class',
    'classname' => 'PseudoNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\PseudoNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\SelectorNode' => 
  array (
    'type' => 'class',
    'classname' => 'SelectorNode',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\SelectorNode',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\Specificity' => 
  array (
    'type' => 'class',
    'classname' => 'Specificity',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\Specificity',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Handler\\CommentHandler' => 
  array (
    'type' => 'class',
    'classname' => 'CommentHandler',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Handler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Handler\\CommentHandler',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Handler\\HashHandler' => 
  array (
    'type' => 'class',
    'classname' => 'HashHandler',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Handler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Handler\\HashHandler',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Handler\\IdentifierHandler' => 
  array (
    'type' => 'class',
    'classname' => 'IdentifierHandler',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Handler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Handler\\IdentifierHandler',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Handler\\NumberHandler' => 
  array (
    'type' => 'class',
    'classname' => 'NumberHandler',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Handler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Handler\\NumberHandler',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Handler\\StringHandler' => 
  array (
    'type' => 'class',
    'classname' => 'StringHandler',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Handler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Handler\\StringHandler',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Handler\\WhitespaceHandler' => 
  array (
    'type' => 'class',
    'classname' => 'WhitespaceHandler',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Handler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Handler\\WhitespaceHandler',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Parser' => 
  array (
    'type' => 'class',
    'classname' => 'Parser',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Parser',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\ParserInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Reader' => 
  array (
    'type' => 'class',
    'classname' => 'Reader',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Reader',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Shortcut\\ClassParser' => 
  array (
    'type' => 'class',
    'classname' => 'ClassParser',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Shortcut',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Shortcut\\ClassParser',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\ParserInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Shortcut\\ElementParser' => 
  array (
    'type' => 'class',
    'classname' => 'ElementParser',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Shortcut',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Shortcut\\ElementParser',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\ParserInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Shortcut\\EmptyStringParser' => 
  array (
    'type' => 'class',
    'classname' => 'EmptyStringParser',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Shortcut',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Shortcut\\EmptyStringParser',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\ParserInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Shortcut\\HashParser' => 
  array (
    'type' => 'class',
    'classname' => 'HashParser',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Shortcut',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Shortcut\\HashParser',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\Parser\\ParserInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Token' => 
  array (
    'type' => 'class',
    'classname' => 'Token',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Token',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\TokenStream' => 
  array (
    'type' => 'class',
    'classname' => 'TokenStream',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\TokenStream',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\Tokenizer' => 
  array (
    'type' => 'class',
    'classname' => 'Tokenizer',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Tokenizer',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\Tokenizer',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\TokenizerEscaping' => 
  array (
    'type' => 'class',
    'classname' => 'TokenizerEscaping',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Tokenizer',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\TokenizerEscaping',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\TokenizerPatterns' => 
  array (
    'type' => 'class',
    'classname' => 'TokenizerPatterns',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Tokenizer',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Tokenizer\\TokenizerPatterns',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Extension\\AbstractExtension' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractExtension',
    'isabstract' => true,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath\\Extension',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Extension\\AbstractExtension',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\XPath\\Extension\\ExtensionInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Extension\\AttributeMatchingExtension' => 
  array (
    'type' => 'class',
    'classname' => 'AttributeMatchingExtension',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath\\Extension',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Extension\\AttributeMatchingExtension',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Extension\\CombinationExtension' => 
  array (
    'type' => 'class',
    'classname' => 'CombinationExtension',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath\\Extension',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Extension\\CombinationExtension',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Extension\\FunctionExtension' => 
  array (
    'type' => 'class',
    'classname' => 'FunctionExtension',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath\\Extension',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Extension\\FunctionExtension',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Extension\\HtmlExtension' => 
  array (
    'type' => 'class',
    'classname' => 'HtmlExtension',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath\\Extension',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Extension\\HtmlExtension',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Extension\\NodeExtension' => 
  array (
    'type' => 'class',
    'classname' => 'NodeExtension',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath\\Extension',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Extension\\NodeExtension',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Extension\\PseudoClassExtension' => 
  array (
    'type' => 'class',
    'classname' => 'PseudoClassExtension',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath\\Extension',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Extension\\PseudoClassExtension',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Translator' => 
  array (
    'type' => 'class',
    'classname' => 'Translator',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Translator',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\CssSelector\\XPath\\TranslatorInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\XPathExpr' => 
  array (
    'type' => 'class',
    'classname' => 'XPathExpr',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\XPathExpr',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\AbstractUriElement' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractUriElement',
    'isabstract' => true,
    'namespace' => 'Symfony\\Component\\DomCrawler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\AbstractUriElement',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Crawler' => 
  array (
    'type' => 'class',
    'classname' => 'Crawler',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Crawler',
    'implements' => 
    array (
      0 => 'Countable',
      1 => 'IteratorAggregate',
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Field\\ChoiceFormField' => 
  array (
    'type' => 'class',
    'classname' => 'ChoiceFormField',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Field',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Field\\ChoiceFormField',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Field\\FileFormField' => 
  array (
    'type' => 'class',
    'classname' => 'FileFormField',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Field',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Field\\FileFormField',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Field\\FormField' => 
  array (
    'type' => 'class',
    'classname' => 'FormField',
    'isabstract' => true,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Field',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Field\\FormField',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Field\\InputFormField' => 
  array (
    'type' => 'class',
    'classname' => 'InputFormField',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Field',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Field\\InputFormField',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Field\\TextareaFormField' => 
  array (
    'type' => 'class',
    'classname' => 'TextareaFormField',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Field',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Field\\TextareaFormField',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Form' => 
  array (
    'type' => 'class',
    'classname' => 'Form',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Form',
    'implements' => 
    array (
      0 => 'ArrayAccess',
    ),
  ),
  'Symfony\\Component\\DomCrawler\\FormFieldRegistry' => 
  array (
    'type' => 'class',
    'classname' => 'FormFieldRegistry',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\FormFieldRegistry',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Image' => 
  array (
    'type' => 'class',
    'classname' => 'Image',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Image',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Link' => 
  array (
    'type' => 'class',
    'classname' => 'Link',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Link',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Test\\Constraint\\CrawlerSelectorAttributeValueSame' => 
  array (
    'type' => 'class',
    'classname' => 'CrawlerSelectorAttributeValueSame',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Test\\Constraint',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Test\\Constraint\\CrawlerSelectorAttributeValueSame',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Test\\Constraint\\CrawlerSelectorExists' => 
  array (
    'type' => 'class',
    'classname' => 'CrawlerSelectorExists',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Test\\Constraint',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Test\\Constraint\\CrawlerSelectorExists',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Test\\Constraint\\CrawlerSelectorTextContains' => 
  array (
    'type' => 'class',
    'classname' => 'CrawlerSelectorTextContains',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Test\\Constraint',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Test\\Constraint\\CrawlerSelectorTextContains',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\Test\\Constraint\\CrawlerSelectorTextSame' => 
  array (
    'type' => 'class',
    'classname' => 'CrawlerSelectorTextSame',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler\\Test\\Constraint',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\Test\\Constraint\\CrawlerSelectorTextSame',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\DomCrawler\\UriResolver' => 
  array (
    'type' => 'class',
    'classname' => 'UriResolver',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\DomCrawler',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\DomCrawler\\UriResolver',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\Filesystem\\Exception\\FileNotFoundException' => 
  array (
    'type' => 'class',
    'classname' => 'FileNotFoundException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\Filesystem\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\Filesystem\\Exception\\FileNotFoundException',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\Filesystem\\Exception\\IOException' => 
  array (
    'type' => 'class',
    'classname' => 'IOException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\Filesystem\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\Filesystem\\Exception\\IOException',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\Filesystem\\Exception\\IOExceptionInterface',
    ),
  ),
  'Symfony\\Component\\Filesystem\\Exception\\InvalidArgumentException' => 
  array (
    'type' => 'class',
    'classname' => 'InvalidArgumentException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\Filesystem\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\Filesystem\\Exception\\InvalidArgumentException',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\Filesystem\\Exception\\ExceptionInterface',
    ),
  ),
  'Symfony\\Component\\Filesystem\\Exception\\RuntimeException' => 
  array (
    'type' => 'class',
    'classname' => 'RuntimeException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\Filesystem\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\Filesystem\\Exception\\RuntimeException',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\Filesystem\\Exception\\ExceptionInterface',
    ),
  ),
  'Symfony\\Component\\Filesystem\\Filesystem' => 
  array (
    'type' => 'class',
    'classname' => 'Filesystem',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\Filesystem',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\Filesystem\\Filesystem',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\Filesystem\\Path' => 
  array (
    'type' => 'class',
    'classname' => 'Path',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\Filesystem',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\Filesystem\\Path',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Test\\HttpClientTestCase' => 
  array (
    'type' => 'class',
    'classname' => 'HttpClientTestCase',
    'isabstract' => true,
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Test',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Test\\HttpClientTestCase',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Test\\TestHttpServer' => 
  array (
    'type' => 'class',
    'classname' => 'TestHttpServer',
    'isabstract' => false,
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Test',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Test\\TestHttpServer',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\AmpHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'AmpHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\AmpHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Psr\\Log\\LoggerAwareInterface',
      2 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\CachingHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'CachingHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\CachingHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Chunk\\DataChunk' => 
  array (
    'type' => 'class',
    'classname' => 'DataChunk',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Chunk',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Chunk\\DataChunk',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ChunkInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Chunk\\ErrorChunk' => 
  array (
    'type' => 'class',
    'classname' => 'ErrorChunk',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Chunk',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Chunk\\ErrorChunk',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ChunkInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Chunk\\FirstChunk' => 
  array (
    'type' => 'class',
    'classname' => 'FirstChunk',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Chunk',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Chunk\\FirstChunk',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Chunk\\InformationalChunk' => 
  array (
    'type' => 'class',
    'classname' => 'InformationalChunk',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Chunk',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Chunk\\InformationalChunk',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Chunk\\LastChunk' => 
  array (
    'type' => 'class',
    'classname' => 'LastChunk',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Chunk',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Chunk\\LastChunk',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Chunk\\ServerSentEvent' => 
  array (
    'type' => 'class',
    'classname' => 'ServerSentEvent',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Chunk',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Chunk\\ServerSentEvent',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ChunkInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\CurlHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'CurlHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\CurlHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Psr\\Log\\LoggerAwareInterface',
      2 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\DataCollector\\HttpClientDataCollector' => 
  array (
    'type' => 'class',
    'classname' => 'HttpClientDataCollector',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\DataCollector',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\DataCollector\\HttpClientDataCollector',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\HttpKernel\\DataCollector\\LateDataCollectorInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\DependencyInjection\\HttpClientPass' => 
  array (
    'type' => 'class',
    'classname' => 'HttpClientPass',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\DependencyInjection',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\DependencyInjection\\HttpClientPass',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\DependencyInjection\\Compiler\\CompilerPassInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\EventSourceHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'EventSourceHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\EventSourceHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\066ffd9601faf9e2d9768b54d07131e9' => 
  array (
    'type' => 'class',
    'classname' => '066ffd9601faf9e2d9768b54d07131e9',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\066ffd9601faf9e2d9768b54d07131e9',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\ClientException' => 
  array (
    'type' => 'class',
    'classname' => 'ClientException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\ClientException',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\Exception\\ClientExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\EventSourceException' => 
  array (
    'type' => 'class',
    'classname' => 'EventSourceException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\EventSourceException',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\Exception\\DecodingExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\InvalidArgumentException' => 
  array (
    'type' => 'class',
    'classname' => 'InvalidArgumentException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\InvalidArgumentException',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\Exception\\TransportExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\JsonException' => 
  array (
    'type' => 'class',
    'classname' => 'JsonException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\JsonException',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\Exception\\DecodingExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\RedirectionException' => 
  array (
    'type' => 'class',
    'classname' => 'RedirectionException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\RedirectionException',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\Exception\\RedirectionExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\ServerException' => 
  array (
    'type' => 'class',
    'classname' => 'ServerException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\ServerException',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\Exception\\ServerExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\TimeoutException' => 
  array (
    'type' => 'class',
    'classname' => 'TimeoutException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\TimeoutException',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\Exception\\TimeoutExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\TransportException' => 
  array (
    'type' => 'class',
    'classname' => 'TransportException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\TransportException',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\Exception\\TransportExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\HttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'HttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\HttpClient',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\HttpOptions' => 
  array (
    'type' => 'class',
    'classname' => 'HttpOptions',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\HttpOptions',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\HttplugClient' => 
  array (
    'type' => 'class',
    'classname' => 'HttplugClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\HttplugClient',
    'implements' => 
    array (
      0 => 'Http\\Client\\HttpClient',
      1 => 'Http\\Client\\HttpAsyncClient',
      2 => 'Http\\Message\\RequestFactory',
      3 => 'Http\\Message\\StreamFactory',
      4 => 'Http\\Message\\UriFactory',
      5 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\AmpBody' => 
  array (
    'type' => 'class',
    'classname' => 'AmpBody',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\AmpBody',
    'implements' => 
    array (
      0 => 'Amp\\Http\\Client\\RequestBody',
      1 => 'Amp\\ByteStream\\InputStream',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\AmpClientState' => 
  array (
    'type' => 'class',
    'classname' => 'AmpClientState',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\AmpClientState',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\80a85602c774ce3b66e65bb1a90036b5' => 
  array (
    'type' => 'class',
    'classname' => '80a85602c774ce3b66e65bb1a90036b5',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\80a85602c774ce3b66e65bb1a90036b5',
    'implements' => 
    array (
      0 => 'Amp\\Socket\\Connector',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\AmpListener' => 
  array (
    'type' => 'class',
    'classname' => 'AmpListener',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\AmpListener',
    'implements' => 
    array (
      0 => 'Amp\\Http\\Client\\EventListener',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\AmpResolver' => 
  array (
    'type' => 'class',
    'classname' => 'AmpResolver',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\AmpResolver',
    'implements' => 
    array (
      0 => 'Amp\\Dns\\Resolver',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\Canary' => 
  array (
    'type' => 'class',
    'classname' => 'Canary',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\Canary',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\ClientState' => 
  array (
    'type' => 'class',
    'classname' => 'ClientState',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\ClientState',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\CurlClientState' => 
  array (
    'type' => 'class',
    'classname' => 'CurlClientState',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\CurlClientState',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\DnsCache' => 
  array (
    'type' => 'class',
    'classname' => 'DnsCache',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\DnsCache',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\HttplugWaitLoop' => 
  array (
    'type' => 'class',
    'classname' => 'HttplugWaitLoop',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\HttplugWaitLoop',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\NativeClientState' => 
  array (
    'type' => 'class',
    'classname' => 'NativeClientState',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\NativeClientState',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Internal\\PushedResponse' => 
  array (
    'type' => 'class',
    'classname' => 'PushedResponse',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Internal',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Internal\\PushedResponse',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\MockHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'MockHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\MockHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\NativeHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'NativeHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\NativeHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Psr\\Log\\LoggerAwareInterface',
      2 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\NoPrivateNetworkHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'NoPrivateNetworkHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\NoPrivateNetworkHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Psr\\Log\\LoggerAwareInterface',
      2 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Psr18Client' => 
  array (
    'type' => 'class',
    'classname' => 'Psr18Client',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Psr18Client',
    'implements' => 
    array (
      0 => 'Psr\\Http\\Client\\ClientInterface',
      1 => 'Psr\\Http\\Message\\RequestFactoryInterface',
      2 => 'Psr\\Http\\Message\\StreamFactoryInterface',
      3 => 'Psr\\Http\\Message\\UriFactoryInterface',
      4 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Psr18NetworkException' => 
  array (
    'type' => 'class',
    'classname' => 'Psr18NetworkException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Psr18NetworkException',
    'implements' => 
    array (
      0 => 'Psr\\Http\\Client\\NetworkExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Psr18RequestException' => 
  array (
    'type' => 'class',
    'classname' => 'Psr18RequestException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Psr18RequestException',
    'implements' => 
    array (
      0 => 'Psr\\Http\\Client\\RequestExceptionInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\AmpResponse' => 
  array (
    'type' => 'class',
    'classname' => 'AmpResponse',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\AmpResponse',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ResponseInterface',
      1 => 'Symfony\\Component\\HttpClient\\Response\\StreamableInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\4a7cddb82685cf63efeded6ea36ef204' => 
  array (
    'type' => 'class',
    'classname' => '4a7cddb82685cf63efeded6ea36ef204',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\4a7cddb82685cf63efeded6ea36ef204',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\AsyncContext' => 
  array (
    'type' => 'class',
    'classname' => 'AsyncContext',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\AsyncContext',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\AsyncResponse' => 
  array (
    'type' => 'class',
    'classname' => 'AsyncResponse',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\AsyncResponse',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ResponseInterface',
      1 => 'Symfony\\Component\\HttpClient\\Response\\StreamableInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\CurlResponse' => 
  array (
    'type' => 'class',
    'classname' => 'CurlResponse',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\CurlResponse',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ResponseInterface',
      1 => 'Symfony\\Component\\HttpClient\\Response\\StreamableInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\HttplugPromise' => 
  array (
    'type' => 'class',
    'classname' => 'HttplugPromise',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\HttplugPromise',
    'implements' => 
    array (
      0 => 'Http\\Promise\\Promise',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\MockResponse' => 
  array (
    'type' => 'class',
    'classname' => 'MockResponse',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\MockResponse',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ResponseInterface',
      1 => 'Symfony\\Component\\HttpClient\\Response\\StreamableInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\NativeResponse' => 
  array (
    'type' => 'class',
    'classname' => 'NativeResponse',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\NativeResponse',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ResponseInterface',
      1 => 'Symfony\\Component\\HttpClient\\Response\\StreamableInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\ResponseStream' => 
  array (
    'type' => 'class',
    'classname' => 'ResponseStream',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\ResponseStream',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ResponseStreamInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\StreamWrapper' => 
  array (
    'type' => 'class',
    'classname' => 'StreamWrapper',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\StreamWrapper',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\TraceableResponse' => 
  array (
    'type' => 'class',
    'classname' => 'TraceableResponse',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\TraceableResponse',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\ResponseInterface',
      1 => 'Symfony\\Component\\HttpClient\\Response\\StreamableInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Retry\\GenericRetryStrategy' => 
  array (
    'type' => 'class',
    'classname' => 'GenericRetryStrategy',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient\\Retry',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Retry\\GenericRetryStrategy',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\HttpClient\\Retry\\RetryStrategyInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\RetryableHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'RetryableHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\RetryableHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\ScopingHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'ScopingHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\ScopingHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Symfony\\Contracts\\Service\\ResetInterface',
      2 => 'Psr\\Log\\LoggerAwareInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\TraceableHttpClient' => 
  array (
    'type' => 'class',
    'classname' => 'TraceableHttpClient',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\HttpClient',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\TraceableHttpClient',
    'implements' => 
    array (
      0 => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
      1 => 'Symfony\\Contracts\\Service\\ResetInterface',
      2 => 'Psr\\Log\\LoggerAwareInterface',
    ),
  ),
  'Symfony\\Polyfill\\Ctype\\Ctype' => 
  array (
    'type' => 'class',
    'classname' => 'Ctype',
    'isabstract' => false,
    'namespace' => 'Symfony\\Polyfill\\Ctype',
    'extends' => 'SolidWP\\Performance\\Symfony\\Polyfill\\Ctype\\Ctype',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Polyfill\\Mbstring\\Mbstring' => 
  array (
    'type' => 'class',
    'classname' => 'Mbstring',
    'isabstract' => false,
    'namespace' => 'Symfony\\Polyfill\\Mbstring',
    'extends' => 'SolidWP\\Performance\\Symfony\\Polyfill\\Mbstring\\Mbstring',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Polyfill\\Php73\\Php73' => 
  array (
    'type' => 'class',
    'classname' => 'Php73',
    'isabstract' => false,
    'namespace' => 'Symfony\\Polyfill\\Php73',
    'extends' => 'SolidWP\\Performance\\Symfony\\Polyfill\\Php73\\Php73',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Polyfill\\Php80\\Php80' => 
  array (
    'type' => 'class',
    'classname' => 'Php80',
    'isabstract' => false,
    'namespace' => 'Symfony\\Polyfill\\Php80',
    'extends' => 'SolidWP\\Performance\\Symfony\\Polyfill\\Php80\\Php80',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Polyfill\\Php80\\PhpToken' => 
  array (
    'type' => 'class',
    'classname' => 'PhpToken',
    'isabstract' => false,
    'namespace' => 'Symfony\\Polyfill\\Php80',
    'extends' => 'SolidWP\\Performance\\Symfony\\Polyfill\\Php80\\PhpToken',
    'implements' => 
    array (
      0 => 'Stringable',
    ),
  ),
  'Symfony\\Polyfill\\Php81\\Php81' => 
  array (
    'type' => 'class',
    'classname' => 'Php81',
    'isabstract' => false,
    'namespace' => 'Symfony\\Polyfill\\Php81',
    'extends' => 'SolidWP\\Performance\\Symfony\\Polyfill\\Php81\\Php81',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Contracts\\Service\\Attribute\\Required' => 
  array (
    'type' => 'class',
    'classname' => 'Required',
    'isabstract' => false,
    'namespace' => 'Symfony\\Contracts\\Service\\Attribute',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\Attribute\\Required',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Contracts\\Service\\Attribute\\SubscribedService' => 
  array (
    'type' => 'class',
    'classname' => 'SubscribedService',
    'isabstract' => false,
    'namespace' => 'Symfony\\Contracts\\Service\\Attribute',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\Attribute\\SubscribedService',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Contracts\\Service\\82f7ae2fa649d884d3542cabdac6aad5' => 
  array (
    'type' => 'class',
    'classname' => '82f7ae2fa649d884d3542cabdac6aad5',
    'isabstract' => false,
    'namespace' => 'Symfony\\Contracts\\Service',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\82f7ae2fa649d884d3542cabdac6aad5',
    'implements' => 
    array (
      0 => 'Psr\\Container\\NotFoundExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\Service\\2b72fc1c716020e7851998537bf54ec4' => 
  array (
    'type' => 'class',
    'classname' => '2b72fc1c716020e7851998537bf54ec4',
    'isabstract' => false,
    'namespace' => 'Symfony\\Contracts\\Service',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\2b72fc1c716020e7851998537bf54ec4',
    'implements' => 
    array (
      0 => 'Psr\\Container\\ContainerExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\Service\\Test\\ServiceLocatorTest' => 
  array (
    'type' => 'class',
    'classname' => 'ServiceLocatorTest',
    'isabstract' => false,
    'namespace' => 'Symfony\\Contracts\\Service\\Test',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\Test\\ServiceLocatorTest',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Contracts\\Service\\Test\\ServiceLocatorTestCase' => 
  array (
    'type' => 'class',
    'classname' => 'ServiceLocatorTestCase',
    'isabstract' => true,
    'namespace' => 'Symfony\\Contracts\\Service\\Test',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\Test\\ServiceLocatorTestCase',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Contracts\\Service\\Test\\38f3c476734c2c002c1b3617c9c478ee' => 
  array (
    'type' => 'class',
    'classname' => '38f3c476734c2c002c1b3617c9c478ee',
    'isabstract' => false,
    'namespace' => 'Symfony\\Contracts\\Service\\Test',
    'extends' => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\Test\\38f3c476734c2c002c1b3617c9c478ee',
    'implements' => 
    array (
      0 => 'Psr\\Container\\ContainerInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\AmqpCaster' => 
  array (
    'type' => 'class',
    'classname' => 'AmqpCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\AmqpCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ArgsStub' => 
  array (
    'type' => 'class',
    'classname' => 'ArgsStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ArgsStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\Caster' => 
  array (
    'type' => 'class',
    'classname' => 'Caster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\Caster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ClassStub' => 
  array (
    'type' => 'class',
    'classname' => 'ClassStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ClassStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ConstStub' => 
  array (
    'type' => 'class',
    'classname' => 'ConstStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ConstStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\CutArrayStub' => 
  array (
    'type' => 'class',
    'classname' => 'CutArrayStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\CutArrayStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\CutStub' => 
  array (
    'type' => 'class',
    'classname' => 'CutStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\CutStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\DOMCaster' => 
  array (
    'type' => 'class',
    'classname' => 'DOMCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\DateCaster' => 
  array (
    'type' => 'class',
    'classname' => 'DateCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\DateCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\DoctrineCaster' => 
  array (
    'type' => 'class',
    'classname' => 'DoctrineCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\DoctrineCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\DsCaster' => 
  array (
    'type' => 'class',
    'classname' => 'DsCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\DsCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\DsPairStub' => 
  array (
    'type' => 'class',
    'classname' => 'DsPairStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\DsPairStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\EnumStub' => 
  array (
    'type' => 'class',
    'classname' => 'EnumStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\EnumStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster' => 
  array (
    'type' => 'class',
    'classname' => 'ExceptionCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\FiberCaster' => 
  array (
    'type' => 'class',
    'classname' => 'FiberCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\FiberCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\FrameStub' => 
  array (
    'type' => 'class',
    'classname' => 'FrameStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\FrameStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\GmpCaster' => 
  array (
    'type' => 'class',
    'classname' => 'GmpCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\GmpCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ImagineCaster' => 
  array (
    'type' => 'class',
    'classname' => 'ImagineCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ImagineCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ImgStub' => 
  array (
    'type' => 'class',
    'classname' => 'ImgStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ImgStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\IntlCaster' => 
  array (
    'type' => 'class',
    'classname' => 'IntlCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\IntlCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\LinkStub' => 
  array (
    'type' => 'class',
    'classname' => 'LinkStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\LinkStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\MemcachedCaster' => 
  array (
    'type' => 'class',
    'classname' => 'MemcachedCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\MemcachedCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\PdoCaster' => 
  array (
    'type' => 'class',
    'classname' => 'PdoCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\PdoCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\PgSqlCaster' => 
  array (
    'type' => 'class',
    'classname' => 'PgSqlCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\PgSqlCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ProxyManagerCaster' => 
  array (
    'type' => 'class',
    'classname' => 'ProxyManagerCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ProxyManagerCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster' => 
  array (
    'type' => 'class',
    'classname' => 'RdKafkaCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\RedisCaster' => 
  array (
    'type' => 'class',
    'classname' => 'RedisCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\RedisCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster' => 
  array (
    'type' => 'class',
    'classname' => 'ReflectionCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\ResourceCaster' => 
  array (
    'type' => 'class',
    'classname' => 'ResourceCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\SplCaster' => 
  array (
    'type' => 'class',
    'classname' => 'SplCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\SplCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\StubCaster' => 
  array (
    'type' => 'class',
    'classname' => 'StubCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\StubCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster' => 
  array (
    'type' => 'class',
    'classname' => 'SymfonyCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\TraceStub' => 
  array (
    'type' => 'class',
    'classname' => 'TraceStub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\TraceStub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\UuidCaster' => 
  array (
    'type' => 'class',
    'classname' => 'UuidCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\UuidCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\XmlReaderCaster' => 
  array (
    'type' => 'class',
    'classname' => 'XmlReaderCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\XmlReaderCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Caster\\XmlResourceCaster' => 
  array (
    'type' => 'class',
    'classname' => 'XmlResourceCaster',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Caster',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Caster\\XmlResourceCaster',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Cloner\\AbstractCloner' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractCloner',
    'isabstract' => true,
    'namespace' => 'Symfony\\Component\\VarDumper\\Cloner',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Cloner\\AbstractCloner',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Cloner\\ClonerInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Cloner\\Cursor' => 
  array (
    'type' => 'class',
    'classname' => 'Cursor',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Cloner',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Cloner\\Cursor',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Cloner\\Data' => 
  array (
    'type' => 'class',
    'classname' => 'Data',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Cloner',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Cloner\\Data',
    'implements' => 
    array (
      0 => 'ArrayAccess',
      1 => 'Countable',
      2 => 'IteratorAggregate',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Cloner\\Stub' => 
  array (
    'type' => 'class',
    'classname' => 'Stub',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Cloner',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Cloner\\Stub',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Cloner\\VarCloner' => 
  array (
    'type' => 'class',
    'classname' => 'VarCloner',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Cloner',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Cloner\\VarCloner',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Command\\Descriptor\\CliDescriptor' => 
  array (
    'type' => 'class',
    'classname' => 'CliDescriptor',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Command\\Descriptor',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Command\\Descriptor\\CliDescriptor',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Command\\Descriptor\\DumpDescriptorInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Command\\Descriptor\\HtmlDescriptor' => 
  array (
    'type' => 'class',
    'classname' => 'HtmlDescriptor',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Command\\Descriptor',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Command\\Descriptor\\HtmlDescriptor',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Command\\Descriptor\\DumpDescriptorInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Command\\ServerDumpCommand' => 
  array (
    'type' => 'class',
    'classname' => 'ServerDumpCommand',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Command',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Command\\ServerDumpCommand',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\AbstractDumper' => 
  array (
    'type' => 'class',
    'classname' => 'AbstractDumper',
    'isabstract' => true,
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\AbstractDumper',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Dumper\\DataDumperInterface',
      1 => 'Symfony\\Component\\VarDumper\\Cloner\\DumperInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\CliDumper' => 
  array (
    'type' => 'class',
    'classname' => 'CliDumper',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\CliDumper',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\CliContextProvider' => 
  array (
    'type' => 'class',
    'classname' => 'CliContextProvider',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\CliContextProvider',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\RequestContextProvider' => 
  array (
    'type' => 'class',
    'classname' => 'RequestContextProvider',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\RequestContextProvider',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\SourceContextProvider' => 
  array (
    'type' => 'class',
    'classname' => 'SourceContextProvider',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\SourceContextProvider',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\ContextualizedDumper' => 
  array (
    'type' => 'class',
    'classname' => 'ContextualizedDumper',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\ContextualizedDumper',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Dumper\\DataDumperInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\HtmlDumper' => 
  array (
    'type' => 'class',
    'classname' => 'HtmlDumper',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\HtmlDumper',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\ServerDumper' => 
  array (
    'type' => 'class',
    'classname' => 'ServerDumper',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\ServerDumper',
    'implements' => 
    array (
      0 => 'Symfony\\Component\\VarDumper\\Dumper\\DataDumperInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Exception\\ThrowingCasterException' => 
  array (
    'type' => 'class',
    'classname' => 'ThrowingCasterException',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Exception',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Exception\\ThrowingCasterException',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Server\\Connection' => 
  array (
    'type' => 'class',
    'classname' => 'Connection',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Server',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Server\\Connection',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\Server\\DumpServer' => 
  array (
    'type' => 'class',
    'classname' => 'DumpServer',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper\\Server',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Server\\DumpServer',
    'implements' => 
    array (
    ),
  ),
  'Symfony\\Component\\VarDumper\\VarDumper' => 
  array (
    'type' => 'class',
    'classname' => 'VarDumper',
    'isabstract' => false,
    'namespace' => 'Symfony\\Component\\VarDumper',
    'extends' => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\VarDumper',
    'implements' => 
    array (
    ),
  ),
  'WPConfigTransformer' => 
  array (
    'type' => 'class',
    'classname' => 'WPConfigTransformer',
    'isabstract' => false,
    'namespace' => '\\',
    'extends' => 'SolidWP_Performance_WPConfigTransformer',
    'implements' => 
    array (
    ),
  ),
  'Monolog\\Handler\\FormattableHandlerTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'FormattableHandlerTrait',
    'namespace' => 'Monolog\\Handler',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Handler\\FormattableHandlerTrait',
    ),
  ),
  'Monolog\\Handler\\ProcessableHandlerTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'ProcessableHandlerTrait',
    'namespace' => 'Monolog\\Handler',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Handler\\ProcessableHandlerTrait',
    ),
  ),
  'Monolog\\Handler\\WebRequestRecognizerTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'WebRequestRecognizerTrait',
    'namespace' => 'Monolog\\Handler',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Handler\\WebRequestRecognizerTrait',
    ),
  ),
  'Niladam\\Uri\\Traits\\Conditionable' => 
  array (
    'type' => 'trait',
    'traitname' => 'Conditionable',
    'namespace' => 'Niladam\\Uri\\Traits',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Niladam\\Uri\\Traits\\Conditionable',
    ),
  ),
  'Niladam\\Uri\\Traits\\Dumpable' => 
  array (
    'type' => 'trait',
    'traitname' => 'Dumpable',
    'namespace' => 'Niladam\\Uri\\Traits',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Niladam\\Uri\\Traits\\Dumpable',
    ),
  ),
  'Niladam\\Uri\\Traits\\Macroable' => 
  array (
    'type' => 'trait',
    'traitname' => 'Macroable',
    'namespace' => 'Niladam\\Uri\\Traits',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Niladam\\Uri\\Traits\\Macroable',
    ),
  ),
  'Niladam\\Uri\\Traits\\Tappable' => 
  array (
    'type' => 'trait',
    'traitname' => 'Tappable',
    'namespace' => 'Niladam\\Uri\\Traits',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Niladam\\Uri\\Traits\\Tappable',
    ),
  ),
  'Psr\\Log\\LoggerAwareTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'LoggerAwareTrait',
    'namespace' => 'Psr\\Log',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Log\\LoggerAwareTrait',
    ),
  ),
  'Psr\\Log\\LoggerTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'LoggerTrait',
    'namespace' => 'Psr\\Log',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Log\\LoggerTrait',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\Aggregate' => 
  array (
    'type' => 'trait',
    'traitname' => 'Aggregate',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\Aggregate',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\CRUD' => 
  array (
    'type' => 'trait',
    'traitname' => 'CRUD',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\CRUD',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\FromClause' => 
  array (
    'type' => 'trait',
    'traitname' => 'FromClause',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\FromClause',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\GroupByStatement' => 
  array (
    'type' => 'trait',
    'traitname' => 'GroupByStatement',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\GroupByStatement',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\HavingClause' => 
  array (
    'type' => 'trait',
    'traitname' => 'HavingClause',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\HavingClause',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\JoinClause' => 
  array (
    'type' => 'trait',
    'traitname' => 'JoinClause',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\JoinClause',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\LimitStatement' => 
  array (
    'type' => 'trait',
    'traitname' => 'LimitStatement',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\LimitStatement',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\MetaQuery' => 
  array (
    'type' => 'trait',
    'traitname' => 'MetaQuery',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\MetaQuery',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\OffsetStatement' => 
  array (
    'type' => 'trait',
    'traitname' => 'OffsetStatement',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\OffsetStatement',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\OrderByStatement' => 
  array (
    'type' => 'trait',
    'traitname' => 'OrderByStatement',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\OrderByStatement',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\SelectStatement' => 
  array (
    'type' => 'trait',
    'traitname' => 'SelectStatement',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\SelectStatement',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\TablePrefix' => 
  array (
    'type' => 'trait',
    'traitname' => 'TablePrefix',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\TablePrefix',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\UnionOperator' => 
  array (
    'type' => 'trait',
    'traitname' => 'UnionOperator',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\UnionOperator',
    ),
  ),
  'StellarWP\\DB\\QueryBuilder\\Concerns\\WhereClause' => 
  array (
    'type' => 'trait',
    'traitname' => 'WhereClause',
    'namespace' => 'StellarWP\\DB\\QueryBuilder\\Concerns',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\DB\\QueryBuilder\\Concerns\\WhereClause',
    ),
  ),
  'StellarWP\\Memoize\\Traits\\MemoizeTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'MemoizeTrait',
    'namespace' => 'StellarWP\\Memoize\\Traits',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Memoize\\Traits\\MemoizeTrait',
    ),
  ),
  'Symfony\\Component\\HttpClient\\AsyncDecoratorTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'AsyncDecoratorTrait',
    'namespace' => 'Symfony\\Component\\HttpClient',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\AsyncDecoratorTrait',
    ),
  ),
  'Symfony\\Component\\HttpClient\\DecoratorTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'DecoratorTrait',
    'namespace' => 'Symfony\\Component\\HttpClient',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\DecoratorTrait',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Exception\\HttpExceptionTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'HttpExceptionTrait',
    'namespace' => 'Symfony\\Component\\HttpClient\\Exception',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Exception\\HttpExceptionTrait',
    ),
  ),
  'Symfony\\Component\\HttpClient\\HttpClientTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'HttpClientTrait',
    'namespace' => 'Symfony\\Component\\HttpClient',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\HttpClientTrait',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\CommonResponseTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'CommonResponseTrait',
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\CommonResponseTrait',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\TransportResponseTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'TransportResponseTrait',
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\TransportResponseTrait',
    ),
  ),
  'Symfony\\Contracts\\Service\\ServiceLocatorTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'ServiceLocatorTrait',
    'namespace' => 'Symfony\\Contracts\\Service',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\ServiceLocatorTrait',
    ),
  ),
  'Symfony\\Contracts\\Service\\ServiceSubscriberTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'ServiceSubscriberTrait',
    'namespace' => 'Symfony\\Contracts\\Service',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\ServiceSubscriberTrait',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Test\\VarDumperTestTrait' => 
  array (
    'type' => 'trait',
    'traitname' => 'VarDumperTestTrait',
    'namespace' => 'Symfony\\Component\\VarDumper\\Test',
    'use' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Test\\VarDumperTestTrait',
    ),
  ),
  'Flintstone\\Cache\\CacheInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'CacheInterface',
    'namespace' => 'Flintstone\\Cache',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Flintstone\\Cache\\CacheInterface',
    ),
  ),
  'Flintstone\\Formatter\\FormatterInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'FormatterInterface',
    'namespace' => 'Flintstone\\Formatter',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Flintstone\\Formatter\\FormatterInterface',
    ),
  ),
  'League\\Uri\\Contracts\\AuthorityInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'AuthorityInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\AuthorityInterface',
    ),
  ),
  'League\\Uri\\Contracts\\DataPathInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'DataPathInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\DataPathInterface',
    ),
  ),
  'League\\Uri\\Contracts\\DomainHostInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'DomainHostInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\DomainHostInterface',
    ),
  ),
  'League\\Uri\\Contracts\\FragmentInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'FragmentInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\FragmentInterface',
    ),
  ),
  'League\\Uri\\Contracts\\HostInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'HostInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\HostInterface',
    ),
  ),
  'League\\Uri\\Contracts\\IpHostInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'IpHostInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\IpHostInterface',
    ),
  ),
  'League\\Uri\\Contracts\\PathInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'PathInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\PathInterface',
    ),
  ),
  'League\\Uri\\Contracts\\PortInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'PortInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\PortInterface',
    ),
  ),
  'League\\Uri\\Contracts\\QueryInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'QueryInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\QueryInterface',
    ),
  ),
  'League\\Uri\\Contracts\\SegmentedPathInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'SegmentedPathInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\SegmentedPathInterface',
    ),
  ),
  'League\\Uri\\Contracts\\UriComponentInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'UriComponentInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\UriComponentInterface',
    ),
  ),
  'League\\Uri\\Contracts\\UriException' => 
  array (
    'type' => 'interface',
    'interfacename' => 'UriException',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\UriException',
    ),
  ),
  'League\\Uri\\Contracts\\UriInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'UriInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\UriInterface',
    ),
  ),
  'League\\Uri\\Contracts\\UserInfoInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'UserInfoInterface',
    'namespace' => 'League\\Uri\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\League\\Uri\\Contracts\\UserInfoInterface',
    ),
  ),
  'lucatume\\DI52\\Builders\\BuilderInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'BuilderInterface',
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\BuilderInterface',
    ),
  ),
  'lucatume\\DI52\\Builders\\ReinitializableBuilderInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ReinitializableBuilderInterface',
    'namespace' => 'lucatume\\DI52\\Builders',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\lucatume\\DI52\\Builders\\ReinitializableBuilderInterface',
    ),
  ),
  'Masterminds\\HTML5\\InstructionProcessor' => 
  array (
    'type' => 'interface',
    'interfacename' => 'InstructionProcessor',
    'namespace' => 'Masterminds\\HTML5',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Masterminds\\HTML5\\InstructionProcessor',
    ),
  ),
  'Masterminds\\HTML5\\Parser\\EventHandler' => 
  array (
    'type' => 'interface',
    'interfacename' => 'EventHandler',
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\EventHandler',
    ),
  ),
  'Masterminds\\HTML5\\Parser\\InputStream' => 
  array (
    'type' => 'interface',
    'interfacename' => 'InputStream',
    'namespace' => 'Masterminds\\HTML5\\Parser',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Masterminds\\HTML5\\Parser\\InputStream',
    ),
  ),
  'Masterminds\\HTML5\\Serializer\\RulesInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'RulesInterface',
    'namespace' => 'Masterminds\\HTML5\\Serializer',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Masterminds\\HTML5\\Serializer\\RulesInterface',
    ),
  ),
  'Monolog\\Formatter\\FormatterInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'FormatterInterface',
    'namespace' => 'Monolog\\Formatter',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Formatter\\FormatterInterface',
    ),
  ),
  'Monolog\\Handler\\FingersCrossed\\ActivationStrategyInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ActivationStrategyInterface',
    'namespace' => 'Monolog\\Handler\\FingersCrossed',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Handler\\FingersCrossed\\ActivationStrategyInterface',
    ),
  ),
  'Monolog\\Handler\\FormattableHandlerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'FormattableHandlerInterface',
    'namespace' => 'Monolog\\Handler',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Handler\\FormattableHandlerInterface',
    ),
  ),
  'Monolog\\Handler\\HandlerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'HandlerInterface',
    'namespace' => 'Monolog\\Handler',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Handler\\HandlerInterface',
    ),
  ),
  'Monolog\\Handler\\ProcessableHandlerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ProcessableHandlerInterface',
    'namespace' => 'Monolog\\Handler',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Handler\\ProcessableHandlerInterface',
    ),
  ),
  'Monolog\\LogRecord' => 
  array (
    'type' => 'interface',
    'interfacename' => 'LogRecord',
    'namespace' => 'Monolog',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\LogRecord',
    ),
  ),
  'Monolog\\Processor\\ProcessorInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ProcessorInterface',
    'namespace' => 'Monolog\\Processor',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\Processor\\ProcessorInterface',
    ),
  ),
  'Monolog\\ResettableInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ResettableInterface',
    'namespace' => 'Monolog',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Monolog\\ResettableInterface',
    ),
  ),
  'Niladam\\Uri\\Concerns\\Arrayable' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Arrayable',
    'namespace' => 'Niladam\\Uri\\Concerns',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Niladam\\Uri\\Concerns\\Arrayable',
    ),
  ),
  'Niladam\\Uri\\Concerns\\Htmlable' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Htmlable',
    'namespace' => 'Niladam\\Uri\\Concerns',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Niladam\\Uri\\Concerns\\Htmlable',
    ),
  ),
  'Niladam\\Uri\\Concerns\\Stringable' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Stringable',
    'namespace' => 'Niladam\\Uri\\Concerns',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Niladam\\Uri\\Concerns\\Stringable',
    ),
  ),
  'Psr\\Container\\ContainerExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ContainerExceptionInterface',
    'namespace' => 'Psr\\Container',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Container\\ContainerExceptionInterface',
    ),
  ),
  'Psr\\Container\\ContainerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ContainerInterface',
    'namespace' => 'Psr\\Container',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Container\\ContainerInterface',
    ),
  ),
  'Psr\\Container\\NotFoundExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'NotFoundExceptionInterface',
    'namespace' => 'Psr\\Container',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Container\\NotFoundExceptionInterface',
    ),
  ),
  'Psr\\Http\\Message\\MessageInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'MessageInterface',
    'namespace' => 'Psr\\Http\\Message',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Http\\Message\\MessageInterface',
    ),
  ),
  'Psr\\Http\\Message\\RequestInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'RequestInterface',
    'namespace' => 'Psr\\Http\\Message',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Http\\Message\\RequestInterface',
    ),
  ),
  'Psr\\Http\\Message\\ResponseInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ResponseInterface',
    'namespace' => 'Psr\\Http\\Message',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Http\\Message\\ResponseInterface',
    ),
  ),
  'Psr\\Http\\Message\\ServerRequestInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ServerRequestInterface',
    'namespace' => 'Psr\\Http\\Message',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Http\\Message\\ServerRequestInterface',
    ),
  ),
  'Psr\\Http\\Message\\StreamInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'StreamInterface',
    'namespace' => 'Psr\\Http\\Message',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Http\\Message\\StreamInterface',
    ),
  ),
  'Psr\\Http\\Message\\UploadedFileInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'UploadedFileInterface',
    'namespace' => 'Psr\\Http\\Message',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Http\\Message\\UploadedFileInterface',
    ),
  ),
  'Psr\\Http\\Message\\UriInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'UriInterface',
    'namespace' => 'Psr\\Http\\Message',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Http\\Message\\UriInterface',
    ),
  ),
  'Psr\\Log\\LoggerAwareInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'LoggerAwareInterface',
    'namespace' => 'Psr\\Log',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Log\\LoggerAwareInterface',
    ),
  ),
  'Psr\\Log\\LoggerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'LoggerInterface',
    'namespace' => 'Psr\\Log',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Psr\\Log\\LoggerInterface',
    ),
  ),
  'StellarWP\\ContainerContract\\ContainerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ContainerInterface',
    'namespace' => 'StellarWP\\ContainerContract',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\ContainerContract\\ContainerInterface',
    ),
  ),
  'StellarWP\\Memoize\\Contracts\\DriverInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'DriverInterface',
    'namespace' => 'StellarWP\\Memoize\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Memoize\\Contracts\\DriverInterface',
    ),
  ),
  'StellarWP\\Memoize\\Contracts\\MemoizerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'MemoizerInterface',
    'namespace' => 'StellarWP\\Memoize\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Memoize\\Contracts\\MemoizerInterface',
    ),
  ),
  'StellarWP\\Pipeline\\Contracts\\Pipe' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Pipe',
    'namespace' => 'StellarWP\\Pipeline\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Pipeline\\Contracts\\Pipe',
    ),
  ),
  'StellarWP\\Schema\\Fields\\Contracts\\Schema_Interface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Schema_Interface',
    'namespace' => 'StellarWP\\Schema\\Fields\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Schema\\Fields\\Contracts\\Schema_Interface',
    ),
  ),
  'StellarWP\\Schema\\Tables\\Contracts\\Schema_Interface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Schema_Interface',
    'namespace' => 'StellarWP\\Schema\\Tables\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Schema\\Tables\\Contracts\\Schema_Interface',
    ),
  ),
  'StellarWP\\Telemetry\\Contracts\\Data_Provider' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Data_Provider',
    'namespace' => 'StellarWP\\Telemetry\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Contracts\\Data_Provider',
    ),
  ),
  'StellarWP\\Telemetry\\Contracts\\Runnable' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Runnable',
    'namespace' => 'StellarWP\\Telemetry\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Contracts\\Runnable',
    ),
  ),
  'StellarWP\\Telemetry\\Contracts\\Subscriber_Interface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Subscriber_Interface',
    'namespace' => 'StellarWP\\Telemetry\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Contracts\\Subscriber_Interface',
    ),
  ),
  'StellarWP\\Telemetry\\Contracts\\Template_Interface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'Template_Interface',
    'namespace' => 'StellarWP\\Telemetry\\Contracts',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\StellarWP\\Telemetry\\Contracts\\Template_Interface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Exception\\ExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ExceptionInterface',
    'namespace' => 'Symfony\\Component\\CssSelector\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Exception\\ExceptionInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Node\\NodeInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'NodeInterface',
    'namespace' => 'Symfony\\Component\\CssSelector\\Node',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Node\\NodeInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'HandlerInterface',
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser\\Handler',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\Handler\\HandlerInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\Parser\\ParserInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ParserInterface',
    'namespace' => 'Symfony\\Component\\CssSelector\\Parser',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\Parser\\ParserInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\Extension\\ExtensionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ExtensionInterface',
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath\\Extension',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\Extension\\ExtensionInterface',
    ),
  ),
  'Symfony\\Component\\CssSelector\\XPath\\TranslatorInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'TranslatorInterface',
    'namespace' => 'Symfony\\Component\\CssSelector\\XPath',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\CssSelector\\XPath\\TranslatorInterface',
    ),
  ),
  'Symfony\\Component\\Filesystem\\Exception\\ExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ExceptionInterface',
    'namespace' => 'Symfony\\Component\\Filesystem\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\Filesystem\\Exception\\ExceptionInterface',
    ),
  ),
  'Symfony\\Component\\Filesystem\\Exception\\IOExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'IOExceptionInterface',
    'namespace' => 'Symfony\\Component\\Filesystem\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\Filesystem\\Exception\\IOExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\ChunkInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ChunkInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\ChunkInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Exception\\ClientExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ClientExceptionInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Exception\\ClientExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Exception\\DecodingExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'DecodingExceptionInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Exception\\DecodingExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Exception\\ExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ExceptionInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Exception\\ExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Exception\\HttpExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'HttpExceptionInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Exception\\HttpExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Exception\\RedirectionExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'RedirectionExceptionInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Exception\\RedirectionExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Exception\\ServerExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ServerExceptionInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Exception\\ServerExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Exception\\TimeoutExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'TimeoutExceptionInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Exception\\TimeoutExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\Exception\\TransportExceptionInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'TransportExceptionInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient\\Exception',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\Exception\\TransportExceptionInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\HttpClientInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'HttpClientInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\HttpClientInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\ResponseInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ResponseInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\ResponseInterface',
    ),
  ),
  'Symfony\\Contracts\\HttpClient\\ResponseStreamInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ResponseStreamInterface',
    'namespace' => 'Symfony\\Contracts\\HttpClient',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\HttpClient\\ResponseStreamInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Response\\StreamableInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'StreamableInterface',
    'namespace' => 'Symfony\\Component\\HttpClient\\Response',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Response\\StreamableInterface',
    ),
  ),
  'Symfony\\Component\\HttpClient\\Retry\\RetryStrategyInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'RetryStrategyInterface',
    'namespace' => 'Symfony\\Component\\HttpClient\\Retry',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\HttpClient\\Retry\\RetryStrategyInterface',
    ),
  ),
  'Symfony\\Contracts\\Service\\ResetInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ResetInterface',
    'namespace' => 'Symfony\\Contracts\\Service',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\ResetInterface',
    ),
  ),
  'Symfony\\Contracts\\Service\\ServiceProviderInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ServiceProviderInterface',
    'namespace' => 'Symfony\\Contracts\\Service',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\ServiceProviderInterface',
    ),
  ),
  'Symfony\\Contracts\\Service\\ServiceSubscriberInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ServiceSubscriberInterface',
    'namespace' => 'Symfony\\Contracts\\Service',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Contracts\\Service\\ServiceSubscriberInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Cloner\\ClonerInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ClonerInterface',
    'namespace' => 'Symfony\\Component\\VarDumper\\Cloner',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Cloner\\ClonerInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Cloner\\DumperInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'DumperInterface',
    'namespace' => 'Symfony\\Component\\VarDumper\\Cloner',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Cloner\\DumperInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Command\\Descriptor\\DumpDescriptorInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'DumpDescriptorInterface',
    'namespace' => 'Symfony\\Component\\VarDumper\\Command\\Descriptor',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Command\\Descriptor\\DumpDescriptorInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'ContextProviderInterface',
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper\\ContextProvider',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface',
    ),
  ),
  'Symfony\\Component\\VarDumper\\Dumper\\DataDumperInterface' => 
  array (
    'type' => 'interface',
    'interfacename' => 'DataDumperInterface',
    'namespace' => 'Symfony\\Component\\VarDumper\\Dumper',
    'extends' => 
    array (
      0 => 'SolidWP\\Performance\\Symfony\\Component\\VarDumper\\Dumper\\DataDumperInterface',
    ),
  ),
);

        public function __construct()
        {
            $this->includeFilePath = __DIR__ . '/autoload_alias.php';
        }

        /**
         * @param string $class
         */
        public function autoload($class): void
        {
            if (!isset($this->autoloadAliases[$class])) {
                return;
            }
            switch ($this->autoloadAliases[$class]['type']) {
                case 'class':
                        $this->load(
                            $this->classTemplate(
                                $this->autoloadAliases[$class]
                            )
                        );
                    break;
                case 'interface':
                    $this->load(
                        $this->interfaceTemplate(
                            $this->autoloadAliases[$class]
                        )
                    );
                    break;
                case 'trait':
                    $this->load(
                        $this->traitTemplate(
                            $this->autoloadAliases[$class]
                        )
                    );
                    break;
                case 'enum':
                    // Enums are final so cannot be aliased with an `extends` shim like classes are; `class_alias()`
                    // makes the original name a true alias of the renamed enum, preserving case identity (`===`),
                    // `match` arms, `::cases()`, `::from()` and `instanceof` the enum itself. Unlike the class shim,
                    // the alias does not implement the enum's original interface names, so `instanceof` against an
                    // original interface name is false. Enums require PHP 8.1, but this only executes when an enum
                    // is autoloaded by its original name, i.e. on a runtime already using enums.
                    \class_alias(
                        $this->autoloadAliases[$class]['concrete'],
                        $class
                    );
                    break;
                default:
                    // Never.
                    break;
            }
        }

        private function load(string $includeFile): void
        {
            file_put_contents($this->includeFilePath, $includeFile);
            include $this->includeFilePath;
            file_exists($this->includeFilePath) && unlink($this->includeFilePath);
        }

        /**
         * @param ClassAliasArray $class
         */
        private function classTemplate(array $class): string
        {
            $abstract = $class['isabstract'] ? 'abstract ' : '';
            $classname = $class['classname'];
            if (isset($class['namespace'])) {
                $namespace = "namespace {$class['namespace']};";
                $extends = '\\' . $class['extends'];
                $implements = empty($class['implements']) ? ''
                : ' implements \\' . implode(', \\', $class['implements']);
            } else {
                $namespace = '';
                $extends = $class['extends'];
                $implements = !empty($class['implements']) ? ''
                : ' implements ' . implode(', ', $class['implements']);
            }
            return <<<EOD
                <?php
                $namespace
                $abstract class $classname extends $extends $implements {}
                EOD;
        }

        /**
         * @param InterfaceAliasArray $interface
         */
        private function interfaceTemplate(array $interface): string
        {
            $interfacename = $interface['interfacename'];
            $namespace = isset($interface['namespace'])
            ? "namespace {$interface['namespace']};" : '';
            $extends = isset($interface['namespace'])
            ? '\\' . implode('\\ ,', $interface['extends'])
            : implode(', ', $interface['extends']);
            return <<<EOD
                <?php
                $namespace
                interface $interfacename extends $extends {}
                EOD;
        }

        /**
         * @param TraitAliasArray $trait
         */
        private function traitTemplate(array $trait): string
        {
            $traitname = $trait['traitname'];
            $namespace = isset($trait['namespace'])
            ? "namespace {$trait['namespace']};" : '';
            $uses = isset($trait['namespace'])
            ? '\\' . implode(';' . PHP_EOL . '    use \\', $trait['use'])
            : implode(';' . PHP_EOL . '    use ', $trait['use']);
            return <<<EOD
                <?php
                $namespace
                trait $traitname { 
                    use $uses; 
                }
                EOD;
        }
    }

    spl_autoload_register([ new AliasAutoloader(), 'autoload' ]);
}
