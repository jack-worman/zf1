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
 * @version    $Id $
 */

// require_once 'Zend/Gdata/Calendar.php';
// require_once 'Zend/Gdata/Calendar/EventQuery.php';
// require_once 'Zend/Http/Client.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Calendar
 */
#[AllowDynamicProperties]
class Zend_Gdata_Calendar_EventQueryTest extends PHPUnit_Framework_TestCase
{
    public const GOOGLE_DEVELOPER_CALENDAR = 'developer-calendar@google.com';
    public const ZEND_CONFERENCE_EVENT = 'bn2h4o4mc3a03ci4t48j3m56pg';
    public const ZEND_CONFERENCE_EVENT_COMMENT = 'i9q87onko1uphfs7i21elnnb4g';
    public const SAMPLE_RFC3339 = '2007-06-05T18:38:00';

    public function setUp()
    {
        $this->query = new Zend_Gdata_Calendar_EventQuery();
    }

    public function testDefaultBaseUrlForQuery()
    {
        $queryUrl = $this->query->getQueryUrl();
        $this->assertEquals('https://www.google.com/calendar/feeds/default/public/full',
            $queryUrl);
    }

    public function testAlternateBaseUrlForQuery()
    {
        $this->query = new Zend_Gdata_Calendar_EventQuery('http://www.foo.com');
        $queryUrl = $this->query->getQueryUrl();
        // the URL passed in the constructor has the user, visibility
        // projection appended for the return value of $query->getQueryUrl()
        $this->assertEquals('http://www.foo.com/default/public/full', $queryUrl);
    }

    public function testUpdatedMinMaxParam()
    {
        $updatedMin = '2006-09-20';
        $updatedMax = '2006-11-05';
        $this->query->resetParameters();
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setUpdatedMin($updatedMin);
        $this->query->setUpdatedMax($updatedMax);
        $this->assertTrue(null != $this->query->updatedMin);
        $this->assertTrue(null != $this->query->updatedMax);
        $this->assertTrue(null != $this->query->user);
        $this->assertEquals(Zend_Gdata_App_Util::formatTimestamp($updatedMin), $this->query->getUpdatedMin());
        $this->assertEquals(Zend_Gdata_App_Util::formatTimestamp($updatedMax), $this->query->getUpdatedMax());
        $this->assertEquals(self::GOOGLE_DEVELOPER_CALENDAR, $this->query->getUser());

        $this->query->updatedMin = null;
        $this->assertFalse(null != $this->query->updatedMin);
        $this->query->updatedMax = null;
        $this->assertFalse(null != $this->query->updatedMax);
        $this->query->user = null;
        $this->assertFalse(null != $this->query->user);
    }

    public function testStartMinMaxParam()
    {
        $this->query->resetParameters();
        $startMin = '2006-10-30';
        $startMax = '2006-11-01';
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setStartMin($startMin);
        $this->query->setStartMax($startMax);
        $this->assertTrue(null != $this->query->startMin);
        $this->assertTrue(null != $this->query->startMax);
        $this->assertEquals(Zend_Gdata_App_Util::formatTimestamp($startMin), $this->query->getStartMin());
        $this->assertEquals(Zend_Gdata_App_Util::formatTimestamp($startMax), $this->query->getStartMax());

        $this->query->startMin = null;
        $this->assertFalse(null != $this->query->startMin);
        $this->query->startMax = null;
        $this->assertFalse(null != $this->query->startMax);
        $this->query->user = null;
        $this->assertFalse(null != $this->query->user);
    }

    public function testVisibilityParam()
    {
        $this->query->resetParameters();
        $visibility = 'private';
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setVisibility($visibility);
        $this->assertTrue(null != $this->query->visibility);
        $this->assertEquals($visibility, $this->query->getVisibility());
        $this->query->visibility = null;
        $this->assertFalse(null != $this->query->visibility);
    }

    public function testProjectionParam()
    {
        $this->query->resetParameters();
        $projection = 'composite';
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setProjection($projection);
        $this->assertTrue(null != $this->query->projection);
        $this->assertEquals($projection, $this->query->getProjection());
        $this->query->projection = null;
        $this->assertFalse(null != $this->query->projection);
    }

    public function testOrderbyParam()
    {
        $this->query->resetParameters();
        $orderby = 'starttime';
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setOrderby($orderby);
        $this->assertTrue(null != $this->query->orderby);
        $this->assertEquals($orderby, $this->query->getOrderby());
        $this->query->orderby = null;
        $this->assertFalse(null != $this->query->orderby);
    }

