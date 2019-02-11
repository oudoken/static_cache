<?php

/*
 * e107 Static Cache
 * Read cache list from ajax calls
 * 
 */

require_once('../../class2.php');

if (!defined('e107_INIT')) { exit; }

if(!e107::isInstalled('static_cache'))
{ 
	return '';
}

if (!getperms('P')) 
{
	e107::redirect('home');
	exit;
}

$qry_params = $_POST['params'];

$oSql = e107::getDb();

//count results
$qry = "SELECT 
              COUNT(l.scache_id) AS tot_results
        FROM 
             #static_cache_cpages l
        ";

//if search something...
if( $qry_params['search_str'] ){
  $qry .= "WHERE
           l.scache_key LIKE '%".$qry_params['search_str']."%'
           OR
           l.scache_url LIKE '%".$qry_params['search_str']."%'
           OR
           l.scache_path LIKE '%".$qry_params['search_str']."%'
           OR
           l.scache_lastmod LIKE '%".$qry_params['search_str']."%'
          ";
}

$oSql->gen($qry);
$row = $oSql->fetch();
$total_rows = $row['tot_results'];

//continue with real query
$qry = "SELECT 
              l.*
        FROM 
             #static_cache_cpages l
        ";

//if search something...
if( $qry_params['search_str'] ){
  $qry .= "WHERE
           l.scache_key LIKE '%".$qry_params['search_str']."%'
           OR
           l.scache_url LIKE '%".$qry_params['search_str']."%'
           OR
           l.scache_path LIKE '%".$qry_params['search_str']."%'
           OR
           l.scache_lastmod LIKE '%".$qry_params['search_str']."%'
          ";
}

$qry .= "
        ORDER BY 
        ";

//if sort orders...
if( $qry_params['sort_field'] ){
  $qry .= "`".$qry_params['sort_field']."` ".( ($qry_params['sort_order'])? $qry_params['sort_order']:'DESC' )." ";
}else{
  $qry .= "l.scache_id DESC ";
}

  $qry .= " LIMIT ".$qry_params['page_offset'].", ".$qry_params['page_size'] ;


  $qry .= "           ;";

//echo $qry;


$oSql->gen($qry);

$res_lines = Array();
while($row = $oSql->fetch()){
  //push to rows array...
  $row['scache_lastmod'] = gmdate("Y-m-d\TH:i:s\Z",$row['scache_lastmod']);
  $row['btn_delete'] = '<button title="'.LAN_STATIC_CACHE_ADMIN_20.'" class="btn btn-xs btn-danger fa fa-eraser " onclick="cnt_delete(\''.$row['scache_id'].'\');" ></span>' ;
  array_push($res_lines, $row);
}

$arr_results = array( "d" => array( "total" => $total_rows, "rows" => $res_lines ) );

echo json_encode( $arr_results );

?>