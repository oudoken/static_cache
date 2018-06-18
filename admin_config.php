<?php

/**
 * @file
 * Class installations to handle configuration forms on Admin UI.
 */

require_once('../../class2.php');

if(!e107::isInstalled('static_cache') || !getperms("P"))
{
	e107::redirect(e_BASE . 'index.php');
}

e107::lan('static_cache', true, true);


/**
 * Class static_cache_admin.
 */
class static_cache_admin extends e_admin_dispatcher
{

	/**
	 * Required (set by child class).
	 *
	 * Controller map array in format.
	 * @code
	 *  'MODE' => array(
	 *      'controller' =>'CONTROLLER_CLASS_NAME',
	 *      'path' => 'CONTROLLER SCRIPT PATH',
	 *      'ui' => 'UI_CLASS', // extend of 'comments_admin_form_ui'
	 *      'uipath' => 'path/to/ui/',
	 *  );
	 * @endcode
	 *
	 * @var array
	 */
	protected $modes = array(
		'main' => array(
			'controller' => 'static_cache_admin_ui',
			'path'       => null,
		),
		'sc_cached'	=> array(
			'controller' 	=> 'static_cache_admin_cached_ui',
			'path' 			=> null,
		),
	);

	/**
	 * Optional (set by child class).
	 *
	 * Required for admin menu render. Format:
	 * @code
	 *  'mode/action' => array(
	 *      'caption' => 'Link title',
	 *      'perm' => '0',
	 *      'url' => '{e_PLUGIN}plugname/admin_config.php',
	 *      ...
	 *  );
	 * @endcode
	 *
	 * Note that 'perm' and 'userclass' restrictions are inherited from the $modes, $access and $perm, so you don't
	 * have to set that vars if you don't need any additional 'visual' control.
	 *
	 * All valid key-value pair (see e107::getNav()->admin function) are accepted.
	 *
	 * @var array
	 */
	protected $adminMenu = array(
		'main/prefs' => array(
			'caption' => LAN_STATIC_CACHE_ADMIN_01,
			'perm'    => 'P',
		),
		'sc_cached/custom' => array(
			'caption' => LAN_STATIC_CACHE_ADMIN_02,
			'perm'    => 'P',
		),
	);

	/**
	 * Optional (set by child class).
	 *
	 * @var string
	 */
	protected $menuTitle = LAN_PLUGIN_STATIC_CACHE_NAME;

}


/**
 * Class static_cache_admin_ui.
 */
class static_cache_admin_ui extends e_admin_ui
{

	/**
	 * Could be LAN constant (multi-language support).
	 *
	 * @var string plugin name
	 */
	protected $pluginTitle = LAN_PLUGIN_STATIC_CACHE_NAME;

	/**
	 * Plugin name.
	 *
	 * @var string
	 */
	protected $pluginName = "static_cache";

	/**
	 * Example: array('0' => 'Tab label', '1' => 'Another label');
	 * Referenced from $prefs property per field - 'tab => xxx' where xxx is the tab key (identifier).
	 *
	 * @var array edit/create form tabs
	 */
	protected $preftabs = array(
		LAN_STATIC_CACHE_ADMIN_01,
	);

