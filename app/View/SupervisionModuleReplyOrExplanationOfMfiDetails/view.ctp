<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}
$isAdmin = (!empty($user_group_ids) && in_array(1, $user_group_ids));
$title = "Explanation Against Letter";
$pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
$this->Paginator->options($pageLoading);

$nf = new NumberFormatter("en-US", NumberFormatter::ORDINAL);
?>
<div>
    <fieldset>
        <legend><?php echo $title; ?></legend>

        <div class="form">
            <?php echo $this->Form->create('SupervisionModuleReplyOrExplanationOfMfiDetailCompleted'); ?>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search Option</td>
                    <td>
                        <?php
                        echo $this->Form->input('search_option_completed', array('label' => false, 'style' => 'width:200px',
                            'options' =>
                            array('BasicModuleBasicInformation.full_name_of_org' => "Organization's Full Name",
                                'BasicModuleBasicInformation.short_name_of_org' => "Organization's Short Name",
                                'BasicModuleBasicInformation.license_no' => 'License No.')
                        ));
                        ?>
                    </td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('search_keyword_completed', array('label' => false, 'style' => 'width:250px')); ?></td>
                    <td style="text-align:left;">
                        <?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?>
                    </td>
                </tr>
            </table>
            <?php echo $this->Form->end(); ?>

            <fieldset>
                <legend>Completed</legend>

                <?php
                if (empty($completed_values) || !is_array($completed_values) || count($completed_values) < 1) {
                    echo '<p class="error-message">No data is available !</p>';
                } else {
                    ?>
                    <?php if (!empty($completed_values) && is_array($completed_values) && count($completed_values) > 0) { ?>

                        <table class="view">
                            <tr>
                                <?php
                                echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                                echo "<th style='min-width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                echo "<th style='min-width:125px;'>" . $this->Paginator->sort('SupervisionModuleIssueLetterToMfiDetail.letter_serial_no', 'Letter No. & Subject') . "</th>";
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('SupervisionModuleIssueLetterToMfiDetail.issue_date', 'Issue Date') . "</th>";
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('SupervisionModuleReplyOrExplanationOfMfiDetail.explanation_giving_date', 'Explanation Date') . "</th>";
                                echo "<th style='width:85px;'>Action</th>";
                                ?>
                            </tr>
                            <?php foreach ($completed_values as $value) { ?>                
                                <tr>
                                    <td><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                    <td>
                                        <?php
                                        $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                        $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                        if (!empty($orgName))
                                            $orgName = "<strong>" . $orgName . ":</strong> ";
                                        if (!empty($orgFullName))
                                            $orgName = $orgName . $orgFullName;
                                        echo $orgName;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (!empty($value['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no']))
                                            echo "<strong>"
                                            . $nf->format($value['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no'])
                                            . " Letter : </strong>"
                                            . $value['SupervisionModuleIssueLetterToMfiDetail']['msg_subject'];
                                        else
                                            echo $value['SupervisionModuleIssueLetterToMfiDetail']['msg_subject'];
                                        ?>
                                    </td>
                                    <td style='text-align:center;'>
                                        <?php
                                        if (!empty($value['SupervisionModuleIssueLetterToMfiDetail']['issue_date']))
                                            echo date("d-m-Y", strtotime($value['SupervisionModuleIssueLetterToMfiDetail']['issue_date']));
                                        ?>
                                    </td>
                                    <td style='text-align:center;'>
                                        <?php
                                        if (!empty($value['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_giving_date']))
                                            echo date("d-m-Y", strtotime($value['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_giving_date']));
                                        ?>
                                    </td>
                                    <td style="padding:2px; text-align:center;">
                                        <?php
                                        echo $this->Js->link('Prepare Next Letter', array('controller' => 'SupervisionModulePrepareLetterDetails', 'action' => 'prepare_letter', $value['SupervisionModuleBasicInformation']['id'], 0, 1,
                                            "?" => array("this_state_ids" => $other_state_ids, "approval_states" => $next_letter_approval_states)), array_merge($pageLoading, array('class' => 'btnlink')))
                                        . $this->Js->link('Details', array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails', 'action' => 'preview', $value['SupervisionModuleBasicInformation']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')))
                                        . $this->Js->link('Back', array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails', 'action' => 'back_to_previous_state', $value['SupervisionModuleBasicInformation']['id'], $value['SupervisionModuleIssueLetterToMfiDetail']['id']), array_merge($pageLoading, array('confirm' => "Are you sure to back to previous state?", 'class' => 'btnlink')));
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>  
                        <?php
                    }
                }
                ?>

                <?php if (!empty($completed_values) && $this->Paginator->param('pageCount') > 1) { ?>
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

            <fieldset>
                <legend>Pending</legend>
                <?php
                if (empty($pending_values) || !is_array($pending_values) || count($pending_values) < 1) {
                    echo '<p class="error-message">No data is available !</p>';
                } else {
                    ?>

                    <?php
                    if ($isAdmin && (!empty($pending_values) && is_array($pending_values) && count($pending_values) > 0 && !$opt_all)) {
                        echo $this->Form->create('SupervisionModuleReplyOrExplanationOfMfiDetailPending');
                        ?>

                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search by</td>
                                <td>
                                    <?php
                                    $options = array('BasicModuleBasicInformation.full_name_of_org' => "Organization's Full Name",
                                        'BasicModuleBasicInformation.short_name_of_org' => "Organization's Short Name",
                                        'BasicModuleBasicInformation.license_no' => 'License No.');

                                    echo $this->Form->input('search_option_pending', array('label' => false, 'style' => 'width:215px', 'options' => $options));
                                    ?>
                                </td>
                                <td style="font-weight:bold;">:</td>
                                <td><?php echo $this->Form->input('search_keyword_pending', array('label' => false, 'style' => 'width:250px')); ?></td>
                                <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?></td>
                                <td>
                                    <?php
                                    if (!empty($opt_all) && $opt_all) {
                                        echo $this->Js->link('View All', array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails', 'action' => 'view', 'all'), array_merge($pageLoading, array('class' => 'mybtns sbtns')));
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>

                        <?php
                        echo $this->Form->end();
                    }
                    ?>

                    <?php if (!empty($pending_values) && is_array($pending_values) && count($pending_values) > 0) { ?>

                        <table class="view">
                            <tr>
                                <?php
                                echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                                echo "<th style='width:85px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                                echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                echo "<th style='min-width:125px;'>" . $this->Paginator->sort('SupervisionModuleIssueLetterToMfiDetail.letter_serial_no', 'Letter No. & Subject') . "</th>";
                                echo "<th style='width:80px;'>" . $this->Paginator->sort('SupervisionModuleIssueLetterToMfiDetail.issue_date', 'Issue Date') . "</th>";
                                echo "<th style='width:150px;'>Action</th>";
                                ?>
                            </tr>
                            <?php foreach ($pending_values as $value) { ?>                
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
                                    <td>
                                        <?php
                                        if (!empty($value['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no']))
                                            echo "<strong>"
                                            . $nf->format($value['SupervisionModuleIssueLetterToMfiDetail']['letter_serial_no'])
                                            . " Letter : </strong>"
                                            . $value['SupervisionModuleIssueLetterToMfiDetail']['msg_subject'];
                                        else
                                            echo $value['SupervisionModuleIssueLetterToMfiDetail']['msg_subject'];
                                        ?>
                                    </td>
                                    <td style='text-align:center;'>
                                        <?php
                                        if (!empty($value['SupervisionModuleIssueLetterToMfiDetail']['issue_date']))
                                            echo date("d-m-Y", strtotime($value['SupervisionModuleIssueLetterToMfiDetail']['issue_date']));
                                        ?>
                                    </td>
                                    <td style="height:30px; padding:2px; text-align:center;">
                                        <?php
                                        echo $this->Js->link('Details', array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails', 'action' => 'preview', $value['SupervisionModuleBasicInformation']['id'], 1), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')))
                                        . $this->Js->link('Explanation', array('controller' => 'SupervisionModuleReplyOrExplanationOfMfiDetails', 'action' => 'explanation_against_letter', $value['SupervisionModuleIssueLetterToMfiDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>

                        <?php
                    }
                }
                ?>
            </fieldset>
        </div>
    </fieldset>
</div>