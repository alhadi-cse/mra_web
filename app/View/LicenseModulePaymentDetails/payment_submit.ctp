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
    <?php
    $title = (!empty($payment_type_id) && !empty($payment_types[$payment_type_id])) ? "$payment_types[$payment_type_id] Payment Information" : "Payment Information";

    $existing_data = $this->request->data;
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('LicenseModulePaymentDetail'); ?>
        <div class="form">
            <table cellpadding="8" cellspacing="8" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        $mfiName = $existing_data['BasicModuleBasicInformation']['short_name_of_org'];
                        $mfiFullName = $existing_data['BasicModuleBasicInformation']['full_name_of_org'];

                        echo ((!empty($mfiName) && !empty($mfiFullName)) ? "$mfiFullName <strong>($mfiName)</strong>" : "$mfiFullName$mfiName")
                            . $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'div' => false, 'label' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        if (!empty($payment_type_id)) {
                            echo '<strong>' . $existing_data['LookupLicensePaymentType']['payment_type'] . '</strong>'
                                    . $this->Form->input('payment_type_id', array('type' => 'hidden', 'value' => $payment_type_id, 'label' => false, 'div' => false));
                        } else {
                            echo $this->Form->input('payment_type_id', array('type' => 'select', 'options' => $payment_types, 'id' => 'payment_type', 'empty' => '-----Select-----', 'label' => false));
                        }
                        ?>
                    </td>
                </tr>
                <?php if (empty($payment_type_id)) { ?>
                    <tr id="if_select_other">
                        <td>Other Type/Reason</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('payment_reason', array('type' => 'text', 'label' => false, 'div' => false)); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Payment Amount</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('payment_amount', array('type' => 'text', 'class' => 'decimals', 'style' => 'width:130px; text-align:right;', 'label' => false, 'div' => false)) . " Tk"; ?></td>
                </tr>
                <tr>
                    <td>Delay Fine (if applicable)</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('payment_delay_fine', array('type' => 'text', 'class' => 'decimals', 'style' => 'width:130px; text-align:right;', 'label' => false, 'div' => false)) . " Tk"; ?></td>
                </tr>
                <tr>
                    <td>Payment Fiscal Year</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('payment_fiscal_year', array('type' => 'text', 'style' => 'width:130px; text-align:center;', 'label' => false, 'div' => false)); ?></td>
                </tr>
                <tr>
                    <td>Date of Payment</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        echo $this->Time->format(new DateTime('now'), '%d-%m-%Y', '');
                        echo $this->Form->input('payment_date', array('type' => 'hidden', 'value' => date("Y-m-d"), 'label' => false, 'div' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Payment Document (No. & Bank Name)</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('payment_document_no', array('type' => 'text', 'label' => false, 'div' => false)); ?></td>
                </tr>
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="5">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', $redirect_url, array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Js->submit('Submit', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been updated successfully.');", 'error' => "msg.init('error', '$title', '$title has been failed to add !');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<script type="text/javascript">

    $(document).ready(function () {
        $('.decimals').numeric({decimal: ".", negative: false});
    });

    $(document).ready(function () {
        hide();
        $('#payment_type').change(function () {
            $('#payment_type option:selected').text();
            if ($(this).val() == "4") {
                show();
            } else {
                hide();
            }
        });

        function hide() {
            $("#if_select_other").hide();
        }
        function show() {
            $("#if_select_other").show();
        }
    });

</script>
