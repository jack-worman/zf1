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
 */

// Grab SplAutoloader interface
// require_once __DIR__ . '/SplAutoloader.php';

/**
 * Class-map autoloader.
 *
 * Utilizes class-map files to lookup classfile locations.
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    New BSD {@link http://framework.zend.com/license/new-bsd}
 */
class Zend_Loader_ClassMapAutoloader implements Zend_Loader_SplAutoloader
{
    /**
     * Registry of map files that have already been loaded.
     *
     * @var array
     */
    protected $mapsLoaded = [];

    /**
     * Class name/filename map.
     *
     * @var array
     */
    protected $map = [];

    /**
     * Constructor.
     *
     * Create a new instance, and optionally configure the autoloader.
     *
     * @param array|Traversable|null $options
     */
    public function __construct($options = null)
    {
        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    /**
     * Configure the autoloader.
     *
     * Proxies to {@link registerAutoloadMaps()}.
     *
     * @param array|Traversable $options
     *
     * @return Zend_Loader_ClassMapAutoloader
     */
    public function setOptions($options)
    {
        $this->registerAutoloadMaps($options);

        return $this;
    }

    /**
     * Register an autoload map.
     *
     * An autoload map may be either an associative array, or a file returning
     * an associative array.
     *
     * An autoload map should be an associative array containing
     * classname/file pairs.
     *
     * @return Zend_Loader_ClassMapAutoloader
     */
    public function registerAutoloadMap($map)
    {
        if (is_string($map)) {
            $location = $map;
            if ($this === ($map = $this->loadMapFromFile($location))) {
                return $this;
            }
        }

        if (!is_array($map)) {
            // require_once __DIR__ . '/Exception/InvalidArgumentException.php';
            throw new Zend_Loader_Exception_InvalidArgumentException('Map file provided does not return a map');
        }

        $this->map = array_merge($this->map, $map);

        if (isset($location)) {
            $this->mapsLoaded[] = $location;
        }

        return $this;
    }

    /**
     * Register many autoload maps at once.
     *
     * @param array $locations
     *
     * @return Zend_Loader_ClassMapAutoloader
     */
    public function registerAutoloadMaps($locations)
    {
        if (!is_array($locations) && !($locations instanceof Traversable)) {
            // require_once __DIR__ . '/Exception/InvalidArgumentException.php';
            throw new Zend_Loader_Exception_InvalidArgumentException('Map list must be an array or implement Traversable');
        }
        foreach ($locations as $location) {
            $this->registerAutoloadMap($location);
        }

        return $this;
    }

    /**
     * Retrieve current autoload map.
     *
     * @return array
     */
    public function getAutoloadMap()
    {
        return $this->map;
    }

    /**
     * Defined by Autoloadable.
     *
     * @param string $class
     *
     * @return void
     */
    public function autoload($class)
    {
        if (isset($this->map[$class])) {
            require_once $this->map[$class];
        }
    }

    /**
     * Register the autoloader with spl_autoload registry.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register([$this, 'autoload'], true, true);
    }

    /**
     * Load a map from a file.
     *
     * If the map has been previously loaded, returns the current instance;
     * otherwise, returns whatever was returned by calling include() on the
     * location.
     *
     * @param string $location
     *
     * @return Zend_Loader_ClassMapAutoloader|mixed
     *
     * @throws Zend_Loader_Exception_InvalidArgumentException for nonexistent locations
     */
    protected function loadMapFromFile($location)
    {
        if (!file_exists((string) $location)) {
            // require_once __DIR__ . '/Exception/InvalidArgumentException.php';
            throw new Zend_Loader_Exception_InvalidArgumentException('Map file provided does not exist');
        }

        if (!$path = self::realPharPath($location)) {
            $path = realpath($location);
        }

        if (in_array($path, $this->mapsLoaded)) {
            // Already loaded this map
            return $this;
        }

        $map = include $path;

        return $map;
    }

    /**
     * Resolve the real_path() to a file within a phar.
     *
     * @see    https://bugs.php.net/bug.php?id=52769
     *
     * @param string $path
     *
     * @return string
     */
    public static function realPharPath($path)
    {
        if (0 !== strpos((string) $path, 'phar://')) {
            return;
        }

        $parts = explode('/', str_replace(['/', '\\'], '/', substr((string) $path, 7)));
        $prependSlash = $parts && '' === $parts[0] ? '/' : '';
        $parts = array_values(array_filter($parts, [__CLASS__, 'concatPharParts']));

        // resolve parent paths - discard occurrences of '..' and point to parent directory
        $out = [];
        foreach ($parts as $value) {
            if ('..' === $value) {
                array_pop($out);
                continue;
            }
            $out[] = $value;
        }
        $parts = $out;

        if (file_exists((string) $realPath = 'phar://'.$prependSlash.implode('/', $parts))) {
            return $realPath;
        }
    }

    /**
     * Helper callback for filtering phar paths.
     *
     * @param string $part
     *
     * @return bool
     */
    public static function concatPharParts($part)
    {
        return '' !== $part && '.' !== $part;
    }
}
