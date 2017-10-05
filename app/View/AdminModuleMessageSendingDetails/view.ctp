
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
//    if (!isset($licensed_mfi))
//        $licensed_mfi = 1;
//    $mfi_no_field = ($licensed_mfi == 1) ? 'license_no' : 'form_serial_no';

    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php echo $this->Form->create('AdminModuleMessageSendingDetail'); ?>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding-left:15px; text-align:right;">Search By</td>
                <td>
                    <?php
                    echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                        'options' => array('AdminModuleMessageSendingDetail.mail_subject' => 'Message Subject',
                            'AdminModuleMessageSendingDetail.mail_to' => 'Message Receiver',
                            'AdminModuleMessageSendingDetail.mail_creation_date' => 'Creation Date',
                            'AdminModuleMessageSendingDetail.mail_sending_date' => 'Date of Sending',
                            'BasicModuleBasicInformation.full_name_of_org' => 'Name of Organization')));
                    ?>
                </td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                <td style="text-align:left;">
                    <?php
                    echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                    ?>
                </td> 
            </tr>
        </table>
        <?php echo $this->Form->end(); ?>

        <?php if (!empty($values_message_sent) && is_array($values_message_sent) && count($values_message_sent) > 0) { ?>
            <fieldset style="margin-top:10px">
                <legend>Message Sent</legend>
                <table class="view">
                    <tr>
                        <?php 
                        echo "<th style='width:185px;'>" . $this->Paginator->sort('AdminModuleMessageSendingDetail.mail_subject', 'Subject') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('AdminModuleMessageSendingDetail.mail_to', 'Receiver') . "</th>";
                        //echo "<th style='width:85px;'>" . $this->Paginator->sort('AdminModuleMessageSendingDetail.mail_creation_date', 'Creation Date') . "</th>";
                        echo "<th style='width:85px;'>" . $this->Paginator->sort('AdminModuleMessageSendingDetail.mail_sending_date', 'Date of Sending') . "</th>";
                        //echo "<th style='width:100px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                        echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_message_sent as $value) { ?>
                        <tr>
                            <td><?php echo $value['AdminModuleMessageSendingDetail']['mail_subject']; ?>
                            <td><?php echo $value['AdminModuleMessageSendingDetail']['mail_to']; ?></td>
                            <!--<td style="text-align:center;"><?php //echo $this->Time->format($value['AdminModuleMessageSendingDetail']['mail_creation_date'], '%d-%m-%Y', ''); ?></td>-->
                            <td style="text-align:center;"><?php echo $this->Time->format($value['AdminModuleMessageSendingDetail']['mail_sending_date'], '%d-%m-%Y', ''); ?></td>
                            <!--<td style="text-align:center;"><?php //echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>-->
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                                ?>
                            </td>
                            <td style="height:30px; padding:2px; text-align:center;">
                                <?php
                                echo $this->Js->link('Details', array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'details', $value['AdminModuleMessageSendingDetail']['id'], 1), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                echo $this->Js->link('Re-Send', array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $value['AdminModuleMessageSendingDetail']['id'], 1), array_merge($pageLoading, array('class' => 'btnlink')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>

            </fieldset>
        <?php } ?>


        <?php if (!empty($values_message_not_sent) && is_array($values_message_not_sent) && count($values_message_not_sent) > 0) { ?>
            <fieldset style="margin-top:10px">
                <legend>Message not Sent</legend>
                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:185px;'>" . $this->Paginator->sort('AdminModuleMessageSendingDetail.mail_subject', 'Subject') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('AdminModuleMessageSendingDetail.mail_to', 'Receiver') . "</th>";
                        echo "<th style='width:85px;'>" . $this->Paginator->sort('AdminModuleMessageSendingDetail.mail_creation_date', 'Creation Date') . "</th>";
                        //echo "<th style='width:85px;'>" . $this->Paginator->sort('AdminModuleMessageSendingDetail.mail_sending_date', 'Date of Sending') . "</th>";
                        //echo "<th style='width:100px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                        echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_message_not_sent as $value) { ?>
                        <tr>
                            <td><?php echo $value['AdminModuleMessageSendingDetail']['mail_subject']; ?>
                            <td><?php echo $value['AdminModuleMessageSendingDetail']['mail_to']; ?></td>
                            <td style="text-align:center;"><?php echo $this->Time->format($value['AdminModuleMessageSendingDetail']['mail_creation_date'], '%d-%m-%Y', ''); ?></td>
                            <!--<td style="text-align:center;"><?php //echo $this->Time->format($value['AdminModuleMessageSendingDetail']['mail_sending_date'], '%d-%m-%Y', ''); ?></td>-->
                            <!--<td style="text-align:center;"><?php //echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>-->
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                                ?>
                            </td>
                            <td style="height:30px; padding:2px; text-align:center;">
                                <?php
                                echo $this->Js->link('Details', array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'details', $value['AdminModuleMessageSendingDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                echo $this->Js->link('Send', array('controller' => 'AdminModuleMessageSendingDetails', 'action' => 'message_send', $value['AdminModuleMessageSendingDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </fieldset>
        <?php } ?>

    </fieldset>
</div>
