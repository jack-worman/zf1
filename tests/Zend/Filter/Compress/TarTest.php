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
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Filter_Compress_TarTest::main');
}

/**
 * @group      Zend_Filter
 */
#[AllowDynamicProperties]
class Zend_Filter_Compress_TarTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs this test suite.
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_Filter_Compress_TarTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp()
    {
        if (!class_exists('Archive_Tar')) {
            try {
                @Zend_Loader::loadClass('Archive_Tar');
            } catch (Zend_Exception $e) {
                $this->markTestSkipped('This filter needs PEARs Archive_Tar');
            }
        }

        $files = [
            __DIR__.'/../_files/zipextracted.txt',
            __DIR__.'/../_files/_compress/Compress/First/Second/zipextracted.txt',
            __DIR__.'/../_files/_compress/Compress/First/Second',
            __DIR__.'/../_files/_compress/Compress/First/zipextracted.txt',
            __DIR__.'/../_files/_compress/Compress/First',
            __DIR__.'/../_files/_compress/Compress/zipextracted.txt',
            __DIR__.'/../_files/_compress/Compress',
            __DIR__.'/../_files/_compress/zipextracted.txt',
            __DIR__.'/../_files/_compress',
            __DIR__.'/../_files/compressed.tar',
        ];

        foreach ($files as $file) {
            if (file_exists((string) $file)) {
                if (is_dir($file)) {
                    rmdir($file);
                } else {
                    unlink($file);
                }
            }
        }

        /*if (!file_exists((string) __DIR__ . '/../_files/Compress/First/Second')) {
            mkdir(__DIR__ . '/../_files/Compress/First/Second', 0777, true);
            file_put_contents(__DIR__ . '/../_files/Compress/First/Second/zipextracted.txt', 'compress me');
            file_put_contents(__DIR__ . '/../_files/Compress/First/zipextracted.txt', 'compress me');
            file_put_contents(__DIR__ . '/../_files/Compress/zipextracted.txt', 'compress me');
        }*/
    }

    public function tearDown()
    {
        $files = [
            __DIR__.'/../_files/zipextracted.txt',
            __DIR__.'/../_files/_compress/Compress/First/Second/zipextracted.txt',
            __DIR__.'/../_files/_compress/Compress/First/Second',
            __DIR__.'/../_files/_compress/Compress/First/zipextracted.txt',
            __DIR__.'/../_files/_compress/Compress/First',
            __DIR__.'/../_files/_compress/Compress/zipextracted.txt',
            __DIR__.'/../_files/_compress/Compress',
            __DIR__.'/../_files/_compress/zipextracted.txt',
            __DIR__.'/../_files/_compress',
            __DIR__.'/../_files/compressed.tar',
        ];

        foreach ($files as $file) {
            if (file_exists((string) $file)) {
                if (is_dir($file)) {
                    rmdir($file);
                } else {
                    unlink($file);
                }
            }
        }

        /*if (!file_exists((string) __DIR__ . '/../_files/Compress/First/Second')) {
            mkdir(__DIR__ . '/../_files/Compress/First/Second', 0777, true);
            file_put_contents(__DIR__ . '/../_files/Compress/First/Second/zipextracted.txt', 'compress me');
            file_put_contents(__DIR__ . '/../_files/Compress/First/zipextracted.txt', 'compress me');
            file_put_contents(__DIR__ . '/../_files/Compress/zipextracted.txt', 'compress me');
        }*/
    }

    /**
     * Basic usage.
     *
     * @return void
     */
    public function testBasicUsage()
    {
        $filter = new Zend_Filter_Compress_Tar(
            [
                'archive' => __DIR__.'/../_files/compressed.tar',
                'target' => __DIR__.'/../_files/zipextracted.txt',
            ]
        );

        $content = $filter->compress('compress me');
        $this->assertEquals(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'_files'
                            .DIRECTORY_SEPARATOR.'compressed.tar', $content);

        $content = $filter->decompress($content);
        $this->assertTrue($content);
        $content = file_get_contents(__DIR__.'/../_files/zipextracted.txt');
        $this->assertEquals('compress me', $content);
    }

    /**
     * Setting Options.
     *
     * @return void
     */
    public function testTarGetSetOptions()
    {
        $filter = new Zend_Filter_Compress_Tar();
        $this->assertEquals(
            [
                'archive' => null,
                'target' => '.',
                'mode' => null],
            $filter->getOptions()
        );

        $this->assertEquals(null, $filter->getOptions('archive'));

        $this->assertNull($filter->getOptions('nooption'));
        $filter->setOptions(['nooptions' => 'foo']);
        $this->assertNull($filter->getOptions('nooption'));

        $filter->setOptions(['archive' => 'temp.txt']);
        $this->assertEquals('temp.txt', $filter->getOptions('archive'));
    }

    /**
     * Setting Archive.
     *
     * @return void
     */
    public function testTarGetSetArchive()
    {
        $filter = new Zend_Filter_Compress_Tar();
        $this->assertEquals(null, $filter->getArchive());
        $filter->setArchive('Testfile.txt');
        $this->assertEquals('Testfile.txt', $filter->getArchive());
        $this->assertEquals('Testfile.txt', $filter->getOptions('archive'));
    }

    /**
     * Setting Target.
     *
     * @return void
     */
    public function testTarGetSetTarget()
    {
        $filter = new Zend_Filter_Compress_Tar();
        $this->assertEquals('.', $filter->getTarget());
        $filter->setTarget('Testfile.txt');
        $this->assertEquals('Testfile.txt', $filter->getTarget());
        $this->assertEquals('Testfile.txt', $filter->getOptions('target'));

        try {
            $filter->setTarget('/unknown/path/to/file.txt');
            $this->fail('Exception expected');
        } catch (Zend_Filter_Exception $e) {
            $this->assertContains('does not exist', $e->getMessage());
        }
    }

    /**
     * Setting Archive.
     *
     * @return void
     */
    public function testTarCompressToFile()
    {
        $filter = new Zend_Filter_Compress_Tar(
            [
                'archive' => __DIR__.'/../_files/compressed.tar',
                'target' => __DIR__.'/../_files/zipextracted.txt',
            ]
        );
        file_put_contents(__DIR__.'/../_files/zipextracted.txt', 'compress me');

        $content = $filter->compress(__DIR__.'/../_files/zipextracted.txt');
        $this->assertEquals(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'_files'
                            .DIRECTORY_SEPARATOR.'compressed.tar', $content);

        $content = $filter->decompress($content);
        $this->assertTrue($content);
        $content = file_get_contents(__DIR__.'/../_files/zipextracted.txt');
        $this->assertEquals('compress me', $content);
    }

    /**
     * Compress directory to Filename.
     *
     * @return void
     */
    public function testTarCompressDirectory()
    {
        $filter = new Zend_Filter_Compress_Tar(
            [
                'archive' => __DIR__.'/../_files/compressed.tar',
                'target' => __DIR__.'/../_files/_compress',
            ]
        );
        $content = $filter->compress(__DIR__.'/../_files/Compress');
        $this->assertEquals(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'_files'
                            .DIRECTORY_SEPARATOR.'compressed.tar', $content);
    }

    /**
     * testing toString.
     *
     * @return void
     */
    public function testTarToString()
    {
        $filter = new Zend_Filter_Compress_Tar();
        $this->assertEquals('Tar', $filter->toString());
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Filter_Compress_TarTest::main') {
    Zend_Filter_Compress_TarTest::main();
}
