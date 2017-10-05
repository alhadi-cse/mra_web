<?php
    $title = 'Final Approval';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleAdministrativeApprovalAll'); ?>
        
        <table>
            <tr>
                <td>
                    <div style="width:720px; height:auto; padding:0; overflow-x:auto;">
                        <?php 
                            if($orgDetails==null || !is_array($orgDetails) || count($orgDetails)<1) {
                                echo '<p class="error-message">';
                                echo 'No data is available!';
                                echo '</P>';

                                echo $this->Js->link('Back', array('controller' => 'LicenseModuleAdministrativeApprovals','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));
                            }
                            else {
                        ?>

                        <table class="view">
                            <tr>
                                <?php 
                                if(!$this->Paginator->param('options'))
                                    echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                                else 
                                    echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                    echo "<th style='width:120px;'>Approve All <input type='checkbox' id='chkbApprovalAll'/> </th>";
                                    echo "<th style='width:130px;'>Reason <br /><span style='padding:0; color:#fa8713;'>(if not approved)</span></th>";
                                    echo "<th style='width:130px;'>Comment</th>";
                                ?>
                            </tr>
                            <?php
                                $rc=-1;
                                foreach($orgDetails as $orgDetail) { 
                                    ++$rc;
                            ?>
                            <tr>
                                <td style="display:none;">
                                    <?php 
                                        echo $this->Form->input("$rc.org_id", array('type'=>'hidden', 'value'=>$orgDetail['BasicModuleBasicInformation']['id'], 'label'=>false)); 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $mfiName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                                        $mfiFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                                        if (!empty($mfiName))
                                            $mfiName = "<strong>".$mfiName.":</strong> ";

                                        if (!empty($mfiFullName))
                                            $mfiName = $mfiName.$mfiFullName;        

                                        echo $mfiName;
                                    ?>
                                </td>                                    
                                <td>
                                    <div>
                                    <?php 

                                        foreach ($approval_status_options as $value => $text) {
                                            echo "<input type='radio' style='margin:2px;' name='data[LicenseModuleAdministrativeApprovalAll][$rc][approval_status_id]' ";
                                            if (strpos($text, 'Not') !== false) { echo 'checked'; } //if($value=='2'){ echo 'checked'; }
                                            echo " value='$value'/>$text<br/>";
                                        }

                                        echo $this->Form->input("$rc.approval_date", array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                                    ?>
                                    </div>
                                </td>
                                <td>
                                    <?php echo $this->Form->input("$rc.reason_if_not_approved",array('type'=>'text', 'style'=>'width:125px; padding:5px;', 'escape'=>false, 'div'=>false, 'label'=>false)); ?>
                                </td>
                                <td>
                                    <?php echo $this->Form->input("$rc.comment",array('type'=>'text', 'style'=>'width:125px; padding:5px;', 'escape'=>false, 'div'=>false, 'label'=>false)); ?>
                                </td>

                            </tr>
                            <?php  } ?>
                        </table> 

                    </div>
                </td>                
            </tr>
        </table>

        
        <?php if($orgDetails && $this->Paginator->param('pageCount')>1) { ?>
        <div class="paginator">
            <?php 
            
            if($this->Paginator->param('pageCount')>10)
            {
               echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')).
                    $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                    $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')).
                    $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
            }
            else {
               echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                    $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
            }
          ?>
        </div>
        <?php } ?>
        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleAdministrativeApprovals','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php                                               
                            echo $this->Js->submit('Approve All', array_merge($pageLoading, 
                                                    array('class'=>'mybtns','success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php echo $this->Form->end(); } ?>
    </fieldset>
</div>

<script>
    $(document).ready(function() {        
        $("#chkbApprovalAll").on("change", function () {
            if(this.checked) {
                $(":radio[value=1]").prop('checked', true);
                $(":radio[value=2]").prop('checked', false);
            }
            else {
                $(":radio[value=1]").prop('checked', false);
                $(":radio[value=2]").prop('checked', true);
            }
        });
    });
</script>
