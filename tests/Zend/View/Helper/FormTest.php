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
 * @version    $Id$
 */

// Call Zend_View_Helper_FormTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_View_Helper_FormTest::main');
}

// require_once 'Zend/View.php';
// require_once 'Zend/View/Helper/Form.php';

/**
 * Test class for Zend_View_Helper_Form.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_View
 * @group      Zend_View_Helper
 */
#[AllowDynamicProperties]
class Zend_View_Helper_FormTest extends PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @static
     */
    public static function main()
    {
        $suite = PHPUnit\Framework\TestSuite::empty('Zend_View_Helper_FormTest');
        (new PHPUnit\TextUI\TestRunner())->run(
            PHPUnit\TextUI\Configuration\Registry::get(),
            new PHPUnit\Runner\ResultCache\NullResultCache(),
            $suite,
        );
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->view = new Zend_View();
        $this->helper = new Zend_View_Helper_Form();
        $this->helper->setView($this->view);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    public function testFormWithSaneInput()
    {
        $form = $this->helper->form('foo', ['action' => '/foo', 'method' => 'get']);
        $this->assertMatchesRegularExpression('/<form[^>]*(id="foo")/', $form);
        $this->assertMatchesRegularExpression('/<form[^>]*(action="\/foo")/', $form);
        $this->assertMatchesRegularExpression('/<form[^>]*(method="get")/', $form);
    }

    public function testFormWithInputNeedingEscapesUsesViewEscaping()
    {
        $form = $this->helper->form('<&foo');
        $this->assertStringContainsString($this->view->escape('<&foo'), $form);
    }

    /**
     * @group ZF-3832
     */
    public function testEmptyIdShouldNotRenderIdAttribute()
    {
        $form = $this->helper->form('', ['action' => '/foo', 'method' => 'get']);
        $this->assertNotRegexp('/<form[^>]*(id="")/', $form);
        $form = $this->helper->form('', ['action' => '/foo', 'method' => 'get', 'id' => null]);
        $this->assertNotRegexp('/<form[^>]*(id="")/', $form);
    }

    /**
     * @group ZF-10791
     */
    public function testPassingNameAsAttributeShouldOverrideFormName()
    {
        $form = $this->helper->form('OrigName', ['action' => '/foo', 'method' => 'get', 'name' => 'SomeNameAttr']);
        $this->assertNotRegexp('/<form[^>]*(name="OrigName")/', $form);
        $this->assertMatchesRegularExpression('/<form[^>]*(name="SomeNameAttr")/', $form);
    }

    /**
     * @group ZF-10791
     */
    public function testNotSpecifyingFormNameShouldNotRenderNameAttrib()
    {
        $form = $this->helper->form('', ['action' => '/foo', 'method' => 'get']);
        $this->assertNotRegexp('/<form[^>]*(name=".*")/', $form);
    }

    /**
     * @group ZF-10791
     */
    public function testSpecifyingFormNameShouldRenderNameAttrib()
    {
        $form = $this->helper->form('FormName', ['action' => '/foo', 'method' => 'get']);
        $this->assertMatchesRegularExpression('/<form[^>]*(name="FormName")/', $form);
    }

    /**
     * @group ZF-10791
     */
    public function testPassingEmptyNameAttributeToUnnamedFormShouldNotRenderNameAttrib()
    {
        $form = $this->helper->form('', ['action' => '/foo', 'method' => 'get', 'name' => null]);
        $this->assertNotRegexp('/<form[^>]*(name=".*")/', $form);
    }

    /**
     * @group ZF-10791
     */
    public function testPassingEmptyNameAttributeToNamedFormShouldNotOverrideNameAttrib()
    {
        $form = $this->helper->form('RealName', ['action' => '/foo', 'method' => 'get', 'name' => null]);
        $this->assertMatchesRegularExpression('/<form[^>]*(name="RealName")/', $form);
    }

    /**
     * @group ZF-10791
     */
    public function testNameAttributeShouldBeOmittedWhenUsingXhtml1Strict()
    {
        $this->view->doctype('XHTML1_STRICT');
        $form = $this->helper->form('FormName', ['action' => '/foo', 'method' => 'get']);
        $this->assertNotRegexp('/<form[^>]*(name="FormName")/', $form);
    }

    /**
     * @group ZF-10791
     */
    public function testNameAttributeShouldBeOmittedWhenUsingXhtml11()
    {
        $this->view->doctype('XHTML11');
        $form = $this->helper->form('FormName', ['action' => '/foo', 'method' => 'get']);
        $this->assertNotRegexp('/<form[^>]*(name="FormName")/', $form);
    }

    public function testEmptyActionShouldNotRenderActionAttributeInHTML5()
    {
        $this->view->doctype(Zend_View_Helper_Doctype::HTML5);
        $form = $this->helper->form('', ['action' => '']);
        $this->assertNotRegexp('/<form[^>]*(action="")/', $form);
        $form = $this->helper->form('', ['action' => null]);
        $this->assertNotRegexp('/<form[^>]*(action="")/', $form);
        $form = $this->helper->form('');
        $this->assertNotRegexp('/<form[^>]*(action="")/', $form);
    }
}

// Call Zend_View_Helper_FormTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_View_Helper_FormTest::main') {
    Zend_View_Helper_FormTest::main();
}
