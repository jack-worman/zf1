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
 * @see Zend_Gdata_Extension_EntryLink
 */
// require_once 'Zend/Gdata/Extension/EntryLink.php';

/**
 * Data model class to represent a location (gd:where element).
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Extension_Where extends Zend_Gdata_Extension
{
    protected $_rootElement = 'where';
    protected $_label;
    protected $_rel;
    protected $_valueString;
    protected $_entryLink;

    public function __construct($valueString = null, $label = null, $rel = null, $entryLink = null)
    {
        parent::__construct();
        $this->_valueString = $valueString;
        $this->_label = $label;
        $this->_rel = $rel;
        $this->_entryLink = $entryLink;
    }

    public function getDOM($doc = null, $majorVersion = 1, $minorVersion = null)
    {
        $element = parent::getDOM($doc, $majorVersion, $minorVersion);
        if (null !== $this->_label) {
            $element->setAttribute('label', $this->_label);
        }
        if (null !== $this->_rel) {
            $element->setAttribute('rel', $this->_rel);
        }
        if (null !== $this->_valueString) {
            $element->setAttribute('valueString', $this->_valueString);
        }
        if (null !== $this->entryLink) {
            $element->appendChild($this->_entryLink->getDOM($element->ownerDocument));
        }

        return $element;
    }

    protected function takeAttributeFromDOM($attribute)
    {
        switch ($attribute->localName) {
            case 'label':
                $this->_label = $attribute->nodeValue;
                break;
            case 'rel':
                $this->_rel = $attribute->nodeValue;
                break;
            case 'valueString':
                $this->_valueString = $attribute->nodeValue;
                break;
            default:
                parent::takeAttributeFromDOM($attribute);
        }
    }

    /**
     * Creates individual Entry objects of the appropriate type and
     * stores them in the $_entry array based upon DOM data.
     *
     * @param DOMNode $child The DOMNode to process
     */
    protected function takeChildFromDOM($child)
    {
        $absoluteNodeName = $child->namespaceURI.':'.$child->localName;
        switch ($absoluteNodeName) {
            case $this->lookupNamespace('gd').':entryLink':
                $entryLink = new Zend_Gdata_Extension_EntryLink();
                $entryLink->transferFromDOM($child);
                $this->_entryLink = $entryLink;
                break;
            default:
                parent::takeChildFromDOM($child);
                break;
        }
    }

    public function __toString()
    {
        if (null != $this->_valueString) {
            return $this->_valueString;
        } else {
            return parent::__toString();
        }
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function setLabel($value)
    {
        $this->_label = $value;

        return $this;
    }

    public function getRel()
    {
        return $this->_rel;
    }

    public function setRel($value)
    {
        $this->_rel = $value;

        return $this;
    }

    public function getValueString()
    {
        return $this->_valueString;
    }

    public function setValueString($value)
    {
        $this->_valueString = $value;

        return $this;
    }

    public function getEntryLink()
    {
        return $this->_entryLink;
    }

    public function setEntryLink($value)
    {
        $this->_entryLink = $value;

        return $this;
    }
}