	/**
	 * Plugin Preference description array.
	 *
	 * @var array
	 */
	protected $prefs = array(
		'sc_enabled'    => array(
			'title' => LAN_STATIC_CACHE_ADMIN_12,
			'help'  => LAN_STATIC_CACHE_ADMIN_13,
			'type'  => 'boolean',
			'data'  => 'int',
			'tab'   => 0,
		),
		'sc_full_path_cache'    => array(
			'title' => LAN_STATIC_CACHE_ADMIN_05,
			'help'  => ST_CACHE_SAVE_PATH,
			'type'  => 'method',
			'data'  => 'str',
			'tab'   => 0,
		),
		'sc_exclude_list'    => array(
			'title' => LAN_STATIC_CACHE_ADMIN_08,
			'help'  => LAN_STATIC_CACHE_ADMIN_09,
			'type'  => 'textarea',
			'data'  => 'str',
			'tab'   => 0,
		),
		'sc_cache_path'    => array(
			'title' => LAN_STATIC_CACHE_ADMIN_10,
			'help'  => LAN_STATIC_CACHE_ADMIN_11,
			'type'  => 'text',
			'data'  => 'str',
			'tab'   => 0,
		),
		'sc_expiration'    => array(
			'title' => LAN_STATIC_CACHE_ADMIN_14,
			'help'  => LAN_STATIC_CACHE_ADMIN_15,
			'type'  => 'text',
			'data'  => 'int',
			'tab'   => 0,
		),
		'sc_gzip_server_tip'    => array(
			'title' => '',
			'help'  => IS_GZIP_SET_TIP,
			'type'  => 'method',
			'data'  => 'str',
			'tab'   => 0,
		),
		'sc_gzip_server'    => array(
			'title' => LAN_STATIC_CACHE_ADMIN_16,
			'help'  => LAN_STATIC_CACHE_ADMIN_17.' '.IS_GZIP_SET,
			'type'  => 'boolean',
			'data'  => 'int',
			'tab'   => 0,
		),
    /*
		'sc_minification'    => array(
			'title' => LAN_STATIC_CACHE_ADMIN_18,
			'help'  => LAN_STATIC_CACHE_ADMIN_19,
			'type'  => 'boolean',
			'data'  => 'int',
			'tab'   => 0,
		),
    */
	);

	/**
	 * User defined init.
	 */
	public function init()
	{
		$isGzipSet = (!$this->getGzipEncoding())? 0 : 1;
		define('IS_GZIP_SET', $isGzipSet);
		if($isGzipSet==1){
			define('IS_GZIP_SET_TIP', LAN_STATIC_CACHE_ADMIN_29);
		}else{
			define('IS_GZIP_SET_TIP', LAN_STATIC_CACHE_ADMIN_30);
		}
		$prefs = e107::getPlugConfig('static_cache')->getPref();
	}
  
  public function getGzipEncoding(){
    
    $get = array();
    $options = array();
    
    $defaults = array(
        CURLOPT_URL => "https://www.montagnavda.it/". (strpos("https://www.montagnavda.it/", '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_ENCODING => 'gzip,deflate'
    );
   
    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch)){
      trigger_error(curl_error($ch));
    }
    
    curl_close($ch);
    
    $headers = [];
    $data = explode("\n",$result);
    $headers['status'] = $data[0];
    array_shift($data);
    
    foreach($data as $part){
      $middle=explode(":",$part);
      $headers[trim($middle[0])] = trim($middle[1]);
    }
    
    return $headers; 
  }
  
}

/**
 * Class static_cache_admin_cached_ui.
 */
class static_cache_admin_cached_ui extends e_admin_ui
{

	/**
	 * Could be LAN constant (multi-language support).
	 *
	 * @var string plugin name
	 */
	protected $pluginTitle = LAN_PLUGIN_STATIC_CACHE_NAME;

	/**
	 * Plugin name.
	 *
	 * @var string
	 */
	protected $pluginName = "static_cache";

	/**
	 * Example: array('0' => 'Tab label', '1' => 'Another label');
	 * Referenced from $prefs property per field - 'tab => xxx' where xxx is the tab key (identifier).
	 *
	 * @var array edit/create form tabs
	 */
	protected $preftabs = array(
		LAN_STATIC_CACHE_ADMIN_03,
	);


	/**
	 * User defined init.
	 */
	public function init()
	{
		$prefs = e107::getPlugConfig('static_cache')->getPref();
	}


