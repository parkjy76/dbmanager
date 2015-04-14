<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * APC
 *
 *  
 * APC(Alternative PHP Cache) Class
 * 
 * 
 * 
 * 
 *  
 * PHP 5.3 Later
 * 
 * @package    cache.memory
 * @author     Junyong Park
 * @copyright  2011
 */

namespace cache\memory;

require_once('_Interface.php');

use cache\memory\_Interface;


class APC implements _Interface 
{
    /**
     * $_usable
     * 
     * @access private
     * @var    boolean
     */
    private $_usable = FALSE;

    /**
     * $_serializer
     * 
     * @access private
     * @var    boolean
     */
    private $_serializer = FALSE;


    /**
     * Constructor
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_usable = $this->_isAvailable();
        $this->_serializer = TRUE;
//        $this->_serializer = $this->_isEnabledSerializer();
    }

    /**
     * Clone
     * 
     * @final
     * @access public
     */
//    public final function __clone()
//    {
//        throw new \BadMethodCallException("Clone is not allowed");
//    }

    /**
     * add
     * 
     * @access public
     * @param  string $key
     * @param  mixed $var
     * @param  integer $ttl [Optional]
     * @return booelan
     */
    public function add( $key, $var, $ttl='' )
    {
        if( !$this->_usable ) return FALSE;

        if( !$this->_serializer && !is_resource($var) ) $var = serialize($var);

        if( !strlen($ttl) ) return apc_add($key, $var);
        else return apc_add($key, $var, $ttl);
    }

    /**
     * isAvailable
     * 
     * @access public
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->_usable;
    }

    /**
     * set
     * 
     * @access public
     * @param  string $key
     * @param  mixed $var
     * @param  integer $ttl [Optional]
     * @return booelan
     */
    public function set( $key, $var, $ttl='' )
    {
        if( !$this->_usable ) return FALSE;

        if( !$this->_serializer && !is_resource($var) ) $var = serialize($var);

        if( !strlen($ttl) ) return apc_store($key, $var);
        else return apc_store($key, $var, $ttl);
    }

    /**
     * delete
     * 
     * @access public
     * @param  string $key
     * @return booelan
     */
    public function delete( $key )
    {
        if( !$this->_usable ) return FALSE;

        return apc_delete($key);
    }

    /**
     * get
     * 
     * @access public
     * @param  string $key
     * @return mixed
     */
    public function get( $key )
    {
        if( !$this->_usable ) return FALSE;

        $var = apc_fetch($key);

        if( !$this->_serializer ) return unserialize($var);
        else return $var;
    }

    /**
     * existsKey
     * 
     * @access public
     * @param  string $key
     * @return boolean
     */
    public function existsKey( $key )
    {
        if( !$this->_usable ) return FALSE;

        return apc_exists($key);
    }

    /**
     * _isEnabledSerializer
     * 
     * @access private
     * @return boolean
     */
    private function _isEnabledSerializer()
    {
        if( strcmp(phpversion('apc'), '3.1.6') > 0 ) return TRUE;
        else return FALSE;
    }

    /**
     * _isAvailable
     * 
     * @access private
     * @return boolean
     */
    private function _isAvailable()
    {
        if( extension_loaded('apc') && ini_get('apc.enabled') ) return TRUE;
        else return FALSE;
    }    
}
