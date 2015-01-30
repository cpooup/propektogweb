<?php echo form_open('', array('role'=>'form')); ?>

    <div class="row">
        <div class="form-group col-sm-6 <?php echo (form_error('email')) ? 'has-error': ''; ?>">
            <?php echo form_label(lang('users input email'), 'email', array('class'=>'control-label')); ?>
            <span class="required">*</span>
            <?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control')); ?>
        </div>
    </div>

    <div class="row pull-right">
        <a class="btn btn-default" href="<?php echo $cancel_url; ?>"><?php echo lang('core button cancel'); ?></a>
        <button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> <?php echo lang('users button reset_password'); ?></button>
    </div>

</form>
