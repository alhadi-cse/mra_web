<div id="frmUserInfo_view">
    <?php
    $is_committee_group = $this->Session->read('Committee.Is_Group');
    $title = '';
    if ($is_committee_group == 0) {
        $title = "User Information";
    } else {
        $title = "Committee Member Information";
    }
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">
            <?php echo $this->Form->create('AdminModuleUser'); ?>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search By</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                            'options' => array('AdminModuleUserGroup.group_name' => 'User Group',
                                'AdminModuleUser.user_name' => 'User Id',
                                'AdminModuleUserProfile.full_name_of_user' => 'User Name',
                                'AdminModuleUserProfile.designation_of_user' => 'Designation')));
                        ?>
                    </td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                    <td style="text-align:left;"><?php echo $this->Js->submit('Search', $pageLoading); ?></td>               
                </tr>
            </table>
            <?php echo $this->Form->end(); ?> 

            <div id="searching">
                <table class="view">
                    <tr>
                        <?php
                        $action_width = '230px';
                        $member_or_activation = '';
                        if ($is_committee_group == 0) {
                            $member_or_activation = $this->Paginator->sort('AdminModuleUser.user_name', 'Activation Status');
                        } else if ($is_committee_group == 1) {
                            $member_or_activation = $this->Paginator->sort('LookupUserCommitteeMemberType.committee_member_type', 'Member Type');
                            $action_width = '150px';
                        }

                        echo "<th style='width:120px;'>" . $this->Paginator->sort('AdminModuleUserGroup.group_name', 'User Group') . "</th>";
                        echo "<th style='width:175px;'>" . $this->Paginator->sort('AdminModuleUser.user_name', 'User ID') . "</th>";
                        echo "<th style='width:170px;'>" . $this->Paginator->sort('AdminModuleUser.name_with_designation_and_division', 'Name & Designation') . "</th>";

                        echo "<th style='width:70px;'>$member_or_activation</th>";
                        echo "<th style='width:$action_width !important;'>Action</th>";
                        ?>
                    </tr>
                    <?php
                    foreach ($values as $value) {
                        $activation_status_id = $value['AdminModuleUser']['activation_status_id'];
                        ?>
                        <tr>
                            <td><?php echo $value['AdminModuleUserGroup']['group_name']; ?></td>
                            <td style="text-align:center;"><?php echo $value['AdminModuleUser']['user_name']; ?></td>  
                            <td><?php echo $value['AdminModuleUser']['name_with_designation_and_division']; ?></td>       
                            <td style="text-align:center;">
                                <?php
                                if ($is_committee_group == 0) {
                                    echo (!empty($activation_status_id) ? 'Active' : 'Inactive');
                                } else if ($is_committee_group == 1) {
                                    echo $value['LookupUserCommitteeMemberType']['committee_member_type'];
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($is_committee_group == 0) {
                                    echo $this->Js->link((!empty($activation_status_id) ? 'Deactivate' : 'Activate'), array('controller' => 'AdminModuleUsers', 'action' => 'activate_deactivate', $activation_status_id, $value['AdminModuleUser']['user_name']), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'width:60px;', 'confirm' => 'Are you sure to change activation ?')));
                                }

                                echo $this->Js->link('Edit', array('controller' => 'AdminModuleUsers', 'action' => 'edit', $is_committee_group, $value['AdminModuleUser']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'width:50px;'))) .
                                $this->Js->link('Details', array('controller' => 'AdminModuleUsers', 'action' => 'details', $is_committee_group, $value['AdminModuleUser']['id'], $value['AdminModuleUserGroup']['id']), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink', 'style' => 'width:50px;')));
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

        </div>
        <?php if ($values && $this->Paginator->param('pageCount') > 1) { ?>
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
                        <?php echo $this->Js->link('Add New User', array('controller' => 'AdminModuleUsers', 'action' => 'add', $is_committee_group), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Add a New User'))); ?>     
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
