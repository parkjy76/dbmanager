<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Manager
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * PHP 5.3 Later
 * 
 * @final
 * @package    db
 * @author     Junyong Park
 * @copyright  2012
 */

namespace db;

require_once('Handler.php');
require_once('dsn/Loader.php');

use db\dsn\Loader;


final class Manager
{
    /**
     * DB_KEY_SUFFIX
     *
     * @access const
     * @var    string
     */
    const DB_KEY_SUFFIX_REPLICATION = '_rep';
    const DB_KEY_SUFFIX_PROXY = '_proxy';

    /**
     * MC_TTL_DEFAULT
     *
     * @access const
     * @var    integer
     */
    const MC_TTL_DEFAULT = 43200;

    /**
     * $_dsnLoadType
     *
     * @access private
     * @var    string
     */
//     private $_dsnLoadType = Loader::TYPE_CDB;
    private $_dsnLoadType = Loader::TYPE_ARRAY;

    /**
     * $_mc
     *
     * @access private
     * @var    object
     */
    private $_mc = NULL;


    /**
     * Constructor
     *
     * @access public
     * @return void
     */
    public function __construct() {}

    /**
     * setDsnLoadType
     * 
     * @access public
     * @param  string $dsnLoadType
     * @return boolean
     */
    public function setDsnLoadType( $dsnLoadType )
    {
        $this->_dsnLoadType = $dsnLoadType;

        return TRUE;
    }

    /**
     * setMemoryCache
     * 
     * @access public
     * @param  \cache\memory\_Interface $mc
     * @return boolean
     */
    public function setMemoryCache( \cache\memory\_Interface $mc )
    {
        $this->_mc = $mc;

        return TRUE;
    }

    /**
     * getInstance
     * DB接続インスタンスを返す
     *
     * @access public
     * @param  string
     * @return mixed
     */
    public function getInstance( $dbInfo )
    {
        return $this->getInstanceByDsn($this->getDsn($dbInfo));
    }

    /**
     * getInstanceByDsn
     *
     * @access public
     * @param  \db\dsn\Entity $dsnEnt
     * @return Handler
     */
    public function getInstanceByDsn( \db\dsn\Entity $dsnEnt )
    {
        return new Handler($dsnEnt);
    }

    /**
     * getDsn
     * dns entityを返す
     *
     * @access public
     * @param  string
     * @return mixed
     */
    public function getDsn( $dbInfo )
    {
        $parseDbInfo = $this->_parseDbInfo($dbInfo);
        if( $parseDbInfo === FALSE ) throw new \Exception($dbInfo . ' is invalid format');

        return $this->_getDsnEnt($parseDbInfo['type'], $parseDbInfo['key']);
    }

    /**
     * _parseDbInfo
     *
     * @access private
     * @param  string
     * @return array
     */
    private function _parseDbInfo( $dbInfo )
    {
        if( !is_string($dbInfo) ) return FALSE;

        // ;で区切り、数チェック
        $expStr = str_replace(' ', '', explode(';', $dbInfo));
        if( count($expStr) < 2 || count($expStr) > 3 ) return FALSE;

        // 接続種類
        if( !preg_match('/^([^:]+):?([0-9]+)?$/i', $expStr[0], $matches) ) return FALSE;
        unset($matches[0]);
        $result['type'] = implode('.', $matches);

        // key生成
        if( !strlen($expStr[1]) ) return FALSE;
         $result['key'] = $expStr[1];

        // key suffix
        if( isset($expStr[2]) && strlen($expStr[2]) )
        {
            if( !preg_match('/^(m|s|p):?([0-9]+)?$/i', $expStr[2], $matches) ) return FALSE;

            // slave又はproxyの場合
            if( $matches[1] !== 'm' )
            {
                if( $matches[1] === 's' ) $result['key'] .= self::DB_KEY_SUFFIX_REPLICATION;
                elseif( $matches[1] === 'p' ) $result['key'] .= self::DB_KEY_SUFFIX_PROXY;

                if( isset($matches[2]) ) $result['key'] .= $matches[2];
            }
        }
        return $result;
    }

    /**
     * _getDsnEnt
     *
     * @access private
     * @param  string
     * @param  string
     * @return object DbDsnEntity
     */
    private function _getDsnEnt( $type, $key )
    {
        // Memory Cache 使用不可の場合
        if( !$this->_mc instanceof \cache\memory\_Interface || !$this->_mc->isAvailable() )
        {
            // dsn loaderから接続情報取得
            $dsn = Loader::getDsn($this->_dsnLoadType, $type, $key);
        }
        // Memory Cache 使用可能の場合
        else
        {
            $mcKeyName = $this->_getMCKeyName($type, $key);

            // cacheから接続情報取得
            $dsn = $this->_mc->get($mcKeyName);
            if( $dsn === FALSE )
            {
                // dsn loaderから接続情報取得$keyName
                $dsn = Loader::getDsn($this->_dsnLoadType, $type, $key);

                // OP Cacheに保存（エラーチェックはしない）
                $this->_mc->set($mcKeyName, $dsn, self::MC_TTL_DEFAULT);
            }
        }

        $dsnEnt = $this->_createDsnEnt($dsn);
        $dsnEnt->setType($type);

        return $dsnEnt;
    }


    /**
     * _createDsnEnt
     *
     * @access private
     * @param  string $dsn
     * @return object DsnEntity
     */
    private function _createDsnEnt( $dsn )
    {
        // 接続情報定義
        $expDsn = explode(":", $dsn);

        // dsn entityに接続情報セット
        require_once('dsn/Entity.php');
        $dsnEnt = new dsn\Entity;
        if( isset($expDsn[0]) ) $dsnEnt->setDbName($expDsn[0]);
        if( isset($expDsn[1]) ) $dsnEnt->setHost($expDsn[1]);
        if( isset($expDsn[2]) ) $dsnEnt->setUser($expDsn[2]);
        if( isset($expDsn[3]) ) $dsnEnt->setPassword($expDsn[3]);
        if( isset($expDsn[4]) ) $dsnEnt->setPort($expDsn[4]);

        return $dsnEnt;
    }

    /**
     * _getMCKeyName
     *
     * @access private
     * @param  string $type
     * @param  string $key
     * @return string
     */
    private function _getMCKeyName( $type, $key )
    {
        return 'dsn::' . $type . ':' . $key;
    }
}
