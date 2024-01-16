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
class Zend_Gdata_Spreadsheets_CellQueryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->docQuery = new Zend_Gdata_Spreadsheets_CellQuery();
    }

    public function testMinRow()
    {
        $this->assertTrue(null == $this->docQuery->getMinRow());
        $this->docQuery->setMinRow('1');
        $this->assertTrue('1' == $this->docQuery->getMinRow());
        $this->assertTrue('?min-row=1' == $this->docQuery->getQueryString());
        $this->docQuery->setMinRow(null);
        $this->assertTrue(null == $this->docQuery->getMinRow());
    }

    public function testMaxRow()
    {
        $this->assertTrue(null == $this->docQuery->getMaxRow());
        $this->docQuery->setMaxRow('2');
        $this->assertTrue('2' == $this->docQuery->getMaxRow());
        $this->assertTrue('?max-row=2' == $this->docQuery->getQueryString());
        $this->docQuery->setMaxRow(null);
        $this->assertTrue(null == $this->docQuery->getMaxRow());
    }

    public function testMinCol()
    {
        $this->assertTrue(null == $this->docQuery->getMinCol());
        $this->docQuery->setMinCol('3');
        $this->assertTrue('3' == $this->docQuery->getMinCol());
        $this->assertTrue('?min-col=3' == $this->docQuery->getQueryString());
        $this->docQuery->setMinCol(null);
        $this->assertTrue(null == $this->docQuery->getMinCol());
    }

    public function testMaxCol()
    {
        $this->assertTrue(null == $this->docQuery->getMaxCol());
        $this->docQuery->setMaxCol('4');
        $this->assertTrue('4' == $this->docQuery->getMaxCol());
        $this->assertTrue('?max-col=4' == $this->docQuery->getQueryString());
        $this->docQuery->setMaxCol(null);
        $this->assertTrue(null == $this->docQuery->getMaxCol());
    }

    public function testRange()
    {
        $this->assertTrue(null == $this->docQuery->getRange());
        $this->docQuery->setRange('A1:B4');
        $this->assertTrue('A1:B4' == $this->docQuery->getRange());
        $this->assertTrue('?range=A1%3AB4' == $this->docQuery->getQueryString());
        $this->docQuery->setRange(null);
        $this->assertTrue(null == $this->docQuery->getRange());
    }

    public function testReturnEmpty()
    {
        $this->assertTrue(null == $this->docQuery->getReturnEmpty());
        $this->docQuery->setReturnEmpty('false');
        $this->assertTrue('false' == $this->docQuery->getReturnEmpty());
        $this->assertTrue('?return-empty=false' == $this->docQuery->getQueryString());
        $this->docQuery->setReturnEmpty(null);
        $this->assertTrue(null == $this->docQuery->getReturnEmpty());
    }

    public function testWorksheetId()
    {
        $this->assertTrue('default' == $this->docQuery->getWorksheetId());
        $this->docQuery->setWorksheetId('123');
        $this->assertTrue('123' == $this->docQuery->getWorksheetId());
    }

    public function testSpreadsheetKey()
    {
        $this->assertTrue(null == $this->docQuery->getSpreadsheetKey());
        $this->docQuery->setSpreadsheetKey('abc');
        $this->assertTrue('abc' == $this->docQuery->getSpreadsheetKey());
    }

    public function testCellId()
    {
        $this->assertTrue(null == $this->docQuery->getCellId());
        $this->docQuery->setCellId('xyz');
        $this->assertTrue('xyz' == $this->docQuery->getCellId());
    }

    public function testProjection()
    {
        $this->assertTrue('full' == $this->docQuery->getProjection());
        $this->docQuery->setProjection('abc');
        $this->assertTrue('abc' == $this->docQuery->getProjection());
    }

    public function testVisibility()
    {
        $this->assertTrue('private' == $this->docQuery->getVisibility());
        $this->docQuery->setVisibility('xyz');
        $this->assertTrue('xyz' == $this->docQuery->getVisibility());
    }
}
