
<?php                 
//echo $this->Form->create('BasicModuleBasicInformation');
//debug($basicInfoDetails);
?>

<div>

    <fieldset style="margin:10px;">
        <legend>
            Rejection History
        </legend>
        
        <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php echo $rejectHistDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>                
                <tr>
                    <td>First Rejection Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($rejectHistDetails['BasicModuleRejectionHistory']['firstRejectionDate'],'%d-%m-%Y',''); ?></td>
                </tr>                
                <tr>
                    <td>Last Final Rejection Date</td>
                    <td class="colons">:</td>
                    <td><?php echo $this->Time->format($rejectHistDetails['BasicModuleRejectionHistory']['lastFinalRejectionDate'],'%d-%m-%Y',''); ?></td>
                </tr>         
                <tr>
                    <td>Rejection Count</td>
                    <td class="colons">:</td>
                    <td><?php echo $rejectHistDetails['BasicModuleRejectionHistory']['rejectionCount']; ?></td>
                </tr>               
                <tr>
                    <td>Comment on Last Rejection</td>
                    <td class="colons">:</td>
                    <td><?php echo $rejectHistDetails['BasicModuleRejectionHistory']['commentOnLastRejection']; ?></td>
                </tr>
            </table> 

        </div>
    </fieldset>


    <div class="btns-div">
        <?php 
            $pageLoading = array('class'=>'mybtns', 'update'=>'#ajax_div', 'evalScripts'=>true, 
                        'before'=>$this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer'=>false)), 
                        'complete'=>$this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer'=>false)));
        ?>
        
        <table style="margin:0 auto; padding:5px;" cellspacing="7">
            <tr>
                <td></td>
                <td>
                    <?php 
                        echo $this->Js->link('Close', array('controller'=>'BasicModuleRejectionHistories', 'action'=>'view'), $pageLoading); 
                    ?>
                </td>

                <td>
                    <?php 
                        echo $this->Js->link('Previous', array('controller'=>'BasicModuleBasicInformations', 'action'=>'add'), array_merge($pageLoading, array('success'=>'msc.prev();'))); 
                    ?>     
                </td>

                <td>
                    <?php 
                        echo $this->Js->link('Add another', array('controller'=>'BasicModuleRejectionHistories', 'action'=>'add'), $pageLoading); 
                    ?>
                </td>
                
                <td>
                    <?php 
                        echo $this->Js->link('Next', array('controller'=>'BasicModuleAddresses', 'action'=>'add'), array_merge($pageLoading, array('success'=>'msc.next();'))); 
                    ?>
                </td>
                <td></td>
            </tr>
        </table>
    </div>

</div>

