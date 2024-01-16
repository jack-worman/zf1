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
class Zend_Gdata_Spreadsheets_CellEntryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cellEntry = new Zend_Gdata_Spreadsheets_CellEntry();
    }

    public function testToAndFromString()
    {
        $this->cellEntry->setCell(new Zend_Gdata_Spreadsheets_Extension_Cell('my cell', '1', '2', 'input value', 'numeric value'));
        $this->assertTrue('my cell' == $this->cellEntry->getCell()->getText());
        $this->assertTrue('1' == $this->cellEntry->getCell()->getRow());
        $this->assertTrue('2' == $this->cellEntry->getCell()->getColumn());
        $this->assertTrue('input value' == $this->cellEntry->getCell()->getInputValue());
        $this->assertTrue('numeric value' == $this->cellEntry->getCell()->getNumericValue());

        $newCellEntry = new Zend_Gdata_Spreadsheets_CellEntry();
        $doc = new DOMDocument();
        $doc->loadXML($this->cellEntry->saveXML());
        $newCellEntry->transferFromDom($doc->documentElement);

        $this->assertTrue($this->cellEntry->getCell()->getText() == $newCellEntry->getCell()->getText());
        $this->assertTrue($this->cellEntry->getCell()->getRow() == $newCellEntry->getCell()->getRow());
        $this->assertTrue($this->cellEntry->getCell()->getColumn() == $newCellEntry->getCell()->getColumn());
        $this->assertTrue($this->cellEntry->getCell()->getInputValue() == $newCellEntry->getCell()->getInputValue());
        $this->assertTrue($this->cellEntry->getCell()->getNumericValue() == $newCellEntry->getCell()->getNumericValue());
    }
}
