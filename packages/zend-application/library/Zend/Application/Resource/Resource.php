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

/**
 * Interface for bootstrap resources.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Zend_Application_Resource_Resource
{
    /**
     * Constructor.
     *
     * Must take an optional single argument, $options.
     */
    public function __construct($options = null);

    /**
     * Set the bootstrap to which the resource is attached.
     *
     * @return Zend_Application_Resource_Resource
     */
    public function setBootstrap(Zend_Application_Bootstrap_Bootstrapper $bootstrap);

    /**
     * Retrieve the bootstrap to which the resource is attached.
     *
     * @return Zend_Application_Bootstrap_Bootstrapper
     */
    public function getBootstrap();

    /**
     * Set resource options.
     *
     * @return Zend_Application_Resource_Resource
     */
    public function setOptions(array $options);

    /**
     * Retrieve resource options.
     *
     * @return array
     */
    public function getOptions();

    /**
     * Strategy pattern: initialize resource.
     */
    public function init();
}
