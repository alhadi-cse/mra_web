<?php                 
    //echo $this->Form->create('CDBNonMfiYearlyMicrocreditInfo');            
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
                        <td>Month</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $this->Time->format($yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['year_and_month'], '%B, %Y', ''); ?></td>
                    </tr>         
                    <tr>
                        <td>Male client</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['maleClient']; ?></td>
                    </tr>               
                    <tr>
                        <td>Female client</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['femaleClient']; ?></td>
                    </tr>
                    <tr>
                        <td>Total client</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['totalClient']; ?></td>
                    </tr>                   
                    <tr>
                        <td>Male borrower</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['maleBorrower']; ?></td>
                    </tr>                
                    <tr>
                        <td>Female borrower</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['femaleBorrower']; ?></td>
                    </tr>   
                     <tr>
                        <td>Total borrower</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['totalBorrower']; ?></td>
                    </tr>                
                    <tr>
                        <td>Target of loan outstanding</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['targetOfLoanOutstanding']; ?></td>
                    </tr>   
                     <tr>
                        <td>Loan outstanding</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['loanOutstanding']; ?></td>
                    </tr>                
                    <tr>
                        <td>Recoverable loan (principle)</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['recoverableLoan']; ?></td>
                    </tr>  
                    <tr>
                        <td>Principle loan recovery</td>
                        <td style="width:3px;font-weight: bold;">:</td>
                        <td><?php echo $yearlyMicrocreditInfo['CDBNonMfiYearlyMicrocreditInfo']['principleLoanRecovery']; ?></td>
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

