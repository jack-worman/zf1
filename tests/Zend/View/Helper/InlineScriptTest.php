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

// Call Zend_View_Helper_InlineScriptTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_View_Helper_InlineScriptTest::main');
}

/** Zend_View_Helper_InlineScript */
// require_once 'Zend/View/Helper/InlineScript.php';

/** Zend_View_Helper_Placeholder_Registry */
// require_once 'Zend/View/Helper/Placeholder/Registry.php';

/** Zend_Registry */
// require_once 'Zend/Registry.php';

/**
 * Test class for Zend_View_Helper_InlineScript.
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
class Zend_View_Helper_InlineScriptTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Zend_View_Helper_InlineScript
     */
    public $helper;

    /**
     * @var string
     */
    public $basePath;

    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite = PHPUnit\Framework\TestSuite::empty('Zend_View_Helper_InlineScriptTest');
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
        $regKey = Zend_View_Helper_Placeholder_Registry::REGISTRY_KEY;
        if (Zend_Registry::isRegistered($regKey)) {
            $registry = Zend_Registry::getInstance();
            unset($registry[$regKey]);
        }
        $this->basePath = __DIR__.'/_files/modules';
        $this->helper = new Zend_View_Helper_InlineScript();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown(): void
    {
        unset($this->helper);
    }

    public function testNamespaceRegisteredInPlaceholderRegistryAfterInstantiation()
    {
        $registry = Zend_View_Helper_Placeholder_Registry::getRegistry();
        if ($registry->containerExists('Zend_View_Helper_InlineScript')) {
            $registry->deleteContainer('Zend_View_Helper_InlineScript');
        }
        $this->assertFalse($registry->containerExists('Zend_View_Helper_InlineScript'));
        $helper = new Zend_View_Helper_InlineScript();
        $this->assertTrue($registry->containerExists('Zend_View_Helper_InlineScript'));
    }

    public function testInlineScriptReturnsObjectInstance()
    {
        $placeholder = $this->helper->inlineScript();
        $this->assertTrue($placeholder instanceof Zend_View_Helper_InlineScript);
    }
}

// Call Zend_View_Helper_InlineScriptTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_View_Helper_InlineScriptTest::main') {
    Zend_View_Helper_InlineScriptTest::main();
}
