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
 * @version    $Id:$
 */

/**
 * @see Zend_Gdata_Gapps_Query
 */
// require_once('Zend/Gdata/Gapps/Query.php');

/**
 * Assists in constructing queries for Google Apps group entries.
 * Instances of this class can be provided in many places where a URL is
 * required.
 *
 * For information on submitting queries to a server, see the Google Apps
 * service class, Zend_Gdata_Gapps.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Gapps_GroupQuery extends Zend_Gdata_Gapps_Query
{
    /**
     * If not null, specifies the group id of the group who should be
     * retrieved by this query.
     *
     * @var string
     */
    protected $_groupId;

    /**
     * Create a new instance.
     *
     * @param string $domain  (optional) The Google Apps-hosted domain to use
     *                        when constructing query URIs
     * @param string $groupId (optional) Value for the groupId property
     */
    public function __construct($domain = null, $groupId = null,
        $startGroupId = null)
    {
        parent::__construct($domain);
        $this->setGroupId($groupId);
        $this->setStartGroupId($startGroupId);
    }

    /**
     * Set the group id to query for. When set, only groups with a group id
     * matching this value will be returned in search results. Set to
     * null to disable filtering by group id.
     *
     * @see getGroupId
     *
     * @param string $value the group id to filter search results by, or null to
     *                      disable
     */
    public function setGroupId($value)
    {
        $this->_groupId = $value;
    }

    /**
     * Get the group id to query for. If no group id is set, null will be
     * returned.
     */
    public function getGroupId()
    {
        return $this->_groupId;
    }

    /**
     * Set the member to query for. When set, only subscribers with an
     * email address matching this value will be returned in search results.
     * Set to null to disable filtering by username.
     *
     * @param string $value the member email address to filter search
     *                      results by, or null to  disable
     */
    public function setMember($value)
    {
        if (null !== $value) {
            $this->_params['member'] = $value;
        } else {
            unset($this->_params['member']);
        }
    }

    /**
     * Get the member email address to query for. If no member is set,
     * null will be returned.
     *
     * @see setMember
     *
     * @return string the member email address to filter search
     *                results by, or null if disabled
     */
    public function getMember()
    {
        if (array_key_exists('member', $this->_params)) {
            return $this->_params['member'];
        } else {
            return null;
        }
    }

    /**
     * Sets the query parameter directOnly.
     *
     * @param bool $value
     */
    public function setDirectOnly($value)
    {
        if (null !== $value) {
            if (true == $value) {
                $this->_params['directOnly'] = 'true';
            } else {
                $this->_params['directOnly'] = 'false';
            }
        } else {
            unset($this->_params['directOnly']);
        }
    }

    /**
     * @see setDirectOnly
     *
     * @return bool
     */
    public function getDirectOnly()
    {
        if (array_key_exists('directOnly', $this->_params)) {
            if ('true' == $this->_params['directOnly']) {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    /**
     * Set the first group id which should be displayed when retrieving
     * a list of groups.
     *
     * @param string $value the first group id to be returned, or null to
     *                      disable
     */
    public function setStartGroupId($value)
    {
        if (null !== $value) {
            $this->_params['start'] = $value;
        } else {
            unset($this->_params['start']);
        }
    }

    /**
     * Get the first group id which should be displayed when retrieving
     * a list of groups.
     *
     * @see setStartGroupId
     *
     * @return string the first group id to be returned, or null if
     *                disabled
     */
    public function getStartGroupId()
    {
        if (array_key_exists('start', $this->_params)) {
            return $this->_params['start'];
        } else {
            return null;
        }
    }

    /**
     * Returns the query URL generated by this query instance.
     *
     * @return string the query URL for this instance
     */
    public function getQueryUrl()
    {
        $uri = Zend_Gdata_Gapps::APPS_BASE_FEED_URI;
        $uri .= Zend_Gdata_Gapps::APPS_GROUP_PATH;
        $uri .= '/'.$this->_domain;

        if (null !== $this->_groupId) {
            $uri .= '/'.$this->_groupId;
        }

        if (array_key_exists('member', $this->_params)) {
            $uri .= '/';
        }

        $uri .= $this->getQueryString();

        return $uri;
    }
}
