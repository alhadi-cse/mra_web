<?php
if (empty($allDetails)) {
    echo '<p class="error-message">No data is available !</p>';
} else {
    ?>
    <div id="basicInfo" title="Detail Information of Organization" style="margin:0px; padding:0px; background-color:#fafdff;">
        <fieldset>
            <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
                <table cellpadding="7" cellspacing="8" border="0">
                    <tr>
                        <td style="width:155px;">Name of Organization</td>
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
                        <td>Case with Reason</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo $allDetails['LookupSupervisionCategory']['case_categories']
                            . (!empty($allDetails['SupervisionModuleOrgSelectionDetail']['supervision_reason']) ? " (" . $allDetails['SupervisionModuleOrgSelectionDetail']['supervision_reason'] . ")" : "");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Letter No.</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            $nf = new NumberFormatter("en-US", NumberFormatter::ORDINAL);
                            echo $nf->format($allDetails['SupervisionModulePrepareLetterDetail']['letter_serial_no']) . " Letter";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Prepared Letter</td>
                        <td class="colons">:</td>
                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['letters']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <fieldset style="padding: 5px 8px 8px;">
                                <legend>Comments/Notes</legend>
                                <table>
                                    <tr>
                                        <td style="width:140px;">Inspector</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_inspector']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Section</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_section']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>AD</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_ad']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>SAD</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_sad']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>DD</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_dd']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>SDD</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_sdd']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Director</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_director']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>EVC</td>
                                        <td class="colons">:</td>
                                        <td><?php echo $allDetails['SupervisionModulePrepareLetterDetail']['comments_or_notes_of_evc']; ?></td>
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