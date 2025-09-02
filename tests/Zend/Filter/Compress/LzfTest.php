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
    define('PHPUnit_MAIN_METHOD', 'Zend_Filter_Compress_LzfTest::main');
}

/**
 * @group      Zend_Filter
 */
#[AllowDynamicProperties]
class Zend_Filter_Compress_LzfTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs this test suite.
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_Filter_Compress_LzfTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp()
    {
        if (!extension_loaded('lzf')) {
            $this->markTestSkipped('This adapter needs the lzf extension');
        }
    }

    /**
     * Basic usage.
     *
     * @return void
     */
    public function testBasicUsage()
    {
        $filter = new Zend_Filter_Compress_Lzf();

        $text = 'compress me';
        $compressed = $filter->compress($text);
        $this->assertNotEquals($text, $compressed);

        $decompressed = $filter->decompress($compressed);
        $this->assertEquals($text, $decompressed);
    }

    /**
     * testing toString.
     *
     * @return void
     */
    public function testLzfToString()
    {
        $filter = new Zend_Filter_Compress_Lzf();
        $this->assertEquals('Lzf', $filter->toString());
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Filter_Compress_LzfTest::main') {
    Zend_Filter_Compress_LzfTest::main();
}
