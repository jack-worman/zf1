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

/**
 * @group      Zend_Db
 */
abstract class Zend_Db_Skip_CommonTest extends PHPUnit_Framework_TestCase
{
    public $message;

    abstract public function getDriver();

    public function setUp()
    {
        $driver = $this->getDriver();
        $message = 'Skipping '.$this->getDriver();
        if ($this->message) {
            $message .= ': '.$this->message;
        }
        $this->markTestSkipped($message);
    }

    public function testDb()
    {
        // this is here only so we have at least one test
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_StaticTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Static';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_Db2Test extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Db2';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_MysqliTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Mysqli';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_OdbcTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Odbc';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_OracleTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Oracle';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_SqlsrvTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Sqlsrv';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_FirebirdTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Firebird';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_Pdo_IbmTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Pdo_Ibm';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_Pdo_MssqlTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Pdo_Mssql';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_Pdo_MysqlTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Pdo_Mysql';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_Pdo_OciTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Pdo_Oci';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_Pdo_FirebirdTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Pdo_Firebird';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_Pdo_PgsqlTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Pdo_Pgsql';
    }
}

/**
 * @group      Zend_Db
 */
#[AllowDynamicProperties]
class Zend_Db_Skip_Pdo_SqliteTest extends Zend_Db_Skip_CommonTest
{
    public function getDriver()
    {
        return 'Pdo_Sqlite';
    }
}
