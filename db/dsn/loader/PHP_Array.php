<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * PHP_Array
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
 * @package    db.dsn.loader
 * @author     Junyong Park
 * @copyright  2012
 */

namespace db\dsn\loader;

require_once('_Interface.php');

use db\dsn\loader\_Interface;


class PHP_Array implements _Interface
{
    /**
     * DSN_FETCH_FILENAME_DEFAULT
     * 
     * @access const 
     * @var    string
     */
    const DSN_FETCH_FILENAME_DEFAULT = 'db.php';

    /**
     * _dsnFetchDir
     * 
     * @static
     * @access private
     * @var    string
     */
//     private static $_dsnFetchDir = '/home/service/etc/';
    private static $_dsnFetchDir = './';


    /**
     * getDsn
     * 
     * @static
     * @access public
     * @param  string
     * @param  string 
     * @return string
     */
    public static function getDsn( $type, $key )
    {
        $filename = self::DSN_FETCH_FILENAME_DEFAULT . '.' . $type;

        // 接続情報取得
        return self::_fetch($filename, $key);
    }

    /**
     * setDsnFetchDir
     * 
     * @static
     * @access private
     * @param  string
     * @return boolean
     */
    public static function setDsnFetchDir( $dir )
    {
        self::$_dsnFetchDir = $dir;
        
        return TRUE;
    }

    /**
     * _fetch
     * 
     * @static
     * @access private
     * @param  string
     * @param  string
     * @return string
     */
    private static function _fetch( $filename, $key )
    {
        $path = self::$_dsnFetchDir . $filename;

        if( !file_exists($path) ) throw new \Exception('can not open array['. $path .']');

        include($path);

        if( !isset($dsn[$key]) ) throw new \Exception( $key . '(' . $filename . ') didn\'t exist');
        else return $dsn[$key];
    }
}
