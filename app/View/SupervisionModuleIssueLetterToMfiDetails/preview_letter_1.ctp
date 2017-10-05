<?php
$title = "Preview of Letter";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
$viewable_user_groups = $this->Session->read('User.ViewableGroups');
$this_state_ids = $this->Session->read('Current.StateIds');
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
?>

<!--        <legend><?php //echo $title;         ?></legend>-->
<div>
    <fieldset>
        <div id="letter">
            <table cellpadding="10" cellspacing="8" border="0" style="width:95%; margin:5px auto;">
                <tr>
                    <td style="width:45%; padding:0; text-align:left; font-weight:bold;">
                        Date: 
                        <?php
                        //echo $requested_data['SupervisionModuleIssueLetterToMfiDetail']['issue_date'];
                        if (!empty($requested_data['SupervisionModuleIssueLetterToMfiDetail']['issue_date']))
                            echo date("d-m-Y", strtotime($requested_data['SupervisionModuleIssueLetterToMfiDetail']['issue_date']));
                        ?> 
                    </td>

                    <td style="width:45%; padding:0; text-align:right; font-weight:bold;">Memo No.: <?php echo $requested_data['SupervisionModuleIssueLetterToMfiDetail']['memo_no']; ?> </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="mra_logo" style="margin-top:-15px; min-height:85px; padding:30px 0 0 0; text-align:left; background-size:auto 100px; background-position: center top;">
                            <?php
                            $to = $requested_data['SupervisionModuleIssueLetterToMfiDetail']['msg_to'] . " <br />" .
                                    implode(", <br />", array($requested_data['SupervisionModuleIssueLetterToMfiDetail']['to_designation'],
                                        $requested_data['SupervisionModuleIssueLetterToMfiDetail']['to_organization'],
                                        $requested_data['SupervisionModuleIssueLetterToMfiDetail']['org_address']));

                            echo $to;
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:5px 0; text-align:left; font-weight:bold;">
                        Subject: <?php echo $requested_data['SupervisionModuleIssueLetterToMfiDetail']['msg_subject']; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:5px 0; text-align:justify; max-width: 500px;">
                        <?php echo $requested_data['SupervisionModuleIssueLetterToMfiDetail']['letter_details'] . "<br /><br />"; ?>
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
                            $authority_details = $authority_info["LookupBasicMraAuthority"]["authority_name"] . '<br />' . $authority_info["LookupBasicMraAuthority"]["authority_designation"];
                            $authority_sign_img = $authority_info["LookupBasicMraAuthority"]["authority_sign"];
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
        <!--        <div class="btns-div"> 
                    <table style="margin:0 auto; padding:5px;" cellspacing="7">
                        <tr>
                            <td>
                                <button class="modal-close" onclick="if (confirm('Are you sure to Close ?'))
                                            modal_close('letter_viewer');
                                        return false;" title="Close Letter Preview.">Close</button>
                            <td>
        <?php
//                        echo $this->Js->submit('Send', array_merge($pageLoading, array(
//                            'url' => "/SupervisionModuleIssueLetterToMfiDetails/send_letter/$supervision_basic_id/$viewable_user_groups",
//                            'class' => 'modal-close',
//                            'confirm' => "Are you sure to Submit?",
//                            'success' => "msg.init('success', 'Issue of Letter', 'Letter has been issued successfully');",
//                            'error' => "msg.init('error', 'Issue of Letter', 'Letter issue failed!');")));
        ?>
                            </td>
                        </tr>
                    </table>
                </div>-->
    </fieldset>
</div>