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
 */

/**
 * Create and send autocompletion lists.
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 */
abstract class Zend_Controller_Action_Helper_AutoComplete_Abstract extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Suppress exit when sendJson() called.
     *
     * @var bool
     */
    public $suppressExit = false;

    /**
     * Validate autocompletion data.
     *
     * @return bool
     */
    abstract public function validateData($data);

    /**
     * Prepare autocompletion data.
     *
     * @param bool $keepLayouts
     */
    abstract public function prepareAutoCompletion($data, $keepLayouts = false);

    /**
     * Disable layouts and view renderer.
     *
     * @return Zend_Controller_Action_Helper_AutoComplete_Abstract Provides a fluent interface
     */
    public function disableLayouts()
    {
        if (null !== ($layout = Zend_Layout::getMvcInstance())) {
            $layout->disableLayout();
        }

        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);

        return $this;
    }

    /**
     * Encode data to JSON.
     *
     * @param bool $keepLayouts
     *
     * @return string
     *
     * @throws Zend_Controller_Action_Exception
     */
    public function encodeJson($data, $keepLayouts = false)
    {
        if ($this->validateData($data)) {
            return Zend_Controller_Action_HelperBroker::getStaticHelper('Json')->encodeJson($data, $keepLayouts);
        }

        throw new Zend_Controller_Action_Exception('Invalid data passed for autocompletion');
    }

    /**
     * Send autocompletion data.
     *
     * Calls prepareAutoCompletion, populates response body with this
     * information, and sends response.
     *
     * @param bool $keepLayouts
     *
     * @return string|void
     */
    public function sendAutoCompletion($data, $keepLayouts = false)
    {
        $data = $this->prepareAutoCompletion($data, $keepLayouts);

        $response = $this->getResponse();
        $response->setBody($data);

        if (!$this->suppressExit) {
            $response->sendResponse();
            exit;
        }

        return $data;
    }

    /**
     * Strategy pattern: allow calling helper as broker method.
     *
     * Prepares autocompletion data and, if $sendNow is true, immediately sends
     * response.
     *
     * @param bool $sendNow
     * @param bool $keepLayouts
     *
     * @return string|void
     */
    public function direct($data, $sendNow = true, $keepLayouts = false)
    {
        if ($sendNow) {
            return $this->sendAutoCompletion($data, $keepLayouts);
        }

        return $this->prepareAutoCompletion($data, $keepLayouts);
    }
}
