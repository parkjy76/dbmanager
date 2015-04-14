<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Entity
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
 * @copyright  2011
 */

namespace db\dsn;


class Entity
{
    /**
     * DRIVER_DEFAULT
     * 
     * @access const
     * @var    string
     */
    const DRIVER_DEFAULT = 'mysql';

    /**
     * PORT_DEFAULT
     * 
     * @access const
     * @var    string
     */
    const PORT_DEFAULT = 3306;

    /**
     * _type
     * 
     * @access private
     * @var    string
     */
    private $_type = NULL;

    /**
     * _driver
     * DB種類
     * 
     * @access private
     * @var string
     */
    private $_driver = NULL;

    /**
     * _user
     * 
     * @access private
     * @var string
     */
    private $_user = NULL;

    /**
     * _password
     * 
     * @access private
     * @var string
     */
    private $_password = NULL;

    /**
     * _dsn
     * 
     * @access private
     * @var    array
     */
    private $_dsn = array();


    /**
     * コンストラクタ
     *
     *
     * @access public
     */
    public function __construct ()
    {
        $this->_driver = self::DRIVER_DEFAULT;
        $this->_dsn = array(
            'port' => self::PORT_DEFAULT,
        );
    }

    /**
     * dsn
     * 
     * @access public
     * @return array
     */
    public function dsn()
    {
        return $this->_dsn;
    }

    /**
     * setType
     *
     * @access public
     * @param  string
     * @return string
     */
    public function setType( $type )
    {
        $this->_type = $type;

        return TRUE;
    }

    /**
     * getType
     *
     * @access public
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * setDriver
     *
     * @access public
     * @param  string
     * @return boolean
     */
    public function setDriver( $driver )
    {
        $this->_driver = $driver;

        return TRUE;
    }

    /**
     * getDriver
     *
     * @access public
     * @return string
     */
    public function getDriver()
    {
        return $this->_driver;
    }

    /**
     * setDbName
     *
     * @access public
     * @param  string 
     * @return boolean
     */
    public function setDbName( $dbName )
    {
        $this->_dsn['dbname'] = $dbName;

        return TRUE;
    }

    /**
     * getDbName
     *
     * @access public
     * @return string
     */
    public function getDbName()
    {
        return isset($this->_dsn['dbname']) ? $this->_dsn['dbname'] : NULL; 
    }

    /**
     * setHost
     *
     * @access public
     * @param  string 
     * @return boolean
     */
    public function setHost( $host )
    {
        $this->_dsn['host'] = $host;

        return TRUE;
    }

    /**
     * getHost
     *
     * @access public
     * @return string
     */
    public function getHost()
    {
        return isset($this->_dsn['host']) ? $this->_dsn['host'] : NULL;
    }

    /**
     * setPort
     *
     * @access public
     * @param  string 
     * @return boolean
     */
    public function setPort( $port )
    {
        $this->_dsn['port'] = $port;

        return TRUE;
    }

    /**
     * getPort
     *
     * @access public
     * @return string
     */
    public function getPort()
    {
        return isset($this->_dsn['port']) ? $this->_dsn['port'] : NULL;
    }

    /**
     * setUser
     *
     * @access public
     * @param  string 
     * @return boolean
     */
    public function setUser( $user )
    {
        $this->_user = $user;

        return TRUE;
    }

    /**
     * getUser
     *
     * @access public
     * @return string
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * setPassword
     *
     * @access public
     * @param  string 
     * @return boolean
     */
    public function setPassword( $password )
    {
        $this->_password = $password;

        return TRUE;
    }

    /**
     * getPassword
     *
     * @access public
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }
}