		// optional - a custom page.  
		public function customPage()
		{
      /*
			$text = 'Hello World!';
			$otherField  = $this->getController()->getFieldVar('other_field_name');
			return $text;
      */
      
      $pref = e107::getPref();
      
      ?>
<!-- production -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/jquery.loadTemplate.min.js"></script>
<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/jquery.loadTemplate.min.js"></script>

<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/bootstrap-table.js"></script>

<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/bootstrap-table-locale-all.min.js"></script>

<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/tableExport.js"></script>
<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/bootstrap-table-export.min.js"></script>
<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/bootstrap-table-sticky-header.min.js"></script>
<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/bootstrap-table-multiple-sort.js"></script>

<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/bootstrap-table-it-IT.min.js"></script>

<script type="text/javascript" src="<?php echo e_PLUGIN; ?>static_cache/libs/spin-js.min.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo e_PLUGIN; ?>static_cache/libs/bootstrap-table.css">
<link rel="stylesheet" href="<?php echo e_PLUGIN; ?>static_cache/libs/bootstrap-table-sticky-header.css">

<style>
.spacer{margin-top:0.5em;}
.input-group-addon input:hover{cursor:pointer;}
.centered{text-align:center;}

.form-group input[type="checkbox"] {
    display: none;
}

.form-group input[type="checkbox"] + .btn-group > label span {
    width: 20px;
}

.form-group input[type="checkbox"] + .btn-group > label span:first-child {
    display: none;
}
.form-group input[type="checkbox"] + .btn-group > label span:last-child {
    display: inline-block;   
}

.form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
    display: inline-block;
}
.form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
    display: none;   
}

.sdata-loader {
  position:fixed; 
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
  top:40vh;
  left:45%;
  display:none;
  z-index:99999;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}


.blocker{ 
  position:fixed; 
  top:0; 
  left:0; 
  background:rgba(0,0,0,0.6); 
  z-index:99998; 
  width:100%; 
  height:100%; 
  display:none; 
}

.modal-data{
  position:fixed; 
  top:5vh;
  left:25%;
  z-index:99997;
  /*overflow: scroll;*/
}

.modal-data .modal-dialog{
  width: 850px !important;
}

.form-horizontal{
  margin-top: 1vh;
}

.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    max-height: calc(100vh - 200px);
    overflow-y: auto;
    overflow-x: hidden !important;
}
</style>

<script type="text/javascript">

$(document).ready(function() {

<?php
  $oSql = e107::getDb();
  $oSql->select('static_cache_cpages', 'scache_id, scache_key, scache_url, scache_path, scache_lastmod');
  $res_lines = Array();
  while($row = $oSql->fetch()){
    //push to rows array...
    $row['scache_lastmod'] = gmdate("Y-m-d\TH:i:s\Z",$row['scache_lastmod']);
    $row['btn_delete'] = '<button title="'.LAN_STATIC_CACHE_ADMIN_20.'" class="btn btn-xs btn-danger glyphicon glyphicon-remove " onclick="cnt_delete(\''.$row['scache_id'].'\');" ></span>' ;
    array_push($res_lines, $row);
  }
  
  $arr_results = array( "d" => array( "total" => $oSql->rowCount(), "rows" => $res_lines ) );
  
  echo 'var data = '.json_encode( $arr_results ).';';
  echo "\n";
  
?>
  if ( $('.navbar-fixed-top').css('height') ) {
      stickyHeaderOffsetY = +$('.navbar-fixed-top').css('height').replace('px','');
  }
  if ( $('.navbar-fixed-top').css('margin-bottom') ) {
      //stickyHeaderOffsetY += +$('.navbar-fixed-top').css('margin-bottom').replace('px','');
  }
  var tbl_spriority = [];
  var data_cols = [
                  {field:'scache_key',title: 'Key',sortable:true},
                  {field:'scache_url',title: 'Url',sortable:true},
                  {field:'scache_path',title: 'Path',sortable:true},
                  {field:'scache_lastmod',title: 'Ultima modifica',sortable:true},
                  {field:'btn_delete',title: '',sortable:false}
                  ];
  
  $('#tbl-cnt-data').bootstrapTable({
        classes: 'table table-responsive table-striped table-bordered',
        undefinedText: '',
        data:data.d.rows,
        iconsPrefix: 'fa',
        showRefresh: true,
        search: true,
        pageSize: 100,
        pagination: true,
        sidePagination: 'client',
        sortable: true,
        cookie: true,
        mobileResponsive: true,
        stickyHeader: true,
        stickyHeaderOffsetY: stickyHeaderOffsetY + 'px',
        showExport: true,
        showColumns: true,
        exportDataType: 'all',
        exportTypes: ['csv', 'txt','json','xml','excel','sql','pdf'],
        maintainSelected: true,
        showMultiSort: true,
        sortPriority: tbl_spriority,
        paginationFirstText: "First",
        paginationLastText: "Last",
        paginationPreText: "Previous",
        paginationNextText: "Next",
        pageList: ['10','25','50','100','150','200'],
        icons: {
            paginationSwitchDown: 'fa-caret-square-o-down',
            paginationSwitchUp: 'fa-caret-square-o-up',
            columns: 'fa-columns',
            refresh: 'fa-refresh',
            sort:'fa-sort-amount-asc',
            plus: 'fa-plus',
            minus: 'fa-minus',
            export: 'fa-database'
        },
        columns: data_cols,
      //rows: data.d.rows,
      total: data.d.total,
      
    });
});

