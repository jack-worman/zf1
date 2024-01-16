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
 * Represents the media:content element of Media RSS.
 * Represents media objects.  Multiple media objects representing
 * the same content can be represented using a
 * media:group (Zend_Gdata_Media_Extension_MediaGroup) element.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Media_Extension_MediaContent extends Zend_Gdata_Extension
{
    protected $_rootElement = 'content';
    protected $_rootNamespace = 'media';

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var int
     */
    protected $_fileSize;

    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_medium;

    /**
     * @var string
     */
    protected $_isDefault;

    /**
     * @var string
     */
    protected $_expression;

    /**
     * @var int
     */
    protected $_bitrate;

    /**
     * @var int
     */
    protected $_framerate;

    /**
     * @var int
     */
    protected $_samplingrate;

    /**
     * @var int
     */
    protected $_channels;

    /**
     * @var int
     */
    protected $_duration;

    /**
     * @var int
     */
    protected $_height;

    /**
     * @var int
     */
    protected $_width;

    /**
     * @var string
     */
    protected $_lang;

    /**
     * Creates an individual MediaContent object.
     */
    public function __construct($url = null, $fileSize = null, $type = null,
        $medium = null, $isDefault = null, $expression = null,
        $bitrate = null, $framerate = null, $samplingrate = null,
        $channels = null, $duration = null, $height = null, $width = null,
        $lang = null)
    {
        $this->registerAllNamespaces(Zend_Gdata_Media::$namespaces);
        parent::__construct();
        $this->_url = $url;
        $this->_fileSize = $fileSize;
        $this->_type = $type;
        $this->_medium = $medium;
        $this->_isDefault = $isDefault;
        $this->_expression = $expression;
        $this->_bitrate = $bitrate;
        $this->_framerate = $framerate;
        $this->_samplingrate = $samplingrate;
        $this->_channels = $channels;
        $this->_duration = $duration;
        $this->_height = $height;
        $this->_width = $width;
        $this->_lang = $lang;
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
        if (null !== $this->_url) {
            $element->setAttribute('url', $this->_url);
        }
        if (null !== $this->_fileSize) {
            $element->setAttribute('fileSize', $this->_fileSize);
        }
        if (null !== $this->_type) {
            $element->setAttribute('type', $this->_type);
        }
        if (null !== $this->_medium) {
            $element->setAttribute('medium', $this->_medium);
        }
        if (null !== $this->_isDefault) {
            $element->setAttribute('isDefault', $this->_isDefault);
        }
        if (null !== $this->_expression) {
            $element->setAttribute('expression', $this->_expression);
        }
        if (null !== $this->_bitrate) {
            $element->setAttribute('bitrate', $this->_bitrate);
        }
        if (null !== $this->_framerate) {
            $element->setAttribute('framerate', $this->_framerate);
        }
        if (null !== $this->_samplingrate) {
            $element->setAttribute('samplingrate', $this->_samplingrate);
        }
        if (null !== $this->_channels) {
            $element->setAttribute('channels', $this->_channels);
        }
        if (null !== $this->_duration) {
            $element->setAttribute('duration', $this->_duration);
        }
        if (null !== $this->_height) {
            $element->setAttribute('height', $this->_height);
        }
        if (null !== $this->_width) {
            $element->setAttribute('width', $this->_width);
        }
        if (null !== $this->_lang) {
            $element->setAttribute('lang', $this->_lang);
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
            case 'url':
                $this->_url = $attribute->nodeValue;
                break;
            case 'fileSize':
                $this->_fileSize = $attribute->nodeValue;
                break;
            case 'type':
                $this->_type = $attribute->nodeValue;
                break;
            case 'medium':
                $this->_medium = $attribute->nodeValue;
                break;
            case 'isDefault':
                $this->_isDefault = $attribute->nodeValue;
                break;
            case 'expression':
                $this->_expression = $attribute->nodeValue;
                break;
            case 'bitrate':
                $this->_bitrate = $attribute->nodeValue;
                break;
            case 'framerate':
                $this->_framerate = $attribute->nodeValue;
                break;
            case 'samplingrate':
                $this->_samplingrate = $attribute->nodeValue;
                break;
            case 'channels':
                $this->_channels = $attribute->nodeValue;
                break;
            case 'duration':
                $this->_duration = $attribute->nodeValue;
                break;
            case 'height':
                $this->_height = $attribute->nodeValue;
                break;
            case 'width':
                $this->_width = $attribute->nodeValue;
                break;
            case 'lang':
                $this->_lang = $attribute->nodeValue;
                break;
            default:
                parent::takeAttributeFromDOM($attribute);
        }
    }

    /**
     * Returns the URL representing this MediaContent object.
     *
     * @return string the URL representing this MediaContent object
     */
    public function __toString()
    {
        return $this->getUrl();
    }

    /**
     * @return string The direct URL to the media object
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param string $value The direct URL to the media object
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setUrl($value)
    {
        $this->_url = $value;

        return $this;
    }

    /**
     * @return int The size of the media in bytes
     */
    public function getFileSize()
    {
        return $this->_fileSize;
    }

    /**
     * @param int $value
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setFileSize($value)
    {
        $this->_fileSize = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $value
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setType($value)
    {
        $this->_type = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getMedium()
    {
        return $this->_medium;
    }

    /**
     * @param string $value
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setMedium($value)
    {
        $this->_medium = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDefault()
    {
        return $this->_isDefault;
    }

    /**
     * @param bool $value
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setIsDefault($value)
    {
        $this->_isDefault = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->_expression;
    }

    /**
     * @param string
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setExpression($value)
    {
        $this->_expression = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getBitrate()
    {
        return $this->_bitrate;
    }

    /**
     * @param int
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setBitrate($value)
    {
        $this->_bitrate = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getFramerate()
    {
        return $this->_framerate;
    }

    /**
     * @param int
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setFramerate($value)
    {
        $this->_framerate = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getSamplingrate()
    {
        return $this->_samplingrate;
    }

    /**
     * @param int
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setSamplingrate($value)
    {
        $this->_samplingrate = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getChannels()
    {
        return $this->_channels;
    }

    /**
     * @param int
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setChannels($value)
    {
        $this->_channels = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->_duration;
    }

    /**
     * @param int
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setDuration($value)
    {
        $this->_duration = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * @param int
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setHeight($value)
    {
        $this->_height = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * @param int
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setWidth($value)
    {
        $this->_width = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->_lang;
    }

    /**
     * @param string
     *
     * @return Zend_Gdata_Media_Extension_MediaContent Provides a fluent interface
     */
    public function setLang($value)
    {
        $this->_lang = $value;

        return $this;
    }
}
