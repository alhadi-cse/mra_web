<div>
    <?php
    
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    
    if (!empty($inspection_type_id) && !empty($inspection_type_detail[$inspection_type_id]))
        $title = "Assign Inspector for $inspection_type_detail[$inspection_type_id]";
    else
        $title = "Assign Inspector for Field Inspection";
    
    if (!isset($licensed_mfi))
        $licensed_mfi = 0;

    $mfi_no_field = ($licensed_mfi == 1) ? 'license_no' : 'form_serial_no';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    ?> 
    <fieldset>
        <legend><?php echo $title; ?></legend> 

        <?php echo $this->Form->create('LicenseModuleFieldInspectionInspectorDetail'); ?>
        

        <fieldset>
            <legend>Assign Inspector</legend>

            <?php
            if (!empty($dist_list)) {
                $allRows = null;
                ?>
                <table class="view">
                    <?php
                    foreach ($dist_list as $dist_id => $dist_name) {
                        if (empty($orgDetailsAll))
                            break;

                        $row = '';
                        $rowspan = 0;
                        foreach ($orgDetailsAll as $data_key => $orgDetails) {
                            $org_district_id = ($licensed_mfi == 1) ? $orgDetails['BasicModuleBranchInfo']['district_id'] : $orgDetails['BasicModuleProposedAddress']['district_id'];
                            if ($org_district_id == $dist_id) {
                                $org_id = $orgDetails['BasicModuleBasicInformation']['id'];
                                $orgName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                                $orgFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                                $orgName = $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);

                                $row = $row . (empty($row) ? '' : '<tr>');
                                $row = $row . '<td style="font-weight:bold;">'
                                        . $this->Form->input("$dist_id.org_ids.$rowspan", array('type' => 'checkbox', 'value' => $org_id, 'div' => false, 'label' => $orgDetails['BasicModuleBasicInformation'][$mfi_no_field])) . '</td>'
                                        . '<td>' . $orgName . '</td>'
                                        . '<td style="text-align:center;">'
                                        . $this->Form->input("$dist_id.inspection_dates.$org_id", array('type' => 'hidden', 'id' => 'txtDate_' . $org_id . '_alt', 'div' => false, 'label' => false))
                                        . $this->Form->input("$dist_id.inspection_dates_show.$org_id", array('type' => 'text', 'id' => 'txtDate_' . $org_id, 'class' => 'date_picker', 'div' => false, 'label' => false))
                                        //. "<input type='text' id='txtDate_$org_id' class='date_picker' />" 
                                        . '</td>';

                                unset($orgDetailsAll[$data_key]);
                                ++$rowspan;
                            }
                        }

                        $row = '<td rowspan="' . $rowspan . '" class="inspector_group_' . $dist_id . '">'
                                //. $this->Form->input("$dist_id.team_leader_user_id", array('type' => 'select', 'style' => 'min-width:200px; width:100%; padding:0;', 'options' => $inspector_list, 'empty' => '---Select Team Leader---', 'escape' => false, 'label' => false, 'div' => false))
                                . $this->Form->input("$dist_id.inspector_user_ids", array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox', 'options' => $inspector_list, 'escape' => false, 'div' => false, 'label' => false))
                                . $this->Form->input("$dist_id.team_leader_user_id", array('type' => 'select', 'class' => 'team_leader', 'style' => 'display:none; min-width:200px; width:95%; margin-top:4px; padding:0; color:#071324;', 'empty' => '---Select Team Leader---', 'escape' => false, 'label' => false, 'div' => false))
                                . '</td>' . $row
                                . '</tr> ';

                        $allRows = $allRows . '<tr><td rowspan="' . $rowspan . '">'
                                . $this->Form->input("$dist_id.district_id", array('type' => 'hidden', 'label' => false, 'value' => $dist_id))
                                . $dist_name . '</td>' . $row;
                    }
                    if (!empty($allRows)) {
                        $headerRows = '<tr><th>District</th>'
                                . '<th style="width:175px;">Inspectors Name & Designation</th>'
                                . '<th style="width:95px;">' . (($licensed_mfi == 1) ? 'License No.' : 'Form No.') . '</th>'
                                . '<th style="min-width:200px;">Name of Organization</th>'
                                . '<th style="width:155px;">Date of Inspection</th>'
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

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'LicenseModuleFieldInspectionInspectorDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?', 'title' => 'Close this Form')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        if (!empty($dist_list))
                            echo $this->Js->submit('Save', array_merge($pageLoading, array('confirm' => 'Are you sure to Save ?', 'title' => 'Inspector Assign', 
                                                        'success' => "msg.init('success', '$title', 'Inspector has been assigned successfully.');", 'error' => "msg.init('error', '$title', 'Inspector assign failed !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div> 
        <?php echo $this->Form->end(); ?>
    </fieldset>    
</div>

<script>
    
    function CheckSelection($inspector_group) {
        $inspector_group.find('select.team_leader').children('option').remove();
        if ($inspector_group.find('.multi-checkbox input[type="checkbox"]:checked').length > 0) {
            $inspector_group.find('select.team_leader').show();
            $inspector_group.find('select.team_leader').append($('<option />', {value: '', text: '---Select Team Leader---'}));
            $inspector_group.find('.multi-checkbox input[type="checkbox"]:checked').each(function () {
                var $value = $(this).val();
                var $text = $('label[for="' + $(this).attr('id') + '"]').text();
                $inspector_group.find('select.team_leader').append($('<option />', {value: $value, text: $text}));
            });
        }
        else
            $inspector_group.find('select.team_leader').hide();
    }

    $(function () {
        
        $('.team_leader').each(function () {
            CheckSelection($(this).closest('td'));
        });
        
        $('.multi-checkbox input[type="checkbox"]').on("click", function () {
            CheckSelection($(this).closest('td'));
        });

        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();

        $('.date_picker').each(function () {
            $(this).datepicker({
                showAnim: 'show',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                minDate: '0',
                yearRange: '-0:+1',
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });
    });
    
</script>
