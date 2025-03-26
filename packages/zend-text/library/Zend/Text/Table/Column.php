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
 * @category  Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @version   $Id$
 */

/**
 * @see Zend_Text_Table
 */
// require_once 'Zend/Text/Table.php';

/**
 * @see Zend_Text_MultiByte
 */
// require_once 'Zend/Text/MultiByte.php';

/**
 * Column class for Zend_Text_Table_Row.
 *
 * @category  Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Text_Table_Column
{
    /**
     * Aligns for columns.
     */
    public const ALIGN_LEFT = 'left';
    public const ALIGN_CENTER = 'center';
    public const ALIGN_RIGHT = 'right';

    /**
     * Content of the column.
     *
     * @var string
     */
    protected $_content = '';

    /**
     * Align of the column.
     *
     * @var string
     */
    protected $_align = self::ALIGN_LEFT;

    /**
     * Colspan of the column.
     *
     * @var int
     */
    protected $_colSpan = 1;

    /**
     * Allowed align parameters.
     *
     * @var array
     */
    protected $_allowedAligns = [self::ALIGN_LEFT, self::ALIGN_CENTER, self::ALIGN_RIGHT];

    /**
     * Create a column for a Zend_Text_Table_Row object.
     *
     * @param string $content The content of the column
     * @param string $align   The align of the content
     * @param int    $colSpan The colspan of the column
     * @param string $charset The encoding of the content
     */
    public function __construct($content = null, $align = null, $colSpan = null, $charset = null)
    {
        if (null !== $content) {
            $this->setContent($content, $charset);
        }

        if (null !== $align) {
            $this->setAlign($align);
        }

        if (null !== $colSpan) {
            $this->setColSpan($colSpan);
        }
    }

    /**
     * Set the content.
     *
     * If $charset is not defined, it is assumed that $content is encoded in
     * the charset defined via Zend_Text_Table::setInputCharset() (defaults
     * to utf-8).
     *
     * @param string $content Content of the column
     * @param string $charset The charset of the content
     *
     * @return Zend_Text_Table_Column
     *
     * @throws Zend_Text_Table_Exception When $content is not a string
     */
    public function setContent($content, $charset = null)
    {
        if (false === is_string($content)) {
            // require_once 'Zend/Text/Table/Exception.php';
            throw new Zend_Text_Table_Exception('$content must be a string');
        }

        if (null === $charset) {
            $inputCharset = Zend_Text_Table::getInputCharset();
        } else {
            $inputCharset = strtolower((string) $charset);
        }

        $outputCharset = Zend_Text_Table::getOutputCharset();

        if ($inputCharset !== $outputCharset) {
            if (PHP_OS !== 'AIX') {
                // AIX does not understand these character sets
                $content = iconv($inputCharset, $outputCharset, $content);
            }
        }

        $this->_content = $content;

        return $this;
    }

    /**
     * Set the align.
     *
     * @param string $align Align of the column
     *
     * @return Zend_Text_Table_Column
     *
     * @throws Zend_Text_Table_Exception When supplied align is invalid
     */
    public function setAlign($align)
    {
        if (false === in_array($align, $this->_allowedAligns)) {
            // require_once 'Zend/Text/Table/Exception.php';
            throw new Zend_Text_Table_Exception('Invalid align supplied');
        }

        $this->_align = $align;

        return $this;
    }

    /**
     * Set the colspan.
     *
     * @param int $colSpan
     *
     * @return Zend_Text_Table_Column
     *
     * @throws Zend_Text_Table_Exception When $colSpan is smaller than 1
     */
    public function setColSpan($colSpan)
    {
        if (false === is_int($colSpan) or $colSpan < 1) {
            // require_once 'Zend/Text/Table/Exception.php';
            throw new Zend_Text_Table_Exception('$colSpan must be an integer and greater than 0');
        }

        $this->_colSpan = $colSpan;

        return $this;
    }

    /**
     * Get the colspan.
     *
     * @return int
     */
    public function getColSpan()
    {
        return $this->_colSpan;
    }

    /**
     * Render the column width the given column width.
     *
     * @param int $columnWidth The width of the column
     * @param int $padding     The padding for the column
     *
     * @return string
     *
     * @throws Zend_Text_Table_Exception When $columnWidth is lower than 1
     * @throws Zend_Text_Table_Exception When padding is greater than columnWidth
     */
    public function render($columnWidth, $padding = 0)
    {
        if (false === is_int($columnWidth) or $columnWidth < 1) {
            // require_once 'Zend/Text/Table/Exception.php';
            throw new Zend_Text_Table_Exception('$columnWidth must be an integer and greater than 0');
        }

        $columnWidth -= ($padding * 2);

        if ($columnWidth < 1) {
            // require_once 'Zend/Text/Table/Exception.php';
            throw new Zend_Text_Table_Exception('Padding ('.$padding.') is greater than column width');
        }

        switch ($this->_align) {
            case self::ALIGN_LEFT:
                $padMode = STR_PAD_RIGHT;
                break;

            case self::ALIGN_CENTER:
                $padMode = STR_PAD_BOTH;
                break;

            case self::ALIGN_RIGHT:
                $padMode = STR_PAD_LEFT;
                break;

            default:
                // This can never happen, but the CS tells I have to have it ...
                break;
        }

        $outputCharset = Zend_Text_Table::getOutputCharset();
        $lines = explode("\n", Zend_Text_MultiByte::wordWrap($this->_content, $columnWidth, "\n", true, $outputCharset));
        $paddedLines = [];

        foreach ($lines as $line) {
            $paddedLines[] = str_repeat(' ', $padding)
                           .Zend_Text_MultiByte::strPad($line, $columnWidth, ' ', $padMode, $outputCharset)
                           .str_repeat(' ', $padding);
        }

        $result = implode("\n", $paddedLines);

        return $result;
    }
}
