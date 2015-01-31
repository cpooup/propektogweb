<?php echo form_open('', array('class'=>'form-horizontal')); ?>
<h4><?php echo lang('users title account_login');?></h4>
<hr>
<div class="form-group">
    <label class="col-md-2 control-label" for="username"><?php echo lang('users col username');?></label>
    <div class="col-md-10">
        <?php echo form_input(array('name'=>'username', 'id'=>'username', 'class'=>'form-control', 'placeholder'=>'')); ?>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label" for="password"><?php echo lang('users input password');?></label>
    <div class="col-md-10">
        <?php echo form_password(array('name'=>'password', 'id'=>'password', 'class'=>'form-control', 'placeholder'=>'', 'autocomplete'=>'off')); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-2 col-md-10">
        <div class="checkbox">
            <?php echo form_checkbox(array('name'=>'remember', 'id'=>'remember', 'class'=>'', 'placeholder'=>'', 'autocomplete'=>'')); ?>
            <label for="remember"><?php echo lang('users input remember');?></label>
        </div>
    </div>
</div>   
<div class="form-group">
    <div class="col-md-offset-2 col-md-10">
        <?php echo form_submit(array('name'=>'submit', 'class'=>'btn btn-default'), lang('core button login')); ?>
    </div>
</div>
<?php echo form_close(); ?>
