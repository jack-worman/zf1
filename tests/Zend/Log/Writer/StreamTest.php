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
    define('PHPUnit_MAIN_METHOD', 'Zend_Log_Writer_StreamTest::main');
}

/** Zend_Log */
// require_once 'Zend/Log.php';

/** Zend_Log_Writer_Stream */
// require_once 'Zend/Log/Writer/Stream.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Log
 */
#[AllowDynamicProperties]
class Zend_Log_Writer_StreamTest extends PHPUnit\Framework\TestCase
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

    public function testConstructorThrowsWhenResourceIsNotStream()
    {
        $resource = xml_parser_create();
        try {
            new Zend_Log_Writer_Stream($resource);
            $this->fail();
        } catch (Throwable $e) {
            $this->assertTrue($e instanceof Zend_Log_Exception);
            $this->assertMatchesRegularExpression('/not a stream/i', $e->getMessage());
        }
        xml_parser_free($resource);
    }

    public function testConstructorWithValidStream()
    {
        $stream = fopen('php://memory', 'w+');
        new Zend_Log_Writer_Stream($stream);
    }

    public function testConstructorWithValidUrl()
    {
        new Zend_Log_Writer_Stream('php://memory');
    }

    public function testConstructorThrowsWhenModeSpecifiedForExistingStream()
    {
        $stream = fopen('php://memory', 'w+');
        try {
            new Zend_Log_Writer_Stream($stream, 'w+');
            $this->fail();
        } catch (Throwable $e) {
            $this->assertTrue($e instanceof Zend_Log_Exception);
            $this->assertMatchesRegularExpression('/existing stream/i', $e->getMessage());
        }
    }

    public function testConstructorThrowsWhenStreamCannotBeOpened()
    {
        try {
            new Zend_Log_Writer_Stream('');
            $this->fail();
        } catch (Throwable $e) {
            $this->assertTrue($e instanceof Zend_Log_Exception);
            $this->assertMatchesRegularExpression('/cannot be opened/i', $e->getMessage());
        }
    }

    public function testWrite()
    {
        $stream = fopen('php://memory', 'w+');
        $fields = ['message' => 'message-to-log'];

        $writer = new Zend_Log_Writer_Stream($stream);
        $writer->write($fields);

        rewind($stream);
        $contents = stream_get_contents($stream);
        fclose($stream);

        $this->assertStringContainsString($fields['message'], $contents);
    }

    public function testWriteThrowsWhenStreamWriteFails()
    {
        $stream = fopen('php://memory', 'w+');
        $writer = new Zend_Log_Writer_Stream($stream);
        fclose($stream);

        try {
            $writer->write(['message' => 'foo']);
            $this->fail();
        } catch (Throwable $e) {
            $this->assertTrue($e instanceof Zend_Log_Exception);
            $this->assertMatchesRegularExpression('/unable to write/i', $e->getMessage());
        }
    }

    public function testShutdownClosesStreamResource()
    {
        $writer = new Zend_Log_Writer_Stream('php://memory', 'w+');
        $writer->write(['message' => 'this write should succeed']);

        $writer->shutdown();

        try {
            $writer->write(['message' => 'this write should fail']);
            $this->fail();
        } catch (Throwable $e) {
            $this->assertTrue($e instanceof Zend_Log_Exception);
            $this->assertMatchesRegularExpression('/unable to write/i', $e->getMessage());
        }
    }

    public function testSettingNewFormatter()
    {
        $stream = fopen('php://memory', 'w+');
        $writer = new Zend_Log_Writer_Stream($stream);
        $expected = 'foo';

        $formatter = new Zend_Log_Formatter_Simple($expected);
        $writer->setFormatter($formatter);

        $writer->write(['bar' => 'baz']);
        rewind($stream);
        $contents = stream_get_contents($stream);
        fclose($stream);

        $this->assertStringContainsString($expected, $contents);
    }

    public function testFactoryStream()
    {
        $cfg = ['log' => ['memory' => [
            'writerName' => 'Mock',
            'writerParams' => [
                'stream' => 'php://memory',
                'mode' => 'a',
            ],
        ]]];

        $logger = Zend_Log::factory($cfg['log']);
        $this->assertTrue($logger instanceof Zend_Log);
    }

    public function testFactoryUrl()
    {
        $cfg = ['log' => ['memory' => [
            'writerName' => 'Mock',
            'writerParams' => [
                'url' => 'http://localhost',
                'mode' => 'a',
            ],
        ]]];

        $logger = Zend_Log::factory($cfg['log']);
        $this->assertTrue($logger instanceof Zend_Log);
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Log_Writer_StreamTest::main') {
    Zend_Log_Writer_StreamTest::main();
}
