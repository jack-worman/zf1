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
 * @version    $Id $
 */

// require_once 'Zend/Gdata/Spreadsheets.php';
// require_once 'Zend/Http/Client.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Spreadsheets
 */
#[AllowDynamicProperties]
class Zend_Gdata_Spreadsheets_CellTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cell = new Zend_Gdata_Spreadsheets_Extension_Cell();
    }

    public function testToAndFromString()
    {
        $this->cell->setText('test cell');
        $this->assertTrue('test cell' == $this->cell->getText());
        $this->cell->setRow('1');
        $this->assertTrue('1' == $this->cell->getRow());
        $this->cell->setColumn('2');
        $this->assertTrue('2' == $this->cell->getColumn());
        $this->cell->setInputValue('test input value');
        $this->assertTrue('test input value' == $this->cell->getInputValue());
        $this->cell->setNumericValue('test numeric value');
        $this->assertTrue('test numeric value' == $this->cell->getNumericValue());

        $newCell = new Zend_Gdata_Spreadsheets_Extension_Cell();
        $doc = new DOMDocument();
        $doc->loadXML($this->cell->saveXML());
        $newCell->transferFromDom($doc->documentElement);
        $this->assertTrue($this->cell->getText() == $newCell->getText());
        $this->assertTrue($this->cell->getRow() == $newCell->getRow());
        $this->assertTrue($this->cell->getColumn() == $newCell->getColumn());
        $this->assertTrue($this->cell->getInputValue() == $newCell->getInputValue());
        $this->assertTrue($this->cell->getNumericValue() == $newCell->getNumericValue());
    }
}