//
function cnt_delete(id){
  var body_text;
  if(id=='all'){
    body_text = '<?php echo LAN_STATIC_CACHE_ADMIN_22_ALL ; ?>';
  }else{
    body_text = '<?php echo LAN_STATIC_CACHE_ADMIN_22 ; ?>';
  }
  $('#bs-modal').empty();
  $('#bs-modal').loadTemplate('tpls/modal.confirm.html',
    {
        title: '<?php echo LAN_STATIC_CACHE_ADMIN_21 ; ?>',
        body: body_text,
        
        btnconfirm:   '<?php echo LAN_STATIC_CACHE_ADMIN_23 ; ?>',
        btncancel:    '<?php echo LAN_STATIC_CACHE_ADMIN_24 ; ?>'
    },
    {
    overwriteCache: true,
    isFile: true
    }
    )
    .modal('show')
    .off('click', '#btnconfirm')
    .off('click', '#btncancel')
    .one('click', '#btnconfirm', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        cnt_delete_confirm(id);
        $('#bs-modal').modal('hide');
    });
}

//delete values
function cnt_delete_confirm(id){

  $('.blocker').css('display','block');
  $('.sdata-loader').css('display','block');
  
  //edit with ajax
  $.ajax({
           type: 'POST',
           url: 'sc_dcache.php',
           dataType: 'JSON',
           //jsonpCallback: "info",
           //data: JSON.stringify( aDataEdit ),
           data: {id: id},
           //crossDomain: true,
           success: function(data) {
                      //console.log( JSON.stringify(data) );
                      
                      $('.blocker').css('display','none');
                      $('.sdata-loader').css('display','none');
                      
                      //no error ok...
                      if(data.err_code==0){
                        //trigger change to refresh list
                        location.reload();
                      }else{
                        $('#bs-modal').empty();
                        $('#bs-modal').loadTemplate('tpls/modal.message.html',
                          {
                              title: '<?php echo LAN_STATIC_CACHE_ADMIN_21 ; ?>',
                              body:  '<?php echo LAN_STATIC_CACHE_ADMIN_25 ; ?>'+data.err_desc,
                              btn:   '<?php echo LAN_STATIC_CACHE_ADMIN_26 ; ?>'
                          }
                          )
                          .modal('show');
                        
                      }
           },
           error: function(e) {
             console.log("errore "+e.message);
           },
           complete: function(e) {
             //console.log("cmpl "+e.message);
           }
  });
  
}

</script>
<div id="bs-modal" class="modal-data"></div>
<div id="bs-modal-cnf" class="modal-data"></div>
<div class="blocker" ></div>
<div class="sdata-loader" ></div>
<div class="centered" >
  <div class="row">
    <table id="tbl-cnt-data" >
    </table>
  </div>
  <hr />
  <div class="row">
    <button title="<?php echo LAN_STATIC_CACHE_ADMIN_27 ; ?>" type="button" id="update_db" class="btn btn-danger" onclick="cnt_delete('all');"><?php echo LAN_STATIC_CACHE_ADMIN_28 ; ?></button>
    <br /><br />
  </div>
</div>
<?php
			
		}
}

new static_cache_admin();

require_once(e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();
require_once(e_ADMIN . "footer.php");
exit;
