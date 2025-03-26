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
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Application_Resource_LogTest::main');
}

/**
 * Zend_Loader_Autoloader.
 */
// require_once 'Zend/Loader/Autoloader.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Application
 */
#[AllowDynamicProperties]
class Zend_Application_Resource_LogTest extends PHPUnit\Framework\TestCase
{
    public static function main()
    {
        $suite = PHPUnit\Framework\TestSuite::empty(__CLASS__);
        (new PHPUnit\TextUI\TestRunner())->run(
            PHPUnit\TextUI\Configuration\Registry::get(),
            new PHPUnit\Runner\ResultCache\NullResultCache(),
            $suite,
        );
    }

    public function setUp(): void
    {
        // Store original autoloaders
        $this->loaders = spl_autoload_functions();
        if (!is_array($this->loaders)) {
            // spl_autoload_functions does not return empty array when no
            // autoloaders registered...
            $this->loaders = [];
        }

        Zend_Loader_Autoloader::resetInstance();
        $this->autoloader = Zend_Loader_Autoloader::getInstance();
        $this->application = new Zend_Application('testing');
        $this->bootstrap = new Zend_Application_Bootstrap_Bootstrap($this->application);

        Zend_Controller_Front::getInstance()->resetInstance();
    }

    public function tearDown(): void
    {
        // Restore original autoloaders
        $loaders = spl_autoload_functions();
        foreach ($loaders as $loader) {
            spl_autoload_unregister($loader);
        }

        foreach ($this->loaders as $loader) {
            spl_autoload_register($loader);
        }

        // Reset autoloader instance so it doesn't affect other tests
        Zend_Loader_Autoloader::resetInstance();
    }

    public function testInitializationInitializesLogObject()
    {
        $resource = new Zend_Application_Resource_Log([]);
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions([
            'Mock' => ['writerName' => 'Mock'],
        ]);
        $resource->init();
        $this->assertTrue($resource->getLog() instanceof Zend_Log);
    }

    public function testInitializationReturnsLogObject()
    {
        $resource = new Zend_Application_Resource_Log([]);
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions([
            'Mock' => ['writerName' => 'Mock'],
        ]);
        $test = $resource->init();
        $this->assertTrue($test instanceof Zend_Log);
    }

    public function testOptionsPassedToResourceAreUsedToInitializeLog()
    {
        $stream = fopen('php://memory', 'w+', false);
        $options = ['memory' => [
            'writerName' => 'Stream',
            'writerParams' => [
                'stream' => $stream,
            ],
        ]];

        $resource = new Zend_Application_Resource_Log($options);
        $resource->setBootstrap($this->bootstrap);
        $resource->init();

        $log = $resource->getLog();
        $this->assertTrue($log instanceof Zend_Log);

        $log->log($message = 'logged-message', Zend_Log::INFO);
        rewind($stream);
        $this->assertStringContainsString($message, stream_get_contents($stream));
    }

    /**
     * @group ZF-8602
     */
    public function testNumericLogStreamFilterParamsPriorityDoesNotFail()
    {
        $options = [
            'stream' => [
                'writerName' => 'Stream',
                'writerParams' => [
                    'stream' => 'php://memory',
                    'mode' => 'a',
                ],
                'filterName' => 'Priority',
                'filterParams' => [
                    'priority' => '4',
                ],
            ],
        ];
        $resource = new Zend_Application_Resource_Log($options);
        $resource->setBootstrap($this->bootstrap);
        $resource->init();
    }

    /**
     * @group ZF-9790
     */
    public function testInitializationWithFilterAndFormatter()
    {
        $stream = fopen('php://memory', 'w+');
        $options = [
            'memory' => [
                'writerName' => 'Stream',
                'writerParams' => [
                    'stream' => $stream,
                ],
                'filterName' => 'Priority',
                'filterParams' => [
                    'priority' => Zend_Log::INFO,
                ],
                'formatterName' => 'Simple',
                'formatterParams' => [
                    'format' => '%timestamp%: %message%',
                ],
            ],
        ];
        $message = 'tottakai';

        $resource = new Zend_Application_Resource_Log($options);
        $resource->setBootstrap($this->bootstrap);
        $log = $resource->init();

        $this->assertTrue($log instanceof Zend_Log);

        $log->log($message, Zend_Log::INFO);
        rewind($stream);
        $contents = stream_get_contents($stream);

        $this->assertStringEndsWith($message, $contents);
        $this->assertMatchesRegularExpression('/\d\d:\d\d:\d\d/', $contents);
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Application_Resource_LogTest::main') {
    Zend_Application_Resource_LogTest::main();
}
