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
class Zend_Gdata_Spreadsheets_CellFeedTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cellFeed = new Zend_Gdata_Spreadsheets_CellFeed(
            file_get_contents('Zend/Gdata/Spreadsheets/_files/TestDataCellFeedSample1.xml', true),
            true);
    }

    public function testToAndFromString()
    {
        $this->assertTrue(2 == count($this->cellFeed->entries));
        $this->assertTrue(2 == $this->cellFeed->entries->count());

        foreach ($this->cellFeed->entries as $entry) {
            $this->assertTrue($entry instanceof Zend_Gdata_Spreadsheets_CellEntry);
        }
        $this->assertTrue($this->cellFeed->getRowCount() instanceof Zend_Gdata_Spreadsheets_Extension_RowCount);
        $this->assertTrue('100' == $this->cellFeed->getRowCount()->getText());
        $this->assertTrue($this->cellFeed->getColumnCount() instanceof Zend_Gdata_Spreadsheets_Extension_ColCount);
        $this->assertTrue('20' == $this->cellFeed->getColumnCount()->getText());

        $newCellFeed = new Zend_Gdata_Spreadsheets_CellFeed();
        $doc = new DOMDocument();
        $doc->loadXML($this->cellFeed->saveXML());
        $newCellFeed->transferFromDom($doc->documentElement);

        $this->assertTrue(2 == count($newCellFeed->entries));
        $this->assertTrue(2 == $newCellFeed->entries->count());

        foreach ($newCellFeed->entries as $entry) {
            $this->assertTrue($entry instanceof Zend_Gdata_Spreadsheets_CellEntry);
        }
        $this->assertTrue($newCellFeed->getRowCount() instanceof Zend_Gdata_Spreadsheets_Extension_RowCount);
        $this->assertTrue('100' == $newCellFeed->getRowCount()->getText());
        $this->assertTrue($newCellFeed->getColumnCount() instanceof Zend_Gdata_Spreadsheets_Extension_ColCount);
        $this->assertTrue('20' == $newCellFeed->getColumnCount()->getText());
    }

    public function testGetSetCounts()
    {
        $newRowCount = new Zend_Gdata_Spreadsheets_Extension_RowCount();
        $newRowCount->setText('20');
        $newColCount = new Zend_Gdata_Spreadsheets_Extension_ColCount();
        $newColCount->setText('50');

        $this->cellFeed->setRowCount($newRowCount);
        $this->cellFeed->setColumnCount($newColCount);

        $this->assertTrue('20' == $this->cellFeed->getRowCount()->getText());
        $this->assertTrue('50' == $this->cellFeed->getColumnCount()->getText());
    }
}
