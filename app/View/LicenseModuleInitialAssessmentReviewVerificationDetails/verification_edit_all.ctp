<?php
    $title = 'Initial Assessment Review and Verification';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleInitialAssessmentReviewVerificationDetailAll'); ?>
        
        <table>
            <tr>
                <td>
                    <div style="width:780px; height:auto; padding:0; overflow-x:auto;">
                        <?php 
                            if($orgDetails==null || !is_array($orgDetails) || count($orgDetails)<1) {
                                echo '<p class="error-message">';
                                echo 'No data is available!';
                                echo '</p>';

                                echo $this->Js->link('Back', array('controller' => 'LicenseModuleInitialAssessmentReviewVerificationDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));
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
                                    echo "<th style='width:120px;'>Verify All <input type='checkbox' id='chkbVerificationAll'/> </th>";
                                    echo "<th style='width:70px;'>Date Of Verification</th>";
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
                                        echo $this->Form->input("$rc.id", array('type'=>'hidden', 'value'=>$orgDetail['LicenseModuleInitialAssessmentReviewVerificationDetail']['id'], 'label'=>false));    
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

                                        foreach ($verification_status_options as $value => $text) {
                                            echo "<input type='radio' style='margin:2px;' name='data[LicenseModuleInitialAssessmentReviewVerificationDetailAll][$rc][verification_status_id]' ";
                                            if($value==$orgDetail['LicenseModuleInitialAssessmentReviewVerificationDetail']['verification_status_id']){ echo 'checked'; }
                                            echo " value='$value'/>$text<br/>";
                                        }
                                    ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        echo $this->Time->format($orgDetail['LicenseModuleInitialAssessmentReviewVerificationDetail']['verification_date'],'%d-%m-%Y','');
                                        echo $this->Form->input("$rc.verification_date", array('type'=>'hidden', 'value'=>date("Y-m-d"), 'label'=>false));
                                    ?>
                                </td>
                                <td>
                                    <?php echo $this->Form->input("$rc.reason_if_not_approved",array('type'=>'text', 'value'=>$orgDetail['LicenseModuleInitialAssessmentReviewVerificationDetail']['reason_if_not_approved'], 'style'=>'width:120px; padding:5px;', 'escape'=>false, 'div'=>false, 'label'=>false)); ?>
                                </td>
                                <td>
                                    <?php echo $this->Form->input("$rc.verification_comment",array('type'=>'text', 'value'=>$orgDetail['LicenseModuleInitialAssessmentReviewVerificationDetail']['verification_comment'], 'style'=>'width:120px; padding:5px;', 'escape'=>false, 'div'=>false, 'label'=>false)); ?>
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
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleInitialAssessmentReviewVerificationDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                       ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php                                               
                            echo $this->Js->submit('Update All', array_merge($pageLoading, 
                                                    array('class'=>'mybtns','success'=>"msg.init('success', '$title', '$title has been update successfully.');", 
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
        $("#chkbVerificationAll").on("change", function () {
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
