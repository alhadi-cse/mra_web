<?php
    $title = 'Edit Administrative Approval of All Assigned Inspector';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading); ?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModuleDirectSuspensionFieldInspAdminApprovalDetailsAll'); ?>
                
        <div style="width:780px; height:auto; padding:0; overflow-x:auto;">
            <?php 
                if(empty($values_approved) || !is_array($values_approved) || count($values_approved) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                }
                else {
            ?>
            
            <table class="view">
                <tr>
                    <?php
                    echo "<th style='width:70px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                    echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                    echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                    echo "<th style='width:150px;'>" . $this->Paginator->sort('0.inspectors_name_with_designation_and_dept', 'Inspectors Name & Designation') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectorDetail.inspection_date', 'Inspection Date') . "</th>";
                    echo "<th style='width:125px;'>Approve All <input type='checkbox' checked='checked' id='chkbApprovalAll'/> </th>";
                    ?>
                </tr>
                <?php
                    $rc = -1;
                    foreach ($values_approved as $value) {
                        ++$rc;
                ?>
                <tr>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Form->input("$rc.org_id", array('type' => 'hidden', 'value' => $value['BasicModuleBasicInformation']['id'], 'label' => false));
                        echo $value['BasicModuleBasicInformation']['license_no'];
                        ?>
                    </td>
                    <td>
                        <?php
                        $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                        $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                        if (!empty($mfiName))
                            $mfiName = "<strong>" . $mfiName . ":</strong> ";
                        if (!empty($mfiFullName))
                            $mfiName = $mfiName . $mfiFullName;

                        echo $mfiName;
                        ?>
                    </td>
                    <td><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                    <td><?php echo $value[0]['inspectors_name_with_designation_and_dept']; ?></td>
                    <td><?php echo date("d-m-Y", strtotime($value['LicenseModuleSuspensionFieldInspectorDetail']['inspection_date'])); ?></td>
                    <td style="text-align:center;"><?php echo $this->Form->input("$rc.is_approved", array('type' => 'checkbox', 'class' => 'isApproved', 'checked' => 'checked', 'div' => false, 'label' => 'Approve')); ?></td>
                </tr>
                <?php } ?>
            </table>
            
            <?php if ($values_approved && $this->Paginator->param('pageCount') > 1) { ?>
            <div class="paginator">
                <?php 
                if ($this->Paginator->param('pageCount') > 10) {
                    echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                    $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                } else {
                    echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                }
                ?>
            </div>
            <?php } ?>

        <?php } ?>
        </div>
        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php 
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleDirectSuspensionFieldInspAdminApprovalDetailss','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns')));  
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
        
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<script>
    $(document).ready(function() {        
        $("#chkbApprovalAll").on("change", function () {
            $(":checkbox.isApproved").prop("checked", this.checked);
        });
        
        $(":checkbox.isApproved").on("change", function () {
            var total = $(":checkbox.isApproved").length;
            var checked = $(":checkbox.isApproved:checked").length;
            if (total === checked) {
                $("#chkbApprovalAll").prop('checked', true);
                $("#chkbApprovalAll").prop('indeterminate', false);
            } else if (checked === 0) {
                $("#chkbApprovalAll").prop('checked', false);
                $("#chkbApprovalAll").prop('indeterminate', false);
            } else {
                $("#chkbApprovalAll").prop('indeterminate', true);
            }
        });
        
    });
</script>
