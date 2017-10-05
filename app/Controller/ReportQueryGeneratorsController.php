<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

App::uses('ConnectionManager', 'Model');

//App::import('Vendor', 'HighchartsPHP/Highchart');

class ReportQueryGeneratorsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    //public $components = array('Paginator', 'Highcharts.Highcharts');
    public $paginate = array();
    public $components = array('Highcharts.Highcharts');

    //public $components = array('Highcharts.Highcharts');
    //public $helpers = array('GoogleMap');

    function is_numeric_array($data_array) {
        foreach ($data_array as $key => $value) {
            if (!is_numeric($value))
                return false;
        }
        return true;
    }

    function GetAdminConditions($div_opt = 'division_id', $dist_opt = 'district_id', $upza_opt = 'upazila_id', $union_opt = 'union_id') {

        $condition = null;

        if (!empty($div_opt)) {
            $admin_div = $this->Session->read("ReportQueryGenerator.SelectedAdmin.Div");
            if (!empty($admin_div))
                $condition[$div_opt] = $admin_div;
        }

        if (!empty($dist_opt)) {
            $admin_dist = $this->Session->read("ReportQueryGenerator.SelectedAdmin.Dist");
            if (!empty($admin_dist))
                $condition[$dist_opt] = $admin_dist;
        }

        if (!empty($upza_opt)) {
            $admin_upza = $this->Session->read("ReportQueryGenerator.SelectedAdmin.Upza");
            if (!empty($admin_upza))
                $condition[$upza_opt] = $admin_upza;
        }

        if (!empty($union_opt)) {
            $admin_union = $this->Session->read("ReportQueryGenerator.SelectedAdmin.Union");
            if (!empty($admin_union))
                $condition[$union_opt] = $admin_union;
        }

        return $condition;
    }

    function GetOrgConditions($org_id_opt = 'org_id', $org_branch_opt = 'branch_id') {

        $condition = null;

        if (!empty($org_id_opt)) {
            $org_ids = $this->Session->read("ReportQueryGenerator.SelectedOrg.Ids");
            if (!empty($org_ids))
                $condition[$org_id_opt] = $org_ids;
        }

        if (!empty($org_branch_opt)) {
            $org_branches = $this->Session->read("ReportQueryGenerator.SelectedOrg.BranchIds");
            if (!empty($org_branches))
                $condition[$org_branch_opt] = $org_branches;
        }

        return $condition;
    }

    public function map() {
        return;
    }

    public function query_generator() {

//        try {
//            $this->loadModel('LicenseModuleInitialAssessmentMark');
//            $aaa = $this->LicenseModuleInitialAssessmentMark->find('all');
//            
//            $this->loadModel('LicenseModuleInitialAssessmentMark1');
//            $aaa = $this->LicenseModuleInitialAssessmentMark1->find('all');
//            
//            
//            debug($aaa);
//        } catch (Exception $ex) {
//            debug($ex);
//        }
//
//        $data1 = array(['name' => 'Dhaka', 'y' => 7.0], ['name' => 'Tangail', 'y' => 6.8], ['name' => 'Dhaka1', 'y' => 9.5], ['name' => 'Uttara', 'y' => 7.13], ['name' => 'Dhaka', 'y' => 5.75]);
//        $chartData30 = array(
//            'no_of_male_borrowers' => '3',
//            'no_of_female_borrowers' => '3',
//            'total_loan_disbursement_amount' => '3',
//            'loan_outstanding' => '3',
//            'vf_no_of_male_borrowers + no_of_female_borrowers' => '6'
//        );
//
//        $chartData3 = array(['Dhaka', 7.0], ['Tangail', 6.8], ['Dhaka1', 9.5], ['Uttara', 7.13], ['Dhaka', 5.75]);
//
//        $chartData1 = array(7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6);
//        $chartData2 = array(-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5);
//
//        $chartData31 = array(
//            array('name' => 'Chrome', 'y' => 45.0, 'sliced' => true, 'selected' => true),
//            array('IE', 26.8),
//            array('Firefox', 12.8),
//            array('Safari', 8.5),
//            array('Opera', 6.2),
//            array('Others', 0.7)
//        );
//
//        $chartNameOne = 'Line Chart';
//        $chartNameTwo = 'Column Chart';
//        $chartNameThree = 'Pie Chart';
//
//        $mychartOne = $this->Highcharts->create($chartNameOne, 'line');
//        $mychartTwo = $this->Highcharts->create($chartNameTwo, 'column');
//        $mychartThree = $this->Highcharts->create($chartNameThree, 'pie');
//
//        $this->Highcharts->setChartParams($chartNameOne, array(
//            'renderTo' => 'line_chart1', // div to display chart inside
//            'chartWidth' => 800,
//            'chartHeight' => 600,
//            'title' => 'Monthly Sales Summary - Line',
//            'yAxisTitleText' => 'Units Sold',
//            'xAxisCategories' => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
//            'creditsEnabled' => false,
//            'exportingEnabled' => true,
//        ));
//
//        $this->Highcharts->setChartParams($chartNameTwo, array(
//            'renderTo' => 'bar_chart1', // div to display chart inside
//            'chartWidth' => 800,
//            'chartHeight' => 600,
//            'title' => 'Monthly Sales Summary - Column',
//            'yAxisTitleText' => 'Y Axis Title Text',
//            'xAxisCategories' => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
//            'creditsText' => 'Example.com',
//            'creditsURL' => 'http://example.com',
//            'exportingEnabled' => false,
//        ));
//
//
//
////        $dataLabelFormatFunction = "<<<EOF
////                function() { return '<b>' + {point.name} + '</b>'  + ({point.data:,.0f});}
////                EOF";
////        $dataLabelsFormat = "<<<EOF
////                function(){return this.point.name; }
////                EOF";
////        $tooltipFormatFunction = "<<<EOF
////                function(){return this.data +'%'; }
////                EOF";
//
//
//        $dataLabelFormatFunction = <<<EOF
//function() {return {point.name} + ({point.data:,.0f});}
//EOF;
//
//        $dataLabelsFormat = <<<EOF
//function() {return {point.name} + ({point.data:,.0f});}
//EOF;
//
//        $tooltipFormatFunction = <<<EOF
//function() {return this.series.name + this.x + ': ' + this.data + ' %';}
//EOF;
//
//        $this->Highcharts->setChartParams($chartNameThree, array(
//            'renderTo' => 'pie_chart1', // div to display chart inside
//            'chartWidth' => 800,
//            'chartHeight' => 600,
//            'title' => 'Population in BD',
//            'creditsText' => 'Example.com',
//            'creditsURL' => 'http://example.com',
//            'plotOptionsShowInLegend' => true,
//            'exportingEnabled' => true,
//            'plotOptionsSeriesDataLabelsFormat' => $dataLabelFormatFunction,
//            //'plotOptionsPieDataLabelsFormat' => $dataLabelsFormat,
//            'tooltipFormatter' => $tooltipFormatFunction,
//        ));
//
//        $seriesOne = $this->Highcharts->addChartSeries();
//        $seriesTwo = $this->Highcharts->addChartSeries();
//        $seriesThree = $this->Highcharts->addChartSeries();
//
//        $seriesOne->addName('Tokyo')->addData($chartData1);
//        $seriesTwo->addName('London')->addData($chartData2);
//        $seriesThree->addName('Population')->addData($chartData3);
//
//        $mychartOne->addSeries($seriesOne);
//        $mychartTwo->addSeries($seriesTwo);
//        $mychartThree->addSeries($seriesThree);
//
//        $this->set(compact('chartNameOne', 'chartNameTwo', 'chartNameThree'));



        $this->Session->write("ReportQueryGenerator.SelectedOrg.Ids", null);
        $this->Session->write("ReportQueryGenerator.SelectedOrg.BranchIds", null);

        $this->Session->write("ReportQueryGenerator.SelectedAdmin.Div", null);
        $this->Session->write("ReportQueryGenerator.SelectedAdmin.Dist", null);
        $this->Session->write("ReportQueryGenerator.SelectedAdmin.Upza", null);
        $this->Session->write("ReportQueryGenerator.SelectedAdmin.Union", null);


        $this->loadModel('LookupModelFieldDefinition');

        $model_ids = null;
        if ($this->request->is('post')) {
            //debug($this->request->data['ReportQueryGeneratorSelect']['listTable']);
            $model_ids = $this->request->data['ReportQueryGeneratorTableSelect']['listTable'];
            $condition = array('model_id' => $model_ids);
            $model_field_list = $this->LookupModelFieldDefinition->find('list', array('fields' => array('field_id', 'field_title_for_report'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_name', 'order' => 'field_id'));

            $this->set(compact('model_field_list'));
            $this->render('selected_model_fields');
            //return;
        }

        try {
            $this->loadModel('BasicModuleBasicInformation');
            //$this->BasicModuleBasicInformation->virtualFields['name_of_org'] = "CONCAT(full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT(' (<strong>', short_name_of_org, '</strong>)') ELSE short_name_of_org END)";
            $fields = array('id', 'name_of_org');
            $condition = array('BasicModuleBasicInformation.id >' => 0);
            $org_list = $this->BasicModuleBasicInformation->find('list', array('fields' => $fields, 'conditions' => $condition, 'recursive' => -1));


//            if ($this->request->is('post')) {
//                $option = $this->request->data['ProductModuleNewProductManagement']['search_option'];
//                $keyword = $this->request->data['ProductModuleNewProductManagement']['search_keyword'];
//                $condition = array("$option LIKE '%$keyword%'");
//            }
//            $this->paginate = array('conditions' => $condition, 'limit' => 10, 'recursive' => 0);
//            //$this->LookupModelFieldDefinition->recursive = 0;
//            $this->Paginator->settings = $this->paginate;
//            $values = $this->Paginator->paginate('LookupModelFieldDefinition');
//            $this->set(compact('values'));

            $condition = array('id >=' => 70);
            $model_list = $this->LookupModelFieldDefinition->LookupModelDefinition->find('list', array('fields' => array('id', 'model_description'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'model_name', 'order' => 'id'));

            $condition = array('model_id' => $model_ids);
            $field_list = $this->LookupModelFieldDefinition->find('list', array('fields' => array('field_id', 'field_title_for_report'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_name', 'order' => 'field_id'));

            //$condition = array('id >' => 0);
            $this->loadModel('LookupAdminBoundaryDivision');
            $div_list = $this->LookupAdminBoundaryDivision->find('list', array('fields' => array('id', 'division_name'), 'recursive' => -1, 'order' => 'id'));

            $this->loadModel('LookupAdminBoundaryDistrict');
            $dist_list = $this->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'district_name'), 'recursive' => -1, 'order' => 'id'));

//            debug($dist_list);
            $this->set(compact('org_list', 'model_list', 'field_list', 'div_list', 'dist_list'));
        } catch (Exception $ex) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => $ex->getMessage()
            );
            $this->set(compact('msg'));
        }
    }

    function selected_org_branches() {

        $org_list = null;
        $branch_list_all = null;

        if (!empty($this->request->data['ReportQueryGeneratorOrgSelect']['listOrg'])) {

            $org_ids = $this->request->data['ReportQueryGeneratorOrgSelect']['listOrg'];

            if (!empty($org_ids)) {
                $this->loadModel('BasicModuleBasicInformation');
                $fields = array('id', 'name_of_org');
                $condition = array('id' => $org_ids);
                $org_list = $this->BasicModuleBasicInformation->find('list', array('fields' => $fields, 'conditions' => $condition, 'recursive' => -1));

                $condition_admin = $this->GetAdminConditions(null, 'BasicModuleBranchInfo.district_id', 'BasicModuleBranchInfo.upazila_id', 'BasicModuleBranchInfo.union_id');
                if (!empty($condition_admin))
                    $condition = array_merge(array('BasicModuleBranchInfo.org_id' => $org_ids), $condition_admin);
                else
                    $condition = array('BasicModuleBranchInfo.org_id' => $org_ids);

                try {
                    $branch_list = $this->BasicModuleBasicInformation->BasicModuleBranchInfo->find('all', array('fields' => array('org_id', 'id', 'branch_with_address'), 'conditions' => $condition, 'recursive' => 1, 'order' => array('org_id', 'id')));
                    if (!empty($branch_list)) {
                        $branch_list_all = Hash::combine($branch_list, '{n}.BasicModuleBranchInfo.id', '{n}.BasicModuleBranchInfo.branch_with_address', '{n}.BasicModuleBranchInfo.org_id');
                    }
                } catch (Exception $ex) {
                    //debug($ex->getMessage());
                }
            }
        }

        $this->set(compact('org_list', 'branch_list_all'));
        return;
    }

    function basic_selection($opt = false) {

        $this->autoRender = false;

        $org_list = null;
        $branch_list_all = null;

        $this->Session->write("ReportQueryGenerator.SelectedOrg.Ids", null);
        $this->Session->write("ReportQueryGenerator.SelectedOrg.BranchIds", null);

        if (empty($opt))
            return;

        if (!empty($this->request->data['ReportQueryGeneratorOrgSelect']['listOrg'])) {

            $org_ids = $this->request->data['ReportQueryGeneratorOrgSelect']['listOrg'];

            if (!empty($org_ids)) {
                $this->Session->write("ReportQueryGenerator.SelectedOrg.Ids", $org_ids);

                if (!empty($this->request->data['ReportQueryGeneratorOrgSelect']['listOrgBranch'])) {

                    $org_branch_details = $this->request->data['ReportQueryGeneratorOrgSelect']['listOrgBranch'];

                    foreach ($org_branch_details as $org_id => $org_branch_id_list) {
                        if (empty($org_branch_id_list))
                            continue;

                        $org_branch_ids = (empty($org_branch_ids)) ? $org_branch_id_list : array_merge($org_branch_ids, $org_branch_id_list);
                    }

                    if (!empty($org_branch_ids)) {
                        $this->Session->write("ReportQueryGenerator.SelectedOrg.BranchIds", $org_branch_ids);
                    }
                }
            }
        }

        return;
    }

    function selected_model_fields() {

        $model_list = null;
        $model_field_list = null;
//        debug($this->request->data);

        if (!empty($this->request->data['ReportQueryGeneratorTableSelect']['listTable'])) {

            $this->loadModel('LookupModelFieldDefinition');
            $model_ids = $this->request->data['ReportQueryGeneratorTableSelect']['listTable'];

            $condition = array('id' => $model_ids);
            $model_list = $this->LookupModelFieldDefinition->LookupModelDefinition->find('all', array('fields' => array('id', 'model_name', 'model_description'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'model_name', 'order' => 'id'));

//            debug($model_list);

            $model_list = Hash::combine($model_list, '{n}.LookupModelDefinition.id', '{n}.LookupModelDefinition');
            $model_list = Hash::remove($model_list, '{n}.id');

//            debug($model_list);

            try {
                $condition = array('LookupModelFieldDefinition.model_id' => $model_ids, 'LookupModelFieldDefinition.display_in_report' => 1);
//                $this->LookupModelFieldDefinition->virtualFields['query_field_name'] = "CASE WHEN field_name_for_report IS NOT NULL AND TRIM(field_name_for_report) <> '' THEN CASE WHEN field_model_for_report IS NOT NULL AND TRIM(field_model_for_report) <> '' THEN CONCAT(field_id, ':', field_model_for_report, '.', field_name_for_report) ELSE CONCAT(field_id, ':', field_name_for_report) END ELSE NULL END";
//                $field_list = $this->LookupModelFieldDefinition->find('all', array('fields' => array('model_id', 'field_id', 'field_name', 'field_title_for_report', 'query_field_name'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_name', 'order' => 'model_id, field_sorting_order'));
//                $model_field_list1 = Hash::combine($field_list, '{n}.LookupModelFieldDefinition.query_field_name', '{n}.LookupModelFieldDefinition.field_title_for_report', '{n}.LookupModelFieldDefinition.model_id');
//            //$model_field_list = Hash::combine($field_list, '{n}.LookupModelFieldDefinition.field_id', '{n}.LookupModelFieldDefinition.query_field_name', '{n}.LookupModelFieldDefinition.field_title_for_report', '{n}.LookupModelFieldDefinition.model_id');
                //$this->LookupModelFieldDefinition->virtualFields['display_field_name'] = "CASE WHEN field_group_title IS NOT NULL AND TRIM(field_group_title) <> '' AND field_title_for_report IS NOT NULL AND TRIM(field_title_for_report) <> '' THEN CONCAT(field_group_title, ' (', field_title_for_report, ')') ELSE CONCAT_WS('', COALESCE(field_group_title,''), COALESCE(field_title_for_report,'')) END";

                $this->LookupModelFieldDefinition->virtualFields['display_field_name'] = "CASE WHEN field_group_title IS NOT NULL AND TRIM(field_group_title) <> '' AND field_title_for_report IS NOT NULL AND TRIM(field_title_for_report) <> '' THEN CONCAT(field_group_title, ' (', field_title_for_report, ')') ELSE CONCAT_WS('', field_group_title, field_title_for_report) END";
                $this->LookupModelFieldDefinition->virtualFields['query_field_name'] = "CASE WHEN field_name_for_report IS NOT NULL AND TRIM(field_name_for_report) <> '' THEN CASE WHEN field_model_for_report IS NOT NULL AND TRIM(field_model_for_report) <> '' THEN CONCAT_WS('.', field_id, field_model_for_report, field_name_for_report) ELSE CONCAT_WS('.', field_id, field_name_for_report) END ELSE NULL END";


                $field_list = $this->LookupModelFieldDefinition->find('all', array('fields' => array('model_id', 'display_field_name', 'query_field_name'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'field_id', 'order' => 'LookupModelFieldDefinition.model_id, LookupModelFieldDefinition.field_sorting_order, LookupModelFieldDefinition.field_id'));
                $model_field_list = Hash::combine($field_list, '{n}.LookupModelFieldDefinition.query_field_name', '{n}.LookupModelFieldDefinition.display_field_name', '{n}.LookupModelFieldDefinition.model_id');
//            
//                debug($field_list);
//                $field_listx = $this->LookupModelFieldDefinition->find('all', array('conditions' => $condition, 'recursive' => 0, 'group' => 'field_id', 'order' => 'LookupModelFieldDefinition.model_id, LookupModelFieldDefinition.field_sorting_order, LookupModelFieldDefinition.field_id'));
//                debug($field_listx);
                //                
//                debug($field_list);
//                debug($model_field_list);
            } catch (Exception $ex) {
                debug($ex);
            }


//            debug($model_field_list);
        }

        $this->set(compact('model_list', 'model_field_list'));
    }

    function generator_query_by_selected_fields() {

        //if (empty($this->request->data['ReportQueryGenerator']) || empty(strlen(implode($this->request->data['ReportQueryGenerator'])))) {
        if (empty($this->request->data['ReportQueryGenerator'])) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Report fields are not selected !'
            );
            $this->set(compact('msg'));

            return;
        }

        $allInfo = $this->request->data['ReportQueryGenerator'];

//        foreach ($allInfo as $model_id => $field_list)
//        {
//            if(!empty($field_list))
//                break;
//            
//        }
//        debug($allInfo);
//        if (count($allInfo) == 1 && empty($allInfo[0])) {
//            $msg = array(
//                'type' => 'error',
//                'title' => 'Error... ... !',
//                'msg' => 'Report fields are not selected !'
//            );
//            $this->set(compact('msg'));
//
//            return;
//        }


        foreach ($allInfo as $model_name => $selected_field_list) {

            if (empty($model_name) || empty($selected_field_list))
                continue;

            $basic_info_model_name = 'BasicModuleBasicInformation';
            $branch_model_name = 'BasicModuleBranchInfo';
            $branch_table_name = 'basic_module_branch_infos';

            $this->loadModel($model_name);

//            $this->$model_name->virtualFields = array('name_of_org' => "CONCAT(full_name_of_org, CASE WHEN full_name_of_org != '' AND short_name_of_org != '' THEN CONCAT(' (', short_name_of_org, ')') ELSE short_name_of_org END)",
//                'branch_with_address' => "SELECT CONCAT_WS(', ', branch_name, upazila_name, district_name) FROM basic_module_branch_infos, lookup_admin_boundary_upazilas, lookup_admin_boundary_districts WHERE (basic_module_branch_infos.id = $branch_model_name.id AND lookup_admin_boundary_upazilas.id = $branch_model_name.upazila_id AND lookup_admin_boundary_districts.id = $branch_model_name.district_id)");

            $cfc = 0;
            foreach ($selected_field_list as $field_id => $field_details) {

                unset($selected_field_list[$field_id]);

                if (empty($field_details) || !strpos($field_details, '.'))
                    continue;

                $field_details = explode('.', $field_details);
                if (empty($field_details[0]) || empty($field_details[1]) || empty($field_details[2]))
                    continue;

                $field_id = $field_details[0];
                $field_model_name = $field_details[1];
                $field_name = $field_details[2];

                if ($model_name == $field_model_name) {
                    if ($this->$model_name->hasField($field_name) || $this->$model_name->isVirtualField($field_name)) {
                        $selected_field_list[$field_id] = "$field_model_name.$field_name";
                    } else {
                        ++$cfc;
                        $virtual_field_name = "vf_$cfc";
                        $this->$model_name->virtualFields[$virtual_field_name] = "$field_name";
                        $selected_field_list[$field_id] = "$model_name.$virtual_field_name";
                    }
                    continue;
                }

                if (!$this->$model_name->$field_model_name->hasField($field_name) && !$this->$model_name->$field_model_name->isVirtualField($field_name))
                    continue;

                if ($this->$model_name->$field_model_name->isVirtualField($field_name)) {
                    $this->$model_name->virtualFields[$field_name] = $this->$model_name->$field_model_name->virtualFields[$field_name];
                    $selected_field_list[$field_id] = "$model_name.$field_name";
                } else
                    $selected_field_list[$field_id] = "$field_model_name.$field_name";
            }

            $field_id_list = array_keys($selected_field_list);

            try {
                $condition = array('LookupModelFieldDefinition.field_id' => $field_id_list);
                $this->loadModel('LookupModelFieldDefinition');

                $model_description = $this->LookupModelFieldDefinition->LookupModelDefinition->field('model_description', array('model_name' => $model_name));

                $field_detail_list = $this->LookupModelFieldDefinition->find('all', array('fields' => array('model_id', 'field_id', 'field_group_id', 'field_sub_group_id', 'field_title_for_report', 'field_sub_title_for_report'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'model_id, field_sorting_order, field_id'));

                $group_wise_field_title = Hash::combine($field_detail_list, '{n}.LookupModelFieldDefinition.field_id', '{n}.LookupModelFieldDefinition.field_title_for_report', '{n}.LookupModelFieldDefinition.field_group_id');
                $group_wise_field_sub_title = Hash::combine($field_detail_list, '{n}.LookupModelFieldDefinition.field_id', '{n}.LookupModelFieldDefinition.field_sub_title_for_report');

                $group_wise_field_sub_title = array_diff($group_wise_field_sub_title, array(null));

                $field_group_id_list = Hash::extract($field_detail_list, '{n}.LookupModelFieldDefinition.field_group_id');
                $condition = array('LookupModelFieldGroup.id' => $field_group_id_list);
                $field_group_detail_list = $this->LookupModelFieldDefinition->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'id', 'order' => 'id'));
            } catch (Exception $ex) {
                debug($ex);
            }

            $basicOption = $this->GetOrgConditions("$model_name.org_id", "$model_name.branch_id");
            $adminOption = $this->GetAdminConditions(null, "$branch_model_name.district_id", "$branch_model_name.upazila_id", "$branch_model_name.union_id");

            $conditions = (!empty($basicOption) && !empty($adminOption)) ? array_merge($basicOption, $adminOption) : (!empty($basicOption) ? $basicOption : $adminOption);

            try {
                $all_data = $this->$model_name->find('all', array('fields' => $selected_field_list, 'conditions' => $conditions, 'recursive' => 1, 'order' => "$model_name.org_id")); //, $model_name.branch_id
            } catch (Exception $ex) {
                debug($ex);
            }

            $model_wise_data_details[$model_name] = array('all_data' => $all_data, 'selected_field_list' => $selected_field_list, 'field_group_detail_list' => $field_group_detail_list, 'group_wise_field_title' => $group_wise_field_title, 'group_wise_field_sub_title' => $group_wise_field_sub_title);
        }

        //debug($model_wise_data_details);
        if (empty($model_wise_data_details)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Report data not available !'
            );
            $this->set(compact('msg'));

            return;
        }

        $this->set(compact('model_wise_data_details'));

        $this->Session->write("ReportChartGenerator.Chart.Name", $model_description);
        $this->Session->write("ReportChartGenerator.Chart.AllDataDetails", $model_wise_data_details);
    }

    function set_chart_opts() {

        $model_wise_data_details = $this->Session->read("ReportChartGenerator.Chart.AllDataDetails");
        if (empty($model_wise_data_details) || !is_array($model_wise_data_details)) {
            return;
        }

        foreach ($model_wise_data_details as $model_name => $model_wise_data) {
            $all_data = $model_wise_data['all_data'];
            $selected_field_list = $model_wise_data['selected_field_list'];
            $field_group_detail_list = $model_wise_data['field_group_detail_list'];
            $group_wise_field_title = $model_wise_data['group_wise_field_title'];

            $chartFields = array();
            $chartAllData = array();
            $chartYTitles = array();

            foreach ($all_data as $data) {
                $dr_data = '';
                $chartData = array();
                $chartSeriesName = '';
                foreach ($group_wise_field_title as $group_id => $field_list) {

                    if (empty($group_id) || empty($field_list) || count($field_list) < 1)
                        continue;

                    foreach ($field_list as $field_id => $field) {

                        $data_field_details = $selected_field_list[$field_id];
                        $data_field_details = explode('.', $data_field_details);
                        $model_name = $data_field_details[0];
                        $field_name = $data_field_details[1];

                        $data_value = $data[$model_name][$field_name];
                        if (!is_numeric($data_value)) {
                            if (!empty($data_value))
                                $chartSeriesName = !empty($chartSeriesName) ? "$chartSeriesName ($data_value)" : $data_value;
                            continue;
                        }

                        $data_group_name = $field_group_detail_list[$group_id];
                        $data_name = !empty($data_group_name) && !empty($field) ? "$data_group_name ($field)" : "$data_group_name$field";

                        if (!in_array($data_name, $chartFields))
                            $chartFields[] = $data_name;

                        if (!empty($data_group_name) && !in_array($data_group_name, $chartYTitles))
                            $chartYTitles[$data_name] = $data_group_name;

                        $chartData[] = array('name' => $data_name, 'y' => floatval($data_value));
                    }
                }

                $chartSeries[] = $chartSeriesName;
                $chartAllData[$chartSeriesName] = $chartData;
            }

            $chartName = $this->Session->read("ReportChartGenerator.Chart.Name");

            $this->Session->write("ReportChartGenerator.Chart.Series", $chartSeries);
            $this->Session->write("ReportChartGenerator.Chart.Fields", $chartFields);
            $this->Session->write("ReportChartGenerator.Chart.AllData", $chartAllData);

            $this->Session->write("ReportChartGenerator.Chart.Y-Titles", $chartYTitles);

//            $chartSeriesName = $chartSeries[0];
//            $chartData = $chartAllData[$chartSeriesName];
//            $chartFields = Hash::extract($chartData, '{n}.name');
//            $chartDataPie = $chartData;

            $this->request->data['ReportChartGeneratorOptions']['fieldSeries'] = array_keys($chartSeries);
            $this->request->data['ReportChartGeneratorOptions']['fieldList'] = array_keys($chartFields);

            $this->set(compact('chartSeries', 'chartFields'));

            $chartYTitle = implode(', ', $chartYTitles);

            $chartNameLine = 'Line Chart';
            $chartNameColumn = 'Column Chart';
            $chartNamePie = 'Pie Chart';

            $mychartLine = $this->Highcharts->create($chartNameLine, 'line');
            $mychartColumn = $this->Highcharts->create($chartNameColumn, 'column');
            $mychartPie = $this->Highcharts->create($chartNamePie, 'pie');

            $this->Highcharts->setChartParams($chartNameLine, array(
                'renderTo' => 'line_chart',
                'chartWidth' => 800,
                'chartHeight' => 600,
                'title' => $chartName,
                'yAxisTitleText' => $chartYTitle,
                'xAxisCategories' => $chartFields,
                'creditsEnabled' => false,
                'exportingEnabled' => true,
            ));

            $this->Highcharts->setChartParams($chartNameColumn, array(
                'renderTo' => 'bar_chart',
                'chartWidth' => 800,
                'chartHeight' => 600,
                'title' => $chartName,
                'yAxisTitleText' => $chartYTitle,
                'xAxisCategories' => $chartFields,
                'creditsEnabled' => false,
                'exportingEnabled' => false,
            ));


            $dataLabelFormat = <<<EOF
                function() {return {{point.name}: ({point.x}: {point.y: .1f}%);} 
EOF;

            $dataLabelsFormat = <<<EOF
                function() {return {point.name}: ({point.y: .0f});}
EOF;

            $tooltipFormat = <<<EOF
                function() {return {point.name}: ({point.y: .0f});}
EOF;

            $this->Highcharts->setChartParams($chartNamePie, array(
                'renderTo' => 'pie_chart',
                'chartWidth' => 800,
                'chartHeight' => 600,
                'title' => $chartName,
                'creditsEnabled' => false,
                'plotOptionsShowInLegend' => true,
                'exportingEnabled' => true,
                'plotOptionsSeriesDataLabelsFormat' => "{point.name} ({series.name}: {point.y:.1f}%)",
                //'plotOptionsPieDataLabelsFormat' => $dataLabelsFormat,
                'tooltipFormatter' => $tooltipFormat,
            ));

            foreach ($chartAllData as $chartSeriesName => $chartData) {
                $chartDataSeries = $this->Highcharts->addChartSeries();
                $chartDataSeries->addName($chartSeriesName)->addData(Hash::extract($chartData, '{n}.y'));
                $mychartLine->addSeries($chartDataSeries);
                $mychartColumn->addSeries($chartDataSeries);
            }

            $chartSeriesName = $chartSeries[0];
            $chartPieDataSeries = $this->Highcharts->addChartSeries();
            $chartPieDataSeries->addName($chartSeriesName)->addData($chartAllData[$chartSeriesName]);
            $mychartPie->addSeries($chartPieDataSeries);

            $this->set(compact('chartNameLine', 'chartNameColumn', 'chartNamePie'));

            return;
        }
    }

    function set_chart() {

        if (isset($this->request->data['ReportChartGeneratorOptions'])) {
            $selected_series = $this->request->data['ReportChartGeneratorOptions']['fieldSeries'];
            $selected_fields = $this->request->data['ReportChartGeneratorOptions']['fieldList'];
        }

        if (empty($selected_series)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Chart data series is not selected !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (empty($selected_fields)) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Chart fields are not selected !'
            );
            $this->set(compact('msg'));
            return;
        }

        $chartName = $this->Session->read("ReportChartGenerator.Chart.Name");
        $chartSeries = $this->Session->read("ReportChartGenerator.Chart.Series");
        $chartFields = $this->Session->read("ReportChartGenerator.Chart.Fields");
        $chartAllData = $this->Session->read("ReportChartGenerator.Chart.AllData");

        $chartYTitles = $this->Session->read("ReportChartGenerator.Chart.Y-Titles");

        $chartFields = array_intersect_key($chartFields, $selected_fields); //array_keys($selected_fields);
        $chartYTitle = implode(', ', array_intersect_key($chartFields, array_keys($chartFields)));

        $chartNameLine = 'Line Chart';
        $chartNameColumn = 'Column Chart';
        $chartNamePie = 'Pie Chart';

        $mychartLine = $this->Highcharts->create($chartNameLine, 'line');
        $mychartColumn = $this->Highcharts->create($chartNameColumn, 'column');
        $mychartPie = $this->Highcharts->create($chartNamePie, 'pie');

        $this->Highcharts->setChartParams($chartNameLine, array(
            'renderTo' => 'line_chart',
            'chartWidth' => 800,
            'chartHeight' => 600,
            'title' => $chartName,
            'yAxisTitleText' => $chartYTitle,
            'xAxisCategories' => $chartFields,
            'creditsEnabled' => false,
            'exportingEnabled' => true,
        ));

        $this->Highcharts->setChartParams($chartNameColumn, array(
            'renderTo' => 'bar_chart',
            'chartWidth' => 800,
            'chartHeight' => 600,
            'title' => $chartName,
            'yAxisTitleText' => $chartYTitle,
            'xAxisCategories' => $chartFields,
            'creditsEnabled' => false,
            'exportingEnabled' => false,
        ));

        $dataLabelFormat = <<<EOF
                function() {return {{point.name}: ({point.x}: {point.y: .1f}%);} 
EOF;

        $dataLabelsFormat = <<<EOF
                function() {return {point.name}: ({point.y: .0f});}
EOF;

        $tooltipFormat = <<<EOF
                function() {return {point.name}: ({point.y: .0f});}
EOF;

        $dataLabelsFormat = <<<EOF
function(){return this.point.name; }
EOF;

        $tooltipFormat = <<<EOF
function(){return this.y +'%'; }
EOF;

        $this->Highcharts->setChartParams($chartNamePie, array(
            'renderTo' => 'pie_chart',
            'chartWidth' => 800,
            'chartHeight' => 600,
            'title' => $chartName,
            'creditsEnabled' => false,
            'plotOptionsShowInLegend' => true,
            'exportingEnabled' => true
        ));

        foreach ($selected_series as $series_id) {
            $chartSeriesName = $chartSeries[$series_id];

            $chartData = array_intersect_key($chartAllData[$chartSeriesName], $selected_fields);
            $chartData = Hash::extract($chartData, '{n}.y');

            $chartDataSeries = $this->Highcharts->addChartSeries();
            $chartDataSeries->addName($chartSeriesName)->addData($chartData);
            $mychartLine->addSeries($chartDataSeries);
            $mychartColumn->addSeries($chartDataSeries);
        }

        $chartSeriesName = $chartSeries[$selected_series[0]];
        $chartDataPie = array_intersect_key($chartAllData[$chartSeriesName], $selected_fields);

        $chartPieDataSeries = $this->Highcharts->addChartSeries();
        $chartPieDataSeries->addName($chartSeriesName)->addData($chartDataPie);
        $mychartPie->addSeries($chartPieDataSeries);

        $this->set(compact('chartNameLine', 'chartNameColumn', 'chartNamePie'));
    }

    function stringToArray($string, $delimiter = ',', $key_delimiter = '=>') {
        if ($arr = explode($delimiter, $string)) {
            $new_arr = array();
            foreach ($arr as $str) {
                if ($str) {
                    if ($pos = strpos($str, $key_delimiter)) {
                        $new_arr[trim(substr($str, 0, $pos))] = trim(substr($str, $pos + strlen($key_delimiter)));
                    } else {
                        $new_arr[] = trim($str);
                    }
                }
            }
            return $new_arr;
        }
        return null;
    }

    function multiexplode($delimiters, $string) {
        $ary = explode($delimiters[0], $string);
        array_shift($delimiters);
        if ($delimiters != NULL) {
            foreach ($ary as $key => $val) {
                $ary[$key] = multiexplode($delimiters, $val);
            }
        }
        return $ary;
    }

    function selected_admin_dists() {

        $div_list = null;
        $dist_list_all = null;

        $this->Session->write("ReportQueryGenerator.SelectedAdmin.Div", null);
//        $this->Session->write("ReportQueryGenerator.CurrentAdminOption", null);

        if (!empty($this->request->data['ReportQueryGeneratorDivSelect']['listDiv'])) {

            $div_ids = $this->request->data['ReportQueryGeneratorDivSelect']['listDiv'];

            $condition = array('id' => $div_ids);
            $this->loadModel('LookupAdminBoundaryDivision');
            $div_list = $this->LookupAdminBoundaryDivision->find('list', array('fields' => array('id', 'division_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

            if (!empty($div_ids)) {
                $condition = array('division_id' => $div_ids);

                $this->Session->write("ReportQueryGenerator.SelectedAdmin.Div", $div_ids);
//                $this->Session->write("ReportQueryGenerator.CurrentAdminOption", "division_id in (" . implode(", ", $div_ids) . ")");

                $this->loadModel('LookupAdminBoundaryDistrict');
                $dist_list = $this->LookupAdminBoundaryDistrict->find('all', array('fields' => array('division_id', 'id', 'district_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

                $dist_list_all = Hash::combine($dist_list, '{n}.LookupAdminBoundaryDistrict.id', '{n}.LookupAdminBoundaryDistrict.district_name', '{n}.LookupAdminBoundaryDistrict.division_id');
            }
        }

        $this->set(compact('div_list', 'dist_list_all'));
    }

    function selected_admin_upzas() {

        $upaz_list = null;
        $upaz_list_all = null;

        $this->Session->write("ReportQueryGenerator.SelectedAdmin.Dist", null);

        if (!empty($this->request->data['ReportQueryGeneratorDivSelect']['listDiv']) || !empty($this->request->data['ReportQueryGeneratorDistSelect'])) {

            if (!empty($this->request->data['ReportQueryGeneratorDivSelect']['listDiv'])) {
                $div_ids = $this->request->data['ReportQueryGeneratorDivSelect']['listDiv'];

                $condition = array('division_id' => $div_ids);
                $this->loadModel('LookupAdminBoundaryDistrict');
                $dist_ids = $this->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'id'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));
            } else {
                $dist_details = $this->request->data['ReportQueryGeneratorDistSelect'];

                $dist_ids = null;
                foreach ($dist_details as $div_id => $dist_id_list) {
                    if (empty($dist_id_list))
                        continue;

                    $dist_ids = (empty($dist_ids)) ? $dist_id_list : array_merge($dist_ids, $dist_id_list);
                }
            }


            if (!empty($dist_ids)) {
                $condition = array('id' => $dist_ids);
                $this->loadModel('LookupAdminBoundaryDistrict');
                $dist_list = $this->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'district_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

                $condition = array('district_id' => $dist_ids);

                $this->Session->write("ReportQueryGenerator.SelectedAdmin.Dist", $dist_ids);
                //$this->Session->write("ReportQueryGenerator.CurrentAdminOption", "district_id in (" . implode(", ", $dist_ids) . ")");

                $this->loadModel('LookupAdminBoundaryUpazila');
                $upaz_list = $this->LookupAdminBoundaryUpazila->find('all', array('fields' => array('district_id', 'id', 'upazila_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

                $upaz_list_all = Hash::combine($upaz_list, '{n}.LookupAdminBoundaryUpazila.id', '{n}.LookupAdminBoundaryUpazila.upazila_name', '{n}.LookupAdminBoundaryUpazila.district_id');
            }
        }

        $this->set(compact('dist_list', 'upaz_list_all'));
    }

    function selected_admin_unions() {

        $upaz_list = null;
        $upaz_list_all = null;
        $union_list_all = null;

        debug($this->request->data);
        $this->autoRender = false;

        die();
        return;
//        $this->set(compact('dist_list', 'upaz_list_all'));
//        return;

        $this->Session->write("ReportQueryGenerator.SelectedAdmin.Dist", null);
        //$this->Session->write("ReportQueryGenerator.CurrentAdminOption", null);


        if (!empty($this->request->data['ReportQueryGeneratorDivSelect']['listDiv']) || !empty($this->request->data['ReportQueryGeneratorDistSelect'])) {

            if (!empty($this->request->data['ReportQueryGeneratorDivSelect']['listDiv'])) {
                $div_ids = $this->request->data['ReportQueryGeneratorDivSelect']['listDiv'];

                $condition = array('division_id' => $div_ids);
                $this->loadModel('LookupAdminBoundaryDistrict');
                $dist_ids = $this->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'id'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));
            } else {
                $dist_details = $this->request->data['ReportQueryGeneratorDistSelect'];

                $dist_ids = null;
                foreach ($dist_details as $div_id => $dist_id_list) {
                    if (empty($dist_id_list))
                        continue;

                    $dist_ids = (empty($dist_ids)) ? $dist_id_list : array_merge($dist_ids, $dist_id_list);
                }
            }


            if (!empty($dist_ids)) {
                $condition = array('id' => $dist_ids);
                $this->loadModel('LookupAdminBoundaryDistrict');
                $dist_list = $this->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'district_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

                $condition = array('district_id' => $dist_ids);

                $this->Session->write("ReportQueryGenerator.SelectedAdmin.Dist", $dist_ids);
                //$this->Session->write("ReportQueryGenerator.CurrentAdminOption", "district_id in (" . implode(", ", $dist_ids) . ")");

                $this->loadModel('LookupAdminBoundaryUpazila');
                $upaz_list = $this->LookupAdminBoundaryUpazila->find('all', array('fields' => array('district_id', 'id', 'upazila_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

                $upaz_list_all = Hash::combine($upaz_list, '{n}.LookupAdminBoundaryUpazila.id', '{n}.LookupAdminBoundaryUpazila.upazila_name', '{n}.LookupAdminBoundaryUpazila.district_id');
            }
        }

        $this->set(compact('dist_list', 'upaz_list_all', 'union_list_all'));
    }

}
