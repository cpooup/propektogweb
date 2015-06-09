<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4><?php echo $title;?></h4>
        </div>
        <div class="modal-body">
                <?php echo form_open('customers_form','', array('role'=>'form')); ?>
                <?php if (isset($customer_id)) : ?>
                    <?php echo form_hidden('id', $customer_id); ?>
                    <?php echo form_hidden('site_id', $user['site_id']); ?>
                <?php endif; ?>
                <div class="form-group has-feedback <?php echo (form_error('name')) ? 'has-error': ''; ?>">
                    <div class="input-group">
                      <?php echo form_span(lang('customers input name'), 'name', array('class'=>'input-group-addon')); ?>
                      <?php echo form_input(array('name'=>'name', 'value'=>set_value('name', (isset($user['name']) ? $user['name'] : '')), 'class'=>'form-control', 'placeholder'=>lang('customers input enter_name'))); ?>
                    </div>
                </div>
                <div class="form-group has-feedback <?php echo (form_error('email')) ? 'has-error': ''; ?>">
                    <div class="input-group">
                      <?php echo form_span(lang('customers input email'), 'email', array('class'=>'input-group-addon')); ?>
                      <?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control', 'placeholder'=>lang('customers input enter_email'))); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="data_entry"><?php echo lang('customers col data_entry');?></label>
                    <div class="col-md-12 data_entry">
                            <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox1" name="data_entry_monday" <?php echo $user['data_entry_monday']>0?'checked':'';?> value="1"> <?php echo lang('data_entry_monday1');?>
                          </label>
                          <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox2" name="data_entry_tuesday" <?php echo $user['data_entry_tuesday']>0?'checked':'';?> value="1"> <?php echo lang('data_entry_tuesday1');?>
                          </label>
                          <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="data_entry_wednesday" <?php echo $user['data_entry_wednesday']>0?'checked':'';?> value="1"> <?php echo lang('data_entry_wednesday1');?>
                          </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="data_entry_thursday" <?php echo $user['data_entry_thursday']>0?'checked':'';?> value="1"> <?php echo lang('data_entry_thursday1');?>
                          </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="data_entry_friday" <?php echo $user['data_entry_friday']>0?'checked':'';?> value="1"> <?php echo lang('data_entry_friday1');?>
                          </label>
                    </div>
                    <div id="data_entry_alert" class="alert alert-danger">
                        <?php echo lang('customers msg select at least one day');?>
                    </div>
                </div>
            <div class="form-group">
                    <label for="kontering"><?php echo lang('customers col kontering');?></label>
                    <div class="col-md-12 data_kontering">
                            <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox1" name="kontering_monday" <?php echo $user['kontering_monday']>0?'checked':'';?> value="1"> <?php echo lang('kontering_monday1');?>
                          </label>
                          <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox2" name="kontering_tuesday" <?php echo $user['kontering_tuesday']>0?'checked':'';?> value="1"> <?php echo lang('kontering_tuesday1');?>
                          </label>
                          <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="kontering_wednesday" <?php echo $user['kontering_wednesday']>0?'checked':'';?> value="1"> <?php echo lang('kontering_wednesday1');?>
                          </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="kontering_thursday" <?php echo $user['kontering_thursday']>0?'checked':'';?> value="1"> <?php echo lang('kontering_thursday1');?>
                          </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="kontering_friday" <?php echo $user['kontering_friday']>0?'checked':'';?> value="1"> <?php echo lang('kontering_friday1');?>
                          </label>
                    </div>
                    <div id="kontering_alert" class="alert alert-danger">
                        <?php echo lang('customers msg select at least one day');?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="posting"><?php echo lang('customers col posting');?></label>
                    <div class="col-md-12 posting">
                            <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox1" name="posting_monday" <?php echo $user['posting_monday']>0?'checked':'';?> value="1"> <?php echo lang('posting_monday1');?>
                          </label>
                          <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox2" name="posting_tuesday" <?php echo $user['posting_tuesday']>0?'checked':'';?> value="1"> <?php echo lang('posting_tuesday1');?>
                          </label>
                          <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="posting_wednesday" <?php echo $user['posting_wednesday']>0?'checked':'';?> value="1"> <?php echo lang('posting_wednesday1');?>
                          </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="posting_thursday" <?php echo $user['posting_thursday']>0?'checked':'';?> value="1"> <?php echo lang('posting_thursday1');?>
                          </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="inlineCheckbox3" name="posting_friday" <?php echo $user['posting_friday']>0?'checked':'';?> value="1"> <?php echo lang('posting_friday1');?>
                          </label>
                    </div>
                    <div id="posting_alert" class="alert alert-danger">
                        <?php echo lang('customers msg select at least one day');?>
                    </div>
                </div>
            <?php if($list_status==1) :?>
                <div class="form-group has-feedback <?php echo (form_error('start_date3')) ? 'has-error': ''; ?>">
                    <div class="input-group task">
                        <?php echo form_span(lang('customers col Bankavstemning'), 'start_date3', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="start_date3" type="text" id="start_date3" value="<?=(isset($task_list['start_date3']) ? $task_list['start_date3'] : "")?>" class="form-control list_date"/> 
                            <?php echo form_dropdown('repeat3', array("0"=>0,"1"=>1,"2"=>2), (isset($task_list['repeat3']) ? $task_list['repeat3'] : '0'), 'id="repeat3" class="form-control list_repeat"'); ?>
                    </div>
                </div>
            <div class="form-group has-feedback <?php echo (form_error('start_date4')) ? 'has-error': ''; ?>">
                    <div class="input-group task">
                        <?php echo form_span(lang('customers col Lønnskjøring'), 'start_date4', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="start_date4" type="text" id="start_date4" value="<?=(isset($task_list['start_date4']) ? $task_list['start_date4'] : "")?>" class="form-control list_date"/> 
                            <?php echo form_dropdown('repeat4', array("0"=>0,"1"=>1,"2"=>2), (isset($task_list['repeat4']) ? $task_list['repeat4'] : '0'), 'id="repeat" class="form-control list_repeat"'); ?>
                    </div>
                </div>
            <div class="form-group has-feedback <?php echo (form_error('start_date5')) ? 'has-error': ''; ?>">
                    <div class="input-group task">
                        <?php echo form_span(lang('customers col A Meldinger'), 'start_date5', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="start_date5" type="text" id="start_date5" value="<?=(isset($task_list['start_date5']) ? $task_list['start_date5'] : "")?>" class="form-control list_date"/> 
                            <?php echo form_dropdown('repeat5', array("0"=>0,"1"=>1,"2"=>2), (isset($task_list['repeat5']) ? $task_list['repeat5'] : '0'), 'id="repeat5" class="form-control list_repeat"'); ?>
                    </div>
                </div>
            <div class="form-group has-feedback <?php echo (form_error('start_date6')) ? 'has-error': ''; ?>">
                    <div class="input-group task">
                        <?php echo form_span(lang('customers col Terminoppgaver'), 'start_date', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="start_date6" type="text" id="start_date6" value="<?=(isset($task_list['start_date6']) ? $task_list['start_date6'] : "")?>" class="form-control list_date"/> 
                            <?php echo form_dropdown('repeat6', array("0"=>0,"1"=>1,"2"=>2), (isset($task_list['repeat6']) ? $task_list['repeat6'] : '0'), 'id="repeat6" class="form-control list_repeat"'); ?>
                    </div>
                </div>
                <div class="form-group has-feedback <?php echo (form_error('start_date7')) ? 'has-error': ''; ?>">
                    <div class="input-group task">
                        <?php echo form_span(lang('customers col Moms Rapportering'), 'start_date7', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="start_date7" type="text" id="start_date7" value="<?=(isset($task_list['start_date7']) ? $task_list['start_date7'] : "")?>" class="form-control list_date"/>    
                            <?php echo form_dropdown('repeat7', array("0"=>0,"1"=>1,"2"=>2), (isset($task_list['repeat7']) ? $task_list['repeat7'] : '0'), 'id="repeat7" class="form-control list_repeat"'); ?>
                    </div>
                </div>
                <div class="form-group has-feedback <?php echo (form_error('start_date8')) ? 'has-error': ''; ?>">
                    <div class="input-group task">
                        <?php echo form_span(lang('customers col Fakturering'), 'start_date8', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="start_date8" type="text" id="start_date8" value="<?=(isset($task_list['start_date8']) ? $task_list['start_date8'] : "")?>" class="form-control list_date"/>  
                            <?php echo form_dropdown('repeat8', array("0"=>0,"1"=>1,"2"=>2), (isset($task_list['repeat8']) ? $task_list['repeat8'] : '0'), 'id="repeat8" class="form-control list_repeat"'); ?>
                    </div>
                </div>
<!--                <div class="form-group has-feedback <?php echo (form_error('start_date9')) ? 'has-error': ''; ?>">
                    <div class="input-group task">
                        <?php echo form_span(lang('customers col BLANK1'), 'start_date9', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="start_date9" type="text" id="start_date9" value="<?=(isset($task_list['start_date9']) ? $task_list['start_date9'] : "")?>" class="form-control list_date"/>  
                            <?php echo form_dropdown('repeat9', array("0"=>0,"1"=>1,"2"=>2), (isset($task_list['repeat9']) ? $task_list['repeat9'] : '0'), 'id="repeat9" class="form-control list_repeat"'); ?>
                    </div>
                </div>
                <div class="form-group has-feedback <?php echo (form_error('start_date10')) ? 'has-error': ''; ?>">
                    <div class="input-group task">
                        <?php echo form_span(lang('customers col BLANK2'), 'start_date10', array('class'=>'input-group-addon')); ?>
                            <input placeholder="<?=lang('customer list date');?>" name="start_date10" type="text" id="start_date10" value="<?=(isset($task_list['start_date10']) ? $task_list['start_date10'] : "")?>" class="form-control list_date"/> 
                            <?php echo form_dropdown('repeat10', array("0"=>0,"1"=>1,"2"=>2), (isset($task_list['repeat10']) ? $task_list['repeat10'] : '0'), 'id="repeat10" class="form-control list_repeat"'); ?>
                    </div>
                </div>-->
                <?php endif; ?>
                <div class="form-group has-feedback <?php echo (form_error('priority')) ? 'has-error': ''; ?>">
                    <div class="input-group">
                      <?php echo form_span(lang('customers input priority'), 'priority', array('class'=>'input-group-addon')); ?>
                      <?php echo form_dropdown('priority', array("1"=>lang("customers select priority yes"),"0"=>lang("customers select priority no")), (isset($user['priority']) ? $user['priority'] : '0'), 'id="priority" class="form-control"'); ?>
                    </div>
                </div>
                <div class="form-group has-feedback <?php echo (form_error('approveby')) ? 'has-error': ''; ?>">
                    <div class="input-group">
                      <?php echo form_span(lang('customers input approveby'), 'approveby', array('class'=>'input-group-addon')); ?>
                      <?php echo form_input(array('name'=>'approveby', 'value'=>set_value('approveby', (isset($user['approveby']) ? $user['approveby'] : '')), 'class'=>'form-control', 'placeholder'=>lang('customers input enter_name'))); ?>
                    </div>
                </div>
                <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                    <div class="form-group has-feedback <?php echo (form_error('comment_privat')) ? 'has-error': ''; ?>">
                        <div class="input-group">
                          <?php echo form_span(lang('customers col comment_privat'), 'comment_privat', array('class'=>'input-group-addon')); ?>
                          <?php echo form_textarea(array('name'=>'comment_privat','cols' => '30', 'rows' => '2', 'value'=>set_value('comment_privat', (isset($user['comment_privat']) ? $user['comment_privat'] : '')), 'class'=>'form-control', 'placeholder'=>lang('customers input enter_comment_privat'))); ?>
                        </div>
                    </div>
                <?php else: ?>
                        <?php echo form_hidden('comment_privat', (isset($user['comment_privat']) ? $user['comment_privat'] : '')); ?>
                <?php endif; ?>
                <div class="form-group has-feedback <?php echo (form_error('comment')) ? 'has-error': ''; ?>">
                        <div class="input-group">
                          <?php echo form_span(lang('customers col comment'), 'comment', array('class'=>'input-group-addon')); ?>
                          <?php echo form_textarea(array('name'=>'comment','cols' => '30', 'rows' => '2', 'value'=>set_value('comment', (isset($user['comment']) ? $user['comment'] : '')), 'class'=>'form-control', 'placeholder'=>lang('customers input enter_comment'))); ?>
                        </div>
                </div>
                <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                    <?php echo form_hidden('on_hold', (isset($user['on_hold']) ? $user['on_hold'] : '')); ?>
                <?php else: ?>
                <div class="form-group">
                    <label for="posting"></label>
                    <div class="col-md-12 posting">
                            <label class="checkbox-inline">
                            <input type="checkbox" id="on_hold" name="on_hold" <?php echo $user['on_hold']>0?'checked':'';?> value="1"> <?php echo lang('on hold');?>
                          </label>
                    </div>
                    <div id="posting_alert" class="alert alert-danger">
                        <?php echo lang('customers msg select at least one day');?>
                    </div>
                </div>
                <?php endif; ?>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="window.app.onCustomerAdd()">Save changes</button>
        </div>
    </div> <!-- /.modal-content -->
</div> <!-- /.modal-dialog -->
<script>
        $("#start_date3,#start_date4,#start_date5,#start_date6,#start_date7,#start_date8,#start_date9,#start_date10").datepicker({  
                    defaultDate: new Date(),
                    dateFormat: 'dd/mm/yy',
                    autoclose: true
        }); 
</script>