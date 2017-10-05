
<div>
    <?php
    $title = "Application Rejection Category";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>

        <div class="form"> 
            <?php echo $this->Form->create('LookupLicenseApplicationRejectionCategory'); ?>
            <table cellpadding="0" cellspacing="0" border="0">                           
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search By</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:230px',
                            'options' => array('LookupLicenseApplicationRejectionCategory.serial_no' => 'Serial No.',
                                'LookupLicenseApplicationRejectionCategory.rejection_category' => 'Rejection Category'
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
                    echo "<th style='width:70px;'>" . $this->Paginator->sort('LookupLicenseApplicationRejectionCategory.serial_no', 'Serial No.') . "</th>";
                    echo "<th style='min-width:380px;'>" . $this->Paginator->sort('LookupLicenseApplicationRejectionCategory.rejection_category', 'Rejection Category') . "</th>";
                    echo "<th style='width:130px;'>Action</th>";
                    ?>                                    
                </tr>
                <?php foreach ($values as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['LookupLicenseApplicationRejectionCategory']['serial_no']; ?></td>
                        <td><?php echo $value['LookupLicenseApplicationRejectionCategory']['rejection_category']; ?></td>   
                        <td style="text-align:center; padding:2px; height:30px;"> 
                            <?php
                            echo $this->Js->link('Edit', array('controller' => 'LookupLicenseApplicationRejectionCategories', 'action' => 'edit', $value['LookupLicenseApplicationRejectionCategory']['id']), array_merge($pageLoading, array('class' => 'btnlink')))
                            . $this->Js->link('Delete', array('controller' => 'LookupLicenseApplicationRejectionCategories', 'action' => 'delete', $value['LookupLicenseApplicationRejectionCategory']['id']), array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', '$title has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');", 'class' => 'btnlink')));
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
                        echo $this->Js->link('Add New', array('controller' => 'LookupLicenseApplicationRejectionCategories', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>                    
    </fieldset>
</div>  
