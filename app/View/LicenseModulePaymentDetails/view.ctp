
<div>
    <?php
    $title = (!empty($payment_type_id) && !empty($payment_types[$payment_type_id])) ? "$payment_types[$payment_type_id] Payment Information" : "Payment Information";

    if (!isset($licensed_mfi))
        $licensed_mfi = 1;

    $mfi_no_field = ($licensed_mfi == 1) ? 'license_no' : 'form_serial_no';

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


        <fieldset style="margin-top:10px">
            <legend>Payment Completed</legend>
            <?php
            if (empty($values_payment_done) || !is_array($values_payment_done) || count($values_payment_done) < 1) {
                echo "<p class='error-message'>Did not find any $title !</p>";
            } else {
                ?>
                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:100px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                        echo "<th style='min-width:250px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_fiscal_year', 'Payment Fiscal Year') . "</th>";
                        echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_date', 'Payment Date') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_document_no', 'Payment Document') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_amount', 'Payment Amount') . "</th>";
                        echo "<th style='width:80px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_payment_done as $value) { ?>
                        <tr>
                            <td style="text-align:center;">
                                <?php echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                                ?>
                            </td>
                            <td style="text-align:center;"><?php echo $value['LicenseModulePaymentDetail']['payment_fiscal_year']; ?></td>
                            <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModulePaymentDetail']['payment_date'], '%d-%m-%Y', ''); ?></td>
                            <td><?php echo $value['LicenseModulePaymentDetail']['payment_document_no']; ?></td>
                            <td style="text-align:right; padding-right:10px;">
                                <?php echo $this->Number->precision($value['LicenseModulePaymentDetail']['payment_amount'], 2); ?></td>
                            <td style="height:30px; padding:2px; text-align:center;">
                                <?php
                                echo $this->Js->link('Details', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'details', $value['LicenseModulePaymentDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </fieldset>



        <fieldset style="margin-top:10px">
            <legend>Payment not Approved</legend>
            <?php
            if (empty($values_payment_requested) || !is_array($values_payment_requested) || count($values_payment_requested) < 1) {
                echo "<p class='error-message'>Did not find any $title !</p>";
            } else {
                ?>

                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:100px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                        echo "<th style='min-width:250px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_fiscal_year', 'Payment Fiscal Year') . "</th>";
                        echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_date', 'Payment Date') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_document_no', 'Payment Document') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModulePaymentDetail.payment_amount', 'Payment Amount') . "</th>";
                        echo "<th style='width:80px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_payment_requested as $value) { ?>
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
                            <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModulePaymentDetail']['payment_date'], '%d-%m-%Y', ''); ?></td>
                            <td><?php echo $value['LicenseModulePaymentDetail']['payment_document_no']; ?></td>
                            <td style="text-align:right; padding-right:10px;"><?php echo $this->Number->precision($value['LicenseModulePaymentDetail']['payment_amount'], 2); ?></td>
                            <td style="height:30px; padding:2px; text-align:center;">
                                <?php
                                if ($is_admin)
                                    echo $this->Js->link('Approved', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_approved', $value['LicenseModulePaymentDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => "Are you sure to Approved this Payment ?")));
                                else 
                                    echo $this->Js->link('Modify', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_submit', $value['LicenseModulePaymentDetail']['id'], $thisStateIds[2]), array_merge($pageLoading, array('class' => 'btnlink')));
                                echo $this->Js->link('Details', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'details', $value['LicenseModulePaymentDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </fieldset>



        <fieldset style="margin-top:10px">
            <legend>Waiting for Payment</legend>

            <?php
            if ((!empty($values_payment_reminder_sent) && is_array($values_payment_reminder_sent) && count($values_payment_reminder_sent) > 0) || (!empty($values_payment_pending) && is_array($values_payment_pending) && count($values_payment_pending) > 0)) {
                ?>

                <?php
                if (!empty($values_payment_reminder_sent) && is_array($values_payment_reminder_sent) && count($values_payment_reminder_sent) > 0) {
                    ?>

                    <fieldset style="margin-top:3px">
                        <legend>Reminder Sent</legend>
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
                                            echo $this->Js->link('Modify', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_modify', $value['LicenseModulePaymentDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                        else 
                                            echo $this->Js->link('Payment', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_submit', $thisStateIds[2]), array_merge($pageLoading, array('class' => 'btnlink')));
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </fieldset>
                <?php } ?>


                <?php
                if (!empty($values_payment_pending) && is_array($values_payment_pending) && count($values_payment_pending) > 0) {
                    ?>

                    <fieldset style="margin-top:5px">
                        <legend>Notification Sent</legend>
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
                            <?php foreach ($values_payment_pending as $value) { ?>
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
                                        if ($is_admin)
                                            echo $this->Js->link('Modify', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_modify', $value['LicenseModulePaymentDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                        else
                                            echo $this->Js->link('Payment', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_submit', $value['LicenseModulePaymentDetail']['id'], $thisStateIds[2]), array_merge($pageLoading, array('class' => 'btnlink')));
                                        echo $this->Js->link('Details', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'details', $value['LicenseModulePaymentDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                        
                                        //echo $this->Js->link('Payment', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_made', $value['BasicModuleBasicInformation']['id'], $payment_type_id, $thisStateIds[2]), array_merge($pageLoading, array('class' => 'btnlink')));
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </fieldset>            
                <?php } ?>

                <?php
            }
            else {
                echo '<p class="error-message">No data is available !</p>';
            }
            ?>

        </fieldset>


        <fieldset>
            <legend>Selected for Payment</legend>

            <?php
            if (empty($values_payment_selected) || !is_array($values_payment_selected) || count($values_payment_selected) < 1) {
                echo '<p class="error-message">No data is available !</p>';
            } else {
                ?>
                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:100px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                        echo "<th style='min-width:400px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:80px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_payment_selected as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                                ?>
                            </td>
                            <td style="height:30px; padding:2px; text-align:center;">
                                <?php
                                if ($is_admin)
                                    echo $this->Js->link('Send Notification', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_notification_send', $value['BasicModuleBasicInformation']['id'], $payment_type_id, $thisStateIds[1]), array_merge($pageLoading, array('class' => 'btnlink'))); //, 'confirm' => "Are you sure to send the payment notification ?")));
                                else
                                    echo $this->Js->link('Payment', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'payment_made', $value['BasicModuleBasicInformation']['id'], $payment_type_id, $thisStateIds[2]), array_merge($pageLoading, array('class' => 'btnlink')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>

            <?php } ?>
        </fieldset>
        
        <?php if ($is_admin) { ?>
            <div class="btns-div"> 
                <table style="margin:0 auto;" cellspacing="5">
                    <tr>
                        <td>
                            <?php
                            echo $this->Js->link('Payment Reminder', array('controller' => 'LicenseModulePaymentDetails', 'action' => 'view_reminder',
                                '?' => array('payment_type_id' => $payment_type_id, 'licensed_mfi' => $licensed_mfi)), array_merge($pageLoading, array('class' => 'mybtns')));
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php } ?>

    </fieldset>
</div>
