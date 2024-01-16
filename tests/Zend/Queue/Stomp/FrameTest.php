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

/*
 * The adapter test class provides a universal test class for all of the
 * abstract methods.
 *
 * All methods marked not supported are explictly checked for for throwing
 * an exception.
 */

/** Zend/Queue/Stomp/Frame.php */
// require_once 'Zend/Queue/Stomp/Frame.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Queue
 */
#[AllowDynamicProperties]
class Zend_Queue_Stomp_FrameTest extends PHPUnit_Framework_TestCase
{
    protected $body = 'hello world'; // 11 characters

    public function testToFromFrame()
    {
        $correct = 'SEND'.Zend_Queue_Stomp_Frame::EOL;
        $correct .= 'content-length: 11'.Zend_Queue_Stomp_Frame::EOL;
        $correct .= Zend_Queue_Stomp_Frame::EOL;
        $correct .= $this->body;
        $correct .= Zend_Queue_Stomp_Frame::END_OF_FRAME;

        $frame = new Zend_Queue_Stomp_Frame();
        $frame->setCommand('SEND');
        $frame->setBody($this->body);
        $this->assertEquals($frame->toFrame(), $correct);

        $frame = new Zend_Queue_Stomp_Frame();
        $frame->fromFrame($correct);
        $this->assertEquals($frame->getCommand(), 'SEND');
        $this->assertEquals($frame->getBody(), $this->body);

        $this->assertEquals($frame->toFrame(), "$frame");

        // fromFrame, but no body
        $correct = 'SEND'.Zend_Queue_Stomp_Frame::EOL;
        $correct .= 'testing: 11'.Zend_Queue_Stomp_Frame::EOL;
        $correct .= Zend_Queue_Stomp_Frame::EOL;
        $correct .= Zend_Queue_Stomp_Frame::END_OF_FRAME;
        $frame->fromFrame($correct);
        $this->assertEquals($frame->getHeader('testing'), 11);
    }

    public function testSetHeaders()
    {
        $frame = new Zend_Queue_Stomp_Frame();
        $frame->setHeaders(['testing' => 1]);
        $this->assertEquals(1, $frame->getHeader('testing'));
    }

    public function testParameters()
    {
        $frame = new Zend_Queue_Stomp_Frame();

        try {
            $frame->setAutoContentLength([]);
            $this->fail('Exception should have been thrown');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $frame->setHeader([], 1);
            $this->fail('Exception should have been thrown');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $frame->setHeader('testing', []);
            $this->fail('Exception should have been thrown');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $frame->getHeader([]);
            $this->fail('Exception should have been thrown');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $frame->setBody([]);
            $this->fail('Exception should have been thrown');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $frame->setCommand([]);
            $this->fail('Exception should have been thrown');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $frame->toFrame();
            $this->fail('Exception should have been thrown');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $frame->fromFrame([]);
            $this->fail('Exception should have been thrown');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }
    }

    public function testConstant()
    {
        $this->assertTrue(is_string(Zend_Queue_Stomp_Frame::END_OF_FRAME));
        $this->assertTrue(is_string(Zend_Queue_Stomp_Frame::CONTENT_LENGTH));
        $this->assertTrue(is_string(Zend_Queue_Stomp_Frame::EOL));
    }
}
