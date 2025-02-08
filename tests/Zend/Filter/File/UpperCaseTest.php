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
 * @see Zend_Filter_File_UpperCase
 */
// require_once 'Zend/Filter/File/UpperCase.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Filter
 */
#[AllowDynamicProperties]
class Zend_Filter_File_UpperCaseTest extends PHPUnit\Framework\TestCase
{
    /**
     * Path to test files.
     *
     * @var string
     */
    protected $_filesPath;

    /**
     * Original testfile.
     *
     * @var string
     */
    protected $_origFile;

    /**
     * Testfile.
     *
     * @var string
     */
    protected $_newFile;

    /**
     * Sets the path to test files.
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->_filesPath = __DIR__.DIRECTORY_SEPARATOR
                          .'..'.DIRECTORY_SEPARATOR.'_files'.DIRECTORY_SEPARATOR;
        $this->_origFile = $this->_filesPath.'testfile2.txt';
        $this->_newFile = $this->_filesPath.'newtestfile2.txt';
    }

    /**
     * Sets the path to test files.
     */
    public function setUp(): void
    {
        if (!file_exists((string) $this->_newFile)) {
            copy($this->_origFile, $this->_newFile);
        }
    }

    /**
     * Sets the path to test files.
     */
    public function tearDown(): void
    {
        if (file_exists((string) $this->_newFile)) {
            unlink($this->_newFile);
        }
    }

    /**
     * @return void
     */
    public function testInstanceCreationAndNormalWorkflow()
    {
        $this->assertStringContainsString('This is a File', file_get_contents($this->_newFile));
        $filter = new Zend_Filter_File_UpperCase();
        $filter->filter($this->_newFile);
        $this->assertStringContainsString('THIS IS A FILE', file_get_contents($this->_newFile));
    }

    /**
     * @return void
     */
    public function testFileNotFoundException()
    {
        try {
            $filter = new Zend_Filter_File_UpperCase();
            $filter->filter($this->_newFile.'unknown');
            $this->fail('Unknown file exception expected');
        } catch (Zend_Filter_Exception $e) {
            $this->assertStringContainsString('not found', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testCheckSettingOfEncodingInIstance()
    {
        $this->assertStringContainsString('This is a File', file_get_contents($this->_newFile));
        try {
            $filter = new Zend_Filter_File_UpperCase('ISO-8859-1');
            $filter->filter($this->_newFile);
            $this->assertStringContainsString('THIS IS A FILE', file_get_contents($this->_newFile));
        } catch (Zend_Filter_Exception $e) {
            $this->assertStringContainsString('mbstring is required', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testCheckSettingOfEncodingWithMethod()
    {
        $this->assertStringContainsString('This is a File', file_get_contents($this->_newFile));
        try {
            $filter = new Zend_Filter_File_UpperCase();
            $filter->setEncoding('ISO-8859-1');
            $filter->filter($this->_newFile);
            $this->assertStringContainsString('THIS IS A FILE', file_get_contents($this->_newFile));
        } catch (Zend_Filter_Exception $e) {
            $this->assertStringContainsString('mbstring is required', $e->getMessage());
        }
    }
}
