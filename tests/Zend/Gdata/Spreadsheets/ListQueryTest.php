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
class Zend_Gdata_Spreadsheets_ListQueryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->docQuery = new Zend_Gdata_Spreadsheets_ListQuery();
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

    public function testRowId()
    {
        $this->assertTrue(null == $this->docQuery->getRowId());
        $this->docQuery->setRowId('xyz');
        $this->assertTrue('xyz' == $this->docQuery->getRowId());
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

    public function testSpreadsheetQuery()
    {
        $this->assertTrue(null == $this->docQuery->getSpreadsheetQuery());
        $this->docQuery->setSpreadsheetQuery('first=john&last=smith');
        $this->assertTrue('first=john&last=smith' == $this->docQuery->getSpreadsheetQuery());
        $this->assertTrue('?sq=first%3Djohn%26last%3Dsmith' == $this->docQuery->getQueryString());
        $this->docQuery->setSpreadsheetQuery(null);
        $this->assertTrue(null == $this->docQuery->getSpreadsheetQuery());
    }

    public function testOrderBy()
    {
        $this->assertTrue(null == $this->docQuery->getOrderBy());
        $this->docQuery->setOrderBy('column:first');
        $this->assertTrue('column:first' == $this->docQuery->getOrderBy());
        $this->assertTrue('?orderby=column%3Afirst' == $this->docQuery->getQueryString());
        $this->docQuery->setOrderBy(null);
        $this->assertTrue(null == $this->docQuery->getOrderBy());
    }

    public function testReverse()
    {
        $this->assertTrue(null == $this->docQuery->getReverse());
        $this->docQuery->setReverse('true');
        $this->assertTrue('true' == $this->docQuery->getReverse());
        $this->assertTrue('?reverse=true' == $this->docQuery->getQueryString());
        $this->docQuery->setReverse(null);
        $this->assertTrue(null == $this->docQuery->getReverse());
    }
}
