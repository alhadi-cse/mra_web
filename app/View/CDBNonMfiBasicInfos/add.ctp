<?php
//echo $this->element('contentheader', array("variable_name"=>"current"));
?>

<div id="frmBasicInfo_add">
    <?php
    $title = "Add Primary Information";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 'class' => 'mybtns',
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <?php echo $this->Form->create('CDBNonMfiBasicInfo'); ?>

        <div class="form">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>Agency Name</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('name_of_org', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Type of Agency</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('type_id', array('type' => 'select', 'class' => 'medium', 'options' => $non_mfi_types, 'empty' => '----- Select -----', 'label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Ministry/Authority</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('ministry_or_authority_id', array('type' => 'select', 'class' => 'medium', 'options' => $ministry_or_authority_options, 'empty' => '----- Select -----', 'label' => false)); ?></td>
                </tr>
<!--                <tr>
                    <td>Registration No.</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php //echo $this->Form->input('registration_no', array('label' => false)); ?></td>
                </tr>-->
                <tr>
                    <td>Name of Reporting Officer</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('name_of_officer', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Designation</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('designation_of_officer', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Contract Number</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('contract_no_of_officer', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Alternative Officer Name</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('name_of_alt_officer', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Designation</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('designation_of_alt_officer', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Contract Number</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('contract_no_of_alt_officer', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Head Office Mailing Address</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('head_office_address', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('email', array('label' => false)); ?></td>
                </tr>
                <tr>
                    <td>Fax</td>
                    <td style="width:10px; text-align:right; font-weight:bold;">:</td>
                    <td><?php echo $this->Form->input('fax', array('label' => false)); ?></td>
                </tr>
            </table>
        </div>
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="5">
                <tr>
                    <td></td>
                    <td>
                        <?php
                        echo $this->Js->submit('Save', array_merge($pageLoading, array('success' => "msg.init('success', '$title', '$title has been added successfully.');",
                            'error' => "msg.init('error', '$title', '$title has been failed to add !');")));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'CDBNonMfiBasicInfos', 'action' => 'view', 'all'), array_merge($pageLoading, array('confirm' => 'Are you sure to close ?')));
                        ?>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>