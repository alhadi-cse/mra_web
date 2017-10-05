
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

        <?php echo $this->Form->create('AdminModuleMessageSendingDetail'); ?>
        <div class="form">
            <span style="font-size:9.75pt;">
                <?php
                if (!empty($org_id) && isset($orgName))
                    echo "Name of Organization : <strong>$orgName</strong>"
                    . $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false, 'div' => false));
                ?>
            </span>

            <fieldset>
                <legend>Message Details</legend>
                <table cellpadding="8" cellspacing="8" border="0">
                    <tr>
                        <td>Creation Date</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo date('d-m-Y');
                            echo $this->Form->input('mail_creation_date', array('type' => 'hidden', 'value' => date('Y-m-d'), 'label' => false, 'div' => false));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>From</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo $this->Form->input('mail_from_email', array('type' => 'text', 'value' => $mail_from_email, 'label' => 'e-mail: ', 'style' => "width:185px; margin-right:15px;", 'div' => false));
                            echo $this->Form->input('mail_from_details', array('type' => 'text', 'value' => $mail_from_details, 'label' => 'details: ', 'style' => "width:280px;", 'div' => false));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>To</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('mail_to', array('type' => 'text', 'value' => $mail_to, 'label' => false, 'div' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>CC</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('mail_cc', array('type' => 'text', 'label' => false, 'div' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>BCC</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('mail_bcc', array('type' => 'text', 'label' => false, 'div' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Subject</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('mail_subject', array('type' => 'text', 'value' => $mail_subject, 'label' => false, 'div' => false)); ?></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; padding-top:5px;">Message Body</td>
                        <td class="colons" style="vertical-align:top;">:</td>
                        <td><?php echo $this->Form->input('mail_message', array('type' => 'textarea', 'value' => $mail_body, 'style="min-width:600px; min-height:230px;"', 'label' => false, 'div' => false)); ?></td>
                    </tr>
                </table>
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
                        echo $this->Js->submit('Send', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been added successfully.');",
                            'error' => "msg.init('error', '$title', '$title has been failed to add !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<!--<script type="text/javascript">

//    $(function () {
//
//        $('.decimals').numeric({decimal: ".", negative: false});
//
//        $('#ui-datepicker-div').remove();
//        $('#ui-datepicker-div').empty();
//        $('.date_picker').each(function () {
//            $(this).datepicker({
//                showAnim: 'show',
//                dateFormat: 'dd-mm-yy',
//                altField: '#' + this.id + '_alt',
//                altFormat: "yy-mm-dd",
//                minDate: '0',
//                showOtherMonths: true,
//                showOn: 'both',
//                buttonImageOnly: true,
//                buttonImage: 'img/calendar.gif',
//                buttonText: 'Click to show the calendar'
//            });
//        });
//
//    });

</script>-->
