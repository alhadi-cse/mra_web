<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    ?>

    <div id="basicInfo" title="Detail Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;">
        <fieldset>
            <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
                <table cellpadding="7" cellspacing="8" border="0">
                    <tr>
                        <td style="width:145px;">Name of Organization</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo $caseDetails['BasicModuleBasicInformation']['full_name_of_org'];
                            if (!empty($caseDetails['BasicModuleBasicInformation']['full_name_of_org']) && !empty($caseDetails['BasicModuleBasicInformation']['short_name_of_org']))
                                echo " (<strong>" . $caseDetails['BasicModuleBasicInformation']['short_name_of_org'] . "<strong>)";
                            else
                                echo $caseDetails['BasicModuleBasicInformation']['short_name_of_org'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Case with Reason</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo $caseDetails['LookupSupervisionCategory']['case_categories'];
                            if (!empty($caseDetails['SupervisionModuleOrgSelectionDetail']['supervision_reason']))
                                echo "  (" . $caseDetails['SupervisionModuleOrgSelectionDetail']['supervision_reason'] . ")";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <fieldset>
                                <legend>Comments/Notes</legend>
                                <table>
                                    <tr>
                                        <td style="width:120px;">AD</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModuleAssignedInspectorApprovalDetail']['comments_of_ad']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>SAD</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModuleAssignedInspectorApprovalDetail']['comments_of_sad']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>DD</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModuleAssignedInspectorApprovalDetail']['comments_of_dd']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>SDD</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModuleAssignedInspectorApprovalDetail']['comments_of_sdd']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Director</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModuleAssignedInspectorApprovalDetail']['comments_of_director']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>EVC</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModuleAssignedInspectorApprovalDetail']['comments_of_evc']; ?></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </div>

<?php } ?>