<?php

 if (!defined('e107_INIT')) { exit; }
if(!e107::isInstalled('static_cache'))
{ 
	return '';
}


if( (ST_CACHE_ENABLED===1 && !USER) ){
  
  $stcache_html           = ob_get_contents();
  
  //also load phpwee minifier...
  /*
  if(MVDA_CACHE_MIN_ENABLED===1){
    $stcache_html = PHPWee\Minify::html($stcache_html);
  }
  */
  $resultsItem->set($stcache_html)->expiresAfter(ST_CACHE_EXPIRATION);
  
  $oStaticCache->save($resultsItem);
  
  $sToDb_key = substr($resultsItem->getEncodedKey(), 0, 2).'/'.substr($resultsItem->getEncodedKey(), 2, 2).'/'.$resultsItem->getEncodedKey().'.'.$aScConfig['cacheFileExtension'];
  
  //scdb object
  $scdb       = new db();
  //22/06/2018 09:53:20 - oudoken
  //FIX delete key if already in database
  $scdb->db_Delete(
                  'static_cache_cpages',
                  'scache_key = \''.$resultsItem->getKey().'\''
                  );
  
  //insert page in db
  $scdb->db_Insert(
                  'static_cache_cpages',
                  array(
                  'scache_key'      => $resultsItem->getKey(),
                  'scache_url'      => $keyword_webpage_nu,
                  'scache_path'     => $sToDb_key,
                  'scache_lastmod'  => time()
                  )
                  );
  
  ob_end_flush();
}

?>