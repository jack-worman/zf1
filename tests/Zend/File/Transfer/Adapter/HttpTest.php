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

// Call Zend_File_Transfer_Adapter_HttpTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_File_Transfer_Adapter_HttpTest::main');
}

/**
 * Test class for Zend_File_Transfer_Adapter_Http.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
#[AllowDynamicProperties]
class Zend_File_Transfer_Adapter_HttpTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_File_Transfer_Adapter_HttpTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $_FILES = [
            'txt' => [
                'name' => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'test.txt',
                'type' => 'plain/text',
                'size' => 8,
                'tmp_name' => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'test.txt',
                'error' => 0]];
        $this->adapter = new Zend_File_Transfer_Adapter_HttpTest_MockAdapter();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
    }

    public function testEmptyAdapter()
    {
        $files = $this->adapter->getFileName();
        $this->assertContains('test.txt', $files);
    }

    public function testAutoSetUploadValidator()
    {
        $validators = [
            new Zend_Validate_File_Count(1),
            new Zend_Validate_File_Extension('jpg'),
        ];
        $this->adapter->setValidators($validators);
        $test = $this->adapter->getValidator('Upload');
        $this->assertTrue($test instanceof Zend_Validate_File_Upload);
    }

    /**
     * @expectedException \Zend_File_Transfer_Exception
     */
    public function testSendingFiles()
    {
        $this->adapter->send();
    }

    /**
     * @expectedException \Zend_File_Transfer_Exception
     */
    public function testFileIsSent()
    {
        $this->adapter->isSent();
    }

    public function testFileIsUploaded()
    {
        $this->assertTrue($this->adapter->isUploaded());
    }

    public function testFileIsNotUploaded()
    {
        $this->assertFalse($this->adapter->isUploaded('unknownFile'));
    }

    public function testFileIsNotFiltered()
    {
        $this->assertFalse($this->adapter->isFiltered('unknownFile'));
        $this->assertFalse($this->adapter->isFiltered());
    }

    public function testFileIsNotReceived()
    {
        $this->assertFalse($this->adapter->isReceived('unknownFile'));
        $this->assertFalse($this->adapter->isReceived());
    }

    public function testReceiveUnknownFile()
    {
        try {
            $this->assertFalse($this->adapter->receive('unknownFile'));
        } catch (Zend_File_Transfer_Exception $e) {
            $this->assertContains('not find', $e->getMessage());
        }
    }

    /**
     * @group ZF-12451
     */
    public function testReceiveEmptyArray()
    {
        $_SERVER['CONTENT_LENGTH'] = 10;
        $_FILES = [];

        $adapter = new Zend_File_Transfer_Adapter_Http();
        $this->assertFalse($adapter->receive([]));
    }

    public function testReceiveValidatedFile()
    {
        $_FILES = [
            'txt' => [
                'name' => 'unknown.txt',
                'type' => 'plain/text',
                'size' => 8,
                'tmp_name' => 'unknown.txt',
                'error' => 0]];
        $adapter = new Zend_File_Transfer_Adapter_HttpTest_MockAdapter();
        $this->assertFalse($adapter->receive());
    }

    public function testReceiveIgnoredFile()
    {
        $this->adapter->setOptions(['ignoreNoFile' => true]);
        $this->assertTrue($this->adapter->receive());
    }

    public function testReceiveWithRenameFilter()
    {
        $this->adapter->addFilter('Rename', ['target' => '/testdir']);
        $this->adapter->setOptions(['ignoreNoFile' => true]);
        $this->assertTrue($this->adapter->receive());
    }

    public function testReceiveWithRenameFilterButWithoutDirectory()
    {
        $this->adapter->setDestination(__DIR__);
        $this->adapter->addFilter('Rename', ['overwrite' => false]);
        $this->adapter->setOptions(['ignoreNoFile' => true]);
        $this->assertTrue($this->adapter->receive());
    }

    public function testMultiFiles()
    {
        $_FILES = [
            'txt' => [
                'name' => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'test.txt',
                'type' => 'plain/text',
                'size' => 8,
                'tmp_name' => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'test.txt',
                'error' => 0],
            'exe' => [
                'name' => [
                    0 => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'file1.txt',
                    1 => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'file2.txt'],
                'type' => [
                    0 => 'plain/text',
                    1 => 'plain/text'],
                'size' => [
                    0 => 8,
                    1 => 8],
                'tmp_name' => [
                    0 => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'file1.txt',
                    1 => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'file2.txt'],
                'error' => [
                    0 => 0,
                    1 => 0]]];
        $adapter = new Zend_File_Transfer_Adapter_HttpTest_MockAdapter();
        $adapter->setOptions(['ignoreNoFile' => true]);
        $this->assertTrue($adapter->receive('exe'));
        $this->assertEquals(
            ['exe_0_' => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'file1.txt',
                'exe_1_' => __DIR__.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR.'file2.txt'],
            $adapter->getFileName('exe', false));
    }

    public function testValidationOfPhpExtendsFormError()
    {
        $_SERVER['CONTENT_LENGTH'] = 10;

        $_FILES = [];
        $adapter = new Zend_File_Transfer_Adapter_HttpTest_MockAdapter();
        $this->assertFalse($adapter->isValidParent());
        $this->assertContains('exceeds the defined ini size', current($adapter->getMessages()));
    }
}

#[AllowDynamicProperties]
class Zend_File_Transfer_Adapter_HttpTest_MockAdapter extends Zend_File_Transfer_Adapter_Http
{
    public function __construct()
    {
        self::$_callbackApc = ['Zend_File_Transfer_Adapter_HttpTest_MockAdapter', 'apcTest'];
        parent::__construct();
    }

    public function isValid($files = null)
    {
        return true;
    }

    public function isValidParent($files = null)
    {
        return parent::isValid($files);
    }

    public static function isApcAvailable()
    {
        return true;
    }

    public static function apcTest($id)
    {
        return ['total' => 100, 'current' => 100, 'rate' => 10];
    }

    public static function uPTest($id)
    {
        return ['bytes_total' => 100, 'bytes_uploaded' => 100, 'speed_average' => 10, 'cancel_upload' => true];
    }

    public function switchApcToUP()
    {
        self::$_callbackApc = null;
        self::$_callbackUploadProgress = ['Zend_File_Transfer_Adapter_HttpTest_MockAdapter', 'uPTest'];
    }
}

// Call Zend_File_Transfer_Adapter_HttpTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_File_Transfer_Adapter_HttpTest::main') {
    Zend_File_Transfer_Adapter_HttpTest::main();
}
