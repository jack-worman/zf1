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
 * @see Zend_Filter_Encrypt
 */
// require_once 'Zend/Filter/File/Encrypt.php';
// require_once 'Zend/Filter/File/Decrypt.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Filter
 */
#[AllowDynamicProperties]
class Zend_Filter_File_EncryptTest extends PHPUnit\Framework\TestCase
{
    public function setUp(): void
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
    }

    public function tearDown(): void
    {
        if (file_exists((string) __DIR__.'/../_files/newencryption.txt')) {
            unlink(__DIR__.'/../_files/newencryption.txt');
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
        $this->assertEquals(__DIR__.'/../_files/newencryption.txt',
            $filter->filter(__DIR__.'/../_files/encryption.txt'));

        $this->assertEquals(
            'Encryption',
            file_get_contents(__DIR__.'/../_files/encryption.txt'));

        $this->assertNotEquals(
            'Encryption',
            file_get_contents(__DIR__.'/../_files/newencryption.txt'));
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
        $filter->setVector('testvect');
        $input = $filter->filter(__DIR__.'/../_files/newencryption.txt');
        $this->assertEquals(__DIR__.'/../_files/newencryption.txt', $input);

        $this->assertEquals(
            'Encryption',
            \trim((string) file_get_contents(__DIR__.'/../_files/newencryption.txt')));
    }

    /**
     * @return void
     */
    public function testNonExistingFile()
    {
        $filter = new Zend_Filter_File_Encrypt();
        $filter->setVector('testvect');

        try {
            $filter->filter(__DIR__.'/../_files/nofile.txt');
            $this->fail();
        } catch (Zend_Filter_Exception $e) {
            $this->assertStringContainsString('not found', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testEncryptionInSameFile()
    {
        $filter = new Zend_Filter_File_Encrypt();
        $filter->setVector('testvect');

        copy(__DIR__.'/../_files/encryption.txt', __DIR__.'/../_files/newencryption.txt');
        $filter->filter(__DIR__.'/../_files/newencryption.txt');

        $this->assertNotEquals(
            'Encryption',
            \trim((string) file_get_contents(__DIR__.'/../_files/newencryption.txt')));
    }
}
