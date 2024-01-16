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
 * @see Zend_Gdata_Photos
 */
// require_once 'Zend/Gdata/Photos.php';

/**
 * @see Zend_Gdata_Feed
 */
// require_once 'Zend/Gdata/Feed.php';

/**
 * @see Zend_Gdata_Photos_AlbumEntry
 */
// require_once 'Zend/Gdata/Photos/AlbumEntry.php';

/**
 * Data model for a collection of album entries, usually
 * provided by the servers.
 *
 * For information on requesting this feed from a server, see the
 * service class, Zend_Gdata_Photos.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Photos_AlbumFeed extends Zend_Gdata_Feed
{
    protected $_entryClassName = 'Zend_Gdata_Photos_AlbumEntry';
    protected $_feedClassName = 'Zend_Gdata_Photos_AlbumFeed';

    /**
     * gphoto:id element.
     *
     * @var Zend_Gdata_Photos_Extension_Id
     */
    protected $_gphotoId;

    /**
     * gphoto:user element.
     *
     * @var Zend_Gdata_Photos_Extension_User
     */
    protected $_gphotoUser;

    /**
     * gphoto:access element.
     *
     * @var Zend_Gdata_Photos_Extension_Access
     */
    protected $_gphotoAccess;

    /**
     * gphoto:location element.
     *
     * @var Zend_Gdata_Photos_Extension_Location
     */
    protected $_gphotoLocation;

    /**
     * gphoto:nickname element.
     *
     * @var Zend_Gdata_Photos_Extension_Nickname
     */
    protected $_gphotoNickname;

    /**
     * gphoto:timestamp element.
     *
     * @var Zend_Gdata_Photos_Extension_Timestamp
     */
    protected $_gphotoTimestamp;

    /**
     * gphoto:name element.
     *
     * @var Zend_Gdata_Photos_Extension_Name
     */
    protected $_gphotoName;

    /**
     * gphoto:numphotos element.
     *
     * @var Zend_Gdata_Photos_Extension_NumPhotos
     */
    protected $_gphotoNumPhotos;

    /**
     * gphoto:commentCount element.
     *
     * @var Zend_Gdata_Photos_Extension_CommentCount
     */
    protected $_gphotoCommentCount;

    /**
     * gphoto:commentingEnabled element.
     *
     * @var Zend_Gdata_Photos_Extension_CommentingEnabled
     */
    protected $_gphotoCommentingEnabled;

    protected $_entryKindClassMapping = [
        'http://schemas.google.com/photos/2007#photo' => 'Zend_Gdata_Photos_PhotoEntry',
        'http://schemas.google.com/photos/2007#comment' => 'Zend_Gdata_Photos_CommentEntry',
        'http://schemas.google.com/photos/2007#tag' => 'Zend_Gdata_Photos_TagEntry',
    ];

    public function __construct($element = null)
    {
        $this->registerAllNamespaces(Zend_Gdata_Photos::$namespaces);
        parent::__construct($element);
    }

    public function getDOM($doc = null, $majorVersion = 1, $minorVersion = null)
    {
        $element = parent::getDOM($doc, $majorVersion, $minorVersion);
        if (null != $this->_gphotoId) {
            $element->appendChild($this->_gphotoId->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoUser) {
            $element->appendChild($this->_gphotoUser->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoNickname) {
            $element->appendChild($this->_gphotoNickname->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoName) {
            $element->appendChild($this->_gphotoName->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoLocation) {
            $element->appendChild($this->_gphotoLocation->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoAccess) {
            $element->appendChild($this->_gphotoAccess->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoTimestamp) {
            $element->appendChild($this->_gphotoTimestamp->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoNumPhotos) {
            $element->appendChild($this->_gphotoNumPhotos->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoCommentingEnabled) {
            $element->appendChild($this->_gphotoCommentingEnabled->getDOM($element->ownerDocument));
        }
        if (null != $this->_gphotoCommentCount) {
            $element->appendChild($this->_gphotoCommentCount->getDOM($element->ownerDocument));
        }

        return $element;
    }

    protected function takeChildFromDOM($child)
    {
        $absoluteNodeName = $child->namespaceURI.':'.$child->localName;

        switch ($absoluteNodeName) {
            case $this->lookupNamespace('gphoto').':id':
                $id = new Zend_Gdata_Photos_Extension_Id();
                $id->transferFromDOM($child);
                $this->_gphotoId = $id;
                break;
            case $this->lookupNamespace('gphoto').':user':
                $user = new Zend_Gdata_Photos_Extension_User();
                $user->transferFromDOM($child);
                $this->_gphotoUser = $user;
                break;
            case $this->lookupNamespace('gphoto').':nickname':
                $nickname = new Zend_Gdata_Photos_Extension_Nickname();
                $nickname->transferFromDOM($child);
                $this->_gphotoNickname = $nickname;
                break;
            case $this->lookupNamespace('gphoto').':name':
                $name = new Zend_Gdata_Photos_Extension_Name();
                $name->transferFromDOM($child);
                $this->_gphotoName = $name;
                break;
            case $this->lookupNamespace('gphoto').':location':
                $location = new Zend_Gdata_Photos_Extension_Location();
                $location->transferFromDOM($child);
                $this->_gphotoLocation = $location;
                break;
            case $this->lookupNamespace('gphoto').':access':
                $access = new Zend_Gdata_Photos_Extension_Access();
                $access->transferFromDOM($child);
                $this->_gphotoAccess = $access;
                break;
            case $this->lookupNamespace('gphoto').':timestamp':
                $timestamp = new Zend_Gdata_Photos_Extension_Timestamp();
                $timestamp->transferFromDOM($child);
                $this->_gphotoTimestamp = $timestamp;
                break;
            case $this->lookupNamespace('gphoto').':numphotos':
                $numphotos = new Zend_Gdata_Photos_Extension_NumPhotos();
                $numphotos->transferFromDOM($child);
                $this->_gphotoNumPhotos = $numphotos;
                break;
            case $this->lookupNamespace('gphoto').':commentingEnabled':
                $commentingEnabled = new Zend_Gdata_Photos_Extension_CommentingEnabled();
                $commentingEnabled->transferFromDOM($child);
                $this->_gphotoCommentingEnabled = $commentingEnabled;
                break;
            case $this->lookupNamespace('gphoto').':commentCount':
                $commentCount = new Zend_Gdata_Photos_Extension_CommentCount();
                $commentCount->transferFromDOM($child);
                $this->_gphotoCommentCount = $commentCount;
                break;
            case $this->lookupNamespace('atom').':entry':
                $entryClassName = $this->_entryClassName;
                $tmpEntry = new Zend_Gdata_App_Entry($child);
                $categories = $tmpEntry->getCategory();
                foreach ($categories as $category) {
                    if (Zend_Gdata_Photos::KIND_PATH == $category->scheme
                        && '' != $this->_entryKindClassMapping[$category->term]) {
                        $entryClassName = $this->_entryKindClassMapping[$category->term];
                        break;
                    } else {
                        // require_once 'Zend/Gdata/App/Exception.php';
                        throw new Zend_Gdata_App_Exception('Entry is missing kind declaration.');
                    }
                }

                $newEntry = new $entryClassName($child);
                $newEntry->setHttpClient($this->getHttpClient());
                $this->_entry[] = $newEntry;
                break;
            default:
                parent::takeChildFromDOM($child);
                break;
        }
    }

    /**
     * Get the value for this element's gphoto:user attribute.
     *
     * @return Zend_Gdata_Photos_Extension_User|null the requested attribute
     *
     *@see setGphotoUser
     */
    public function getGphotoUser()
    {
        return $this->_gphotoUser;
    }

    /**
     * Set the value for this element's gphoto:user attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoUser($value)
    {
        $this->_gphotoUser = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:access attribute.
     *
     * @return Zend_Gdata_Photos_Extension_Access|null the requested attribute
     *
     *@see setGphotoAccess
     */
    public function getGphotoAccess()
    {
        return $this->_gphotoAccess;
    }

    /**
     * Set the value for this element's gphoto:access attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoAccess($value)
    {
        $this->_gphotoAccess = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:location attribute.
     *
     * @return Zend_Gdata_Photos_Extension_Location|null the requested attribute
     *
     * @see setGphotoLocation
     */
    public function getGphotoLocation()
    {
        return $this->_gphotoLocation;
    }

    /**
     * Set the value for this element's gphoto:location attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoLocation($value)
    {
        $this->_gphotoLocation = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:name attribute.
     *
     * @return Zend_Gdata_Photos_Extension_Name|null the requested attribute
     *
     *@see setGphotoName
     */
    public function getGphotoName()
    {
        return $this->_gphotoName;
    }

    /**
     * Set the value for this element's gphoto:name attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoName($value)
    {
        $this->_gphotoName = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:numphotos attribute.
     *
     * @return Zend_Gdata_Photos_Extension_NumPhotos|null the requested attribute
     *
     * @see setGphotoNumPhotos
     */
    public function getGphotoNumPhotos()
    {
        return $this->_gphotoNumPhotos;
    }

    /**
     * Set the value for this element's gphoto:numphotos attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoNumPhotos($value)
    {
        $this->_gphotoNumPhotos = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:commentCount attribute.
     *
     * @return Zend_Gdata_Photos_Extension_CommentCount|null the requested attribute
     *
     * @see setGphotoCommentCount
     */
    public function getGphotoCommentCount()
    {
        return $this->_gphotoCommentCount;
    }

    /**
     * Set the value for this element's gphoto:commentCount attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoCommentCount($value)
    {
        $this->_gphotoCommentCount = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:commentingEnabled attribute.
     *
     * @return Zend_Gdata_Photos_Extension_CommentingEnabled|null the requested attribute
     *
     * @see setGphotoCommentingEnabled
     */
    public function getGphotoCommentingEnabled()
    {
        return $this->_gphotoCommentingEnabled;
    }

    /**
     * Set the value for this element's gphoto:commentingEnabled attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoCommentingEnabled($value)
    {
        $this->_gphotoCommentingEnabled = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:id attribute.
     *
     * @return Zend_Gdata_Photos_Extension_Id|null the requested attribute
     *
     *@see setGphotoId
     */
    public function getGphotoId()
    {
        return $this->_gphotoId;
    }

    /**
     * Set the value for this element's gphoto:id attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoId($value)
    {
        $this->_gphotoId = $value;

        return $this;
    }

    /**
     * Get the value for this element's georss:where attribute.
     *
     * @see setGeoRssWhere
     *
     * @return string the requested attribute
     */
    public function getGeoRssWhere()
    {
        return $this->_geoRssWhere;
    }

    /**
     * Set the value for this element's georss:where attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGeoRssWhere($value)
    {
        $this->_geoRssWhere = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:nickname attribute.
     *
     * @return Zend_Gdata_Photos_Extension_Nickname|null the requested attribute
     *
     *@see setGphotoNickname
     */
    public function getGphotoNickname()
    {
        return $this->_gphotoNickname;
    }

    /**
     * Set the value for this element's gphoto:nickname attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoNickname($value)
    {
        $this->_gphotoNickname = $value;

        return $this;
    }

    /**
     * Get the value for this element's gphoto:timestamp attribute.
     *
     * @return Zend_Gdata_Photos_Extension_Timestamp|null the requested attribute
     *
     * @see setGphotoTimestamp
     */
    public function getGphotoTimestamp()
    {
        return $this->_gphotoTimestamp;
    }

    /**
     * Set the value for this element's gphoto:timestamp attribute.
     *
     * @param string $value the desired value for this attribute
     *
     * @return Zend_Gdata_Photos_AlbumFeed the element being modified
     */
    public function setGphotoTimestamp($value)
    {
        $this->_gphotoTimestamp = $value;

        return $this;
    }
}
