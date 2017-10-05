
<div id="basicInfo" title="Detail Information of Organization" style="margin:0px; padding:0px; background-color:#fafdff;"> 
    <fieldset>        
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <table cellpadding="7" cellspacing="8" border="0">
                <tr>
                    <td>Organization's Name</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['SupervisionModuleBasicInformation']['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>
                <tr>
                    <td>License No.</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['SupervisionModuleBasicInformation']['BasicModuleBasicInformation']['license_no']; ?></td>
                </tr>
                <tr>
                    <td>Type of Problem</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['LookupSupervisionTypeOfProblem']['type_of_problems']; ?></td>
                </tr>
                <tr>
                    <td>Title of Problem</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['LookupSupervisionTitleOfProblem']['title_of_problems']; ?></td>
                </tr>
                <tr>
                    <td>Description of Problem</td>
                    <td class="colons">:</td>
                    <td><?php echo $allDetails['SupervisionModuleIdentifiedProblemDetail']['description_of_problem']; ?></td>
                </tr>                
            </table>
        </div>
    </fieldset>
</div>
