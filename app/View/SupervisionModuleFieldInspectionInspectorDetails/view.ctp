<div>    
    <?php
    $title = "Inspector for Supervision";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <fieldset>
            <legend>Inspector Assigned</legend>
            <?php
            if (empty($values_assigned) || !is_array($values_assigned) || count($values_assigned) < 1) {
                echo '<p class="error-message">';
                echo 'No data is available !';
                echo '</p>';
            } else {
                ?>
                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                        echo "<th style='min-width:200px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                        echo "<th style='width:150px;'>Inspectors Name & Designation</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('SupervisionModuleAssignedInspectorApprovalDetail.inspection_date', 'Inspection Date') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_assigned as $value) { ?>                    
                        <tr>                            
                            <td style="text-align:left;"><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                            <td>
                                <?php
                                $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                ?>
                            </td>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                            <td><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                            <td>
                                <?php
                                if (!empty($value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']) && !empty($assigned_inspectors_list[$value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']]))
                                    echo implode(";<br />", array_filter($assigned_inspectors_list[$value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']]));
                                ?>
                            </td>
                            <td><?php echo date("d-m-Y", strtotime($value['SupervisionModuleAssignedInspectorApprovalDetail']['inspection_date'])); ?></td>
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                echo $this->Js->link('Cancel', array('controller' => 'SupervisionModuleFieldInspectionInspectorDetails', 'action' => 'cancel', $value['SupervisionModuleAssignedInspectorApprovalDetail']['supervision_basic_id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to Cancel this Inspector assignment ?', 'title' => 'Cancel this Inspector assignment.')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <?php
                if (count($values_assigned) > 1) {
                    echo '<div class="btns-div" style="padding:7px; text-align:center;">'
                    . $this->Js->link('Cancel All', array('controller' => 'SupervisionModuleFieldInspectionInspectorDetails', 'action' => 'cancel_all'), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Cancel all the Inspector assignment ?', 'title' => 'Cancel all the Inspector assignment')))
                    . '</div>';
                }
            }
            ?>
        </fieldset>

        <fieldset style="margin-top:10px">
            <legend>Inspector Assign Pending</legend>
            <?php
            if (empty($values_pending) || !is_array($values_pending) || count($values_pending) < 1) {
                echo '<p class="error-message">' . 'There is no pending form for Inspector assign !' . '</p>';
            } else {
                ?>
                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                        echo "<th style='min-width:250px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                        echo "<th style='width:115px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_pending as $value) { ?>
                        <tr>
                            <td style="text-align:left;"><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                            <td>
                                <?php
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                echo $mfiFullName . ((!empty($mfiFullName) && !empty($mfiName)) ? " (<strong>" . $mfiName . "</strong>)" : $mfiName);
                                ?>
                            </td>
                            <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                            <td><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                echo $this->Js->link('Address Details', array('controller' => 'BasicModuleBranchInfos', 'action' => 'preview', $value['BasicModuleBranchInfo']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
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

            <?php } ?>
        </fieldset>

        <?php if (!empty($allRows) || (!empty($values_pending) && count($values_pending) > 0)) { ?>
            <div class="btns-div">                
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>
                        <td></td>
                        <td>
                            <?php
                            if (!empty($values_pending) && count($values_pending) > 0)
                                echo $this->Js->link('Assign', array('controller' => 'SupervisionModuleFieldInspectionInspectorDetails', 'action' => 'assign'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Inspector Assign for Inspection.')));
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($allRows))
                                echo $this->Js->link('Re-assign', array('controller' => 'SupervisionModuleFieldInspectionInspectorDetails', 'action' => 're_assign'), array_merge($pageLoading, array('class' => 'mybtns')));
                            ?>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
        <?php } ?>
    </fieldset>
</div>