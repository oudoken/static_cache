<?php
/*
 *
 */

require_once('../../class2.php');
if (!getperms('P')) 
{
	e107::redirect('home');
	exit;
}

//$pref = e107::getPref();

//get db object
$oSql = e107::getDb();

$edit_id = $_POST['id'];

if( $edit_id!='' ){
  //
  if($edit_id=='all'){
    //remove all items
    $oStaticCache->clear();
    $del_where = null;
  }else{
    $oSql->select('static_cache_cpages', 'scache_key', 'scache_id='.$edit_id);
    $rowScache = $oSql->fetch();
    $sCacheKey = $rowScache['scache_key'];
    //delete cache with key method
    $oStaticCache->deleteItem($sCacheKey);
    $del_where = 'scache_id='.$edit_id;
    //unlink ( string $filename [, resource $context ] )
  }
  if($oSql->delete('static_cache_cpages', $del_where)){
    echo json_encode( array('err_code'=>'0', 'err_desc'=>'no error' ) );
  }else{
    echo json_encode( array('err_code'=>'1', 'err_desc'=>'error while updating' ) );
  }
}else{
  echo json_encode( array('err_code'=>'1', 'err_desc'=>'error while updating' ) );
}

?>