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
#[AllowDynamicProperties]
class Zend_Cache_Backend_FooBarTest extends Zend_Cache_Backend_File
{
}
#[AllowDynamicProperties]
class FooBarTestBackend extends Zend_Cache_Backend_File
{
}

#[AllowDynamicProperties]
class Zend_Cache_Frontend_FooBarTest extends Zend_Cache_Core
{
}
#[AllowDynamicProperties]
class FooBarTestFrontend extends Zend_Cache_Core
{
}

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Cache
 */
#[AllowDynamicProperties]
class Zend_Cache_FactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function testFactoryCorrectCall()
    {
        $generated_frontend = Zend_Cache::factory('Core', 'File');
        $this->assertEquals('Zend_Cache_Core', get_class($generated_frontend));
    }

    public function testFactoryCorrectCallWithCustomBackend()
    {
        $generated_frontend = Zend_Cache::factory('Core', 'FooBarTest', [], [], false, false, true);
        $this->assertEquals('Zend_Cache_Core', get_class($generated_frontend));
    }

    public function testFactoryCorrectCallWithCustomBackend2()
    {
        $generated_frontend = Zend_Cache::factory('Core', 'FooBarTestBackend', [], [], false, true, true);
        $this->assertEquals('Zend_Cache_Core', get_class($generated_frontend));
    }

    public function testFactoryCorrectCallWithCustomFrontend()
    {
        $generated_frontend = Zend_Cache::factory('FooBarTest', 'File', [], [], false, false, true);
        $this->assertEquals('Zend_Cache_Frontend_FooBarTest', get_class($generated_frontend));
    }

    public function testFactoryCorrectCallWithCustomFrontend2()
    {
        $generated_frontend = Zend_Cache::factory('FooBarTestFrontend', 'File', [], [], true, false, true);
        $this->assertEquals('FooBarTestFrontend', get_class($generated_frontend));
    }

    public function testFactoryLoadsPlatformBackend()
    {
        try {
            $cache = Zend_Cache::factory('Core', 'Zend-Platform');
        } catch (Zend_Cache_Exception $e) {
            $message = $e->getMessage();
            if (strstr((string) $message, 'Incorrect backend')) {
                $this->fail('Zend Platform is a valid backend');
            }
        }
    }

    public function testBadFrontend()
    {
        try {
            Zend_Cache::factory('badFrontend', 'File', [], [], false, false, false);
        } catch (Zend_Exception $e) {
            return;
        }
        $this->fail('Zend_Exception was expected but not thrown');
    }

    public function testBadBackend()
    {
        try {
            Zend_Cache::factory('Output', 'badBackend', [], [], false, false, false);
        } catch (Zend_Exception $e) {
            return;
        }
        $this->fail('Zend_Exception was expected but not thrown');
    }

    /**
     * @group ZF-11988
     */
    public function testNamespacedFrontendClassAccepted()
    {
        try {
            Zend_Cache::factory('ZF11988\Frontend', 'File', [], [], true, false, false);
            $this->fail('Zend_Cache_Exception was expected but not thrown');
        } catch (Zend_Cache_Exception $e) {
            $this->assertNotEquals('Invalid frontend name [ZF11988\Frontend]', $e->getMessage());
        }
    }

    /**
     * @group ZF-11988
     */
    public function testNamespacedBackendClassAccepted()
    {
        try {
            Zend_Cache::factory('Output', 'ZF11988\Backend', [], [], false, true, false);
            $this->fail('Zend_Cache_Exception was expected but not thrown');
        } catch (Zend_Cache_Exception $e) {
            $this->assertNotEquals('Invalid backend name [ZF11988\Backend]', $e->getMessage());
        }
    }
}
