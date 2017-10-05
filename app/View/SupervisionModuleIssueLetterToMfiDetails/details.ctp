<div id="basicInfo" title="Detail" style="margin:0px; padding:0px; background-color:#fafdff;"> 
    <fieldset>        
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <?php if (!empty($issued_letter_details) && count($issued_letter_details) > 0) { ?>

                <?php
                $nf = new NumberFormatter("en-US", NumberFormatter::ORDINAL);
                foreach ($issued_letter_details as $letter_details) {

                    $letter_title = $nf->format($letter_details['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no']) . " Letter";
                    ?>
                    <fieldset>

                        <legend><?php echo $letter_title; ?></legend>

                        <table cellpadding="7" cellspacing="8" border="0">                
                            <tr>
                                <td style="width:130px;">Issued Date</td>
                                <td class="colons">:</td>
                                <td>
                                    <?php
                                    if (!empty($letter_details['SupervisionModuleIssueLetterToMfiDetail']['issue_date']))
                                        echo date("d-m-Y", strtotime($letter_details['SupervisionModuleIssueLetterToMfiDetail']['issue_date']));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Letter Subject</td>
                                <td class="colons">:</td>
                                <td><?php echo $letter_details['SupervisionModuleIssueLetterToMfiDetail']['msg_subject']; ?></td>
                            </tr>
                            <tr>
                                <td>Letter To</td>
                                <td class="colons">:</td>
                                <td>
                                    <?php
                                    if (!empty($letter_details['SupervisionModuleIssueLetterToMfiDetail']['msg_to'])) {
                                        echo $letter_details['SupervisionModuleIssueLetterToMfiDetail']['msg_to'];

                                        if (!empty($letter_details['SupervisionModuleIssueLetterToMfiDetail']['to_designation']))
                                            echo ', ' . $letter_details['SupervisionModuleIssueLetterToMfiDetail']['to_designation'];
                                        if (!empty($letter_details['SupervisionModuleIssueLetterToMfiDetail']['to_organization']))
                                            echo ', ' . $letter_details['SupervisionModuleIssueLetterToMfiDetail']['to_organization'];
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Issued Letter</td>
                                <td class="colons">:</td>
                                <td><?php echo $letter_details['SupervisionModuleIssueLetterToMfiDetail']['letter_details']; ?></td>
                            </tr>
                        </table>

                    </fieldset>
                    <?php
                }
            }
            ?>

        </div>
    </fieldset>
</div>