
<div id="basicInfo" title="Findings" style="margin:0px; padding:0px; background-color:#fafdff;"> 
    <fieldset>        
        <div class="datagrid" style="max-height:430px; overflow-y:auto; color:#232428;">
            <?php
            if (!empty($findings_values)) {
                ?>
                <table cellpadding="7" cellspacing="8" border="0">                
                    <tr>
                        <th style="width:50px;">SL No.</th>
                        <th style="width:200px;">Findings</th>
                        <th style="width:200px;">MFI's Reply</th>
                        <th style="width:200px;">Comments</th>                    
                    </tr>
                    <?php
                    foreach ($findings_values as $existing_findings_value) {
                        ?>
                        <tr>
                            <td><?php echo $existing_findings_value['SupervisionModuleFindingsDetail']['serial_no']; ?></td>
                            <td><?php echo $existing_findings_value['SupervisionModuleFindingsDetail']['findings']; ?></td>
                            <td><?php echo $existing_findings_value['SupervisionModuleFindingsDetail']['mfis_reply']; ?></td>
                            <td><?php echo $existing_findings_value['SupervisionModuleFindingsDetail']['comments']; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<p class='error-message'>No findings is available ! </p>";
                }
                ?>
            </table>
        </div>
    </fieldset>
</div>
