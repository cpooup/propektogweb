<div class="row table-responsive">
    <?php echo form_open("customers_list","{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
        <table class="table table-striped table-hover-warning">
            <thead>
                <tr>
                    <?php if($column['id']==1) : ?>
                        <td>
                            <a href="<?php echo current_url(); ?>?sort=id&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col id'); ?></a>
                            <?php if ($sort == 'id') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if($column['sitename']==1) : ?>
                        <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                        <td>
                            <a href="<?php echo current_url(); ?>?sort=sitename&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col sitename'); ?></a>
                            <?php if ($sort == 'sitename') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                        </td>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($column['name']==1) : ?>
                        <td>
                            <a href="<?php echo current_url(); ?>?sort=name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col name'); ?></a>
                            <?php if ($sort == 'name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if($column['email']==1) : ?>
                        <td>
                            <a href="<?php echo current_url(); ?>?sort=email&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col email'); ?></a>
                            <?php if ($sort == 'email') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if($column['data_entry']==1) : ?>
                        <td>
                            <?php echo lang('customers col data_entry'); ?>
                        </td>
                    <?php endif; ?>
                    <?php if($column['posting']==1) : ?>
                        <td>
                            <?php echo lang('customers col posting'); ?>
                        </td>
                    <?php endif; ?>                   
                    <?php if($column['priority']==1) : ?>    
                        <td>
                            <a href="<?php echo current_url(); ?>?sort=priority&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col priority'); ?></a>
                            <?php if ($sort == 'priority') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if($column['comment']==1) : ?> 
                        <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                            <td>
                                <a href="<?php echo current_url(); ?>?sort=comment&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col comment'); ?></a>
                                <?php if ($sort == 'comment') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                            </td>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($column['approveby']==1) : ?>    
                        <td>
                            <a href="<?php echo current_url(); ?>?sort=approveby&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col approveby'); ?></a>
                            <?php if ($sort == 'approveby') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if($column['updated']==1) : ?> 
                        <td>
                            <a href="<?php echo current_url(); ?>?sort=updated&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col updated'); ?></a>
                            <?php if ($sort == 'updated') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if($column['updateby']==1) : ?> 
                        <td>
                            <a href="<?php echo current_url(); ?>?sort=updateby&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col updateby'); ?></a>
                            <?php if ($sort == 'updateby') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if($column['deleted']==1) : ?> 
                        <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                            <td>
                                <a href="<?php echo current_url(); ?>?sort=deleted&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers input status'); ?></a>
                                <?php if ($sort == 'deleted') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                            </td>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($total) : ?>
                    <?php foreach ($customers as $user) : ?>
                        <tr>
                            <?php if($column['id']==1) : ?>
                                <td<?php echo (($sort == 'id') ? ' class="sorted"' : ''); ?>>
                                    <?php echo $user['id']; ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['sitename']==1) : ?>
                                <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                                    <td<?php echo (($sort == 'sitename') ? ' class="sorted"' : ''); ?>>
                                        <?php echo $user['sitename']; ?>
                                    </td>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if($column['name']==1) : ?>
                                <td<?php echo (($sort == 'name') ? ' class="sorted"' : ''); ?>>
                                    <?php echo $user['name']; ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['email']==1) : ?>
                                <td<?php echo (($sort == 'email') ? ' class="sorted"' : ''); ?>>
                                    <?php echo $user['email']; ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['data_entry']==1) : ?>
                                <td>
                                    <?php 
                                        echo lang($tab.'data_entry_monday'.$user['data_entry_monday']);
                                        echo lang($tab.'data_entry_tuesday'.$user['data_entry_tuesday']); 
                                        echo lang($tab.'data_entry_wednesday'.$user['data_entry_wednesday']); 
                                        echo lang($tab.'data_entry_thursday'.$user['data_entry_thursday']);
                                        echo lang($tab.'data_entry_friday'.$user['data_entry_friday']);
                                    ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['posting']==1) : ?>
                                <td>
                                    <?php 
                                        echo lang($tab.'posting_monday'.$user['posting_monday']);
                                        echo lang($tab.'posting_tuesday'.$user['posting_tuesday']);
                                        echo lang($tab.'posting_wednesday'.$user['posting_wednesday']);
                                        echo lang($tab.'posting_thursday'.$user['posting_thursday']);
                                        echo lang($tab.'posting_friday'.$user['posting_friday']); 
                                    ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['choice']==1) : ?>
                                <td<?php echo (($sort == 'choice') ? ' class="sorted"' : ''); ?>>
                                    <?php echo lang('customers select alternativ'.$user['choice']); ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['priority']==1) : ?> 
                                <td<?php echo (($sort == 'priority') ? ' class="sorted"' : ''); ?>>
                                    <?php echo lang('customers select priority'.$user['priority']); ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['comment']==1) : ?> 
                                    <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                                        <td<?php echo (($sort == 'comment') ? ' class="sorted"' : ''); ?>>
                                            <?php echo $user['comment']; ?>
                                        </td>
                                    <?php endif; ?>
                            <?php endif; ?>
                            <?php if($column['approveby']==1) : ?>            
                                <td<?php echo (($sort == 'approveby') ? ' class="sorted"' : ''); ?>>
                                    <?php echo $user['approveby']; ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['updated']==1) : ?>
                                <td<?php echo (($sort == 'updated') ? ' class="sorted"' : ''); ?>>
                                    <?php echo $user['updated']; ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['updateby']==1) : ?> 
                                <td<?php echo (($sort == 'updateby') ? ' class="sorted"' : ''); ?>>
                                    <?php echo $user['username']; ?>
                                </td>
                            <?php endif; ?>
                            <?php if($column['deleted']==1) : ?> 
                                <?php if($this->config->item('master_sitename')==$this->config->item('sitename')) : ?>
                                    <td<?php echo (($sort == 'deleted') ? ' class="sorted"' : ''); ?>>
                                        <?php echo (!$user['deleted']) ? '<span class="active">' . lang('admin input active') . '</span>' : '<span class="inactive">' . lang('admin input inactive') . '</span>'; ?>
                                    </td>
                                <?php endif; ?>
                            <?php endif; ?>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="<?php echo $this_url; ?>/edit/<?php echo $user['id']; ?>" data-toggle="modal" data-target="#modal-edit-<?php echo $user['id']; ?>" class="btn btn-warning" title="<?php echo lang('admin button edit'); ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a href="#modal-<?php echo $user['id']; ?>" data-toggle="modal" class="btn btn-danger" title="<?php echo lang('admin button delete'); ?>"><span class="glyphicon glyphicon-trash"></span></a>
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
    <div class="col-md-2 text-right">
        <?php if ($total) : ?>
            <a href="<?php echo $this_url; ?>/export?tab=<?php echo $tab; ?>&sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?><?php echo $filter; ?>" class="btn btn-primary tooltips" data-toggle="tooltip" title="<?php echo lang('admin tooltip csv_export'); ?>"><span class="glyphicon glyphicon-export"></span> <?php echo lang('admin button csv_export'); ?></a>
        <?php endif; ?>
    </div>
</div>

<?php if ($total) : ?>
    <?php foreach ($customers as $user) : ?>
        <div class="modal fade" id="modal-<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-label-<?php echo $user['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 id="modal-label-<?php echo $user['id']; ?>"><?php echo lang('customers title user_delete');  ?></h4>
                    </div>
                    <div class="modal-body">
                        <p><?php echo sprintf(lang('customers msg delete_confirm'), $user['name']); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('core button cancel'); ?></button>
                        <button type="button" class="btn btn-primary btn-delete-user" data-id="<?php echo $user['id']; ?>"><?php echo lang('admin button delete'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php foreach ($customers as $user) : ?>
        <div class="modal fade" id="modal-edit-<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-label-<?php echo $user['id']; ?>" aria-hidden="true"></div> 
    <?php endforeach; ?>
<?php endif; ?>
