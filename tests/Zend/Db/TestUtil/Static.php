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
 * @see Zend_Db_TestUtil_Common
 */
require_once 'Zend/Db/TestUtil/Common.php';

#[AllowDynamicProperties]
class Zend_Db_TestUtil_Static extends Zend_Db_TestUtil_Common
{
    public function getParams(array $constants = [])
    {
        $constants = [
            'dbname' => 'dummy',
        ];

        return $constants;
    }

    protected function _rawQuery($sql)
    {
        // no-op
    }
}
