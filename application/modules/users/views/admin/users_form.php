<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4><?php echo $title;?></h4>
        </div>
        <div class="modal-body">
            <?php echo form_open('users_form','', array('role'=>'form')); ?>
            <?php if (isset($user_id)) : ?>
                <?php echo form_hidden('id', $user_id); ?>
            <?php endif; ?>
            <?php echo form_hidden('is_admin', 1); ?>
            <div class="form-group has-feedback <?php echo (form_error('username')) ? 'has-error': ''; ?>">
                <div class="input-group">
                  <?php echo form_span(lang('users input username'), 'name', array('class'=>'input-group-addon')); ?>
                  <?php echo form_input(array('name'=>'username', 'value'=>set_value('username', (isset($user['username']) ? $user['username'] : '')), 'class'=>'form-control', 'placeholder'=>lang('users input enter_username'))); ?>
                </div>
            </div>
            <div class="form-group has-feedback <?php echo (form_error('password')) ? 'has-error': ''; ?>">
                <div class="input-group">
                  <?php echo form_span(lang('users input password'), 'password', array('class'=>'input-group-addon')); ?>
                  <?php echo form_password(array('name'=>'password', 'value'=>'', 'autocomplete'=>'off', 'class'=>'form-control', 'placeholder'=>lang('users input enter_password'))); ?>
                </div>
            </div>
            <div class="form-group has-feedback <?php echo (form_error('password_repeat')) ? 'has-error': ''; ?>">
                <div class="input-group">
                  <?php echo form_span(lang('users input password_repeat'), 'password', array('class'=>'input-group-addon')); ?>
                  <?php echo form_password(array('name'=>'password_repeat', 'value'=>'', 'autocomplete'=>'off', 'class'=>'form-control', 'placeholder'=>lang('users input enter_password_repeat'))); ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="window.app.onUserAdd()">Save changes</button>
        </div>
    </div> <!-- /.modal-content -->
</div> <!-- /.modal-dialog -->
