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
 * Delete All Operation that can be executed on set up or tear down of a database tester.
 *
 * @uses       PHPUnit_Extensions_Database_Operation_IDatabaseOperation
 */
class Zend_Test_PHPUnit_Db_Operation_DeleteAll implements PHPUnit_Extensions_Database_Operation_IDatabaseOperation
{
    public function execute(PHPUnit_Extensions_Database_DB_IDatabaseConnection $connection, PHPUnit_Extensions_Database_DataSet_IDataSet $dataSet)
    {
        if (!($connection instanceof Zend_Test_PHPUnit_Db_Connection)) {
            throw new Zend_Test_PHPUnit_Db_Exception('Not a valid Zend_Test_PHPUnit_Db_Connection instance, '.get_class($connection).' given!');
        }

        foreach ($dataSet as $table) {
            try {
                $tableName = $table->getTableMetaData()->getTableName();
                $connection->getConnection()->delete($tableName);
            } catch (Throwable $e) {
                throw new PHPUnit_Extensions_Database_Operation_Exception('DELETEALL', 'DELETE FROM '.$tableName.'', [], $table, $e->getMessage());
            }
        }
    }
}
