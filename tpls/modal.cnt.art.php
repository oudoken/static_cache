<?php

require_once('../../../class2.php');
if (!getperms('P')) 
{
  e107::redirect('admin');
  exit;
}

//04/05/2017 11:16:53 - Roberto
//is user superadmin?

$el_hide_non_admin = (check_class(e_UC_MAINADMIN))? '' : 'hide';

?>

<div class="modal-dialog " role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="gridSystemModalLabel" data-content="title" style="width:95%;float:left;" ></h4>
      <button class="btn btn-xs btn-danger glyphicon glyphicon-remove " data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#data-gen" aria-controls="data-gen" role="tab" data-toggle="tab">Generale</a></li>
      </ul>
      
        <!-- hiddens vars -->
        <input type="hidden" data-value="id" id="id" value="" />
        <input type="hidden" data-value="cat" id="cat" value="" />
      
        <!-- Tab panes -->
        
        <!-- contenuti generali -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="data-gen">
            <div class="form-horizontal " >
              <div class="form-group">
                <label for="message-text" class="col-sm-2 control-label">Id</label>
                <div class="col-sm-1">
                  <input type="text" class="form-control input-sm " data-value="art_id" id="art_id" value="" />
                </div>
                <label for="message-text" class="col-sm-1 control-label">Datestamp</label>
                <div class="col-sm-2">
                  <input type="text" class="form-control input-sm " data-value="art_datestamp" id="art_datestamp" value="" />
                </div>
              </div>
              <div class="form-group <?php echo $el_hide_non_admin; ?> " >
                <label for="message-text" class="col-sm-2 control-label">Titolo</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control input-sm " data-value="art_title" id="art_title" value="" />
                </div>
              </div>
              <div class="form-group <?php echo $el_hide_non_admin; ?> " >
                <label for="message-text" class="col-sm-2 control-label">Descrizione</label>
                <div class="col-sm-10">
                  <textarea type="text" class="form-control input-sm " data-content="art_description" id="art_description" rows="10" ></textarea>
                </div>
              </div>
              <div class="form-group <?php echo $el_hide_non_admin; ?> " >
                <label for="message-text" class="col-sm-2 control-label">Tipologia</label>
                <div class="col-sm-6">
                  <select class="form-control input-sm " id="art_tipologia" data-value="art_tipologia_sel" data-template-bind='{"attribute": "options", "value": {"data": "art_tipologia_opt", "value":"value", "content":"content"}}'></select>
                </div>
                <label for="message-text" class="col-sm-2 control-label">Weight</label>
                <div class="col-sm-2">
                  <input type="text" class="form-control input-sm " data-value="art_weight" id="art_weight" value="" />
                </div>
              </div>
              <div class="form-group <?php echo $el_hide_non_admin; ?> " >
                <label for="message-text" class="col-sm-2 control-label">Immagine</label>
                <div class="col-sm-4">
                  <input type="text" onchange="updateImgArt();" class="form-control input-sm " data-value="art_percorso" id="art_percorso" value="" />
                </div>
                <div class="col-sm-4">
                  <img src class=" img-thumbnail" data-src="art_percorso_full_path" id="img_path_percorso_src"  />
                </div>
              </div>
              <div class="form-group <?php echo $el_hide_non_admin; ?> " >
                <label for="message-text" class="col-sm-2 control-label">Score</label>
                <div class="col-sm-4">
                  <select class="form-control input-sm " id="art_score" data-value="art_score_sel" data-template-bind='{"attribute": "options", "value": {"data": "art_score_opt", "value":"value", "content":"content"}}'></select>
                </div>
                <label for="message-text" class="col-sm-2 control-label">Meta tags</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control input-sm " data-value="art_meta" id="art_meta" value="" />
                </div>
              </div>

        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-success" data-content="btnconfirm" onclick="cnt_edit_confirm();" ></button>
      <button type="button" class="btn btn-danger" data-dismiss="modal" data-content="btncancel"></button>
    </div>
   </div>
</div>