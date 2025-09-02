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

// Call Zend_Filter_DashToSeparatorTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Filter_Word_DashToSeparatorTest::main');
}

/**
 * Test class for Zend_Filter_Word_DashToSeparator.
 *
 * @group      Zend_Filter
 */
#[AllowDynamicProperties]
class Zend_Filter_Word_DashToSeparatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @static
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_Filter_Word_DashToSeparatorTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function testFilterSeparatesDashedWordsWithDefaultSpaces()
    {
        $string = 'dash-separated-words';
        $filter = new Zend_Filter_Word_DashToSeparator();
        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals('dash separated words', $filtered);
    }

    public function testFilterSeparatesDashedWordsWithSomeString()
    {
        $string = 'dash-separated-words';
        $filter = new Zend_Filter_Word_DashToSeparator(':-:');
        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals('dash:-:separated:-:words', $filtered);
    }
}

// Call Zend_Filter_Word_DashToSeparatorTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_Filter_Word_DashToSeparatorTest::main') {
    Zend_Filter_Word_DashToSeparatorTest::main();
}
