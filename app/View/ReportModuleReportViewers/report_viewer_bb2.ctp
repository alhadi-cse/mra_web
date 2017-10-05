
<?php
//    debug($report_details);
//    debug($report_details);

if (!empty($msg)) {
    if (is_array($msg)) {
        echo $this->Html->scriptBlock('msg.init(' . json_encode($msg) . ');', array('inline' => true));
    } else {
        echo $this->Html->scriptBlock("msg.init('error', 'Error...', '$msg');", array('inline' => true));
    }
}

//if (empty($report_details))
//    return;

/*
$no_of_member_male = $no_of_member_female = 320;
$no_of_member_male_next = $no_of_member_female_next = 250;
$no_of_borrower_male = $no_of_borrower_female = 5464;
$no_of_borrower_male_next = $no_of_borrower_female_next = 9542;

$loan_disbursed_all = $loan_disbursed_all_next = $loan_disbursed_t10 = $loan_disbursed_t10_next = 587552;
$saving_balance_all = $saving_balance_all_next = $saving_balance_t10 = $saving_balance_t10_next = 654217;

$market_share_member_all = $market_share_member_all_next = 481;
$market_share_member_t10 = $market_share_member_t10_next = 5512;
$market_share_member_t20 = $market_share_member_t20_next = 8542;


$market_share_branch_all = $market_share_branch_all_next = 784;
$market_share_branch_t10 = $market_share_branch_t10_next = 65412;
$market_share_branch_t20 = $market_share_branch_t20_next = 98676;

$return_asset = $return_asset_next = 854752;
$return_equity = $return_equity_next = 254672;
$net_income = $net_income_next = 8626358;
*/


$no_of_mfis = [345, 357];
$no_of_branches = [5432, 6543];
$no_of_employees = [76544, 78654];
$no_of_members = [876493, 985783];
$no_of_borrowers = [676493, 785783];

$loan_disbursed_all = [48764935, 69857834];
$loan_disbursed_t20 = [28764933, 39857837];

$saving_balance_all = [5764935, 7857834];
$saving_balance_t20 = [38764935, 49857834];

$disbursed_loans_all = [5487649354, 6698578340];
$disbursed_loans_vl = [48764935, 69857834];
$disbursed_loans_lz = [28764935, 49857834];
$disbursed_loans_me = [18764935, 39857834];
$disbursed_loans_sm = [8764935, 9857834];

$outstanding_loans_all = [7649355484, 5783669840];
$outstanding_loans_vl = [64935487, 57834698];
$outstanding_loans_lz = [93548764, 83469857];
$outstanding_loans_me = [44935876, 67834985];
$outstanding_loans_sm = [64948735, 83674985];

$loan_recipients_all = [9355476484, 9845783660];
$loan_recipients_vl = [76493485, 83469857];
$loan_recipients_lz = [64973548, 57869834];
$loan_recipients_me = [87649354, 85697834];
$loan_recipients_sm = [93487645, 98567834];

$avg_loan_per_recipient = [6345, 8753];

$source_of_fund_all = [48764935, 69857834];
$source_of_fund_member_saving = [876435, 685784];
$source_of_fund_pksf_loan = [486495, 695783];
$source_of_fund_own_capital = [764935, 857834];
$source_of_fund_donar_fund = [464936, 645783];
$source_of_fund_bank_loan = [649384, 485783];
$source_of_fund_govt_loan = [764938, 685800];
$source_of_fund_other_loan = [464930, 557833];
$source_of_fund_mfis_loan = [764935, 857836];
$source_of_fund_other_fund = [876435, 698834];

$defaulted_loan_outstanding = [8876488, 6857839];

$org_infos = [['Rural Reconstruction Foundation (RRF)', [7434,7657], [9676,9757], [5322,6657], [4465,8965], [8795,8435], [3308,6435], [7854, 7890]], 
    ['Basic Unit For Resource And Opportunities Of Bangladesh (Buro Bangladesh)', [555,7657], [845,6435], [354,8435], [624,5435], [728,6457], [985,9489], [852,7657]], 
    ['Jagrata Juba Shangha (JJS)', [9489, 5678], [355,5489], [445,7489], [554,8989], [724,2989], [428,7683], [685,5983], [552, 664]]];
?>

