<?php

/**
 * Zend Framework.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 */
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Config_AllTests::main');
}

require_once 'Zend/Config/Writer/AllTests.php';
require_once 'Zend/Config/IniTest.php';
require_once 'Zend/Config/JsonTest.php';
require_once 'Zend/Config/XmlTest.php';
require_once 'Zend/Config/YamlTest.php';

/**
 * @group      Zend_Config
 */
#[AllowDynamicProperties]
class Zend_Config_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend_Config');

        $suite->addTest(Zend_Config_Writer_AllTests::suite());

        $suite->addTestSuite('Zend_Config_IniTest');
        $suite->addTestSuite('Zend_Config_JsonTest');
        $suite->addTestSuite('Zend_Config_XmlTest');
        $suite->addTestSuite('Zend_Config_YamlTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Config_AllTests::main') {
    Zend_Config_AllTests::main();
}
