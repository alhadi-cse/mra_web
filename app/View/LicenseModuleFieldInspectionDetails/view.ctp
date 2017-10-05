
<div>
    <?php
    if (isset($msg) && !empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }

    if (!empty($inspection_type_id) && !empty($inspection_type_detail[$inspection_type_id]))
        $title = "$inspection_type_detail[$inspection_type_id] Details";
    else
        $title = 'Field Inspection Details';
    
    //$is_basic_opt = ($inspection_type_id == 1 || $inspection_type_id == 2);

    if (!isset($licensed_mfi))
        $licensed_mfi = 0;
    $mfi_no_field = ($licensed_mfi == 1) ? 'license_no' : 'form_serial_no';
    
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>


    <fieldset>
        <legend><?php echo $title; ?></legend>                
        <div class="form">

            <?php
            if (empty($org_id)) {
                echo $this->Form->create('LicenseModuleFieldInspectionDetail');
                ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php
                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                                'options' =>
                                array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                    'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                    'BasicModuleBasicInformation.form_serial_no' => 'Form No.')
                            ));
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
                <?php
                echo $this->Form->end();
            }
            ?>

            <fieldset>
                <legend>Inspection Completed</legend>
                <?php
                if (empty($values_inspected) || !is_array($values_inspected) || count($values_inspected) < 1) {
                    echo '<p class="error-message">Not yet done any inspection !</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
//                            if (!$this->Paginator->param('options'))
//                                echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
//                            else
//                                echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='width:80px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                            
                            if ($inspection_type_id == 1 || $inspection_type_id == 2)
                                echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.inspector_recommendation', 'Recommended') . "</th>";
                            
                            echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_inspected as $value) { ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $inspection_date = $value['LicenseModuleFieldInspectionDetail']['inspection_date'];
                                    if (!empty($inspection_date))
                                        echo date("d-m-Y", strtotime($inspection_date));
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $submission_date = $value['LicenseModuleFieldInspectionDetail']['submission_date'];
                                    if (!empty($submission_date))
                                        echo date('d-m-Y', strtotime($submission_date));
                                    ?>
                                </td>
                                
                                <?php if ($inspection_type_id == 1 || $inspection_type_id == 2) { ?>
                                <td style="text-align:center;">
                                    <?php
                                    $is_recommended = $value['LicenseModuleFieldInspectionDetail']['inspector_recommendation'];
                                    echo (!empty($is_recommended) && $is_recommended == 1) ? "Yes" : "No";
                                    ?>
                                </td>
                                <?php } ?>
                                
                                <td style="text-align:center; padding:2px; height:30px;">
                                    <?php 
                                    $inspection_slno = $value['LicenseModuleFieldInspectionDetail']['inspection_slno'];
                                    $org_id = $value['LicenseModuleFieldInspectionDetail']['org_id'];
                                    $redirect_url = array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'preview', $org_id, $inspection_type_id, $inspection_slno, '?' => array('licensed_mfi' => $licensed_mfi));
                                    echo $this->Js->link('Details', $redirect_url, array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                    ?>     
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 

                <?php } ?>
            </fieldset>


            <fieldset>
                <legend>Inspect but not Approve</legend>
                <?php
                if (empty($values_inspected_not_approved) || !is_array($values_inspected_not_approved) || count($values_inspected_not_approved) < 1) {
                    echo '<p class="error-message">Not yet done any inspection !</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
//                            if (!$this->Paginator->param('options'))
//                                echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
//                            else
//                                echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='width:80px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                            
                            if ($inspection_type_id == 1 || $inspection_type_id == 2)
                                echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.inspector_recommendation', 'Recommended') . "</th>";
                            
                            echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_inspected_not_approved as $value) { ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $inspection_date = $value['LicenseModuleFieldInspectionDetail']['inspection_date'];
                                    if (!empty($inspection_date))
                                        echo date("d-m-Y", strtotime($inspection_date));
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $submission_date = $value['LicenseModuleFieldInspectionDetail']['submission_date'];
                                    if (!empty($submission_date))
                                        echo date('d-m-Y', strtotime($submission_date));
                                    ?>
                                </td>
                                
                                <?php if ($inspection_type_id == 1 || $inspection_type_id == 2) { ?>
                                <td style="text-align:center;">
                                    <?php
                                    $is_recommended = $value['LicenseModuleFieldInspectionDetail']['inspector_recommendation'];
                                    echo (!empty($is_recommended) && $is_recommended == 1) ? "Yes" : "No";
                                    ?>
                                </td>
                                <?php } ?>
                                
                                <td style="text-align:center; padding:2px; height:30px;">
                                    <?php 
                                    $inspection_slno = $value['LicenseModuleFieldInspectionDetail']['inspection_slno'];
                                    $org_id = $value['LicenseModuleFieldInspectionDetail']['org_id'];
                                    
                                    $redirect_url = array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'inspection_approval', $org_id, $inspection_type_id, $inspection_slno, '?' => array('licensed_mfi' => $licensed_mfi));
                                    if (!empty($user_is_inspector))
                                        echo $this->Js->link('Approval', $redirect_url, array_merge($pageLoading, array('class' => 'btnlink')));
                                    echo $this->Js->link('Details', array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'preview', $org_id, $inspection_type_id, $inspection_slno, '?' => array('licensed_mfi' => $licensed_mfi)), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 

                <?php } ?>
            </fieldset>


            <fieldset>
                <legend>Inspect but not Submit</legend>
                <?php
                if (empty($values_inspected_not_submit) || !is_array($values_inspected_not_submit) || count($values_inspected_not_submit) < 1) {
                    echo '<p class="error-message">Not yet done any inspection !</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
//                            if (!$this->Paginator->param('options'))
//                                echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.', array('class' => 'asc')) . "</th>";
//                            else
//                                echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.') . "</th>";
                            echo "<th style='width:80px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                            
                            if ($inspection_type_id == 1 || $inspection_type_id == 2)
                                echo "<th style='width:85px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.inspector_recommendation', 'Recommended') . "</th>";

//                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.has_deposit_against_org_name', 'Deposit Against Organization') . "</th>";
//                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.was_the_money_withdrawn', 'Was the Money Withdrawn') . "</th>";
//                        echo "<th style='width:50px;'>" . $this->Paginator->sort('LicenseModuleFieldInspectionDetail.is_exist_during_inspection', 'Existence in the Time of Inspection') . "</th>";
                            echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_inspected_not_submit as $value) { ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $inspection_date = $value['LicenseModuleFieldInspectionDetail']['inspection_date'];
                                    if (!empty($inspection_date))
                                        echo date("d-m-Y", strtotime($inspection_date));
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $submission_date = $value['LicenseModuleFieldInspectionDetail']['submission_date'];
                                    if (!empty($submission_date))
                                        echo date('d-m-Y', strtotime($submission_date));
                                    ?>
                                </td>
                                
                                <?php if ($inspection_type_id == 1 || $inspection_type_id == 2) { ?>
                                <td style="text-align:center;">
                                    <?php
                                    $is_recommended = $value['LicenseModuleFieldInspectionDetail']['inspector_recommendation'];
                                    echo (!empty($is_recommended) && $is_recommended == 1) ? "Yes" : "No";
                                    ?>
                                </td>
                                <?php } ?>
                                
                                <td style="text-align:center; padding:2px; height:30px;">
                                    <?php
                                    $inspection_slno = $value['LicenseModuleFieldInspectionDetail']['inspection_slno'];
                                    $org_id = $value['LicenseModuleFieldInspectionDetail']['org_id'];
                                    
                                    $redirect_url = array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 're_inspection', $org_id, $inspection_type_id, $inspection_slno, '?' => array('licensed_mfi' => $licensed_mfi));
                                    echo $this->Js->link('Submit', $redirect_url, array_merge($pageLoading, array('class' => 'btnlink')));
                                    echo $this->Js->link('Details', array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'preview', $org_id, $inspection_type_id, $inspection_slno, '?' => array('licensed_mfi' => $licensed_mfi)), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 

                <?php } ?>
            </fieldset>

            <fieldset>
                <legend>Inspection Pending</legend>
                <?php
                if (empty($values_not_inspect) || !is_array($values_not_inspect) || count($values_not_inspect) < 1) {                    
                    echo '<p class="error-message">There is no inspector to assign !</p>';
                } else {                    
                    ?>
                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:80px;'>" . (($licensed_mfi == 1) ? $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') : $this->Paginator->sort('BasicModuleBasicInformation.form_serial_no', 'Form No.')) . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:85px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_not_inspect as $value) { ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation'][$mfi_no_field]; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td style="height:30px; padding:2px; text-align:center;"> 
                                    <?php
                                    $redirect_url = array('controller' => 'LicenseModuleFieldInspectionDetails', 'action' => 'inspection', $value['BasicModuleBasicInformation']['id'], '?' => array('inspection_type_id' => $inspection_type_id, 'licensed_mfi' => $licensed_mfi));
                                    echo $this->Js->link('Inspection', $redirect_url, array_merge($pageLoading, array('class' => 'btnlink')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>

                <?php } ?>
            </fieldset>

        </div>

        <?php //if (!empty($values_inspected) && $this->Paginator->param('pageCount') > 1) { ?>
<!--            <div class="paginator">
                <?php
////                if ($this->Paginator->param('pageCount') > 10) {
////                    echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')) .
////                    $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
////                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
////                    $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')) .
////                    $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
////                } else {
////                    echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')) .
////                    $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
////                    $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
////                }
//                ?>
            </div>-->
        <?php //} ?>

    </fieldset>

</div>

