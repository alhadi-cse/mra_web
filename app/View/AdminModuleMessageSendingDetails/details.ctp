
<div id="msgInfo" title="Message Sending Details" style="margin:0px; padding:10px; background-color:#fafdff;"> 

    <!--<fieldset>
            <legend>Message Sending Details</legend>
            <div class="form"></div>
            
        </fieldset>-->
    <span style="font-size:9.75pt;">
        <?php
        if (!empty($orgName))
            echo "Name of Organization : <strong>$orgName</strong>";
        ?>
    </span>

    <fieldset>
        <legend>Message Details</legend>
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
            <?php if (!empty($is_send)) { ?>
            <tr>
                <td>Sending Date</td>
                <td class="colons">:</td>
                <td>
                    <?php
                    if (!empty($messageDetails['mail_sending_date']))
                        echo $this->Time->format($messageDetails['mail_sending_date'], '%d-%m-%Y', '');
                    ?>
                </td>
            </tr>
            <?php } ?>
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
                <td style="border:1px solid #ddd; padding:5px 7px; white-space:pre-wrap;">
                    <?php echo $messageDetails['mail_message']; ?></td>
            </tr>
        </table>
    </fieldset>
    
</div>

<script>
    $(function () {
        $("#msgInfo").dialog({
            modal: true, width: 'auto', height: 'auto', resizable: false, dialogClass: 'my-dialog-box',
            buttons: {
                Close: function () {
                    $(this).dialog("close");
                }
            },
            create: function (evt, ui) {
                $(this).css("minWidth", "850px").css("maxWidth", "1000px");
            }
        });
    });
</script>


