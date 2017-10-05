<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (empty($approvalType))
    $approvalType = "Director's";

$title = "$approvalType Cancellation of Approval";

$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('SupervisionModuleAssignedInspectorApprovalDetailAll'); ?>

        <div style="width:780px; height:auto; padding:0; overflow-x:auto;">
            <?php
            if (empty($values_approved) || !is_array($values_approved) || count($values_approved) < 1) {
                echo '<p class="error-message"> No data is available ! </p>';
            } else {
                ?>

                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='width:120px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('0.name_with_designation_and_dept', 'Inspectors Name & Designation') . "</th>";
                        echo "<th style='width:80px;'>Inspection Date</th>";
                        echo "<th style='width:80px;'>Comments</th>";
                        echo "<th style='width:80px;'>Cancell All <input type='checkbox' id='chkbCancelAll'/> </th>";
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
                                echo $this->Form->input("$rc.supervision_basic_id", array('type' => 'hidden', 'value' => $value['SupervisionModuleBasicInformation']['id'], 'label' => false));
                                echo $value['BasicModuleBasicInformation']['license_no'];
                                ?>
                            </td>
                            <td>
                                <?php
                                $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                ?>
                            </td>
                            <td><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                            <td><?php echo $value['name_with_designation_and_dept']; ?></td>
                            <td><?php echo date("d-m-Y", strtotime($value['SupervisionModuleAssignedInspectorApprovalDetail']['inspection_date'])); ?></td>
                            <td><?php echo $this->Form->input("$rc.tier_wise_comments", array('type' => 'text', 'class' => 'small', 'div' => false, 'label' => false)); ?></td>
                            <td style="text-align:center;"><?php echo $this->Form->input("$rc.is_approved", array('type' => 'checkbox', 'class' => 'isApproved', 'div' => false, 'label' => 'Cancel')); ?></td>
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
                        echo $this->Js->link('Close', array('controller' => 'SupervisionModuleAssignedInspectorApprovalDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?', 'title' => 'Back to Previous Page')));
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Js->submit('Cancell All', array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Cancell All ?', 'title' => 'Cancell All Inspector Assignment',
                            'success' => "msg.init('success', '$title', 'Approval has been cancelled successfully.');",
                            'error' => "msg.init('error', '$title', 'Cancellation failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<script>
    $(document).ready(function () {
        $("#chkbCancelAll").on("change", function () {
            $(":checkbox.isApproved").prop("checked", this.checked);
        });

        $(":checkbox.isApproved").on("change", function () {
            var total = $(":checkbox.isApproved").length;
            var checked = $(":checkbox.isApproved:checked").length;
            if (total === checked) {
                $("#chkbCancelAll").prop('checked', true);
                $("#chkbCancelAll").prop('indeterminate', false);
            } else if (checked === 0) {
                $("#chkbCancelAll").prop('checked', false);
                $("#chkbCancelAll").prop('indeterminate', false);
            } else {
                $("#chkbCancelAll").prop('indeterminate', true);
            }
        });

    });
</script>
