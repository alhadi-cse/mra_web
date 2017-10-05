
<div>
    <fieldset>       
        <div id="letter">
            <table cellpadding="10" cellspacing="8" border="0" style="width:95%; margin:5px auto;">                
                <tr>
                    <td style="width:85px;">Date</td>
                    <td class="colons">:</td>
                    <td>
                        <?php
                        if (!empty($explanation_details['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_giving_date']))
                            echo date("d-m-Y", strtotime($explanation_details['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_giving_date']));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top; padding-top:5px;">Explanation</td>
                    <td class="colons" style="vertical-align:top; padding-top:5px;">:</td>
                    <td style="vertical-align:top; padding-top:5px;"><?php echo $explanation_details['SupervisionModuleReplyOrExplanationOfMfiDetail']['explanation_details']; ?></td>
                </tr>                
            </table>                       
        </div>        
    </fieldset>
</div>