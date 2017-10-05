<div id="frmTypeOfOrg_view">
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
        <legend><?php echo $title; ?></legend>

        <div class="form">
            <table>
                <tr>
                    <td style="text-align: justify;font-family: verdana,helvetica,arial;">
                        <?php
                        echo $this->Form->create('AdminModulePeriodDetail');
                        $selected_period_type_id = '';
                        $selected_from_date = '';
                        if (!empty($period_type_id)) {
                            $selected_period_type_id = $period_type_id;
                        }
                        if (!empty($from_date)) {
                            $selected_from_date = $from_date;
                        }
                        ?>
                        <table cellpadding="0" cellspacing="5" border="0">
                            <tr>
                                <td>Type of User</td>
                                <td class="colons">:</td>
                                <td ><?php echo $this->Form->input('user_group_id', array('type' => 'select', 'id' => 'user_types', 'options' => $user_group_options, 'empty' => '---Select---', 'label' => false, 'div' => false)); ?></td>
                            </tr>
                            <tr>
                                <td>Type of Period</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('period_type_id', array('type' => 'select', 'id' => 'period_types', 'options' => 'null', 'value' => $selected_period_type_id, 'empty' => '---Select---', 'label' => false, 'div' => false)); ?></td>
                            </tr>                            
                            <tr>
                                <td style="padding-left:15px; text-align:right;" >Period</td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('period_id', array('type' => 'select', 'id' => 'periods', 'options' => 'null', 'value' => $selected_from_date, 'empty' => '---Select---', 'label' => false, 'div' => false)); ?></td>
                            </tr>
                            <tr>                                
                                <td colspan="3">
                                    <table>
                                        <tr>
                                            <td><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('url' => "/AdminModulePeriodDetails/view/custom", 'class' => 'btnsearch'))); ?></td>
                                            <td><?php echo $this->Js->submit('View All', array_merge($pageLoading, array('url' => "/AdminModulePeriodDetails/view/all", 'class' => 'btnsearch'))); ?></td>
                                            <td><?php echo $this->Js->submit('Current Periods', array_merge($pageLoading, array('url' => "/AdminModulePeriodDetails/view/current", 'class' => 'btnsearch'))); ?></td>                                            
                                        </tr>
                                    </table>                                    
                                </td>                          
                            </tr>
                        </table>
                        <?php echo $this->Form->end(); ?> 
                    </td>        
                </tr>
                <tr>
                    <td>
                        <div id="searching" style="width:780px;">              
                            <table class="view">
                                <tr>
                                    <?php
                                    echo "<th style='width:150px;'>" . $this->Paginator->sort('AdminModulePeriodDataType.data_types', 'Data Type') . "</th>";
                                    echo "<th style='width:75px;'>" . $this->Paginator->sort('AdminModulePeriodType.period_types', 'Period Type') . "</th>";
                                    echo "<th style='width:160px;text-align: justify;'>" . $this->Paginator->sort('AdminModulePeriodList.period', 'Period') . "</th>";
                                    echo "<th style='width:150px;'>" . $this->Paginator->sort('AdminModuleUserGroup.group_name', 'User Type') . "</th>";
                                    echo "<th style='width:85px;'>" . $this->Paginator->sort('AdminModulePeriodDetail.is_current_period', 'Status') . "</th>";
                                    echo "<th style='width:105px;'>Action</th>";
                                    ?>
                                </tr>
                                <?php foreach ($values as $value) { ?>
                                    <tr>
                                        <td style="text-align: justify;"><?php echo $value['AdminModulePeriodDataType']['data_types']; ?></td> 
                                        <td style="text-align: justify;"><?php echo $value['AdminModulePeriodType']['period_types']; ?></td>  
                                        <td style="text-align: justify;"><?php echo $value['AdminModulePeriodList']['period']; ?></td>                                    
                                        <td style="text-align: justify;"><?php echo $value['AdminModuleUserGroup']['group_name']; ?></td>
                                        <td style="text-align: justify;">
                                            <?php
                                            $btnSetPeriod = "";
                                            if ($value['AdminModulePeriodDetail']['is_current_period'] == '1') {
                                                echo 'Current';
                                            } else {
                                                $btnSetPeriod = $this->Js->link('Set as Current', array('controller' => 'AdminModulePeriodDetails', 'action' => 'set_current_period', $value['AdminModulePeriodDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'width:82px;', 'success' => "msg.init('success', '$title', 'Data Period Set as Current Successfully.');", 'error' => "msg.init('error', '$title', 'Update failed!');")));
                                            }
                                            ?>
                                        </td>        
                                        <td><?php echo $this->Js->link('Details', array('controller' => 'AdminModulePeriodDetails', 'action' => 'preview', $value['AdminModulePeriodDetail']['id']), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink', 'style' => 'width:82px;'))) . $btnSetPeriod; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table> 
                        </div>
                    </td>                
                </tr>
            </table>
        </div>
        <?php if ($values && $this->Paginator->param('pageCount') > 1) { ?>
            <div class="paginator">
                <?php
                if ($this->Paginator->param('pageCount') > 5) {
                    echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                    $this->Paginator->last('>>', array('class' => 'nextPg', 'title' => 'Goto last page.'), null, array('class' => 'nextPg no_link'));
                } else {
                    echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                }
                ?>
            </div>
        <?php } ?> 
        <div class="btns-div">  
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php echo $this->Js->link('Add New', array('controller' => 'AdminModulePeriodDetails', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => ''))); ?>     
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>         
    </fieldset>
</div>  

<script>
    $(document).ready(function () {
        $(".paging a").click(function () {
            $("#ajax_div").load(this.href);
            return false;
        });
        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});
    });

    $(function () {
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
$this->Js->get('#user_types')->event('change', $this->Js->request(array(
            'controller' => 'AdminModulePeriodDetails', 'action' => 'period_type_select'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
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
$this->Js->get('#user_types')->event('change', $this->Js->request(array(
            'controller' => 'AdminModulePeriodDetails', 'action' => 'period_select'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#periods',
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
            'controller' => 'AdminModulePeriodDetails', 'action' => 'period_select'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#periods',
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