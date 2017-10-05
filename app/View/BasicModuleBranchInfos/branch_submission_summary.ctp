<?php
if (isset($msg) && !empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
} else {
    $title = "Submitted Office Information";
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
            if (!empty($branch_summary)) {
                echo "<p style='color:#072fa3;font-weight:bold;'>Total Branch Count : $total_branch </p>";
            }
            ?> 
            <div style="width:780px; height:auto; overflow-x:auto;">                                
                <table class="view">
                    <tr>
                        <?php
                        echo "<th style='width:25px;'>Sl. No.</th>";
                        echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                        echo "<th style='width:540px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.name_of_org', 'Name of Organization') . "</th>";
                        echo "<th style='width:80px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.branch_count', 'Total Submitted') . "</th>";
                        ?>                                        
                    </tr>
                    <?php
                    $curr_page_no = (int) $this->Paginator->counter(array('format' => ('{:page}')));
                    $i = (($curr_page_no - 1) * $page_limit) + 1;

                    foreach ($branch_summary as $branch_info) {
                        ?>
                        <tr>
                            <td style="padding-right:15px; text-align:right; font-weight:bold;"><?php echo "$i."; ?></td>                                        
                            <td style="text-align:center;"><?php echo $branch_info['BasicModuleBasicInformation']['license_no']; ?></td>
                            <td style="text-align:left;"><?php echo $branch_info['BasicModuleBranchInfo']['name_of_org']; ?></td> 
                            <td style="padding-right:15px; text-align:right; font-weight:bold;"><?php echo $branch_info['BasicModuleBranchInfo']['branch_count']; ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </table>                                
            </div>

            <?php if ($branch_summary && $this->Paginator->param('pageCount') > 1) { ?>
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
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>
                        <td></td>
                        <td><?php echo $this->Js->link('Back to List', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns'))); ?></td>
                        <td><?php echo $this->Html->link('Export Branch Summary', array('controller' => 'BasicModuleBranchInfos', 'action' => 'export_branch_summary', 0), array('class' => 'mybtns', 'target' => '_blank')); ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>

        </div>
    </fieldset>
<?php } ?>
