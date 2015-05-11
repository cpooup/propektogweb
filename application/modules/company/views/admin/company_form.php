<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4><?php echo $title;?></h4>
        </div>
        <div class="modal-body">
            <?php echo form_open_multipart('company_form','', array('role'=>'form')); ?>
            <?php echo form_hidden('is_admin', 1); ?>
            <?php if (isset($company_id)) : ?>
                <?php echo form_hidden('id', $company_id); ?>
            <?php endif; ?>
            <?php if($method=='add') : ?>
                        <div class="form-group has-feedback <?php echo (form_error('sitename')) ? 'has-error': ''; ?>">
                            <div class="input-group">
                              <?php echo form_span(lang('users input sitename'), 'name', array('class'=>'input-group-addon')); ?>
                              <?php echo form_input(array('name'=>'sitename', 'value'=>set_value('sitename', (isset($company['sitename']) ? $company['sitename'] : '')), 'class'=>'form-control', 'placeholder'=>lang('company input enter_sitename'))); ?>
                            </div>
                        </div>
             <?php else : ?>
                <?php echo form_hidden('sitename', $company['sitename']); ?>
            <?php endif ; ?>
            <div class="form-group has-feedback <?php echo (form_error('sitename_name')) ? 'has-error': ''; ?>">
                <div class="input-group">
                  <?php echo form_span(lang('users input sitename_name'), 'sitename_name', array('class'=>'input-group-addon')); ?>
                  <?php echo form_input(array('name'=>'sitename_name', 'value'=>set_value('sitename_name', (isset($company['sitename_name']) ? $company['sitename_name'] : '')), 'class'=>'form-control', 'placeholder'=>lang('company input enter_sitename_name'))); ?>
                </div>
            </div>
            <div class="form-group has-feedback <?php echo (form_error('sitename_email')) ? 'has-error': ''; ?>">
                <div class="input-group">
                  <?php echo form_span(lang('users input sitename_email'), 'name', array('class'=>'input-group-addon')); ?>
                  <?php echo form_input(array('name'=>'sitename_email', 'value'=>set_value('sitename_email', (isset($company['sitename_email']) ? $company['sitename_email'] : '')), 'class'=>'form-control', 'placeholder'=>lang('company input enter_sitename_email'))); ?>
                </div>
            </div>
            <div class="form-group has-feedback <?php echo (form_error('logo')) ? 'has-error': ''; ?>">
                <div class="input-group">
                  <?php echo form_span(lang('users input logo'), 'logo', array('class'=>'input-group-addon')); ?>
                  <?php echo form_upload(array('name'=>'logo', 'value'=>"")); ?>
                </div>
                <div class="input-group">
                    <p class="text-danger"><?php echo lang('company error logo');?></p>
                </div>
            </div>
            <div class="form-group has-feedback <?php echo (form_error('list_status')) ? 'has-error': ''; ?>">
                <label for="posting"></label>
                    <div class="col-md-12 posting">
                            <label class="checkbox-inline">
                                <input type="checkbox" id="list_status" name="list_status" <?php echo (isset($company['list_status']) && $company['list_status'] =="1"?'checked':'');?> value="1"> <?php echo lang('users list status');?>
                          </label>
                    </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="window.app.onCompanyAdd()">Save changes</button>
        </div>
    </div> <!-- /.modal-content -->
</div> <!-- /.modal-dialog -->
