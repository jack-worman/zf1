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
 */

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Zend_Http_UserAgent_Storage
{
    /**
     * Returns true if and only if storage is empty.
     *
     * @return bool
     *
     * @throws Zend_Http_UserAgent_Storage_Exception If it is impossible to determine whether storage is empty
     */
    public function isEmpty();

    /**
     * Returns the contents of storage associated to the key parameter.
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws Zend_Http_UserAgent_Storage_Exception If reading contents from storage is impossible
     */
    public function read();

    /**
     * Writes $contents associated to the key parameter to storage.
     *
     * @return void
     *
     * @throws Zend_Http_UserAgent_Storage_Exception If writing $contents to storage is impossible
     */
    public function write($contents);

    /**
     * Clears contents from storage.
     *
     * @return void
     *
     * @throws Zend_Http_UserAgent_Storage_Exception If clearing contents from storage is impossible
     */
    public function clear();
}
