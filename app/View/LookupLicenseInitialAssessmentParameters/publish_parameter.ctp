<div>
    <fieldset>
        <legend><?php $title = 'Publish Parameter'; echo $title; ?></legend>
        
        <?php echo $this->Form->create('LookupLicenseInitialAssessmentParameter'); ?>
        
            <table cellpadding="0" cellspacing="0" border="0" class="view">                
                <tr>
                    <th style="width:47px;">Order</th>
                    <th>Name of Parameter</th>
                    <th style="width:110px;">Status</th>
                </tr>
                <?php
                $i = 0; 
                foreach ($parameterList as $paramList) {
                    $sorting_order = $paramList['LookupLicenseInitialAssessmentParameter']['sorting_order'];
                    $is_published = $paramList['LookupLicenseInitialAssessmentParameter']['is_published'];
                    $parameter_name = $paramList['LookupLicenseInitialAssessmentParameter']['parameter'];
                    $parameter_id = $paramList['LookupLicenseInitialAssessmentParameter']['id'];
                    ?>
                <tr>
                    <td>
                        <?php echo $this->Form->input("LookupLicenseInitialAssessmentParameter.$i.id", array('type' => 'hidden', 'value' => $parameter_id, 'label' => false)); ?>
                        <?php echo $this->Form->input("LookupLicenseInitialAssessmentParameter.$i.sorting_order", array('type' => 'text', 'label' => false, 'value' => $sorting_order, 'style' => 'width:25px; padding:1px 5px; text-align:right;')); ?>
                    </td>
                    <td><?php echo $parameter_name; ?></td>
                    <td>
                        <?php
                        echo $this->Form->input("LookupLicenseInitialAssessmentParameter.$i.is_published", array('type' => 'radio', 'options' => array('0' => 'Off', '1' => 'On'), 'legend' => false, 'value' => $is_published));
                        ?>                           
                    </td>
                </tr>
                <?php
                    $i++;
                }
                ?>
            </table> 
        
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                        echo $this->Js->link('Close', array('controller' => 'LookupLicenseInitialAssessmentParameters', 'action' => 'view'), array('class' => 'mybtns', 'update' => '#ajax_div', 'evalScripts' => true,
                            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)))
                        );
                        ?> 
                    </td>
                    <td style="text-align:center;">
                        <?php
                        echo $this->Js->submit('Save', array('update' => '#ajax_div', 'evalScripts' => true,
                            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
                            'success' => "msg.init('success', '$title', '$title has been published successfully.');",
                            'error' => "msg.init('error', '$title', '$title has been failed!');"));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php echo $this->Form->end(); ?> 
    </fieldset>
</div>
