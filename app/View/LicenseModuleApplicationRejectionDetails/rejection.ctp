<?php

if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = 'Application Rejection Details';
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    
    
//    $controller = 'LicenseModuleInitialAssessmentAdminApprovalDetails';
//    $action = 'view';
//    $parameters = null;
    
?>

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend>

            <?php echo $this->Form->create('LicenseModuleApplicationRejectionHistoryDetail'); ?>

            <div class="form">
                <table cellpadding="5" cellspacing="5" border="0" style="width:95%;">

                    <tr>
                        <td style="width:160px;">Form No.</td>
                        <td class="colons">:</td>
                        <td>
                            <?php 
                            echo $this->Form->input('org_id', array('type' => 'hidden', 'value' => $org_id, 'label' => false, 'div' => false));
                            
                            echo '<span style="float:left; margin-top:7px; font-weight:bold;">' . $orgDetail['BasicModuleBasicInformation']['form_serial_no'] . '</span>';
                            
                            echo $this->Js->link('Rejection History', array('action' => 'rejection_history_preview', $org_id),
                                    array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'float:right; display:inline-block;', 'update' => '#popup_div')));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            $mfiName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];
                            $mfiShortName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];

                            echo $mfiName . ((!empty($mfiName) && !empty($mfiShortName)) ? " (<strong>" . $mfiShortName . "</strong>)" : $mfiShortName);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Rejection Type</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo '<div style="margin-left:-8px; padding:0;">'
                                    . $this->Form->input("rejection_type_id", array('type' => 'radio', 'id' => 'types', 'class' => 'types', 'options' => $rejection_type_options, 'default' => $rejection_type_id, 'legend' => false, 'div' => false))
                                    . '</div>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Rejection Category</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('rejection_category_id', array('type' => 'select', 'id' => 'categories', 'options' => $rejection_category_options, 'empty' => '-----Select-----', 'label' => false, 'div' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Rejection Reason</td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('rejection_reason_id', array('type' => 'select', 'id' => 'reasons', 'options' => $rejection_reason_options, 'empty' => '-----Select-----', 'label' => false, 'div' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Date Of Rejection</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo $this->Time->format(new DateTime('now'), '%d-%m-%Y', '');
                            echo $this->Form->input('rejection_date', array('type' => 'hidden', 'value' => date("Y-m-d"), 'label' => false, 'div' => false));
                            ?>
                        </td>
                    </tr>
                    <tr class="for_appealable_rejection" <?php echo (!empty($rejection_type_id) && $rejection_type_id == 2) ? ' style="display:none;"' : '' ?>>
                        <td>Appeal Deadline Date(s)</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo $this->Form->input('appeal_deadline_days', array('type' => 'text', 'id' => 'appeal_deadline_days', 'class' => 'integers', 'style' => 'width:85px; padding:3px;', 'label' => false, 'div' => false));
                            ?>
                            <span style="color:red;"> (with off days)</span>
                        </td>
                    </tr>
                    <tr class="for_appealable_rejection" <?php echo (!empty($rejection_type_id) && $rejection_type_id == 2) ? ' style="display:none;"' : '' ?>>
                        <td>Appeal Deadline Date</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            echo $this->Form->input('deadline_date', array('type' => 'hidden', 'id' => 'txtDeadlineDate_alt', 'label' => false, 'div' => false))
                            . "<input type='text' id='txtDeadlineDate' class='date_picker' style='margin:0 3px 0 0;' />";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; padding-top:3px;">Rejection Message</td>
                        <td class="colons" style="vertical-align:top;">:</td>
                        <td style="padding-top:3px;">
                            <?php
                            echo $this->Form->input('rejection_msg', array('type' => 'textarea', 'escape' => false, 'label' => false, 'div' => false));
                            ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="btns-div">
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td>
                            <?php
                            echo $this->Js->link('Close', $redirect_url, array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Close ?')));
                            ?> 
                        </td>
                        <td style="text-align:center;">
                            <?php
                            echo $this->Js->submit('Reject and Notify', array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to reject this Organization ?',
                                                            'success' => "msg.init('success', '$title', '$title has been successfully completed.');",
                                                            'error' => "msg.init('error', '$title', '$title failed !');")));
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
                
            <?php echo $this->Form->end(); ?>

        </fieldset>
    </div>

<?php } ?>



<script type="text/javascript">
    
//    function pageLoad() {
//         
//        $('#txtDeadlineDate').datepicker({
//            showAnim: 'show',
//            dateFormat: 'dd-mm-yy',
//            altField: '#txtDeadlineDate_alt',
//            altFormat: "yy-mm-dd",
//            minDate: '0',
//            showOtherMonths: true,
//            showOn: 'both',
//            buttonImageOnly: true,
//            buttonImage: 'img/calendar.gif',
//            buttonText: 'Click to show the calendar',
//            onSelect: function() {
//                if(!$(this).datepicker('getDate')) {
//                    $('#appeal_deadline_days').val('');
//                    return;
//                }
//                var days = Math.ceil(($(this).datepicker('getDate') - new Date()) / (1000 * 60 * 60 * 24));
//                $('#appeal_deadline_days').val(days);
//            }
//        });
//    }
    
    $(document).ready(function () {
        $('.integers').numeric({decimal: false, negative: false});
        
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();
        $('#txtDeadlineDate').datepicker({
            showAnim: 'show',
            dateFormat: 'dd-mm-yy',
            altField: '#txtDeadlineDate_alt',
            altFormat: "yy-mm-dd",
            minDate: '0',
            showOtherMonths: true,
            showOn: 'both',
            buttonImageOnly: true,
            buttonImage: 'img/calendar.gif',
            buttonText: 'Click to show the calendar',
            onSelect: function() {
                if(!$(this).datepicker('getDate')) {
                    $('#appeal_deadline_days').val('');
                    return;
                }
                var days = Math.ceil(($(this).datepicker('getDate') - new Date()) / (1000 * 60 * 60 * 24));
                $('#appeal_deadline_days').val(days);
            }
        });
        
        $('#appeal_deadline_days').on('keyup change', function() {
            var days = $(this).val();
            if(days == '') return;
            
            var deadline_date = new Date();
            deadline_date.setDate(deadline_date.getDate() + +days);
            $('#txtDeadlineDate').datepicker("setDate", deadline_date);
            $('#txtDeadlineDate').datepicker("altFormat", "yy-mm-dd");
        });
        
        $("input:radio.types").on('change', function() {
            if($(this).attr("value") != '1')
                $(".for_appealable_rejection").hide();
            else
                $(".for_appealable_rejection").show();
        });
        
    });
    
</script>


<?php

    $this->Js->get('.types')->event('change', $this->Js->request(
            array('controller' => 'LookupLicenseApplicationRejectionCategories', 'action' => 'selected_categories'), 
            array('update' => '#categories', 'async' => true, 'method' => 'post',
                    'dataExpression' => true, 'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true)))
        )
    );

    $this->Js->get('.types')->event('change', $this->Js->request(
            array('controller' => 'LookupLicenseApplicationRejectionReasons', 'action' => 'selected_reasons'), 
            array('update' => '#reasons', 'async' => true, 'method' => 'post',
                    'dataExpression' => true, 'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true)))
        )
    );

    $this->Js->get('#categories')->event('change', $this->Js->request(
            array('controller' => 'LookupLicenseApplicationRejectionReasons', 'action' => 'selected_reasons'), 
            array('update' => '#reasons', 'async' => true, 'method' => 'post',
                    'dataExpression' => true, 'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true)))
        )
    );

    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
    
    
?>
