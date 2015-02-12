<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4><?php echo $title;?></h4>
        </div>
        <div class="modal-body">
                <?php echo form_open('customers_column_form','', array('role'=>'form')); ?>
                <?php if ($total) : ?>
                    <?php foreach ($columns as $column) : ?>
                        <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                              <?php if ($column['name']!='choice')   : ?>
                                <div class="form-group has-feedback <?php echo (form_error($column['name'])) ? 'has-error': ''; ?>">
                                    <label class="col-sm-4 control-label"><?php echo lang('customers col '.$column['name']);?></label>
                                    <div class="col-sm-8">
                                      <label class="radio-inline"> <?php echo form_radio(array('name'=>$column['name'], 'value'=>'1', 'checked'=>($column['status'] == 1 ? true : false))); ?> <?php echo lang('enable');?> </label>
                                      <label class="radio-inline"> <?php echo form_radio(array('name'=>$column['name'], 'value'=>'0', 'checked'=>($column['status'] == 0 ? true : false))); ?> <?php echo lang('disable');?> </label>
                                    </div>
                                </div>
                                 <?php endif; ?> 
                        <?php else : ?>
                                <?php if ($column['name']!='comment' && $column['name']!='deleted' && $column['name']!='sitename' && $column['name']!='choice')   : ?>
                                    <div class="form-group has-feedback <?php echo (form_error($column['name'])) ? 'has-error': ''; ?>">
                                        <label class="col-sm-4 control-label"><?php echo lang('customers col '.$column['name']);?></label>
                                        <div class="col-sm-8">
                                          <label class="radio-inline"> <?php echo form_radio(array('name'=>$column['name'], 'value'=>'1', 'checked'=>($column['status'] == 1 ? true : false))); ?> <?php echo lang('enable');?> </label>
                                          <label class="radio-inline"> <?php echo form_radio(array('name'=>$column['name'], 'value'=>'0', 'checked'=>($column['status'] == 0 ? true : false))); ?> <?php echo lang('disable');?> </label>
                                        </div>
                                    </div>
                                <?php endif; ?>        
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2">
                            <?php echo lang('core error no_results'); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="window.app.onCustomerColumnEdit()">Save changes</button>
        </div>
    </div> <!-- /.modal-content -->
</div> <!-- /.modal-dialog -->