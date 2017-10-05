<?php
    if(isset($msg) && !empty($msg)) {
        if(is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init('.json_encode($msg).');', array('inline'=>true));
        }
        else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline'=>true));
        }
    }
    else {
        $title = 'Field Inspection Information';    
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
        $this->Paginator->options($pageLoading); ?>
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>                
        <div class="form">
            
            <?php if(empty($org_id)) { 
                echo $this->Form->create('LicenseModuleSuspensionFieldInspectionDetail'); ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php 
                                echo $this->Form->input('search_option', 
                                        array('label' => false, 'style'=>'width:200px',
                                            'options' => 
                                                array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                    'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                                    'BasicModuleBasicInformation.license_no'=>'License No.')
                                                ));
                            ?>
                        </td>
                        <td class="colons">:</td>
                        <td><?php echo $this->Form->input('search_keyword',array('label' => false,'style'=>'width:250px')); ?></td>
                        <td style="text-align:left;">
                           <?php
                               echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch')));
                            ?>
                       </td>                                
                    </tr>
                </table>
            <?php echo $this->Form->end(); 
            } ?>
                        
            <fieldset>
                <legend>Inspection Completed</legend>
                <?php
                if ($values_inspected == null || !is_array($values_inspected) || count($values_inspected) < 1) {
                    echo '<p class="error-message">';
                    echo 'Not yet done any inspection !';
                    echo '</p>';
                } else {
                ?>
                <table class="view">
                    <tr>
                        <?php 
                        if(!$this->Paginator->param('options'))
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                        echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.inspector_recommendation', 'Recommended') . "</th>";
                        
//                        echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.has_deposit_against_org_name', 'Deposit Against Organization') . "</th>";
//                        echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.was_the_money_withdrawn', 'Was the Money Withdrawn') . "</th>";
//                        echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.is_exist_during_inspection', 'Existence in the Time of Inspection') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_inspected as $value){ ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php 
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>".$mfiName.":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName.$mfiFullName;        

                                echo $mfiName;
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                            $inspection_date = $value['LicenseModuleSuspensionFieldInspectionDetail']['inspection_date'];
                            if(!empty($inspection_date)) echo date("d-m-Y", strtotime($inspection_date));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                            $submission_date = $value['LicenseModuleSuspensionFieldInspectionDetail']['submission_date'];
                            if(!empty($submission_date)) echo date('d-m-Y', strtotime($submission_date));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                                $is_recommended = $value['LicenseModuleSuspensionFieldInspectionDetail']['inspector_recommendation'];
                                echo (!empty($is_recommended) && $is_recommended == 1) ? "Yes" : "No";
                            ?>
                        </td>
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php 
                                echo $this->Js->link('Details', array('controller'=>'LicenseModuleSuspensionFieldInspectionDetails', 'action'=>'preview', $value['LicenseModuleSuspensionFieldInspectionDetail']['org_id']), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));
                            ?>     
                        </td>
                    </tr>
                    <?php  } ?>
                </table> 
                
                <?php  } ?>
            </fieldset>
            
            
            <fieldset>
                <legend>Inspect but not Approve</legend>
                <?php 
                    if ($values_inspected_not_approved == null || !is_array($values_inspected_not_approved) || count($values_inspected_not_approved) < 1) {
                        echo '<p class="error-message">';
                        echo 'Not yet done any inspection !';
                        echo '</p>';
                    } else { //debug($values_inspected_not_submit);
                ?>
                <table class="view">
                    <tr>
                        <?php 
                        if(!$this->Paginator->param('options'))
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                        echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.inspector_recommendation', 'Recommended') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_inspected_not_approved as $value){ ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php 
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>".$mfiName.":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName.$mfiFullName;        

                                echo $mfiName;
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                            $inspection_date = $value['LicenseModuleSuspensionFieldInspectionDetail']['inspection_date'];
                            if(!empty($inspection_date)) echo date("d-m-Y", strtotime($inspection_date));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                            $submission_date = $value['LicenseModuleSuspensionFieldInspectionDetail']['submission_date'];
                            if(!empty($submission_date)) echo date('d-m-Y', strtotime($submission_date));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                                $is_recommended = $value['LicenseModuleSuspensionFieldInspectionDetail']['inspector_recommendation'];
                                echo (!empty($is_recommended) && $is_recommended == 1) ? "Yes" : "No";
                            ?>
                        </td>
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            echo $this->Js->link('Approval', array('controller' => 'LicenseModuleSuspensionFieldInspectionDetails', 'action' => 'inspection_approval', $value['LicenseModuleSuspensionFieldInspectionDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleSuspensionFieldInspectionDetails', 'action' => 'preview', $value['LicenseModuleSuspensionFieldInspectionDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            ?>
                        </td>
                    </tr>
                    <?php  } ?>
                </table> 
                
                <?php } ?>
            </fieldset>
            
            
            <fieldset>
                <legend>Inspect but not Submit</legend>
                <?php 
                    if ($values_inspected_not_submit == null || !is_array($values_inspected_not_submit) || count($values_inspected_not_submit) < 1) {
                        echo '<p class="error-message">';
                        echo 'Not yet done any inspection !';
                        echo '</p>';
                    } else { //debug($values_inspected_not_submit);
                ?>
                <table class="view">
                    <tr>
                        <?php 
                        if(!$this->Paginator->param('options'))
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                        echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.inspector_recommendation', 'Recommended') . "</th>";
                        
//                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.has_deposit_against_org_name', 'Deposit Against Organization') . "</th>";
//                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.was_the_money_withdrawn', 'Was the Money Withdrawn') . "</th>";
//                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleSuspensionFieldInspectionDetail.is_exist_during_inspection', 'Existence in the Time of Inspection') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_inspected_not_submit as $value){ ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php 
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>".$mfiName.":</strong> ";

                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName.$mfiFullName;        

                                echo $mfiName;
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                            $inspection_date = $value['LicenseModuleSuspensionFieldInspectionDetail']['inspection_date'];
                            if(!empty($inspection_date)) echo date("d-m-Y", strtotime($inspection_date));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                            $submission_date = $value['LicenseModuleSuspensionFieldInspectionDetail']['submission_date'];
                            if(!empty($submission_date)) echo date('d-m-Y', strtotime($submission_date));
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php 
                                $is_recommended = $value['LicenseModuleSuspensionFieldInspectionDetail']['inspector_recommendation'];
                                echo (!empty($is_recommended) && $is_recommended == 1) ? "Yes" : "No";
                            ?>
                        </td>
                        <td style="text-align:center; padding:2px; height:30px;">
                            <?php
                            echo $this->Js->link('Submit', array('controller' => 'LicenseModuleSuspensionFieldInspectionDetails', 'action' => 're_inspection', $value['LicenseModuleSuspensionFieldInspectionDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                            echo $this->Js->link('Details', array('controller' => 'LicenseModuleSuspensionFieldInspectionDetails', 'action' => 'preview', $value['LicenseModuleSuspensionFieldInspectionDetail']['org_id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                            ?>
                        </td>
                    </tr>
                    <?php  } ?>
                </table> 
                
                <?php } ?>
            </fieldset>
            
            <fieldset>
                <legend>Inspection Pending</legend>
                <?php 
                    if($values_not_inspect == null || !is_array($values_not_inspect) || count($values_not_inspect) < 1) {
                        echo '<p class="error-message">';
                        echo 'There is no inspector assigned !';
                        echo '</p>';
                    }
                    else {
                ?>
                <table class="view">
                    <tr>
                        <?php 
                        if(!$this->Paginator->param('options'))
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.', array('class'=>'asc')) . "</th>";
                        else 
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:85px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_not_inspect as $value) { ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php 
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>".$mfiName.":</strong> ";
                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName.$mfiFullName;

                                echo $mfiName;
                            ?>
                        </td>
                        <td style="height:30px; padding:2px; text-align:center;"> 
                            <?php 
                                echo $this->Js->link('Inspection', array('controller'=>'LicenseModuleSuspensionFieldInspectionDetails','action'=>'inspection', $value['BasicModuleBasicInformation']['id']), 
                                                    array_merge($pageLoading, array('class'=>'btnlink')));
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                
                <?php } ?>
            </fieldset>
                    
        </div>
        
        <?php if($values_inspected && $this->Paginator->param('pageCount')>1) { ?>
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
    
</div>
<?php } ?>
