<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = "Identification of Problems";
    $isAdmin = !empty($user_group_id) && $user_group_id == 1;
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>

    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div>
            <div class="form">
                <?php echo $this->Form->create('SupervisionModuleIdentifiedProblemDetail'); ?>
                <table cellpadding="0" cellspacing="0" border="0">                           
                    <tr>
                        <td style="padding-left:15px; text-align:right;">Search by</td>
                        <td>
                            <?php
                            $options = array('SupervisionModuleOrgSelectionDetail.supervision_case_title' => 'Inspection Title',
                                             'BasicModuleBasicInformation.full_name_of_org' => 'Name of Organization',
                                             'LookupSupervisionTypeOfProblem.type_of_problems' => 'Type of Problem',
                                             'LookupSupervisionTitleOfProblem.title_of_problems' => 'Title of Problem'
                                        );
                            echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:215px', 'options' => $options));
                            ?>
                        </td>
                        <td style="font-weight:bold;">:</td>
                        <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>
                        <td style="padding-right:5px"><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch'))); ?></td>
                        <td>
                            <?php
                            if (!empty($opt_all) && $opt_all) {
                                echo $this->Js->link('View All', array('controller' => 'SupervisionModuleIdentifiedProblemDetails', 'action' => 'view', 'all'), array_merge($pageLoading, array('class' => 'mybtns sbtns')));
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                <?php echo $this->Form->end(); ?> 

                <div style="width:780px; height:auto; overflow-x:auto;">
                    <?php
                    if ($values == null || !is_array($values) || count($values) < 1) {
                        echo '<p class="error-message">';
                        echo 'Did not find any data !';
                        echo '</p>';
                    } else {
                        ?>

                        <table class="view">
                            <tr>
                                <?php
                                echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Inspection Title') . "</th>";
                                echo "<th style='width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                                echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionTypeOfProblem.type_of_problems', 'Type of Problems') . "</th>";
                                echo "<th style='width:100px;'>" . $this->Paginator->sort('LookupSupervisionTitleOfProblem.title_of_problems', 'Title of Problems') . "</th>";
                                echo "<th style='width:102px;'>Action</th>";
                                ?>
                            </tr>
                            <?php foreach ($values as $value) { ?>
                                <tr>
                                    <td><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                    <td><?php echo $value['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                                    <td><?php echo $value['LookupSupervisionTypeOfProblem']['type_of_problems']; ?></td> 
                                    <td><?php echo $value['LookupSupervisionTitleOfProblem']['title_of_problems']; ?></td>        
                                    <td style="height:30px; padding: 2px; text-align: center;"> 
                                    <?php
                                        echo $this->Js->link('Edit', array('controller' => 'SupervisionModuleIdentifiedProblemDetails', 'action' => 'edit', $value['SupervisionModuleIdentifiedProblemDetail']['id'], $value['SupervisionModuleIdentifiedProblemDetail']['supervision_basic_id']), array_merge($pageLoading, array('class' => 'btnlink')));
                                        echo $this->Js->link('Details', array('controller' => 'SupervisionModuleIdentifiedProblemDetails', 'action' => 'preview', $value['SupervisionModuleIdentifiedProblemDetail']['id']), array_merge($pageLoading, array('class' => 'btnlink', 'update' => '#popup_div')));
                                    ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>
                <?php if ($values && $this->Paginator->param('pageCount') > 1) { ?>
                    <div class="paginator">
                    <?php
                        echo $this->Paginator->prev('<<', array('class' => 'prevPg'), null, array('class' => 'prevPg no_link')) .
                        $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')) .
                        $this->Paginator->next('>>', array('class' => 'nextPg'), null, array('class' => 'nextPg no_link'));
                    ?>
                    </div>
                <?php } ?>
            </div>
            <div class="btns-div">
                <table style="margin:0 auto; padding:0;" cellspacing="7">
                    <tr>
                        <td></td>
                        <td><?php echo $this->Js->link('Add Problems/Issues', array('controller' => 'SupervisionModuleIdentifiedProblemDetails', 'action' => 'add_problems?thisStateIds='.$thisStateIds[0].'&viewable_user_groups='.$viewable_user_groups), array_merge($pageLoading, array('class' => 'mybtns'))); ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </fieldset>
<?php } ?>