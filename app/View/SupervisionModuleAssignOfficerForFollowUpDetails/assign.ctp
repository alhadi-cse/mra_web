<div>
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    $title = "Assign Officer for Follow Up";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?> 
    <fieldset>
        <legend><?php echo $title; ?></legend> 

        <?php echo $this->Form->create('SupervisionModuleAssignOfficerForFollowUpDetail'); ?>
        <?php    
        $allRows = null;
        
        foreach ($follow_up_officer_list as $user_id => $follow_up_officer) {
            $row = '';
            $rowspan = 0;
            if(!empty($orgDetailsAll)) {
                foreach ($orgDetailsAll as $data_key => $orgDetails) {
                    $org_id = $orgDetails['BasicModuleBasicInformation']['id'];
                    $orgName = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
                    $orgFullName = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                    $orgName = $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);

                    $row = $row . (empty($row) ? '' : '<tr>');
                    $row = $row . '<td style="font-weight:bold;">'
                            . $this->Form->input("$user_id.org_ids.$rowspan", array('type' => 'checkbox', 'value' => $org_id, 'class' => 'org-ids', 'div' => false, 'label' => $orgName)) . '</td>'
                            . '<td>' . $orgDetails['BasicModuleBasicInformation']['license_no'] . '</td>';
                    ++$rowspan;
                }
                $row = '<td rowspan="' . $rowspan . '" class="inspector_group" style="padding-left:8px;">'
                        . $follow_up_officer
                        . $this->Form->input("$user_id.follow_up_officer_user_id", array('type' => 'hidden', 'value' => $user_id, 'div' => false, 'label' => false))
                        . $this->Form->input("$user_id.is_current", array('type' => 'hidden', 'value' => 1, 'div' => false, 'label' => false))
                        . '</td>' . $row
                        . '</tr> ';
                $allRows = $allRows . $row;
            }
        }
    ?> 
        <?php if (!empty($allRows)) { ?>
        <fieldset>
            <legend>Assign Officer</legend>
            <table class="view">
                <?php         
                    $headerRows = '<tr>'
                            . '<th style="width:200px;">Officers Name & Designation</th>'
                            . '<th style="min-width:200px;">Name of Organization</th>'
                            . '<th style="width:175px;">License No.</th>'
                            . '</tr>';
                    $allRows = $headerRows . $allRows;
                    echo $allRows;
                ?>
            </table>
        </fieldset>
        
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'SupervisionModuleAssignOfficerForFollowUpDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?', 'title' => 'Close this Form')));
                        ?> 
                    </td>
                    <?php if (!empty($allRows)) { ?>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Save', array_merge($pageLoading, array('confirm' => 'Are you sure to Save ?', 'title' => 'Officer Assign',
                            'success' => "msg.init('success', '$title', 'Officer has been assigned successfully.');", 'error' => "msg.init('error', '$title', 'Officer assign failed !');")));
                        ?>
                    </td>
                    <?php } ?>
                </tr>
            </table>
        </div> 
        <?php } else {
            echo '<div class="error-message">There is no pending organization to follow up!</div>';
        } 
        echo $this->Form->end();?>
    </fieldset>    
</div>

<script>
    function DisableAllCheckbox($check_box) {
        if ($check_box.closest('table').find('input[value="' + $check_box.val() + '"].org-ids').length > 0) {
            $check_box.closest('table').find('input[value="' + $check_box.val() + '"].org-ids').each(function () {
                $(this).prop("disabled", $check_box.prop("checked"));
                if ($check_box.prop("checked"))
                    $(this).parent().addClass('disable');
                else
                    $(this).parent().removeClass('disable');
            });
            
            $check_box.prop("disabled", false);
            $check_box.parent().removeClass('disable');
        }
    }

    $(function () {

        $('input[type="checkbox"].org-ids').on("click", function () {
            DisableAllCheckbox($(this));
        });

    });

</script>