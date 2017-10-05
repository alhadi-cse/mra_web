<?php        
    $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));
    $this->Paginator->options($pageLoading);
?>
<div class="form">            
    <fieldset style="margin-top:10px;">
        <legend>Pending/Not Requested</legend>
        <?php
        if(empty($pending_values) || !is_array($pending_values) || count($pending_values) < 1) {
            echo '<p class="error-message">No data is available !</p>';
        }
        else {
            if(empty($org_id)) {
            echo $this->Form->create('LicenseModuleCancelByMfiCancelRequestPending'); ?>
            <table cellpadding="0" cellspacing="0" border="0">                           
                <tr>
                    <td style="padding-left:15px; text-align:right;">Search Option</td>
                    <td>
                        <?php 
                            echo $this->Form->input('search_option_pending', 
                                    array('label' => false, 'style'=>'width:200px',
                                        'options' => 
                                            array('BasicModuleBasicInformation.full_name_of_org' => 'Organization\'s Full Name',
                                                'BasicModuleBasicInformation.short_name_of_org' => 'Organization\'s Short Name',
                                                'BasicModuleBasicInformation.license_no'=>'License No.')
                                            ));
                        ?>
                    </td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Form->input('search_keyword_pending',array('label' => false,'style'=>'width:250px')); ?></td>
                    <td style="text-align:left;">
                       <?php echo $this->Js->submit('Search', array_merge($pageLoading, array('class'=>'btnsearch'))); ?>
                   </td>                                
                </tr>
            </table>
            <?php echo $this->Form->end(); } ?>
            <?php if (!empty($pending_values) && is_array($pending_values) && count($pending_values) > 0) { ?>
            <table class="view">
                <tr>
                <?php
                    echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                    echo "<th style='min-width:150px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";                        
                    echo "<th>Action</th>";
                ?>
                </tr>
                <?php foreach($pending_values as $value) { ?>                
                <tr>
                    <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>  
                    <td>
                        <?php
                            $orgName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                            $orgFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                            if (!empty($orgName))
                                $orgName = "<strong>".$orgName.":</strong> ";
                            if (!empty($orgFullName))
                                $orgName = $orgName.$orgFullName;
                            echo $orgName;
                        ?>
                    </td>                                
                    <?php
                        $actions = $this->Js->link('Details', array('controller'=>'LicenseModuleCancelByMfiCancelRequests', 'action'=>'preview', $value['BasicModuleBasicInformation']['id'],1), 
                                                        array_merge($pageLoading, array('class'=>'btnlink', 'update'=>'#popup_div')));                                                                     

                        if($thisStateIds[0]==$value['BasicModuleBasicInformation']['licensing_state_id']){
                            $width = '220px';
                            $actions = $actions.$this->Js->link('Request for Cancellation', array('controller'=>'LicenseModuleCancelByMfiCancelRequests', 'action'=>'request_for_cancellation', $value['BasicModuleBasicInformation']['id']), 
                                                        array_merge($pageLoading, array('class'=>'btnlink')));     
                        }
                        else{
                            $width = '50px';
                        }
                    ?>
                    <td style="width:<?php echo $width;?>;height:30px; padding:2px; text-align:justify;">
                       <?php
                            echo $actions;
                       ?>
                    </td>
                </tr>
            <?php } }?>
            </table>
            <?php }
            if (!empty($pending_values) && $this->Paginator->param('pageCount') > 1) { ?>
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
    </fieldset>
</div>