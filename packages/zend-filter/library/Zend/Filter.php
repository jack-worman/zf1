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
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Filter implements Zend_Filter_Interface
{
    public const CHAIN_APPEND = 'append';
    public const CHAIN_PREPEND = 'prepend';

    /**
     * Filter chain.
     *
     * @var array
     */
    protected $_filters = [];

    /**
     * Default Namespaces.
     *
     * @var array
     */
    protected static $_defaultNamespaces = [];

    /**
     * Adds a filter to the chain.
     *
     * @param string $placement
     *
     * @return Zend_Filter Provides a fluent interface
     */
    public function addFilter(Zend_Filter_Interface $filter, $placement = self::CHAIN_APPEND)
    {
        if (self::CHAIN_PREPEND == $placement) {
            array_unshift($this->_filters, $filter);
        } else {
            $this->_filters[] = $filter;
        }

        return $this;
    }

    /**
     * Add a filter to the end of the chain.
     *
     * @return Zend_Filter Provides a fluent interface
     */
    public function appendFilter(Zend_Filter_Interface $filter)
    {
        return $this->addFilter($filter, self::CHAIN_APPEND);
    }

    /**
     * Add a filter to the start of the chain.
     *
     * @return Zend_Filter Provides a fluent interface
     */
    public function prependFilter(Zend_Filter_Interface $filter)
    {
        return $this->addFilter($filter, self::CHAIN_PREPEND);
    }

    /**
     * Get all the filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Returns $value filtered through each filter in the chain.
     *
     * Filters are run in the order in which they were added to the chain (FIFO)
     */
    public function filter($value)
    {
        $valueFiltered = $value;
        foreach ($this->_filters as $filter) {
            $valueFiltered = $filter->filter($valueFiltered);
        }

        return $valueFiltered;
    }

    /**
     * Returns the set default namespaces.
     *
     * @return array
     */
    public static function getDefaultNamespaces()
    {
        return self::$_defaultNamespaces;
    }

    /**
     * Sets new default namespaces.
     *
     * @param array|string $namespace
     *
     * @return null
     */
    public static function setDefaultNamespaces($namespace)
    {
        if (!is_array($namespace)) {
            $namespace = [(string) $namespace];
        }

        self::$_defaultNamespaces = $namespace;
    }

    /**
     * Adds a new default namespace.
     *
     * @param array|string $namespace
     *
     * @return null
     */
    public static function addDefaultNamespaces($namespace)
    {
        if (!is_array($namespace)) {
            $namespace = [(string) $namespace];
        }

        self::$_defaultNamespaces = array_unique(array_merge(self::$_defaultNamespaces, $namespace));
    }

    /**
     * Returns true when defaultNamespaces are set.
     *
     * @return bool
     */
    public static function hasDefaultNamespaces()
    {
        return !empty(self::$_defaultNamespaces);
    }

    /**
     * @deprecated
     * @see Zend_Filter::filterStatic()
     *
     * @param string       $classBaseName
     * @param array        $args          OPTIONAL
     * @param array|string $namespaces    OPTIONAL
     *
     * @throws Zend_Filter_Exception
     */
    public static function get($value, $classBaseName, array $args = [], $namespaces = [])
    {
        trigger_error(
            'Zend_Filter::get() is deprecated as of 1.9.0; please update your code to utilize Zend_Filter::filterStatic()',
            E_USER_NOTICE
        );

        return self::filterStatic($value, $classBaseName, $args, $namespaces);
    }

    /**
     * Returns a value filtered through a specified filter class, without requiring separate
     * instantiation of the filter object.
     *
     * The first argument of this method is a data input value, that you would have filtered.
     * The second argument is a string, which corresponds to the basename of the filter class,
     * relative to the Zend_Filter namespace. This method automatically loads the class,
     * creates an instance, and applies the filter() method to the data input. You can also pass
     * an array of constructor arguments, if they are needed for the filter class.
     *
     * @param string       $classBaseName
     * @param array        $args          OPTIONAL
     * @param array|string $namespaces    OPTIONAL
     *
     * @throws Zend_Filter_Exception
     */
    public static function filterStatic($value, $classBaseName, array $args = [], $namespaces = [])
    {
        $namespaces = array_merge((array) $namespaces, self::$_defaultNamespaces, ['Zend_Filter']);
        foreach ($namespaces as $namespace) {
            $nsSeparator = (false !== strpos((string) $namespace, '\\')) ? '\\' : '_';
            $className = rtrim((string) $namespace, $nsSeparator).$nsSeparator.ucfirst($classBaseName);
            if (!class_exists($className)) {
                continue;
                /*try {
                    $file = str_replace((string) '_', DIRECTORY_SEPARATOR, $className) . '.php';
                    if (Zend_Loader::isReadable($file)) {
                        Zend_Loader::loadClass($className);
                    } else {
                        continue;
                    }
                } catch (Zend_Exception $ze) {
                    continue;
                }*/
            }

            $class = new ReflectionClass($className);
            if ($class->implementsInterface('Zend_Filter_Interface')) {
                if ($class->hasMethod('__construct')) {
                    $object = $class->newInstanceArgs($args);
                } else {
                    $object = $class->newInstance();
                }

                return $object->filter($value);
            }
        }
        throw new Zend_Filter_Exception("Filter class not found from basename '$classBaseName'");
    }
}
