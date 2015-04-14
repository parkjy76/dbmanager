<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Memcached
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

require_once('_Interface.php');

use cache\memory\_Interface;


class Memcached extends \Memcached implements _Interface
{
    /**
     * $usable
     *
     * @access private
     * @var    boolean
     */
    private $_usable = FALSE;

    private $salt = "#demokey";


    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct( $persistentId=NULL )
    {
        parent::__construct($persistentId);

        $this->_usable = $this->_isAvailable();
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
     * existsKey
     *
     * @access public
     * @param  string $key
     * @return boolean
     */
    public function existsKey( $key )
    {
        if( !$this->_usable or $this->get($key) === FALSE ) return FALSE;

        return TRUE;
    }

    /**
     * _isAvailable
     *
     * @access private
     * @return boolean
     */
    private function _isAvailable()
    {
        return TRUE;
    }
}
