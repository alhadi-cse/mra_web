<div id="basicInfo" title="Detail Information" style="margin:0px; padding:0px; background-color:#fafdff;"> 
    <fieldset>        
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">              
                <tr>
                    <td style="width:155px;">Organization's Name</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        if (!empty($allDetails['BasicModuleBasicInformation'])) {
                            $orgDetail = $allDetails['BasicModuleBasicInformation'];
                            echo $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>License No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['BasicModuleBasicInformation']['license_no']; ?></td>
                </tr>
                <tr>
                    <td>Case Title/Category</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['LookupSupervisionCategory']['case_categories']; ?></td>
                </tr>
                <tr>
                    <td>Supervision Reason</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['SupervisionModuleOrgSelectionDetail']['supervision_reason']; ?></td>
                </tr>
                <tr>
                    <td>Inspection Starting Date</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        if (!empty($allDetails['SupervisionModuleOrgSelectionDetail']['from_date']))
                            echo date("d-m-Y", strtotime($allDetails['SupervisionModuleOrgSelectionDetail']['from_date']));
                        ?>
                    </td>
                </tr>
                <?php if ($allDetails['SupervisionModuleOrgSelectionDetail']['is_running_case'] == '1') { ?>
                    <tr>
                        <td>Case Status</td>
                        <td class="colons">:</td>
                        <td><?php echo 'Running'; ?> </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </fieldset>
</div>