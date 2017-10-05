<?php

$this->PhpExcel->createWorksheet();
$this->PhpExcel->setDefaultFont('Calibri', 12);

$this->PhpExcel->addTableHeader($header_row, array('name' => 'Cambria', 'bold' => true));

foreach ($branch_data as $branch_info) {

    $has_image = "Image Not Found";
    if (!empty($branch_info['BasicModuleBranchInfo']['image_name'])) {
        $path = WWW_ROOT . DS . 'files' . DS . 'uploads' . DS . 'branches' . DS . $branch_info['BasicModuleBranchInfo']['image_name'];
        if (file_exists($path) == 1)
            $has_image = "Image Found";
    }

    $this->PhpExcel->addTableRow(array(
        $branch_info['BasicModuleBasicInformation']['license_no'], $branch_info['BasicModuleBranchInfo']['name_of_org'],
        $branch_info['LookupBasicOfficeType']['office_type'], $branch_info['BasicModuleBranchInfo']['branch_name'],
        $branch_info['BasicModuleBranchInfo']['branch_code'], $branch_info['BasicModuleBranchInfo']['mailing_address'],
        $branch_info['LookupAdminBoundaryDistrict']['district_name'], $branch_info['LookupAdminBoundaryUpazila']['upazila_name'],
        $branch_info['BasicModuleBranchInfo']['mohalla_or_post_office'], $branch_info['BasicModuleBranchInfo']['road_name_or_village'],
        $branch_info['BasicModuleBranchInfo']['mobile_no'], $branch_info['BasicModuleBranchInfo']['phone_no'],
        $branch_info['BasicModuleBranchInfo']['fax'], $branch_info['BasicModuleBranchInfo']['email_address'],
        $branch_info['BasicModuleBranchInfo']['lat'], $branch_info['BasicModuleBranchInfo']['long'], $has_image), array('name' => 'Cambria', 'bold' => true)
    );
}

$current_datetime = date('Ymd_His');
$fileName = "branch_list-$current_datetime.xlsx";
$this->PhpExcel->addTableFooter();
$this->PhpExcel->output($fileName);
?>