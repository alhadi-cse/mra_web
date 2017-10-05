
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


$no_of_member_male = $no_of_member_female = $no_of_member_male_next = $no_of_member_female_next = 2450;
$no_of_borrower_male = $no_of_borrower_female = $no_of_borrower_male_next = $no_of_borrower_female_next = 1542;

$loan_disbursed_all = $loan_disbursed_all_next = $loan_disbursed_t10 = $loan_disbursed_t10_next = 587552;
$saving_balance_all = $saving_balance_all_next = $saving_balance_t10 = $saving_balance_t10_next = 654217;

$market_share_member_all = $market_share_member_all_next = 481;
$market_share_member_t10 = $market_share_member_t10_next = 4512;
$market_share_member_t20 = $market_share_member_t20_next = 8542;


$market_share_branch_all = $market_share_branch_all_next = 784;
$market_share_branch_t10 = $market_share_branch_t10_next = 35412;
$market_share_branch_t20 = $market_share_branch_t20_next = 98676;

$return_asset = $return_asset_next = 854752;
$return_equity = $return_equity_next = 254672;
$net_income = $net_income_next = 8626358;
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
            <td rowspan="3" style="text-align: center; vertical-align: top;">01.</td>
            <td>Number of members (in millions)</td>
            <td style="text-align: right;"><?php echo $no_of_member_male + $no_of_member_female ?></td>
            <td style="text-align: right;"><?php echo $no_of_member_male_next + $no_of_member_female_next ?></td>
        </tr>
        <tr>
            <td>a. Male (in millions)</td>
            <td style="text-align: right;"><?php echo $no_of_member_male ?></td>
            <td style="text-align: right;"><?php echo $no_of_member_male_next ?></td>
        </tr>
        <tr>
            <td>b. Female (in millions)</td>
            <td style="text-align: right;"><?php echo $no_of_member_female ?></td>
            <td style="text-align: right;"><?php echo $no_of_member_female_next ?></td>
        </tr>
        <tr>
            <td rowspan="3" style="text-align: center; vertical-align: top;">02.</td>
            <td>Number of borrowers (in millions)</td>
            <td style="text-align: right;"><?php echo $no_of_borrower_male + $no_of_borrower_female ?></td>
            <td style="text-align: right;"><?php echo $no_of_borrower_male_next + $no_of_borrower_female_next ?></td>
        </tr>
        <tr>
            <td>a. Male (in millions)</td>
            <td style="text-align: right;"><?php echo $no_of_borrower_male ?></td>
            <td style="text-align: right;"><?php echo $no_of_borrower_male_next ?></td>
        </tr>
        <tr>
            <td>b. Female (in millions)</td>
            <td style="text-align: right;"><?php echo $no_of_borrower_female ?></td>
            <td style="text-align: right;"><?php echo $no_of_borrower_female_next ?></td>
        </tr>
        <tr>
            <td rowspan="2" style="text-align: center; vertical-align: top;">03.</td>
            <td>Outstanding Loan disbursed by licensed institutions (in billions)</td>
            <td style="text-align: right;"><?php echo $loan_disbursed_all ?></td>
            <td style="text-align: right;"><?php echo $loan_disbursed_all_next ?></td>
        </tr>
        <tr>
            <td>a. By Top 10 MFI's</td>
            <td style="text-align: right;"><?php echo $loan_disbursed_t10 ?></td>
            <td style="text-align: right;"><?php echo $loan_disbursed_t10_next ?></td>
        </tr>
        <tr>
            <td rowspan="2" style="text-align: center; vertical-align: top;">04.</td>
            <td>Outstanding Saving Balance by licensed institutions (in billions)</td>
            <td style="text-align: right;"><?php echo $saving_balance_all ?></td>
            <td style="text-align: right;"><?php echo $saving_balance_all_next ?></td>
        </tr>
        <tr>
            <td>a. By Top 10 MFI's</td>
            <td style="text-align: right;"><?php echo $saving_balance_t10 ?></td>
            <td style="text-align: right;"><?php echo $saving_balance_t10_next ?></td>
        </tr>
        <tr>
            <td rowspan="3" style="text-align: center; vertical-align: top;">05.</td>
            <td>Market share by Number of Active Members (in percentage)</td>
            <td style="text-align: right;"><?php echo $market_share_member_all ?></td>
            <td style="text-align: right;"><?php echo $market_share_member_all_next ?></td>
        </tr>
        <tr>
            <td>a. By Top 10 MFI's</td>
            <td style="text-align: right;"><?php echo $market_share_member_t10 ?></td>
            <td style="text-align: right;"><?php echo $market_share_member_t10_next ?></td>
        </tr>
        <tr>
            <td>b. By Top 20 MFI's</td>
            <td style="text-align: right;"><?php echo $market_share_member_t20 ?></td>
            <td style="text-align: right;"><?php echo $market_share_member_t20_next ?></td>
        </tr>
        <tr>
            <td rowspan="3" style="text-align: center; vertical-align: top;">06.</td>
            <td>Market share by Number of Branches (in percentage)</td>
            <td style="text-align: right;"><?php echo $market_share_branch_all ?></td>
            <td style="text-align: right;"><?php echo $market_share_branch_all_next ?></td>
        </tr>
        <tr>
            <td>a. By Top 10 MFI's</td>
            <td style="text-align: right;"><?php echo $market_share_branch_t10 ?></td>
            <td style="text-align: right;"><?php echo $market_share_branch_t10_next ?></td>
        </tr>
        <tr>
            <td>b. By Top 20 MFI's</td>
            <td style="text-align: right;"><?php echo $market_share_branch_t20 ?></td>
            <td style="text-align: right;"><?php echo $market_share_branch_t20_next ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">07.</td>
            <td>Return on Asset (ROA)</td>
            <td style="text-align: right;"><?php echo $return_asset ?></td>
            <td style="text-align: right;"><?php echo $return_asset_next ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">08.</td>
            <td>Return on Equity (ROE)</td>
            <td style="text-align: right;"><?php echo $return_equity ?></td>
            <td style="text-align: right;"><?php echo $return_equity_next ?></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: top;">09.</td>
            <td>Net Income (in Million)</td>
            <td style="text-align: right;"><?php echo $net_income ?></td>
            <td style="text-align: right;"><?php echo $net_income_next ?></td>
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

