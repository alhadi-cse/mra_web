<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {

    if (empty($allDetails)) {
        echo '<p class="error-message">No data is available!</p>';
    } else {
        debug($allDetails);
        $nf = new NumberFormatter("en-US", NumberFormatter::ORDINAL);
        ?>

        <div id="basicInfo" title="Detail Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 
            <fieldset>        
                <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
                    <table cellpadding="7" cellspacing="8" border="0">
        <!--                        <tr>
                            <td>Name of Organization</td>
                            <td class="colons">:</td>
                            <td>
                        <?php
//                                if (!empty($allDetails['BasicModuleBasicInformation'])) {
//                                    $orgDetail = $allDetails['BasicModuleBasicInformation'];
//                                    echo $orgDetail['full_name_of_org'] . (!empty($orgDetail['short_name_of_org']) ? ' <strong>(' . $orgDetail['short_name_of_org'] . ')</strong>' : '');
//                                }
                        ?>
                            </td>
                        </tr>-->
                        <tr>
                            <td style="width:150px;">Explanation Against</td>
                            <td class="colons">:</td>
                            <td>
                                <?php
                                if (!empty($allDetails['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no']))
                                    echo "<strong>"
                                    . $nf->format($allDetails['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no'])
                                    . " Letter : </strong>"
                                    . $allDetails['SupervisionModuleIssueLetterToMfiDetail']['msg_subject'];
                                else
                                    echo $allDetails['SupervisionModuleIssueLetterToMfiDetail']['msg_subject'];
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Explanation</td>
                            <td class="colons">:</td>
                            <td>
                                <?php
                                if (!empty($allDetails['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_giving_date']))
                                    echo date("d-m-Y", strtotime($allDetails['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_giving_date']));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Explanation</td>
                            <td class="colons">:</td>
                            <td><?php echo $allDetails['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_details']; ?></td>
                        </tr>
                    </table>
                </div>
            </fieldset>
        </div>

        <?php
    }
}
?>