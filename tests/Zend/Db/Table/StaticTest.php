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
 */

require_once 'Zend/Db/Table/TestCommon.php';

/**
 * @group      Zend_Db
 * @group      Zend_Db_Table
 */
#[AllowDynamicProperties]
class Zend_Db_Table_StaticTest extends PHPUnit_Framework_TestCase
{
    public function testStatic()
    {
        $this->markTestIncomplete('Static table tests are not implemented yet');
    }

    public function getDriver()
    {
        return 'Static';
    }
}
