
<div>
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    
    $title = 'Message Sending Details';
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <div class="form">
            <span style="font-size:9.75pt;">
                <?php
                if (!empty($orgName))
                    echo "Name of Organization : <strong>$orgName</strong>";
                ?>
            </span>

            <fieldset>
                <legend>Message Details</legend>
                
                <?php if (!empty($messageDetails)) { ?>
                <table cellpadding="8" cellspacing="8" border="0" style="width:98%;">
                    <tr>
                        <td style="width:95px;">Creation Date</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            if (!empty($messageDetails['mail_creation_date']))
                                echo $this->Time->format($messageDetails['mail_creation_date'], '%d-%m-%Y', '');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Sending Date</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            if (!empty($messageDetails['mail_sending_date']))
                                echo $this->Time->format($messageDetails['mail_sending_date'], '%d-%m-%Y', '');
                            else
                                echo 'The message has not been sent yet';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>From</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo $messageDetails['mail_from_details'] . ' (' . $messageDetails['mail_from_email'] . ')';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>To</td>
                        <td class="colons">:</td>
                        <td><?php echo $messageDetails['mail_to']; ?></td>
                    </tr>
                    <?php if (!empty($messageDetails['mail_cc'])) { ?>
                    <tr>
                        <td>CC</td>
                        <td class="colons">:</td>
                        <td><?php echo $messageDetails['mail_cc']; ?></td>
                    </tr>
                    <?php } 
                    if (!empty($messageDetails['mail_bcc'])) { ?>
                    <tr>
                        <td>BCC</td>
                        <td class="colons">:</td>
                        <td><?php echo $messageDetails['mail_bcc']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>Subject</td>
                        <td class="colons">:</td>
                        <td><?php echo $messageDetails['mail_subject']; ?></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; padding-top:5px;">Message Body</td>
                        <td class="colons" style="vertical-align:top;">:</td>
                        <td style="border:1px solid #ddd; padding:5px 7px; white-space:pre-wrap;"><?php echo $messageDetails['mail_message']; ?></td>
                    </tr>
                </table>
                <?php } else { echo 'Invalid Message Details !'; } ?>
                
            </fieldset>
        </div>
        
        <div class="btns-div">
            <table style="margin:0 auto; padding:5px;" cellspacing="5">
                <tr>
                    <td>
                        <?php
                        if (!empty($redirect_url))
                            echo $this->Js->link('Close', $redirect_url, array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                        ?>
                    </td>
                    <td style="text-align:center;">
                        <?php
                        if (!empty($message_id))
                            echo $this->Js->link('Re-Send', array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $message_id, 1), $pageLoading);
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
    </fieldset>
</div>
