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
class Zend_Config implements Countable, Iterator
{
    /**
     * Whether in-memory modifications to configuration data are allowed.
     *
     * @var bool
     */
    protected $_allowModifications;

    /**
     * Iteration index.
     *
     * @var int
     */
    protected $_index;

    /**
     * Number of elements in configuration data.
     *
     * @var int
     */
    protected $_count;

    /**
     * Contains array of configuration data.
     *
     * @var array
     */
    protected $_data;

    /**
     * Used when unsetting values during iteration to ensure we do not skip
     * the next element.
     *
     * @var bool
     */
    protected $_skipNextIteration;

    /**
     * Contains which config file sections were loaded. This is null
     * if all sections were loaded, a string name if one section is loaded
     * and an array of string names if multiple sections were loaded.
     */
    protected $_loadedSection;

    /**
     * This is used to track section inheritance. The keys are names of sections that
     * extend other sections, and the values are the extended sections.
     *
     * @var array
     */
    protected $_extends = [];

    /**
     * Load file error string.
     *
     * Is null if there was no error while file loading
     *
     * @var string
     */
    protected $_loadFileErrorStr;

    /**
     * Zend_Config provides a property based interface to
     * an array. The data are read-only unless $allowModifications
     * is set to true on construction.
     *
     * Zend_Config also implements Countable and Iterator to
     * facilitate easy access to the data.
     *
     * @param bool $allowModifications
     */
    public function __construct(array $array, $allowModifications = false)
    {
        $this->_allowModifications = (bool) $allowModifications;
        $this->_loadedSection = null;
        $this->_index = 0;
        $this->_data = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->_data[$key] = new self($value, $this->_allowModifications);
            } else {
                $this->_data[$key] = $value;
            }
        }
        $this->_count = count($this->_data);
    }

    /**
     * Retrieve a value and return $default if there is no element set.
     * added: retrieving nested value by ->get('value.value2.value3').
     *
     * @param string $name
     */
    public function get($name, $default = null)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }

        if (false !== ($dotpos = strpos((string) $name, '.'))) {
            $restName = substr((string) $name, $dotpos + 1);
            $name = substr((string) $name, 0, $dotpos);

            if (!array_key_exists($name, $this->_data) || !($this->_data[$name] instanceof Zend_Config)) {
                return $default;
            }
            $result = $this->_data[$name];
            if ('' === $restName) {
                return $result;
            }

            return $result->get($restName, $default);
        }

        return $default;
    }

    /**
     * Magic function so that $obj->value will work.
     *
     * @param string $name
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Only allow setting of a property if $allowModifications
     * was set to true on construction. Otherwise, throw an exception.
     *
     * @param string $name
     *
     * @return void
     *
     * @throws Zend_Config_Exception
     */
    public function __set($name, $value)
    {
        if ($this->_allowModifications) {
            if (is_array($value)) {
                $this->_data[$name] = new self($value, true);
            } else {
                $this->_data[$name] = $value;
            }
            $this->_count = count($this->_data);
        } else {
            throw new Zend_Config_Exception('Zend_Config is read only');
        }
    }

    /**
     * Deep clone of this instance to ensure that nested Zend_Configs
     * are also cloned.
     *
     * @return void
     */
    public function __clone()
    {
        $array = [];
        foreach ($this->_data as $key => $value) {
            if ($value instanceof Zend_Config) {
                $array[$key] = clone $value;
            } else {
                $array[$key] = $value;
            }
        }
        $this->_data = $array;
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        $data = $this->_data;
        foreach ($data as $key => $value) {
            if ($value instanceof Zend_Config) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Support isset() overloading on PHP 5.1.
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * Support unset() overloading on PHP 5.1.
     *
     * @param string $name
     *
     * @return void
     *
     * @throws Zend_Config_Exception
     */
    public function __unset($name)
    {
        if ($this->_allowModifications) {
            unset($this->_data[$name]);
            $this->_count = count($this->_data);
            $this->_skipNextIteration = true;
        } else {
            throw new Zend_Config_Exception('Zend_Config is read only');
        }
    }

    /**
     * Defined by Countable interface.
     *
     * @return int
     */
    #[ReturnTypeWillChange]
    public function count()
    {
        return $this->_count;
    }

    /**
     * Defined by Iterator interface.
     */
    public function current(): mixed
    {
        $this->_skipNextIteration = false;

        return current($this->_data);
    }

    /**
     * Defined by Iterator interface.
     *
     * @return int|string|null
     */
    #[ReturnTypeWillChange]
    public function key()
    {
        return key($this->_data);
    }

    /**
     * @return void
     */
    #[ReturnTypeWillChange]
    public function next()
    {
        if ($this->_skipNextIteration) {
            $this->_skipNextIteration = false;

            return;
        }
        next($this->_data);
        ++$this->_index;
    }

    /**
     * @return void
     */
    #[ReturnTypeWillChange]
    public function rewind()
    {
        $this->_skipNextIteration = false;
        reset($this->_data);
        $this->_index = 0;
    }

    /**
     * Defined by Iterator interface.
     *
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function valid()
    {
        return $this->_index < $this->_count;
    }

    /**
     * Returns the section name(s) loaded.
     */
    public function getSectionName()
    {
        if (is_array($this->_loadedSection) && 1 == count($this->_loadedSection)) {
            $this->_loadedSection = $this->_loadedSection[0];
        }

        return $this->_loadedSection;
    }

    /**
     * Returns true if all sections were loaded.
     *
     * @return bool
     */
    public function areAllSectionsLoaded()
    {
        return null === $this->_loadedSection;
    }

    /**
     * Merge another Zend_Config with this one. The items
     * in $merge will override the same named items in
     * the current config.
     *
     * @return Zend_Config
     */
    public function merge(Zend_Config $merge)
    {
        foreach ($merge as $key => $item) {
            if (array_key_exists($key, $this->_data)) {
                if ($item instanceof Zend_Config && $this->$key instanceof Zend_Config) {
                    $this->$key = $this->$key->merge(new Zend_Config($item->toArray(), !$this->readOnly()));
                } else {
                    $this->$key = $item;
                }
            } else {
                if ($item instanceof Zend_Config) {
                    $this->$key = new Zend_Config($item->toArray(), !$this->readOnly());
                } else {
                    $this->$key = $item;
                }
            }
        }

        return $this;
    }

    /**
     * Prevent any more modifications being made to this instance. Useful
     * after merge() has been used to merge multiple Zend_Config objects
     * into one object which should then not be modified again.
     */
    public function setReadOnly()
    {
        $this->_allowModifications = false;
        foreach ($this->_data as $key => $value) {
            if ($value instanceof Zend_Config) {
                $value->setReadOnly();
            }
        }
    }

    /**
     * Returns if this Zend_Config object is read only or not.
     *
     * @return bool
     */
    public function readOnly()
    {
        return !$this->_allowModifications;
    }

    /**
     * Get the current extends.
     *
     * @return array
     */
    public function getExtends()
    {
        return $this->_extends;
    }

    /**
     * Set an extend for Zend_Config_Writer.
     *
     * @param string $extendingSection
     * @param string $extendedSection
     *
     * @return void
     */
    public function setExtend($extendingSection, $extendedSection = null)
    {
        if (null === $extendedSection && isset($this->_extends[$extendingSection])) {
            unset($this->_extends[$extendingSection]);
        } elseif (null !== $extendedSection) {
            $this->_extends[$extendingSection] = $extendedSection;
        }
    }

    /**
     * Throws an exception if $extendingSection may not extend $extendedSection,
     * and tracks the section extension if it is valid.
     *
     * @param string $extendingSection
     * @param string $extendedSection
     *
     * @return void
     *
     * @throws Zend_Config_Exception
     */
    protected function _assertValidExtend($extendingSection, $extendedSection)
    {
        // detect circular section inheritance
        $extendedSectionCurrent = $extendedSection;
        while (array_key_exists($extendedSectionCurrent, $this->_extends)) {
            if ($this->_extends[$extendedSectionCurrent] == $extendingSection) {
                throw new Zend_Config_Exception('Illegal circular inheritance detected');
            }
            $extendedSectionCurrent = $this->_extends[$extendedSectionCurrent];
        }
        // remember that this section extends another section
        $this->_extends[$extendingSection] = $extendedSection;
    }

    /**
     * Handle any errors from simplexml_load_file or parse_ini_file.
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     */
    public function _loadFileErrorHandler($errno, $errstr, $errfile, $errline)
    {
        if (null === $this->_loadFileErrorStr) {
            $this->_loadFileErrorStr = $errstr;
        } else {
            $this->_loadFileErrorStr .= (PHP_EOL.$errstr);
        }
    }

    /**
     * Merge two arrays recursively, overwriting keys of the same name
     * in $firstArray with the value in $secondArray.
     *
     * @param mixed $firstArray  First array
     * @param mixed $secondArray Second array to merge into first array
     *
     * @return array
     */
    protected function _arrayMergeRecursive($firstArray, $secondArray)
    {
        if (is_array($firstArray) && is_array($secondArray)) {
            foreach ($secondArray as $key => $value) {
                if (isset($firstArray[$key])) {
                    $firstArray[$key] = $this->_arrayMergeRecursive($firstArray[$key], $value);
                } else {
                    if (0 === $key) {
                        $firstArray = [0 => $this->_arrayMergeRecursive($firstArray, $value)];
                    } else {
                        $firstArray[$key] = $value;
                    }
                }
            }
        } else {
            $firstArray = $secondArray;
        }

        return $firstArray;
    }
}
