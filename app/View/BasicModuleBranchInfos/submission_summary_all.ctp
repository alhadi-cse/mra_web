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
    
    
    debug($this->Paginator->params);
    
    $this->Paginator->options($pageLoading);

    if (empty($sort_field))
        $sort_field = '__license_no';

    if (empty($sort_field) || strtolower($sort_dir) == 'desc') {
        $sort_dir = 'asc';
        $sort_class = 'desc';
    } else {
        $sort_dir = 'desc';
        $sort_class = 'asc';
    }
    
    
    array(
	'page' => (int) 4,
	'current' => (int) 20,
	'count' => (int) 504,
	'prevPage' => true,
	'nextPage' => true,
	'pageCount' => (int) 26,
	'order' => array(
		'BasicModuleBasicInformation.license_no' => 'desc'
	),
	'limit' => (int) 20,
	'options' => array(
		'order' => array(
			'BasicModuleBasicInformation.license_no' => 'desc'
		),
		'page' => (int) 4,
		'sort' => 'BasicModuleBasicInformation.license_no',
		'direction' => 'desc',
		'conditions' => array()
	),
	'paramType' => 'named'
);

//    if (!empty($sort_field) && !empty($sort_dir))
//        $this->Paginator->options['url'] = array('sort' => $sort_field, 'direction' => $sort_dir);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        <div>
            <?php
            if (!empty($branch_data)) {
                echo "<p style='color:#072fa3;font-weight:bold;'>Total Branch Count : $total_branch </p>";
            }
            ?> 
            <div style="width:780px; height:auto; max-height:75vh; overflow-x:auto;">
                <table class="view">
                    <thead>
                        <tr>
                            <?php
                            echo "<th style='width:25px;'>Sl. No.</th>";
                            echo "<th style='width:100px;'>"
                            . ($sort_field == "__license_no" ?
                                    $this->Paginator->sort('__license_no', 'License No.', array('direction' => $sort_dir, 'class' => $sort_class)) :
                                    $this->Paginator->sort('__license_no', 'License No.', array('direction' => 'asc')))
                            . "</th>";
                            echo "<th style='width:540px;'>"
                            . ($sort_field == "__name_of_org" ?
                                    $this->Paginator->sort('__name_of_org', 'Name of Organization', array('direction' => $sort_dir, 'class' => $sort_class)) :
                                    $this->Paginator->sort('__name_of_org', 'Name of Organization', array('direction' => 'asc')))
                            . "</th>";
                            echo "<th style='width:80px;'>"
                            . ($sort_field == "__branch_count" ?
                                    $this->Paginator->sort('__branch_count', 'Total Submitted', array('direction' => $sort_dir, 'class' => $sort_class)) :
                                    $this->Paginator->sort('__branch_count', 'Total Submitted', array('direction' => 'asc')))
                            . "</th>";
                            ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $sl_no = 0;
                        foreach ($branch_data as $branch_info) {
                            ++$sl_no;
                            ?>
                            <tr>
                                <td style="padding-right:15px; text-align:right; font-weight:bold;"><?php echo "$sl_no."; ?></td>                                        
                                <td style="text-align:center;"><?php echo $branch_info[0]['__license_no']; ?></td>
                                <td style="text-align:left;"><?php echo $branch_info[0]['__name_of_org']; ?></td> 
                                <td style="padding-right:15px; text-align:right; font-weight:bold;"><?php echo $branch_info[0]['__branch_count']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>
                </table>



                <?php if ($branch_data && $this->Paginator->param('pageCount') > 1) { ?>
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
                <table style="margin:0 auto; padding:0;" cellspacing="5">
                    <tr>
                        <td></td>
                        <td><?php echo $this->Js->link('Back to Office List', array('controller' => 'BasicModuleBranchInfos', 'action' => 'view'), array_merge($pageLoading, array('class' => 'mybtns'))); ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>

        </div>
    </fieldset>
<?php } ?>
