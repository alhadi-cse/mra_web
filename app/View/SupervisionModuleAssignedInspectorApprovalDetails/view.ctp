<div>    
    <?php
    if (empty($approvalType))
        $approvalType = "Director's";

    $title = "$approvalType's Approval for Assigned Inspector";

    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <fieldset>
            <legend>Approval Completed</legend>
            <?php
            if (empty($values_approved) || !is_array($values_approved) || count($values_approved) < 1) {
                echo '<p class="error-message">No data is available !</p>';
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
                    <?php foreach ($values_approved as $value) { ?>                    
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
                                if (!empty($value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']) && !empty($approved_inspectors_list[$value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']]))
                                    echo implode(";<br />", array_filter($approved_inspectors_list[$value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']]));
                                ?>
                            </td>
                            <td><?php echo date("d-m-Y", strtotime($value['SupervisionModuleAssignedInspectorApprovalDetail']['inspection_date'])); ?></td>
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                echo $this->Js->link('Details', array('controller' => 'SupervisionModuleAssignedInspectorApprovalDetails', 'action' => 'preview', $value['SupervisionModuleAssignedInspectorApprovalDetail']['supervision_basic_id'], $value['BasicModuleBranchInfo']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')))
                                . $this->Js->link('Cancel Approval', array('controller' => 'SupervisionModuleAssignedInspectorApprovalDetails', 'action' => 'cancel_approval', $value['SupervisionModuleAssignedInspectorApprovalDetail']['supervision_basic_id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to Cancel Approval ?', 'title' => "$approvalType Approval Cancel")));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <?php
                if (count($values_approved) > 1) {
                    echo '<div class="btns-div" style="padding:7px; text-align:center;">'
                    . $this->Js->link('Cancell All', array('controller' => 'SupervisionModuleAssignedInspectorApprovalDetails', 'action' => 'cancel_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Approval for All Assigned Inspector')))
                    . '</div>';
                }
            }
            ?>
        </fieldset>
        <fieldset>
            <legend>Approval Pending</legend>
            <?php
            if (empty($values_not_approved) || !is_array($values_not_approved) || count($values_not_approved) < 1) {
                echo '<p class="error-message">No data is available !</p>';
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
                    <?php foreach ($values_not_approved as $value) { ?>                    
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
                                if (!empty($value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']) && !empty($not_approved_inspectors_list[$value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']]))
                                    echo implode(";<br />", array_filter($not_approved_inspectors_list[$value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_schedule_id']]));
                                ?>
                            </td>
                            <td><?php echo date("d-m-Y", strtotime($value['SupervisionModuleAssignedInspectorApprovalDetail']['inspection_date'])); ?></td>
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                echo $this->Js->link('Details', array('controller' => 'SupervisionModuleAssignedInspectorApprovalDetails', 'action' => 'preview', $value['SupervisionModuleAssignedInspectorApprovalDetail']['supervision_basic_id'], $value['BasicModuleBranchInfo']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')))
                                . $this->Js->link('Approve', array('controller' => 'SupervisionModuleAssignedInspectorApprovalDetails', 'action' => 'approve', $value['SupervisionModuleAssignedInspectorApprovalDetail']['supervision_basic_id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to approve Inspection Schedule?', 'title' => 'Approval of Inspection Schedule')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <?php
                if (count($values_not_approved) > 1) {
                    echo '<div class="btns-div" style="padding:7px; text-align:center;">'
                    . $this->Js->link('Approve All', array('controller' => 'SupervisionModuleAssignedInspectorApprovalDetails', 'action' => 'approve_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Approval for All Assigned Inspector')))
                    . '</div>';
                }
            }
            ?>
        </fieldset>
    </fieldset>
</div>