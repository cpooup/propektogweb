<div class="row table-responsive">
    <?php echo form_open("task_log","{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
        <table class="table table-striped table-hover-warning">
            <thead>
                <tr>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=task_log_date&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col datetime'); ?></a>
                        <?php if ($sort == 'task_log_date') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=customer_name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col name'); ?></a>
                        <?php if ($sort == 'customer_name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=task_log_name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col task'); ?></a>
                        <?php if ($sort == 'task_log_name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=task_log_status&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col status'); ?></a>
                        <?php if ($sort == 'task_log_status') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=task_date&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col date'); ?></a>
                        <?php if ($sort == 'task_date') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=username&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col user'); ?></a>
                        <?php if ($sort == 'username') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php if ($total) : ?>
                    <?php foreach ($task_log as $value) : ?>
                        <tr>
                            <td>
                                    <?php echo ($value['task_log_date']!=NULL) ? mdate("%d/%m/%Y %h:%i", strtotime($value['task_log_date'])):""; ?>
                            </td>
                            <td>
                                    <?php echo $value['customer_name']; ?>
                            </td>
                            <td>
                                    <?php echo $value['task_log_name']; ?>
                            </td>
                            <td>
                                <?php
                                    if($value['task_log_name']=="Prekontering" ||$value['task_log_name']=="Bokføring" ||$value['task_log_name']=="Kontering"){
                                        $task_log_type = " (".(($value['task_log_type']==1)?"X":"O").")";
                                    }else{
                                        $task_log_type = "";
                                    }
                                ?>
                                    <?php echo (($value['task_log_status']==1)?"Checked":"Unchecked").$task_log_type; ?>
                            </td>
                            <td>
                                    <?php echo ($value['task_date']!=NULL) ? mdate("%d/%m/%Y", strtotime($value['task_date'])):""; ?>
                            </td>
                            <td>
                                    <?php echo $value['username']; ?>
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