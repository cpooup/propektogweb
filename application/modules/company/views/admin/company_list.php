<div class="row table-responsive">
    <?php echo form_open("company","{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
        <table class="table table-striped table-hover-warning">
            <thead>
                <tr>
                    <td>
                        <a href="<?php echo current_url(); ?>?sort=sitename&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('users col sitename'); ?></a>
                        <?php if ($sort == 'sitename') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                    </td>
                    <td>
                        <?php echo lang('log'); ?>
                    </td>
                    <td>
                        <?php echo lang('users title Logo'); ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php if ($total) : ?>
                    <?php foreach ($company as $value) : ?>
                        <tr>
                            <td>
                                    <?php echo $value['sitename']; ?>
                            </td>
                            <td>
                                <a href="<?php echo $this_url.'/log/'.$value['sitename']; ?>"><?php echo lang('view'); ?></a>
                            </td>
                            <td>
                                    <?php 
                                    if($this->config->item('master_sitename')==$value['sitename']){
                                        $img = './images/logo/logo-master';
                                    }else{
                                        $img = './images/logo/logo-'.$value['sitename'];
                                    }
                                    
                                    $logo = '';
                                    if(file_exists($img.'.gif')){
                                        $logo =  base_url().$img.'.gif'; 
                                    }else if(file_exists($img.'.png')){
                                        $logo =  base_url().$img.'.png'; 
                                    }else if(file_exists($img.'.jpg')){
                                        $logo =  base_url().$img.'.jpg'; 
                                    }else if(file_exists($img.'.jpeg')){
                                        $logo =  base_url().$img.'.jpeg'; 
                                    }
                                    if(!empty($logo)){
                                        echo '<img src="'.$logo.'" alt="logo" />';
                                    }
                                    ?>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="<?php echo $this_url; ?>/edit/<?php echo $value['id']; ?>" data-toggle="modal" data-target="#modal-edit-<?php echo $value['id']; ?>" class="btn btn-warning" title="<?php echo lang('admin button edit'); ?>"><span class="glyphicon glyphicon-pencil"></span></a>
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
    <?php foreach ($company as $value) : ?>
        <div class="modal fade" id="modal-edit-<?php echo $value['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-label-<?php echo $value['id']; ?>" aria-hidden="true"></div> 
    <?php endforeach; ?>
<?php endif; ?>