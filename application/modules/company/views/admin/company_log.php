<div class="row table-responsive">
    <?php echo form_open("company_log","{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
        <table class="table table-striped table-hover-warning">
            <thead>
                <tr>
                    <td>
                        <?php echo lang('log'); ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php if ($total>0) : ?>
                    <?php foreach ($logs as $value) : ?>
                        <tr>
                            <td>
                                <a href="<?php echo $this_url.'/log_view/'.$sitename.'_'.str_replace('.php','/',$value); ?>"><?php echo str_replace('.php','',$value); ?></a>
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
    
</div>
