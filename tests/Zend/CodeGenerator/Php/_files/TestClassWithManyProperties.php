<?php

#[AllowDynamicProperties]
class Zend_CodeGenerator_Php_TestClassWithManyProperties
{
    public const FOO = 'foo';

    public static $fooStaticProperty;

    public $fooProperty = true;

    protected static $_barStaticProperty = 1;

    protected $_barProperty = 1.1115;

    private static $_bazStaticProperty = self::FOO;

    private $_bazProperty = [true, false, true];

    protected $_complexType = [
        5,
        'one' => 1,
        'two' => '2',
        [
            'bar',
            'baz',
            // PHP_EOL
        ],
    ];
}
