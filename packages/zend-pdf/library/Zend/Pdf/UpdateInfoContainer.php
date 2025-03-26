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
 * Container which collects updated object info.
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_UpdateInfoContainer
{
    /**
     * Object number.
     *
     * @var int
     */
    private $_objNum;

    /**
     * Generation number.
     *
     * @var int
     */
    private $_genNum;

    /**
     * Flag, which signals, that object is free.
     *
     * @var bool
     */
    private $_isFree;

    /**
     * String representation of the object.
     *
     * @var Zend_Memory_Container|null
     */
    private $_dump;

    /**
     * Object constructor.
     */
    public function __construct($objNum, $genNum, $isFree, $dump = null)
    {
        $this->_objNum = $objNum;
        $this->_genNum = $genNum;
        $this->_isFree = $isFree;

        if (null !== $dump) {
            if (strlen((string) $dump) > 1024) {
                // require_once 'Zend/Pdf.php';
                $this->_dump = Zend_Pdf::getMemoryManager()->create($dump);
            } else {
                $this->_dump = $dump;
            }
        }
    }

    /**
     * Get object number.
     *
     * @return int
     */
    public function getObjNum()
    {
        return $this->_objNum;
    }

    /**
     * Get generation number.
     *
     * @return int
     */
    public function getGenNum()
    {
        return $this->_genNum;
    }

    /**
     * Check, that object is free.
     *
     * @return bool
     */
    public function isFree()
    {
        return $this->_isFree;
    }

    /**
     * Get string representation of the object.
     *
     * @return string
     */
    public function getObjectDump()
    {
        if (null === $this->_dump) {
            return '';
        }

        if (is_string($this->_dump)) {
            return $this->_dump;
        }

        return $this->_dump->getRef();
    }
}
