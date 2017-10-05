<?php
    if(isset($msg) && !empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    else {
        $title = "Office List";
        $isAdmin = !empty($user_group_id) && in_array(1,$user_group_id);
        $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true,
                'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)),
                'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
        $this->Paginator->options($pageLoading);
?>
<fieldset>
    <legend><?php echo $title; ?></legend>
        <div>
            <?php
            if (!empty($values)) {
                echo "<p style='color:#072fa3;font-weight:bold;'>";
                echo 'Total records found : '.$total;
                echo '</p>';                
            }
            ?>       
            <div class="form"> 
                <table>                    
                    <tr>
                        <td>
                            <div style="width:780px; height:auto; overflow-x:auto;">                                
                                <table class="view">
                                    <tr>
                                        <?php 
                                            echo "<th style='width:30px;'>SL</th>";                                                                                       
                                            echo "<th style='width:60px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                                            echo "<th style='width:350px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                            echo "<th style='width:70px;'>" . $this->Paginator->sort('0.total_branch', 'Total Submitted') . "</th>";                                            
                                        ?>                                        
                                    </tr>
                                    <?php
                                    $current_page_number = (int)$this->Paginator->counter(array('format' => ('{:page}')));
                                    $i=$current_page_number; 
                                    if($current_page_number>1) {
                                       $i=$current_page_number+$page_limit-1;
                                    }
                                    foreach($values as $value){ ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $i; ?></td>                                        
                                        <td style="text-align: center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                                        <td style="text-align: justify;"><?php echo $value['BasicModuleBasicInformation']['full_name_of_org']; ?></td> 
                                        <td style="text-align: center;"><?php echo $value[0]['total_branch']; ?></td>
                                    </tr>
                                  <?php $i++; } ?>
                                </table>                                
                            </div>
                        </td>                
                    </tr>
                </table>
            </div>        
            <?php if($values && $this->Paginator->param('pageCount')>1) { ?>
            <div class="paginator">
              <?php 
                echo $this->Paginator->prev('<<', array('class'=>'prevPg'), null, array('class'=>'prevPg no_link')).
                        $this->Paginator->numbers(array('class'=>'numbers', 'separator'=>'')).
                        $this->Paginator->next('>>', array('class'=>'nextPg'), null, array('class'=>'nextPg no_link'));
              ?>
            </div>
            <?php } ?>        
            <div class="btns-div"></div>    
    </div> 
</fieldset>
<?php } ?>
