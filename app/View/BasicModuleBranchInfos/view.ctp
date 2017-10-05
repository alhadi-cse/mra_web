<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = "Office/Branch Information";
    $isAdmin = !empty($user_group_id) && in_array(1, $user_group_id);
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div>
            <?php
            if (!empty($all_branch_info)) {
                echo "<p style='color:#072fa3;font-weight:bold;'>"
                . "Total no. of Office/Branch: $total_branch_count"
                . "</p>";

                if ($user_group_id == '2' || !empty($org_id)) {
                    $name_of_org = $all_branch_info[0]['BasicModuleBranchInfo']['name_of_org'];
                    $license_no = $all_branch_info[0]['BasicModuleBasicInformation']['license_no'];

                    echo "<p style='margin:5px;'>Name of Organization : $name_of_org </p>"
                    . (empty($license_no) ? "" : "<p style='margin:5px;'>License No. : $license_no</p>");
                }
            }
            ?> 
            <div class="form"> 
                <table>
                    <?php if (!empty($all_branch_info) || $opt_all) { ?>
                        <tr> 
                            <td>
                                <?php echo $this->Form->create('SearchOption'); ?>
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="padding-left:15px; text-align:right;">Search by</td>
                                        <td>
                                            <?php
                                            $options = array('BasicModuleBranchInfo.branch_code' => 'Branch Code',
                                                'BasicModuleBranchInfo.branch_name' => 'Branch Name',
                                                'LookupBasicOfficeType.office_type' => 'Office Type',
                                                'LookupAdminBoundaryDistrict.district_name' => 'District Name',
                                                'LookupAdminBoundaryUpazila.upazila_name' => 'Upazila Name');

                                            if (empty($org_id)) {
                                                $options = array_merge(array('BasicModuleBasicInformation.license_no' => 'License No.',
                                                    //'BasicModuleBasicInformation.name_of_org' => 'Organization\'s Name',
                                                    'BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                    'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name'), $options);
                                            }

                                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:215px; padding:0;', 'options' => $options));
                                            ?>
                                        </td>
                                        <td style="font-weight:bold;">:</td>
                                        <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                                        <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?></td>
                                        <td>
                                            <?php
                                            if (!empty($opt_all)) {
                                                echo $this->Js->link('View All', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view', 'all'), array_merge($pageLoading, array('class' => 'mybtns sbtns')));
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <?php echo $this->Form->end(); ?> 
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <div style="width:780px; height:auto; overflow-x:auto;">
                                <?php
                                if (empty($all_branch_info) || count($all_branch_info) < 1) {
                                    echo '<p class="error-message">No branch information available !</p>';
                                    $btn_title = 'Add an Office';
                                } else {
                                    $btn_title = 'Add Another Office';
                                    ?>
                                    <table class="view">
                                        <tr>
                                            <?php
                                            $width_th = 'width:55px;';
                                            if (empty($org_id)) {
                                                echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                                                echo "<th style='width:540px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.name_of_org', 'Name of Organization') . "</th>";
                                            } else {
                                                $width_th = 'width:120px;';
                                            }

                                            echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.branch_name', 'Branch Name') . "</th>";
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupBasicOfficeType.office_type', 'Office Type') . "</th>";
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryUpazila.upazila_name', 'Upazila') . "</th>";
                                            echo "<th style='width:75px;'>Picture</th>";
                                            echo "<th style=$width_th>Action</th>";
                                            ?>
                                        </tr>
                                        <?php
                                        foreach ($all_branch_info as $branch_info) {
                                            $style = "";
                                            if ($branch_info['BasicModuleBranchInfo']['is_active'] == '1') {
                                                $style = "style='background-color:#fff;'";
                                            } elseif ($branch_info['BasicModuleBranchInfo']['is_active'] == '0') {
                                                $style = "style='background-color:#fe2e2b;'";
                                            }
                                            ?>
                                            <tr <?php echo $style ?>>
                                                <?php if (empty($org_id)) { ?>
                                                    <td style="text-align:center;"><?php echo $branch_info['BasicModuleBasicInformation']['license_no']; ?></td>
                                                    <td style="text-align:left;"><?php echo $branch_info['BasicModuleBranchInfo']['name_of_org']; ?></td>
                                                <?php } ?>
                                                <td><?php echo $branch_info['BasicModuleBranchInfo']['branch_name']; ?></td>
                                                <td><?php echo $branch_info['LookupBasicOfficeType']['office_type']; ?></td>
                                                <td><?php echo $branch_info['LookupAdminBoundaryDistrict']['district_name']; ?></td> 
                                                <td><?php echo $branch_info['LookupAdminBoundaryUpazila']['upazila_name']; ?></td>
                                                <td style="text-align:center;">
                                                    <?php
                                                    if (!empty($branch_info['BasicModuleBranchInfo']['image_name'])) {
                                                        $img_url = $branch_info['BasicModuleBranchInfo']['image_name'];
                                                        $rand_number = rand(1000, 99999);
                                                        echo $this->Html->image('/files/uploads/branches/' . $img_url . "?$rand_number", array('plugin' => false, 'alt' => 'no image', 'style' => 'width:75px; height:50px; text-align:center;display: block;margin: auto;'));
                                                    } else {
                                                        echo 'no image';
                                                    }
                                                    ?>
                                                </td> 
                                                <td style="height:30px; padding:2px; text-align:justify;">
                                                    <?php
                                                    if (!empty($org_id)) {
                                                        echo $this->Js->link('Edit', array('controller' => 'BasicModuleBranchInfos', 'action' => 'edit', $branch_info['BasicModuleBranchInfo']['id'], $org_id), array_merge($pageLoading, array('class' => 'btnlink')));
                                                    }
                                                    /* elseif ($user_group_id=='2') {
                                                      $width = 'width:135px';
                                                      if($branch_info['BasicModuleBranchInfo']['is_active']=='1'){
                                                      if($branch_info['BasicModuleBranchInfo']['is_approved']=='0'){
                                                      echo $this->Js->link('Edit Deactivation Request', array('controller'=>'BasicModuleBranchInfos', 'action'=>'edit_deactivation_request', $branch_info['BasicModuleBranchInfo']['id']),
                                                      array_merge($pageLoading, array('class'=>'btnlink','style'=>$width)));
                                                      echo $this->Js->link('Cancel Deactivation Request', array('controller'=>'BasicModuleBranchInfos', 'action'=>'cancel_deactivation_request', $branch_info['BasicModuleBranchInfo']['id']),
                                                      array_merge($pageLoading, array('confirm' => 'Are you sure to cancel?','class'=>'btnlink','style'=>'width:135px')));
                                                      }
                                                      else {
                                                      echo $this->Js->link('Request for Branch Deactivation', array('controller'=>'BasicModuleBranchInfos', 'action'=>'branch_deactivation_request',$branch_info['BasicModuleBranchInfo']['id']),
                                                      array_merge($pageLoading, array('class'=>'btnlink','style'=>$width)));
                                                      }
                                                      }
                                                      elseif($branch_info['BasicModuleBranchInfo']['is_active']=='0'){
                                                      if($branch_info['BasicModuleBranchInfo']['is_approved']=='0'){
                                                      echo $this->Js->link('Edit Aactivation Request', array('controller'=>'BasicModuleBranchInfos', 'action'=>'edit_activation_request', $branch_info['BasicModuleBranchInfo']['id']),
                                                      array_merge($pageLoading, array('class'=>'btnlink','style'=>$width)));
                                                      echo $this->Js->link('Cancel Aactivation Request', array('controller'=>'BasicModuleBranchInfos', 'action'=>'cancel_activation_request', $branch_info['BasicModuleBranchInfo']['id']),
                                                      array_merge($pageLoading, array('confirm' => 'Are you sure to cancel?','class'=>'btnlink','style'=>$width)));
                                                      }
                                                      else {
                                                      echo $this->Js->link('Request for Branch Activation', array('controller'=>'BasicModuleBranchInfos', 'action'=>'branch_activation_request',$branch_info['BasicModuleBranchInfo']['id']),
                                                      array_merge($pageLoading, array('class'=>'btnlink','style'=>$width)));
                                                      }
                                                      }
                                                      } */
                                                    echo $this->Js->link('Details', array('controller' => 'BasicModuleBranchInfos', 'action' => 'preview', $branch_info['BasicModuleBranchInfo']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
//                                                    if (!empty($org_id)) {
////                                                echo $this->Js->link('Delete', array('controller' => 'BasicModuleBranchInfos','action' => 'delete', $branch_info['BasicModuleBranchInfo']['id']), 
////                                                    array_merge($pageLoading, array('confirm' => 'Are you sure to delete?', 'success' => "msg.init('success', '$title', 'A branch information has been deleted successfully.');", 'error' => "msg.init('error', '$title', 'Deletion failed!');",'class'=>'btnlink')));
//                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <?php if ($all_branch_info && $this->Paginator->param('pageCount') > 1) { ?>
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
                            <?php
                            if (!empty($org_id)) {
                                echo $this->Js->link($btn_title, array('controller' => 'BasicModuleBranchInfos', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns')));
                                echo '</td><td>';
                                echo $this->Html->link('Export All Details', array('controller' => 'BasicModuleBranchInfos', 'action' => 'export_all_branch', $org_id), array('class' => 'mybtns', 'target' => '_blank'));
                            } else {
                                echo $this->Js->link('Summary of Branch', array('controller' => 'BasicModuleBranchInfos', 'action' => 'submission_summary_all'), array_merge($pageLoading, array('class' => 'mybtns')));
                                echo '</td><td>';

                                echo $this->Js->link('Summary of Branch', array('controller' => 'BasicModuleBranchInfos', 'action' => 'branch_submission_summary'), array_merge($pageLoading, array('class' => 'mybtns')));

                                echo '</td><td>';
                                echo $this->Html->link('Export Branch Summary', array('controller' => 'BasicModuleBranchInfos', 'action' => 'export_branch_summary'), array('class' => 'mybtns', 'target' => '_blank'));

                                echo '</td><td>';
                                echo $this->Html->link('Export Branch without H.O.', array('controller' => 'BasicModuleBranchInfos', 'action' => 'export_branch_without_ho'), array('class' => 'mybtns', 'target' => '_blank'));

                                echo '</td><td>';
                                echo $this->Html->link('Export All (Xlsx)', array('controller' => 'BasicModuleBranchInfos', 'action' => 'export_all_branch'), array('class' => 'mybtns', 'target' => '_blank', 'confirm' => 'Its takes several (4/5) minutes. Are you sure to Export ?'));

                                echo '</td><td>';
                                echo $this->Html->link('Export All (CSV)', array('controller' => 'BasicModuleBranchInfos', 'action' => 'export_all_branch_csv'), array('class' => 'mybtns', 'target' => '_blank'));
                            }
                            ?>
                        </td>

                        <td></td>
                    </tr>
                </table>
            </div> 
        </div> 
    </fieldset>
<?php } ?>
