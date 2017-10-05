<div>
    <?php 
        $title = "Assign Inspector for Initial Field Inspection"; 
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        if(!empty($msg)) {
            if(is_array($msg)) {
                echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
            }
            else {
                echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
            }
        }
    ?> 
    <fieldset>
        <legend><?php echo $title; ?></legend> 
        
        <?php  echo $this->Form->create('LicenseModuleCancelByMfiFieldInspectorDetail'); ?>
        <div class="form">
            
            <fieldset>
                <legend>Assign Inspector</legend>
                    
                <?php
                if (!empty($dist_list)) {
                    $allRows = null;
                ?>
                <table class="view">
                <?php
//                if (!empty($dist_list)) {
//                    $allRows = null;
                    foreach ($dist_list as $dist_id => $dist_name) {
                        if (empty($orgDetailsAll)) break;
                        
                        $row = '';
                        $rowspan = 0;
                        foreach ($orgDetailsAll as $data_key => $orgDetails) {
                            if ($orgDetails['BasicModuleBranchInfo']['district_id'] == $dist_id) {
                                
                                $org_id = $orgDetails['BasicModuleBasicInformation']['id'];
                                $orgName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                                $orgFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                                if (!empty($orgName))
                                    $orgName = '<strong>' . $orgName . ':</strong> ';
                                if (!empty($orgFullName))
                                    $orgName = $orgName . $orgFullName;
                                
                                $row = $row . (empty($row) ? '' : '<tr>');
                                $row = $row . '<td style="font-weight:bold;">'
                                        . $this->Form->input("$dist_id.org_ids.$rowspan", array('type' => 'checkbox', 'value' => $org_id, 'div' => false, 'label' => $orgDetails['BasicModuleBasicInformation']['license_no'])) . '</td>'
                                        
                                        . '<td>' . $orgName . '</td>'
                                        . '<td style="text-align:center;">' 
                                        . $this->Form->input("$dist_id.inspection_dates.$org_id", array('type' => 'hidden', 'id' => 'txtDate_' . $org_id . '_alt')) 
                                        . "<input type='text' id='txtDate_$org_id' class='date_picker' />" . '</td>';
                                
                                unset($orgDetailsAll[$data_key]);
                                ++$rowspan;
                            }
                        }
                        
                        $row = '<td rowspan="' . $rowspan . '">' 
                                . $this->Form->input("$dist_id.inspector_user_ids", array('type' => 'select', 'multiple' => 'checkbox', 'options' => $inspector_list, 'escape' => false, 'div' => false, 'label' => false))
                                . '</td>' . $row 
                                . '</tr> ';
                        
                        $allRows = $allRows . '<tr><td rowspan="' . $rowspan . '">' 
                                . $this->Form->input("$dist_id.district_id", array('type' => 'hidden', 'label' => false, 'value' => $dist_id))
                                . $dist_name . '</td>' . $row;
                    }
                    if (!empty($allRows)) {
                        $headerRows = '<tr><th>District</th>'
                                        . '<th>Inspectors Name & Designation</th>'
                                        . '<th style="width:100px;">Form Serial No.</th>'
                                        . '<th style="min-width:230px;">Name of Organization</th>'
                                        . '<th style="width:115px;">Date of Inspection</th>'
                                    . '</tr>';
                        $allRows = $headerRows . $allRows;

                        echo $allRows;
                    }
                ?>
                </table>
                
                <?php 
                } else {
                    echo '<p class="error-message">' . 'There is no pending form for Inspector assign !' . '</p>';
                }
                ?>
            </fieldset>
        </div>        
       <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php                                
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleCancelByMfiFieldInspectorDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php 
                        if (!empty($dist_list))
                            echo $this->Js->submit('Save', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
     <?php  echo $this->Form->end(); ?>
    </fieldset>    
</div>

<script>
    
    $(function() {
        
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();
        
        $('.date_picker').each(function() {
            $(this).datepicker({
                yearRange: 'c-5:c+5',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });
        
    });
    
</script>
