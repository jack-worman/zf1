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
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @version    $Id: $
 */
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Filter_Compress_LzfTest::main');
}

/**
 * @see Zend_Filter_Compress_Lzf
 */
// require_once 'Zend/Filter/Compress/Lzf.php';

/**
 * @category   Zend
 *
 * @group      Zend_Filter
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
#[AllowDynamicProperties]
class Zend_Filter_Compress_LzfTest extends PHPUnit\Framework\TestCase
{
    /**
     * Runs this test suite.
     *
     * @return void
     */
    public static function main()
    {
        $suite = PHPUnit\Framework\TestSuite::empty('Zend_Filter_Compress_LzfTest');
        (new PHPUnit\TextUI\TestRunner())->run(
            PHPUnit\TextUI\Configuration\Registry::get(),
            new PHPUnit\Runner\ResultCache\NullResultCache(),
            $suite,
        );
    }

    public function setUp(): void
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
