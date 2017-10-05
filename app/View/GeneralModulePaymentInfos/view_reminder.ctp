
<div>    
    <?php
    
        if (!empty($payment_type_id)) {
            if ($payment_type_id == 1) {
                $title = "License Fee Payment Information";
            } else if ($payment_type_id == 2) {
                $title = "Annual Fee Payment Information";
            } else if ($payment_type_id == 3) {
                $title = "Renewal Fee Payment Information";
            } else if ($payment_type_id == 4) {
                $title = "Others Type of Payment Information";
            }
        }
        else {
            $title = "Payment Information";
        }

        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
    ?>
    
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <?php echo $this->Form->create('GeneralModulePaymentInfo'); ?>                        
        <table cellpadding="0" cellspacing="0" border="0">          
            <tr>
                <td style="padding-left:15px; text-align:right;">Search By</td>
                <td>
                    <?php
                        echo $this->Form->input('search_option', 
                                array('label' => false, 'style'=>'width:200px',
                                    'options' => array('BasicModuleBasicInformation.full_name_of_org' => 'Name of Organization',
                                            'LookupPaymentType.payment_type' => 'Type of Payment',
                                            'GeneralModulePaymentInfo.payment_document_no' => 'Payment Document No.'
                                        ))
                                    );
                    ?>
                </td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
                <td style="text-align:left;">
                   <?php
                       echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch')));
                    ?>
               </td>               
            </tr>
        </table>
        <?php echo $this->Form->end(); ?>
                
        
        <?php if (!empty($values_payment_requested) && is_array($values_payment_requested) && count($values_payment_requested) > 0) { ?>
        <fieldset style="margin-top:10px">
            <legend>Not Approved</legend>
            <?php
//            if (empty($values_payment_requested) || !is_array($values_payment_requested) || count($values_payment_requested) < 1) {
//                echo '<p class="error-message">' . 'There is no pending form for approve !' . '</p>';
//            } else {
            ?>
            <table class="view">
                <tr>
                    <?php
                        echo "<th style='width:95px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                        echo "<th style='min-width:50px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupPaymentType.payment_type', 'Type of Payment') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('GeneralModulePaymentInfo.payment_document_no', 'Payment Document No.') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('GeneralModulePaymentInfo.payment_amount', 'Payment Amount (BDT.)') . "</th>";
                        echo "<th style='width:120px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach($values_payment_requested as $value) { ?>
                <tr>
                    <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                    <td>
                        <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>".$mfiName.":</strong> ";
                            if (!empty($mfiFullName))
                                $mfiName = $mfiName.$mfiFullName;

                            echo $mfiName;
                        ?>
                    </td>
                    <td><?php echo $value['LookupPaymentType']['payment_type']; ?></td>
                    <td><?php echo $value['GeneralModulePaymentInfo']['payment_document_no']; ?></td>
                    <td style="text-align:right; padding-right:15px;"><?php echo $this->Number->precision($value['GeneralModulePaymentInfo']['payment_amount'], 2); ?></td>
                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php 
                        if (in_array(1,$user_group_id))
                            echo $this->Js->link('Approved', array('controller' => 'GeneralModulePaymentInfos', 'action' => 'payment_approved', $value['GeneralModulePaymentInfo']['id']), 
                                        array_merge($pageLoading, array('class'=>'btnlink', 'confirm'=>"Are you sure to Approved this Payment ?")));
                        else
                            echo $this->Js->link('Modify', array('controller' => 'GeneralModulePaymentInfos', 'action' => 'payment_modify', $value['GeneralModulePaymentInfo']['id'], $value['BasicModuleBasicInformation']['id']), 
                                        array_merge($pageLoading, array('class'=>'btnlink')));
                        echo $this->Js->link('Details', array('controller'=>'GeneralModulePaymentInfos', 'action'=>'details', $value['GeneralModulePaymentInfo']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                       ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </fieldset>
        <?php } ?>
        
        
        <fieldset style="margin-top:10px">
            <legend>Sent First Reminder for Payment</legend>
            <?php
            if (empty($values_payment_reminder1_sent) || !is_array($values_payment_reminder1_sent) || count($values_payment_reminder1_sent) < 1) {
                echo '<p class="error-message">' . 'There is no pending form !' . '</p>';
            } else {
            ?>
                        
            <table class="view">
                <tr>
                    <?php
                        echo "<th style='width:95px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LicenseModuleStateHistory.date_of_deadline', 'Deadline Date') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach($values_payment_reminder1_sent as $value){ ?>
                <tr>
                    <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                    <td>
                        <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>".$mfiName.":</strong> ";
                            if (!empty($mfiFullName))
                                $mfiName = $mfiName.$mfiFullName;

                            echo $mfiName;
                        ?>
                    </td>
                    <td style="text-align:center; font-weight:bold; color:#fa2413;">
                        <?php echo $this->Time->format($value['LicenseModulePaymentReminderDetail']['date_of_deadline'], '%d-%m-%Y', ''); ?>
                    </td>
                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php
                            
                            echo $this->Js->link('Payment', array('controller' => 'GeneralModulePaymentInfos', 'action' => 'payment_made', $value['BasicModuleBasicInformation']['id'], $payment_type_id, $thisStateIds[2]), 
                                                array_merge($pageLoading, array('class'=>'btnlink')));
                            
                            if (in_array(1,$user_group_id))
                                echo $this->Js->link('Send Final Reminder', array('controller' => 'GeneralModulePaymentInfos', 'action' => 'reminder_send', $value['BasicModuleBasicInformation']['id'], $thisStateIds[2], 2), 
                                                array_merge($pageLoading, array('class'=>'btnlink', 'confirm'=>'Are you sure to send Final Reminder ?')));
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
                        echo "<th style='width:95px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                        if(!$this->Paginator->param('options'))
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LicenseModuleStateHistory.date_of_deadline', 'Deadline Date') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach($values_payment_deadline_over as $value){ ?>
                <tr>
                    <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                    <td>
                        <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>".$mfiName.":</strong> ";
                            if (!empty($mfiFullName))
                                $mfiName = $mfiName.$mfiFullName;

                            echo $mfiName;
                        ?>
                    </td>
                    <td style="text-align:center; font-weight:bold; color:#fa2413;">
                        <?php echo $this->Time->format($value['LicenseModuleStateHistory']['date_of_deadline'], '%d-%m-%Y', ''); ?>
                    </td>
                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php
                            
                            echo $this->Js->link('Payment', array('controller' => 'GeneralModulePaymentInfos', 'action' => 'payment_made', $value['BasicModuleBasicInformation']['id'], $payment_type_id, $thisStateIds[2]), 
                                                array_merge($pageLoading, array('class'=>'btnlink')));
                            
                            if (in_array(1,$user_group_id))
                                echo $this->Js->link('Send 1st Reminder', array('controller' => 'GeneralModulePaymentInfos', 'action' => 'reminder_send', $value['BasicModuleBasicInformation']['id'], $thisStateIds[1], 1), 
                                                array_merge($pageLoading, array('class'=>'btnlink', 'confirm'=>'Are you sure to send 1st Reminder ?')));
                        ?>
                    </td>
                </tr>
              <?php } ?>
            </table>
            
            <?php } ?>
        </fieldset>
        
        
    </fieldset>
</div>
