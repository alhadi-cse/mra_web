<div id="basicInfo" title="Detail Information" style="margin:0px; padding:10px; background-color:#fafdff;"> 
    <fieldset>        
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">              
                <tr>
                    <td style="width:25%;">Data Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['AdminModulePeriodDataType']['data_types']; ?></td>
                </tr>
                <tr>
                    <td>Period Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['AdminModulePeriodType']['period_types']; ?></td>
                </tr>
                <tr>
                    <td>Period</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['AdminModulePeriodList']['period']; ?></td>
                </tr>
                <tr>
                    <td>User Type</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['AdminModuleUserGroup']['group_name']; ?></td>
                </tr> 
                <tr>
                    <td>Status</td>
                    <td class="colons">:</td>
                    <td> <?php if($allDetails['AdminModulePeriodDetail']['is_current_period']=='1'){ echo 'Current'; } ?> </td>
                </tr>                 
            </table>
        </div>
    </fieldset>
</div>