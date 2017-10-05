<div id="frmStatus_add">
    <?php 
        if (!empty($msg)) {
            if (is_array($msg)) {
                echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
            } else {
                echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
            }
        }
        $title = "Select Organization according to Supervision Case";     
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend>
            <?php echo $title; ?>            
        </legend> 
        <?php  echo $this->Form->create('SupervisionModuleOrgSelectionDetail'); ?>
        <div class="form">           
            <table cellpadding="3" cellspacing="7">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        if (!empty($org_id))
                            echo $this->Form->input('org_id', array('type' => 'text', 'value' => $org_name_options[$org_id], 'disabled' => 'disabled', 'label' => false, 'div' => false));
                        else
                            echo $this->Form->input('org_id', array('type' => 'select', 'options' => $org_name_options, 'value' => $org_id, 'empty' => '---Select---', 'label' => false, 'div' => false));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Supervision Case/Category</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('supervision_category_id', array('type' => 'select', 'options' => $case_category_options, 'id'=>'case_categories', 'empty' => '---Select---', 'label' => false, 'div' => false)); ?></td>
                </tr>
                <tr id="other_case">
                    <td>Case Title</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('supervision_other_case_title', array('type'=>'text', 'label'=>false, 'div' => false)); ?></td>
                </tr>
                <tr>
                    <td valign="top">Supervision Reason</td>
                    <td style="font-weight:bold; padding-left: 7px;" valign="top">:</td>
                    <td><?php echo $this->Form->input('supervision_reason', array('type' => 'textarea', 'escape' => false, 'div' => false, 'label' => false, 'style' => 'width: 375px; height:200px;')); ?></td>
                </tr>
                <tr>
                    <td>From</td>
                    <td class="colons">:</td>
                    <td ><?php echo $this->Form->input('from_date', array('type' => 'hidden', 'id' => 'from_date_alt', 'label' => false, 'div' => false))
                                    . " <input type='text' id='from_date' class='date_picker' />"; ?>
                    </td>
                </tr>
            </table>
        </div>        
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php echo $this->Js->link('Close', array('controller' => 'SupervisionModuleOrgSelectionDetails','action' => 'view'),array_merge($pageLoading, array('class'=>'mybtns'))); ?>
                    </td>
                    <td style="text-align: center;">
                        <?php
                            echo $this->Js->submit('Save', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', 'Organization with case has been added successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
     <?php  echo $this->Form->end(); ?>
    </fieldset>
</div>
<script>
    $(document).ready(function () {
        $('.integers').numeric({decimal: false, negative: false});
        $('.decimals').numeric({decimal: ".", negative: false});        
        selectedVal = $( "#case_categories option:selected" ).val();
        if(selectedVal==""){
           $("#other_case").hide(); 
        }
        $('#case_categories').change(function(){
            if ($(this).val()=="5"){
               $("#other_case").show(); 
            }            
            else {
               $("#other_case").hide();     
            }
        });        
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