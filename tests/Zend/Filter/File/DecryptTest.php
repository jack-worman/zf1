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

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Filter
 */
#[AllowDynamicProperties]
class Zend_Filter_File_DecryptTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!extension_loaded('mcrypt')) {
            $this->markTestSkipped('This filter needs the mcrypt extension');
        }

        if (PHP_VERSION_ID >= 70100) {
            $this->markTestSkipped('mcrypt is deprecated in php 7.1');
        }

        if (file_exists((string) __DIR__.'/../_files/newencryption.txt')) {
            unlink(__DIR__.'/../_files/newencryption.txt');
        }

        if (file_exists((string) __DIR__.'/../_files/newencryption2.txt')) {
            unlink(__DIR__.'/../_files/newencryption2.txt');
        }
    }

    public function tearDown()
    {
        if (file_exists((string) __DIR__.'/../_files/newencryption.txt')) {
            unlink(__DIR__.'/../_files/newencryption.txt');
        }

        if (file_exists((string) __DIR__.'/../_files/newencryption2.txt')) {
            unlink(__DIR__.'/../_files/newencryption2.txt');
        }
    }

    /**
     * Ensures that the filter follows expected behavior.
     *
     * @return void
     */
    public function testBasic()
    {
        $filter = new Zend_Filter_File_Encrypt();
        $filter->setFilename(__DIR__.'/../_files/newencryption.txt');

        $this->assertEquals(
            __DIR__.'/../_files/newencryption.txt',
            $filter->getFilename());

        $filter->setVector('testvect');
        $filter->filter(__DIR__.'/../_files/encryption.txt');

        $filter = new Zend_Filter_File_Decrypt();

        $this->assertNotEquals(
            'Encryption',
            file_get_contents(__DIR__.'/../_files/newencryption.txt'));

        $filter->setVector('testvect');
        $this->assertEquals(
            __DIR__.'/../_files/newencryption.txt',
            $filter->filter(__DIR__.'/../_files/newencryption.txt'));

        $this->assertEquals(
            'Encryption',
            \trim((string) file_get_contents(__DIR__.'/../_files/newencryption.txt')));
    }

    public function testEncryptionWithDecryption()
    {
        $filter = new Zend_Filter_File_Encrypt();
        $filter->setFilename(__DIR__.'/../_files/newencryption.txt');
        $filter->setVector('testvect');
        $this->assertEquals(__DIR__.'/../_files/newencryption.txt',
            $filter->filter(__DIR__.'/../_files/encryption.txt'));

        $this->assertNotEquals(
            'Encryption',
            file_get_contents(__DIR__.'/../_files/newencryption.txt'));

        $filter = new Zend_Filter_File_Decrypt();
        $filter->setFilename(__DIR__.'/../_files/newencryption2.txt');

        $this->assertEquals(
            __DIR__.'/../_files/newencryption2.txt',
            $filter->getFilename());

        $filter->setVector('testvect');
        $input = $filter->filter(__DIR__.'/../_files/newencryption.txt');
        $this->assertEquals(__DIR__.'/../_files/newencryption2.txt', $input);

        $this->assertEquals(
            'Encryption',
            \trim((string) file_get_contents(__DIR__.'/../_files/newencryption2.txt')));
    }

    /**
     * @return void
     */
    public function testNonExistingFile()
    {
        $filter = new Zend_Filter_File_Decrypt();
        $filter->setVector('testvect');

        try {
            $filter->filter(__DIR__.'/../_files/nofile.txt');
            $this->fail();
        } catch (Zend_Filter_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
    }
}
