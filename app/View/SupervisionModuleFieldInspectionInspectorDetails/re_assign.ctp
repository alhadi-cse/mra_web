        <div>
    <?php
    if (!empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    $title = "Re-Assign Inspector for Supervision";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    ?> 
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <fieldset>
            <legend>Inspector Assigned</legend>
            <?php
            if (empty($values_assign) || !is_array($values_assign) || count($values_assign) < 1) {
                echo '<p class="error-message">No data is available !</p>';
            } else {
                ?>
                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                        echo "<th style='width:150px;'>" . $this->Paginator->sort('0.inspectors_name_with_designation_and_dept', 'Inspectors Name & Designation') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('SupervisionModuleFieldInspectionInspectorDetail.inspection_date', 'Inspection Date') . "</th>";
                        echo "<th style='width:75px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach ($values_assign as $value) { ?>
                        <tr>
                            <td style="text-align:center;">
                                <?php echo $value['BasicModuleBasicInformation']['license_no']; ?>
                            </td>
                            <td>
                                <?php
                                $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                ?>
                            </td>
                            <td><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                            <td><?php echo $value[0]['inspectors_name_with_designation_and_dept']; ?></td>
                            <td><?php echo date("d-m-Y", strtotime($value['SupervisionModuleFieldInspectionInspectorDetail']['inspection_date'])); ?></td>
                            <td style="height:30px; padding:2px; text-align:center;"> 
                                <?php
                                echo $this->Js->link('Cancel', array('controller' => 'SupervisionModuleFieldInspectionInspectorDetails', 'action' => 'cancel', $value['BasicModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to Cancel this Inspector assignment ?', 'title' => 'Cancel this Inspector assignment.')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <?php
                if (count($values_assign) > 1) {
                    echo '<div class="btns-div" style="padding:7px; text-align:center;">'
                    . $this->Js->link('Cancel All', array('controller' => 'SupervisionModuleFieldInspectionInspectorDetails', 'action' => 'cancel_all'), array_merge($pageLoading, array('class' => 'mybtns', 'confirm' => 'Are you sure to Cancel all the Inspector assignment ?', 'title' => 'Cancel all the Inspector assignment')))
                    . '</div>';
                }
            }
            ?>
        </fieldset>
    </fieldset>
</div>

