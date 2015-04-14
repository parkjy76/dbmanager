<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Handler(PDO)
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
 * @package    db
 * @author     Junyong Park
 * @copyright  2010
 */

namespace db;

class Handler
{
    /**
     * _dsnObj
     * 
     * @access protected
     * @var    DbDsnEntity
     */
    protected $_dsnObj = NULL;

    /**
     * _dbh
     * 
     * @access protected
     * @var    \PDO
     */
    protected $_dbh = NULL;

    /**
     * charset
     * 
     * @var string
     */
    protected $charset = 'utf8';


    /**
     * Constructor
     *
     * @access public
     * @param  \db\dsn\Entity $dsn
     * @return void
     */ 
    public function __construct( \db\dsn\Entity $dsn )
    {
        $this->_dsnObj = $dsn;
    }

    /**
     * getHandle
     *
     *
     * @access public
     * @param  array $options [Optional]
     * @return object
     */
    public function getHandle( array $options=array() )
    {
        if( !$this->_dbh instanceof \PDO )
        {
            // driver
            $dsn = $this->_dsnObj->getDriver() . ':';

            // 接続DSN
            foreach( $this->_dsnObj->dsn() as $key => $value )
                if( strlen($value) )
                    $dsn .= "${key}=${value};";

            // charset
            if( strlen($this->getCharset()) ) $dsn .= 'charset=' . $this->getCharset() . ';';

            // 接続開始
            $this->_dbh = new \PDO($dsn, $this->_dsnObj->getUser(), $this->_dsnObj->getPassword(), $options);
            $this->_dbh->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }

        return $this->_dbh;
    }

    /**
     * getDsn
     * 
     * @access public
     * @return DbDsnEntity
     */
    public function getDsn()
    {
        return $this->_dsnObj;
    }

    /**
     * setCharset
     * 
     * @access public
     * @param  string
     * @return DBHandler
     */
    public function setCharset( $charset )
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * getCharset
     * 
     * @access public
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

}
