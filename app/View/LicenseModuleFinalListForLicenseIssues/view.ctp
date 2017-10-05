<?php

$title = 'License Awarded MFI Information';

$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$isAdmin = (!empty($user_group_id) && in_array(1,$user_group_id));
$this->Paginator->options($pageLoading);
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>                
        <div class="form"> 

            <?php
            if (empty($values_final_list) || !is_array($values_final_list) || count($values_final_list) < 1) {
                echo '<p class="error-message">';
                echo 'No data is available!';
                echo '</p>';
            } else {
                ?>

            <?php echo $this->Form->create('LicenseModuleFindFinalListForLicenseIssue'); ?>
            <table cellpadding="0" cellspacing="0" border="0">                           
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search Option</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                            'options' =>
                            array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name')
                        ));
                        ?>
                    </td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                    <td style="text-align:left;">
                        <?php
                        echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch')));
                        ?>
                    </td>                                
                </tr>
            </table>
            <?php echo $this->Form->end(); ?> 

            <table class="view">
                <tr>
                    <?php
                    if (!$this->Paginator->param('options'))
                        echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization', array('class' => 'asc')) . "</th>";
                    else
                        echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                    echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                    echo "<th style='width:75px;'>Action</th>";
                    ?>
                </tr>
                <?php foreach ($values_final_list as $orgDetail) { ?>
                <tr>
                    <td>
                        <?php
                        $orgName = $orgDetail['BasicModuleBasicInformation']['short_name_of_org'];
                        $orgFullName = $orgDetail['BasicModuleBasicInformation']['full_name_of_org'];

                        if (!empty($orgName))
                            $orgName = "<strong>" . $orgName . ":</strong> ";
                        if (!empty($orgFullName))
                            $orgName = $orgName . $orgFullName;

                        echo $orgName;
                        ?>
                    </td>
                    <td style="text-align:center;"><?php echo $orgDetail['BasicModuleBasicInformation']['form_serial_no']; ?></td>
                    <td style="height:30px; padding:2px; text-align:center;">
                        <?php
                        if($isAdmin)
                            echo $this->Js->link('Done', array('controller' => 'LicenseModuleFinalListForLicenseIssues', 'action' => 'done', $orgDetail['BasicModuleBasicInformation']['id'], $this_state_ids), 
                                                                array_merge($pageLoading, array('class' => 'btnlink')));
                        
                        echo $this->Js->link('Details', array('controller' => 'LicenseModuleAdministrativeApprovals','action' => 'preview', $orgDetail['BasicModuleBasicInformation']['id']), 
                                                                array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            
                        
            <?php if ($this->Paginator->param('pageCount') > 1) { ?>
            <div class="paginator">
                <?php
                if ($this->Paginator->param('pageCount') > 10) {
                    echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
                    $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                } else {
                    echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                    $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                }
                ?>
            </div>
            <?php } ?>

        </div>

        <?php } ?>
    </fieldset>
</div>