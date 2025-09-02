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

// Call Zend_View_Helper_FormTextareaTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_View_Helper_FormTextareaTest::main');
}

/**
 * Zend_View_Helper_FormTextareaTest.
 *
 * Tests formTextarea helper
 *
 * @uses \PHPUnit_Framework_TestCase
 *
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
#[AllowDynamicProperties]
class Zend_View_Helper_FormTextareaTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @static
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_View_Helper_FormTextareaTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->view = new Zend_View();
        $this->helper = new Zend_View_Helper_FormTextarea();
        $this->helper->setView($this->view);
    }

    /**
     * ZF-1666.
     */
    public function testCanDisableElement()
    {
        $html = $this->helper->formTextarea([
            'name' => 'foo',
            'value' => 'bar',
            'attribs' => ['disable' => true],
        ]);

        $this->assertRegexp('/<textarea[^>]*?(disabled="disabled")/', $html);
    }

    /**
     * ZF-1666.
     */
    public function testDisablingElementDoesNotRenderHiddenElements()
    {
        $html = $this->helper->formTextarea([
            'name' => 'foo',
            'value' => 'bar',
            'attribs' => ['disable' => true],
        ]);

        $this->assertNotRegexp('/<textarea[^>]*?(type="hidden")/', $html);
    }
}

// Call Zend_View_Helper_FormTextareaTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_View_Helper_FormTextareaTest::main') {
    Zend_View_Helper_FormTextareaTest::main();
}
