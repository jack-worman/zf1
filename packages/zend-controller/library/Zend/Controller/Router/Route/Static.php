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
 * StaticRoute is used for managing static URIs.
 *
 * It's a lot faster compared to the standard Route implementation.
 */
class Zend_Controller_Router_Route_Static extends Zend_Controller_Router_Route_Abstract
{
    /**
     * Route.
     *
     * @var string|null
     */
    protected $_route;

    /**
     * Default values for the route (ie. module, controller, action, params).
     *
     * @var array
     */
    protected $_defaults = [];

    /**
     * Get the version of the route.
     *
     * @return int
     */
    public function getVersion()
    {
        return 1;
    }

    /**
     * Instantiates route based on passed Zend_Config structure.
     *
     * @param Zend_Config $config Configuration object
     *
     * @return Zend_Controller_Router_Route_Static
     */
    public static function getInstance(Zend_Config $config)
    {
        $defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : [];

        return new self($config->route, $defs);
    }

    /**
     * Prepares the route for mapping.
     *
     * @param string $route    Map used to match with later submitted URL path
     * @param array  $defaults Defaults for map variables with keys as variable names
     */
    public function __construct($route, $defaults = [])
    {
        $this->_route = \trim((string) $route, self::URI_DELIMITER);
        $this->_defaults = (array) $defaults;
    }

    /**
     * Matches a user submitted path with a previously defined route.
     * Assigns and returns an array of defaults on a successful match.
     *
     * @param string $path Path used to match against this routing map
     *
     * @return array|false An array of assigned values or a false on a mismatch
     */
    public function match($path, $partial = false)
    {
        if ($partial) {
            if ((empty($path) && empty($this->_route))
                || (substr((string) $path, 0, strlen((string) $this->_route)) === $this->_route)
            ) {
                $this->setMatchedPath($this->_route);

                return $this->_defaults;
            }
        } else {
            if (\trim((string) $path, self::URI_DELIMITER) == $this->_route) {
                return $this->_defaults;
            }
        }

        return false;
    }

    /**
     * Assembles a URL path defined by this route.
     *
     * @param array $data An array of variable and value pairs used as parameters
     *
     * @return string Route path with user submitted parameters
     */
    public function assemble($data = [], $reset = false, $encode = false, $partial = false)
    {
        return $this->_route;
    }

    /**
     * Return a single parameter of route's defaults.
     *
     * @param string $name Array key of the parameter
     *
     * @return string Previously set default
     */
    public function getDefault($name)
    {
        if (isset($this->_defaults[$name])) {
            return $this->_defaults[$name];
        }

        return null;
    }

    /**
     * Return an array of defaults.
     *
     * @return array Route defaults
     */
    public function getDefaults()
    {
        return $this->_defaults;
    }
}
