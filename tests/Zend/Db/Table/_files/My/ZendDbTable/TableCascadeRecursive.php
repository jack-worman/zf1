<?php
/**
 * Zend Framework
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
 */


/**
 */
#[AllowDynamicProperties]
class My_ZendDbTable_TableCascadeRecursive extends Zend_Db_Table_Abstract
{

    protected $_name = 'zfalt_cascade_recursive';
    protected $_primary = 'item_id'; // Deliberate non-array value

    protected $_dependentTables = array('My_ZendDbTable_TableCascadeRecursive');

    protected $_referenceMap    = array(
        'Children' => array(
            'columns'           => array('item_parent'),
            'refTableClass'     => 'My_ZendDbTable_TableCascadeRecursive',
            'refColumns'        => array('item_id'),
            'onDelete'          => self::CASCADE_RECURSE
        )
    );

}
