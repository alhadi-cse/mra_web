<div>    
     <?php 
        $title = "Inspector for Field Inspection"; 
        $pageLoading = array('update' => '#ajax_div', 'evalScripts' => true, 
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)), 
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)));

        //$this->Paginator->options(array_merge($pageLoading, array('update' => '#dtPending')));
        $this->Paginator->options($pageLoading);
    ?>
    <fieldset>
        <legend><?php echo $title; ?></legend>
        
        <div class="form">
            
            <?php if(!empty($allRows)) { ?>
            <fieldset>
                <legend>Inspector Assigned</legend>
                <table class="view">
                    <?php echo $allRows; ?>
                </table>
            </fieldset>
            <?php } ?>
            
            <fieldset style="margin-top:10px">
                <legend>Inspector Assign Pending</legend>
                <?php
                if (empty($values_pending) || !is_array($values_pending) || count($values_pending) < 1) {
                    echo '<p class="error-message">' . 'There is no pending form for Inspector assign !' . '</p>';
                } else {
                ?>
            
                <table class="view">
                    <tr>
                        <?php 
                            echo "<th style='width:100px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.license_no', 'License No.') . "</th>";
                            echo "<th style='min-width:170px;'>" . $this->Paginator->sort('BasicModuleBasicInformation.full_name_of_org', 'Name of Organization') . "</th>";
                            echo "<th style='width:120px;'>" . $this->Paginator->sort('LookupAdminBoundaryDistrict.district_name', 'District') . "</th>";
                            echo "<th style='width:115px;'>Action</th>";
                        ?>
                    </tr>
                    <?php foreach($values_pending as $value){ ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $value['BasicModuleBasicInformation']['license_no']; ?></td>
                        <td>
                            <?php 
                                $mfiName = $value['BasicModuleBasicInformation']['short_name_of_org'];
                                $mfiFullName = $value['BasicModuleBasicInformation']['full_name_of_org'];

                                if (!empty($mfiName))
                                    $mfiName = "<strong>".$mfiName.":</strong> ";
                                if (!empty($mfiFullName))
                                    $mfiName = $mfiName.$mfiFullName;

                                echo $mfiName;
                            ?>
                        </td>
                        <td><?php echo $value['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                        <td style="height:30px; padding:2px; text-align:center;"> 
                            <?php 
                                echo $this->Js->link('Address Details', array('controller' => 'BasicModuleBranchInfoes', 'action' => 'details_all', $value['BasicModuleBasicInformation']['id']), 
                                                    array_merge($pageLoading, array('class'=>'btnlink', 'update' => '#popup_div')));
                            ?>
                        </td>
                    </tr>
                  <?php } ?>
                </table>

                <?php if($values_pending && $this->Paginator->param('pageCount')>1) { ?>
                <div class="paginator">
                    <?php 

                    if($this->Paginator->param('pageCount')>10)
                    {
                       echo $this->Paginator->first('<<', array('class' => 'prevPg', 'title' => 'Goto first page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->prev('<', array('class' => 'numbers', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                            $this->Paginator->next('>', array('class' => 'numbers', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link')).
                            $this->Paginator->last('<<', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                    else {
                       echo $this->Paginator->prev('<<', array('class' => 'prevPg', 'title' => 'Goto previous page.'), null, array('class' => 'prevPg no_link')).
                            $this->Paginator->numbers(array('class' => 'numbers', 'separator' => '')).
                            $this->Paginator->next('>>', array('class' => 'nextPg', 'title' => 'Goto next page.'), null, array('class' => 'nextPg no_link'));
                    }
                  ?>
                </div>
                <?php } ?>

            <?php } ?>
            </fieldset>
            
        </div>
        
        <div class="btns-div">                
            <table style="margin:0 auto; padding:0;" cellspacing="7">
                <tr>
                    <td></td>                    
                    <td>
                        <?php 
                        if (!empty($values_pending) && is_array($values_pending) && count($values_pending) > 0)
                            echo $this->Js->link('Assign', array('controller'=>'LicenseModuleSuspensionFieldInspectorDetails','action'=>'assign'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td>
                        <?php 
                        if(!empty($allRows))
                            echo $this->Js->link('Re-assign', array('controller'=>'LicenseModuleSuspensionFieldInspectorDetails','action'=>'re_assign'), 
                                                    array_merge($pageLoading, array('class'=>'mybtns')));
                        ?>
                    </td>
                    <td></td>   
                </tr>
            </table>
        </div>
        
    </fieldset>
</div>
