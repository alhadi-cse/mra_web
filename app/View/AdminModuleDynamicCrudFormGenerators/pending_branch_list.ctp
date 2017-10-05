<?php
if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

if (!empty($model_name)) {
    ?>
    <div>
        <?php
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        $this->Paginator->options($pageLoading);
        ?>
        <div style="padding:10px;">
            <table>          
                <tr>
                    <td colspan="3" style="font-size:15px;font-weight:bold; color:#052458;">
                        <?php echo $title; ?>
                    </td>                                                      
                </tr>
                <tr>
                    <td style="min-width:150px; font-weight: bold;">Data Period</td>
                    <td class="colons">:</td>
                    <td style="min-width:530px;"><?php echo $data_period; ?></td>                                  
                </tr>
                <tr>
                    <td style="font-weight:bold;">Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $org_name; ?></td>                                  
                </tr>
                <tr>
                    <td style="font-weight:bold;">No. of Branches</td>
                    <td class="colons">:</td>
                    <td><?php echo count($sent_branches_names)+ count($pending_branches_names); ?></td>                                  
                </tr>
                <tr>
                    <td style="font-weight:bold;">Sent Data</td>
                    <td class="colons">:</td>
                    <td><?php echo count($sent_branches_names); ?></td>                                  
                </tr>
                <tr>
                    <td style="font-weight: bold;">Not Yet Sent Data</td>
                    <td class="colons">:</td>
                    <td><?php echo count($pending_branches_names); ?></td>                                  
                </tr>                
            </table>
        </div>
        <table>
            <tr>
                <td style="vertical-align: top;">                   
                    <fieldset>
                        <legend><?php echo "Branches that  Not Yet Sent Data"; ?></legend>
                        <?php echo $this->Form->create('BasicModuleBranchInfo_Pending'); ?>
                        <table cellpadding="0" cellspacing="0" border="0">          
                            <tr>
                                <td style="padding-left:5px; text-align:right;">Search By</td>
                                <td>
                                    <?php
                                    echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:95px',
                                        'options' => array('BasicModuleBranchInfo.branch_name' => 'Name of Branch')
                                    ));
                                    ?>
                                </td>
                                <td class="colons" style="padding: 5px 0;">:</td>
                                <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:95px')); ?></td>
                                <td style="text-align:left;">
                                    <?php
                                    echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                                    ?>
                                </td>               
                            </tr>
                        </table>
                        <?php echo $this->Form->end(); ?> 

                        <div id="searching" style="width:375px; min-height: 200px; max-height: 30vh; overflow-y: auto;">
                            <?php
                            if (!$pending_branches_names || !is_array($pending_branches_names) || count($pending_branches_names) < 1) {
                                echo '<p class="error-message">';
                                echo 'Did not find any data !';
                                echo '</p>';
                            } else {
                                ?>
                                <table class="view">
                                    <tr>
                                        <?php
                                        echo "<th style='min-width:30px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.serial_no', 'Serial') . "</th>";
                                        echo "<th style='width:85%;'>" . $this->Paginator->sort('BasicModuleBranchInfo.branch_with_address', 'Name of Branch') . "</th>";
                                        $counter = 0;
                                        ?>                                    
                                    </tr>
                                    <?php foreach ($pending_branches_names as $branche_id => $branche_name) { ?>
                                        <tr>
                                            <td style="padding:0 8px; text-align: right;">
                                                <?php
                                                $counter++;
                                                echo "$counter.";
                                                ?>
                                            </td>
                                            <td><?php echo $branche_name; ?></td>                                        
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } ?>                                                            
                        </div>

                        <?php if ($pending_branches_names && $this->Paginator->param('pageCount') > 1) { ?>
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
                        <!--<div class="btns-div"></div>-->
                    </fieldset>
                </td>                
                <td style="padding-left: 10px;">

                </td>
                <td style="vertical-align: top;">                   
                    <fieldset>
                        <legend><?php echo "Branches that Sent Data"; ?></legend>
                        <?php echo $this->Form->create('BasicModuleBranchInfo_Sent'); ?>
                        <table cellpadding="0" cellspacing="0" border="0">          
                            <tr>
                                <td style="padding-left:5px; text-align:right;">Search By</td>
                                <td>
                                    <?php
                                    echo $this->Form->input('search_option', array('label' => false, 'style' => 'width:95px',
                                        'options' => array('BasicModuleBranchInfo.branch_name' => 'Name of Branch')
                                    ));
                                    ?>
                                </td>
                                <td class="colons" style="padding: 5px 0;">:</td>
                                <td><?php echo $this->Form->input('search_keyword', array('label' => false, 'style' => 'width:95px')); ?></td>
                                <td style="text-align:left;">
                                    <?php
                                    echo $this->Js->submit('Search', array_merge($pageLoading, array('class' => 'btnsearch')));
                                    ?>
                                </td>               
                            </tr>
                        </table>
                        <?php echo $this->Form->end(); ?> 

                        <div id="searching" style="width:375px; min-height: 200px; max-height: 30vh; overflow-y: auto;">
                            <?php
                            if (!$sent_branches_names || !is_array($sent_branches_names) || count($sent_branches_names) < 1) {
                                echo '<p class="error-message">';
                                echo 'Did not find any data !';
                                echo '</p>';
                            } else {
                                ?>
                                <table class="view">
                                    <tr>
                                        <?php
                                        echo "<th style='min-width:30px;'>" . $this->Paginator->sort('BasicModuleBranchInfo.serial_no', 'Serial') . "</th>";
                                        echo "<th style='width:85%;'>" . $this->Paginator->sort('BasicModuleBranchInfo.branch_with_address', 'Name of Branch') . "</th>";
                                        $counter = 0;
                                        ?>                                    
                                    </tr>
                                    <?php foreach ($sent_branches_names as $branche_id => $branche_name) { ?>
                                        <tr>
                                            <td style="padding:0 8px; text-align:center;">
                                                <?php
                                                $counter++;
                                                echo "$counter.";
                                                ?>
                                            </td>
                                            <td><?php echo $branche_name; ?></td>                                        
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php } ?>                                                            
                        </div>

                        <?php if ($sent_branches_names && $this->Paginator->param('pageCount') > 1) { ?>
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
                        <!--<div class="btns-div"> </div>-->
                    </fieldset>
                </td>
            </tr>
        </table>        
    </div>

    <?php
} else {
    echo '<p class="error-message">No data is available ! </p>';
}
?>
