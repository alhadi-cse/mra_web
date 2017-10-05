<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
?> 

<div>
    <fieldset>       
        <div id="letter">
            <table cellpadding="10" cellspacing="8" border="0" style="width:95%; margin:5px auto;">
                <tr>
                    <td style="width:45%; padding:0; text-align:left; font-weight:bold;">Date: 
                        <?php
                        if (!empty($issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['issue_date']))
                            echo date("d-m-Y", strtotime($issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['issue_date']));
                        ?> 
                    </td>
                    <td style="width:45%; padding:0; text-align:right; font-weight:bold;">Memo No.: 
                        <?php echo $issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['memo_no']; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="">
                        <div class="mra_logo" style="margin-top:-15px; min-height:85px; padding:30px 0 0 0; text-align:left; background-size:auto 100px; background-position: center top;">
                            <?php
                            $to = $issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['msg_to'] . " <br />" .
                                    $issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['to_designation'] . ", <br />" .
                                    $issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['to_organization'] . ", <br />" .
                                    $issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['org_address'];
                            echo $to;
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:5px 0; text-align:left; font-weight:bold;">
                        Subject: <?php echo $issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['msg_subject']; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:5px 0; text-align:justify;">
                        <?php echo $issued_letter_details['SupervisionModuleIssueLetterToMfiDetail']['letter_details'] . "<br /><br />"; ?>
                    </td>
                </tr>                
                <tr>
                    <td colspan="2" style="padding:5px 0; text-align:left;">
                        <style>
                            .sign_image {
                                border: 0 none;
                                width: 250px;
                                height: 70px;
                                overflow: hidden;

                                background-size: 100% 100%;
                                background-color: #fff;
                                background-repeat: no-repeat;
                            }
                            .sign_line {
                                border-top:2px solid #333; 
                                width:260px; 
                                height:0; 
                                margin-left:0;
                            }
                        </style>
                        <?php
                        if (!empty($authority_info)) {
                            $authority_details = $issued_letter_details["LookupBasicMraAuthority"]["authority_name"] . '<br />' . $authority_info["LookupBasicMraAuthority"]["authority_designation"];
                            $authority_sign_img = $issued_letter_details["LookupBasicMraAuthority"]["authority_sign"];
                        }
                        if (!empty($authority_sign_img))
                            echo "<image src='~/../files/uploads/$authority_sign_img' class='sign_image' />";
                        echo "<hr class='sign_line' /> Signature";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:5px 0; text-align:left;">
                        <?php if (!empty($authority_info)) echo $authority_details; ?>
                    </td>
                </tr>
            </table>                       
        </div>        
    </fieldset>
</div>
