# PDO-based Database Manager with DSN Cache - PHP
* PDO
* DSN Entity class
 - driver, host, dbname, port, username, password
* DSN Loader class
 - CDB Loader
 - PHP-Array Loader
* support Memory Cache for loading DSN data.
* support charset for MySQL.

## Requirements
+ PHP 5.3+
+ APC(u), Memcached PHP module for cache

## Features
This library is DSN-based Database Manager.  
DSNデータは下記のような順番で指定

    DBNAME:HOST:USERNAME:PASSWORD:PORT

必要によって項目の値は省略可能  
このDSNデータをKey-Valueの形で用意する必要がある  
このライブラではPHPのArrayとCDBからKey-Valueでロード可能なLoaderが用意されてある   
DBManagerは下記のような形指定可能

    TYPE[:[number]];DSN_KEY[:[m|s|p][:number]]

必要によって項目の値は省略可能  
TYPEはDSNファイルの種類を意味して各ローダはDSNファイルをロードする時にこのTYPEをsuffixとして使う 
各ローダのDSNファイルのprefixは各ロード指定されてある
DSN_KEYはDSNキーであって一般的にDSN_KEYはDBNAMEを使うのをおすすめする
DSN_KEYmはmaster, sはslave, pはproxyの意味で指定により決まったsuffixをDSN_KEYのsuffixとして使う

    m : suffixなし
    s : _rep
    p : _proxy

suffix関してはdb/Managerで確認できる
例)

    $dbManager->getInstance('set;test');
    $dbManager->getInstance('set:1;test');
    $dbManager->getInstance('set:1;test;s');
    $dbManager->getInstance('set:1;test;s:1');

defaultのローダはPHP_Arrayになっているのでdb/dsn/loader/PHP_Arrayを使ってDSN情報をロードする  
1番目は指定したpathからdb.php.setをロードしtestというキーでDSNデータを取得する  
2番目は指定したpathからdb.php.set.1をロードしtestというキーでDSNデータを取得する  
2番目は指定したpathからdb.php.set.1をロードしtest_repというキーでDSNデータを取得する  
2番目は指定したpathからdb.php.set.1をロードしtest_rep1というキーでDSNデータを取得する 

DSNローダを使わずにdsn/EntityにDSN情報をセットし使うことも可能  

cache機能はこのDSNデータをcacheさせmemoryから高速でロードできるようにする
memory cache libaryはcache/memoryにあってDBMangerにMCのinstanceを指定しないとcacheは動作しない

## Settings
you need to define dsn filename(prefix) and path in db/dsn/loader/CDB.php, PHP_Array.php.  
default loader is PHP_Array, you can change it at db/Manager.  

## Sample Code
```php
<?php // PHP 5.4+
require_once('db/Manager.php');

/* use dsn file */
require_once('cache/memory/APC.php');
$dbManager = new db\Manager; 
$dbManager->setMemoryCache(new cache\memory\APC);
$dbInst = $dbManager->getInstance('test;test');
// $dbInst = $dbManager->getInstance('set:1;test'); // ;m');
// $dbInst = $dbManager->getInstance('set:1;test;s'); // ;s:1');
// $dbInst = $dbManager->getInstance('set:1;test;p'); // ;p:1');
var_dump($dbInst);

try {
    $dbh = $dbInst->getHandle();
    $stmt = $dbh->query('select * from aaa limit 1');
} catch( Exception $e ) {
    print_r($e);
}

while( $row = $stmt->fetch(PDO::FETCH_ASSOC) )
    print_r($row);

/* use dsn entity */
require_once('db/dsn/Entity.php');

$dsnEnt = new db\dsn\Entity;
$dsnEnt->setDbName('test');
$dsnEnt->setHost('localhost');
$dsnEnt->setUser('parkjy76');

$dbInst = (new db\Manager)->getInstanceByDsn($dsnEnt);
$dbInst->setCharset('utf8'); // set charset

try {
    $dbh = $dbInst->getHandle();
    $stmt = $dbh->query('select * from aaa limit 1');
} catch( Exception $e ) {
    print_r($e);
}

while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) ;

// result
/*
object(db\Handler)#9 (3) {
  ["_dsnObj":protected]=>
  object(db\dsn\Entity)#7 (5) {
    ["_type":"db\dsn\Entity":private]=>
    NULL
    ["_driver":"db\dsn\Entity":private]=>
    string(5) "mysql"
    ["_user":"db\dsn\Entity":private]=>
    string(8) "parkjy76"
    ["_password":"db\dsn\Entity":private]=>
    NULL
    ["_dsn":"db\dsn\Entity":private]=>
    array(3) {
      ["port"]=>
      int(3306)
      ["dbname"]=>
      string(4) "test"
      ["host"]=>
      string(9) "localhost"
    }
  }
  ["_dbh":protected]=>
  NULL
  ["charset":protected]=>
  string(4) "utf8"
}
Array
(
    [id] => 1
    [borrowdate] => 2014-10-18
    [barcode] => aaaaaa
)
*/
```
+ APC Cache  
<p align="center"><img src="https://github.com/parkjy76/dbmanager/blob/master/images/apc.png"></p>
