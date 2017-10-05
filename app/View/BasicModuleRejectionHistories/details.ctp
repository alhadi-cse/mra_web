
<?php                 
    //echo $this->Form->create('BasicModuleBasicInformation');            
    //echo debug($rejectHistDetails);
?>

<div>
    <div id="basicInfo" title="General Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 
        <fieldset>
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
    </div>

    <script>
        $(function () {
            $("#basicInfo").dialog({
                modal: true, width: 870,
                buttons: {
                    Close: function () {
                        $(this ).dialog("close");
                    }
                }
            });
        });
    </script>

</div>

