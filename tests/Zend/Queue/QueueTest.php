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

/** Zend_Queue */
// require_once 'Zend/Queue.php';

/** Zend_Queue */
// require_once 'Zend/Queue/Message.php';

/** Zend_Queue_Adapter_Array */
// require_once 'Zend/Queue/Adapter/Array.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Queue
 */
#[AllowDynamicProperties]
class Zend_Queue_QueueTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        // Test Zend_Config
        $this->config = [
            'name' => 'queue1',
            'params' => [],
        ];

        $this->queue = new Zend_Queue('array', $this->config);
    }

    protected function tearDown()
    {
    }

    public function testConst()
    {
        $this->assertTrue(is_string(Zend_Queue::TIMEOUT));
        $this->assertTrue(is_integer(Zend_Queue::VISIBILITY_TIMEOUT));
        $this->assertTrue(is_string(Zend_Queue::NAME));
    }

    /**
     * Constructor.
     */
    public function testConstruct()
    {
        // Test Zend_Config
        $config = [
            'name' => 'queue1',
            'params' => [],
            'adapter' => 'array',
        ];

        // require_once "Zend/Config.php";
        $zend_config = new Zend_Config($config);

        $obj = new Zend_Queue($config);
        $this->assertTrue($obj instanceof Zend_Queue);

        try {
            $obj = new Zend_Queue('ops');
            $this->fail('Zend_Queue cannot accept a string');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }
    }

    public function testGetOptions()
    {
        $config = $this->queue->getOptions();
        $this->assertTrue(is_array($config));
        $this->assertEquals($this->config['name'], $config['name']);
    }

    public function testSetGetAdapter()
    {
        $adapter = new Zend_Queue_Adapter_Array($this->config);
        $this->assertTrue($this->queue->setAdapter($adapter) instanceof Zend_Queue);
        $this->assertTrue($this->queue->getAdapter($adapter) instanceof Zend_Queue_Adapter_Array);
    }

    public function testSetGetMessageClass()
    {
        $class = 'test';
        $this->assertTrue($this->queue->setMessageClass($class) instanceof Zend_Queue);
        $this->assertEquals($class, $this->queue->getMessageClass());
    }

    public function testSetGetMessageSetClass()
    {
        $class = 'test';
        $this->assertTrue($this->queue->setMessageSetClass($class) instanceof Zend_Queue);
        $this->assertEquals($class, $this->queue->getMessageSetClass());
    }

    public function testSetGetName()
    {
        // $this->assertTrue($this->queue->setName($new) instanceof Zend_Queue);
        $this->assertEquals($this->config['name'], $this->queue->getName());
    }

    public function testCreateDeleteQueue()
    {
        // parameter testing
        try {
            $this->queue->createQueue([]);
            $this->fail('createQueue() $name must be a string');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $this->queue->createQueue('test', 'test');
            $this->fail('createQueue() $timeout must be an integer');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        // isExists
        $queue = 'test';
        $new = $this->queue->createQueue($queue);
        $this->assertTrue($new instanceof Zend_Queue);
        $this->assertFalse($this->queue->createQueue($queue));

        $this->assertTrue($new->deleteQueue());
    }

    public function testSendCountReceiveDeleteMessage()
    {
        // ------------------------------------ send()
        // parameter verification
        try {
            $this->queue->send([]);
            $this->fail('send() $mesage must be a string');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        $message = 'Hello world'; // never gets boring!
        $this->assertTrue($this->queue->send($message) instanceof Zend_Queue_Message);

        // ------------------------------------ count()
        $this->assertEquals($this->queue->count(), 1);

        // ------------------------------------ receive()
        // parameter verification
        try {
            $this->queue->receive([]);
            $this->fail('receive() $maxMessages must be a integer or null');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        try {
            $this->queue->receive(1, []);
            $this->fail('receive() $timeout must be a integer or null');
        } catch (Throwable $e) {
            $this->assertTrue(true);
        }

        $messages = $this->queue->receive();
        $this->assertTrue($messages instanceof Zend_Queue_Message_Iterator);

        // ------------------------------------ deleteMessage()
        foreach ($messages as $i => $message) {
            $this->assertTrue($this->queue->deleteMessage($message));
        }
    }

    public function testCapabilities()
    {
        $list = $this->queue->getCapabilities();
        $this->assertTrue(is_array($list));

        // these functions must have an boolean answer
        $func = [
            'create', 'delete', 'send', 'receive',
            'deleteMessage', 'getQueues', 'count',
            'isExists',
        ];

        foreach (array_values($func) as $f) {
            $this->assertTrue(isset($list[$f]));
            $this->assertTrue(is_bool($list[$f]));
        }
    }

    public function testIsSupported()
    {
        $list = $this->queue->getCapabilities();
        foreach ($list as $function => $result) {
            $this->assertTrue(is_bool($result));
            if ($result) {
                $this->assertTrue($this->queue->isSupported($function));
            } else {
                $this->assertFalse($this->queue->isSupported($function));
            }
        }
    }

    public function testGetQueues()
    {
        $queues = $this->queue->getQueues();
        $this->assertTrue(is_array($queues));
        $this->assertTrue(in_array($this->config['name'], $queues));
    }
}
