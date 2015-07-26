<?php
/*	SVN FILE: $Id: raycache.php 85 2008-05-13 07:36:10Z rayhan $	 */
/**	Cache Object Class.
*
*	Caching classes for easy and plug and play installation.
*
*	PHP version 5+
*
*
*	@copyright		Copyright 2006-2010, Md. Rayhan Chowdhury
*	@package		raynux
*	@subpackage		raynux.labs.cache
*	@version		$Revision: 85 $
* 	@modifiedby		$LastChangedBy: rayhan $
*	@lastModified           $Date: 2008-05-13 13:36:10 +0600 (Tue, 13 May 2008) $
*	@author			$Author: rayhan $
*	@website		www.raynux.com
*       @license                MIT License http://www.opensource.org/licenses/mit-license.php
*/

/**
 * Cache Engine Interface
 *
 */
Interface RayCacheEngineInterface{
    function write($key, $data = '', $options = array());
    function read($key, $options = array());
    function delete($key, $options = array());
    function clear($expired = true);
    function gc();
    public static function &getInstance($configs = array());
}
/**
 * Cache Class
 *
 * Provide cache functionality for multiple configuration and engine.
 * Static read-write method are helpful to call from anywhere of the script.
 *
 * @package raynux
 * @subpackage raynux.labs.cache
 */
class RayCache{
    /**
     * Class Instances
     *
     * @var array
     */
    private static $__instances = array();

    /**
     * Get Instance of a cache engine
     *
     * Factory Interface for Cache Engine.
     *
     * @param config $configName
     * @param string $engine default file
     * @param array $configs
     * @return object
     */
    public static function &getInstance($configName = null, $engine = null, $configs = array()){
        if (empty($configName)) {
            $configName = 'default';
        }

        if (empty($engine)) {
            $engine = 'file';
        }

        if (isset(self::$__instances[$configName])) {
            return self::$__instances[$configName];
        }

        if (empty(self::$__instances)) {
            $default = true;
        }

        $engine = strtolower($engine);

        switch ($engine){
            case 'file':
            default:
                self::$__instances[$configName] = new RayFileCache($configs);
                break;
        }

        return self::$__instances[$configName];
    }
    /**
     * Static wrapper to cache write method
     *
     * @param string $key
     * @param mixed $data
     * @param array $options, array('expire' => 10), expire in seconds
     * @param string $configName
     * @return boolean
     */
    public static function write($key, $data, $options = array(), $configName = 'default') {
        $_this = self::getInstance($configName);
        return $_this->write($key, $data, $options);
    }
    /**
     * Static Wrapper to cache read method
     *
     * @param string $key
     * @param array $options
     * @param string $configName
     * @return mixed
     */
    public static function read($key, $options = array(), $configName = 'default') {
        $_this = self::getInstance($configName);
        return $_this->read($key, $options);
    }
    /**
     * Static wrapper to cache delete mathod
     *
     * @param string $key
     * @param array $options
     * @param string $configName
     * @return boolean
     */
    public static function delete($key, $options = array(), $configName = 'default') {
        $_this = self::getInstance($configName);
        return $_this->delete($key, $options);
    }
}
/**
 * File Cache Engine
 *
 * @package raynux
 * @subpackage raynux.labs.cache
 */
class RayFileCache implements RayCacheEngineInterface{
    /**
     * Class Instances
     *
     * @var array
     */
    private static $__instance;
    /**
     * Runtime Configuration Data
     *
     * @var array
     */
    protected $_configs = array();
    /**
     * Class Constructor
     *
     * @param array $configs
     */
    private $enable_cache = true; // edit to false if you want caching off
	function  __construct($configs = array()) {
        $this->config($configs);

        // run garbage collection
        if (rand(1, $this->_configs['gc']) === 1) {
            $this->gc();
        }
    }
    /**
     * Get Instance of Class
     *
     * @param string $name
     * @param array $configs
     * @return object
     * @static
     */
    public static function &getInstance($configs = array()){
        if (is_null(self::$__instance)) {
            self::$__instance = new self($configs);
        }
        return self::$__instance;
    }
    /**
     * Set Configuration
     *
     * default: array('path' => './cache/', 'prefix' => 'raycache_', 'expire' => 10, 'gc' => 100)
     *
     * @param array $configs
     * @return object self instance
     */
    function &config($configs = array()) {
    	// default path modified to work with ci cache
        $default = array('path' => './cache/', 'prefix' => 'yas_', 'expire' => 10, 'gc' => 100);
        $this->_configs = array_merge($default, $configs);
        return $this;
    }
    /**
     * Write data to cache
     *
     * @param string $key
     * @param mixed $data
     * @param array $options
     * @return boolean
     */
    public function start_caching() {
		if ($this ->enable_cache) {
			ob_start();
			return true;
		}
		else return false;
	}
	public function stop_caching() {
		ob_end_clean();
	}
	public function write($key, $data = '', $options = array()){
        // check is writable
        if (is_null($data) or $data == '') $data = ob_get_contents();
		if (!is_writable($this->_configs['path'])) {
            echo $this->_configs['path'];
            return false;
        }

        // Prepare data for writing
        if (!empty($options['expire'])) {
            $expire = $options['expire'];
        } else {
            $expire = $this->_configs['expire'];
        }

        if (is_string($expire)) {
            $expire = strtotime($expire);
        } else {
            $expire = time() + $expire;
        }

        $data = serialize(array('expire' => $expire, 'data' => $data));

        $fileName = $this->_configs['path'] . $this->_configs['prefix'] . $key;

        // Write data to files
        if (file_put_contents($fileName, $data, LOCK_EX)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Read Data from cache
     *
     * @param string $key
     * @param array $options
     * @return mixed
     */
    public function read($key, $options = array()) {
        $fileName = $this->_configs['path'] . $this->_configs['prefix'] . $key;

        if (!file_exists($fileName)) {
            return false;
        }

        if (!is_readable($fileName)) {
            return false;
        }

        $data = file_get_contents($fileName);
        if ($data === false) {
            return false;
        }

        $data = unserialize($data);

        if ($data['expire'] < time()) {
            $this->delete($key);
            return false;
        }

        return $data['data'];
    }
    /**
     * Delete a cache data
     *
     * @param string $key
     * @param arrayt $options
     * @return boolean
     */
    function delete($key, $options = array()) {
        $fileName = $this->_configs['path'] . $this->_configs['prefix'] . $key;
        if (!file_exists($fileName) || !is_writable($fileName)) {
            return false;
        }
        return unlink($fileName);
    }
    /**
     * Clear cache data
     *
     * @param boolean $expired if true then only delete expired cache
     * @return booelan
     */
    public function clear($expired = true) {
        $entries = glob($this->_configs['path'] . $this->_configs['prefix'] . "*");

        if (!is_array($entries)) {
            return false;
        }

        foreach ($entries as $item) {
            if (!is_file($item) || !is_writable($item)) {
                continue;
            }

            if ($expired) {
                $expire = file_get_contents($item, null, null, 20, 11);

                $strpos = strpos($expire, ';');
                if ($strpos !== false) {
                    $expire = substr($expire, 0, $strpos);
                }

                if ($expire > time()) {
                    continue;
                }
            }

            if (!unlink($item)) {
                return false;
            }
        }

        return true;
    }
    /**
     * Garbage collection
     *
     * @return boolean
     */
    public function gc() {
        return $this->clear(true);
    }
}
?>