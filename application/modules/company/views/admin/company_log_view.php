<div class="row table-responsive">
    <?php echo form_open("company_log_view","{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
        <table class="table table-striped table-hover-warning">
            <thead>
                <tr>
                    <td>
                        <?php echo lang('detail'); ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php if ($total>0) : ?>
                    <?php foreach ($logs as $value) : ?>
                        <tr>
                            <td>
                                <?php echo $value; ?>
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
