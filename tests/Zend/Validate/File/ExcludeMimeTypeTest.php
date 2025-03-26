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

// Call Zend_Validate_File_ExcludeMimeTypeTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Validate_File_ExcludeMimeTypeTest::main');
}

/**
 * @see Zend_Validate_File_ExcludeMimeType
 */
// require_once 'Zend/Validate/File/ExcludeMimeType.php';

/**
 * ExcludeMimeType testbed.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Validate
 */
#[AllowDynamicProperties]
class Zend_Validate_File_ExcludeMimeTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_Validate_File_ExcludeMimeTypeTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Ensures that the validator follows expected behavior.
     *
     * @return void
     */
    public function testBasic()
    {
        $valuesExpected = [
            ['image/gif', true],
            ['image/jpeg', false],
            ['image', false],
            ['test/notype', true],
            ['image/gif, image/jpeg', false],
            [['image/vasa', 'image/jpeg'], false],
            [['image/gif', 'jpeg'], false],
            [['image/gif', 'gif'], true],
        ];

        $filetest = __DIR__.'/_files/picture.jpg';
        $files = [
            'name' => 'picture.jpg',
            'type' => 'image/jpeg',
            'size' => 200,
            'tmp_name' => $filetest,
            'error' => 0,
        ];

        foreach ($valuesExpected as $element) {
            $validator = new Zend_Validate_File_ExcludeMimeType($element[0]);
            $validator->enableHeaderCheck();
            $this->assertEquals(
                $element[1],
                $validator->isValid($filetest, $files),
                'Tested with '.var_export($element, 1)
            );
        }
    }

    /**
     * Ensures that getMimeType() returns expected value.
     *
     * @return void
     */
    public function testGetMimeType()
    {
        $validator = new Zend_Validate_File_ExcludeMimeType('image/gif');
        $this->assertEquals('image/gif', $validator->getMimeType());

        $validator = new Zend_Validate_File_ExcludeMimeType(['image/gif', 'video', 'text/test']);
        $this->assertEquals('image/gif,video,text/test', $validator->getMimeType());

        $validator = new Zend_Validate_File_ExcludeMimeType(['image/gif', 'video', 'text/test']);
        $this->assertEquals(['image/gif', 'video', 'text/test'], $validator->getMimeType(true));
    }

    /**
     * Ensures that setMimeType() returns expected value.
     *
     * @return void
     */
    public function testSetMimeType()
    {
        $validator = new Zend_Validate_File_ExcludeMimeType('image/gif');
        $validator->setMimeType('image/jpeg');
        $this->assertEquals('image/jpeg', $validator->getMimeType());
        $this->assertEquals(['image/jpeg'], $validator->getMimeType(true));

        $validator->setMimeType('image/gif, text/test');
        $this->assertEquals('image/gif,text/test', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text/test'], $validator->getMimeType(true));

        $validator->setMimeType(['video/mpeg', 'gif']);
        $this->assertEquals('video/mpeg,gif', $validator->getMimeType());
        $this->assertEquals(['video/mpeg', 'gif'], $validator->getMimeType(true));
    }

    /**
     * Ensures that addMimeType() returns expected value.
     *
     * @return void
     */
    public function testAddMimeType()
    {
        $validator = new Zend_Validate_File_ExcludeMimeType('image/gif');
        $validator->addMimeType('text');
        $this->assertEquals('image/gif,text', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text'], $validator->getMimeType(true));

        $validator->addMimeType('jpg, to');
        $this->assertEquals('image/gif,text,jpg,to', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text', 'jpg', 'to'], $validator->getMimeType(true));

        $validator->addMimeType(['zip', 'ti']);
        $this->assertEquals('image/gif,text,jpg,to,zip,ti', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text', 'jpg', 'to', 'zip', 'ti'], $validator->getMimeType(true));

        $validator->addMimeType('');
        $this->assertEquals('image/gif,text,jpg,to,zip,ti', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text', 'jpg', 'to', 'zip', 'ti'], $validator->getMimeType(true));
    }

    /**
     * Ensure validator is not affected by PHP bug #63976.
     */
    public function testShouldHaveProperErrorMessageOnNotReadableFile()
    {
        $validator = new Zend_Validate_File_ExcludeMimeType('image/jpeg');

        $this->assertFalse($validator->isValid('notexisting'), ['name' => 'notexisting']);
        $this->assertEquals(
            ['fileExcludeMimeTypeNotReadable' => "File 'notexisting' is not readable or does not exist"],
            $validator->getMessages()
        );
    }
}

// Call Zend_Validate_File_ExcludeMimeTypeTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_Validate_File_ExcludeMimeTypeTest::main') {
    Zend_Validate_File_ExcludeMimeTypeTest::main();
}
