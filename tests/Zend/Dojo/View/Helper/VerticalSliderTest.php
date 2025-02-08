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

// Call Zend_Dojo_View_Helper_VerticalSliderTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Dojo_View_Helper_VerticalSliderTest::main');
}

/** Zend_Dojo_View_Helper_VerticalSlider */
// require_once 'Zend/Dojo/View/Helper/VerticalSlider.php';

/** Zend_View */
// require_once 'Zend/View.php';

/** Zend_Registry */
// require_once 'Zend/Registry.php';

/** Zend_Dojo_View_Helper_Dojo */
// require_once 'Zend/Dojo/View/Helper/Dojo.php';

/**
 * Test class for Zend_Dojo_View_Helper_VerticalSlider.
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
class Zend_Dojo_View_Helper_VerticalSliderTest extends PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite = PHPUnit\Framework\TestSuite::empty('Zend_Dojo_View_Helper_VerticalSliderTest');
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
        $this->helper = new Zend_Dojo_View_Helper_VerticalSlider();
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

    public function getElement()
    {
        return $this->helper->verticalSlider(
            'elementId',
            '',
            [
                'minimum' => -10,
                'maximum' => 10,
                'discreteValues' => 11,
                'topDecoration' => [
                    'labels' => [
                        ' ',
                        '20%',
                        '40%',
                        '60%',
                        '80%',
                        ' ',
                    ],
                    'attribs' => [
                        'container' => [
                            'style' => 'height:1.2em; font-size=75%;color:gray;',
                        ],
                        'labels' => [
                            'style' => 'height:1em; font-size=75%;color:gray;',
                        ],
                    ],
                    'dijit' => 'VerticalRuleLabels',
                ],
                'bottomDecoration' => [
                    'labels' => [
                        '0%',
                        '50%',
                        '100%',
                    ],
                    'attribs' => [
                        'labels' => [
                            'style' => 'height:1em; font-size=75%;color:gray;',
                        ],
                    ],
                ],
                'leftDecoration' => [
                    'labels' => [
                        ' ',
                        '20%',
                        '40%',
                        '60%',
                        '80%',
                        ' ',
                    ],
                    'attribs' => [
                        'container' => [
                            'style' => 'height:1.2em; font-size=75%;color:gray;',
                        ],
                        'labels' => [
                            'style' => 'height:1em; font-size=75%;color:gray;',
                        ],
                    ],
                    'dijit' => 'VerticalRuleLabels',
                ],
                'rightDecoration' => [
                    'labels' => [
                        '0%',
                        '50%',
                        '100%',
                    ],
                    'attribs' => [
                        'labels' => [
                            'style' => 'height:1em; font-size=75%;color:gray;',
                        ],
                    ],
                ],
            ],
            []
        );
    }

    public function testShouldAllowDeclarativeDijitCreation()
    {
        $html = $this->getElement();
        $this->assertMatchesRegularExpression('/<div[^>]*(dojoType="dijit.form.VerticalSlider")/', $html, $html);
    }

    public function testShouldAllowProgrammaticDijitCreation()
    {
        Zend_Dojo_View_Helper_Dojo::setUseProgrammatic();
        $html = $this->getElement();
        $this->assertNotRegexp('/<div[^>]*(dojoType="dijit.form.VerticalSlider")/', $html);
        $this->assertNotNull($this->view->dojo()->getDijit('elementId-slider'));
    }

    public function testShouldCreateOnChangeAttributeByDefault()
    {
        $html = $this->getElement();
        // Note that ' is converted to &#39; in Zend_View_Helper_HtmlElement::_htmlAttribs() (line 116)
        $this->assertStringContainsString('onChange="dojo.byId(&#39;elementId&#39;).value = arguments[0];"', $html, $html);
    }

    public function testShouldCreateHiddenElementWithValue()
    {
        $html = $this->getElement();
        if (!preg_match('/(<input[^>]*(type="hidden")[^>]*>)/', $html, $m)) {
            $this->fail('No hidden element found');
        }
        $this->assertStringContainsString('id="elementId"', $m[1]);
        $this->assertStringContainsString('value="', $m[1]);
    }

    public function testShouldCreateLeftAndRightDecorationsWhenRequested()
    {
        $html = $this->getElement();
        $this->assertMatchesRegularExpression('/<div[^>]*(dojoType="dijit.form.VerticalRule")/', $html, $html);
        $this->assertMatchesRegularExpression('/<ol[^>]*(dojoType="dijit.form.VerticalRuleLabels")/', $html, $html);
        $this->assertStringContainsString('leftDecoration', $html);
        $this->assertStringContainsString('rightDecoration', $html);
    }

    public function testShouldIgnoreTopAndBottomDecorationsWhenPassed()
    {
        $html = $this->getElement();
        $this->assertStringNotContainsString('topDecoration', $html);
        $this->assertStringNotContainsString('bottomDecoration', $html);
    }
}

// Call Zend_Dojo_View_Helper_VerticalSliderTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_Dojo_View_Helper_VerticalSliderTest::main') {
    Zend_Dojo_View_Helper_VerticalSliderTest::main();
}
