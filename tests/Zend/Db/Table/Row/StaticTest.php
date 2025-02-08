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

/**
 * @see Zend_Db_Table_Row_TestMockRow
 */
require_once __DIR__.'/../_files/My/ZendDbTable/Row/TestMockRow.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Db
 * @group      Zend_Db_Table
 * @group      Zend_Db_Table_Row
 */
#[AllowDynamicProperties]
class Zend_Db_Table_Row_StaticTest extends PHPUnit\Framework\TestCase
{
    public function testTableRowTransformColumnNotUsedInConstructor()
    {
        $data = [
            'column' => 'value1',
            'column_foo' => 'value2',
            'column_bar_baz' => 'value3',
        ];
        $row = new My_ZendDbTable_Row_TestMockRow(['data' => $data]);

        $array = $row->toArray();
        $this->assertEquals($data, $array);
    }

    public function testTableRowTransformColumnMagicGet()
    {
        $data = [
            'column' => 'value1',
            'column_foo' => 'value2',
            'column_bar_baz' => 'value3',
        ];
        $row = new My_ZendDbTable_Row_TestMockRow(['data' => $data]);

        $this->assertEquals('value1', $row->column);
        $this->assertEquals('value2', $row->columnFoo);
        $this->assertEquals('value3', $row->columnBarBaz);
    }

    public function testTableRowTransformColumnMagicSet()
    {
        $data = [
            'column' => 'value1',
            'column_foo' => 'value2',
            'column_bar_baz' => 'value3',
        ];
        $row = new My_ZendDbTable_Row_TestMockRow(['data' => $data]);

        $this->assertEquals('value1', $row->column);
        $this->assertEquals('value2', $row->columnFoo);
        $this->assertEquals('value3', $row->columnBarBaz);

        $row->column = 'another value 1';
        $row->columnFoo = 'another value 2';
        $row->columnBarBaz = 'another value 3';

        $array = $row->toArray();
        $this->assertEquals(
            [
                'column' => 'another value 1',
                'column_foo' => 'another value 2',
                'column_bar_baz' => 'another value 3',
            ], $array);
    }

    public function getDriver()
    {
        return 'Static';
    }
}
