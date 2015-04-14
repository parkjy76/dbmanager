<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Loader
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
 * @package    db.dsn
 * @author     Junyong Park
 * @copyright  2012
 */

namespace db\dsn;

require_once('loader/CDB.php');
require_once('loader/PHP_Array.php');


class Loader
{
    /**
     * TYPE_ARRAY
     * 
     * @access const
     * @var    string
     */
    const TYPE_ARRAY = 'PHP_Array';

    /**
     * TYPE_CDB
     * 
     * @access const
     * @var    string
     */
    const TYPE_CDB = 'CDB';


    /**
     * __callStatic
     *
     * @static
     * @param  string $method
     * @param  array $params
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public static function __callStatic( $method, $params )
    {
        list($loadType, $type, $key) = $params;

        $dsnLoaderClass = 'db\\dsn\\loader\\' . $loadType;
        if( !class_exists($dsnLoaderClass) )
            throw \InvalidArgumentException("Unable to load class: $dsnLoaderClass");

        if( method_exists($dsnLoaderClass, $method) )
            return $dsnLoaderClass::$method($type, $key);
    }
}
