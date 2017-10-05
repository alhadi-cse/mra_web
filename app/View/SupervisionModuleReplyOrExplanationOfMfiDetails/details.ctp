
<div id="basicInfo" title="Detail Information of Organization" style="margin:0px; padding:0px; background-color:#fafdff;"> 
    <fieldset>        
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <?php if (!empty($allDetails) && count($allDetails) > 0) { 
                
                $nf = new NumberFormatter("en-US", NumberFormatter::ORDINAL);
                foreach ($allDetails as $expla_details) {

                    $expla_title = "Explanation Against: " . $nf->format($expla_details['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no']) . " Letter";
                    ?>
                    <fieldset>

                        <legend><?php echo $expla_title; ?></legend>

                        <table cellpadding="7" cellspacing="8" border="0">                
                            <tr>
                                <td style="width:145px;">Letter Issued Date</td>
                                <td class="colons">:</td>
                                <td>
                                    <?php
                                    if (!empty($expla_details['SupervisionModuleIssueLetterToMfiDetail']['issue_date']))
                                        echo date("d-m-Y", strtotime($expla_details['SupervisionModuleIssueLetterToMfiDetail']['issue_date']));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Letter Subject</td>
                                <td class="colons">:</td>
                                <td>
                                    <?php echo $expla_details['SupervisionModuleIssueLetterToMfiDetail']['msg_subject']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Date of Explanation</td>
                                <td class="colons">:</td>
                                <td>
                                    <?php
                                    if (!empty($expla_details['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_giving_date']))
                                        echo date("d-m-Y", strtotime($expla_details['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_giving_date']));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Explanation</td>
                                <td class="colons">:</td>
                                <td><?php echo $expla_details['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_details']; ?></td>
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
