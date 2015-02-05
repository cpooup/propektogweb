<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4><?php echo $title;?></h4>
        </div>
        <div class="modal-body">
            <?php echo form_open('company_form','', array('role'=>'form')); ?>
            <?php echo form_hidden('is_admin', 1); ?>
            <div class="form-group has-feedback <?php echo (form_error('username')) ? 'has-error': ''; ?>">
                <div class="input-group">
                  <?php echo form_span(lang('users input sitename'), 'name', array('class'=>'input-group-addon')); ?>
                  <?php echo form_input(array('name'=>'sitename', 'value'=>set_value('sitename', ''), 'class'=>'form-control', 'placeholder'=>lang('company input enter_sitename'))); ?>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="window.app.onCompanyAdd()">Save changes</button>
        </div>
    </div> <!-- /.modal-content -->
</div> <!-- /.modal-dialog -->
