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

// Call Zend_Validate_File_NotExistsTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Validate_File_NotExistsTest::main');
}

/**
 * @see Zend_Validate_File_Size
 */
// require_once 'Zend/Validate/File/NotExists.php';

/**
 * NotExists testbed.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Validate
 */
#[AllowDynamicProperties]
class Zend_Validate_File_NotExistsTest extends PHPUnit\Framework\TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite = PHPUnit\Framework\TestSuite::empty('Zend_Validate_File_NotExistsTest');
        (new PHPUnit\TextUI\TestRunner())->run(
            PHPUnit\TextUI\Configuration\Registry::get(),
            new PHPUnit\Runner\ResultCache\NullResultCache(),
            $suite,
        );
    }

    /**
     * Ensures that the validator follows expected behavior.
     *
     * @return void
     */
    public function testBasic()
    {
        $baseDir = __DIR__;
        $valuesExpected = [
            [$baseDir, 'testsize.mo', true],
            [$baseDir.'/_files', 'testsize.mo', false],
        ];

        $files = [
            'name' => 'testsize.mo',
            'type' => 'text',
            'size' => 200,
            'tmp_name' => __DIR__.'/_files/testsize.mo',
            'error' => 0,
        ];

        foreach ($valuesExpected as $element) {
            $validator = new Zend_Validate_File_NotExists($element[0]);
            $this->assertEquals(
                $element[2],
                $validator->isValid($element[1]),
                'Tested with '.var_export($element, 1)
            );
            $this->assertEquals(
                $element[2],
                $validator->isValid($element[1], $files),
                'Tested with '.var_export($element, 1)
            );
        }

        $valuesExpected = [
            [$baseDir, 'testsize.mo', true, false],
            [$baseDir.'/_files', 'testsize.mo', false, false],
        ];

        $files = [
            'name' => 'testsize.mo',
            'type' => 'text',
            'size' => 200,
            'tmp_name' => __DIR__.'/_files/testsize.mo',
            'error' => 0,
            'destination' => __DIR__.'/_files',
        ];

        foreach ($valuesExpected as $element) {
            $validator = new Zend_Validate_File_NotExists($element[0]);
            $this->assertEquals(
                $element[2],
                $validator->isValid($element[1]),
                'Tested with '.var_export($element, 1)
            );
            $this->assertEquals(
                $element[3],
                $validator->isValid($element[1], $files),
                'Tested with '.var_export($element, 1)
            );
        }

        $valuesExpected = [
            [$baseDir, 'testsize.mo', false, false],
            [$baseDir.'/_files', 'testsize.mo', false, false],
        ];

        foreach ($valuesExpected as $element) {
            $validator = new Zend_Validate_File_NotExists();
            $this->assertEquals(
                $element[2],
                $validator->isValid($element[1]),
                'Tested with '.var_export($element, 1)
            );
            $this->assertEquals(
                $element[3],
                $validator->isValid($element[1], $files),
                'Tested with '.var_export($element, 1)
            );
        }
    }

    /**
     * Ensures that getDirectory() returns expected value.
     *
     * @return void
     */
    public function testGetDirectory()
    {
        $validator = new Zend_Validate_File_NotExists('C:/temp');
        $this->assertEquals('C:/temp', $validator->getDirectory());

        $validator = new Zend_Validate_File_NotExists(['temp', 'dir', 'jpg']);
        $this->assertEquals('temp,dir,jpg', $validator->getDirectory());

        $validator = new Zend_Validate_File_NotExists(['temp', 'dir', 'jpg']);
        $this->assertEquals(['temp', 'dir', 'jpg'], $validator->getDirectory(true));
    }

    /**
     * Ensures that setDirectory() returns expected value.
     *
     * @return void
     */
    public function testSetDirectory()
    {
        $validator = new Zend_Validate_File_NotExists('temp');
        $validator->setDirectory('gif');
        $this->assertEquals('gif', $validator->getDirectory());
        $this->assertEquals(['gif'], $validator->getDirectory(true));

        $validator->setDirectory('jpg, temp');
        $this->assertEquals('jpg,temp', $validator->getDirectory());
        $this->assertEquals(['jpg', 'temp'], $validator->getDirectory(true));

        $validator->setDirectory(['zip', 'ti']);
        $this->assertEquals('zip,ti', $validator->getDirectory());
        $this->assertEquals(['zip', 'ti'], $validator->getDirectory(true));
    }

    /**
     * Ensures that addDirectory() returns expected value.
     *
     * @return void
     */
    public function testAddDirectory()
    {
        $validator = new Zend_Validate_File_NotExists('temp');
        $validator->addDirectory('gif');
        $this->assertEquals('temp,gif', $validator->getDirectory());
        $this->assertEquals(['temp', 'gif'], $validator->getDirectory(true));

        $validator->addDirectory('jpg, to');
        $this->assertEquals('temp,gif,jpg,to', $validator->getDirectory());
        $this->assertEquals(['temp', 'gif', 'jpg', 'to'], $validator->getDirectory(true));

        $validator->addDirectory(['zip', 'ti']);
        $this->assertEquals('temp,gif,jpg,to,zip,ti', $validator->getDirectory());
        $this->assertEquals(['temp', 'gif', 'jpg', 'to', 'zip', 'ti'], $validator->getDirectory(true));

        $validator->addDirectory('');
        $this->assertEquals('temp,gif,jpg,to,zip,ti', $validator->getDirectory());
        $this->assertEquals(['temp', 'gif', 'jpg', 'to', 'zip', 'ti'], $validator->getDirectory(true));
    }
}

// Call Zend_Validate_File_NotExistsTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_Validate_File_NotExistsTest::main') {
    Zend_Validate_File_NotExistsTest::main();
}
