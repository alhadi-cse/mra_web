<div>
    <?php
    $assessor_group_id = $this->Session->read('assessor_group_id');
    $title = "Assessor for Initial Assessment";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    ?>
    <fieldset>
        <legend>
            <?php echo $title.' of Form No. ( '.$min_form_serial.' to '.$max_form_serial.' )'; ?>
        </legend>

        <?php  echo $this->Form->create('LicenseModuleInitialAssessmentAssessorDetail'); ?>
        <div class="form">
            <?php
                $i = 1;
                $total_no_of_assessor = count($assessorLists);
                $all_form_no = array();                
                for($i = $pending_min_serial_no; $i <= (int)$max_form_serial; $i++){
                   $all_form_no[$i] = $i;
                }
                if($total_no_of_assessor!=0){
                    $form_distribution_factor = floor(count($all_form_no)/$total_no_of_assessor);
                    $pending_form_no_distributed_to_last = count($all_form_no)%$total_no_of_assessor;
                }  else {
                    echo "<p style='font-weight:bold;color:red;'>There is no pending assessor exists to assign evaluation form!</p>";
                }
                
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="view">
                <tr>
                    <th style="border:1px solid #aed0ea;width:350px; text-align: left; padding-left: 10px;">Name of Assessor</th>
                    <th style="border:1px solid #aed0ea;width:100px;">From Form No.</th>
                    <th style="border:1px solid #aed0ea;width:100px;">To Form No.</th>
                </tr>
               <?php                
                $min_form_no = $pending_min_serial_no;
                $max_form_no = 0;
                $counter_of_assessor = 0;                           
                
                foreach($assessorLists as $assessorDetails)
                {
                    $assessor_id = $assessorDetails['assessor_id'];
                    $assessor_name = $assessorDetails['assessor_name']; 
                    $max_form_no = $min_form_no + $form_distribution_factor - 1;
                    
                    if($total_no_of_assessor-1 == $counter_of_assessor){
                        $max_form_no = $max_form_no + $pending_form_no_distributed_to_last;
                    }
                ?>
                <tr <?php if ($i%2==0) { echo ' class="alt"'; } ?>>
                    <td style="text-align: left; padding-left: 10px;">
                        <?php
                            echo $assessor_name;
                            echo $this->Form->input("LicenseModuleInitialAssessmentAssessorDetail.$i.assessor_id",array('type'=>'hidden', 'label' => false, 'value'=>$assessor_id));
                        ?>
                    </td>
                    <td style="text-align: center;"><?php echo $this->Form->input("LicenseModuleInitialAssessmentAssessorDetail.$i.from_form_no", array('type' => 'text','label' => false, 'value'=>$min_form_no, 'style' => 'width:150px;')); ?></td>
                    <td style="text-align: center;"><?php echo $this->Form->input("LicenseModuleInitialAssessmentAssessorDetail.$i.to_form_no", array('type' => 'text', 'label' => false, 'value'=>$max_form_no, 'style' => 'width:150px;')); ?></td>
                </tr>
                <?php
                    $i++;
                    $min_form_no = $max_form_no + 1;
                    $counter_of_assessor++;
                }
                    $j=$i;
                foreach($values as $value){ ?>
                <tr <?php if ($j%2==0) { echo ' class="alt"'; } ?>>
                    <td style="text-align: left; padding: 13px 0px 13px 10px; font-weight: bold;"><?php echo $value['LookupLicenseInspectorList']['assessor_name']; ?></td>
                    <td style="text-align: center; padding: 12px 0px 12px 10px; font-weight: bold;"><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['from_form_no']; ?></td>  
                    <td style="text-align: center; padding: 12px 0px 12px 10px; font-weight: bold;"><?php echo $value['LicenseModuleInitialAssessmentAssessorDetail']['to_form_no']; ?></td>                                      
                </tr>
                <?php
                    $j++;
                }
                ?>
            </table>
        </div>
        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td>
                        <?php
                            echo $this->Js->link('Close', array('controller' => 'LicenseModuleInitialAssessmentAssessorDetails','action' => 'view','?' => array('assessor_group_id' => $assessor_group_id)),array_merge($pageLoading, array('class'=>'mybtns')));  
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <?php
                        if($total_no_of_assessor>0){
                            echo $this->Js->submit('Save', array_merge($pageLoading, 
                                                    array('success'=>"msg.init('success', '$title', '$title has been added successfully.');", 
                                                          'error'=>"msg.init('error', '$title', 'Insertion failed!');")));
                        }
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
        $('.integers').numeric({ decimal: false, negative: false });
        $('.decimals').numeric({ decimal: ".", negative: false });
    });
</script>
