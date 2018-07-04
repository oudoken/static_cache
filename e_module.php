<?php
/*
 *
*/

 if (!defined('e107_INIT')) { exit; }
if(!e107::isInstalled('static_cache'))
{ 
	return '';
}

//full caching system  START

//get plugin configuration
$sc_prefs = e107::getPlugConfig('static_cache')->getPref();

define('ST_CACHE_ENABLED',intval($sc_prefs['sc_enabled']));
define('ST_CACHE_EXPIRATION',intval($sc_prefs['sc_expiration']));
//also minify content?
define('ST_CACHE_MIN_ENABLED',intval($sc_prefs['sc_minification']));
//gzip compression enabled?
define('ST_CACHE_GZIP_ENABLED',intval($sc_prefs['sc_gzip_server']));
//pages excluded from cache
define('ST_CACHE_EXCLUDE_PAGES',$sc_prefs['sc_exclude_list']);
//path to save cache
define('ST_CACHE_SAVE_PATH',str_replace('/', DIRECTORY_SEPARATOR,e_ROOT.$e107->getFolder('web').$sc_prefs['sc_cache_path']));

$aPageExcluded = explode(',', ST_CACHE_EXCLUDE_PAGES );
$sCurrentPage  = basename($_SERVER['SCRIPT_FILENAME']); //add ,'.php' to remove extension...

//check if current page is excluded from cache and exit immediately
if( in_array($sCurrentPage, $aPageExcluded ) ){
  return '';
}

global $e_event,$e107cache,$ns;

// $e_event->register("newspost", "pingit");
// $e_event->register("newsupd", "pingit");		// Disable these for now, until admin functions written

//phpFastCache inside plugin
require(e_PLUGIN.'static_cache/libs/phpfastcache/src/autoload.php');
//require(e_PLUGIN.'static_cache/libs/phpwee/phpwee.php');

use phpFastCache\CacheManager;
use phpFastCache\Core\phpFastCache;

// Setup File Path on your config files
CacheManager::setDefaultConfig([
  "path" => ST_CACHE_SAVE_PATH,
  "itemDetailedDate" => false
]);

//instantiate class
$oStaticCache = CacheManager::getInstance('files');

//get config object
$aScConfig     = $oStaticCache->getConfig();

$keyword_webpage     = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'];
$keyword_webpage_nu  = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$keyword_webpage_md5 = md5($keyword_webpage);

// try to get from Cache first.
$resultsItem     = $oStaticCache->getItem($keyword_webpage_md5);
$resultsItemCnt  = $resultsItem->get();

if(!is_null($resultsItemCnt) &&
   (ST_CACHE_ENABLED===1 && !USER)){
  
  if(ST_CACHE_GZIP_ENABLED===1){
    //21/05/2018 14:17:53 - RF
    //mod_pagespeed on!
    $unzipped_string = gzdecode( $resultsItemCnt );
    echo $unzipped_string;
  }else{
    echo $resultsItemCnt;
  }
  
  //file_put_contents(dirname(__FILE__).e_WEB_ABS."cache/test.txt",$unzipped_string);
  
  echo "\n<!-- File generated from cache ".$resultsItem->getExpirationDate()->format('Y-m-d H:i:s')." -->";
  
  exit;
}

//cache ONLY when user is NOT LOGGED!
if( (ST_CACHE_ENABLED===1 && !USER) ){
  ob_start();
}

?>