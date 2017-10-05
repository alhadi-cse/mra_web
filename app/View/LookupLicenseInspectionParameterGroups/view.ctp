<div id="frmInspection TypeOfOrg_view">
    <?php
    $title = "License Inspection Parameter Group";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <div class="form">
            <?php echo $this->Form->create('LookupLicenseInspectionParameterGroup'); ?>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search By</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                            'options' => array('LookupLicenseInspectionType.inspection_type' => 'Inspection Type',
                                'LookupLicenseInspectionParameterGroup.serial_no' => 'Serial no.',
                                'LookupLicenseInspectionParameterGroup.inspection_parameter_group' => 'Parameter Group Name'
                        )));
                        ?>
                    </td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                    <td style="text-align:left;">
                        <?php
                        echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                        ?>
                    </td>
                </tr>
            </table>
            <?php echo $this->Form->end(); ?>

            <table class="view">
                <tr>
                    <?php
                    echo "<th style='width:50px;'>" . $this->Paginator->sort('LookupLicenseInspectionParameterGroup.serial_no', 'Serial No.') . "</th>";
                    echo "<th style='width:70px;'>" . $this->Paginator->sort('LookupLicenseInspectionParameterGroup.licensing_year', 'Licensing Year') . "</th>";
                    echo "<th style='min-width:200px;'>" . $this->Paginator->sort('LookupLicenseInspectionType.inspection_type', 'Inspection Type') . "</th>";
                    echo "<th style='min-width:230px;'>" . $this->Paginator->sort('LookupLicenseInspectionParameterGroup.inspection_parameter_group', 'Parameter Group Name') . "</th>";
                    echo "<th style='width:130px;'>Action</th>";
                    ?>                                    
                </tr>
                <?php foreach ($values as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['LookupLicenseInspectionParameterGroup']['serial_no']; ?></td>
                        <td style="text-align:center;"><?php echo $value['LookupLicenseInspectionParameterGroup']['licensing_year']; ?></td>
                        <td style="text-align:justify;"><?php echo $value['LookupLicenseInspectionType']['inspection_type']; ?></td>  
                        <td style="text-align:justify;"><?php echo $value['LookupLicenseInspectionParameterGroup']['inspection_parameter_group']; ?></td>   
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            echo $this->Js->link('Edit', array('controller' => 'LookupLicenseInspectionParameterGroups', 'action' => 'edit', $value['LookupLicenseInspectionParameterGroup']['id']), array_merge($pageLoading, array('class' => 'btnlink')))
                                    . $this->Js->link('Delete', array('controller' => 'LookupLicenseInspectionParameterGroups', 'action' => 'delete', $value['LookupLicenseInspectionParameterGroup']['id']), array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');", 'class' => 'btnlink')));
                            ?>
                        </td>
                    </tr>
                <?php } ?>
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
                        <?php
                        echo $this->Js->link('Add New', array('controller' => 'LookupLicenseInspectionParameterGroups', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>

