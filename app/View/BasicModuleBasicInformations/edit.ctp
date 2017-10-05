<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (!empty($IsValidUser) && !empty($org_id)) {
    $title = "Primary Information";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $short_name_of_org = "";
    $full_name_of_org = "";
    $registration_no = "";
    $registration_authority = "";
    if (!empty($orgDetails)) {
        //debug($orgDetails);
        $short_name_of_org = $orgDetails['BasicModuleBasicInformation']['short_name_of_org'];
        $full_name_of_org = $orgDetails['BasicModuleBasicInformation']['full_name_of_org'];
                
        $application_date = '';
        if (!empty($orgDetails['BasicModuleBasicInformation']['date_of_application'])) {
            $application_date = $orgDetails['BasicModuleBasicInformation']['date_of_application'];
            $application_date = (!empty($application_date) ? date("d-m-Y", strtotime($application_date)) : '');
        }
        $license_no = $orgDetails['BasicModuleBasicInformation']['license_no'];
        $licensing_year = $orgDetails['BasicModuleBasicInformation']['licensing_year'];
        $license_issue_date = $orgDetails['BasicModuleBasicInformation']['license_issue_date'];        
    }
    ?>

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend>
            <?php echo $this->Form->create('BasicModuleBasicInformation'); ?>
            <div class="form">
                <table cellpadding="8" cellspacing="8" border="0" style="width:95%;">
                    <tr>
                        <td style="width:30%;">Name of NGO-MFIs</td>
                        <td class="colons">:</td>
                        <td ><?php echo $full_name_of_org; ?></td>
                    </tr>                    
                    <?php if($licensing_state_id=='0') { ?>
                    <tr>
                        <td>Short Name of Organization</td>
                        <td class="colons">:</td>
                        <td>
                            <?php 
                                echo $this->Form->input('short_name_of_org',array('label'=>false, 'div' => false)); 
                                echo $this->Form->input('date_of_application', array('type' => 'hidden', 'value' => $application_date, 'id' => 'txtApplicationDate_alt', 'label' => false, 'div' => false));
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if($licensing_state_id=='30') { ?>
                    <tr>
                        <td>License No.</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            if(!empty($license_no)) {
                                echo $license_no;
                            }
                            else {
                                echo $this->Form->input('license_no', array('label'=>false, 'div' => false));                                
                            }
                            ?>
                        </td>
                    </tr>                    
                    <tr>
                        <td>Licensing Year</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('licensing_year', array('label'=>false, 'class' => 'small', 'div' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Date of License Issue</td>
                        <td class="colons">:</td>
                        <td>                            
                            <?php
                                if (!empty($this->request->data['BasicModuleBasicInformation'])) {
                                    $license_issue_date = $this->request->data['BasicModuleBasicInformation']['license_issue_date'];                                    
                                }
                                $license_issue_date = (!empty($license_issue_date) ? date("d-m-Y", strtotime($license_issue_date)) : '');
                                echo $this->Form->input("license_issue_date", array('type' => 'hidden', 'id' => 'txtLicenseIssueDate_alt', 'div' => false, 'label' => false))
                                     ."<input type='text' id='txtLicenseIssueDate' value='$license_issue_date' class='date_picker' />";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Primary Registration</td>
                        <td class="colons">:</td>                        
                        <td><?php echo $this->Form->input('primary_reg_act_id', array('type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-inline-checkbox', 'options' => $primary_reg_act_options, 'selected' => $selected_reg_act_values, 'escape' => false, 'div' => false, 'label' => false)); ?></td>
                    </tr>                    
                    <?php } ?>
                </table>
            </div>

            <div class="btns-div"> 
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>
                        <td>
                            <?php
                            echo $this->Js->submit('Update', array_merge($pageLoading, array(
                                'success' => "msg.init('success', '$title', '$title has been updated successfully.');",
                                'error' => "msg.init('error', '$title', '$title has been updated failed !');")));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Js->link('Close', array('controller' => 'BasicModuleBasicInformations', 'action' => 'view', (!empty($org_id) ? 'all' : null)), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Js->link('Next', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view', $org_id, 'edit'), array_merge($pageLoading, array('success' => 'msc.next();')));
                            ?>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>

            <?php echo $this->Form->end(); ?>
        </fieldset>
    </div>
<?php } ?>
<script type="text/javascript">
    $(function () {	
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();
        $('.date_picker').each(function () {
            $(this).datepicker({
                showAnim: 'show',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                //minDate: '0',
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });
    });
</script>