<div>
    <?php
    if (isset($msg) && !empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    $title = 'Field Inspection/Queries Details';
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
                echo $this->Form->create('SupervisionModuleFieldInspectionDetail');
                ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search Option</td>
                        <td>
                            <?php
                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                                'options' =>
                                array('BasicModuleBasicInformation.full_name_of_org' => "Organization's Full Name",
                                    'BasicModuleBasicInformation.short_name_of_org' => "Organization's Short Name",
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
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                            echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_inspected as $value) { ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td style="text-align:left;"><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                <td style="text-align:center;">
                                    <?php
                                    $inspection_date = $value['SupervisionModuleFieldInspectionDetail']['inspection_date'];
                                    if (!empty($inspection_date))
                                        echo date("d-m-Y", strtotime($inspection_date));
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $submission_date = $value['SupervisionModuleFieldInspectionDetail']['submission_date'];
                                    if (!empty($submission_date))
                                        echo date('d-m-Y', strtotime($submission_date));
                                    ?>
                                </td>                                
                                <td style="text-align:center; padding:2px; height:30px;">
                                    <?php
                                    $supervision_basic_id = $value['SupervisionModuleFieldInspectionDetail']['supervision_basic_id'];
                                    $redirect_url = array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'preview', $supervision_basic_id);
                                    echo $this->Js->link('Details', $redirect_url, array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                    ?>     
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </fieldset>
            <fieldset>
                <legend>Not yet Approved by Team Leader</legend>
                <?php
                if (empty($values_inspected_not_approved) || !is_array($values_inspected_not_approved) || count($values_inspected_not_approved) < 1) {
                    echo '<p class="error-message">Not yet done any inspection !</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                            echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_inspected_not_approved as $value) { ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td style="text-align:left;"><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                <td style="text-align:center;">
                                    <?php
                                    $inspection_date = $value['SupervisionModuleFieldInspectionDetail']['inspection_date'];
                                    if (!empty($inspection_date))
                                        echo date("d-m-Y", strtotime($inspection_date));
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $submission_date = $value['SupervisionModuleFieldInspectionDetail']['submission_date'];
                                    if (!empty($submission_date))
                                        echo date('d-m-Y', strtotime($submission_date));
                                    ?>
                                </td>

                                <td style="text-align:center; padding:2px; height:30px;">
                                    <?php
                                    $supervision_basic_id = $value['SupervisionModuleFieldInspectionDetail']['supervision_basic_id'];
                                    $redirect_url = array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'inspection_approval', $supervision_basic_id);
                                    if (!empty($user_is_inspector))
                                        echo $this->Js->link('Approve', $redirect_url, array_merge($pageLoading, array('class' => 'btnlink')));
                                    echo $this->Js->link('Details', array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'preview', $supervision_basic_id), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table> 

                <?php } ?>
            </fieldset>


            <fieldset>
                <legend>Inspected but not yet Submitted</legend>
                <?php
                if (empty($values_inspected_not_yet_submitted) || !is_array($values_inspected_not_yet_submitted) || count($values_inspected_not_yet_submitted) < 1) {
                    echo '<p class="error-message">Not yet done any inspection !</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionDetail.inspection_date', 'Inspection Date') . "</th>";
                            echo "<th style='width:50px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionDetail.submission_date', 'Submission Date') . "</th>";
                            echo "<th style='width:75px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_inspected_not_yet_submitted as $value) { ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td style="text-align:left;"><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                <td style="text-align:center;">
                                    <?php
                                    $inspection_date = $value['SupervisionModuleFieldInspectionDetail']['inspection_date'];
                                    if (!empty($inspection_date))
                                        echo date("d-m-Y", strtotime($inspection_date));
                                    ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                    $submission_date = $value['SupervisionModuleFieldInspectionDetail']['submission_date'];
                                    if (!empty($submission_date))
                                        echo date('d-m-Y', strtotime($submission_date));
                                    ?>
                                </td>                                
                                <td style="text-align:center; padding:2px; height:30px;">
                                    <?php
                                    $supervision_basic_id = $value['SupervisionModuleBasicInformation']['id'];
                                    $redirect_url = array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 're_inspection', $supervision_basic_id);
                                    echo $this->Js->link('Submit', $redirect_url, array_merge($pageLoading, array('class' => 'btnlink')));
                                    echo $this->Js->link('Details', array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'preview', $supervision_basic_id), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
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
                if (empty($values_not_inspected) || !is_array($values_not_inspected) || count($values_not_inspected) < 1) {
                    echo '<p class="error-message">There is no inspector assigned !</p>';
                } else {
                    ?>
                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                            echo "<th style='width:85px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_not_inspected as $value) { ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td style="text-align:left;"><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                <td style="height:30px; padding:2px; text-align:center;"> 
                                    <?php
                                    $supervision_basic_id = $value['SupervisionModuleBasicInformation']['id'];
                                    $redirect_url = array('controller' => 'SupervisionModuleFieldInspectionDetails', 'action' => 'inspection', $supervision_basic_id);
                                    echo $this->Js->link('Inspection', $redirect_url, array_merge($pageLoading, array('class' => 'btnlink')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </fieldset>
        </div>
    </fieldset>
</div>