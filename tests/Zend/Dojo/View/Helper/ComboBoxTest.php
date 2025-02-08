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

// Call Zend_Dojo_View_Helper_ComboBoxTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Dojo_View_Helper_ComboBoxTest::main');
}

/** Zend_Dojo_View_Helper_ComboBox */
// require_once 'Zend/Dojo/View/Helper/ComboBox.php';

/** Zend_View */
// require_once 'Zend/View.php';

/** Zend_Registry */
// require_once 'Zend/Registry.php';

/** Zend_Dojo_View_Helper_Dojo */
// require_once 'Zend/Dojo/View/Helper/Dojo.php';

/**
 * Test class for Zend_Dojo_View_Helper_ComboBox.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Dojo
 * @group      Zend_Dojo_View
 */
#[AllowDynamicProperties]
class Zend_Dojo_View_Helper_ComboBoxTest extends PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite = PHPUnit\Framework\TestSuite::empty('Zend_Dojo_View_Helper_ComboBoxTest');
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
        $this->helper = new Zend_Dojo_View_Helper_ComboBox();
        $this->helper->setView($this->view);
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
        $view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');

        return $view;
    }

    public function getElementAsSelect()
    {
        return $this->helper->comboBox(
            'elementId',
            'someCombo',
            [],
            [],
            [
                'red' => 'Rouge',
                'blue' => 'Bleu',
                'white' => 'Blanc',
                'orange' => 'Orange',
                'black' => 'Noir',
                'green' => 'Vert',
            ]
        );
    }

    public function getElementAsRemoter()
    {
        return $this->helper->comboBox(
            'elementId',
            'someCombo',
            [
                'store' => [
                    'store' => 'stateStore',
                    'type' => 'dojo.data.ItemFileReadStore',
                    'params' => [
                        'url' => 'states.txt',
                    ],
                ],
                'searchAttr' => 'name',
            ],
            []
        );
    }

    public function testShouldAllowDeclarativeDijitCreationAsSelect()
    {
        $html = $this->getElementAsSelect();
        $this->assertMatchesRegularExpression('/<select[^>]*(dojoType="dijit.form.ComboBox")/', $html, $html);
    }

    public function testShouldAllowProgrammaticDijitCreationAsSelect()
    {
        Zend_Dojo_View_Helper_Dojo::setUseProgrammatic();
        $html = $this->getElementAsSelect();
        $this->assertNotRegexp('/<select[^>]*(dojoType="dijit.form.ComboBox")/', $html);
        $this->assertNotNull($this->view->dojo()->getDijit('elementId'));
    }

    public function testShouldAllowDeclarativeDijitCreationAsRemoter()
    {
        $html = $this->getElementAsRemoter();
        if (!preg_match('/(<input[^>]*(dojoType="dijit.form.ComboBox"))/', $html, $m)) {
            $this->fail('Did not create text input as remoter: '.$html);
        }
        $this->assertStringContainsString('type="text"', $m[1]);
    }

    public function testShouldAllowProgrammaticDijitCreationAsRemoter()
    {
        Zend_Dojo_View_Helper_Dojo::setUseProgrammatic();
        $html = $this->getElementAsRemoter();
        $this->assertNotRegexp('/<input[^>]*(dojoType="dijit.form.ComboBox")/', $html);
        $this->assertMatchesRegularExpression('/<input[^>]*(type="text")/', $html);
        $this->assertNotNull($this->view->dojo()->getDijit('elementId'));

        $found = false;
        $this->assertStringContainsString('var stateStore;', $this->view->dojo()->getJavascript());

        $scripts = $this->view->dojo()->_getZendLoadActions();
        foreach ($scripts as $js) {
            if (strstr((string) $js, 'stateStore = new ')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'No store declaration found: '.var_export($scripts, 1));
    }

    public function testShouldAllowAlternateNotationToSpecifyRemoter()
    {
        $html = $this->helper->comboBox(
            'elementId',
            'someCombo',
            [
                'store' => 'stateStore',
                'storeType' => 'dojo.data.ItemFileReadStore',
                'storeParams' => ['url' => 'states.txt'],
                'searchAttr' => 'name',
            ]
        );
        if (!preg_match('/(<input[^>]*(dojoType="dijit.form.ComboBox"))/', $html, $m)) {
            $this->fail('Did not create text input as remoter: '.$html);
        }
        $this->assertStringContainsString('type="text"', $m[1]);
        if (!preg_match('/(<div[^>]*(?:dojoType="dojo.data.ItemFileReadStore")[^>]*>)/', $html, $m)) {
            $this->fail('Did not create data store: '.$html);
        }
        $this->assertStringContainsString('url="states.txt"', $m[1]);
    }

    /**
     * @group ZF-5987
     * @group ZF-7266
     */
    public function testStoreCreationWhenUsingProgrammaticCreationShouldRegisterAsDojoJavascript()
    {
        Zend_Dojo_View_Helper_Dojo::setUseProgrammatic(true);
        $html = $this->getElementAsRemoter();

        $js = $this->view->dojo()->getJavascript();
        $this->assertStringContainsString('var stateStore;', $js);

        $onLoad = $this->view->dojo()->_getZendLoadActions();
        $storeDeclarationFound = false;
        foreach ($onLoad as $statement) {
            if (strstr((string) $statement, 'stateStore = new ')) {
                $storeDeclarationFound = true;
                break;
            }
        }
        $this->assertTrue($storeDeclarationFound, 'Store definition not found');
    }
}

// Call Zend_Dojo_View_Helper_ComboBoxTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_Dojo_View_Helper_ComboBoxTest::main') {
    Zend_Dojo_View_Helper_ComboBoxTest::main();
}
