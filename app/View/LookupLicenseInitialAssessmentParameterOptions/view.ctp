<?php
$title = "Assessment Parameter Options/Criteria";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
?>
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <div class="form">            
            <?php echo $this->Form->create('LookupLicenseInitialAssessmentParameterOption'); ?>
            <table cellpadding="0" cellspacing="0" border="0">          
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search By</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px;', 'value' => $option, 
                                                                        'options' => array('LookupLicenseInitialAssessmentParameterOption.serial_no' => 'Serial no.',
                                                                                            'LookupLicenseInitialAssessmentParameter.parameter' => 'Name of Parameter')));
                        ?>
                    </td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('search_keyword', array('value' => $keyword, 'label' => false, 'style' => 'width:250px;')); ?></td>
                    <td style="text-align:left;">
                        <?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?>
                    </td>               
                </tr>
            </table>
            <?php echo $this->Form->end(); ?>


            <table class="view" style="font-family:verdana, helvetica, arial;">
                <tr>                                  
                    <?php
                    echo "<th style='min-width:250px;'>" . $this->Paginator->sort('LookupLicenseInitialAssessmentParameter.parameter', 'Name of Parameter') . "</th>";
                    echo "<th style='width:230px;'>" . $this->Paginator->sort('LookupLicenseInitialAssessmentParameterOption.parameter_option.', 'Parameter Option') . "</th>";
                    echo "<th style='width:80px;'>" . $this->Paginator->sort('LookupLicenseInitialAssessmentParameterOption.assessment_marks', 'Maximum Marks') . "</th>";
                    echo "<th style='width:130px;'>Action</th>";
                    ?>
                </tr>
                <?php
                foreach ($values as $value) {
                ?>                               
                <tr>                                    
                    <td style="text-align: left;"><?php echo $value['LookupLicenseInitialAssessmentParameter']['parameter']; ?></td>  
                    <td style="text-align: justify;"><?php echo $value['LookupLicenseInitialAssessmentParameterOption']['parameter_option']; ?></td>        
                    <td style="text-align: center;"><?php echo $value['LookupLicenseInitialAssessmentParameterOption']['assessment_marks']; ?></td>
                    <td style="text-align: center; padding:2px; height:30px;">
                        <?php
                        echo $this->Js->link('Edit', array('controller' => 'LookupLicenseInitialAssessmentParameterOptions', 'action' => 'edit', $value['LookupLicenseInitialAssessmentParameterOption']['id']), array_merge($pageLoading, array('class' => 'btnlink')))
                                . $this->Js->link('Delete', array('controller' => 'LookupLicenseInitialAssessmentParameterOptions', 'action' => 'delete', $value['LookupLicenseInitialAssessmentParameterOption']['id']), array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');", 'class' => 'btnlink')));
                        ?> 
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>  

        </div>
        
        <?php if ($values && $this->Paginator->param('pageCount') > 1) { ?>
            <div class="paginator">
                <?php
                echo $this->Paginator->prev('<<', array('class' => 'prevPg'), null, array('class' => 'prevPg no_link')) .
                $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                $this->Paginator->next('>>', array('class' => 'nextPg'), null, array('class' => 'nextPg no_link'));
                ?>
            </div>
        <?php } ?>
        
        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php echo $this->Js->link('Add New', array('controller' => 'LookupLicenseInitialAssessmentParameterOptions', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns'))); ?>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        
    </fieldset>
</div>
