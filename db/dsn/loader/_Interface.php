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
 * PHP 5.3 Later
 * 
 * @category   common
 * @package    db.dsn.loader
 * @author     Junyong Park
 * @copyright  2012
 */

namespace db\dsn\loader;


interface _Interface
{
    /**
     * getDsn
     * 
     * @static
     * @access public
     * @param  string
     * @param  string
     * @return string
     */
    public static function getDsn( $type, $key );

    /**
     * setDsnFetchDir
     *
     * @static
     * @access private
     * @param  string
     * @return boolean
     */
    public static function setDsnFetchDir( $dir );
}
