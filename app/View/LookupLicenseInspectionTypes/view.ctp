<div>
    <?php
    $title = "Inspection Type Information";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>
    
    <fieldset>
        <legend><?php echo $title; ?></legend> 

        <div class="form">
            <?php echo $this->Form->create('LookupLicenseInspectionType'); ?>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search By</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                                                    'options' => array('LookupLicenseInspectionType.serial_no' => 'Serial no.',
                                                        'LookupLicenseInspectionType.inspection_type' => 'Type of Inspection')));
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
                    echo "<th style='width:50px;'>" . $this->Paginator->sort('LookupLicenseInspectionType.serial_no', 'Serial no.') . "</th>";
                    echo "<th style='min-width:285px;'>" . $this->Paginator->sort('LookupLicenseInspectionType.inspection_type.', 'Name of Inspection Type') . "</th>";
                    echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupLicenseInspectionType.inspection_is_multiple.', 'Inspection Entry Level') . "</th>";
                    echo "<th style='width:130px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach ($values as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['LookupLicenseInspectionType']['serial_no']; ?></td>
                        <td style="text-align:justify;"><?php echo $value['LookupLicenseInspectionType']['inspection_type']; ?></td>        
                        <td style="text-align:center;">
                            <?php 
                                $is_multiple = $value['LookupLicenseInspectionType']['inspection_is_multiple'];
                                if (isset($is_multiple))
                                    echo ($is_multiple == 0) ? 'Single' : 'Multiple';
                                ?>
                        </td>        
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            echo $this->Js->link('Edit', array('controller' => 'LookupLicenseInspectionTypes', 'action' => 'edit', $value['LookupLicenseInspectionType']['id']), array_merge($pageLoading, array('class' => 'btnlink')))
                                    . $this->Js->link('Delete', array('controller' => 'LookupLicenseInspectionTypes', 'action' => 'delete', $value['LookupLicenseInspectionType']['id']), array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');", 'class' => 'btnlink')));
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table> 
        </div>
        
        <?php if ($values && $this->Paginator->param('pageCount') > 1) { ?>
        <div class="paginator">
            <?php
            if ($this->Paginator->param('pageCount') > 10) {
                echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                $this->Paginator->last('>>', array('class' => 'nextPg', 'title' => 'Goto last page.'), null, array('class' => 'nextPg no_link'));
            } else {
                echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
            }
            ?>
        </div>
        <?php } ?>

        <div class="btns-div">
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>
                    <td>
                        <?php echo $this->Js->link('Add New', array('controller' => 'LookupLicenseInspectionTypes', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => ''))); ?>     
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        
    </fieldset>
    
</div>
