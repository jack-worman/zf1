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

// Call Zend_Dojo_Form_Element_SimpleTextareaTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Dojo_Form_Element_SimpleTextareaTest::main');
}

/** Zend_Dojo_Form_Element_SimpleTextarea */
// require_once 'Zend/Dojo/Form/Element/SimpleTextarea.php';

/** Zend_View */
// require_once 'Zend/View.php';

/** Zend_Dojo */
// require_once 'Zend/Dojo.php';

/** Zend_Registry */
// require_once 'Zend/Registry.php';

/** Zend_Dojo_View_Helper_Dojo */
// require_once 'Zend/Dojo/View/Helper/Dojo.php';

/**
 * Test class for Zend_Dojo_Form_Element_SimpleTextarea.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Dojo
 * @group      Zend_Dojo_Form
 */
#[AllowDynamicProperties]
class Zend_Dojo_Form_Element_SimpleTextareaTest extends PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite = PHPUnit\Framework\TestSuite::empty('Zend_Dojo_Form_Element_SimpleTextareaTest');
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
    public function setUp(): void
    {
        Zend_Registry::_unsetInstance();
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative();

        $this->view = $this->getView();
        $this->element = $this->getElement();
        $this->element->setView($this->view);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown(): void
    {
    }

    public function getView()
    {
        // require_once 'Zend/View.php';
        $view = new Zend_View();
        Zend_Dojo::enableView($view);

        return $view;
    }

    public function getElement()
    {
        $element = new Zend_Dojo_Form_Element_SimpleTextarea(
            'foo',
            [
                'value' => 'some text',
                'label' => 'SimpleTextarea',
                'class' => 'someclass',
                'style' => 'width: 100px;',
            ]
        );

        return $element;
    }

    public function testShouldRenderSimpleTextareaDijit()
    {
        $html = $this->element->render();
        $this->assertStringContainsString('dojoType="dijit.form.SimpleTextarea"', $html);
    }
}

// Call Zend_Dojo_Form_Element_SimpleTextareaTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_Dojo_Form_Element_SimpleTextareaTest::main') {
    Zend_Dojo_Form_Element_SimpleTextareaTest::main();
}
