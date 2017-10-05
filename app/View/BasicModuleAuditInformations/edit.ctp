<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (!empty($IsValidUser)) {
    $title = "Audit Information (Edit)";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?> 

    <div>
        <fieldset>
            <legend><?php echo $title; ?></legend>         
    <?php echo $this->Form->create('BasicModuleAuditInformation'); ?>

            <div class="form">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>Name of Organization</td>
                        <td class="colons">:</td>
                        <td>
                            <?php
                            if (!empty($org_id))
                                echo $this->Form->input('org_id', array('type' => 'text', 'value' => $orgNameOptions[$org_id], 'disabled' => 'disabled', 'label' => false));
                            else
                                echo $this->Form->input('org_id', array('type' => 'select', 'options' => $orgNameOptions, 'value' => $org_id, 'empty' => '---Select---', 'label' => false));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Has External Audit been Carried Out Previously</td>
                        <td class="colons">:</td>
                        <td> <?php echo $this->Form->input('external_audit_carried_out_id', array('type' => 'radio', 'options' => $options, 'id'=>'external_audits', 'label' => false, 'legend' => false, 'div' => false)); ?></td>
                    </tr>                    
                    <tr id="if_external_audit_carried_out">
                        <td colspan="3">
                            <table cellpadding="0" cellspacing="0" border="0">                                
                                <tr>
                                    <td>Name of the Audit Firm</td>
                                    <td class="colons" style="padding-left: 226px;">:</td>
                                    <td><?php echo $this->Form->input('name_of_the_audit_firm', array('type' => 'text','label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top;">Address</td>
                                    <td class="colons" style="padding-left: 226px;vertical-align: top;">:</td>
                                    <td style="vertical-align: top;"><?php echo $this->Form->input('address', array('type' => 'textarea', 'escape' => false,'rows' => '5', 'cols' => '5', 'label' => false));?></td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td class="colons" style="padding-left: 226px;">:</td>
                                    <td><?php echo $this->Form->input('phone', array('type' => 'text','label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Fax</td>
                                    <td class="colons" style="padding-left: 226px;">:</td>
                                    <td><?php echo $this->Form->input('fax', array('type' => 'text','label' => false)); ?></td>
                                </tr>
                                <tr>
                                    <td>E-Mail</td>
                                    <td class="colons" style="padding-left: 226px;">:</td>
                                    <td><?php echo $this->Form->input('email', array('type' => 'text','label' => false)); ?></td>
                                </tr>                                
                            </table>
                        </td>                        
                    </tr>                    
                </table>
            </div>

            <div class="btns-div"> 
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>
                        <td></td>
                        <td>
                            <?php
                            $data_mode = $this->Session->read('Data.Mode');
                            $isNew = empty($data_mode) || $data_mode == 'insert';

                            if ($isNew) {
                                echo $this->Js->submit('Save', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been added successfully.');",
                                    'error' => "msg.init('error', '$title', '$title has been failed to add !');")));
                            } 
                            else {
                                echo $this->Js->submit('Update', array_merge($pageLoading, array('update' => '#popup_div',
                                    'success' => "msg.init('success', '$title', '$title has been update successfully.');",
                                    'error' => "msg.init('error', '$title', '$title has been failed to update !');")));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Js->link('Close', array('controller' => 'BasicModuleAuditInformations', 'action' => 'view'), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!$isNew) {
                                echo $this->Js->link('Next', array('controller' => 'BasicModuleAuditInformations', 'action' => 'add'), array_merge($pageLoading, array('success' => 'msc.next();')));
                            }
                            ?>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>

        <?php echo $this->Form->end(); ?>
        </fieldset>

    </div>

    <?php
    if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer'))
        echo $this->Js->writeBuffer();
    ?>

<?php } ?>
<script>    
    jQuery('.creload').on('click', function() {
        var mySrc = $(this).prev().attr('src');
        var glue = '?';
        if(mySrc.indexOf('?')!=-1)  {
            glue = '&';
        }
        $(this).prev().attr('src', mySrc + glue + new Date().getTime());
        return false;
    });
    $(document).ready(function(){
        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});
        
        $('#external_audits1').click(function () {
            $("#if_external_audit_carried_out").show();                       
        });
        $('#external_audits2').click(function () {
            $("#if_external_audit_carried_out").hide();
        });                
    });
    
    $(function() {        
        $('#ui-datepicker-div').remove();
        $('#ui-datepicker-div').empty();        
        $('.date_picker').each(function() {
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