<div>

    <style>
        .rpt-tbl {
            border: 1px solid #ddd;
            margin: 5px auto;
            padding: 0;
            font-size:13px;
        }
        .rpt-tbl th, .rpt-tbl td {
            border: 1px solid #ddd;
            margin: 0;
            padding: 5px;    
        }
    </style>


    <table class="rpt-tbl">
        <tr style="font-weight: bold; font-size: 14px;">
            <td style="text-align: center;">Sl. No.</td>
            <td>Particulars</td>
            <td style="text-align: center;">2015-16</td>
            <td style="text-align: center;">2016-17</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">01.</td>
            <td>Total Number of Licensed Institutions</td>
            <td style="text-align: right;"><?php echo $no_of_mfis[0]; ?></td>
            <td style="text-align: right;"><?php echo $no_of_mfis[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">02.</td>
            <td>Number of Branches</td>
            <td style="text-align: right;"><?php echo $no_of_branches[0]; ?></td>
            <td style="text-align: right;"><?php echo $no_of_branches[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">03.</td>
            <td>Number of Employees</td>
            <td style="text-align: right;"><?php echo $no_of_employees[0]; ?></td>
            <td style="text-align: right;"><?php echo $no_of_employees[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">04.</td>
            <td>Number of Members (in millions)</td>
            <td style="text-align: right;"><?php echo $no_of_members[0]; ?></td>
            <td style="text-align: right;"><?php echo $no_of_members[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">05.</td>
            <td>Number of Borrowers (in millions)</td>
            <td style="text-align: right;"><?php echo $no_of_borrowers[0]; ?></td>
            <td style="text-align: right;"><?php echo $no_of_borrowers[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">06.</td>
            <td>Outstanding Loan disbursed by licensed institutions (in billions)</td>
            <td style="text-align: right;"><?php echo $loan_disbursed_all[0]; ?></td>
            <td style="text-align: right;"><?php echo $loan_disbursed_all[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">07.</td>
            <td>Outstanding Loan disbursed by top 20 institutions</td>
            <td style="text-align: right;"><?php echo $loan_disbursed_t20[0]; ?></td>
            <td style="text-align: right;"><?php echo $loan_disbursed_t20[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">08.</td>
            <td>Outstanding Saving Balance by licensed institutions (in billions)</td>
            <td style="text-align: right;"><?php echo $saving_balance_all[0]; ?></td>
            <td style="text-align: right;"><?php echo $saving_balance_all[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">09.</td>
            <td>Outstanding Saving Balance held in top 20 licensed institutions</td>
            <td style="text-align: right;"><?php echo $saving_balance_t20[0]; ?></td>
            <td style="text-align: right;"><?php echo $saving_balance_t20[1]; ?></td>
        </tr>
        <tr style="font-weight:bold;">
            <td style="text-align: center; vertical-align: top;">10.</td>
            <td>Particulars of disbursed Loans (in millions)</td>
            <td style="text-align: right;"><?php echo $disbursed_loans_all[0]; ?></td>
            <td style="text-align: right;"><?php echo $disbursed_loans_all[1]; ?></td>
        </tr>
        <tr>
            <td rowspan="4"></td>
            <td>i. Very large (5 lac and above)</td>
            <td style="text-align: right;"><?php echo $disbursed_loans_vl[0]; ?></td>
            <td style="text-align: right;"><?php echo $disbursed_loans_vl[1]; ?></td>
        </tr>
        <tr>
            <td>ii. Large (1 lac to 5 lac)</td>
            <td style="text-align: right;"><?php echo $disbursed_loans_lz[0]; ?></td>
            <td style="text-align: right;"><?php echo $disbursed_loans_lz[1]; ?></td>
        </tr>
        <tr>
            <td>iii. Medium (10 thousand to 1 lac)</td>
            <td style="text-align: right;"><?php echo $disbursed_loans_me[0]; ?></td>
            <td style="text-align: right;"><?php echo $disbursed_loans_me[1]; ?></td>
        </tr>
        <tr>
            <td>iv. Small (upto 10 thousand)</td>
            <td style="text-align: right;"><?php echo $disbursed_loans_sm[0]; ?></td>
            <td style="text-align: right;"><?php echo $disbursed_loans_sm[1]; ?></td>
        </tr>
        <tr style="font-weight:bold;">
            <td style="text-align: center; vertical-align: top;">11.</td>
            <td>Outstanding portion of disbursed Loans (in millions)</td>
            <td style="text-align: right;"><?php echo $outstanding_loans_all[0]; ?></td>
            <td style="text-align: right;"><?php echo $outstanding_loans_all[1]; ?></td>
        </tr>
        <tr>
            <td rowspan="4"></td>
            <td>i. Very large (5 lac and above)</td>
            <td style="text-align: right;"><?php echo $outstanding_loans_vl[0]; ?></td>
            <td style="text-align: right;"><?php echo $outstanding_loans_vl[1]; ?></td>
        </tr>
        <tr>
            <td>ii. Large (1 lac to 5 lac)</td>
            <td style="text-align: right;"><?php echo $outstanding_loans_lz[0]; ?></td>
            <td style="text-align: right;"><?php echo $outstanding_loans_lz[1]; ?></td>
        </tr>
        <tr>
            <td>iii. Medium (10 thousand to 1 lac)</td>
            <td style="text-align: right;"><?php echo $outstanding_loans_me[0]; ?></td>
            <td style="text-align: right;"><?php echo $outstanding_loans_me[1]; ?></td>
        </tr>
        <tr>
            <td>iv. Small (upto 10 thousand)</td>
            <td style="text-align: right;"><?php echo $outstanding_loans_sm[0]; ?></td>
            <td style="text-align: right;"><?php echo $outstanding_loans_sm[1]; ?></td>
        </tr>
        <tr style="font-weight:bold;">
            <td style="text-align: center; vertical-align: top;">12.</td>
            <td>Total Number of loan Recipients (in thousands)</td>
            <td style="text-align: right;"><?php echo $loan_recipients_all[0]; ?></td>
            <td style="text-align: right;"><?php echo $loan_recipients_all[1]; ?></td>
        </tr>
        <tr>
            <td rowspan="4"></td>
            <td>i. Very large (5 lac and above)</td>
            <td style="text-align: right;"><?php echo $loan_recipients_vl[0]; ?></td>
            <td style="text-align: right;"><?php echo $loan_recipients_vl[1]; ?></td>
        </tr>
        <tr>
            <td>ii. Large (1 lac to 5 lac)</td>
            <td style="text-align: right;"><?php echo $loan_recipients_lz[0]; ?></td>
            <td style="text-align: right;"><?php echo $loan_recipients_lz[1]; ?></td>
        </tr>
        <tr>
            <td>iii. Medium (10 thousand to 1 lac)</td>
            <td style="text-align: right;"><?php echo $loan_recipients_me[0]; ?></td>
            <td style="text-align: right;"><?php echo $loan_recipients_me[1]; ?></td>
        </tr>
        <tr>
            <td>iv. Small (upto 10 thousand)</td>
            <td style="text-align: right;"><?php echo $loan_recipients_sm[0]; ?></td>
            <td style="text-align: right;"><?php echo $loan_recipients_sm[1]; ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">13.</td>
            <td>Average loan per Recipient</td>
            <td style="text-align: right;"><?php echo $avg_loan_per_recipient[0]; ?></td>
            <td style="text-align: right;"><?php echo $avg_loan_per_recipient[1]; ?></td>
        </tr>
        <tr style="font-weight:bold;">
            <td style="text-align: center; vertical-align: top;">14.</td>
            <td>Source of Fund of NGO-MFIs (i to ix) (in millions)</td>
            <td style="text-align: right;"><?php echo $source_of_fund_all[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_all[1]; ?></td>
        </tr>
        <tr>
            <td rowspan="9"></td>
            <td>i. Saving Amount of the Members</td>
            <td style="text-align: right;"><?php echo $source_of_fund_member_saving[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_member_saving[1]; ?></td>
        </tr>
        <tr>
            <td>ii. Loan from PKSF</td>
            <td style="text-align: right;"><?php echo $source_of_fund_pksf_loan[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_pksf_loan[1]; ?></td>
        </tr>
        <tr>
            <td>iii. Own Capital</td>
            <td style="text-align: right;"><?php echo $source_of_fund_own_capital[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_own_capital[1]; ?></td>
        </tr>
        <tr>
            <td>iv. Donar Fund</td>
            <td style="text-align: right;"><?php echo $source_of_fund_donar_fund[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_donar_fund[1]; ?></td>
        </tr>
        <tr>
            <td>v. Loan from Commercial Bank</td>
            <td style="text-align: right;"><?php echo $source_of_fund_bank_loan[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_bank_loan[1]; ?></td>
        </tr>
        <tr>
            <td>vi. Loan from Government</td>
            <td style="text-align: right;"><?php echo $source_of_fund_govt_loan[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_govt_loan[1]; ?></td>
        </tr>
        <tr>
            <td>vii. Other Loans</td>
            <td style="text-align: right;"><?php echo $source_of_fund_other_loan[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_other_loan[1]; ?></td>
        </tr>
        <tr>
            <td>viii. Loan from other MFI</td>
            <td style="text-align: right;"><?php echo $source_of_fund_mfis_loan[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_mfis_loan[1]; ?></td>
        </tr>
        <tr>
            <td>ix. Other Fund</td>
            <td style="text-align: right;"><?php echo $source_of_fund_other_fund[0]; ?></td>
            <td style="text-align: right;"><?php echo $source_of_fund_other_fund[1]; ?></td>
        </tr>

        <tr style="font-weight:bold;">
            <td style="text-align: center; vertical-align: top;">15.</td>
            <td>Defaulted Loan Outstanding (in millions)</td>
            <td style="text-align: right;"><?php echo $defaulted_loan_outstanding[0]; ?></td>
            <td style="text-align: right;"><?php echo $defaulted_loan_outstanding[1]; ?></td>
        </tr>

        <tr style="font-weight:bold;">
            <td style="text-align: center; vertical-align: top;">16.</td>
            <td colspan="3">Information on the following Top MFI's: 1-20</td>
        </tr>

        <?php foreach ($org_infos as $org_info) { ?>
            <tr>
                <td rowspan="8"></td>
                <td colspan="3">i. Name : <strong><?php echo $org_info[0]; ?></strong></td>
                
                <!--<td>01. Name</td>-->
<!--                <td>i. Name</td>
                <td colspan="2"><?php echo $org_info[0]; ?></td>-->
            </tr>
            <tr>
                <!--<td>02. No. of Active members (in millions)</td>-->
                <td>ii. No. of Active members (in millions)</td>
                <td style="text-align: right;"><?php echo $org_info[1][0]; ?></td>
                <td style="text-align: right;"><?php echo $org_info[1][1]; ?></td>
            </tr>
            <tr>
                <!--<td>03. No. of Branchs</td>-->
                <td>iii. No. of Branchs</td>
                <td style="text-align: right;"><?php echo $org_info[2][0]; ?></td>
                <td style="text-align: right;"><?php echo $org_info[2][1]; ?></td>
            </tr>
            <tr>
                <!--<td>04. Total Equity (in millions)</td>-->
                <td>iv. Cumulative Surplus (in millions)</td>
                <td style="text-align: right;"><?php echo $org_info[3][0]; ?></td>
                <td style="text-align: right;"><?php echo $org_info[3][1]; ?></td>
            </tr>
            <tr>
                <!--<td>05. Outstanding Saving (in millions)</td>-->
                <td>v. Outstanding Saving (in millions)</td>
                <td style="text-align: right;"><?php echo $org_info[4][0]; ?></td>
                <td style="text-align: right;"><?php echo $org_info[4][1]; ?></td>
            </tr>
            <tr>
                <!--<td>06. Outstanding Loan (in millions)</td>-->
                <td>vi. Outstanding Loan (in millions)</td>
                <td style="text-align: right;"><?php echo $org_info[5][0]; ?></td>
                <td style="text-align: right;"><?php echo $org_info[5][1]; ?></td>
            </tr>
            <tr>
                <!--<td>07. Donor Fund (in millions)</td>-->
                <td>vii. Donor Fund (in millions)</td>
                <td style="text-align: right;"><?php echo $org_info[6][0]; ?></td>
                <td style="text-align: right;"><?php echo $org_info[6][1]; ?></td>
            </tr>
            <tr>
                <!--<td>08. Net profit/Loss (in millions)</td>-->
                <td>viii. Net profit/Loss (in millions)</td>
                <td style="text-align: right;"><?php echo $org_info[7][0]; ?></td>
                <td style="text-align: right;"><?php echo $org_info[7][1]; ?></td>
            </tr>

        <?php } ?>

        <tr style="font-weight:bold;">
            <td style="text-align: center; vertical-align: top;">17.</td>
            <td>New policy initiatives taken by MRA in 2015-16</td>
            <td colspan="2"></td>
        </tr>
    </table>

</div>


<script>

    function print_report(report_div_id, report_title) {
        if (!confirm('Are you sure to Print ?'))
            return false;
        if (!report_title)
            report_title = 'MRA Report';
        var w = 1020;
        var h = 580;
        if (window.screen) {
            w = window.screen.availWidth;
            h = window.screen.availHeight;
        }

        var objWindow = window.open("mra_report", "PrintWindow", "top=20,left=20,width=" + w + ",height=" + h + ",location=0,toolbar=0,statusbar=0,menubar=0,scrollbars=1,resizable=1");
        objWindow.document.write('<html> <head><title>');
        objWindow.document.write(report_title);
        objWindow.document.write('</title></head> <body><div class="report-container">');
        objWindow.document.write($('#' + report_div_id).html());
        objWindow.document.write('</div></body> </html>');
        objWindow.document.close();
        objWindow.focus();
        objWindow.print();
        return false;
    }

</script>

