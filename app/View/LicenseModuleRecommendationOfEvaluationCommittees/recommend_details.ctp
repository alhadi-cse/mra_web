
<div>
    <fieldset>
        <legend>Recommendation of Evaluation Committee Details</legend>
        <div class="form">
            <table cellpadding="7" cellspacing="8" border="0">                    
                <tr>
                    <td>Name of Organization</td>
                    <td class="colons">:</td>
                    <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['BasicModuleBasicInformation']['full_name_of_org']; ?></td>
                </tr>                
                <tr>
                    <td>Recommendation</td>
                    <td class="colons">:</td>
                    <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['LookupLicenseRecommendationStatus']['recommendation_status']; ?></td>
                </tr>
                <?php 
                    if(!empty($licApprovalDetails) && $licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['recommendation_status_id']=="1")
                    {
                ?>
                <tr>
                    <td>Date of Recommendation</td>
                    <td class="colons">:</td>
                    <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['recommendation_date']; ?></td>
                </tr>
                <?php 
                    }
                    else if(!empty($licApprovalDetails) && $licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['recommendation_status_id']=="2")
                    {
                ?>
                <tr>
                    <td>Reason (if not recommend)</td>
                    <td class="colons">:</td>
                    <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['reason_if_not_recommended']; ?></td>
                </tr>
                <?php 
                    }
                ?>
                <tr>
                    <td>Comment</td>
                    <td class="colons">:</td>
                    <td><?php if(!empty($licApprovalDetails)) echo $licApprovalDetails['LicenseModuleRecommendationOfEvaluationCommittee']['comment']; ?></td>
                </tr>
            </table> 
        </div>
    </fieldset>
</div>


