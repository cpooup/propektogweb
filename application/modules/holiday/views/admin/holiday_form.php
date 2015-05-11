<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4><?php echo $title;?></h4>
        </div>
        <div class="modal-body">
            <?php echo form_open_multipart('holiday_form','', array('role'=>'form')); ?>
            <?php echo form_hidden('is_admin', 1); ?>
            <?php if (isset($holiday_id)) : ?>
                <?php echo form_hidden('id', $holiday_id); ?>
            <?php endif; ?>
            <div class="form-group has-feedback <?php echo (form_error('holiday_name')) ? 'has-error': ''; ?>">
                <div class="input-group">
                  <?php echo form_span(lang('users input sitename_name'), 'holiday_name', array('class'=>'input-group-addon')); ?>
                  <?php echo form_input(array('name'=>'holiday_name', 'value'=>set_value('holiday_name', (isset($holiday['holiday_name']) ? $holiday['holiday_name'] : '')), 'class'=>'form-control', 'placeholder'=>lang('company input enter_sitename_name'))); ?>
                </div>
            </div>
            <div class="form-group has-feedback <?php echo (form_error('date')) ? 'has-error': ''; ?>">
                    <div class="input-group">
                        <?php echo form_span(lang('company title date'), 'date', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="date" type="text" class="date" value="<?php echo ($holiday['date']!=NULL) ? mdate("%m/%d/%Y", strtotime($holiday['date'])):""; ?>" class="form-control"/> 
                    </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="window.app.onHolidayAdd()">Save changes</button>
        </div>
    </div> <!-- /.modal-content -->
</div> <!-- /.modal-dialog -->
<script>
        $(".date").datepicker({  
                    defaultDate: new Date(),
                    dateFormat: 'mm/dd/yyy',
                    autoclose: true
        }); 
</script>