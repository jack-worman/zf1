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
 * @see Zend_Gdata_Extension
 */
// require_once 'Zend/Gdata/Extension.php';

/**
 * Implements the gd:rating element.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Extension_Rating extends Zend_Gdata_Extension
{
    protected $_rootElement = 'rating';
    protected $_min;
    protected $_max;
    protected $_numRaters;
    protected $_average;
    protected $_value;

    /**
     * Constructs a new Zend_Gdata_Extension_Rating object.
     *
     * @param int $average   (optional) Average rating
     * @param int $min       (optional) Minimum rating
     * @param int $max       (optional) Maximum rating
     * @param int $numRaters (optional) Number of raters
     * @param int $value     (optional) The value of the rating
     */
    public function __construct($average = null, $min = null,
        $max = null, $numRaters = null, $value = null)
    {
        parent::__construct();
        $this->_average = $average;
        $this->_min = $min;
        $this->_max = $max;
        $this->_numRaters = $numRaters;
        $this->_value = $value;
    }

    /**
     * Retrieves a DOMElement which corresponds to this element and all
     * child properties.  This is used to build an entry back into a DOM
     * and eventually XML text for sending to the server upon updates, or
     * for application storage/persistence.
     *
     * @param DOMDocument $doc The DOMDocument used to construct DOMElements
     *
     * @return DOMElement the DOMElement representing this element and all
     *                    child properties
     */
    public function getDOM($doc = null, $majorVersion = 1, $minorVersion = null)
    {
        $element = parent::getDOM($doc, $majorVersion, $minorVersion);
        if (null !== $this->_min) {
            $element->setAttribute('min', $this->_min);
        }
        if (null !== $this->_max) {
            $element->setAttribute('max', $this->_max);
        }
        if (null !== $this->_numRaters) {
            $element->setAttribute('numRaters', $this->_numRaters);
        }
        if (null !== $this->_average) {
            $element->setAttribute('average', $this->_average);
        }
        if (null !== $this->_value) {
            $element->setAttribute('value', $this->_value);
        }

        return $element;
    }

    /**
     * Given a DOMNode representing an attribute, tries to map the data into
     * instance members.  If no mapping is defined, the name and value are
     * stored in an array.
     *
     * @param DOMNode $attribute The DOMNode attribute needed to be handled
     */
    protected function takeAttributeFromDOM($attribute)
    {
        switch ($attribute->localName) {
            case 'min':
                $this->_min = $attribute->nodeValue;
                break;
            case 'max':
                $this->_max = $attribute->nodeValue;
                break;
            case 'numRaters':
                $this->_numRaters = $attribute->nodeValue;
                break;
            case 'average':
                $this->_average = $attribute->nodeValue;
                break;
            case 'value':
                $this->_value = $attribute->nodeValue;
                // no break
            default:
                parent::takeAttributeFromDOM($attribute);
        }
    }

    /**
     * Get the value for this element's min attribute.
     *
     * @return int the requested attribute
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * Set the value for this element's min attribute.
     *
     * @param bool $value the desired value for this attribute
     *
     * @return Zend_Gdata_Extension_Rating the element being modified
     */
    public function setMin($value)
    {
        $this->_min = $value;

        return $this;
    }

    /**
     * Get the value for this element's numRaters attribute.
     *
     * @return int the requested attribute
     */
    public function getNumRaters()
    {
        return $this->_numRaters;
    }

    /**
     * Set the value for this element's numRaters attribute.
     *
     * @param bool $value the desired value for this attribute
     *
     * @return Zend_Gdata_Extension_Rating the element being modified
     */
    public function setNumRaters($value)
    {
        $this->_numRaters = $value;

        return $this;
    }

    /**
     * Get the value for this element's average attribute.
     *
     * @return int the requested attribute
     */
    public function getAverage()
    {
        return $this->_average;
    }

    /**
     * Set the value for this element's average attribute.
     *
     * @param bool $value the desired value for this attribute
     *
     * @return Zend_Gdata_Extension_Rating the element being modified
     */
    public function setAverage($value)
    {
        $this->_average = $value;

        return $this;
    }

    /**
     * Get the value for this element's max attribute.
     *
     * @return int the requested attribute
     */
    public function getMax()
    {
        return $this->_max;
    }

    /**
     * Set the value for this element's max attribute.
     *
     * @param bool $value the desired value for this attribute
     *
     * @return Zend_Gdata_Extension_Rating the element being modified
     */
    public function setMax($value)
    {
        $this->_max = $value;

        return $this;
    }

    /**
     * Get the value for this element's value attribute.
     *
     * @return int the requested attribute
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Set the value for this element's value attribute.
     *
     * @param bool $value the desired value for this attribute
     *
     * @return Zend_Gdata_Extension_Rating the element being modified
     */
    public function setValue($value)
    {
        $this->_value = $value;

        return $this;
    }
}
