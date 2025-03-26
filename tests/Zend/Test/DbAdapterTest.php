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

// require_once "Zend/Test/DbAdapter.php";
// require_once "Zend/Test/DbStatement.php";

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Test
 */
#[AllowDynamicProperties]
class Zend_Test_DbAdapterTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Zend_Test_DbAdapter
     */
    private $_adapter;

    public function setUp(): void
    {
        $this->_adapter = new Zend_Test_DbAdapter();
    }

    public function testAppendStatementToStack()
    {
        $stmt1 = Zend_Test_DbStatement::createSelectStatement([]);
        $this->_adapter->appendStatementToStack($stmt1);

        $stmt2 = Zend_Test_DbStatement::createSelectStatement([]);
        $this->_adapter->appendStatementToStack($stmt2);

        $this->assertSame($stmt2, $this->_adapter->query('foo'));
        $this->assertSame($stmt1, $this->_adapter->query('foo'));
    }

    public function testAppendLastInsertId()
    {
        $this->_adapter->appendLastInsertIdToStack(1);
        $this->_adapter->appendLastInsertIdToStack(2);

        $this->assertEquals(2, $this->_adapter->lastInsertId());
        $this->assertEquals(1, $this->_adapter->lastInsertId());
    }

    public function testLastInsertIdDefault()
    {
        $this->assertFalse($this->_adapter->lastInsertId());
    }

    public function testListTablesDefault()
    {
        $this->assertEquals([], $this->_adapter->listTables());
    }

    public function testSetListTables()
    {
        $this->_adapter->setListTables(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $this->_adapter->listTables());
    }

    public function testDescribeTableDefault()
    {
        $this->assertEquals([], $this->_adapter->describeTable('foo'));
    }

    public function testDescribeTable()
    {
        $this->_adapter->setDescribeTable('foo', ['bar']);
        $this->assertEquals(['bar'], $this->_adapter->describeTable('foo'));
    }

    public function testConnect()
    {
        $this->assertFalse($this->_adapter->isConnected());
        $this->_adapter->query('foo');
        $this->assertTrue($this->_adapter->isConnected());
        $this->_adapter->closeConnection();
        $this->assertFalse($this->_adapter->isConnected());
    }

    public function testAppendLimitToSql()
    {
        $sql = $this->_adapter->limit('foo', 10, 20);
        $this->assertEquals(
            'foo LIMIT 20,10', $sql
        );
    }

    public function testQueryProfilerEnabledByDefault()
    {
        $this->assertTrue($this->_adapter->getProfiler()->getEnabled());
    }

    public function testQueryPRofilerPrepareStartsQueryProfiler()
    {
        $stmt = $this->_adapter->prepare('SELECT foo');

        $this->assertEquals(1, $this->_adapter->getProfiler()->getTotalNumQueries());

        $qp = $this->_adapter->getProfiler()->getLastQueryProfile();
        /* @var $qp Zend_Db_Profiler_Query */

        $this->assertFalse($qp->hasEnded());
    }

    public function testQueryProfilerQueryStartEndsQueryProfiler()
    {
        $stmt = $this->_adapter->query('SELECT foo');

        $this->assertEquals(1, $this->_adapter->getProfiler()->getTotalNumQueries());

        $qp = $this->_adapter->getProfiler()->getLastQueryProfile();
        /* @var $qp Zend_Db_Profiler_Query */

        $this->assertTrue($qp->hasEnded());
    }

    public function testQueryProfilerQueryBindWithParams()
    {
        $stmt = $this->_adapter->query('SELECT * FROM foo WHERE bar = ?', [1234]);

        $qp = $this->_adapter->getProfiler()->getLastQueryProfile();
        /* @var $qp Zend_Db_Profiler_Query */

        $this->assertEquals([1 => 1234], $qp->getQueryParams());
        $this->assertEquals('SELECT * FROM foo WHERE bar = ?', $qp->getQuery());
    }

    public function testQueryProfilerPrepareBindExecute()
    {
        $var = 1234;

        $stmt = $this->_adapter->prepare('SELECT * FROM foo WHERE bar = ?');
        $stmt->bindParam(1, $var);

        $qp = $this->_adapter->getProfiler()->getLastQueryProfile();
        /* @var $qp Zend_Db_Profiler_Query */

        $this->assertEquals([1 => 1234], $qp->getQueryParams());
        $this->assertEquals('SELECT * FROM foo WHERE bar = ?', $qp->getQuery());
    }

    public function testGetSetQuoteIdentifierSymbol()
    {
        $this->assertEquals('', $this->_adapter->getQuoteIdentifierSymbol());
        $this->_adapter->setQuoteIdentifierSymbol('`');
        $this->assertEquals('`', $this->_adapter->getQuoteIdentifierSymbol());
    }
}
