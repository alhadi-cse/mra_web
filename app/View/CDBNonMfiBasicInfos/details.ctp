<div class="datagrid"  id="basicInfo" title="Non-MFI Agency" style="margin:0px; padding:10px; background-color:#fafdff;"> 
    <fieldset>
        <legend>Agency Details</legend>
        <table cellpadding="7" cellspacing="8" border="0">                  

            <tr class="alt">
                <td style="width:5px; text-align:center; font-weight:bold;">01.</td>
                <td style="width:180px;">Agency Name</td>
                <td style="width:5px; text-align:center; font-weight:bold;">:</td>
                <td style="min-width:280px;"><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['name_of_org']; ?></td>
            </tr>
            <tr>
                <td style="width:5px; text-align:center; font-weight:bold;">02.</td>
                <td>Type of Agency</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['LookupCDBNonMfiType']['type_name']; ?></td>
            </tr>
            <tr class="alt">
                <td style="width:5px; text-align:center; font-weight:bold;">04.</td>
                <td>Ministry/Authority</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['LookupCDBNonMfiMinistryAuthorityName']['name_of_ministry_or_authority']; ?></td>
            </tr>
            <tr class="alt">
                <td style="width:5px; text-align:center; font-weight:bold;">06.</td>
                <td>Name of Reporting Officer</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['name_of_officer']; ?></td>
            </tr>
            <tr class="alt">
                <td style="width:5px; text-align:center; font-weight:bold;">07.</td>
                <td>Designation</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['designation_of_officer']; ?></td>
            </tr>
            <tr>
                <td style="width:5px; text-align:center; font-weight:bold;">08.</td>
                <td>Contract Number</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['contract_no_of_officer']; ?></td>
            </tr>
            <tr class="alt">
                <td style="width:5px; text-align:center; font-weight:bold;">09.</td>
                <td>Alternative Officer Name</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['name_of_alt_officer']; ?></td>
            </tr>
            <tr>
                <td style="width:5px; text-align:center; font-weight:bold;">10.</td>
                <td>Designation</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['designation_of_alt_officer']; ?></td>
            </tr>
            <tr class="alt">
                <td style="width:5px; text-align:center; font-weight:bold;">11.</td>
                <td>Contract Number</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['contract_no_of_alt_officer']; ?></td>
            </tr>
            <tr>
                <td style="width:5px; text-align:center; font-weight:bold;">12.</td>
                <td>Head Office Mailing Address</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['head_office_address']; ?></td>
            </tr>
            <tr class="alt">
                <td style="width:5px; text-align:center; font-weight:bold;">13.</td>
                <td>Email</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['email']; ?></td>
            </tr>
            <tr>
                <td style="width:5px; text-align:center; font-weight:bold;">14.</td>
                <td>Fax</td>
                <td style="text-align:center; font-weight:bold;">:</td>
                <td><?php echo $basicInfoDetails['CDBNonMfiBasicInfo']['fax']; ?></td>
            </tr>

        </table> 
    </fieldset>
</div>

<script>
    $(function () {
        $("#basicInfo").dialog({
            modal: true, minWidth: 830,
            buttons: {
                Close: function () {
                    $(this ).dialog("close");
                }
            }
        });
    });
</script>