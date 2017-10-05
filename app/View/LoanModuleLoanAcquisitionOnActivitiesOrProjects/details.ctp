
<?php 

    if(!empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    
    $title = 'Case Information Details';
    $pageLoading = array('update'=>'#ajax_div', 'evalScripts'=>true, 'class'=>'mybtns', 
                    'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                    'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
    
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">
            
        <?php if(!empty($data_count)) { ?>
            <?php if($data_count==1) { ?>
            <table cellpadding="7" cellspacing="8" border="0" style="width:100%;">
                <tr>
                    <td style="min-width:170px">Name of Organization</td>
                    <td class="colons">:</td>
                    <td style="min-width:375px">
                        <span style="float:left; max-width:87%; margin:3px 0;">
                            <?php echo $allDataDetails['BasicModuleBasicInformation']['full_name_of_org']; ?>
                        </span>
                        <span style="float:right;">
                            <?php
                                echo $this->Js->link('Edit', array('controller'=>'BasicModuleCaseInformations', 
                                                       'action'=>'edit', $allDataDetails['BasicModuleCaseInformation']['id'], $allDataDetails['BasicModuleCaseInformation']['org_id'], 2), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'style'=>'margin:0; padding:4px;')));
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Member National ID</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleCaseInformation']['member_nid']; ?></td>
                </tr>                
                <tr>
                    <td>Case No</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleCaseInformation']['caseNo']; ?></td>
                </tr>
                <tr>
                    <td>Case Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['LookupCaseType']['case_types']; ?></td>
                </tr>
                <tr>
                    <td>Name The Court</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleCaseInformation']['nameOfCourt']; ?></td>
                </tr>  
                <tr>
                    <td>Duration Of Conviction</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDataDetails['BasicModuleCaseInformation']['durationOfConviction']; ?></td>
                </tr>
            </table>
            
            <?php } else if($data_count=='all') { ?>
            
            <div style="max-width:780px; overflow-x:auto;">
                <table cellpadding="7" cellspacing="8" border="0" class="view">
                    <tr>
                        <th style="width:100px;">Member National ID</th>
                        <th style="width:100px;">Case No</th>
                        <th style="width:100px;">Case Type</th>
                        <th style="width:185px;">Name The Court</th>
                        <th style="width:100px;">Duration Of Conviction</th>
                    </tr>

                    <?php
                        $rc=0;
                        foreach($allDataDetails as $caseDetails){ 
                        $rc++;
                    ?>

                    <tr<?php if ($rc%2==0) { echo ' class="alt"'; } ?>>
                        <td><?php echo $caseDetails['BasicModuleCaseInformation']['member_nid']; ?></td>
                        <td><?php echo $caseDetails['BasicModuleCaseInformation']['caseNo']; ?></td>
                        <td><?php echo $caseDetails['LookupCaseType']['case_types']; ?></td>
                        <td><?php echo $caseDetails['BasicModuleCaseInformation']['nameOfCourt']; ?></td>
                        <td><?php echo $caseDetails['BasicModuleCaseInformation']['durationOfConviction']; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            
            <?php } ?>
        <?php 
            }
            else {
                echo '<p class="error-message">';
                echo 'Did not find any data !';
                echo '</p>';
            }
        ?>
        </div>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td>
                        <?php 
                            echo $this->Js->link('Previous', array('controller'=>'BasicModuleBranchHRInfos','action'=>'details'),
                                                                    array_merge($pageLoading, array('success'=>'msc.prev();')));
                        ?>
                    </td>

                    <td>
                        <?php 
                            if(empty($data_count) || $data_count === 0) {
                                echo $this->Js->link('Add New', array('controller'=>'BasicModuleCaseInformations', 'action'=>'add'), $pageLoading);
                            }
                            else {
                                echo $this->Js->link('Close', array('controller'=>'BasicModuleCaseInformations','action'=>'view'), $pageLoading);
                            }
                        ?>
                    </td>

                    <td>
                        <?php
                            echo $this->Js->link('Next', array('controller'=>'BasicModuleSisterOrganizationInfos', 'action'=>'details'), 
                                                                array_merge($pageLoading, array('success'=>'msc.next();')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>
        
    </fieldset>
</div>

