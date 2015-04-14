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
