<?php                 
    //echo $this->Form->create('CDBNonMfiDistrictWiseMicrocreditActivity');            
    // debug($yearlyMicrocreditInfo);
?>

<div>
    <div id="basicInfo" title="General Information of Organization" style="margin:0px; padding:10px; background-color:#fafdff;"> 

        <fieldset>
            <legend>
                Detail Information of Non-MFI
            </legend>
            <div class="form" style="max-height:430px; overflow-y:auto; color:#232428;">

                <table cellpadding="7" cellspacing="8" border="0">                  
                    <tr>
                        <td>Name of the Organization</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiBasicInfo']['name_of_org']; ?></td>
                    </tr>                   
                    <tr>
                        <td>Month & Year</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $this->Time->format($yearlyMicrocreditInfo['CDBNonMfiDistrictWiseMicrocreditActivity']['year_and_month'], '%B, %Y', ''); ?></td>
                    </tr>         
                    <tr>
                        <td>District</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['LookupAdminBoundaryDistrict']['district_name']; ?></td>
                    </tr>               
                    <tr>
                        <td>Female client</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiDistrictWiseMicrocreditActivity']['distribution']; ?></td>
                    </tr>
                    <tr>
                        <td>Total client</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiDistrictWiseMicrocreditActivity']['recovery']; ?></td>
                    </tr>                   
                    <tr>
                        <td>Male borrower</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiDistrictWiseMicrocreditActivity']['maleBorrower']; ?></td>
                    </tr>                
                    <tr>
                        <td>Female borrower</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiDistrictWiseMicrocreditActivity']['femaleBorrower']; ?></td>
                    </tr>   
                     <tr>
                        <td>Total borrower</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiDistrictWiseMicrocreditActivity']['totalBorrower']; ?></td>
                    </tr>   
                     
                </table> 
                
            </div>
        </fieldset>

    </div>

    <script>
        $(function() {
            $( "#basicInfo" ).dialog({
                modal: true, width: 870, 
                buttons: {
                    Close: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
    </script>

</div>

