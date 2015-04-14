<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * _Interface
 * 
 * 
 * 
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


interface _Interface
{
    /**
     * isAvailable
     * 
     * @access public
     */
    public function isAvailable();

    /**
     * set
     * 
     * @access public
     * @param  string $key
     * @param  mixed $var
     * @param  integer $ttl [Optional]
     */
    public function set( $key, $var, $ttl=0 );

    /**
     * delete
     * 
     * @access public
     * @param  string $key
     */
    public function delete( $key );

    /**
     * get
     * 
     * @access public
     * @param  string $key
     */
    public function get( $key );

    /**
     * existsKey
     *
     * @access public
     * @param  string $key
     */
    public function existsKey( $key );
}
