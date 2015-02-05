<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4><?php echo $title;?></h4>
        </div>
        <div class="modal-body">
                <?php echo form_open('customers_column_form','', array('role'=>'form')); ?>
                <div class="form-group has-feedback <?php echo (form_error('name')) ? 'has-error': ''; ?>">
                    <label class="col-sm-4 control-label"><?php echo lang('customers col name');?></label>
                    <div class="col-sm-8">
                      <label class="radio-inline"> <?php echo form_radio(array('name'=>'name', 'value'=>'0', 'checked'=>true)); ?> <?php echo lang('enable');?> </label>
                      <label class="radio-inline"> <?php echo form_radio(array('name'=>'name', 'value'=>'1', 'checked'=>'')); ?> <?php echo lang('disable');?> </label>
                    </div>
                </div>
                <div class="form-group has-feedback <?php echo (form_error('email')) ? 'has-error': ''; ?>">
                    <label class="col-sm-4 control-label"><?php echo lang('customers col email');?></label>
                    <div class="col-sm-8">
                      <label class="radio-inline"> <?php echo form_radio(array('name'=>'email', 'value'=>'0', 'checked'=>true)); ?> <?php echo lang('enable');?> </label>
                      <label class="radio-inline"> <?php echo form_radio(array('name'=>'email', 'value'=>'1', 'checked'=>'')); ?> <?php echo lang('disable');?> </label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="window.app.onCustomerColumnEdit()">Save changes</button>
        </div>
    </div> <!-- /.modal-content -->
</div> <!-- /.modal-dialog -->