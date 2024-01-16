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

/** Zend_Pdf_Cmap */
// require_once 'Zend/Pdf/Cmap.php';

/**
 * Implements the "byte encoding" character map (type 0).
 *
 * This is the (legacy) Apple standard encoding mechanism and provides coverage
 * for characters in the Mac Roman character set only. Consequently, this cmap
 * type should be used only as a last resort.
 *
 * The mapping from Mac Roman to Unicode can be found at
 * {@link http://www.unicode.org/Public/MAPPINGS/VENDORS/APPLE/ROMAN.TXT}.
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_Cmap_ByteEncoding extends Zend_Pdf_Cmap
{
    /**** Instance Variables ****/

    /**
     * Glyph index array. Stores the actual glyph numbers. The array keys are
     * the translated Unicode code points.
     *
     * @var array
     */
    protected $_glyphIndexArray = [];

    /**** Public Interface ****/

    /* Concrete Class Implementation */

    /**
     * Returns an array of glyph numbers corresponding to the Unicode characters.
     *
     * If a particular character doesn't exist in this font, the special 'missing
     * character glyph' will be substituted.
     *
     * See also {@link glyphNumberForCharacter()}.
     *
     * @param array $characterCodes array of Unicode character codes (code points)
     *
     * @return array array of glyph numbers
     */
    public function glyphNumbersForCharacters($characterCodes)
    {
        $glyphNumbers = [];
        foreach ($characterCodes as $key => $characterCode) {
            if (!isset($this->_glyphIndexArray[$characterCode])) {
                $glyphNumbers[$key] = Zend_Pdf_Cmap::MISSING_CHARACTER_GLYPH;
                continue;
            }

            $glyphNumbers[$key] = $this->_glyphIndexArray[$characterCode];
        }

        return $glyphNumbers;
    }

    /**
     * Returns the glyph number corresponding to the Unicode character.
     *
     * If a particular character doesn't exist in this font, the special 'missing
     * character glyph' will be substituted.
     *
     * See also {@link glyphNumbersForCharacters()} which is optimized for bulk
     * operations.
     *
     * @param int $characterCode unicode character code (code point)
     *
     * @return int glyph number
     */
    public function glyphNumberForCharacter($characterCode)
    {
        if (!isset($this->_glyphIndexArray[$characterCode])) {
            return Zend_Pdf_Cmap::MISSING_CHARACTER_GLYPH;
        }

        return $this->_glyphIndexArray[$characterCode];
    }

    /**
     * Returns an array containing the Unicode characters that have entries in
     * this character map.
     *
     * @return array unicode character codes
     */
    public function getCoveredCharacters()
    {
        return array_keys($this->_glyphIndexArray);
    }

    /**
     * Returns an array containing the glyphs numbers that have entries in this character map.
     * Keys are Unicode character codes (integers).
     *
     * This functionality is partially covered by glyphNumbersForCharacters(getCoveredCharacters())
     * call, but this method do it in more effective way (prepare complete list instead of searching
     * glyph for each character code).
     *
     * @internal
     *
     * @return array array representing <Unicode character code> => <glyph number> pairs
     */
    public function getCoveredCharactersGlyphs()
    {
        return $this->_glyphIndexArray;
    }

    /* Object Lifecycle */

    /**
     * Object constructor.
     *
     * Parses the raw binary table data. Throws an exception if the table is
     * malformed.
     *
     * @param string $cmapData raw binary cmap table data
     *
     * @throws Zend_Pdf_Exception
     */
    public function __construct($cmapData)
    {
        /* Sanity check: This table must be exactly 262 bytes long.
         */
        $actualLength = strlen((string) $cmapData);
        if (262 != $actualLength) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Insufficient table data', Zend_Pdf_Exception::CMAP_TABLE_DATA_TOO_SMALL);
        }

        /* Sanity check: Make sure this is right data for this table type.
         */
        $type = $this->_extractUInt2($cmapData, 0);
        if (Zend_Pdf_Cmap::TYPE_BYTE_ENCODING != $type) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Wrong cmap table type', Zend_Pdf_Exception::CMAP_WRONG_TABLE_TYPE);
        }

        $length = $this->_extractUInt2($cmapData, 2);
        if ($length != $actualLength) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception("Table length ($length) does not match actual length ($actualLength)", Zend_Pdf_Exception::CMAP_WRONG_TABLE_LENGTH);
        }

        /* Mapping tables should be language-independent. The font may not work
         * as expected if they are not. Unfortunately, many font files in the
         * wild incorrectly record a language ID in this field, so we can't
         * call this a failure.
         */
        $language = $this->_extractUInt2($cmapData, 4);
        if (0 != $language) {
            // Record a warning here somehow?
        }

        /* The mapping between the Mac Roman and Unicode characters is static.
         * For simplicity, just put all 256 glyph indices into one array keyed
         * off the corresponding Unicode character.
         */
        $i = 6;
        $this->_glyphIndexArray[0x00] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x01] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x03] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x04] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x05] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x06] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x07] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x08] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x09] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x10] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x11] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x12] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x13] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x14] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x15] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x16] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x17] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x18] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x19] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x20] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x21] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x22] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x23] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x24] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x25] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x26] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x27] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x28] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x29] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x30] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x31] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x32] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x33] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x34] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x35] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x36] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x37] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x38] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x39] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x40] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x41] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x42] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x43] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x44] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x45] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x46] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x47] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x48] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x49] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x50] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x51] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x52] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x53] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x54] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x55] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x56] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x57] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x58] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x59] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x60] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x61] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x62] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x63] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x64] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x65] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x66] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x67] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x68] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x69] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x70] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x71] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x72] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x73] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x74] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x75] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x76] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x77] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x78] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x79] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC4] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC5] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC7] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xD1] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xD6] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xDC] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE1] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE0] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE2] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE4] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE3] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE5] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE7] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE8] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xEA] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xEB] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xED] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xEC] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xEE] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xEF] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF1] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF3] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF2] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF4] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF6] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF5] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xFA] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xFB] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xFC] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2020] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xB0] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xA2] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xA3] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xA7] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2022] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xB6] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xDF] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xAE] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xA9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2122] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xB4] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xA8] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2260] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC6] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xD8] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x221E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xB1] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2264] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2265] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xA5] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xB5] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2202] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2211] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x220F] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x03C0] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x222B] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xAA] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xBA] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x03A9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xE6] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF8] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xBF] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xA1] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xAC] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x221A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0192] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2248] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2206] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xAB] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xBB] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2026] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xA0] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC0] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC3] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xD5] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0152] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0153] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2013] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2014] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x201C] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x201D] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2018] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2019] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF7] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x25CA] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xFF] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0178] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2044] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x20AC] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2039] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x203A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xFB01] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xFB02] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2021] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xB7] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x201A] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x201E] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2030] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC2] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xCA] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC1] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xCB] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xC8] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xCD] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xCE] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xCF] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xCC] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xD3] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xD4] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xF8FF] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xD2] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xDA] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xDB] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xD9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0131] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02C6] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02DC] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xAF] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02D8] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02D9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02DA] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xB8] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02DD] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02DB] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02C7] = ord((string) $cmapData[$i]);
    }
}
