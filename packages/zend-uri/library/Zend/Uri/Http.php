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
 * @see Zend_Uri
 */
// require_once 'Zend/Uri.php';

/**
 * @see Zend_Validate_Hostname
 */
// require_once 'Zend/Validate/Hostname.php';

/**
 * HTTP(S) URI handler.
 *
 * @category  Zend
 *
 * @uses      Zend_Uri
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Uri_Http extends Zend_Uri
{
    /**
     * Character classes for validation regular expressions.
     */
    public const CHAR_ALNUM = 'A-Za-z0-9';
    public const CHAR_MARK = '-_.!~*\'()\[\]';
    public const CHAR_RESERVED = ';\/?:@&=+$,';
    public const CHAR_SEGMENT = ':@&=+$,;';
    public const CHAR_UNWISE = '{}|\\\\^`';

    /**
     * HTTP username.
     *
     * @var string
     */
    protected $_username = '';

    /**
     * HTTP password.
     *
     * @var string
     */
    protected $_password = '';

    /**
     * HTTP host.
     *
     * @var string
     */
    protected $_host = '';

    /**
     * HTTP post.
     *
     * @var string
     */
    protected $_port = '';

    /**
     * HTTP part.
     *
     * @var string
     */
    protected $_path = '';

    /**
     * HTTP query.
     *
     * @var string
     */
    protected $_query = '';

    /**
     * HTTP fragment.
     *
     * @var string
     */
    protected $_fragment = '';

    /**
     * Regular expression grammar rules for validation; values added by constructor.
     *
     * @var array
     */
    protected $_regex = [];

    /**
     * Constructor accepts a string $scheme (e.g., http, https) and a scheme-specific part of the URI
     * (e.g., example.com/path/to/resource?query=param#fragment).
     *
     * @param string $scheme         The scheme of the URI
     * @param string $schemeSpecific The scheme-specific part of the URI
     *
     * @throws Zend_Uri_Exception When the URI is not valid
     */
    protected function __construct($scheme, $schemeSpecific = '')
    {
        // Set the scheme
        $this->_scheme = $scheme;

        // Set up grammar rules for validation via regular expressions. These
        // are to be used with slash-delimited regular expression strings.

        // Escaped special characters (eg. '%25' for '%')
        $this->_regex['escaped'] = '%[[:xdigit:]]{2}';

        // Unreserved characters
        $this->_regex['unreserved'] = '['.self::CHAR_ALNUM.self::CHAR_MARK.']';

        // Segment can use escaped, unreserved or a set of additional chars
        $this->_regex['segment'] = '(?:'.$this->_regex['escaped'].'|['.
            self::CHAR_ALNUM.self::CHAR_MARK.self::CHAR_SEGMENT.'])*';

        // Path can be a series of segmets char strings seperated by '/'
        $this->_regex['path'] = '(?:\/(?:'.$this->_regex['segment'].')?)+';

        // URI characters can be escaped, alphanumeric, mark or reserved chars
        $this->_regex['uric'] = '(?:'.$this->_regex['escaped'].'|['.
            self::CHAR_ALNUM.self::CHAR_MARK.self::CHAR_RESERVED.

        // If unwise chars are allowed, add them to the URI chars class
            (self::$_config['allow_unwise'] ? self::CHAR_UNWISE : '').'])';

        // If no scheme-specific part was supplied, the user intends to create
        // a new URI with this object.  No further parsing is required.
        if (0 === strlen((string) $schemeSpecific)) {
            return;
        }

        // Parse the scheme-specific URI parts into the instance variables.
        $this->_parseUri($schemeSpecific);

        // Validate the URI
        if (false === $this->valid()) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('Invalid URI supplied');
        }
    }

    /**
     * Creates a Zend_Uri_Http from the given string.
     *
     * @param string $uri String to create URI from, must start with
     *                    'http://' or 'https://'
     *
     * @return Zend_Uri_Http
     *
     * @throws InvalidArgumentException When the given $uri is not a string or
     *                                  does not start with http:// or https://
     * @throws Zend_Uri_Exception       When the given $uri is invalid
     */
    public static function fromString($uri)
    {
        if (false === is_string($uri)) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('$uri is not a string');
        }

        $uri = explode(':', $uri, 2);
        $scheme = strtolower((string) $uri[0]);
        $schemeSpecific = true === isset($uri[1]) ? $uri[1] : '';

        if (false === in_array($scheme, ['http', 'https'])) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("Invalid scheme: '$scheme'");
        }

        $schemeHandler = new Zend_Uri_Http($scheme, $schemeSpecific);

        return $schemeHandler;
    }

    /**
     * Parse the scheme-specific portion of the URI and place its parts into instance variables.
     *
     * @param string $schemeSpecific The scheme-specific portion to parse
     *
     * @return void
     *
     * @throws Zend_Uri_Exception When scheme-specific decoposition fails
     * @throws Zend_Uri_Exception When authority decomposition fails
     */
    protected function _parseUri($schemeSpecific)
    {
        // High-level decomposition parser
        $pattern = '~^((//)([^/?#]*))([^?#]*)(\?([^#]*))?(#(.*))?$~';
        $status = @preg_match($pattern, $schemeSpecific, $matches);
        if (false === $status) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('Internal error: scheme-specific decomposition failed');
        }

        // Failed decomposition; no further processing needed
        if (false === $status) {
            return;
        }

        // Save URI components that need no further decomposition
        $this->_path = true === isset($matches[4]) ? $matches[4] : '';
        $this->_query = true === isset($matches[6]) ? $matches[6] : '';
        $this->_fragment = true === isset($matches[8]) ? $matches[8] : '';

        // Additional decomposition to get username, password, host, and port
        $combo = true === isset($matches[3]) ? $matches[3] : '';
        $pattern = '~^(([^:@]*)(:([^@]*))?@)?((?(?=[[])[[][^]]+[]]|[^:]+))(:(.*))?$~';
        $status = @preg_match($pattern, $combo, $matches);
        if (false === $status) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('Internal error: authority decomposition failed');
        }

        // Save remaining URI components
        $this->_username = true === isset($matches[2]) ? $matches[2] : '';
        $this->_password = true === isset($matches[4]) ? $matches[4] : '';
        $this->_host = true === isset($matches[5])
                         ? preg_replace('~^\[([^]]+)\]$~', '\1', $matches[5])  // Strip wrapper [] from IPv6 literal
                         : '';
        $this->_port = true === isset($matches[7]) ? $matches[7] : '';
    }

    /**
     * Returns a URI based on current values of the instance variables. If any
     * part of the URI does not pass validation, then an exception is thrown.
     *
     * @return string
     *
     * @throws Zend_Uri_Exception When one or more parts of the URI are invalid
     */
    public function getUri()
    {
        if (false === $this->valid()) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('One or more parts of the URI are invalid');
        }

        $password = strlen((string) $this->_password) > 0 ? ":$this->_password" : '';
        $auth = strlen((string) $this->_username) > 0 ? "$this->_username$password@" : '';
        $port = strlen((string) $this->_port) > 0 ? ":$this->_port" : '';
        $query = strlen((string) $this->_query) > 0 ? "?$this->_query" : '';
        $fragment = strlen((string) $this->_fragment) > 0 ? "#$this->_fragment" : '';

        return $this->_scheme
             .'://'
             .$auth
             .$this->_host
             .$port
             .$this->_path
             .$query
             .$fragment;
    }

    /**
     * Validate the current URI from the instance variables. Returns true if and only if all
     * parts pass validation.
     *
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function valid()
    {
        // Return true if and only if all parts of the URI have passed validation
        return $this->validateUsername()
           and $this->validatePassword()
           and $this->validateHost()
           and $this->validatePort()
           and $this->validatePath()
           and $this->validateQuery()
           and $this->validateFragment();
    }

    /**
     * Returns the username portion of the URL, or FALSE if none.
     *
     * @return string
     */
    public function getUsername()
    {
        return strlen((string) $this->_username) > 0 ? $this->_username : false;
    }

    /**
     * Returns true if and only if the username passes validation. If no username is passed,
     * then the username contained in the instance variable is used.
     *
     * @param string $username The HTTP username
     *
     * @return bool
     *
     * @throws Zend_Uri_Exception When username validation fails
     *
     * @see   http://www.faqs.org/rfcs/rfc2396.html
     */
    public function validateUsername($username = null)
    {
        if (null === $username) {
            $username = $this->_username;
        }

        // If the username is empty, then it is considered valid
        if (0 === strlen((string) $username)) {
            return true;
        }

        // Check the username against the allowed values
        $status = @preg_match('/^(?:'.$this->_regex['escaped'].'|['.
            self::CHAR_ALNUM.self::CHAR_MARK.';:&=+$,])+$/', $username);

        if (false === $status) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('Internal error: username validation failed');
        }

        return 1 === $status;
    }

    /**
     * Sets the username for the current URI, and returns the old username.
     *
     * @param string $username The HTTP username
     *
     * @return string
     *
     * @throws Zend_Uri_Exception When $username is not a valid HTTP username
     */
    public function setUsername($username)
    {
        if (false === $this->validateUsername($username)) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("Username \"$username\" is not a valid HTTP username");
        }

        $oldUsername = $this->_username;
        $this->_username = $username;

        return $oldUsername;
    }

    /**
     * Returns the password portion of the URL, or FALSE if none.
     *
     * @return string
     */
    public function getPassword()
    {
        return strlen((string) $this->_password) > 0 ? $this->_password : false;
    }

    /**
     * Returns true if and only if the password passes validation. If no password is passed,
     * then the password contained in the instance variable is used.
     *
     * @param string $password The HTTP password
     *
     * @return bool
     *
     * @throws Zend_Uri_Exception When password validation fails
     *
     * @see   http://www.faqs.org/rfcs/rfc2396.html
     */
    public function validatePassword($password = null)
    {
        if (null === $password) {
            $password = $this->_password;
        }

        // If the password is empty, then it is considered valid
        if (0 === strlen((string) $password)) {
            return true;
        }

        // If the password is nonempty, but there is no username, then it is considered invalid
        if (strlen((string) $password) > 0 and 0 === strlen((string) $this->_username)) {
            return false;
        }

        // Check the password against the allowed values
        $status = @preg_match('/^(?:'.$this->_regex['escaped'].'|['.
            self::CHAR_ALNUM.self::CHAR_MARK.';:&=+$,])+$/', $password);

        if (false === $status) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('Internal error: password validation failed.');
        }

        return 1 == $status;
    }

    /**
     * Sets the password for the current URI, and returns the old password.
     *
     * @param string $password The HTTP password
     *
     * @return string
     *
     * @throws Zend_Uri_Exception When $password is not a valid HTTP password
     */
    public function setPassword($password)
    {
        if (false === $this->validatePassword($password)) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("Password \"$password\" is not a valid HTTP password.");
        }

        $oldPassword = $this->_password;
        $this->_password = $password;

        return $oldPassword;
    }

    /**
     * Returns the domain or host IP portion of the URL, or FALSE if none.
     *
     * @return string
     */
    public function getHost()
    {
        return strlen((string) $this->_host) > 0 ? $this->_host : false;
    }

    /**
     * Returns true if and only if the host string passes validation. If no host is passed,
     * then the host contained in the instance variable is used.
     *
     * @param string $host The HTTP host
     *
     * @return bool
     *
     * @uses   Zend_Filter
     */
    public function validateHost($host = null)
    {
        if (null === $host) {
            $host = $this->_host;
        }

        // If the host is empty, then it is considered invalid
        if (0 === strlen((string) $host)) {
            return false;
        }

        // Check the host against the allowed values; delegated to Zend_Filter.
        $validate = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_ALL);

        return $validate->isValid($host);
    }

    /**
     * Sets the host for the current URI, and returns the old host.
     *
     * @param string $host The HTTP host
     *
     * @return string
     *
     * @throws Zend_Uri_Exception When $host is nota valid HTTP host
     */
    public function setHost($host)
    {
        if (false === $this->validateHost($host)) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("Host \"$host\" is not a valid HTTP host");
        }

        $oldHost = $this->_host;
        $this->_host = $host;

        return $oldHost;
    }

    /**
     * Returns the TCP port, or FALSE if none.
     *
     * @return string
     */
    public function getPort()
    {
        return strlen((string) $this->_port) > 0 ? $this->_port : false;
    }

    /**
     * Returns true if and only if the TCP port string passes validation. If no port is passed,
     * then the port contained in the instance variable is used.
     *
     * @param string $port The HTTP port
     *
     * @return bool
     */
    public function validatePort($port = null)
    {
        if (null === $port) {
            $port = $this->_port;
        }

        // If the port is empty, then it is considered valid
        if (0 === strlen((string) $port)) {
            return true;
        }

        // Check the port against the allowed values
        return ctype_digit((string) $port) and 1 <= $port and $port <= 65535;
    }

    /**
     * Sets the port for the current URI, and returns the old port.
     *
     * @param string $port The HTTP port
     *
     * @return string
     *
     * @throws Zend_Uri_Exception When $port is not a valid HTTP port
     */
    public function setPort($port)
    {
        if (false === $this->validatePort($port)) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("Port \"$port\" is not a valid HTTP port.");
        }

        $oldPort = $this->_port;
        $this->_port = $port;

        return $oldPort;
    }

    /**
     * Returns the path and filename portion of the URL.
     *
     * @return string
     */
    public function getPath()
    {
        return strlen((string) $this->_path) > 0 ? $this->_path : '/';
    }

    /**
     * Returns true if and only if the path string passes validation. If no path is passed,
     * then the path contained in the instance variable is used.
     *
     * @param string $path The HTTP path
     *
     * @return bool
     *
     * @throws Zend_Uri_Exception When path validation fails
     */
    public function validatePath($path = null)
    {
        if (null === $path) {
            $path = $this->_path;
        }

        // If the path is empty, then it is considered valid
        if (0 === strlen((string) $path)) {
            return true;
        }

        // Determine whether the path is well-formed
        $pattern = '/^'.$this->_regex['path'].'$/';
        $status = @preg_match($pattern, $path);
        if (false === $status) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('Internal error: path validation failed');
        }

        return (bool) $status;
    }

    /**
     * Sets the path for the current URI, and returns the old path.
     *
     * @param string $path The HTTP path
     *
     * @return string
     *
     * @throws Zend_Uri_Exception When $path is not a valid HTTP path
     */
    public function setPath($path)
    {
        if (false === $this->validatePath($path)) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("Path \"$path\" is not a valid HTTP path");
        }

        $oldPath = $this->_path;
        $this->_path = $path;

        return $oldPath;
    }

    /**
     * Returns the query portion of the URL (after ?), or FALSE if none.
     *
     * @return string
     */
    public function getQuery()
    {
        return strlen((string) $this->_query) > 0 ? $this->_query : false;
    }

    /**
     * Returns the query portion of the URL (after ?) as a
     * key-value-array. If the query is empty an empty array
     * is returned.
     *
     * @return array
     */
    public function getQueryAsArray()
    {
        $query = $this->getQuery();
        $querryArray = [];
        if (false !== $query) {
            parse_str($query, $querryArray);
        }

        return $querryArray;
    }

    /**
     * Returns true if and only if the query string passes validation. If no query is passed,
     * then the query string contained in the instance variable is used.
     *
     * @param string $query The query to validate
     *
     * @return bool
     *
     * @throws Zend_Uri_Exception When query validation fails
     *
     * @see   http://www.faqs.org/rfcs/rfc2396.html
     */
    public function validateQuery($query = null)
    {
        if (null === $query) {
            $query = $this->_query;
        }

        // If query is empty, it is considered to be valid
        if (0 === strlen((string) $query)) {
            return true;
        }

        // Determine whether the query is well-formed
        $pattern = '/^'.$this->_regex['uric'].'*$/';
        $status = @preg_match($pattern, $query);
        if (false === $status) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('Internal error: query validation failed');
        }

        return 1 == $status;
    }

    /**
     * Add or replace params in the query string for the current URI, and
     * return the old query.
     *
     * @return string Old query string
     */
    public function addReplaceQueryParameters(array $queryParams)
    {
        $queryParams = array_merge($this->getQueryAsArray(), $queryParams);

        return $this->setQuery($queryParams);
    }

    /**
     * Remove params in the query string for the current URI, and
     * return the old query.
     *
     * @return string Old query string
     */
    public function removeQueryParameters(array $queryParamKeys)
    {
        $queryParams = array_diff_key($this->getQueryAsArray(), array_fill_keys($queryParamKeys, 0));

        return $this->setQuery($queryParams);
    }

    /**
     * Set the query string for the current URI, and return the old query
     * string This method accepts both strings and arrays.
     *
     * @param string|array $query The query string or array
     *
     * @return string Old query string
     *
     * @throws Zend_Uri_Exception When $query is not a valid query string
     */
    public function setQuery($query)
    {
        $oldQuery = $this->_query;

        // If query is empty, set an empty string
        if (true === empty($query)) {
            $this->_query = '';

            return $oldQuery;
        }

        // If query is an array, make a string out of it
        if (true === is_array($query)) {
            $query = http_build_query($query, '', '&');
        } else {
            // If it is a string, make sure it is valid. If not parse and encode it
            $query = (string) $query;
            if (false === $this->validateQuery($query)) {
                parse_str($query, $queryArray);
                $query = http_build_query($queryArray, '', '&');
            }
        }

        // Make sure the query is valid, and set it
        if (false === $this->validateQuery($query)) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("'$query' is not a valid query string");
        }

        $this->_query = $query;

        return $oldQuery;
    }

    /**
     * Returns the fragment portion of the URL (after #), or FALSE if none.
     *
     * @return string|false
     */
    public function getFragment()
    {
        return strlen((string) $this->_fragment) > 0 ? $this->_fragment : false;
    }

    /**
     * Returns true if and only if the fragment passes validation. If no fragment is passed,
     * then the fragment contained in the instance variable is used.
     *
     * @param string $fragment Fragment of an URI
     *
     * @return bool
     *
     * @throws Zend_Uri_Exception When fragment validation fails
     *
     * @see   http://www.faqs.org/rfcs/rfc2396.html
     */
    public function validateFragment($fragment = null)
    {
        if (null === $fragment) {
            $fragment = $this->_fragment;
        }

        // If fragment is empty, it is considered to be valid
        if (0 === strlen((string) $fragment)) {
            return true;
        }

        // Determine whether the fragment is well-formed
        $pattern = '/^'.$this->_regex['uric'].'*$/';
        $status = @preg_match($pattern, $fragment);
        if (false === $status) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception('Internal error: fragment validation failed');
        }

        return (bool) $status;
    }

    /**
     * Sets the fragment for the current URI, and returns the old fragment.
     *
     * @param string $fragment Fragment of the current URI
     *
     * @return string
     *
     * @throws Zend_Uri_Exception When $fragment is not a valid HTTP fragment
     */
    public function setFragment($fragment)
    {
        if (false === $this->validateFragment($fragment)) {
            // require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("Fragment \"$fragment\" is not a valid HTTP fragment");
        }

        $oldFragment = $this->_fragment;
        $this->_fragment = $fragment;

        return $oldFragment;
    }
}
