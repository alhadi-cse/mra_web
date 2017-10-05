<div id="frmUserInfo_view">
    <?php
    $title = 'Branch User Information';
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>
    
    <fieldset>
        <legend>
            <?php echo $title; ?>
        </legend>
        <div class="form">

            <?php echo $this->Form->create('AdminModuleUser'); ?>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search By</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:200px',
                            'options' => array('AdminModuleUserProfile.BasicModuleBranchInfo.branch_name' => 'Name of Branch',
                                'AdminModuleUser.user_name' => 'User Name'))
                        );
                        ?>
                    </td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px'));
                        ?>
                    </td>
                    <td style="text-align:left;">
                        <?php
                        echo $this->Js->submit('Search', $pageLoading);
                        ?>
                    </td>
                </tr>
            </table>
            <?php echo $this->Form->end(); ?>

            <div style="border:1px solid #ededed; margin:5px 0 0 0; padding:7px; background-color:#f3f5fa; color:#053470;">
                <?php if (!empty($org_name)) echo "Name of Organization: <strong>$org_name</strong>"; ?>
            </div>

            <?php if (!empty($values) && count($values) > 0) { ?>
                <div id="searching">
                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:70px;'>" . $this->Paginator->sort('AdminModuleUser.user_name', 'User ID') . "</th>";
                            echo "<th style='width:120px;'>" . $this->Paginator->sort('AdminModuleUserProfile.full_name_of_user', 'Name & Designation') . "</th>";
                            echo "<th style='width:70px;'>" . $this->Paginator->sort('AdminModuleUserProfile.mobile_no', 'Mobile No.') . "</th>";
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('AdminModuleUserProfile.email', 'E-mail') . "</th>";
                            echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.branch_with_address', 'Branch') . "</th>";
                            echo "<th style='width:75px;'>" . $this->Paginator->sort('AdminModuleUser.activation_status_id', 'Activation Status') . "</th>";
                            echo "<th style='width:100px;'>Action</th>";
                            ?>
                        </tr>
                        <?php
                        foreach ($values as $value) {
                            $activation_status_id = $value['AdminModuleUser']['activation_status_id'];
                            ?>
                            <tr>                                        
                                <td style="text-align:center;"><?php echo $value['AdminModuleUser']['user_name']; ?></td>
                                <td><?php echo $value['AdminModuleUserProfile']['full_name_of_user']; ?></td>
                                <td><?php echo $value['AdminModuleUserProfile']['mobile_no']; ?></td>
                                <td><?php echo $value['AdminModuleUserProfile']['email']; ?></td>
                                <td style="text-align:justify;"><?php echo $value['AdminModuleUserProfile']['branch_id']; ?></td>
                                <td style="text-align:center;"><?php echo (!empty($activation_status_id) ? 'Active' : 'Inactive'); ?></td>
                                <td>
                                    <?php
                                    echo $this->Js->link((!empty($activation_status_id) ? 'Deactivate' : 'Activate'), array('controller' => 'AdminModuleUsers', 'action' => 'activate_deactivate', $activation_status_id, $value['AdminModuleUser']['user_name']), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'width:108px;', 'confirm' => 'Are you sure to change activation ?'))) .
                                    $this->Js->link('Edit', array('controller' => 'AdminModuleUsers', 'action' => 'edit_branch_user', $value['AdminModuleUserProfile']['user_id'], $value['AdminModuleUserProfile']['org_id'], $value['AdminModuleUserProfile']['branch_id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to edit ?'))) .
                                    $this->Js->link('Details', array('controller' => 'AdminModuleUsers', 'action' => 'branch_user_details', $value['AdminModuleUser']['id']), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php } ?>
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
                        <?php echo $this->Js->link('Add a Branch User', array('controller' => 'AdminModuleUsers', 'action' => 'add_branch_user'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Add a new branch user'))); ?>     
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
