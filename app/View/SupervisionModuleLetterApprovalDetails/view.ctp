
<div>
    <?php
    if (isset($msg) && !empty($msg)) {
        if (is_array($msg)) {
            echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
        } else {
            echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
        }
    }
    if (empty($approvalType))
        $approvalType = "Director's";

    //$title = "$approvalType's Approval of Letter";
    $title = htmlspecialchars("$approvalType's " . $approval_title . " of Letter", ENT_QUOTES);

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
                echo $this->Form->create('SupervisionModuleLetterApprovalSearch');
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
                                    'BasicModuleBasicInformation.license_no' => 'License No.')
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


            <fieldset style="margin-top:7px;">
                <legend><?php echo $approval_title ?> Completed</legend>

                <?php
                if (empty($values_approved) || !is_array($values_approved) || count($values_approved) < 1) {
                    echo '<p class="error-message"> No data is available! </p>';
                } else {
                    ?>

                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                            echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:180px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_approved as $value) { ?>
                            <tr>
                                <td><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>
                                <td><?php
                                    echo $this->Js->link('Cancel ' . $approval_title, array('controller' => 'SupervisionModuleLetterApprovalDetails', 'action' => 'cancel_approval', $value['SupervisionModulePrepareLetterDetail']['supervision_basic_id']), array_merge($pageLoading, array('class' => 'btnlink', 'confirm' => 'Are you sure to cancel approval ?', 'title' => "$approvalType approval cancel")))
                                    . $this->Js->link('Details', array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'preview', $value['SupervisionModulePrepareLetterDetail']['supervision_basic_id'], null), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>

                    <?php
                    if (count($values_approved) > 1) {
                        echo '<div class="btns-div" style="padding:7px; text-align:center;">'
                        . $this->Js->link('Update All', array('controller' => 'SupervisionModuleLetterApprovalDetails', 'action' => 'approve_edit_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Edit Approval of All Letter')))
                        . '</div>';
                    }
                    ?>

                    <?php if ($values_approved && $this->Paginator->param('pageCount') > 1) { ?>
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

            <fieldset>
                <legend><?php echo $approval_title ?> Pending</legend>
                <?php
                if (empty($values_not_approved) || !is_array($values_not_approved) || count($values_not_approved) < 1) {
                    echo '<p class="error-message">';
                    echo 'No data is available !';
                    echo '</p>';
                } else {
                    ?>

                    <table class="view">
                        <tr>
                            <?php
                            echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                            echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:135px;'>Action</th>";
                            ?>
                        </tr>
                        <?php foreach ($values_not_approved as $value) { ?>
                            <tr>
                                <td><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                                <td>
                                    <?php
                                    $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                    $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                    echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                    ?>
                                </td>                                
                                <td style="height:30px; padding:2px; text-align:center;"> 
                                    <?php
                                    echo $this->Js->link($btn_title, array('controller' => 'SupervisionModuleLetterApprovalDetails', 'action' => 'approve', $value['SupervisionModulePrepareLetterDetail']['supervision_basic_id']), array_merge($pageLoading, array('class' => 'btnlink', 'title' => $approval_title . ' of letter')))
                                    . $this->Js->link('Details', array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'preview', $value['SupervisionModuleBasicInformation']['id'], null), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <?php
                    if (count($values_not_approved) > 1) {
                        echo '<div class="btns-div" style="padding:7px; text-align:center;">'
                        . $this->Js->link('Approve All', array('controller' => 'SupervisionModuleLetterApprovalDetails', 'action' => 'approve_all'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => 'Approval for All Assigned Inspector')))
                        . '</div>';
                    }
                }
                ?>
            </fieldset>
        </div>
    </fieldset>
</div>