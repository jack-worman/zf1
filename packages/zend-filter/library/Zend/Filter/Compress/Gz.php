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
 * Compression adapter for Gzip (ZLib).
 */
class Zend_Filter_Compress_Gz extends Zend_Filter_Compress_CompressAbstract
{
    /**
     * Compression Options
     * array(
     *     'level'    => Compression level 0-9
     *     'mode'     => Compression mode, can be 'compress', 'deflate'
     *     'archive'  => Archive to use
     * ).
     *
     * @var array
     */
    protected $_options = [
        'level' => 9,
        'mode' => 'compress',
        'archive' => null,
    ];

    /**
     * Class constructor.
     *
     * @param array|Zend_Config|null $options (Optional) Options to set
     */
    public function __construct($options = null)
    {
        if (!extension_loaded('zlib')) {
            throw new Zend_Filter_Exception('This filter needs the zlib extension');
        }
        parent::__construct($options);
    }

    /**
     * Returns the set compression level.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->_options['level'];
    }

    /**
     * Sets a new compression level.
     *
     * @param int $level
     *
     * @return Zend_Filter_Compress_Gz
     */
    public function setLevel($level)
    {
        if (($level < 0) || ($level > 9)) {
            throw new Zend_Filter_Exception('Level must be between 0 and 9');
        }

        $this->_options['level'] = (int) $level;

        return $this;
    }

    /**
     * Returns the set compression mode.
     *
     * @return string
     */
    public function getMode()
    {
        return $this->_options['mode'];
    }

    /**
     * Sets a new compression mode.
     *
     * @param string $mode Supported are 'compress', 'deflate' and 'file'
     */
    public function setMode($mode)
    {
        if (('compress' != $mode) && ('deflate' != $mode)) {
            throw new Zend_Filter_Exception('Given compression mode not supported');
        }

        $this->_options['mode'] = $mode;

        return $this;
    }

    /**
     * Returns the set archive.
     *
     * @return string
     */
    public function getArchive()
    {
        return $this->_options['archive'];
    }

    /**
     * Sets the archive to use for de-/compression.
     *
     * @param string $archive Archive to use
     *
     * @return Zend_Filter_Compress_Gz
     */
    public function setArchive($archive)
    {
        $this->_options['archive'] = (string) $archive;

        return $this;
    }

    /**
     * Compresses the given content.
     *
     * @param string $content
     *
     * @return string
     */
    public function compress($content)
    {
        $archive = $this->getArchive();
        if (!empty($archive)) {
            $file = gzopen($archive, 'w'.$this->getLevel());
            if (!$file) {
                throw new Zend_Filter_Exception("Error opening the archive '".$this->_options['archive']."'");
            }

            gzwrite($file, $content);
            gzclose($file);
            $compressed = true;
        } elseif ('deflate' == $this->_options['mode']) {
            $compressed = gzdeflate($content, $this->getLevel());
        } else {
            $compressed = gzcompress($content, $this->getLevel());
        }

        if (!$compressed) {
            throw new Zend_Filter_Exception('Error during compression');
        }

        return $compressed;
    }

    /**
     * Decompresses the given content.
     *
     * @param string $content
     *
     * @return string
     */
    public function decompress($content)
    {
        $archive = $this->getArchive();
        $mode = $this->getMode();
        // check $content for NULL bytes or else file_exists will error out
        if ((0 === preg_match('/\0/', (string) $content)) && @file_exists((string) $content)) {
            $archive = $content;
        }

        if (@file_exists((string) $archive)) {
            $handler = fopen($archive, 'rb');
            if (!$handler) {
                throw new Zend_Filter_Exception("Error opening the archive '".$archive."'");
            }

            fseek($handler, -4, SEEK_END);
            $packet = fread($handler, 4);
            $bytes = unpack('V', $packet);
            $size = end($bytes);
            fclose($handler);

            $file = gzopen($archive, 'r');
            $compressed = gzread($file, $size);
            gzclose($file);
        } elseif ('deflate' == $mode) {
            $compressed = gzinflate($content);
        } else {
            $compressed = gzuncompress($content);
        }

        if (!$compressed) {
            throw new Zend_Filter_Exception('Error during compression');
        }

        return $compressed;
    }

    /**
     * Returns the adapter name.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gz';
    }
}
