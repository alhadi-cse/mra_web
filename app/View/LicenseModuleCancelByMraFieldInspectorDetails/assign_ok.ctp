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
        
        <?php  echo $this->Form->create('LicenseModuleCancelByMraFieldInspectorDetail'); ?>
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
                                $orgName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                                $orgFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                                if (!empty($orgName))
                                    $orgName = '<strong>' . $orgName . ':</strong> ';
                                if (!empty($orgFullName))
                                    $orgName = $orgName . $orgFullName;

                                $row = $row . (empty($row) ? '' : '<tr>');
                                $row = $row . '<td>' 
                                        . $this->Form->input("$dist_id.org_ids.$rowspan", array('type' => 'checkbox', 'value' => $orgDetails['BasicModuleBasicInformation']['id'], 'div' => false, 'label' => false))
                                        . '<strong style="display:inline;">' . $orgDetails['BasicModuleBasicInformation']['form_serial_no'] . '</strong></td>'
                                        .'<td>' . $orgName . '</td>'
                                        . '<td style="text-align:center;">' 
                                        . $this->Form->input("$dist_id.inspection_date.$rowspan", array('type' => 'text', 'id' => 'txtInspectionDate_' . $dist_id . '_' . $rowspan, 'class' => 'date_picker', 'div' => false, 'label' => false))
                                        //. $this->Form->image("img/calendar.gif", array('id' => 'txtInspectionDate_' . $dist_id . '_' . $rowspan . '_img', 'alt' => 'Calendar....'))
                                        . '<img id="txtInspectionDate_' . $dist_id . '_' . $rowspan . '_img" src="img/calendar.gif" alt="Calendar...." />'
                                        . '</td>';
                                
                                unset($orgDetailsAll[$data_key]);
                                ++$rowspan;
                            }
                        }
                        
                        $row = '<td style="text-align:center;" rowspan="' . $rowspan . '">' 
                                . $this->Form->input("$dist_id.inspector_user_ids", array('type' => 'select', 'multiple' => 'checkbox', 'options' => $inspector_list, 'escape' => false, 'div' => false, 'label' => false))
                                . '</td>' . $row 
                                . '</tr> ';
                        
                        $allRows = $allRows . '<tr><td style="text-align:center;" rowspan="' . $rowspan . '">' 
                                . $this->Form->input("$dist_id.district_id", array('type' => 'hidden', 'label' => false, 'value' => $dist_id))
                                . $dist_name . '</td>' . $row;
                    }
                    if (!empty($allRows)) {
                        $headerRows = '<tr><th>District</th><th>Inspectors Name & Designation</th>'
                                        . '<th style="width:110px;">Form Serial No.</th>'
                                        . '<th>Name of Organization</th>'
                                        . '<th>Date of Inspection</th>'
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
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleCancelByMraFieldInspectorDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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
    
    $(document).ready(function() {
        
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();
        
        $('.date_picker').each(function() {            
            $(this).datepicker({
                dateFormat: 'dd-mm-yy',
                yearRange: 'c-5:c+5',
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true
            });
            $("#" + this.id + '_img').click( function() {
                $(this).datepicker("show");
            });
        });
        
    });

    
//    $(function() {
//        
//        $('#ui-datepicker-div').remove();
//        $('#ui-datepicker-div').empty();
//        
//        $('.date_picker').each(function() {
//            $(this).datepicker({
//                dateFormat: 'dd-mm-yy',
//                yearRange: 'c-5:c+5',
//                changeMonth: true,
//                changeYear: true,
//                showOtherMonths: true,
//                showOn: 'button',
//                buttonImage: 'img/calendar.gif',
//                buttonImageOnly: true
//            });
//        });
//        
//    });
        
        
//        //$("div").remove("#ui-datepicker-div");
//        //$('#datepick').datepicker();
//        
//        //$('#ui-datepicker-div').empty();
//        $('.date_picker').each(function(){
//            $(this).datepicker({
//                dateFormat: 'dd-mm-yy',
//                yearRange: 'c-5:c+5',
//                changeMonth: true,
//                changeYear: true,
//                showOtherMonths: true,
//                showOn: 'button',
//                buttonImage: 'img/calendar.gif',
//                buttonImageOnly: true//,
////                onSelect: function(dateText, inst) {
////                    $(this).val(dateText);
////                    $(this).datepicker("destroy");
////                }
//            });
//        });
//        $('#ui-datepicker-div').css('clip', 'auto');
//        
//            
////            $(this).datepicker({
////                dateFormat: 'dd-mm-yy',
////                yearRange: 'c-5:c+5',
////                changeMonth: true,
////                changeYear: true,
////                showOtherMonths: true,
////                showOn: 'focus'
////            });
////        $('.date_picker').focusin(function() {
////            $(this).datepicker({
////                dateFormat: 'dd-mm-yy',
////                changeMonth: true,
////                changeYear: true,
////                buttonImage: '/img/calendar.gif',
////                onSelect: function(dateText, inst) {
////                    $(this).val(dateText);
////                    $('#datepicker').datepicker("destroy");
////                }
////            });
////        });

    
</script>
