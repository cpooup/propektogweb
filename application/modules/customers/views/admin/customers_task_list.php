<div class="row table-responsive task">
        <table class="table task_date">
            <tbody> 
                <tr>
                    <td class="btn-primary"><a href="<?=$back_week;?>"><i class="glyphicon glyphicon-chevron-left"></i>Prev</a></td>
                    <?php $i=0; ?>
                    <?php foreach ($tab_date as $tab) : ?>
                       <?php if(isset($tab['Date'])) : ?>
                            <td class="<?php echo $tab['DateActive']; ?>">
                                <a href="<?php echo $tab['DateAction']; ?>">
                                    <?php echo $tab['DayOfWeek']; ?>
                                    <br />
                                    <?php echo $tab['Date']; ?>
                                </a>
                            </td>
                        <?php if($i==4) : ?>
                            <td class="btn-primary">&nbsp;</td>
                        <?php endif; $i++; ?>
                      <?php endif; ?>
                    <?php endforeach; ?>
                    <td class="btn-primary"><a href="<?=$nex_week;?>">Next<i class="glyphicon glyphicon-chevron-right"></i></a></td>       
                 </tr>
            </tbody>
        </table>
</div>
<input type="hidden" id="site_id" name="site_id" value="<?php echo $site_id; ?>" />
<input type="hidden" id="csrf-token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<div class="row table-responsive task task_list">
    <table class="table task_date">
        <thead>
            <tr class="active">
                <td><?=lang('customers col name');?></td>
                <td><?=lang('customers col email');?></td>
                <td><?=lang('customers col data_entry');?><br />O&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</td>
                <td><?=lang('customers col posting');?><br />O&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</td>
                <td><?=lang('customers col Bankavstemning');?></td>
                <td><?=lang('customers col Lønnskjøring');?></td>
                <td><?=lang('customers col A Meldinger');?></td>
                <td><?=lang('customers col Terminoppgaver');?></td>
                <td><?=lang('customers col Moms Rapportering');?></td>
                <td><?=lang('customers col Fakturering');?></td>
