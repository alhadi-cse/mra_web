<?php

if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

$title = 'License Cancel Request Verification';

$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

$this->Paginator->options($pageLoading);
?>

<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <div class="form">

            <?php if (empty($org_id)) {
                echo $this->Form->create('LicenseModuleCancelByMfiCancelRequestVerificationDetail');
                ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php
                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                                                        'options' => array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                                           'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                                                           'BasicModuleBasicInformation.license_no' => 'License No.')));
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
                <?php echo $this->Form->end();
            }
            ?>

            <fieldset>
                <legend>License Cancel Request Verification Completed</legend>

                <?php
                if ($values_verified == null || !is_array($values_verified) || count($values_verified) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available!';
                    echo '</p>';
                } else {
                ?>

                <table class="view">
                    <tr>
                        <?php
                        if (!$this->Paginator->param('options'))
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.', array('class' => 'asc')) . "</th>";
                        else
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='width:250px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.verification_status', 'Verification Status') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleCancelByMfiCancelRequestVerificationDetail.verification_date', 'Verification Date') . "</th>";
                        echo "<th style='width:80px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_verified as $value) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>" . $mfiName . ":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName . $mfiFullName;

                                echo $mfiName;
                                ?>
                            </td>                            
                            <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['verification_status']; ?></td>
                            <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleCancelByMfiCancelRequestVerificationDetail']['verification_date'], '%d-%m-%Y', ''); ?></td>
                            <td style="text-align:center; padding:2px; height:30px;">
                                <?php
                                    echo $this->Js->link('Details', array('controller' => 'LicenseModuleCancelByMfiCancelRequestVerificationDetails', 'action' => 'preview', $value['LicenseModuleCancelByMfiCancelRequestVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                ?>     
                            </td>
                        </tr>
                    <?php } ?>
                </table> 
                <?php } ?>


                <?php if ($values_verified && $this->Paginator->param('pageCount') > 1) { ?>
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
            </fieldset>
            
            <fieldset>
                <legend>License Cancel Request Verification Not Approved</legend>

                <?php
                if (empty($values_not_verified) && empty($values_not_verified_by_all)) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                ?>

                <table class="view">
                    <tr>
                        <?php
                        if (!$this->Paginator->param('options'))
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.', array('class' => 'asc')) . "</th>";
                        else
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='width:250px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:90px;'>" . $this->Paginator->sort('LookupLicenseApprovalStatus.verification_status', 'Verification Status') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('LicenseModuleCancelByMfiCancelRequestVerificationDetail.verification_date', 'Verification Date') . "</th>";
                        echo "<th style='width:80px;'>Action</th>";
                        ?>
                    </tr>
                    <?php if (!empty($values_not_verified)) {
                        foreach ($values_not_verified as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                        </td>                        
                        <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['verification_status']; ?></td>
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleCancelByMfiCancelRequestVerificationDetail']['verification_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            if (!empty($user_is_committee_member)) 
                                echo $this->Js->link('Approve', array('controller' => 'LicenseModuleCancelByMfiCancelRequestVerificationDetails', 'action' => 'verification_approval', $value['LicenseModuleCancelByMfiCancelRequestVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleCancelByMfiCancelRequestVerificationDetails', 'action' => 'preview', $value['LicenseModuleCancelByMfiCancelRequestVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            ?>     
                        </td>
                    </tr>
                    <?php }
                    } ?>
                    
                    <?php if (!empty($values_not_verified_by_all)) {
                        foreach ($values_not_verified_by_all as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";

                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                        </td>                        
                        <td style="text-align:center;"><?php echo $value['LookupLicenseApprovalStatus']['verification_status']; ?></td>
                        <td style="text-align:center;"><?php echo $this->Time->format($value['LicenseModuleCancelByMfiCancelRequestVerificationDetail']['verification_date'], '%d-%m-%Y', ''); ?></td>
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            if (!empty($user_is_committee_member)) 
                                echo $this->Js->link('Re-Approve', array('controller' => 'LicenseModuleCancelByMfiCancelRequestVerificationDetails', 'action' => 'verification_re_approval', $value['LicenseModuleCancelByMfiCancelRequestVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));                            
                            
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleCancelByMfiCancelRequestVerificationDetails', 'action' => 'preview', $value['LicenseModuleCancelByMfiCancelRequestVerificationDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            ?>
                        </td>
                    </tr>
                    <?php }
                        } ?>
                        
                </table> 
                <?php } ?>


                <?php if ($values_not_verified && $this->Paginator->param('pageCount') > 1) { ?>
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

            </fieldset>

            <fieldset>
                <legend>License Cancel Request Verification Pending</legend>
                <?php
                if (empty($values_pending) || !is_array($values_pending) || count($values_pending) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {                    
                    ?>

                <table class="view">
                    <tr>
                        <?php
                        if (!$this->Paginator->param('options'))
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.', array('class' => 'asc')) . "</th>";
                        else
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='min-width:280px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        if (!empty($user_is_committee_member)) {
                            $width = 'width:150px;';
                        }else{
                            $width = 'width:80px;';
                        }
                        echo "<th style=$width>Action</th>";
                        ?>
                    </tr>

                    <?php foreach ($values_pending as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php
                            $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($mfiName))
                                $mfiName = "<strong>" . $mfiName . ":</strong> ";
                            if (!empty($mfiFullName))
                                $mfiName = $mfiName . $mfiFullName;

                            echo $mfiName;
                            ?>
                        </td>                        
                        <td style="height:30px; padding:2px; text-align:center;"> 
                            <?php                            
                            if (!empty($user_is_committee_member)) {
                                echo $this->Js->link('Verification', array('controller' => 'LicenseModuleCancelByMfiCancelRequestVerificationDetails', 'action' => 'verification', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            }
                            echo $this->Js->link('Details', array('controller'=>'LicenseModuleCancelByMfiCancelRequests', 'action'=>'preview', $value['BasicModuleBasicInformation']['id']), 
                                                            array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>

                <?php
//                if (count($values_pending) > 1 && !empty($user_is_committee_member)) {
//                    echo '<div class="btns-div" style="padding:7px; text-align:center;">'
//                    . $this->Js->link('Verify All', array('controller' => 'LicenseModuleCancelByMfiCancelRequestVerificationDetails', 'action' => 'verification_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Initial Assessment Verify All')))
//                    . '</div>';
//                }
            }
            ?>

            </fieldset>

        </div>
    </fieldset>
</div>
