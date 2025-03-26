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

// require_once "Zend/Test/PHPUnit/Db/Operation/Truncate.php";

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Test
 */
#[AllowDynamicProperties]
class Zend_Test_PHPUnit_Db_Operation_TruncateTest extends PHPUnit\Framework\TestCase
{
    private $operation;
    private $libxmlDisableEntityLoader;

    public function setUp(): void
    {
        $this->operation = new Zend_Test_PHPUnit_Db_Operation_Truncate();
        if (LIBXML_VERSION < 20900) {
            $this->libxmlDisableEntityLoader = libxml_disable_entity_loader(false);
        }
    }

    public function tearDown(): void
    {
        if (LIBXML_VERSION < 20900) {
            libxml_disable_entity_loader($this->libxmlDisableEntityLoader);
        }
    }

    public function testTruncateTablesExecutesAdapterQuery()
    {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet(__DIR__.'/_files/truncateFixture.xml');

        $testAdapter = $this->getMock('Zend_Test_DbAdapter');
        $testAdapter->expects($this->at(0))
                    ->method('quoteIdentifier')
                    ->with('bar')->will($this->returnValue('bar'));
        $testAdapter->expects($this->at(1))
                    ->method('query')
                    ->with('TRUNCATE bar');
        $testAdapter->expects($this->at(2))
                    ->method('quoteIdentifier')
                    ->with('foo')->will($this->returnValue('foo'));
        $testAdapter->expects($this->at(3))
                    ->method('query')
                    ->with('TRUNCATE foo');

        $connection = new Zend_Test_PHPUnit_Db_Connection($testAdapter, 'schema');

        $this->operation->execute($connection, $dataSet);
    }

    public function testTruncateTableInvalidQueryTransformsException()
    {
        $this->expectException('PHPUnit_Extensions_Database_Operation_Exception');

        $dataSet = new PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet(__DIR__.'/_files/insertFixture.xml');

        $testAdapter = $this->getMock('Zend_Test_DbAdapter');
        $testAdapter->expects($this->any())->method('query')->will($this->throwException(new Exception()));

        $connection = new Zend_Test_PHPUnit_Db_Connection($testAdapter, 'schema');

        $this->operation->execute($connection, $dataSet);
    }

    public function testInvalidConnectionGivenThrowsException()
    {
        $this->expectException('Zend_Test_PHPUnit_Db_Exception');

        $dataSet = $this->getMock('PHPUnit_Extensions_Database_DataSet_IDataSet');
        $connection = $this->getMock('PHPUnit_Extensions_Database_DB_IDatabaseConnection');

        $this->operation->execute($connection, $dataSet);
    }

    /**
     * @group ZF-7936
     */
    public function testTruncateAppliedToTablesInReverseOrder()
    {
        $testAdapter = new Zend_Test_DbAdapter();
        $connection = new Zend_Test_PHPUnit_Db_Connection($testAdapter, 'schema');

        $dataSet = new PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet(__DIR__.'/_files/truncateFixture.xml');

        $this->operation->execute($connection, $dataSet);

        $profiler = $testAdapter->getProfiler();
        $queries = $profiler->getQueryProfiles();

        $this->assertEquals(2, count($queries));
        $this->assertStringContainsString('bar', $queries[0]->getQuery());
        $this->assertStringContainsString('foo', $queries[1]->getQuery());
    }
}
