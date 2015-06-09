<div class="row table-responsive">
    <?php echo form_open("holiday","{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
        <table class="table table-striped table-hover-warning">
            <thead>
                <tr>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=holiday_name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('company title name'); ?></a>
                        <?php if ($sort == 'holiday_name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=date&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('company title date'); ?></a>
                        <?php if ($sort == 'date') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php if ($total) : ?>
                    <?php foreach ($holiday as $value) : ?>
                        <tr>
                            <td>
                                    <?php echo $value['holiday_name']; ?>
                            </td>
                            <td>
                                    <?php echo ($value['date']!=NULL) ? mdate("%d/%m/%Y", strtotime($value['date'])):""; ?>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="<?php echo $this_url; ?>/edit/<?php echo $value['holiday_id']; ?>" data-toggle="modal" data-target="#modal-edit-<?php echo $value['holiday_id']; ?>" class="btn btn-warning" title="<?php echo lang('admin button edit'); ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a href="#modal-<?php echo $value['holiday_id']; ?>" data-toggle="modal" class="btn btn-danger" title="<?php echo lang('admin button delete'); ?>"><span class="glyphicon glyphicon-trash"></span></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">
                            <?php echo lang('core error no_results'); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>

<div class="row well well-sm">
    <div class="col-md-2 text-left">
        <label><?php echo sprintf(lang('admin label rows'), $total); ?></label>
    </div>
    <div class="col-md-2 text-left">
        <?php if ($total > 10) : ?>
            <select id="limit" class="form-control">
                <option value="10"<?php echo ($limit == 10 || ($limit != 10 && $limit != 25 && $limit != 50 && $limit != 75 && $limit != 100)) ? ' selected' : ''; ?>>10 <?php echo lang('admin input items_per_page'); ?></option>
                <option value="25"<?php echo ($limit == 25) ? ' selected' : ''; ?>>25 <?php echo lang('admin input items_per_page'); ?></option>
                <option value="50"<?php echo ($limit == 50) ? ' selected' : ''; ?>>50 <?php echo lang('admin input items_per_page'); ?></option>
                <option value="75"<?php echo ($limit == 75) ? ' selected' : ''; ?>>75 <?php echo lang('admin input items_per_page'); ?></option>
                <option value="100"<?php echo ($limit == 100) ? ' selected' : ''; ?>>100 <?php echo lang('admin input items_per_page'); ?></option>
            </select>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <?php echo $pagination; ?>
    </div>
<!--    <div class="col-md-2 text-right">
        <?php if ($total) : ?>
            <a href="<?php echo $this_url; ?>/export?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?><?php echo $filter; ?>" class="btn btn-success tooltips" data-toggle="tooltip" title="<?php echo lang('admin tooltip csv_export'); ?>"><span class="glyphicon glyphicon-export"></span> <?php echo lang('admin button csv_export'); ?></a>
        <?php endif; ?>
    </div>-->
</div>

<?php if ($total) : ?>
    <?php foreach ($holiday as $user) : ?>
        <div class="modal fade" id="modal-<?php echo $user['holiday_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-label-<?php echo $user['holiday_id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 id="modal-label-<?php echo $user['holiday_id']; ?>"><?php echo lang('customers title user_delete');  ?></h4>
                    </div>
                    <div class="modal-body">
                        <p><?php echo sprintf(lang('users msg delete_confirm'), $user['holiday_name']); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('core button cancel'); ?></button>
                        <button type="button" class="btn btn-primary btn-delete-holiday" data-id="<?php echo $user['holiday_id']; ?>"><?php echo lang('admin button delete'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php foreach ($holiday as $user) : ?>
        <div class="modal fade" id="modal-edit-<?php echo $user['holiday_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-label-<?php echo $user['holiday_id']; ?>" aria-hidden="true"></div> 
    <?php endforeach; ?>
<?php endif; ?>