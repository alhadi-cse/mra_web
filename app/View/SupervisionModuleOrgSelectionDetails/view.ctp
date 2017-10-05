<div id="frmTypeOfOrg_view">
    <?php
    $title = "Selection of Organization for Supervision";
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div class="form">
            <table>
                <tr>
                    <td style="text-align: justify;font-family: verdana,helvetica,arial;">
                        <?php echo $this->Form->create('SupervisionModuleOrgSelectionDetail'); ?>
                        <table>
                            <tr>
                                <td style="padding-left:15px; text-align:right;">Search Option</td>
                                <td>
                                    <?php
                                    echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:150px',
                                        'options' =>
                                        array('BasicModuleBasicInformation.full_name_of_org' => "Organization's Full Name",
                                            'BasicModuleBasicInformation.short_name_of_org' => "Organization's Short Name",
                                            'BasicModuleBasicInformation.license_no' => "License No.")
                                    ));
                                    ?>
                                </td>
                                <td class="colons">:</td>
                                <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:250px')); ?></td>                        
                                <td><?php echo $this->Js->submit('Search', array_merge($pageLoading, array('url' => "/SupervisionModuleOrgSelectionDetails/view/custom", 'class' => 'btnsearch'))); ?></td>
                                <td><?php echo $this->Js->submit('View All', array_merge($pageLoading, array('url' => "/SupervisionModuleOrgSelectionDetails/view/all", 'class' => 'btnsearch'))); ?></td>
                                <td><?php echo $this->Js->submit('Current', array_merge($pageLoading, array('url' => "/SupervisionModuleOrgSelectionDetails/view/current", 'class' => 'btnsearch'))); ?></td>                                            
                            </tr>
                        </table>
                        <?php echo $this->Form->end(); ?> 
                    </td>        
                </tr>
                <tr>
                    <td>
                        <div id="searching" style="width:780px;"> 
                            <?php
                            if (!$values || !is_array($values) || count($values) < 1) {
                                echo '<p class="error-message">No data is available !</p>';
                            } else {
                                ?>
                                <table class="view">
                                    <tr>
                                        <?php
                                        echo "<th style='width:230px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', "Organization's Full Name") . "</th>";
                                        echo "<th style='width:150px;'>" . $this->Paginator->sort('LookupSupervisionCategory.case_categories', 'Case Title') . "</th>";
                                        echo "<th style='width:50px;'>" . $this->Paginator->sort('SupervisionModuleOrgSelectionDetail.from_date', 'From') . "</th>";
                                        echo "<th style='width:100px;'>" . $this->Paginator->sort('SupervisionModuleOrgSelectionDetail.is_running_case', 'Status') . "</th>";
                                        echo "<th style='width:95px;'>Action</th>";
                                        ?>
                                    </tr>
                                    <?php foreach ($values as $value) { ?>
                                        <tr>
                                            <td>
                                                <?php
                                                //echo $value['BasicModuleBasicInformation']['full_name_of_org'];
                                                $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                                $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];
                                                echo $orgFullName . ((!empty($orgFullName) && !empty($orgName)) ? " (<strong>" . $orgName . "</strong>)" : $orgName);
                                                ?>
                                            </td>
                                            <td><?php echo $value['LookupSupervisionCategory']['case_categories']; ?></td>
                                            <td style="text-align: center;"><?php echo $value['SupervisionModuleOrgSelectionDetail']['from_date']; ?></td>
                                            <td style="text-align: center;">
                                                <?php
                                                $btnSetStatus = "";
                                                $status_id = 'null';
                                                if ($value['SupervisionModuleOrgSelectionDetail']['is_running_case'] == '1') {
                                                    echo 'Running';
                                                    $btnSetStatus = $this->Js->link('Make Waiting', array('controller' => 'SupervisionModuleOrgSelectionDetails', 'action' => 'set_status', $value['SupervisionModuleOrgSelectionDetail']['id'], 0, null), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'width:95px;', 'success' => "msg.init('success', '$title', 'Supervision Case Set as Waiting Successfully.');", 'error' => "msg.init('error', '$title', 'Update failed!');")));
                                                } elseif ($value['SupervisionModuleOrgSelectionDetail']['is_running_case'] == '0') {
                                                    echo 'Waiting';
                                                    $btnSetStatus = $this->Js->link('Make Running', array('controller' => 'SupervisionModuleOrgSelectionDetails', 'action' => 'set_status', $value['SupervisionModuleOrgSelectionDetail']['id'], 1), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'width:95px;', 'success' => "msg.init('success', '$title', 'Supervision Case Set as Running Successfully.');", 'error' => "msg.init('error', '$title', 'Update failed!');")));
                                                } else {
                                                    echo 'Disposed';
                                                    $btnSetStatus = '';
                                                }
                                                if (!empty($btnSetStatus)) {
                                                    $btnSetStatus = $btnSetStatus . $this->Js->link('Make Disposed', array('controller' => 'SupervisionModuleOrgSelectionDetails', 'action' => 'set_status', $value['SupervisionModuleOrgSelectionDetail']['id'], $status_id, 711), array_merge($pageLoading, array('class' => 'btnlink', 'style' => 'width:95px;', 'success' => "msg.init('success', '$title', 'Supervision Case Set as Disposed Successfully.');", 'error' => "msg.init('error', '$title', 'Update failed!');")));
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $this->Js->link('Details', array('controller' => 'SupervisionModuleOrgSelectionDetails', 'action' => 'preview', $value['SupervisionModuleOrgSelectionDetail']['id']), array_merge($pageLoading, array('update' => '#popup_div', 'class' => 'btnlink', 'style' => 'width:95px;'))) . $btnSetStatus; ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <?php if ($values && $this->Paginator->param('pageCount') > 1) { ?>
            <div class="paginator">
                <?php
                if ($this->Paginator->param('pageCount') > 5) {
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
                        <?php echo $this->Js->link('Select Organization', array('controller' => 'SupervisionModuleOrgSelectionDetails', 'action' => 'add'), array_merge($pageLoading, array('class' => 'mybtns', 'title' => ''))); ?>     
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </fieldset>
</div>
