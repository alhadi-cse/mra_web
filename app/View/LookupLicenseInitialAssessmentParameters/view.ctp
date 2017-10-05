<?php
$title = "Initial Assessment Parameter";
//$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
//    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
//    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <?php echo $this->Form->create('LookupLicenseInitialAssessmentParameter'); ?>
        <table cellpadding="0" cellspacing="0" border="0">          
            <tr>
                <td style="padding-left:15px; text-align:right;">Search By</td>
                <td>
                    <?php
                    echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                        'options' => array('LookupLicenseInitialAssessmentParameter.parameter' => 'Name of Parameter',
                            'LookupLicenseInitialAssessmentParameter.declaration_year' => 'Declaration Year')));
                    ?>
                </td>
                <td class="colons">:</td>
                <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                <td style="text-align:left;">
                    <?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?>
                </td>               
            </tr>
        </table>
        <?php echo $this->Form->end(); ?> 

        <table class="view">
            <tr>
                <?php
                echo "<th style='width:70px;'>" . $this->Paginator->sort('LookupLicenseInitialAssessmentParameter.sorting_order', 'Serial no.') . "</th>";
                echo "<th style='min-width:320px;'>" . $this->Paginator->sort('LookupLicenseInitialAssessmentParameter.parameter.', 'Name of Parameter') . "</th>";
                echo "<th style='width:85px;'>" . $this->Paginator->sort('LookupLicenseInitialAssessmentParameter.declaration_year', 'Declaration Year') . "</th>";
                echo "<th style='width:130px;'>Action</th>";
                ?>
            </tr>
            <?php foreach ($values as $value) { ?>
                <tr>
                    <td style="text-align:center;"><?php echo $value['LookupLicenseInitialAssessmentParameter']['sorting_order']; ?></td>
                    <td style="text-align:left;"><?php echo $value['LookupLicenseInitialAssessmentParameter']['parameter']; ?></td>  
                    <td style="text-align:center;"><?php echo $value['LookupLicenseInitialAssessmentParameter']['declaration_year']; ?></td>        
                    <td style="text-align:center; padding:2px; height:30px;">
                        <?php
                        echo $this->Js->link('Edit', array('controller' => 'LookupLicenseInitialAssessmentParameters', 'action' => 'edit', $value['LookupLicenseInitialAssessmentParameter']['id']), array_merge($pageLoading, array('class' => 'btnlink')))
                        . $this->Js->link('Delete', array('controller' => 'LookupLicenseInitialAssessmentParameters', 'action' => 'delete', $value['LookupLicenseInitialAssessmentParameter']['id']), array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');", 'class' => 'btnlink')));
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>


        <?php if ($values != null && $this->Paginator->param('pageCount') > 1) { ?>
        <div class="paginator">
            <?php
            echo $this->Paginator->prev('<<', array('class' => 'prevPg'), null, array('class' => 'prevPg no_link')) .
            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
            $this->Paginator->next('>>', array('class' => 'nextPg'), null, array('class' => 'nextPg no_link'));
            ?>
        </div>
        <?php } ?>

        <div class="btns-div"> 
            <table style="margin:0 auto; padding:5px;" cellspacing="7">
                <tr>
                    <td></td>                    
                    <td>
                        <?php
                        echo $this->Js->link('Add New', array('controller' => 'LookupLicenseInitialAssessmentParameters', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?>
                    </td>                    
                    <td>
                        <?php
                        echo $this->Js->link('Publish Parameter', array('controller' => 'LookupLicenseInitialAssessmentParameters', 'action' => 'publish_parameter'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>

    </fieldset>
</div>
