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

/** Internally used classes */
// require_once 'Zend/Pdf/Element/Array.php';
// require_once 'Zend/Pdf/Element/String/Binary.php';
// require_once 'Zend/Pdf/Element/Boolean.php';
// require_once 'Zend/Pdf/Element/Dictionary.php';
// require_once 'Zend/Pdf/Element/Name.php';
// require_once 'Zend/Pdf/Element/Null.php';
// require_once 'Zend/Pdf/Element/Numeric.php';
// require_once 'Zend/Pdf/Element/Object.php';
// require_once 'Zend/Pdf/Element/Object/Stream.php';
// require_once 'Zend/Pdf/Element/Reference.php';
// require_once 'Zend/Pdf/Element/String.php';

/**
 * PDF string parser.
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_StringParser
{
    /**
     * Source PDF.
     *
     * @var string
     */
    public $data = '';

    /**
     * Current position in a data.
     *
     * @var int
     */
    public $offset = 0;

    /**
     * Current reference context.
     *
     * @var Zend_Pdf_Element_Reference_Context
     */
    private $_context;

    /**
     * Array of elements of the currently parsed object/trailer.
     *
     * @var array
     */
    private $_elements = [];

    /**
     * PDF objects factory.
     *
     * @var Zend_Pdf_ElementFactory_Interface
     */
    private $_objFactory;

    /**
     * Clean up resources.
     *
     * Clear current state to remove cyclic object references
     */
    public function cleanUp()
    {
        $this->_context = null;
        $this->_elements = [];
        $this->_objFactory = null;
    }

    /**
     * Character with code $chCode is white space.
     *
     * @param int $chCode
     *
     * @return bool
     */
    public static function isWhiteSpace($chCode)
    {
        if (0x00 == $chCode // null character
            || 0x09 == $chCode // Tab
            || 0x0A == $chCode // Line feed
            || 0x0C == $chCode // Form Feed
            || 0x0D == $chCode // Carriage return
            || 0x20 == $chCode    // Space
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Character with code $chCode is a delimiter character.
     *
     * @param int $chCode
     *
     * @return bool
     */
    public static function isDelimiter($chCode)
    {
        if (0x28 == $chCode // '('
            || 0x29 == $chCode // ')'
            || 0x3C == $chCode // '<'
            || 0x3E == $chCode // '>'
            || 0x5B == $chCode // '['
            || 0x5D == $chCode // ']'
            || 0x7B == $chCode // '{'
            || 0x7D == $chCode // '}'
            || 0x2F == $chCode // '/'
            || 0x25 == $chCode    // '%'
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Skip white space.
     *
     * @param bool $skipComment
     */
    public function skipWhiteSpace($skipComment = true)
    {
        if ($skipComment) {
            while (true) {
                $this->offset += strspn($this->data, "\x00\t\n\f\r ", $this->offset);

                if ($this->offset < strlen((string) $this->data) && '%' == $this->data[$this->offset]) {
                    // Skip comment
                    $this->offset += strcspn($this->data, "\r\n", $this->offset);
                } else {
                    // Non white space character not equal to '%' is found
                    return;
                }
            }
        } else {
            $this->offset += strspn($this->data, "\x00\t\n\f\r ", $this->offset);
        }

        //        /** Original (non-optimized) implementation. */
        //
        //        while ($this->offset < strlen((string) $this->data)) {
        //            if (strpos((string) "\x00\t\n\f\r ", $this->data[$this->offset]) !== false) {
        //                $this->offset++;
        //            } else if (ord((string) $this->data[$this->offset]) == 0x25 && $skipComment) { // '%'
        //                $this->skipComment();
        //            } else {
        //                return;
        //            }
        //        }
    }

    /**
     * Skip comment.
     */
    public function skipComment()
    {
        while ($this->offset < strlen((string) $this->data)) {
            if (0x0A != ord((string) $this->data[$this->offset]) // Line feed
                || 0x0D != ord((string) $this->data[$this->offset])    // Carriage return
            ) {
                ++$this->offset;
            } else {
                return;
            }
        }
    }

    /**
     * Read comment line.
     *
     * @return string
     */
    public function readComment()
    {
        $this->skipWhiteSpace(false);

        /* Check if it's a comment line */
        if ('%' != $this->data[$this->offset]) {
            return '';
        }

        for ($start = $this->offset;
            $this->offset < strlen((string) $this->data);
            ++$this->offset) {
            if (0x0A == ord((string) $this->data[$this->offset]) // Line feed
                || 0x0D == ord((string) $this->data[$this->offset])    // Carriage return
            ) {
                break;
            }
        }

        return substr((string) $this->data, $start, $this->offset - $start);
    }

    /**
     * Returns next lexeme from a pdf stream.
     *
     * @return string
     */
    public function readLexeme()
    {
        // $this->skipWhiteSpace();
        while (true) {
            $this->offset += strspn($this->data, "\x00\t\n\f\r ", $this->offset);

            if ($this->offset < strlen((string) $this->data) && '%' == $this->data[$this->offset]) {
                $this->offset += strcspn($this->data, "\r\n", $this->offset);
            } else {
                break;
            }
        }

        if ($this->offset >= strlen((string) $this->data)) {
            return '';
        }

        if ( /* self::isDelimiter( ord((string) $this->data[$start]) ) */
            false !== strpos((string) '()<>[]{}/%', $this->data[$this->offset])) {
            switch (substr((string) $this->data, $this->offset, 2)) {
                case '<<':
                    $this->offset += 2;

                    return '<<';
                    break;

                case '>>':
                    $this->offset += 2;

                    return '>>';
                    break;

                default:
                    return $this->data[$this->offset++];
                    break;
            }
        } else {
            $start = $this->offset;
            $compare = "()<>[]{}/%\x00\t\n\f\r ";

            $this->offset += strcspn($this->data, $compare, $this->offset);

            return substr((string) $this->data, $start, $this->offset - $start);
        }
    }

    /**
     * Read elemental object from a PDF stream.
     *
     * @return Zend_Pdf_Element
     *
     * @throws Zend_Pdf_Exception
     */
    public function readElement($nextLexeme = null)
    {
        if (null === $nextLexeme) {
            $nextLexeme = $this->readLexeme();
        }

        /*
         * Note: readElement() method is a public method and could be invoked from other classes.
         * If readElement() is used not by Zend_Pdf_StringParser::getObject() method, then we should not care
         * about _elements member management.
         */
        switch ($nextLexeme) {
            case '(':
                return $this->_elements[] = $this->_readString();

            case '<':
                return $this->_elements[] = $this->_readBinaryString();

            case '/':
                return $this->_elements[] = new Zend_Pdf_Element_Name(
                    Zend_Pdf_Element_Name::unescape($this->readLexeme())
                );

            case '[':
                return $this->_elements[] = $this->_readArray();

            case '<<':
                return $this->_elements[] = $this->_readDictionary();

            case ')':
                // fall through to next case
            case '>':
                // fall through to next case
            case ']':
                // fall through to next case
            case '>>':
                // fall through to next case
            case '{':
                // fall through to next case
            case '}':
                // require_once 'Zend/Pdf/Exception.php';
                throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X.', $this->offset));
            default:
                if (0 == strcasecmp($nextLexeme, 'true')) {
                    return $this->_elements[] = new Zend_Pdf_Element_Boolean(true);
                } elseif (0 == strcasecmp($nextLexeme, 'false')) {
                    return $this->_elements[] = new Zend_Pdf_Element_Boolean(false);
                } elseif (0 == strcasecmp($nextLexeme, 'null')) {
                    return $this->_elements[] = new Zend_Pdf_Element_Null();
                }

                $ref = $this->_readReference($nextLexeme);
                if (null !== $ref) {
                    return $this->_elements[] = $ref;
                }

                return $this->_elements[] = $this->_readNumeric($nextLexeme);
        }
    }

    /**
     * Read string PDF object
     * Also reads trailing ')' from a pdf stream.
     *
     * @return Zend_Pdf_Element_String
     *
     * @throws Zend_Pdf_Exception
     */
    private function _readString()
    {
        $start = $this->offset;
        $openedBrackets = 1;

        $this->offset += strcspn($this->data, '()\\', $this->offset);

        while ($this->offset < strlen((string) $this->data)) {
            switch (ord((string) $this->data[$this->offset])) {
                case 0x28: // '(' - opened bracket in the string, needs balanced pair.
                    $this->offset++;
                    ++$openedBrackets;
                    break;

                case 0x29: // ')' - pair to the opened bracket
                    $this->offset++;
                    --$openedBrackets;
                    break;

                case 0x5C: // '\\' - escape sequence, skip next char from a check
                    $this->offset += 2;
            }

            if (0 == $openedBrackets) {
                break; // end of string
            }

            $this->offset += strcspn($this->data, '()\\', $this->offset);
        }
        if (0 != $openedBrackets) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Unexpected end of file while string reading. Offset - 0x%X. \')\' expected.', $start));
        }

        return new Zend_Pdf_Element_String(Zend_Pdf_Element_String::unescape(substr((string) $this->data,
            $start,
            $this->offset - $start - 1)));
    }

    /**
     * Read binary string PDF object
     * Also reads trailing '>' from a pdf stream.
     *
     * @return Zend_Pdf_Element_String_Binary
     *
     * @throws Zend_Pdf_Exception
     */
    private function _readBinaryString()
    {
        $start = $this->offset;

        $this->offset += strspn($this->data, "\x00\t\n\f\r 0123456789abcdefABCDEF", $this->offset);

        if ($this->offset >= strlen((string) $this->data) - 1) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Unexpected end of file while reading binary string. Offset - 0x%X. \'>\' expected.', $start));
        }

        if ('>' != $this->data[$this->offset++]) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Unexpected character while binary string reading. Offset - 0x%X.', $this->offset));
        }

        return new Zend_Pdf_Element_String_Binary(
            Zend_Pdf_Element_String_Binary::unescape(substr((string) $this->data,
                $start,
                $this->offset - $start - 1)));
    }

    /**
     * Read array PDF object
     * Also reads trailing ']' from a pdf stream.
     *
     * @return Zend_Pdf_Element_Array
     *
     * @throws Zend_Pdf_Exception
     */
    private function _readArray()
    {
        $elements = [];

        while (0 != strlen((string) $nextLexeme = $this->readLexeme())) {
            if (']' != $nextLexeme) {
                $elements[] = $this->readElement($nextLexeme);
            } else {
                return new Zend_Pdf_Element_Array($elements);
            }
        }

        // require_once 'Zend/Pdf/Exception.php';
        throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Unexpected end of file while array reading. Offset - 0x%X. \']\' expected.', $this->offset));
    }

    /**
     * Read dictionary PDF object
     * Also reads trailing '>>' from a pdf stream.
     *
     * @return Zend_Pdf_Element_Dictionary
     *
     * @throws Zend_Pdf_Exception
     */
    private function _readDictionary()
    {
        $dictionary = new Zend_Pdf_Element_Dictionary();

        while (0 != strlen((string) $nextLexeme = $this->readLexeme())) {
            if ('>>' != $nextLexeme) {
                $nameStart = $this->offset - strlen((string) $nextLexeme);

                $name = $this->readElement($nextLexeme);
                $value = $this->readElement();

                if (!$name instanceof Zend_Pdf_Element_Name) {
                    // require_once 'Zend/Pdf/Exception.php';
                    throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Name object expected while dictionary reading. Offset - 0x%X.', $nameStart));
                }

                $dictionary->add($name, $value);
            } else {
                return $dictionary;
            }
        }

        // require_once 'Zend/Pdf/Exception.php';
        throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Unexpected end of file while dictionary reading. Offset - 0x%X. \'>>\' expected.', $this->offset));
    }

    /**
     * Read reference PDF object.
     *
     * @param string $nextLexeme
     *
     * @return Zend_Pdf_Element_Reference
     */
    private function _readReference($nextLexeme = null)
    {
        $start = $this->offset;

        if (null === $nextLexeme) {
            $objNum = $this->readLexeme();
        } else {
            $objNum = $nextLexeme;
        }
        if (!ctype_digit($objNum)) { // it's not a reference
            $this->offset = $start;

            return null;
        }

        $genNum = $this->readLexeme();
        if (!ctype_digit($genNum)) { // it's not a reference
            $this->offset = $start;

            return null;
        }

        $rMark = $this->readLexeme();
        if ('R' != $rMark) { // it's not a reference
            $this->offset = $start;

            return null;
        }

        $ref = new Zend_Pdf_Element_Reference((int) $objNum, (int) $genNum, $this->_context, $this->_objFactory->resolve());

        return $ref;
    }

    /**
     * Read numeric PDF object.
     *
     * @param string $nextLexeme
     *
     * @return Zend_Pdf_Element_Numeric
     */
    private function _readNumeric($nextLexeme = null)
    {
        if (null === $nextLexeme) {
            $nextLexeme = $this->readLexeme();
        }

        return new Zend_Pdf_Element_Numeric($nextLexeme);
    }

    /**
     * Read inderect object from a PDF stream.
     *
     * @param int $offset
     *
     * @return Zend_Pdf_Element_Object
     */
    public function getObject($offset, Zend_Pdf_Element_Reference_Context $context)
    {
        if (null === $offset) {
            return new Zend_Pdf_Element_Null();
        }

        // Save current offset to make getObject() reentrant
        $offsetSave = $this->offset;

        $this->offset = $offset;
        $this->_context = $context;
        $this->_elements = [];

        $objNum = $this->readLexeme();
        if (!ctype_digit($objNum)) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X. Object number expected.', $this->offset - strlen((string) $objNum)));
        }

        $genNum = $this->readLexeme();
        if (!ctype_digit($genNum)) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X. Object generation number expected.', $this->offset - strlen((string) $genNum)));
        }

        $objKeyword = $this->readLexeme();
        if ('obj' != $objKeyword) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X. \'obj\' keyword expected.', $this->offset - strlen((string) $objKeyword)));
        }

        $objValue = $this->readElement();

        $nextLexeme = $this->readLexeme();

        if ('endobj' == $nextLexeme) {
            /**
             * Object is not generated by factory (thus it's not marked as modified object).
             * But factory is assigned to the obect.
             */
            $obj = new Zend_Pdf_Element_Object($objValue, (int) $objNum, (int) $genNum, $this->_objFactory->resolve());

            foreach ($this->_elements as $element) {
                $element->setParentObject($obj);
            }

            // Restore offset value
            $this->offset = $offsetSave;

            return $obj;
        }

        /*
         * It's a stream object
         */
        if ('stream' != $nextLexeme) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X. \'endobj\' or \'stream\' keywords expected.', $this->offset - strlen((string) $nextLexeme)));
        }

        if (!$objValue instanceof Zend_Pdf_Element_Dictionary) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X. Stream extent must be preceded by stream dictionary.', $this->offset - strlen((string) $nextLexeme)));
        }

        /**
         * References are automatically dereferenced at this moment.
         */
        $streamLength = $objValue->Length->value;

        /*
         * 'stream' keyword must be followed by either cr-lf sequence or lf character only.
         * This restriction gives the possibility to recognize all cases exactly
         */
        if ("\r" == $this->data[$this->offset]
            && "\n" == $this->data[$this->offset + 1]) {
            $this->offset += 2;
        } elseif ("\n" == $this->data[$this->offset]) {
            ++$this->offset;
        } else {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X. \'stream\' must be followed by either cr-lf sequence or lf character only.', $this->offset - strlen((string) $nextLexeme)));
        }

        $dataOffset = $this->offset;

        $this->offset += $streamLength;

        $nextLexeme = $this->readLexeme();
        if ('endstream' != $nextLexeme) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X. \'endstream\' keyword expected.', $this->offset - strlen((string) $nextLexeme)));
        }

        $nextLexeme = $this->readLexeme();
        if ('endobj' != $nextLexeme) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception(sprintf('PDF file syntax error. Offset - 0x%X. \'endobj\' keyword expected.', $this->offset - strlen((string) $nextLexeme)));
        }

        $obj = new Zend_Pdf_Element_Object_Stream(substr((string) $this->data,
            $dataOffset,
            $streamLength),
            (int) $objNum,
            (int) $genNum,
            $this->_objFactory->resolve(),
            $objValue);

        foreach ($this->_elements as $element) {
            $element->setParentObject($obj);
        }

        // Restore offset value
        $this->offset = $offsetSave;

        return $obj;
    }

    /**
     * Get length of source string.
     *
     * @return int
     */
    public function getLength()
    {
        return strlen((string) $this->data);
    }

    /**
     * Get source string.
     *
     * @return string
     */
    public function getString()
    {
        return $this->data;
    }

    /**
     * Parse integer value from a binary stream.
     *
     * @param string $stream
     * @param int    $offset
     * @param int    $size
     *
     * @return int
     */
    public static function parseIntFromStream($stream, $offset, $size)
    {
        $value = 0;
        for ($count = 0; $count < $size; ++$count) {
            $value *= 256;
            $value += ord((string) $stream[$offset + $count]);
        }

        return $value;
    }

    /**
     * Set current context.
     */
    public function setContext(Zend_Pdf_Element_Reference_Context $context)
    {
        $this->_context = $context;
    }

    /**
     * Object constructor.
     *
     * Note: PHP duplicates string, which is sent by value, only of it's updated.
     * Thus we don't need to care about overhead
     */
    public function __construct($source, Zend_Pdf_ElementFactory_Interface $factory)
    {
        $this->data = $source;
        $this->_objFactory = $factory;
    }
}
