
<?php

//echo $this->Html->script("map/highmaps");
//echo $this->Html->script("map/highcharts-more");

//echo $this->Html->script("map/high-maps");
echo $this->Html->script("chart/high-charts");
echo $this->Html->script("map/high-map");


if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$isAdmin = (!empty($user_group_id) && $user_group_id == 1);

$title = "Report/Query Generator";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
?>

<fieldset>
    <legend><?php echo $title; ?></legend>
    <?php //if ($isAdmin) {  }  ?>

    <div id="data_div" style="width:100%; height:auto; overflow-x:auto;">

        <div id="basic_opt_bg" class="modal-bg">

            <div id="basic_opt" class="modal-content">

                <div id="basic_title" class="modal-title">
                    <span class="modal-title-txt">Basic Selection</span>
                    <button class="close" onclick="if (confirm('Are you sure to Cancel ?'))
                                modal_close('basic_opt');
                            return false;">✖</button>
                </div>

                <?php echo $this->Form->create('ReportQueryGeneratorOrgSelect'); ?>
                <div style="width:auto; height:auto; max-height:530px; max-height:90vh; margin:0; padding:7px; overflow:auto; cursor:default;">

                    <table>
                        <tr>
                            <td style="vertical-align:top;">
                                <fieldset style="margin:0 5px 5px;">
                                    <legend>Organization List</legend>

                                    <div id="divOrg" style="width:auto; height:100%; min-width:250px; min-height:350px; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">
                                        <?php echo $this->Form->input("listOrg", array('id' => 'listOrg', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox org-list', 'options' => $org_list, 'escape' => false, 'div' => false, 'label' => false)); ?>
                                    </div>
                                </fieldset>
                            </td>

                            <td style="vertical-align:top;">
                                <fieldset style="margin:0 5px 5px;">
                                    <legend>Branch List</legend>

                                    <div id="divBranch" style="width:auto; height:100%; min-width:250px; min-height:350px; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">
                                    </div>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="btns-div" style="margin-top:0;">
                    <table style="min-width:170px; margin:0 auto; padding:0;" cellspacing="5">
                        <tr>
                            <td></td>
                            <td>
                                <?php
                                echo $this->Js->submit('Close', array_merge($pageLoading, array('update' => '#geneQuery', //#geneQuery
                                    'url' => array('controller' => 'ReportQueryGenerators', 'action' => 'basic_selection', 0),
                                    'confirm' => 'Are you sure to Cancel the Selection ?',
                                    'success' => "modal_close('basic_opt');"
                                )));
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Js->submit('Done', array_merge($pageLoading, array('update' => '#',
                                    'url' => array('controller' => 'ReportQueryGenerators', 'action' => 'basic_selection', 1),
                                    'confirm' => 'Are you sure to Selection ?',
                                    'success' => "msg.init('success', 'Basic Selection', 'Selection has been completed.'); modal_close('basic_opt');",
                                    'error' => "msg.init('error', 'Basic Selection', 'Selection failed !');")));
                                ?>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>

                <?php echo $this->Form->end(); ?>

            </div>

        </div>
        <button id="myBasicSelect" class="my-btns" onclick="modal_open('basic_opt'); return false;">Basic Selection</button>


        <div id="admin_opt_bg" class="modal-bg">

            <div id="admin_opt" class="modal-content">

                <div id="admin_title" class="modal-title">
                    <span class="modal-title-txt">Admin Boundary Selection</span>
                    <button class="close" onclick="if (confirm('Are you sure to Close ?'))
                                modal_close('admin_opt');
                            return false;">✖</button>
                </div>

                <div style="width:auto; height:auto; max-height:520px; max-height:90vh; margin:0; padding:7px; overflow:auto; cursor:default;">

                    <table style="width:100%;">
                        <tr>
                            <td style="vertical-align:top;">
                                <fieldset style="margin:0 5px 5px;">
                                    <legend>Division</legend>
                                    <div id="divDiv" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">
                                        <?php
                                        echo $this->Form->create('ReportQueryGeneratorDivSelect');
                                        echo $this->Form->input("listDiv", array('id' => 'listDiv', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox div-list', 'options' => $div_list, 'escape' => false, 'div' => false, 'label' => false));
                                        echo $this->Form->end();
                                        ?>
                                    </div>
                                </fieldset>
                            </td>

                            <td style="vertical-align:top;">
                                <fieldset style="margin:0 5px 5px;">
                                    <legend>District</legend>
                                    <div id="divDist" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">

                                    </div>
                                </fieldset>
                            </td>

                            <td style="vertical-align:top;">
                                <fieldset style="margin:0 5px 5px;">
                                    <legend>Upazila</legend>
                                    <div id="divUpaz" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">

                                    </div>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="btns-div" style="margin-top:0;">
                    <table style="min-width:170px; margin:0 auto; padding:0;" cellspacing="5">
                        <tr>
                            <td></td>
                            <td>
                                <?php
//                                echo $this->Js->submit('Close', array_merge($pageLoading, array('update' => '#geneQuery', //#geneQuery
//                                    'url' => array('controller' => 'ReportQueryGenerators', 'action' => 'basic_selection', 0),
//                                    'confirm' => 'Are you sure to cancel selection ?',
//                                    'success' => "modal_close('basic_opt');"
//                                )));
                                ?>
                            </td>
                            <td>
                                <?php
//                                echo $this->Js->submit('Done', array_merge($pageLoading, array('update' => '#',
//                                    'url' => array('controller' => 'ReportQueryGenerators', 'action' => 'basic_selection', 1),
//                                    'confirm' => 'Are you sure to Selection ?',
//                                    'success' => "msg.init('success', 'Basic Selection', 'Selection has been completed.'); modal_close('basic_opt');",
//                                    'error' => "msg.init('error', 'Basic Selection', 'Selection failed !');")));
                                ?>

                                <button class="modal-close" onclick="if (confirm('Are you sure to Close ?'))
                                            modal_close('admin_opt');
                                        return false;">Close</button>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <button id="myAdminSelect" class="my-btns" onclick="modal_open('admin_opt'); return false;">Admin Selection</button>



        <?php echo $this->Form->create('ReportQueryGeneratorSelect'); ?>

        <table>

            <tr>
                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>Table Name</legend>
                        <div id="divTable" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">
                            <?php
                            echo $this->Form->create('ReportQueryGeneratorTableSelect');
                            echo $this->Form->input("listTable", array('id' => 'listTable', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox tbl-list', 'options' => $model_list, 'escape' => false, 'div' => false, 'label' => false));
                            echo $this->Form->end();
                            ?>
                        </div>
                    </fieldset>
                </td>
                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>Fields</legend>
                        <!--<div id="divField" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">-->
                        <div id="divField" style="width:auto; height:100%; padding:2px 3px;">
                            <?php
//                            echo $this->Form->create('ReportQueryGeneratorFieldSelect');
//                            echo $this->Form->input("listField", array('id' => 'listField', 'type' => 'select', 'multiple' => 'checkbox', 'class' => 'multi-checkbox', 'options' => $field_list, 'escape' => false, 'div' => false, 'label' => false));
//                            echo $this->Form->end();
                            ?>
                        </div>
                    </fieldset>
                </td>
                <td style="vertical-align:top;">
                    <fieldset style="margin:0 5px 5px;">
                        <legend>Options</legend>
                        <div id="listOption" style="width:auto; height:100%; min-width:150px; min-height:300px; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">
                            Operation:
                            <div>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="button" id="btn_equal" class="opt-btns" value="=">
                                        </td>
                                        <td>
                                            <input type="button" id="btn_geter_equal" class="opt-btns" value=">=">
                                        </td>
                                        <td>
                                            <input type="button" id="btn_less_equal" class="opt-btns" value="<=">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </td>
            </tr>

            <tr>
                <td colspan="3" style="vertical-align:top; display:none;">
                    <fieldset style="margin:5px; width:auto;">
                        <legend>Report/Query</legend>
                        <div id="geneQuery" style="width:auto; height:100%; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">

                        </div>
                    </fieldset>
                </td>
            </tr>


            <tr>
                <td colspan="3" style="vertical-align:top;">
                    <fieldset style="margin:5px; width:auto;">
                        <legend>Report/Query</legend>
                        <div id="geneQueryTest" style="width:auto; height:100%; max-height:500px; max-height:85vh; padding:2px 3px; resize:both; overflow:auto;">

                        </div>
                    </fieldset>
                </td>
            </tr>
            
        </table>

        <?php echo $this->Form->end(); ?>

    </div>


<!--    <div class="chart">

        <div id="line_chart1" style="display:block; float:left; width:90%; margin-bottom:20px;"></div>

        <?php // echo $this->Highcharts->render($chartNameOne); ?>	

    </div>

    <div class="chart">

        <div id="bar_chart1" style="display:block; float:left; width:90%; margin-bottom:20px;"></div>

        <?php // echo $this->Highcharts->render($chartNameTwo); ?>

    </div>

    <div class="chart">

        <div id="pie_chart1" style="display:block; float:left; width:90%; margin-bottom:20px;"></div>

        <?php // echo $this->Highcharts->render($chartNameThree); ?>

    </div>-->


<!--    <div id="line_chart0" style="min-width:350px; height:400px; margin:0 auto"></div>

    <div id="line_chart11" style="min-width:350px; height:400px; margin:0 auto"></div>
    <div id="pie_chart11" style="min-width:350px; height:400px; margin:0 auto"></div>
    <div id="bar_chart11" style="min-width:350px; height:400px; margin:0 auto"></div>

    <div id="bar_chart_test" style="min-width:350px; height:400px; margin:0 auto"></div>-->
</fieldset>


<?php
$this->Js->get('.org-list')->event('change', $this->Js->request(
                array('controller' => 'ReportQueryGenerators', 'action' => 'selected_org_branches'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#divBranch',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => '$(this).closest("form").serialize()'
        ))
);

$this->Js->get('.tbl-list')->event('change', $this->Js->request(
                array('controller' => 'ReportQueryGenerators', 'action' => 'selected_model_fields'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#divField',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => '$(this).closest("form").serialize()'
        ))
);

$this->Js->get('.div-list')->event('change', $this->Js->request(
                array('controller' => 'ReportQueryGenerators', 'action' => 'selected_admin_dists'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#divDist',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => '$(this).closest("form").serialize()'
        ))
);

$this->Js->get('.div-list')->event('change', $this->Js->request(
                array('controller' => 'ReportQueryGenerators', 'action' => 'selected_admin_upzas'), array(
            'beforeSend' => '$("#busy-indicator").fadeIn();',
            'complete' => '$("#busy-indicator").fadeOut();',
            'update' => '#divUpaz',
            'async' => true,
            'method' => 'post',
            'dataExpression' => true,
            'data' => '$(this).closest("form").serialize()'
        ))
);

//
//$this->Js->get('.dist-list')->event('change', $this->Js->request(
//        array('controller' => 'ReportQueryGenerators', 'action' => 'selected_admin_upzas'), 
//        array(
//            'beforeSend' => '$("#busy-indicator").fadeIn();',
//            'complete' => '$("#busy-indicator").fadeOut();',
//            'update' => '#divUpaz',
//            'async' => true,
//            'method' => 'post',
//            'dataExpression' => true,
//            'data' => '$(this).closest("form").serialize()'
//        ))
//);
//$actionLink = array('controller' => 'ReportQueryGenerators', 'action' => 'org_branch');
//    $updateDiv = '#';
//
//    $this->Js->get('.org_branch-list')->event('change', 'CheckMultiCheckBox(this);');
//    $this->Js->get('.org_branch-list')->event('change', $this->Js->request($actionLink, array(
//                'beforeSend' => '$("#busy-indicator").fadeIn();',
//                'complete' => '$("#busy-indicator").fadeOut();',
//                'update' => $updateDiv,
//                'async' => true,
//                'method' => 'post',
//                'dataExpression' => true,
//                'data' => '$(this).closest("form").serialize()'
//            ))
//    );
//    
?>

<script>
    
    function get_total_by_class(className) {
        var total = 0;
        $('.' + className).each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        return total;
    }

    function set_total_by_class(sumFieldId, className) {
        var total = 0;
        $('.' + className).each(function () {
            total += parseFloat($(this).val()) || 0;
        });

        $('#' + sumFieldId).val(total);
        return;
    }


    $(document).ready(function () {
        draggable_modal('basic_title', 'basic_opt', 'basic_opt_bg');
        draggable_modal('admin_title', 'admin_opt', 'admin_opt_bg');
    });


//    function modal_open(content) {
//        $("#" + content).css({top: '-350px', left: 0, opacity: 0});
//        $("#" + content + "_bg").fadeIn(350, function () {
//            $("#" + content).animate({top: '0', opacity: 1}, 500);
//        });
//    }
//
//    function modal_close(content) {
//        $("#" + content).animate({top: '-350px', opacity: 0}, 500, function () {
//            $("#" + content + "_bg").fadeOut(350);
//        });
//    }

    function CheckAllMultiCheckBox(obj) {
        $(obj).closest("fieldset").find(".multi-checkbox input:checkbox").prop("checked", $(obj).prop("checked"));
    }

    function CheckMultiCheckBox(obj) {
        var $chkbParent = $(obj).closest("fieldset");
        var noOfCheckBoxes = $chkbParent.find(".multi-checkbox input:checkbox").length;
        if (noOfCheckBoxes === 0)
            return false;
        var optChecked = null;
        if ($(this).prop("checked")) {
            var noOfCheckedBoxes = $chkbParent.find(".multi-checkbox input:checkbox:checked").length;
            if (noOfCheckedBoxes === 0) {
                optChecked = false;
            }
            if (noOfCheckedBoxes === noOfCheckBoxes) {
                optChecked = true;
            }
        } else {
            var noOfUnCheckedBoxes = $chkbParent.find(".multi-checkbox input:checkbox:not(:checked)").length;
            if (noOfUnCheckedBoxes === 0) {
                optChecked = true;
            }
            if (noOfUnCheckedBoxes === noOfCheckBoxes) {
                optChecked = false;
            }
        }

        $chkbParent.find("input:checkbox.all-checked").prop("indeterminate", optChecked === null);
        $chkbParent.find("input:checkbox.all-checked").prop("checked", optChecked);
        return false;
    }

</script>