<!--                <td><?=lang('customers col BLANK1');?></td>
                <td><?=lang('customers col BLANK2');?></td>-->
                <td><?=lang('customer list date');?></td>
            </tr>
        </thead>
            <tbody> 
                <?php 
                $i=0; 
                       $normal_zone = Array();
                        $green_zone = Array();
                        $red_zone = Array();
                if(count($data_old)>0) :
                    foreach (array_filter($data_old) as $k => $value) :
                        foreach (array_filter($value) as $k => $item) : 
                            $text = '';
                            $checkbox3 = '';
                            $checkbox4 = '';
                            $checkbox5 = '';
                            $checkbox6 = '';
                            $checkbox7 = '';
                            $checkbox8 = '';
                            $checkbox9 = '';
                            $checkbox10 = '';
                            $data_entry = '';
                            $data_posting = '';
                            $check_count = 0;
                            $check_active = 0;
                            foreach ($item as $value) : 
                                $customer_name = $value["customer_name"];
                                $customer_email = $value["customer_email"];
                                $task_checked_date = $value["task_checked_date"];
                                if($value["task_id"]==3) : 
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $checkbox3 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Bankavstemning').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif; 
                                if($value["task_id"]==4) : 
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $checkbox4 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Lønnskjøring').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif;
                                if($value["task_id"]==5) : 
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $checkbox5 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col A Meldinger').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif;
                                if($value["task_id"]==6) :
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $checkbox6 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Terminoppgaver').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif;
                                if($value["task_id"]==7) : 
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $checkbox7 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Moms Rapportering').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif;
                                if($value["task_id"]==8) : 
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $checkbox8 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Fakturering').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif;
                                if($value["task_id"]==9) : 
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $checkbox9 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col BLANK1').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif;
                                if($value["task_id"]==10) :
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $checkbox10 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col BLANK2').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif;
                                if($value["task_id"]==11 || $value["task_id"]==12 || $value["task_id"]==13 || $value["task_id"]==14 || $value["task_id"]==15) : 
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $data_entry = '<input class="checked-id-'.$value["task_checked_id"].'" data-checked_specail="1" data-checked_type="0" data-task_name="'.lang('customers col data_entry').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1&&$value["task_checked_type"]==0?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                    $data_entry .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="checked-id-'.$value["task_checked_id"].'" data-checked_specail="1" data-checked_type="1" data-task_name="'.lang('customers col data_entry').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1&&$value["task_checked_type"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif; 
                                if($value["task_id"]==16 || $value["task_id"]==17 || $value["task_id"]==18 || $value["task_id"]==19 || $value["task_id"]==20) : 
                                    $check_active += 1;
                                    $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                    $data_posting = '<input class="checked-id-'.$value["task_checked_id"].'" data-checked_specail="1" data-task_name="'.lang('customers col posting').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1&&$value["task_checked_type"]==0?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                    $data_posting .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="checked-id-'.$value["task_checked_id"].'" data-checked_specail="1" data-checked_type="1" data-task_name="'.lang('customers col posting').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1&&$value["task_checked_type"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                endif; 
                            endforeach;

                        $text .= "<td>".$customer_name."</td>";
                        $text .= "<td>".$customer_email."</td>";
                        $text .= "<td>".$data_entry."</td>";
                        $text .= "<td>".$data_posting."</td>";
                        $text .= "<td>".(isset($checkbox3)?$checkbox3:'')."</td>";
                        $text .= "<td>".(isset($checkbox4)?$checkbox4:'')."</td>";
                        $text .= "<td>".(isset($checkbox5)?$checkbox5:'')."</td>";
                        $text .= "<td>".(isset($checkbox6)?$checkbox6:'')."</td>";
                        $text .= "<td>".(isset($checkbox7)?$checkbox7:'')."</td>";
                        $text .= "<td>".(isset($checkbox8)?$checkbox8:'')."</td>";
                        //$text .= "<td>".isset($checkbox9)?$checkbox9:""."</td>";
                        //$text .= "<td>".isset($checkbox10)?$checkbox10:""."</td>";
                        if($check_count<$check_active){
                            $text .= "<td>".$task_checked_date."</td>";
                            $tr = "<tr data-datenow='".mdate("%Y-%m-%d",time())."' data-date='".$task_checked_date."' data-color='danger' data-zone='red' class='danger red_zone old'>".$text."</tr>";
                            array_push($red_zone,$tr); 
                        }
                        endforeach;
                    endforeach;
                endif;        
                if(count($data)>0) :  
                    foreach ($data as $k => $item) : 
                        $text = '';
                        $checkbox3 = '';
                        $checkbox4 = '';
                        $checkbox5 = '';
                        $checkbox6 = '';
                        $checkbox7 = '';
                        $checkbox8 = '';
                        $checkbox9 = '';
                        $checkbox10 = '';
                        $data_entry = '';
                        $data_posting = '';
                        $check_count = 0;
                        $check_active = 0;
                        foreach ($item as $value) : 
                            $customer_name = $value["customer_name"];
                            $customer_email = $value["customer_email"];
                            $task_checked_date = $value["task_checked_date"];
                            if($value["task_id"]==3) : 
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $checkbox3 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Bankavstemning').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif; 
                            if($value["task_id"]==4) : 
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $checkbox4 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Lønnskjøring').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif;
                            if($value["task_id"]==5) : 
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $checkbox5 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col A Meldinger').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif;
                            if($value["task_id"]==6) :
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $checkbox6 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Terminoppgaver').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif;
                            if($value["task_id"]==7) : 
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $checkbox7 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Moms Rapportering').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif;
                            if($value["task_id"]==8) : 
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $checkbox8 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col Fakturering').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif;
                            if($value["task_id"]==9) : 
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $checkbox9 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col BLANK1').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif;
                            if($value["task_id"]==10) :
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $checkbox10 = '<input class="checked-id-'.$value["task_checked_id"].'" data-task_name="'.lang('customers col BLANK2').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif;
                            if($value["task_id"]==11 || $value["task_id"]==12 || $value["task_id"]==13 || $value["task_id"]==14 || $value["task_id"]==15) : 
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $data_entry = '<input class="checked-id-'.$value["task_checked_id"].'" data-checked_specail="1" data-checked_type="0" data-task_name="'.lang('customers col data_entry').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1&&$value["task_checked_type"]==0?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                $data_entry .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="checked-id-'.$value["task_checked_id"].'" data-checked_specail="1" data-checked_type="1" data-task_name="'.lang('customers col data_entry').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1&&$value["task_checked_type"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif; 
                            if($value["task_id"]==16 || $value["task_id"]==17 || $value["task_id"]==18 || $value["task_id"]==19 || $value["task_id"]==20) : 
                                $check_active += 1;
                                $check_count = ($value["task_checked_status"]==1?$check_count+1:$check_count);
                                $data_posting = '<input class="checked-id-'.$value["task_checked_id"].'" data-checked_specail="1" data-checked_type="0" data-task_name="'.lang('customers col posting').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1&&$value["task_checked_type"]==0?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                                $data_posting .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="checked-id-'.$value["task_checked_id"].'" data-checked_specail="1" data-checked_type="1" data-task_name="'.lang('customers col posting').'" data-customer_id="'.$value["customer_id"].'" type="checkbox" '.($value["task_checked_status"]==1&&$value["task_checked_type"]==1?"checked":"").' value="'.$value["task_checked_id"].'" name="task_checked_id" />';
                            endif; 
                        endforeach;

                    $text .= "<td>".$customer_name."</td>";
                    $text .= "<td>".$customer_email."</td>";
                    $text .= "<td>".$data_entry."</td>";
                    $text .= "<td>".$data_posting."</td>";
                    $text .= "<td>".(isset($checkbox3)?$checkbox3:'')."</td>";
                    $text .= "<td>".(isset($checkbox4)?$checkbox4:'')."</td>";
                    $text .= "<td>".(isset($checkbox5)?$checkbox5:'')."</td>";
                    $text .= "<td>".(isset($checkbox6)?$checkbox6:'')."</td>";
                    $text .= "<td>".(isset($checkbox7)?$checkbox7:'')."</td>";
                    $text .= "<td>".(isset($checkbox8)?$checkbox8:'')."</td>";
                    //$text .= "<td>".isset($checkbox9)?$checkbox9:""."</td>";
                    //$text .= "<td>".isset($checkbox10)?$checkbox10:""."</td>";
                    if($check_count==$check_active){
                        $text .= "<td></td>";
                        $tr = "<tr data-datenow='".mdate("%Y-%m-%d",time())."' data-date='".$task_checked_date."' data-color='success' data-zone='green' class='success green_zone now'>".$text."</tr>";
                        array_push($green_zone,$tr);
                    }else{
                        if($task_checked_date >= mdate("%Y-%m-%d",time())){
                            $text .= "<td></td>";
                            $tr = "<tr data-datenow='".mdate("%Y-%m-%d",time())."' data-date='".$task_checked_date."' data-color='' data-zone='normal' class='normal_zone now'>".$text."</tr>";
                            array_push($normal_zone,$tr);
                        }else{
                           $text .= "<td>".$task_checked_date."</td>";
                           $tr = "<tr data-datenow='".mdate("%Y-%m-%d",time())."' data-date='".$task_checked_date."' data-color='danger' data-zone='red' class='danger red_zone now'>".$text."</tr>";
                            array_push($red_zone,$tr); 
                        }
                    }
                    endforeach;
                endif;
                    if(!empty($red_zone)):
                        foreach($red_zone as $content):
                            echo $content;
                        endforeach;
                    endif;
                    echo '<tr class="red_zone_group"></tr>';
                    if(!empty($normal_zone)):
                        foreach($normal_zone as $content):
                            echo $content;
                        endforeach;
                    endif;
                    echo '<tr class="normal_zone_group"></tr>';
                    if(!empty($green_zone)):
                        foreach($green_zone as $content):
                            echo $content;
                        endforeach;
                    endif;
                    echo '<tr class="green_zone_group"></tr>';
                ?>
            </tbody>
    </table>
</div>
