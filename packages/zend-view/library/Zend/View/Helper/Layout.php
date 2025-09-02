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
 * View helper for retrieving layout object.
 */
class Zend_View_Helper_Layout extends Zend_View_Helper_Abstract
{
    /** @var Zend_Layout */
    protected $_layout;

    public function getLayout(): Zend_Layout
    {
        if (null === $this->_layout) {
            $this->_layout = Zend_Layout::getMvcInstance();
            if (null === $this->_layout) {
                // Implicitly creates layout object
                $this->_layout = new Zend_Layout();
            }
        }

        return $this->_layout;
    }

    /**
     * Set layout object.
     *
     * @return static
     */
    public function setLayout(Zend_Layout $layout)
    {
        $this->_layout = $layout;

        return $this;
    }

    public function layout(): Zend_Layout
    {
        return $this->getLayout();
    }
}
