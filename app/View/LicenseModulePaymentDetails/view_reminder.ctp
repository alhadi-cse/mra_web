
<div>    
    <?php
    $title = (!empty($payment_type_id) && !empty($payment_types[$payment_type_id])) ? "$payment_types[$payment_type_id] Payment Information" : "Payment Information";

    if (!isset($licensed_mfi))
        $licensed_mfi = 1;

    $mfi_no_field = ($licensed_mfi == 1) ? 'license_no' : 'form_serial_no';
//    (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.'));

    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <?php echo $this->Form->create('LicenseModulePaymentDetail'); ?>                        
        <table cellpadding="0" cellspacing="0" border="0">          
            <tr>
                <td style="padding-left:15px; text-align:right;">Search By</td>
                <td>
                    <?php
                    echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                        'options' => array('BasicModuleBasicInformation.full_name_of_org' => 'Name of Organization',
                            'LicenseModulePaymentDetail.payment_fiscal_year' => 'Payment Fiscal Year',
                            'LicenseModulePaymentDetail.payment_document_no' => 'Payment Document No.')));
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
        
        <fieldset>
            <legend>Reminder Sent for Payment</legend>
            <?php
            if (empty($values_payment_reminder_sent) || !is_array($values_payment_reminder_sent) || count($values_payment_reminder_sent) < 1) {
                echo '<p class="error-message">' . 'There is no pending form !' . '</p>';
            } else {
                ?>
            <table class="view">
                <tr>
                    <?php
                    echo "<th style='width:100px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                    echo "<th style='min-width:320px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_fiscal_year', 'Payment Fiscal Year') . "</th>";
                    echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModulePaymentReminderDetail.reminder_notify_date', 'Sending Date') . "</th>";
                    echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModulePaymentReminderDetail.reminder_deadline_date', 'Deadline Date') . "</th>";
                    echo "<th style='width:80px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach ($values_payment_reminder_sent as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>
                        <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                            ?>
                        </td>
                        <td style="text-align:center;"><?php echo $value['LicenseModulePaymentDetail']['payment_fiscal_year']; ?></td>
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModulePaymentReminderDetail']['reminder_notify_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="font-weight:bold; text-align:center;">
                            <?php
                            $deadlineDate = $value['LicenseModulePaymentReminderDetail']['reminder_deadline_date'];

                            if (!empty($deadlineDate)) {
                                if ($deadlineDate < date('Y-m-d'))
                                    $color = '#fa2413';
                                else if ($deadlineDate < (date('Y-m-d', strtotime('+7 days'))))
                                    $color = '#eaac23';
                                else
                                    $color = '#138723';

                                $deadlineDate = $this->Time->format($deadlineDate, '%d-%m-%Y', '');
                                echo "<strong style='color:$color;'>$deadlineDate</strong>";
                            }
                            ?>
                        </td>
                        <td style="height:30px; padding:2px; text-align:center;">
                            <?php
                            if($is_admin)
                                echo $this->Js->link('Reject', array('controller' => 'LicenseModuleApplicationRejectionDetails', 'action' => 'rejection', $value['BasicModuleBasicInformation']['id'], 1, "$thisStateIds[4]_48_50", 1), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to reject this organization ?')));
                                //echo $this->Js->link('Modify', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_modify', $value['LicenseModulePaymentDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            else 
                                echo $this->Js->link('Payment', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_submit', $value['LicenseModulePaymentDetail']['id'], $thisStateIds[2]), array_merge($pageLoading, array('class' => 'btnlink')));
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </fieldset>


        <fieldset style="margin-top:10px">
            <legend>Payment Deadline Over</legend>
            <?php
            if (empty($values_payment_deadline_over) || !is_array($values_payment_deadline_over) || count($values_payment_deadline_over) < 1) {
                echo '<p class="error-message">' . 'There is no pending form !' . '</p>';
            } else {
                ?>
            
            <table class="view">
                <tr>
                    <?php
                    echo "<th style='width:100px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                    echo "<th style='min-width:320px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_fiscal_year', 'Payment Fiscal Year') . "</th>";
                    echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_notify_date', 'Notify Date') . "</th>";
                    echo "<th style='width:90px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_deadline_date', 'Deadline Date') . "</th>";
                    echo "<th style='width:80px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach ($values_payment_deadline_over as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>
                        <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                            ?>
                        </td>
                        <td style="text-align:center;"><?php echo $value['LicenseModulePaymentDetail']['payment_fiscal_year']; ?></td>
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModulePaymentDetail']['payment_notify_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="font-weight:bold; text-align:center;">
                            <?php
                            $deadlineDate = $value['LicenseModulePaymentDetail']['payment_deadline_date'];

                            if (!empty($deadlineDate)) {
                                if ($deadlineDate < date('Y-m-d'))
                                    $color = '#fa2413';
                                else if ($deadlineDate < (date('Y-m-d', strtotime('+7 days'))))
                                    $color = '#eaac23';
                                else
                                    $color = '#138723';

                                $deadlineDate = $this->Time->format($deadlineDate, '%d-%m-%Y', '');
                                echo "<strong style='color:$color;'>$deadlineDate</strong>";
                            }
                            ?>
                        </td>
                        <td style="height:30px; padding:2px; text-align:center;">
                            <?php
                                //echo $this->Js->link('Modify', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_modify', $value['LicenseModulePaymentDetail']['id'], $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            
                            if ($is_admin)
                                echo $this->Js->link('Reminder', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'reminder_send', $value['BasicModuleBasicInformation']['id'], $payment_type_id, $thisStateIds[4]), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to send Reminder ?')));
                            else
                                echo $this->Js->link('Payment', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_submit', $value['LicenseModulePaymentDetail']['id'], $thisStateIds[2]), array_merge($pageLoading, array('class' => 'btnlink')));
                            echo $this->Js->link('Details', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'details', $value['LicenseModulePaymentDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));

                            //echo $this->Js->link('Payment', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_made', $value['BasicModuleBasicInformation']['id'], $payment_type_id, $thisStateIds[2]), array_merge($pageLoading, array('class' => 'btnlink')));
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?php } ?>
        </fieldset>

        <div class="btns-div">
            <table style="margin:0 auto;" cellspacing="5">
                <tr>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Js->link('Close', $redirect_url, array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to close ?')));
                        ?>
                    </td>
                </tr>
            </table>
        </div>

    </fieldset>

</fieldset>
</div>