    public function testEventParam()
    {
        $this->query->resetParameters();
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setEvent(self::ZEND_CONFERENCE_EVENT);
        $this->assertTrue(null != $this->query->event);
        $this->assertEquals(self::ZEND_CONFERENCE_EVENT, $this->query->getEvent());
        $this->query->event = null;
        $this->assertFalse(null != $this->query->event);
    }

    public function testCommentsParam()
    {
        $this->query->resetParameters();
        $comment = 'we need to reschedule';
        $this->query->setComments($comment);
        $this->assertTrue(null != $this->query->comments);
        $this->assertEquals($comment, $this->query->getComments());
        $this->query->comments = null;
        $this->assertFalse(isset($this->query->comments));
    }

    public function testSortOrder()
    {
        $this->query->resetParameters();
        $sortOrder = 'ascending';
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setSortOrder($sortOrder);
        $this->assertTrue(null != $this->query->sortOrder);
        $this->assertEquals($sortOrder, $this->query->getSortOrder());
        $this->query->sortOrder = null;
        $this->assertFalse(null != $this->query->sortOrder);
    }

    public function testRecurrenceExpansionStart()
    {
        $this->query->resetParameters();
        $res = self::SAMPLE_RFC3339;
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setRecurrenceExpansionStart($res);
        $this->assertTrue(null != $this->query->recurrenceExpansionStart);
        $this->assertEquals($res, $this->query->getRecurrenceExpansionStart());
        $this->query->recurrenceExpansionStart = null;
        $this->assertFalse(null != $this->query->recurrenceExpansionStart);
    }

    public function testRecurrenceExpansionEnd()
    {
        $this->query->resetParameters();
        $ree = self::SAMPLE_RFC3339;
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setRecurrenceExpansionEnd($ree);
        $this->assertTrue(null != $this->query->recurrenceExpansionEnd);
        $this->assertEquals($ree, $this->query->getRecurrenceExpansionEnd());
        $this->query->recurrenceExpansionEnd = null;
        $this->assertFalse(null != $this->query->recurrenceExpansionEnd);
    }

    public function testSingleEvents()
    {
        $this->query->resetParameters();
        // Test string handling
        $singleEvents = 'true';
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setSingleEvents($singleEvents);
        $this->assertTrue(true === $this->query->singleEvents);
        // Test bool handling
        $singleEvents = false;
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setSingleEvents($singleEvents);
        $this->assertTrue(false === $this->query->singleEvents);
        // Test unsetting
        $this->assertEquals($singleEvents, $this->query->getSingleEvents());
        $this->query->setSingleEvents(null);
        $this->assertFalse(null != $this->query->singleEvents);
    }

    public function testFutureEvents()
    {
        $this->query->resetParameters();
        // Test string handling
        $singleEvents = 'true';
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setFutureEvents($singleEvents);
        $this->assertTrue(true === $this->query->futureEvents);
        // Test bool handling
        $singleEvents = false;
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setFutureEvents($singleEvents);
        $this->assertTrue(false === $this->query->futureEvents);
        // Test unsetting
        $this->query->futureEvents = null;
        $this->assertFalse(null != $this->query->futureEvents);
    }

    public function testCustomQueryURIGeneration()
    {
        $this->query->resetParameters();
        $this->query->setUser(self::GOOGLE_DEVELOPER_CALENDAR);
        $this->query->setVisibility('private');
        $this->query->setProjection('composite');
        $this->query->setEvent(self::ZEND_CONFERENCE_EVENT);
        $this->query->setComments(self::ZEND_CONFERENCE_EVENT_COMMENT);
        $this->assertEquals('https://www.google.com/calendar/feeds/developer-calendar@google.com/private/composite/'.
                self::ZEND_CONFERENCE_EVENT.'/comments/'.self::ZEND_CONFERENCE_EVENT_COMMENT,
            $this->query->getQueryUrl());
    }

    public function testDefaultQueryURIGeneration()
    {
        $this->query->resetParameters();
        $this->assertEquals('https://www.google.com/calendar/feeds/default/public/full',
            $this->query->getQueryUrl());
    }

    public function testCanNullifyParameters()
    {
        $testURI = 'http://www.google.com/calendar/feeds/foo%40group.calendar.google.com/private/full';
        $this->query = new Zend_Gdata_Calendar_EventQuery($testURI);
        $this->query->setUser(null);
        $this->query->setVisibility(null);
        $this->query->setProjection(null);
        $result = $this->query->getQueryUrl();
        $this->assertEquals($testURI, $result);
    }
}
