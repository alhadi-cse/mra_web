<div id="frmStatus_add">
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    $title = "Set Data Period";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>            
        </legend> 

        <?php echo $this->Form->create('AdminModulePeriodDetail'); ?>
        <div class="form">           
            <table cellpadding="3" cellspacing="7">
                <tr>
                    <td>Type of User</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('AdminModulePeriodDetail.user_group_id', array('type' => 'select', 'id' => 'user_types', 'options' => $user_group_options, 'empty' => '---Select---', 'label' => false, 'div' => false)); ?></td>
                </tr>
                <tr>
                    <td>Type of Period</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('AdminModulePeriodDetail.period_type_id', array('type' => 'select', 'id' => 'period_types', 'options' => 'null', 'empty' => '---Select---', 'label' => false, 'div' => false)); ?></td>
                </tr>
                <tr>
                    <td colspan="3" id="data_type_list" style="vertical-align: top;"></td>
                </tr>
                <tr style="display: none;">
                    <td colspan="3" id="data_types"></td>
                </tr>
                <tr>
                    <td>From</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        echo $this->Form->input('AdminModulePeriodList.from_date', array('type' => 'hidden', 'id' => 'from_date_alt', 'label' => false, 'div' => false))
                        . " <input type='text' id='from_date' class='date_picker' />"
                        . "<strong id='to_date'> </strong>";
                        ?>
                    </td>
                </tr>
                <tr style="display: none;">                    
                    <td class="inputTd" colspan="3" style="padding-left:100px;">
                        <input type="checkbox" name="data[AdminModulePeriodDetail][is_current_period][]" value="0" id="AdminModulePeriodDetailIsCurrentPeriod1" /><label for="AdminModulePeriodDetailIsCurrentPeriod1">Check to Set as Current Period</label>
                    </td>
                </tr>
            </table>
        </div>        
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'AdminModulePeriodDetails', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?> 
                    </td>
                    <td style="text-align: center;">
                        <?php
                        echo $this->Js->submit('Save', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been added successfully.');",
                            'error' => "msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
<script>

    $(function () {
        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});
        
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();
        $('.date_picker').each(function () {
            $(this).datepicker({
                yearRange: 'c-5:c+5',
                dateFormat: 'dd-mm-yy',
                altField: '#' + this.id + '_alt',
                altFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'both',
                buttonImageOnly: true,
                buttonImage: 'img/calendar.gif',
                buttonText: 'Click to show the calendar'
            });
        });

    });

</script>

<?php
$this->Js->get('.date_picker')->event('change', $this->Js->request(array(
            'controller' => 'AdminModulePeriodDetails', 'action' => 'get_to_date'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#to_date',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => '$(this).closest("form").serialize()'
        ))
);

$this->Js->get('#user_types')->event('change', $this->Js->request(array(
            'controller' => 'AdminModulePeriodDetails', 'action' => 'period_type_select'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut(); $("#to_date").empty();',
            'update' => '#period_types',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);

$this->Js->get('#user_types')->event('change', $this->Js->request(array(
            'controller' => 'AdminModulePeriodDetails', 'action' => 'show_data_type_list'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#data_type_list',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
$this->Js->get('#period_types')->event('change', $this->Js->request(array(
            'controller' => 'AdminModulePeriodDetails', 'action' => 'show_data_type_list'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut(); $("#to_date").empty();',
            'update' => '#data_type_list',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
$this->Js->get('#user_types')->event('change', $this->Js->request(array(
            'controller' => 'AdminModulePeriodDetails', 'action' => 'data_type_select'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#data_types',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
$this->Js->get('#period_types')->event('change', $this->Js->request(array(
            'controller' => 'AdminModulePeriodDetails', 'action' => 'data_type_select'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#data_types',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);

if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
    echo $this->Js->writeBuffer();
?>