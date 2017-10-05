<?php

App::uses('AppController', 'Controller');
//App::uses('Folder', 'Utility');
//App::uses('File', 'Utility');

App::uses('ConnectionManager', 'Model');

//App::import('Vendor', 'HighchartsPHP/Highchart');
//App::import('Helper', 'Javascript');

class ReportModuleReportViewersController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator');
    public $paginate = array();
    public $components = array('Highcharts.Highcharts');

    function is_numeric_array($data_array) {
        foreach ($data_array as $key => $value) {
            if (!is_numeric($value))
                return false;
        }
        return true;
    }

    public function map() {
        return;
    }

//
//        $operators = array('=' => '=', '<>' => '<>', '<' => '<', '>' => '>', 'LIKE' => 'Like');
//        $orders = array('ASC' => 'Ascending', 'DESC' => 'Descending');
//	'FilterFieldList',
//	'GroupByFieldList',
//	'OrderByFieldList'
    //function GetReportConditions($div_opt = 'division_id', $dist_opt = 'district_id', $upza_opt = 'upazila_id', $union_opt = 'union_id') {
    //function GetReportConditions($base_model = null, $base_opts = ['period_id', 'from_date', 'to_date'], $org_opts = ['org_id', 'branch_id'], $admin_opts = ['division_id', 'district_id', 'upazila_id', 'union_id']) {
    //function GetReportConditions($base_model = null, $base_opts = array('period_id', 'from_date', 'to_date'), $org_opts = array('org_id', 'branch_id'), $admin_opts = array('division_id', 'district_id', 'upazila_id', 'union_id')) {
    function GetReportConditions($base_model = null, $base_opts = null, $org_opts = null, $admin_opts = null) {

        if (empty($base_opts))
            $base_opts = array('period_id', 'from_date', 'to_date');
        if (empty($org_opts)) {
            if (!empty($base_model) && $base_model == 'BasicModuleBasicInformation')
                $org_opts = array('id', 'branch_id');
            else
                $org_opts = array('org_id', 'branch_id');
        }
        if (empty($admin_opts))
            $admin_opts = array('division_id', 'district_id', 'upazila_id', 'union_id');

        $report_query_options = array();

        $rpt_all_opts = $this->Session->read("ReportModuleReportViewer.FilterReportOptions");

        if (!empty($rpt_all_opts['GroupByFieldList']['fields'])) {
            $group_by_field_list = $rpt_all_opts['GroupByFieldList']['fields'];
            $report_query_options['group'] = $group_by_field_list;
        }

        if (!empty($rpt_all_opts['OrderByFieldList'])) {
            $order_by_field_list = $rpt_all_opts['OrderByFieldList'];
            foreach ($order_by_field_list as $order_by_field) {
                if (empty($order_by_field['field']) || empty($order_by_field['order']))
                    continue;
                $report_query_options['order'][$order_by_field['field']] = $order_by_field['order'];
            }
        }

        if (!empty($rpt_all_opts['FilterFieldList'])) {
            $filter_field_list = $rpt_all_opts['FilterFieldList'];
            foreach ($filter_field_list as $filter_field) {

                if (empty($filter_field['field']) || empty($filter_field['operator']) || !isset($filter_field['value']) || trim($filter_field['value']) == '')
                    continue;
                $field_name = $filter_field['field'];
                $operator = $filter_field['operator'];
                $value = $filter_field['value'];
                switch ($operator) {
                    case '=':
                        $report_query_options['conditions'][$field_name] = $value;
                        break;
                    case '<>':
                    case '>':
                    case '<':
                        $report_query_options['conditions']["$field_name $operator"] = $value;
                        break;
                    case 'LIKE':
                        $report_query_options['conditions']["$field_name $operator"] = (strpos($value, '%') !== false) ? "%$value%" : $value;
                        break;
                    default :
                        break;
                }
            }
        }

//[$filter_field['field'] + " $operator"] = '%' + $filter_field['value'] + '%';

        if (empty($base_model) || empty($rpt_all_opts['BasicOpt']))
            return $report_query_options;

        try {

            $filter_basic_opts = $rpt_all_opts['BasicOpt'];

            if (!empty($filter_basic_opts["limit"]))
                $report_query_options['limit'] = $filter_basic_opts["limit"];

//                if (!empty($filter_basic_opts["order_dir"]) && !empty($report_query_options['order'])) {
//                    $report_query_options['order'] = array($report_query_options['order'] => $filter_basic_opts["order_dir"]);
//                }

            if (!empty($base_opts[0]) && !empty($filter_basic_opts["period_id"])) {
                $period_id_opt = $base_opts[0];
                if ($this->$base_model->hasField($period_id_opt))
                    $report_query_options['conditions']["$base_model.$period_id_opt"] = $filter_basic_opts["period_id"];
                else if ($this->$base_model->AdminModulePeriodList->hasField($period_id_opt))
                    $report_query_options['conditions']["AdminModulePeriodList.id"] = $filter_basic_opts["period_id"];

//               if ($this->$base_model->hasField($period_id_opt) || $this->$base_model->AdminModulePeriodList->hasField($period_id_opt))
//                   $report_query_options['conditions'][$period_id_opt] = $filter_basic_opts["period_id"];
            } else {

                if (!empty($base_opts[1]) && !empty($filter_basic_opts["from_date"])) {
                    $from_date_opt = $base_opts[1];
                    if ($this->$base_model->hasField($from_date_opt))
                        $report_query_options['conditions']["$base_model.$from_date_opt >="] = $filter_basic_opts["from_date"];
                    else if ($this->$base_model->AdminModulePeriodList->hasField($from_date_opt))
                        $report_query_options['conditions']["AdminModulePeriodList.$from_date_opt >="] = $filter_basic_opts["from_date"];

//               if ($this->$base_model->hasField($from_date_opt) || $this->$base_model->AdminModulePeriodList->hasField($from_date_opt))
//                   $report_query_options['conditions'][$from_date_opt] = $filter_basic_opts["from_date"];
                }

                if (!empty($base_opts[2]) && !empty($filter_basic_opts["to_date"])) {
                    $to_date_opt = $base_opts[2];

                    if ($this->$base_model->hasField($to_date_opt))
                        $report_query_options['conditions']["$base_model.$to_date_opt <="] = $filter_basic_opts["to_date"];
                    else if ($this->$base_model->AdminModulePeriodList->hasField($to_date_opt))
                        $report_query_options['conditions']["AdminModulePeriodList.$to_date_opt <="] = $filter_basic_opts["to_date"];

//                if ($this->$base_model->hasField($to_date_opt) || $this->$base_model->AdminModulePeriodList->hasField($to_date_opt))
//                    $report_query_options['conditions'][$to_date_opt] = $filter_basic_opts["to_date"];
                }
            }

//        $rpt_all_opts[BasicOpt] => array(
//		'order_dir' => 'desc',
//		'limit' => '',
//		'period_id' => '',
//		'from_date' => '',
//		'to_date' => '',
//		'org_id' => ''
//	),
//$filter_basic_opts['org_id']

            if (!empty($org_opts[0]) && !empty($filter_basic_opts['org_id'])) {
                $org_opt = $org_opts[0];

                if ($this->$base_model->hasField($org_opt))
                    $report_query_options['conditions']["$base_model.$org_opt"] = $filter_basic_opts['org_id'];
//            else if ($this->$base_model->BasicModuleBranchInfo->hasField($org_opt))
//                $report_query_options['conditions']["BasicModuleBranchInfo.$org_opt"] = $filter_basic_opts['org_id'];
//            if ($this->$base_model->hasField($org_opt) || $this->$base_model->BasicModuleBranchInfo->hasField($org_opt))
//                $report_query_options['conditions'][$org_opt] = $filter_basic_opts['org_id'];
            }

//            $org_branches = $this->Session->read("ReportModuleReportViewer.SelectedOrg.BranchIds");
            if (!empty($org_opts[1]) && !empty($filter_basic_opts['branches_id'])) {
                $org_opt = $org_opts[1];

                if ($this->$base_model->hasField($org_opt))
                    $report_query_options['conditions']["$base_model.$org_opt"] = $filter_basic_opts['branches_id'];
                else if ($this->$base_model->BasicModuleBranchInfo->hasField($org_opt))
                    $report_query_options['conditions']["BasicModuleBranchInfo.$org_opt"] = $filter_basic_opts['branches_id'];

//            if ($this->$base_model->hasField($org_opt) || $this->$base_model->BasicModuleBranchInfo->hasField($org_opt))
//                $report_query_options['conditions'][$org_opt] = $org_branches;
            }


            $admin_divs = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Divs");
            //debug($admin_divs);
            if (!empty($admin_opts[0]) && !empty($admin_divs)) {
                $div_opt = $admin_opts[0];

                if ($this->$base_model->hasField($div_opt))
                    $report_query_options['conditions']["$base_model.$div_opt"] = $admin_divs;
                else if ($this->$base_model->BasicModuleBranchInfo->hasField($div_opt))
                    $report_query_options['conditions']["BasicModuleBranchInfo.$div_opt"] = $admin_divs;
//            else if ($this->$base_model->BasicModuleBranchInfo->hasField($div_opt))
//                $report_query_options['conditions']["BasicModuleBranchInfo.$div_opt"] = $admin_divs;
//            if ($this->$base_model->hasField($div_opt) || $this->$base_model->BasicModuleBranchInfo->hasField($div_opt))
//                $report_query_options['conditions'][$div_opt] = $admin_divs;
            }

            $admin_dists = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Dists");
            if (!empty($admin_opts[1]) && !empty($admin_dists)) {
                $dist_opt = $admin_opts[1];

                if ($this->$base_model->hasField($dist_opt))
                    $report_query_options['conditions']["$base_model.$dist_opt"] = $admin_dists;
                else if ($this->$base_model->BasicModuleBranchInfo->hasField($dist_opt))
                    $report_query_options['conditions']["BasicModuleBranchInfo.$dist_opt"] = $admin_dists;

                //debug($report_query_options);
//            if ($this->$base_model->hasField($dist_opt) || $this->$base_model->BasicModuleBranchInfo->hasField($dist_opt))
//                $report_query_options['conditions'][$dist_opt] = $admin_dists;
            }

            $admin_upzas = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Upzas");
            if (!empty($admin_opts[2]) && !empty($admin_upzas)) {
                $upza_opt = $admin_opts[2];

                if ($this->$base_model->hasField($upza_opt))
                    $report_query_options['conditions']["$base_model.$upza_opt"] = $admin_upzas;
                else if ($this->$base_model->BasicModuleBranchInfo->hasField($upza_opt))
                    $report_query_options['conditions']["BasicModuleBranchInfo.$upza_opt"] = $admin_upzas;

//            if ($this->$base_model->hasField($upza_opt) || $this->$base_model->BasicModuleBranchInfo->hasField($upza_opt))
//                $report_query_options['conditions'][$upza_opt] = $admin_upzas;
            }

            $admin_unions = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Unions");
            if (!empty($admin_opts[3]) && !empty($admin_unions)) {
                $union_opt = $admin_opts[3];

                if ($this->$base_model->hasField($union_opt))
                    $report_query_options['conditions']["$base_model.$union_opt"] = $admin_unions;
                else if ($this->$base_model->BasicModuleBranchInfo->hasField($union_opt))
                    $report_query_options['conditions']["BasicModuleBranchInfo.$union_opt"] = $admin_unions;

//            if ($this->$base_model->hasField($union_opt) || $this->$base_model->BasicModuleBranchInfo->hasField($union_opt))
//                $report_query_options['conditions'][$union_opt] = $admin_unions;
            }
        } catch (Exception $ex) {
            return $report_query_options;
            //debug($ex);
        }

//        debug($admin_dists);
//        debug($report_query_options);
        return $report_query_options;
    }

    function GetReportFixedFields($base_model = null, $base_opts = null, $org_opts = null, $admin_opts = null) {

        if (empty($base_opts))
            $base_opts = array('period_id', 'from_date', 'to_date');
        if (empty($org_opts))
            $org_opts = array('org_id', 'branch_id');
        if (empty($admin_opts))
            $admin_opts = array('division_id', 'district_id', 'upazila_id', 'union_id');


        $rpt_all_opts = $this->Session->read("ReportModuleReportViewer.FilterReportOptions");

        $fixed_field_condition = array();
        $fixed_field_list = array();
        if (!empty($rpt_all_opts['FilterFieldList'])) {
            $filter_field_list = $rpt_all_opts['FilterFieldList'];
            foreach ($filter_field_list as $filter_field) {
                if (empty($filter_field['field']) || empty($filter_field['operator']) || $filter_field['operator'] != '=' || !isset($filter_field['value']) || trim($filter_field['value']) == '')
                    continue;

                $field_opts = explode('.', $filter_field['field']);
                $fixed_field_list[] = $field_opts[count($field_opts) - 1];
                $fixed_field_condition[$filter_field['field']] = $filter_field['value'];
            }
        }

        if (empty($base_model) || empty($rpt_all_opts['BasicOpt']))
            return array('field_list' => $fixed_field_list, 'fixed_field_condition' => $fixed_field_condition);


        try {
            $filter_basic_opts = $rpt_all_opts['BasicOpt'];

            if (!empty($base_opts[0]) && !empty($filter_basic_opts["period_id"])) {
                $period_id_opt = $base_opts[0];
                if ($this->$base_model->hasField($period_id_opt)) {
                    $fixed_field_list[] = $period_id_opt;
                    $fixed_field_condition["$base_model.$period_id_opt"] = $filter_basic_opts["period_id"];
                } else if ($this->$base_model->AdminModulePeriodList->hasField($period_id_opt)) {
                    $fixed_field_list[] = "id";
                    $fixed_field_condition["AdminModulePeriodList.id"] = $filter_basic_opts["period_id"];
                }
            }

            if (!empty($org_opts[0]) && !empty($filter_basic_opts['org_id'])) {
                $org_opt = $org_opts[0];
                if ($this->$base_model->hasField($org_opt)) {
                    $fixed_field_list[] = $org_opt;
                    $fixed_field_condition["$base_model.$org_opt"] = $filter_basic_opts['org_id'];
                }
            }

            if (!empty($org_opts[1]) && !empty($filter_basic_opts['branches_id'])) {
                $org_opt = $org_opts[1];
                if ($this->$base_model->hasField($org_opt)) {
                    $fixed_field_list[] = $org_opt;
                    $fixed_field_condition["$base_model.$org_opt"] = $filter_basic_opts['branches_id'];
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($org_opt)) {
                    $fixed_field_list[] = $org_opt;
                    $fixed_field_condition["BasicModuleBranchInfo.$org_opt"] = $filter_basic_opts['branches_id'];
                }
            }

            $admin_divs = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Divs");
            if (!empty($admin_opts[0]) && !empty($admin_divs) && count($admin_divs) == 1) {
                $div_opt = $admin_opts[0];
                if ($this->$base_model->hasField($div_opt)) {
                    $fixed_field_list[] = $div_opt;
                    $fixed_field_condition["$base_model.$div_opt"] = $admin_divs;
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($div_opt)) {
                    $fixed_field_list[] = $div_opt;
                    $fixed_field_condition["BasicModuleBranchInfo.$div_opt"] = $admin_divs;
                }
            }

            $admin_dists = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Dists");
            if (!empty($admin_opts[1]) && !empty($admin_dists) && count($admin_dists) == 1) {
                $dist_opt = $admin_opts[1];
                if ($this->$base_model->hasField($dist_opt)) {
                    $fixed_field_list[] = $dist_opt;
                    $fixed_field_condition["$base_model.$dist_opt"] = $admin_dists;
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($dist_opt)) {
                    $fixed_field_list[] = $dist_opt;
                    $fixed_field_condition["BasicModuleBranchInfo.$dist_opt"] = $admin_dists;
                }
            }

            $admin_upzas = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Upzas");
            if (!empty($admin_opts[2]) && !empty($admin_upzas) && count($admin_upzas) == 1) {
                $upza_opt = $admin_opts[2];
                if ($this->$base_model->hasField($upza_opt)) {
                    $fixed_field_list[] = $upza_opt;
                    $fixed_field_condition["$base_model.$union_opt"] = $admin_unions;
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($upza_opt)) {
                    $fixed_field_list[] = $upza_opt;
                    $fixed_field_condition["BasicModuleBranchInfo.$union_opt"] = $admin_unions;
                }
            }

            $admin_unions = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Unions");
            if (!empty($admin_opts[3]) && !empty($admin_unions) && count($admin_unions) == 1) {
                $union_opt = $admin_opts[3];
                if ($this->$base_model->hasField($union_opt)) {
                    $fixed_field_list[] = $union_opt;
                    $fixed_field_condition["$base_model.$union_opt"] = $admin_unions;
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($union_opt)) {
                    $fixed_field_list[] = $union_opt;
                    $fixed_field_condition["BasicModuleBranchInfo.$union_opt"] = $admin_unions;
                }
            }
        } catch (Exception $ex) {
            return array('field_list' => $fixed_field_list, 'fixed_field_condition' => $fixed_field_condition);
        }

        return array('field_list' => $fixed_field_list, 'fixed_field_condition' => $fixed_field_condition);
    }

    function GetReportFixedHeadersP($base_model = null, $base_opts = null, $org_opts = null, $admin_opts = null) {

        if (empty($base_opts))
            $base_opts = array('period_id', 'from_date', 'to_date');
        if (empty($org_opts))
            $org_opts = array('org_id', 'branch_id');
        if (empty($admin_opts))
            $admin_opts = array('division_id', 'district_id', 'upazila_id', 'union_id');


        $rpt_all_opts = $this->Session->read("ReportModuleReportViewer.FilterReportOptions");

        $fixed_column_cond = array();
        $fixed_column_list = array();
        if (!empty($rpt_all_opts['FilterFieldList'])) {
            $filter_field_list = $rpt_all_opts['FilterFieldList'];
            foreach ($filter_field_list as $filter_field) {
                if (empty($filter_field['field']) || empty($filter_field['operator']) || $filter_field['operator'] != '=' || !isset($filter_field['value']) || trim($filter_field['value']) == '')
                    continue;

                $fixed_column_list[] = $filter_field['field'];
                $fixed_column_cond[$filter_field['field']] = $filter_field['value'];
            }
        }

        if (empty($base_model) || empty($rpt_all_opts['BasicOpt']))
            return array('column_list' => $fixed_column_list, 'column_condition' => $fixed_column_cond);


        try {
            $filter_basic_opts = $rpt_all_opts['BasicOpt'];

            if (!empty($base_opts[0]) && !empty($filter_basic_opts["period_id"])) {
                $period_id_opt = $base_opts[0];
                if ($this->$base_model->hasField($period_id_opt)) {
                    $fixed_column_list[] = "$base_model.$period_id_opt";
                    $fixed_column_cond["$base_model.$period_id_opt"] = $filter_basic_opts["period_id"];
                } else if ($this->$base_model->AdminModulePeriodList->hasField($period_id_opt)) {
                    $fixed_column_list[] = "AdminModulePeriodList.id";
                    $fixed_column_cond["AdminModulePeriodList.id"] = $filter_basic_opts["period_id"];
                }
            }

            if (!empty($org_opts[0]) && !empty($filter_basic_opts['org_id'])) {
                $org_opt = $org_opts[0];
                if ($this->$base_model->hasField($org_opt)) {
                    $fixed_column_list[] = "$base_model.$org_opt";
                    $fixed_column_cond["$base_model.$org_opt"] = $filter_basic_opts['org_id'];
                }
            }

            if (!empty($org_opts[1]) && !empty($filter_basic_opts['branches_id'])) {
                $org_opt = $org_opts[1];
                if ($this->$base_model->hasField($org_opt)) {
                    $fixed_column_list[] = "$base_model.$org_opt";
                    $fixed_column_cond["$base_model.$org_opt"] = $filter_basic_opts['branches_id'];
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($org_opt)) {
                    $fixed_column_list[] = "BasicModuleBranchInfo.$org_opt";
                    $fixed_column_cond["BasicModuleBranchInfo.$org_opt"] = $filter_basic_opts['branches_id'];
                }
            }


            $admin_divs = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Divs");
            if (!empty($admin_opts[0]) && !empty($admin_divs)) {
                $div_opt = $admin_opts[0];
                if ($this->$base_model->hasField($div_opt)) {
                    $fixed_column_list[] = "$base_model.$div_opt";
                    $fixed_column_cond["$base_model.$div_opt"] = $admin_divs;
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($div_opt)) {
                    $fixed_column_list[] = "BasicModuleBranchInfo.$div_opt";
                    $fixed_column_cond["BasicModuleBranchInfo.$div_opt"] = $admin_divs;
                }
            }

            $admin_dists = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Dists");
            if (!empty($admin_opts[1]) && !empty($admin_dists)) {
                $dist_opt = $admin_opts[1];
                if ($this->$base_model->hasField($dist_opt)) {
                    $fixed_column_list[] = "$base_model.$dist_opt";
                    $fixed_column_cond["$base_model.$dist_opt"] = $admin_dists;
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($dist_opt)) {
                    $fixed_column_list[] = "BasicModuleBranchInfo.$dist_opt";
                    $fixed_column_cond["BasicModuleBranchInfo.$dist_opt"] = $admin_dists;
                }
            }

            $admin_upzas = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Upzas");
            if (!empty($admin_opts[2]) && !empty($admin_upzas)) {
                $upza_opt = $admin_opts[2];
                if ($this->$base_model->hasField($upza_opt)) {
                    $fixed_column_list[] = "$base_model.$upza_opt";
                    $fixed_column_cond["$base_model.$union_opt"] = $admin_unions;
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($upza_opt)) {
                    $fixed_column_list[] = "BasicModuleBranchInfo.$upza_opt";
                    $fixed_column_cond["BasicModuleBranchInfo.$union_opt"] = $admin_unions;
                }
            }

            $admin_unions = $this->Session->read("ReportModuleReportViewer.SelectedAdmin.Unions");
            if (!empty($admin_opts[3]) && !empty($admin_unions)) {
                $union_opt = $admin_opts[3];
                if ($this->$base_model->hasField($union_opt)) {
                    $fixed_column_list[] = "$base_model.$union_opt";
                    $fixed_column_cond["$base_model.$union_opt"] = $admin_unions;
                } else if ($this->$base_model->BasicModuleBranchInfo->hasField($union_opt)) {
                    $fixed_column_list[] = "BasicModuleBranchInfo.$union_opt";
                    $fixed_column_cond["BasicModuleBranchInfo.$union_opt"] = $admin_unions;
                }
            }
        } catch (Exception $ex) {
            return array('column_list' => $fixed_column_list, 'column_condition' => $fixed_column_cond);
        }

        return array('column_list' => $fixed_column_list, 'column_condition' => $fixed_column_cond);
    }

    function report_options_set($opt = false) {

        $this->autoRender = false;

//        debug($this->request->data);
//        return;

        $this->Session->write("ReportModuleReportViewer.FilterReportOptions", null);

        if (empty($opt))
            return;

        if (!empty($this->request->data['ReportModuleReportOptionsSet'])) {

            $report_options = $this->request->data['ReportModuleReportOptionsSet'];
            $this->Session->write("ReportModuleReportViewer.FilterReportOptions", $report_options);
        }

        return;
    }

    function report_filters_set($opt = false) {

        $this->autoRender = false;
//        debug($this->request->data);
//        return;

        if (empty($opt))
            return;

        $this->Session->write("ReportModuleReportViewer.ReportFilters", null);
        if (!empty($this->request->data)) {
            $this->Session->write("ReportModuleReportViewer.ReportFilters", $this->request->data);
        }

        return;
    }

    function selected_data_periods() {

        debug($this->request->data);
        return;

        if (!empty($this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'])) {

            $org_id = $this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'];

            if (!empty($org_id)) {
                $this->loadModel('BasicModuleBasicInformation');
                $org_name = $this->BasicModuleBasicInformation->field('name_of_org', array('id' => $org_id));

                $condition_admin = $this->GetAdminConditions(null, 'BasicModuleBranchInfo.district_id', 'BasicModuleBranchInfo.upazila_id', 'BasicModuleBranchInfo.union_id');

                if (!empty($condition_admin))
                    $condition = array_merge(array('BasicModuleBranchInfo.org_id' => $org_id), $condition_admin);
                else
                    $condition = array('BasicModuleBranchInfo.org_id' => $org_id);

                try {
                    $this->loadModel('BasicModuleBranchInfo');
                    $branch_list = $this->BasicModuleBranchInfo->find('list', array('fields' => array('id', 'branch_address'), 'conditions' => $condition, 'recursive' => 1, 'order' => 'id'));
                } catch (Exception $ex) {
                    //debug($ex->getMessage());
                }
            }
        }

        $this->set(compact('org_id', 'org_name', 'branch_list'));
        return;
    }

    public function report_viewer() {

        //model_ids=70^95&=title_ids=1_2
        $model_ids = $this->request->query('model_ids');
        if (!empty($model_ids))
            $this->Session->write('Current.ModelIds', $model_ids);
        else
            $model_ids = $this->Session->read('Current.ModelIds');

        $condition = array();
        if (!empty($model_ids)) {

            if (strpos($model_ids, '^') > 0) {
                $modelIds = explode('^', $model_ids);
                if (count($modelIds) < 2) {
                    $condition = array('LookupModelDefinition.id' => $modelIds[0]);
                } else {
                    $condition = array('LookupModelDefinition.id >=' => $modelIds[0], 'LookupModelDefinition.id <=' => $modelIds[1]);
                }
            } else if (strpos($model_ids, '-') > 0) {
                $modelIds = explode('-', $model_ids);
                $condition = array('LookupModelDefinition.id' => $modelIds);
            }
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid report model information !'
            );
            $this->set(compact('msg'));
            return;
        }

        //title_ids=1_2        
        $title_ids = $this->request->query('title_ids');
        if (!empty($title_ids))
            $this->Session->write('Current.TitleIds', $title_ids);
        else
            $title_ids = $this->Session->read('Current.TitleIds');

        $report_title = 'MRA Reports';
        if (!empty($title_ids)) {
            if (strpos($title_ids, '_') > 0) {
                $titleIds = explode('_', $title_ids);
                if (count($titleIds) > 1) {
                    $this->loadModel('AdminModuleSubMenu');
                    $report_title = $this->AdminModuleSubMenu->field('sub_menu_title', array('module_id' => 9, 'menu_id' => $titleIds[0], 'sub_menu_id' => $titleIds[1]));
                }
            }
        }

//        $this->loadModel('LookupModelFieldDefinition');
//        $model_list = $this->LookupModelFieldDefinition->LookupModelDefinition->find('list', array('fields' => array('id', 'model_description'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'model_name', 'order' => 'id'));

        $this->loadModel('LookupModelDefinition');
        $model_list = $this->LookupModelDefinition->find('list', array('fields' => array('id', 'model_description'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'model_name', 'order' => 'id'));

        $this->set(compact('model_list', 'report_title'));
    }

    function report_viewer_selected_fields() {

//        debug($this->request->data['ReportModuleReportViewerReportSelect']);
//        return;

        $this->Session->write("ReportModuleReportViewer.FilterReportOptions", null);
        $this->Session->write("ReportModuleReportViewer.ReportFilters", null);
        $this->Session->write("ReportModuleReportViewer.SelectedOrg.Ids", null);
        $this->Session->write("ReportModuleReportViewer.SelectedOrg.BranchIds", null);

        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Divs", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Upzas", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Unions", null);

        try {
            $this->loadModel('LookupAdminBoundaryDivision');
            $div_list = $this->LookupAdminBoundaryDivision->find('list', array('fields' => array('id', 'division_name'), 'recursive' => -1, 'order' => 'id'));

            $this->loadModel('LookupAdminBoundaryDistrict');
            $dist_list = $this->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'district_name'), 'recursive' => -1, 'order' => 'id'));

            $this->set(compact('div_list', 'dist_list'));
        } catch (Exception $ex) {
            //debug($ex);
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => $ex->getMessage()
            );
            $this->set(compact('msg'));
        }


        $report_list = null;
        $report_fields_list = null;

        if (!empty($this->request->data['ReportModuleReportViewerReportSelect']['ModelList'])) {

            $this->loadModel('LookupModelFieldDefinition');
            $this->loadModel('ReportModuleReportFieldDefinition');

            $model_id = $this->request->data['ReportModuleReportViewerReportSelect']['ModelList'];
            $model_details = $this->LookupModelFieldDefinition->LookupModelDefinition->find('first', array('fields' => array('id', 'model_name', 'model_description', 'cat_group_id'), 'conditions' => array('LookupModelDefinition.id' => $model_id), 'recursive' => -1));

            $model_name = $model_details['LookupModelDefinition']['model_name'];
            $cat_group_id = $model_details['LookupModelDefinition']['cat_group_id'] != null ?
                    $model_details['LookupModelDefinition']['cat_group_id'] : -1;

            $report_list = array($model_details['LookupModelDefinition']['id'] => $model_details['LookupModelDefinition']['model_description']);


            $this->LookupModelFieldDefinition->virtualFields['query_field_name'] = "CASE WHEN field_name_for_report IS NOT NULL AND TRIM(field_name_for_report) <> '' THEN CASE WHEN field_model_for_report IS NOT NULL AND TRIM(field_model_for_report) <> '' THEN CONCAT_WS('.', field_model_for_report, field_name_for_report) ELSE CONCAT_WS('.', '$model_name', field_name_for_report) END ELSE NULL END";

            $condition = array('LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_report' => 1);
            $report_fields_list = $this->LookupModelFieldDefinition->find('list', array('fields' => array('field_id', 'field_description'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));

//            debug($report_fields_list);
//            exit;
            $order_by_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('query_field_name', 'field_description'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));

            $condition = array('LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_report' => 1,
                'LookupModelFieldDefinition.field_group_id <>' => array(0, $cat_group_id));

            $filter_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('query_field_name', 'field_description'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));


            $this->loadModel($model_name);

            $condition = array('LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_report' => 1,
                'LookupModelFieldDefinition.field_group_id' => array(0, $cat_group_id));
            $group_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('field_name', 'field_description'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));

            $group_by_fields = array();
            foreach ($group_fields as $field_name => $field_description) {
                if ($this->$model_name->hasField($field_name))
                    $group_by_fields["$model_name.$field_name"] = $field_description;
                else if ($this->$model_name->BasicModuleBranchInfo->hasField($field_name))
                    $group_by_fields["BasicModuleBranchInfo.$field_name"] = $field_description;;
            }

            $group_wise_period_list = null;
            if ($this->$model_name->hasField('period_id')) {
                $period_ids = $this->$model_name->find('list', array('fields' => array('period_id', 'period_id'), 'recursive' => -1, 'group' => 'period_id', 'order' => 'period_id'));

                $this->loadModel('AdminModulePeriodList');
                $group_wise_period_list = $this->AdminModulePeriodList->find('list', array('fields' => array('AdminModulePeriodList.id', 'AdminModulePeriodList.period', 'AdminModulePeriodType.period_types'), 'conditions' => array('AdminModulePeriodList.id' => $period_ids), 'recursive' => 0, 'group' => 'type_id, id', 'order' => 'type_id'));
            }

            $org_list = null;
            try {
                $fields = array('id', 'name_of_org');
                if ($model_name == "BasicModuleBasicInformation" && $this->$model_name->hasField('name_of_org', true)) {
                    $org_list = $this->$model_name->find('list', array('fields' => $fields, 'recursive' => -1, 'group' => array('id')));
                } else if ($this->$model_name->hasField('org_id') && $this->$model_name->BasicModuleBasicInformation->isVirtualField('name_of_org')) {
                    $org_list = $this->$model_name->BasicModuleBasicInformation->find('list', array('fields' => $fields, 'recursive' => -1, 'group' => array('id')));
                }
            } catch (Exception $ex) {
                //debug($ex);
            }

            $this->set(compact('model_id', 'model_name', 'org_list', 'group_wise_period_list', 'report_list', 'report_fields_list', 'cat_group_id'));


            $condition = array('LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_report' => 1,
                'LookupModelFieldDefinition.field_group_id' => $cat_group_id);
            //    'LookupModelFieldDefinition.field_group_id' => array(0, $cat_group_id));

            $fields = array('id', 'model_id', 'child_model_id', 'parent_model_id', 'parent_field_id',
                'field_name', 'field_description', 'control_type_for_add', 'field_title_to_view_in_crud',
                'containable_model_names', 'dropdown_display_field', 'dropdown_value_field', 'dropdown_condition_field',
                'model_name_for_select_option', 'model_name_for_dependent_select_option', 'dependent_dropdown_display_field',
                'dependent_dropdown_condition_field', 'dependent_dropdown_value_field', 'parent_or_child_control_id');

            $cat_fields_list = array();

            $cat_fields_details = $this->LookupModelFieldDefinition->find('all', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 1, 'order' => array('field_sorting_order' => 'asc')));
            if (!empty($cat_fields_details)) {

                $org_id = $branch_id = $from_date = $to_date = '';

                $cat_field_detail = array();

                foreach ($cat_fields_details as $field_details) {
                    $field_details = $field_details['LookupModelFieldDefinition'];

                    $field_id = $field_details['id'];

                    $cat_field_detail['field_name'] = $field_name = $field_details['field_name'];
                    $cat_field_detail['field_label'] = $field_details['field_title_to_view_in_crud'];
                    $cat_field_detail['control_type'] = $control_type = $field_details['control_type_for_add'];

                    $cat_field_detail['child_model_id'] = $field_details['child_model_id'];
                    $cat_field_detail['parent_control_id'] = $field_details['dependent_dropdown_condition_field'];
                    $cat_field_detail['parent_or_child_control_id'] = $field_details['parent_or_child_control_id'];

                    //$group_by_fields["$model_name.$field_name"] = $cat_field_detail['field_label'];
                    $options = array();
                    if ($control_type == "select" || $control_type == "select_or_label" || $control_type == "dependent_dropdown" || $control_type == "radio" || $control_type == "checkbox") {
                        $select_option_model = $field_details['model_name_for_select_option'];
                        $dropdown_display_field = $field_details['dropdown_display_field'];
                        $dropdown_value_field = $field_details['dropdown_value_field'];
                        $dropdown_condition_field = $field_details['dropdown_condition_field'];

                        $containable_model_names = $field_details['containable_model_names'];
                        $this->loadModel($select_option_model);
                        $fields = array("$select_option_model.$dropdown_value_field", "$select_option_model.$dropdown_display_field");
                        $containable_model_name_list = explode(',', $containable_model_names);

                        $order_by = $fields[0];
                        $value_exist_conditions = array();

                        if (!empty($select_option_model) && !empty($fields)) {
                            if ($control_type == "dependent_dropdown") {
                                if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by));
                                } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                    $dependent_dropdown_condition_field = $field_details['dependent_dropdown_condition_field'];
                                    $fields[2] = "$select_option_model.$dependent_dropdown_condition_field";
                                    try {
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'contain' => $containable_model_name_list, 'recursive' => -1, 'order' => $order_by));
                                    } catch (Exception $ex) {
                                        $options = array();
                                    }
                                } elseif (!empty($dropdown_condition_field) && !empty($containable_model_name_list) && !empty($org_id)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'contain' => $containable_model_name_list, 'recursive' => -1, 'order' => $order_by));
                                } else {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by));
                                }
                            }

                            if ($control_type != "dependent_dropdown") {
                                if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by));
                                } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by, 'contain' => $containable_model_name_list));
                                } elseif (!empty($dropdown_condition_field) && !empty($containable_model_name_list) && !empty($org_id)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by, 'contain' => $containable_model_name_list));
                                } else {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by));
                                }
                            }
                        }
                    }

                    $cat_field_detail['options'] = $options;

                    $cat_fields_list[$field_id] = $cat_field_detail;
                }
            }

            $this->set(compact('cat_fields_list', 'filter_fields', 'group_by_fields', 'order_by_fields'));
        }
    }

    function report_viewer_report() {

//        debug($this->request->data);
//        return;

        $all_req_data = $this->request->data;

        if (empty($all_req_data['ReportFieldList'])) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Report fields are not selected !'
            );
            $this->set(compact('msg'));

            return;
        }

        $selected_fields_details = $all_req_data['ReportFieldList'];
        unset($all_req_data['ReportFieldList']);

//        $all_req_data['GroupByFieldList'];

        foreach ($selected_fields_details as $model_id => $selected_fields_id) {

            if (empty($model_id) || empty($selected_fields_id))
                return;


            $this->loadModel('LookupModelFieldDefinition');

            $model_fields = array('model_name', 'model_description', 'is_total_show');
            //'unbind_models_for_view', 'cat_group_id', 
            $model_details = $this->LookupModelFieldDefinition->LookupModelDefinition->findById($model_id, $model_fields);
//            debug($model_details);

            if (empty($model_details) || empty($model_details['LookupModelDefinition']['model_name']))
                return;

            try {
//                $model_name = $this->LookupModelFieldDefinition->LookupModelDefinition->field('model_name', array('id' => $model_id));
//                $model_description = $this->LookupModelFieldDefinition->LookupModelDefinition->field('model_description', array('id' => $model_id));

                $model_name = $model_details['LookupModelDefinition']['model_name'];
                $model_description = $model_details['LookupModelDefinition']['model_description'];
                $is_total_show = !empty($model_details['LookupModelDefinition']['is_total_show']);

                //$unbind_models_for_view = $model_details['LookupModelDefinition']['unbind_models_for_view'];

                $this->loadModel($model_name);

                $asso_models_list = $this->$model_name->getAssociated();

                $condition = array('LookupModelFieldDefinition.field_id' => $selected_fields_id);

                $field_details_list = $this->LookupModelFieldDefinition->find('all', array('fields' => array('field_id', 'field_name_for_report', 'field_model_for_report', 'field_type_for_report', 'field_group_id', 'field_title_for_report'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));
                //$field_details_list = $this->LookupModelFieldDefinition->find('all', array('fields' => array('field_id', 'field_name_for_report', 'field_model_for_report', 'field_group_id', 'field_sub_group_id', 'field_title_for_report', 'field_sub_title_for_report'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));
//                debug($field_details_list);
//                debug($all_req_data['GroupByFieldList']['fields']);
//                debug(empty($all_req_data['GroupByFieldList']['fields']));
//                $selected_field_types = Hash::extract($field_details_list, '{n}.LookupModelFieldDefinition.field_id', '{n}.LookupModelFieldDefinition.field_type_for_report');
//                debug($selected_field_types);


                $recursive_level = 0;
                $cfc = 0;
                $selected_field_list = array();
                $selected_field_types = array();
                $used_asso_models_list = array();

                if (empty($all_req_data['GroupByFieldList']['fields'])) {
                    foreach ($field_details_list as $field_id => $field_details) {

                        if (empty($field_details['LookupModelFieldDefinition']))
                            continue;
                        $field_details = $field_details['LookupModelFieldDefinition'];
                        if (empty($field_details['field_id']) || empty($field_details['field_name_for_report']))
                            continue;

                        $field_id = $field_details['field_id'];
                        $field_type = trim($field_details['field_type_for_report']);

                        $field_name = $field_details['field_name_for_report'];
                        $field_model_name = empty($field_details['field_model_for_report']) ? $model_name : $field_details['field_model_for_report'];

                        $selected_field_types[$field_id] = $field_type;
                        if (strpos($field_model_name, '.') !== false) {
                            $field_models_name = explode('.', $field_model_name);
                            $field_parent_model_name = $field_models_name[0];
                            $field_model_name = $field_models_name[1];

//                        debug($field_parent_model_name);

                            if ($model_name == $field_parent_model_name) {
                                //if (empty($asso_models_list) || !in_array($field_parent_model_name, $asso_models_list))
                                if (!isset($asso_models_list[$field_model_name]))
                                    continue;

                                if (!$this->$model_name->$field_model_name->hasField($field_name) && !$this->$model_name->$field_model_name->isVirtualField($field_name))
                                    continue;

                                $used_asso_models_list[] = $field_model_name;

                                if ($this->$model_name->$field_model_name->isVirtualField($field_name)) {
                                    $this->$model_name->virtualFields[$field_name] = $this->$model_name->$field_model_name->virtualFields[$field_name];
                                    $selected_field_list[$field_id] = "$model_name.$field_name";
                                } else {
                                    $selected_field_list[$field_id] = "$field_model_name.$field_name";
                                }
                                continue;
                            }

                            if ($model_name != $field_model_name) {
                                if (!isset($asso_models_list[$field_model_name]))
                                    continue;

                                if (!$this->$model_name->$field_parent_model_name->$field_model_name->hasField($field_name) && !$this->$model_name->$field_parent_model_name->$field_model_name->isVirtualField($field_name))
                                    continue;

                                $used_asso_models_list[] = $field_parent_model_name;
                                $used_asso_models_list[] = $field_model_name;

                                $recursive_level = 1;
                                if ($this->$model_name->$field_parent_model_name->$field_model_name->isVirtualField($field_name)) {
                                    $this->$model_name->virtualFields[$field_name] = $this->$model_name->$field_parent_model_name->$field_model_name->virtualFields[$field_name];
                                    $selected_field_list[$field_id] = "$model_name.$field_name";
                                } else {
                                    $selected_field_list[$field_id] = "$field_model_name.$field_name";
                                }
                                continue;
                            }
                        }

                        if ($model_name == $field_model_name) {
                            $used_asso_models_list[] = $field_model_name;
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
//                    debug($asso_models_list);

                        if (!isset($asso_models_list[$field_model_name]))
                            continue;

                        if (!$this->$model_name->$field_model_name->hasField($field_name) && !$this->$model_name->$field_model_name->isVirtualField($field_name))
                            continue;

                        $used_asso_models_list[] = $field_model_name;
                        if ($this->$model_name->$field_model_name->isVirtualField($field_name)) {
                            $this->$model_name->virtualFields[$field_name] = $this->$model_name->$field_model_name->virtualFields[$field_name];
                            $selected_field_list[$field_id] = "$model_name.$field_name";
                        } else {
                            $selected_field_list[$field_id] = "$field_model_name.$field_name";
                        }
                    }
                } else {
                    //For DROUP BY AND SUM
                    $aggre_opetator = 'SUM';
                    foreach ($field_details_list as $field_id => $field_details) {

                        if (empty($field_details['LookupModelFieldDefinition']))
                            continue;
                        $field_details = $field_details['LookupModelFieldDefinition'];
                        if (empty($field_details['field_id']) || empty($field_details['field_name_for_report']))
                            continue;

                        $field_id = $field_details['field_id'];
                        $field_type = trim($field_details['field_type_for_report']);

                        $field_name = $field_details['field_name_for_report'];
                        $field_model_name = empty($field_details['field_model_for_report']) ? $model_name : $field_details['field_model_for_report'];

                        $aggre_field_name = '';
                        $selected_field_types[$field_id] = $field_type;
                        if (!empty($field_type) && ($field_type == 'int' || $field_type == 'double'))
                            $aggre_field_name = strtolower($aggre_opetator) . "all_$field_name";

                        if (strpos($field_model_name, '.') !== false) {
                            $field_models_name = explode('.', $field_model_name);
                            $field_parent_model_name = $field_models_name[0];
                            $field_model_name = $field_models_name[1];

                            if ($model_name == $field_parent_model_name) {
                                //if (empty($asso_models_list) || !in_array($field_parent_model_name, $asso_models_list))
                                if (!isset($asso_models_list[$field_model_name]))
                                    continue;

                                if (!$this->$model_name->$field_model_name->hasField($field_name) && !$this->$model_name->$field_model_name->isVirtualField($field_name))
                                    continue;

                                $used_asso_models_list[] = $field_model_name;

                                if ($this->$model_name->$field_model_name->isVirtualField($field_name)) {
                                    $this->$model_name->virtualFields[$field_name] = $this->$model_name->$field_model_name->virtualFields[$field_name];
                                    if (empty($aggre_field_name)) {
                                        $selected_field_list[$field_id] = "$model_name.$field_name";
                                    } else {
                                        $this->$model_name->virtualFields[$aggre_field_name] = "$aggre_opetator($model_name.$field_name)";
                                        $selected_field_list[$field_id] = "$model_name.$aggre_field_name";
                                    }
                                } else {
                                    if (empty($aggre_field_name)) {
                                        $selected_field_list[$field_id] = "$field_model_name.$field_name";
                                    } else {
                                        $this->$model_name->virtualFields[$aggre_field_name] = "$aggre_opetator($field_model_name.$field_name)";
                                        $selected_field_list[$field_id] = "$model_name.$aggre_field_name";
                                    }
                                }
                                continue;
                            }

                            if ($model_name != $field_model_name) {
                                if (!isset($asso_models_list[$field_model_name]))
                                    continue;

                                if (!$this->$model_name->$field_parent_model_name->$field_model_name->hasField($field_name) && !$this->$model_name->$field_parent_model_name->$field_model_name->isVirtualField($field_name))
                                    continue;

                                $used_asso_models_list[] = $field_parent_model_name;
                                $used_asso_models_list[] = $field_model_name;

                                $recursive_level = 1;
                                if ($this->$model_name->$field_parent_model_name->$field_model_name->isVirtualField($field_name)) {
                                    $this->$model_name->virtualFields[$field_name] = $this->$model_name->$field_parent_model_name->$field_model_name->virtualFields[$field_name];
                                    $selected_field_list[$field_id] = "$model_name.$field_name";
                                    if (empty($aggre_field_name)) {
                                        $selected_field_list[$field_id] = "$model_name.$field_name";
                                    } else {
                                        $this->$model_name->virtualFields[$aggre_field_name] = "$aggre_opetator($model_name.$field_name)";
                                        $selected_field_list[$field_id] = "$model_name.$aggre_field_name";
                                    }
                                } else {
                                    if (empty($aggre_field_name)) {
                                        $selected_field_list[$field_id] = "$field_model_name.$field_name";
                                    } else {
                                        $this->$model_name->virtualFields[$aggre_field_name] = "$aggre_opetator($field_model_name.$field_name)";
                                        $selected_field_list[$field_id] = "$model_name.$aggre_field_name";
                                    }
                                }
                                continue;
                            }
                        }

                        if ($model_name == $field_model_name) {
                            $used_asso_models_list[] = $field_model_name;
                            if ($this->$model_name->hasField($field_name) || $this->$model_name->isVirtualField($field_name)) {
                                if (empty($aggre_field_name)) {
                                    $selected_field_list[$field_id] = "$field_model_name.$field_name";
                                } else {
                                    $this->$model_name->virtualFields[$aggre_field_name] = "$aggre_opetator($field_model_name.$field_name)";
                                    $selected_field_list[$field_id] = "$model_name.$aggre_field_name";
                                }
                            } else {
                                ++$cfc;
                                $virtual_field_name = "vf_$cfc";
                                $this->$model_name->virtualFields[$virtual_field_name] = "$field_name";
                                if (empty($aggre_field_name)) {
                                    $selected_field_list[$field_id] = "$model_name.$virtual_field_name";
                                } else {
                                    $this->$model_name->virtualFields[$aggre_field_name] = "$aggre_opetator($model_name.$virtual_field_name)";
                                    $selected_field_list[$field_id] = "$model_name.$aggre_field_name";
                                }
                            }
                            continue;
                        }
//                    debug($asso_models_list);

                        if (!isset($asso_models_list[$field_model_name]))
                            continue;

                        if (!$this->$model_name->$field_model_name->hasField($field_name) && !$this->$model_name->$field_model_name->isVirtualField($field_name))
                            continue;

                        $used_asso_models_list[] = $field_model_name;
                        if ($this->$model_name->$field_model_name->isVirtualField($field_name)) {
                            $this->$model_name->virtualFields[$field_name] = $this->$model_name->$field_model_name->virtualFields[$field_name];
                            if (empty($aggre_field_name)) {
                                $selected_field_list[$field_id] = "$model_name.$field_name";
                            } else {
                                $this->$model_name->virtualFields[$aggre_field_name] = "$aggre_opetator($model_name.$field_name)";
                                $selected_field_list[$field_id] = "$model_name.$aggre_field_name";
                            }
                        } else {
                            if (empty($aggre_field_name)) {
                                $selected_field_list[$field_id] = "$field_model_name.$field_name";
                            } else {
                                $this->$model_name->virtualFields[$aggre_field_name] = "$aggre_opetator($field_model_name.$field_name)";
                                $selected_field_list[$field_id] = "$model_name.$aggre_field_name";
                            }
                        }
                    }
                }

//                debug($asso_models_list);
//                debug($used_asso_models_list);
                if (!empty($asso_models_list)) {

                    if (!empty($used_asso_models_list)) {
                        foreach ($used_asso_models_list as $used_asso_model) {
                            if (empty($asso_models_list[$used_asso_model]))
                                continue;
                            try {
                                unset($asso_models_list[$used_asso_model]);
                            } catch (Exception $ex) {
                                
                            }
                        }
                    }

                    $unbind_models_list = array();
                    foreach ($asso_models_list as $asso_model_name => $asso_type) {
                        $unbind_models_list[$asso_type][] = $asso_model_name;
                    }

//                    if (!empty($unbind_models_list))
//                        $this->$model_name->unbindModel($unbind_models_list, true);
//                    debug($asso_models_list);
//                    debug($unbind_models_list);
                }

//                $unbind_models_list = array();
//                if (!empty($model_details['LookupModelDefinition']['unbind_models_for_view'])) {
//                    $unbind_models_details = explode(";", $model_details['LookupModelDefinition']['unbind_models_for_view']);
//                    foreach ($unbind_models_details as $unbind_model_detail) {
//                        $unbind_model_detail = explode("=>", $unbind_model_detail);
//                        if (!empty($unbind_model_detail[0]) && !empty($unbind_model_detail[1]))
//                            $unbind_models_list[$unbind_model_detail[0]] = explode(",", $unbind_model_detail[1]);
//                    }
//                }
//                $this->$model_name->unbindModel($unbind_models_list, true);


                $this->Session->write("ReportModuleReportViewer.FilterReportOptions", $all_req_data);

                $fixed_field_details = $this->GetReportFixedFields($model_name);
                //debug($fixed_field_details);

                if (!empty($fixed_field_details) && !empty($fixed_field_details['field_list'])) {
                    try {
                        $fixed_fields = $fixed_field_details['field_list'];
                        $fixed_field_condition = $fixed_field_details['fixed_field_condition'];
                        $fixed_field_ids = $this->LookupModelFieldDefinition->find('list', array('fields' => array('id', 'field_id'), 'conditions' => array('model_id' => $model_id, 'field_name' => $fixed_fields), 'recursive' => -1, 'group' => 'id', 'order' => 'id'));

                        $fixed_field_list = array();
                        foreach ($fixed_field_ids as $fixed_field_id) {
                            if (!isset($selected_field_list[$fixed_field_id]))
                                continue;

                            $fixed_field_list[$fixed_field_id] = $selected_field_list[$fixed_field_id];
                            unset($selected_field_list[$fixed_field_id]);
                        }
                        $fixed_field_data = $this->$model_name->find('first', array('fields' => $fixed_field_list, 'conditions' => $fixed_field_condition));
                        $fixed_field_details = array('fixed_field_list' => $fixed_field_list, 'fixed_field_data' => $fixed_field_data);
                    } catch (Exception $ex) {
                        $fixed_field_details = null;
//                        debug($ex);
                    }
                } else {
                    $fixed_field_details = null;
                }


                $group_wise_field_title = Hash::combine($field_details_list, '{n}.LookupModelFieldDefinition.field_id', '{n}.LookupModelFieldDefinition.field_title_for_report', '{n}.LookupModelFieldDefinition.field_group_id');

                $field_group_id_list = Hash::extract($field_details_list, '{n}.LookupModelFieldDefinition.field_group_id');
                $condition = array('LookupModelFieldGroup.id' => $field_group_id_list);
                $field_group_list = $this->LookupModelFieldDefinition->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'id', 'order' => 'id'));


                $report_query_options = $this->GetReportConditions($model_name);
                $report_query_options['fields'] = $selected_field_list;
                $report_query_options['recursive'] = $recursive_level;


                $all_data = array();
                try {
//                    if (!empty($unbind_models_list))
//                        $this->$model_name->unbindModel($unbind_models_list, true);

                    $all_data = $this->$model_name->find('all', $report_query_options); //    , $report_query_options, $model_name.branch_id
//                    debug($all_data);
                    //$all_data = $this->$model_name->find('all', array('fields' => $selected_field_list, 'conditions' => $conditions, 'recursive' => 1, 'order' => "$model_name.org_id")); //, $model_name.branch_id
                } catch (Exception $ex) {
                    debug($ex);
                }

//                debug($selected_field_list);
//                debug($report_query_options);
//                debug($all_data);

                $group_wise_field_sub_title = null;
                $model_wise_data_details[$model_name] = array('all_data' => $all_data,
                    'selected_field_list' => $selected_field_list, 'selected_field_types' => $selected_field_types,
                    'fixed_field_details' => $fixed_field_details, 'field_group_list' => $field_group_list,
                    'group_wise_field_title' => $group_wise_field_title, 'group_wise_field_sub_title' => $group_wise_field_sub_title);
            } catch (Exception $ex) {
                debug($ex);
            }
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

        $this->set(compact('model_wise_data_details', 'is_total_show'));

//        $this->Session->write("ReportChartGenerator.Chart.Name", $model_description);
//        $this->Session->write("ReportChartGenerator.Chart.AllDataDetails", $model_wise_data_details);
    }

    function set_chart_opts() {

        $report_details = $this->Session->read("ReportChartGenerator.Chart.ReportDetails");
        if (empty($report_details) || !is_array($report_details)) {
            return;
        }

        $all_report_data = $report_details['all_report_data'];
        $report_fields = $report_details['report_fields'];

        //debug($report_details);
        //$field_group_list = $report_details['field_group_list'];
        //$group_wise_field_title = $report_details['group_wise_field_title'];

        $chartFields = array();
        $chartAllData = array();
        $chartYTitles = array();

        foreach ($all_report_data as $field_data) {
            $dr_data = '';
            $chartData = array();
            $chartSeriesName = '';

            foreach ($report_fields as $field_id => $field_title) {
                if (empty($field_id))
                    continue;

                $data_value = $field_data[$field_id];

                if (!is_numeric($data_value)) {
                    if (!empty($data_value))
                        $chartSeriesName = !empty($chartSeriesName) ? "$chartSeriesName ($data_value)" : $data_value;
                    continue;
                }

                //$data_group_name = $field_group_list[$group_id];
                $data_name = $field_title;

                if (!in_array($data_name, $chartFields))
                    $chartFields[] = $data_name;

//                        if (!empty($data_group_name) && !in_array($data_group_name, $chartYTitles))
//                            $chartYTitles[$data_name] = $data_group_name;

                $chartData[] = array('name' => $data_name, 'y' => floatval($data_value));
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
            'chartWidth' => 1000,
            'chartHeight' => 600,
            'title' => $chartName,
            'yAxisTitleText' => $chartYTitle,
            'xAxisCategories' => $chartFields,
            'creditsEnabled' => false,
            'exportingEnabled' => true,
        ));

        $this->Highcharts->setChartParams($chartNameColumn, array(
            'renderTo' => 'bar_chart',
            'chartWidth' => 1000,
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
            'chartWidth' => 1000,
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

    function set_chart_data() {

        if (isset($this->request->data['ReportChartGeneratorOptions'])) {
            $chart_type = $this->request->data['ReportChartGeneratorOptions']['chart_type'];
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

//        $chartFields = array_intersect_key($chartFields, $selected_fields); //array_keys($selected_fields);
//        //$chartFields = array_intersect_key($chartFields, $selected_fields); //array_keys($selected_fields);
//        $chartYTitle = implode(', ', array_intersect_key($chartFields, array_keys($chartFields)));


        $selected_fields_key = array_flip($selected_fields);
        $chartFields = array_intersect_key($chartFields, $selected_fields_key);
        $chartFields = array_values($chartFields);

        $chartYTitle = implode(', ', $chartFields);


        $chartNameLine = 'Line Chart';
        $chartNameColumn = 'Column Chart';
        $chartNamePie = 'Pie Chart';

        $mychartLine = $this->Highcharts->create($chartNameLine, 'line');
        $mychartColumn = $this->Highcharts->create($chartNameColumn, 'column');
        $mychartPie = $this->Highcharts->create($chartNamePie, 'pie');

        $this->Highcharts->setChartParams($chartNameLine, array(
            'renderTo' => 'line_chart',
            'chartWidth' => 1000,
            'chartHeight' => 600,
            'title' => $chartName,
            'yAxisTitleText' => $chartYTitle,
            'xAxisCategories' => $chartFields,
            'creditsEnabled' => false,
            'exportingEnabled' => true,
        ));

        $this->Highcharts->setChartParams($chartNameColumn, array(
            'renderTo' => 'bar_chart',
            'chartWidth' => 1000,
            'chartHeight' => 600,
            'title' => $chartName,
            'yAxisTitleText' => $chartYTitle,
            'xAxisCategories' => $chartFields,
            'creditsEnabled' => false,
            'exportingEnabled' => false,
        ));

//        $dataLabelFormat = <<<EOF
//                function() {return {{point.name}: ({point.x}: {point.y: .1f}%);} 
//EOF;
//
//        $dataLabelsFormat = <<<EOF
//                function() {return {point.name}: ({point.y: .0f});}
//EOF;
//
//        $tooltipFormat = <<<EOF
//                function() {return {point.name}: ({point.y: .0f});}
//EOF;
//
//        $dataLabelsFormat = <<<EOF
//function(){return this.point.name; }
//EOF;
//
//        $tooltipFormat = <<<EOF
//function(){return this.y +'%'; }
//EOF;


        $dataLabelFormat = <<<EOF
function() {return {point.name} + ({point.data:,.0f});}
EOF;

        $dataLabelsFormat = <<<EOF
function() {return {point.name} + ({point.data:,.0f});}
EOF;

        $tooltipFormat = <<<EOF
function() {return this.series.name + this.x + ': ' + this.data + ' %';}
EOF;


        $this->Highcharts->setChartParams($chartNamePie, array(
            'renderTo' => 'pie_chart',
            'chartWidth' => 1000,
            'chartHeight' => 600,
            'title' => $chartName,
            'creditsEnabled' => false,
            'plotOptionsShowInLegend' => true,
            'exportingEnabled' => true
        ));

//        debug($chartAllData);

        foreach ($selected_series as $series_id) {
            $chartSeriesName = $chartSeries[$series_id];

            $chartData = array_intersect_key($chartAllData[$chartSeriesName], $selected_fields_key);  //$selected_fields
            $chartData = Hash::extract($chartData, '{n}.y');

            $chartDataSeries = $this->Highcharts->addChartSeries();
            $chartDataSeries->addName($chartSeriesName)->addData($chartData);
            $mychartLine->addSeries($chartDataSeries);
            $mychartColumn->addSeries($chartDataSeries);
        }

        $chartSeriesName = $chartSeries[$selected_series[0]];
        $chartDataPie = array_intersect_key($chartAllData[$chartSeriesName], $selected_fields_key);  //$selected_fields
        //debug($chartDataPie);

        $chartPieDataSeries = $this->Highcharts->addChartSeries();
        $chartPieDataSeries->addName($chartSeriesName)->addData($chartDataPie);
        $mychartPie->addSeries($chartPieDataSeries);

        $this->set(compact('chart_type', 'chartNameLine', 'chartNameColumn', 'chartNamePie'));
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

    function basic_selection($opt = false) {

        $this->autoRender = false;

        $org_list = null;
        $branch_list_all = null;

        $this->Session->write("ReportModuleReportViewer.SelectedOrg.Ids", null);
        $this->Session->write("ReportModuleReportViewer.SelectedOrg.BranchIds", null);

        if (empty($opt))
            return;

        if (!empty($this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'])) {

            $org_ids = $this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'];

            if (!empty($org_ids)) {
                $this->Session->write("ReportModuleReportViewer.SelectedOrg.Ids", $org_ids);

                if (!empty($this->request->data['ReportModuleReportViewerOrgSelect']['listOrgBranch'])) {

                    $org_branch_details = $this->request->data['ReportModuleReportViewerOrgSelect']['listOrgBranch'];

                    foreach ($org_branch_details as $org_id => $org_branch_id_list) {
                        if (empty($org_branch_id_list))
                            continue;

                        $org_branch_ids = (empty($org_branch_ids)) ? $org_branch_id_list : array_merge($org_branch_ids, $org_branch_id_list);
                    }

                    if (!empty($org_branch_ids)) {
                        $this->Session->write("ReportModuleReportViewer.SelectedOrg.BranchIds", $org_branch_ids);
                    }
                }
            }
        }

        return;
    }

    function report_filter() {

        $this->Session->write("ReportModuleReportViewer.FilterReportOptions", null);

        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Divs", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Upzas", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Unions", null);

        try {
            $this->loadModel('BasicModuleBasicInformation');
            $fields = array('id', 'name_of_org');
            $condition = array('BasicModuleBasicInformation.id >' => 0);
            $org_list = $this->BasicModuleBasicInformation->find('list', array('fields' => $fields, 'conditions' => $condition, 'recursive' => -1));

            $this->loadModel('LookupAdminBoundaryDivision');
            $div_list = $this->LookupAdminBoundaryDivision->find('list', array('fields' => array('id', 'division_name'), 'recursive' => -1, 'order' => 'id'));

            $this->loadModel('LookupAdminBoundaryDistrict');
            $dist_list = $this->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'district_name'), 'recursive' => -1, 'order' => 'id'));

            $this->loadModel('AdminModulePeriodList');

            $period_type_list = $this->AdminModulePeriodList->AdminModulePeriodType->find('list', array('fields' => array('id', 'period_types'), 'recursive' => -1, 'group' => 'id', 'order' => 'serial_no'));
            $period_list = $this->AdminModulePeriodList->find('list', array('fields' => array('id', 'period'), 'recursive' => -1, 'group' => 'id', 'order' => 'type_id'));

            $group_wise_period_list = $this->AdminModulePeriodList->find('all', array('fields' => array('id', 'AdminModulePeriodType.period_types', 'period'), 'recursive' => 1, 'group' => 'type_id, id', 'order' => 'type_id'));
            $group_wise_period_list = Hash::combine($group_wise_period_list, '{n}.AdminModulePeriodList.id', '{n}.AdminModulePeriodList.period', '{n}.AdminModulePeriodType.period_types');

            $this->set(compact('org_list', 'group_wise_period_list', 'period_type_list', 'period_list', 'field_list', 'div_list', 'dist_list'));
        } catch (Exception $ex) {
            debug($ex);
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => $ex->getMessage()
            );
            $this->set(compact('msg'));
        }
    }

    function report_viewer_reset_filter($model_id = null) {

        $this->Session->write("ReportModuleReportViewer.FilterReportOptions", null);

        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Divs", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Upzas", null);
        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Unions", null);

        $this->request->data = null;

        try {
            $this->loadModel('LookupAdminBoundaryDivision');
            $div_list = $this->LookupAdminBoundaryDivision->find('list', array('fields' => array('id', 'division_name'), 'recursive' => -1, 'order' => 'id'));

            $this->loadModel('LookupAdminBoundaryDistrict');
            $dist_list = $this->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'district_name'), 'recursive' => -1, 'order' => 'id'));

            $this->set(compact('div_list', 'dist_list'));
        } catch (Exception $ex) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => $ex->getMessage()
            );
            $this->set(compact('msg'));
        }


        $report_list = null;
        $report_fields_list = null;

        $this->loadModel('LookupModelFieldDefinition');
        $this->loadModel('ReportModuleReportFieldDefinition');

        if (!empty($model_id)) {

            $model_details = $this->LookupModelFieldDefinition->LookupModelDefinition->find('first', array('fields' => array('id', 'model_name', 'model_description', 'cat_group_id'), 'conditions' => array('LookupModelDefinition.id' => $model_id), 'recursive' => -1));

            $model_name = $model_details['LookupModelDefinition']['model_name'];
            $cat_group_id = $model_details['LookupModelDefinition']['cat_group_id'];

            $report_list = array($model_details['LookupModelDefinition']['id'] => $model_details['LookupModelDefinition']['model_description']);

            $this->LookupModelFieldDefinition->virtualFields['query_field_name'] = "CASE WHEN field_name_for_report IS NOT NULL AND TRIM(field_name_for_report) <> '' THEN CASE WHEN field_model_for_report IS NOT NULL AND TRIM(field_model_for_report) <> '' THEN CONCAT_WS('.', field_model_for_report, field_name_for_report) ELSE CONCAT_WS('.', '$model_name', field_name_for_report) END ELSE NULL END";

            $condition = array('LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_report' => 1);
            $report_fields_list = $this->LookupModelFieldDefinition->find('list', array('fields' => array('field_id', 'field_description'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));
            $group_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('query_field_name', 'field_description'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));


            $condition = array('LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_report' => 1,
                'LookupModelFieldDefinition.field_group_id <>' => array(0, $cat_group_id));
            $filter_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('query_field_name', 'field_description'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));

            $condition = array('LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_report' => 1,
                'LookupModelFieldDefinition.field_group_id' => array(0, $cat_group_id));
            $order_by_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('query_field_name', 'field_description'), 'conditions' => $condition, 'recursive' => -1, 'group' => 'field_id', 'order' => 'field_sorting_order, field_id'));


            $this->loadModel($model_name);

            $group_by_fields = array();
            foreach ($group_fields as $field_name => $field_description) {
                if ($this->$model_name->hasField($field_name))
                    $group_by_fields["$model_name.$field_name"] = $field_description;
                else if ($this->$model_name->BasicModuleBranchInfo->hasField($field_name))
                    $group_by_fields["BasicModuleBranchInfo.$field_name"] = $field_description;;
            }

            if ($this->$model_name->hasField('period_id')) {
                $period_ids = $this->$model_name->find('list', array('fields' => array('period_id', 'period_id'), 'recursive' => -1, 'group' => 'period_id', 'order' => 'period_id'));

                $this->loadModel('AdminModulePeriodList');
                $group_wise_period_list = $this->AdminModulePeriodList->find('list', array('fields' => array('AdminModulePeriodList.id', 'AdminModulePeriodList.period', 'AdminModulePeriodType.period_types'), 'conditions' => array('AdminModulePeriodList.id' => $period_ids), 'recursive' => 0, 'group' => 'type_id, id', 'order' => 'type_id'));
            } else {
                $group_wise_period_list = null;
            }

            if ($this->$model_name->hasField('org_id') && $this->$model_name->BasicModuleBasicInformation->isVirtualField('name_of_org')) {
                $this->$model_name->virtualFields['name_of_org'] = $this->$model_name->BasicModuleBasicInformation->virtualFields['name_of_org'];
                $fields = array('org_id', 'name_of_org');
                $condition = array('BasicModuleBasicInformation.id >' => 0);
                $org_list = $this->$model_name->find('list', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 0, 'group' => array('org_id')));
            } else {
                $org_list = null;
            }

            $this->set(compact('org_list', 'group_wise_period_list'));

            $condition = array('LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_report' => 1,
                'LookupModelFieldDefinition.field_group_id' => $cat_group_id);
            //    'LookupModelFieldDefinition.field_group_id' => array(0, $cat_group_id));

            $fields = array('id', 'model_id', 'child_model_id', 'parent_model_id', 'parent_field_id',
                'field_name', 'field_description', 'control_type_for_add', 'field_title_to_view_in_crud',
                'containable_model_names', 'dropdown_display_field', 'dropdown_value_field', 'dropdown_condition_field',
                'model_name_for_select_option', 'model_name_for_dependent_select_option', 'dependent_dropdown_display_field',
                'dependent_dropdown_condition_field', 'dependent_dropdown_value_field', 'parent_or_child_control_id');

            $cat_fields_details = $this->LookupModelFieldDefinition->find('all', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 1, 'order' => array('field_sorting_order' => 'asc')));
            //debug($all_fields_details);

            if (!empty($cat_fields_details)) {

                $org_id = $branch_id = $from_date = $to_date = '';

                $cat_field_detail = array();
                $cat_fields_list = array();

                foreach ($cat_fields_details as $field_details) {
                    $field_details = $field_details['LookupModelFieldDefinition'];

                    $field_id = $field_details['id'];

                    $cat_field_detail['field_name'] = $field_name = $field_details['field_name'];
                    $cat_field_detail['field_label'] = $field_details['field_title_to_view_in_crud'];
                    $cat_field_detail['control_type'] = $control_type = $field_details['control_type_for_add'];

                    $cat_field_detail['child_model_id'] = $field_details['child_model_id'];
                    $cat_field_detail['parent_control_id'] = $field_details['dependent_dropdown_condition_field'];
                    $cat_field_detail['parent_or_child_control_id'] = $field_details['parent_or_child_control_id'];

                    //$group_by_fields["$model_name.$field_name"] = $cat_field_detail['field_label'];

                    $options = array();
                    if ($control_type == "select" || $control_type == "select_or_label" || $control_type == "dependent_dropdown" || $control_type == "radio" || $control_type == "checkbox") {
                        $select_option_model = $field_details['model_name_for_select_option'];
                        $dropdown_display_field = $field_details['dropdown_display_field'];
                        $dropdown_value_field = $field_details['dropdown_value_field'];
                        $dropdown_condition_field = $field_details['dropdown_condition_field'];

                        $containable_model_names = $field_details['containable_model_names'];
                        $this->loadModel($select_option_model);
                        $fields = array("$select_option_model.$dropdown_value_field", "$select_option_model.$dropdown_display_field");
                        $containable_model_name_list = explode(',', $containable_model_names);

                        $order_by = $fields[0];
                        $value_exist_conditions = array();

                        if (!empty($select_option_model) && !empty($fields)) {
                            if ($control_type == "dependent_dropdown") {
                                if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by));
                                } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                    $dependent_dropdown_condition_field = $field_details['dependent_dropdown_condition_field'];
                                    $fields[2] = "$select_option_model.$dependent_dropdown_condition_field";
                                    try {
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'contain' => $containable_model_name_list, 'recursive' => -1, 'order' => $order_by));
                                    } catch (Exception $ex) {
                                        $options = array();
                                    }
                                } elseif (!empty($dropdown_condition_field) && !empty($containable_model_name_list) && !empty($org_id)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'contain' => $containable_model_name_list, 'recursive' => -1, 'order' => $order_by));
                                } else {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by));
                                }
                            }

                            if ($control_type != "dependent_dropdown") {
                                if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by));
                                } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by, 'contain' => $containable_model_name_list));
                                } elseif (!empty($dropdown_condition_field) && !empty($containable_model_name_list) && !empty($org_id)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by, 'contain' => $containable_model_name_list));
                                } else {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by));
                                }
                            }
                        }
                    }

                    $cat_field_detail['options'] = $options;

                    $cat_fields_list[$field_id] = $cat_field_detail;
                }

                $this->set(compact('model_name', 'cat_group_id', 'filter_fields', 'group_by_fields', 'order_by_fields', 'cat_fields_list'));
            }
        }

        $this->set(compact('report_list', 'report_fields_list'));
    }

    function selected_orgs() {

        $org_list = null;

//        debug(new DateTime());
//        debug($this->request->data);

        if (isset($this->request->data['ReportModuleReportViewerDivSelect']['listDiv'])) {
            $div_ids = $this->request->data['ReportModuleReportViewerDivSelect']['listDiv'];
            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Divs", $div_ids);
        }

        if (isset($this->request->data['ReportModuleReportViewerDistSelect'])) {
            $dist_ids = array();
            $dist_ids_all_list = $this->request->data['ReportModuleReportViewerDistSelect'];
            foreach ($dist_ids_all_list as $div_id => $dist_ids_list) {
                if (empty($dist_ids_list))
                    continue;
                $dist_ids = array_merge($dist_ids, $dist_ids_list);
            }
            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", $dist_ids);
        }

        if (isset($this->request->data['ReportModuleReportViewerUpazSelect'])) {
            $upaz_ids = array();
            $upaz_ids_all_list = $this->request->data['ReportModuleReportViewerUpazSelect'];
            foreach ($upaz_ids_all_list as $dist_id => $upaz_ids_list) {
                if (empty($upaz_ids_list))
                    continue;
                $upaz_ids = array_merge($upaz_ids, $upaz_ids_list);
            }
            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Upzas", $upaz_ids);
        }

        $this->Session->write("ReportModuleReportViewer.SelectedOrg.Ids", null);


        $condition = $this->GetAdminConditions(null, 'BasicModuleBranchInfo.district_id', 'BasicModuleBranchInfo.upazila_id', 'BasicModuleBranchInfo.union_id');


        try {
//            $unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'),
//                'hasOne' => array('BasicModuleBranchImage'));

            $unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));

            $this->loadModel('BasicModuleBranchInfo');
            $this->BasicModuleBranchInfo->virtualFields['name_of_org'] = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->virtualFields['name_of_org'];
            $this->BasicModuleBranchInfo->unbindModel($unbound_models, true);
            $org_list = $this->BasicModuleBranchInfo->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBranchInfo.name_of_org'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'BasicModuleBasicInformation.id', 'order' => 'BasicModuleBasicInformation.id'));
            //debug($branch_list);
        } catch (Exception $ex) {
            debug($ex); //->getMessage()
        }

        $this->set(compact('org_list'));
        return;
    }

    function selected_org_branches() {

        $branch_list_all = null;
//        debug($this->request->data);

        if (isset($this->request->data['ReportModuleReportViewerDivSelect']['listDiv'])) {
            $div_ids = $this->request->data['ReportModuleReportViewerDivSelect']['listDiv'];
            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Divs", $div_ids);
        }

        if (isset($this->request->data['ReportModuleReportViewerDistSelect'])) {
            $dist_ids = array();
            $dist_ids_all_list = $this->request->data['ReportModuleReportViewerDistSelect'];
            foreach ($dist_ids_all_list as $div_id => $dist_ids_list) {
                if (empty($dist_ids_list))
                    continue;
                $dist_ids = array_merge($dist_ids, $dist_ids_list);
            }
            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", $dist_ids);
        }

        if (isset($this->request->data['ReportModuleReportViewerUpazSelect'])) {
            $upaz_ids = array();
            $upaz_ids_all_list = $this->request->data['ReportModuleReportViewerUpazSelect'];
            foreach ($upaz_ids_all_list as $dist_id => $upaz_ids_list) {
                if (empty($upaz_ids_list))
                    continue;
                $upaz_ids = array_merge($upaz_ids, $upaz_ids_list);
            }
            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Upzas", $upaz_ids);
        }

        if (!empty($this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'])) {
            $org_id = $this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'];
            $this->Session->write("ReportModuleReportViewer.SelectedOrg.Ids", $org_id);
        }
        $org_id = $this->Session->read("ReportModuleReportViewer.SelectedOrg.Ids");

        if (!empty($org_id)) {
            $this->loadModel('BasicModuleBasicInformation');
            $org_name = $this->BasicModuleBasicInformation->field('name_of_org', array('id' => $org_id));

            $condition_admin = $this->GetAdminConditions(null, 'BasicModuleBranchInfo.district_id', 'BasicModuleBranchInfo.upazila_id', 'BasicModuleBranchInfo.union_id');

//            debug($condition_admin);
            if (!empty($condition_admin))
                $condition = array_merge(array('BasicModuleBranchInfo.org_id' => $org_id), $condition_admin);
            else
                $condition = array('BasicModuleBranchInfo.org_id' => $org_id);

            try {
                $this->loadModel('BasicModuleBranchInfo');
                $branch_list = $this->BasicModuleBranchInfo->find('list', array('fields' => array('id', 'branch_address'), 'conditions' => $condition, 'recursive' => 0, 'order' => 'id'));
                //debug($branch_list);
            } catch (Exception $ex) {
                debug($ex); //->getMessage()
            }
        }

        $this->set(compact('org_id', 'org_name', 'branch_list'));
        return;
    }

    function selected_admin_divs() {

        try {
            $div_list = null;

            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Divs", null);

            if (isset($this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'])) {
                $org_id = $this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'];
                $this->Session->write("ReportModuleReportViewer.SelectedOrg.Ids", $org_id);
            }
            $org_ids = $this->Session->read("ReportModuleReportViewer.SelectedOrg.Ids");

            if (!empty($org_ids))
                $condition = array('BasicModuleBranchInfo.org_id' => $org_ids);
            else
                $condition = null;

            //$unbound_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'),
            //    'hasOne' => array('BasicModuleBranchImage'));

            $unbound_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));

            $this->loadModel('BasicModuleBranchInfo');
            $this->BasicModuleBranchInfo->unbindModel($unbound_models, true);
            $div_ids = $this->BasicModuleBranchInfo->find('list', array('fields' => array('LookupAdminBoundaryDistrict.division_id', 'LookupAdminBoundaryDistrict.division_id'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'LookupAdminBoundaryDistrict.division_id', 'order' => 'LookupAdminBoundaryDistrict.division_id'));

            //debug($div_ids);
            $this->loadModel('LookupAdminBoundaryDivision');
            $div_list = $this->LookupAdminBoundaryDivision->find('list', array('fields' => array('id', 'division_name'), 'conditions' => array('id' => $div_ids), 'recursive' => -1, 'order' => 'id'));

            //debug($div_list);

            $this->set(compact('div_list'));
        } catch (Exception $ex) {
            debug($ex);
        }

        $this->set(compact('div_list', 'dist_list_all'));
    }

    function selected_admin_dists() {

        try {
            $div_list = null;
            $dist_list_all = null;

            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Divs", null);

            if (!empty($this->request->data['ReportModuleReportViewerDivSelect']['listDiv'])) {

                $div_ids = $this->request->data['ReportModuleReportViewerDivSelect']['listDiv'];

                if (isset($this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'])) {
                    $org_id = $this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'];
                    $this->Session->write("ReportModuleReportViewer.SelectedOrg.Ids", $org_id);
                }

                $org_ids = $this->Session->read("ReportModuleReportViewer.SelectedOrg.Ids");

                if (!empty($org_ids))
                    $condition = array('BasicModuleBranchInfo.org_id' => $org_ids);
                else
                    $condition = null;

                $this->loadModel('BasicModuleBranchInfo');

                if (empty($div_ids)) {
                    //$unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza', 'BasicModuleBasicInformation'),
                    //    'hasOne' => array('BasicModuleBranchImage'));

                    $unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza', 'BasicModuleBasicInformation'));

                    $this->BasicModuleBranchInfo->unbindModel($unbound_models, true);
                    $div_ids = $this->BasicModuleBranchInfo->find('list', array('fields' => array('LookupAdminBoundaryDistrict.division_id', 'LookupAdminBoundaryDistrict.division_id'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'LookupAdminBoundaryDistrict.division_id', 'order' => 'LookupAdminBoundaryDistrict.division_id'));
                }

                if (!empty($div_ids)) {

                    $condition = array('id' => $div_ids);
                    $this->loadModel('LookupAdminBoundaryDivision');
                    $div_list = $this->LookupAdminBoundaryDivision->find('list', array('fields' => array('id', 'division_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

                    $condition = array('division_id' => $div_ids);
                    $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Divs", $div_ids);

                    $dist_list = $this->BasicModuleBranchInfo->LookupAdminBoundaryDistrict->find('all', array('fields' => array('division_id', 'id', 'district_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'division_id, id'));
                    $dist_list_all = Hash::combine($dist_list, '{n}.LookupAdminBoundaryDistrict.id', '{n}.LookupAdminBoundaryDistrict.district_name', '{n}.LookupAdminBoundaryDistrict.division_id');
                }
            }
        } catch (Exception $ex) {
            debug($ex);
        }

        $this->set(compact('div_list', 'dist_list_all'));
    }

    function selected_admin_upzas() {

        try {
            $upaz_list = null;
            $upaz_list_all = null;

            $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", null);


            if (!empty($this->request->data['ReportModuleReportViewerDivSelect']['listDiv']) || !empty($this->request->data['ReportModuleReportViewerDistSelect'])) {

                $this->loadModel('BasicModuleBranchInfo');

                if (!empty($this->request->data['ReportModuleReportViewerDivSelect']['listDiv'])) {
                    $div_ids = $this->request->data['ReportModuleReportViewerDivSelect']['listDiv'];

                    if (isset($this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'])) {
                        $org_id = $this->request->data['ReportModuleReportViewerOrgSelect']['listOrg'];
                        $this->Session->write("ReportModuleReportViewer.SelectedOrg.Ids", $org_id);
                    }

                    $org_ids = $this->Session->read("ReportModuleReportViewer.SelectedOrg.Ids");

//                    $condition = array();
//                    if (!empty($div_ids))
//                        $condition['division_id'] = $div_ids;
//                    if (!empty($org_ids))
//                        $condition['BasicModuleBranchInfo.org_id'] = $org_ids;
//                    
//                    //$this->loadModel('BasicModuleBranchInfo');
//                    $dist_ids = $this->BasicModuleBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'id'), 'conditions' => $condition, 'recursive' => 0, 'order' => 'id'));

                    $condition = array();
                    if (!empty($org_ids))
                        $condition['BasicModuleBranchInfo.org_id'] = $org_ids;

                    if (!empty($org_ids))
                        $condition['LookupAdminBoundaryDistrict.division_id'] = $div_ids;

//                $unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza', 'BasicModuleBasicInformation'),
//                    'hasOne' => array('BasicModuleBranchImage'));
//                    $unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza', 'BasicModuleBasicInformation'),
//                        'hasOne' => array('BasicModuleBranchImage'));
                    $unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza', 'BasicModuleBasicInformation'));

                    //$this->loadModel('BasicModuleBranchInfo');
                    $this->BasicModuleBranchInfo->unbindModel($unbound_models, true);
                    $dist_ids = $this->BasicModuleBranchInfo->find('list', array('fields' => array('LookupAdminBoundaryDistrict.id', 'LookupAdminBoundaryDistrict.id'), 'conditions' => $condition, 'recursive' => 0, 'group' => 'LookupAdminBoundaryDistrict.id', 'order' => 'LookupAdminBoundaryDistrict.id'));
                } else {
                    $dist_details = $this->request->data['ReportModuleReportViewerDistSelect'];

                    $dist_ids = null;
                    foreach ($dist_details as $div_id => $dist_id_list) {
                        if (empty($dist_id_list))
                            continue;

                        $dist_ids = (empty($dist_ids)) ? $dist_id_list : array_merge($dist_ids, $dist_id_list);
                    }
                }

                if (!empty($dist_ids)) {
                    $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", $dist_ids);

                    $condition = array('id' => $dist_ids);
                    $unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza', 'BasicModuleBasicInformation'));
                    $this->BasicModuleBranchInfo->unbindModel($unbound_models, true);
                    $dist_list = $this->BasicModuleBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'district_name'), 'conditions' => $condition, 'recursive' => 0, 'order' => 'id'));

                    $condition = array('district_id' => $dist_ids);
                    $unbound_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza', 'BasicModuleBasicInformation'));
                    $this->BasicModuleBranchInfo->unbindModel($unbound_models, true);
                    $upaz_list = $this->BasicModuleBranchInfo->LookupAdminBoundaryUpazila->find('all', array('fields' => array('district_id', 'id', 'upazila_name'), 'conditions' => $condition, 'recursive' => 0, 'order' => 'id'));

                    $upaz_list_all = Hash::combine($upaz_list, '{n}.LookupAdminBoundaryUpazila.id', '{n}.LookupAdminBoundaryUpazila.upazila_name', '{n}.LookupAdminBoundaryUpazila.district_id');
                }
            }
        } catch (Exception $ex) {
            debug($ex);
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

        $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", null);
        //$this->Session->write("ReportModuleReportViewer.CurrentAdminOption", null);

        if (!empty($this->request->data['ReportModuleReportViewerDivSelect']['listDiv']) || !empty($this->request->data['ReportModuleReportViewerDistSelect'])) {

            $this->loadModel('BasicModuleBranchInfo');
            if (!empty($this->request->data['ReportModuleReportViewerDivSelect']['listDiv'])) {
                $div_ids = $this->request->data['ReportModuleReportViewerDivSelect']['listDiv'];

                $condition = array('division_id' => $div_ids);
                //$this->loadModel('BasicModuleBranchInfo');
                $dist_ids = $this->BasicModuleBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'id'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));
            } else {
                $dist_details = $this->request->data['ReportModuleReportViewerDistSelect'];

                $dist_ids = null;
                foreach ($dist_details as $div_id => $dist_id_list) {
                    if (empty($dist_id_list))
                        continue;

                    $dist_ids = (empty($dist_ids)) ? $dist_id_list : array_merge($dist_ids, $dist_id_list);
                }
            }

            if (!empty($dist_ids)) {
                $condition = array('id' => $dist_ids);
                //$this->loadModel('BasicModuleBranchInfo');
                $dist_list = $this->BasicModuleBranchInfo->LookupAdminBoundaryDistrict->find('list', array('fields' => array('id', 'district_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

                $condition = array('district_id' => $dist_ids);

                $this->Session->write("ReportModuleReportViewer.SelectedAdmin.Dists", $dist_ids);
                //$this->Session->write("ReportModuleReportViewer.CurrentAdminOption", "district_id in (" . implode(", ", $dist_ids) . ")");
                ///$this->loadModel('LookupAdminBoundaryUpazila');
                $upaz_list = $this->BasicModuleBranchInfo->LookupAdminBoundaryUpazila->find('all', array('fields' => array('district_id', 'id', 'upazila_name'), 'conditions' => $condition, 'recursive' => -1, 'order' => 'id'));

                $upaz_list_all = Hash::combine($upaz_list, '{n}.LookupAdminBoundaryUpazila.id', '{n}.LookupAdminBoundaryUpazila.upazila_name', '{n}.LookupAdminBoundaryUpazila.district_id');
            }
        }

        $this->set(compact('dist_list', 'upaz_list_all', 'union_list_all'));
    }

    function report_map_viewer($admin_code = null, $org_id = null) {

        if (!$admin_code)
            $admin_code = "dist";

        $this->loadModel('BasicModuleBasicInformation');
        $org_list = $this->BasicModuleBasicInformation->find('list', array('fields' => array('id', 'name_of_org'), 'recursive' => -1, 'order' => 'name_of_org'));

//        $unbind_models = array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'), 'hasOne' => array('BasicModuleBranchImage'));
//        $this->loadModel("BasicModuleBranchInfo");
//        $this->BasicModuleBranchInfo->unbindModel($unbind_models, true);
//        $org_list = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->find('list', array('fields' => array('id', 'name_of_org'), 'recursive' => -1, 'group' => array('BasicModuleBranchInfo.org_id'), 'order' => 'name_of_org'));

        $unbind_models = $fields = $group_field_list = $group_field_list = $order_field_list = array();

        if ($admin_code == "dist") {
            $unbind_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));

            $fields = array("LookupAdminBoundaryDistrict.id AS __geo_code",
                "LookupAdminBoundaryDistrict.district_name AS __geo_name",
                "COUNT(BasicModuleBranchInfo.id) AS __data_value");
            $group_field_list = array("BasicModuleBranchInfo.district_id");
            $order_field_list = array("LookupAdminBoundaryDistrict.id ASC");
        } else if ($admin_code == "upaz") {
            $unbind_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));

            $fields = array("LookupAdminBoundaryUpazila.id AS __geo_code",
                "LookupAdminBoundaryUpazila.upazila_name AS __geo_name",
                "COUNT(BasicModuleBranchInfo.id) AS __data_value");
            $group_field_list = array("BasicModuleBranchInfo.upazila_id");
            $order_field_list = array("LookupAdminBoundaryUpazila.district_id ASC", "LookupAdminBoundaryUpazila.id ASC");
        }

        $conditions = array();
        //$org_id = $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id))
            $conditions = array('BasicModuleBranchInfo.org_id' => $org_id);

//        $this->BasicModuleBranchInfo->virtualFields["org_name"] = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->virtualFields["name_of_org"];
//        $this->BasicModuleBranchInfo->virtualFields["contract_info1"] = "CONCAT_WS(', ', CASE WHEN email_address IS NOT NULL AND REPLACE(email_address, ' ','') != '' THEN email_address ELSE NULL END, REPLACE(phone_no, ' ',''), CASE WHEN fax IS NOT NULL AND REPLACE(fax, ' ','') != '' THEN CONCAT(' fax: ', fax) ELSE NULL END)";
        $this->loadModel("BasicModuleBranchInfo");
        $this->BasicModuleBranchInfo->unbindModel($unbind_models, true);
        $branchInfos = $this->BasicModuleBranchInfo->find("all", array('fields' => $fields, 'conditions' => $conditions, 'group' => $group_field_list, 'order' => $order_field_list, 'recursive' => 0)); //, 'limit'=>100
//        debug($branchInfos);
//        die();
        $branch_infos = array();
        foreach ($branchInfos as $branchInfo) {
            $branch_infos[] = array(
                'geo_code' => $branchInfo[0]["__geo_code"],
                'geo_name' => $branchInfo[0]["__geo_name"],
                'data_value' => $branchInfo[0]["__data_value"]
            );
        }

//            'lat' => $branchInfo["BasicModuleBranchInfo"]["lat"],
//            'lon' => $branchInfo["BasicModuleBranchInfo"]["long"],
        //echo(json_encode($branch_infos));

        $this->set(compact('org_id', 'org_list', 'admin_code', 'branch_infos'));
    }

    function get_branche_count_info($admin_code = null, $org_id = null, $branch_type_id = null) {

        $branch_infos = array();

        if (!$admin_code)
            $admin_code = "dist";

        $unbind_models = $fields = $group_field_list = $group_field_list = $order_field_list = array();

        if ($admin_code == "dist") {
            $unbind_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));

            $fields = array("LookupAdminBoundaryDistrict.id AS __geo_code",
                "LookupAdminBoundaryDistrict.district_name AS __geo_name",
                "COUNT(BasicModuleBranchInfo.id) AS __data_value");
            $group_field_list = array("BasicModuleBranchInfo.district_id");
            $order_field_list = array("LookupAdminBoundaryDistrict.id ASC");
        } else if ($admin_code == "upaz") {
            $unbind_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));

            $fields = array("LookupAdminBoundaryUpazila.id AS __geo_code",
                "LookupAdminBoundaryUpazila.upazila_name AS __geo_name",
                "COUNT(BasicModuleBranchInfo.id) AS __data_value");
            $group_field_list = array("BasicModuleBranchInfo.upazila_id");
            $order_field_list = array("LookupAdminBoundaryUpazila.district_id ASC", "LookupAdminBoundaryUpazila.id ASC");
        }

        $conditions = array();
        if (!empty($org_id))
            $conditions = array('BasicModuleBranchInfo.org_id' => $org_id);
        if (!empty($branch_type_id))
            $conditions = array('BasicModuleBranchInfo.office_type_id' => $branch_type_id);

        $this->loadModel("BasicModuleBranchInfo");
        $this->BasicModuleBranchInfo->unbindModel($unbind_models, true);
        $branchInfos = $this->BasicModuleBranchInfo->find("all", array('fields' => $fields, 'conditions' => $conditions, 'group' => $group_field_list, 'order' => $order_field_list, 'recursive' => 0));

        foreach ($branchInfos as $branchInfo) {
            $branch_infos[] = array(
                'geo_code' => $branchInfo[0]["__geo_code"],
                'geo_name' => $branchInfo[0]["__geo_name"],
                'data_value' => $branchInfo[0]["__data_value"]
            );
        }

        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
            $this->autoLayout = false;
            $this->autoRender = false;
            echo(json_encode($branch_infos));
            return;
        }

        return $branch_infos;
    }

    function get_branche_count_infoP($admin_code = null, $org_id = null) {

        $branch_infos = array();

        if (!$admin_code)
            $admin_code = "dist";

        $unbind_models = $fields = $group_field_list = $group_field_list = $order_field_list = array();

        if ($admin_code == "dist") {
            $unbind_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));

            $fields = array("LookupAdminBoundaryDistrict.id AS __geo_code",
                "LookupAdminBoundaryDistrict.district_name AS __geo_name",
                "COUNT(BasicModuleBranchInfo.id) AS __data_value");
            $group_field_list = array("BasicModuleBranchInfo.district_id");
            $order_field_list = array("LookupAdminBoundaryDistrict.id ASC");
        } else if ($admin_code == "upaz") {
            $unbind_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));

            $fields = array("LookupAdminBoundaryUpazila.id AS __geo_code",
                "LookupAdminBoundaryUpazila.upazila_name AS __geo_name",
                "COUNT(BasicModuleBranchInfo.id) AS __data_value");
            $group_field_list = array("BasicModuleBranchInfo.upazila_id");
            $order_field_list = array("LookupAdminBoundaryUpazila.district_id ASC", "LookupAdminBoundaryUpazila.id ASC");
        }

        $conditions = array();
        if (!empty($org_id))
            $conditions = array('BasicModuleBranchInfo.org_id' => $org_id);

        $this->loadModel("BasicModuleBranchInfo");
        $this->BasicModuleBranchInfo->unbindModel($unbind_models, true);
        $branchInfos = $this->BasicModuleBranchInfo->find("all", array('fields' => $fields, 'conditions' => $conditions, 'group' => $group_field_list, 'order' => $order_field_list, 'recursive' => 0));

        foreach ($branchInfos as $branchInfo) {
            $branch_infos[] = array(
                'geo_code' => $branchInfo[0]["__geo_code"],
                'geo_name' => $branchInfo[0]["__geo_name"],
                'data_value' => $branchInfo[0]["__data_value"]
            );
        }

        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
            $this->autoLayout = false;
            $this->autoRender = false;
            echo(json_encode($branch_infos));
            return;
        }

        return $branch_infos;
    }

    function get_loan_info($admin_code = null, $org_id = null, $model_name = null, $field_name = null, $cat_id = null, $cat_sub_id = null, $cat_sub_sub_id = null) {

        $loan_infos = array();

        $model_name = empty($model_name) ? 'LoanModuleBranchwiseLoanInformation' : $model_name;
        $field_name = empty($field_name) ? 'no_of_borrowers' : $field_name;

        $branch_type_id = 3;

        if (!$admin_code)
            $admin_code = "dist";

        $unbind_models = $fields = $joins = $group_field_list = $group_field_list = $order_field_list = array();

        if ($admin_code == "dist") {
            $joins = array(
                array(
                    'table' => 'lookup_admin_boundary_districts',
                    'alias' => 'LookupAdminBoundaryDistrict',
                    'type' => 'LEFT',
                    'conditions' => array('LookupAdminBoundaryDistrict.id = BasicModuleBranchInfo.district_id')
                )
            );

            $fields = array("BasicModuleBranchInfo.district_id AS __geo_code",
                "LookupAdminBoundaryDistrict.district_name AS __geo_name",
                "SUM(LoanModuleBranchwiseLoanInformation.$field_name) AS __data_value");
            $group_field_list = array("BasicModuleBranchInfo.district_id");
            $order_field_list = array("LookupAdminBoundaryDistrict.id ASC");
        } else if ($admin_code == "upaz") {
            $joins = array(
                array(
                    'table' => 'lookup_admin_boundary_upazilas',
                    'alias' => 'LookupAdminBoundaryUpazila',
                    'type' => 'LEFT',
                    'conditions' => array('LookupAdminBoundaryUpazila.id = BasicModuleBranchInfo.upazila_id')
                )
            );

            $fields = array("LookupAdminBoundaryUpazila.id AS __geo_code",
                "LookupAdminBoundaryUpazila.upazila_name AS __geo_name",
                "SUM(LoanModuleBranchwiseLoanInformation.$field_name) AS __data_value");
            $group_field_list = array("BasicModuleBranchInfo.upazila_id");
            $order_field_list = array("LookupAdminBoundaryUpazila.district_id ASC", "LookupAdminBoundaryUpazila.id ASC");
        }


//            if (empty($org_id))
//                $org_id = $org_id = $this->Session->read('Org.Id');

        $conditions = array();
        if (!empty($org_id))
            $conditions = array('BasicModuleBranchInfo.org_id' => $org_id);
        if (!empty($branch_type_id))
            $conditions = array('BasicModuleBranchInfo.office_type_id' => $branch_type_id);

        if (!empty($cat_id))
            $conditions = array('LoanModuleBranchwiseLoanInformation.loan_category_id' => $cat_id);
        if (!empty($cat_sub_id))
            $conditions = array('LoanModuleBranchwiseLoanInformation.loan_sub_category_id' => $cat_sub_id);
        if (!empty($cat_sub_sub_id))
            $conditions = array('LoanModuleBranchwiseLoanInformation.loan_sub_sub_category_id' => $cat_sub_sub_id);


        $this->loadModel($model_name);

//        $unbind_models = array('belongsTo' => array('BasicModuleBasicInformation', 'LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza'));
//        $this->LoanModuleBranchwiseLoanInformation->BasicModuleBranchInfo->unbindModel($unbind_models, true);

        $unbind_models = array('belongsTo' => array('AdminModulePeriodList', 'BasicModuleBasicInformation', 'LookupLoanCategory', 'LookupLoanSubCategory', 'LookupLoanSubSubCategory'));
        $this->$model_name->unbindModel($unbind_models, true);
        $loanInfos = $this->$model_name->find("all", array('fields' => $fields, 'joins' => $joins, 'contain' => array('LookupAdminBoundaryDistrict'), 'group' => $group_field_list, 'order' => $order_field_list, 'recursive' => 0));

        //$loanInfos = $this->LoanModuleBranchwiseLoanInformation->find("all", array('fields' => $fields, 'limit' => 2, 'recursive' => 0));
        //debug($loanInfos);

        foreach ($loanInfos as $loanInfo) {
            $loan_infos[] = array(
                'geo_code' => $loanInfo[0]["__geo_code"],
                'geo_name' => $loanInfo[0]["__geo_name"],
                'data_value' => $loanInfo[0]["__data_value"]
            );
        }

        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
            $this->autoLayout = false;
            $this->autoRender = false;
            echo(json_encode($loan_infos));
            return;
        }

        return $loan_infos;
    }

    function get_branches_location($org_id = null, $branch_type_id = null) {

//            if (empty($org_id))
//                $org_id = $org_id = $this->Session->read('Org.Id');

        $conditions = array();
        if (!empty($org_id))
            $conditions['BasicModuleBranchInfo.org_id'] = $org_id;
        else if (empty($branch_type_id))
            $branch_type_id = 1;

        if (!empty($branch_type_id))
            $conditions['BasicModuleBranchInfo.office_type_id'] = $branch_type_id;

//            else if (empty($org_id)) {
//                $branch_type_id = 1;
//                $conditions['BasicModuleBranchInfo.office_type_id'] = $branch_type_id;
//            }
//            debug($conditions);
//            if (!empty($org_id))
//                $conditions = array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id' => $branch_type_id);
//            else {
//                //$branch_type_id = 1;
//                $conditions = array('BasicModuleBranchInfo.office_type_id' => $branch_type_id);
//            }

        $this->loadModel("BasicModuleBranchInfo");
        $this->BasicModuleBranchInfo->virtualFields["org_name"] = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->virtualFields["name_of_org"];
        $this->BasicModuleBranchInfo->virtualFields["contract_info"] = "CONCAT_WS(', ', CASE WHEN email_address IS NOT NULL AND REPLACE(email_address, ' ','') != '' THEN email_address ELSE NULL END, REPLACE(phone_no, ' ',''), CASE WHEN fax IS NOT NULL AND REPLACE(fax, ' ','') != '' THEN CONCAT(' fax: ', fax) ELSE NULL END)";
        $this->BasicModuleBranchInfo->unbindModel(array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza')), true);

        $fields = array('org_id', 'org_name', 'office_type_id', 'branch_name', 'branch_code', 'district_id', 'upazila_id', 'road_name_or_village',
            'mohalla_or_post_office', 'mailing_address', 'lat', 'long', 'contract_info', 'image_name');

        $branchInfos = $this->BasicModuleBranchInfo->find("all", array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0)); //, 'limit'=>100
//            debug($branchInfos);
        $branch_infos = array();
        foreach ($branchInfos as $branchInfo) {
            try {
                $branch_infos[] = array(
                    'lat' => $branchInfo["BasicModuleBranchInfo"]["lat"],
                    'lon' => $branchInfo["BasicModuleBranchInfo"]["long"],
                    'org_id' => $branchInfo["BasicModuleBranchInfo"]["org_id"],
                    'org_name' => $branchInfo["BasicModuleBranchInfo"]["org_name"],
                    'branch_type_id' => $branchInfo["BasicModuleBranchInfo"]["office_type_id"],
                    'branch_code' => $branchInfo["BasicModuleBranchInfo"]["branch_code"],
                    'branch_name' => $branchInfo["BasicModuleBranchInfo"]["branch_name"],
                    'dist_code' => $branchInfo["BasicModuleBranchInfo"]["district_id"],
                    'upaz_code' => $branchInfo["BasicModuleBranchInfo"]["upazila_id"],
                    'road_name_or_village' => $branchInfo["BasicModuleBranchInfo"]["road_name_or_village"],
                    'mohalla_or_post_office' => $branchInfo["BasicModuleBranchInfo"]["mohalla_or_post_office"],
                    'mailing_address' => $branchInfo["BasicModuleBranchInfo"]["mailing_address"],
                    'file_name' => $branchInfo["BasicModuleBranchInfo"]["image_name"],
                    'contract_info' => $branchInfo["BasicModuleBranchInfo"]["contract_info"]
                );
            } catch (Exception $ex) {
                
            }
        }

        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
            $this->autoLayout = false;
            $this->autoRender = false;

            echo(json_encode($branch_infos));
            return;
        }

        return $branch_infos;
    }

    //, $branch_type_id = 1
    function branches_location_map($org_id = null, $branch_type_id = 1) {

        if (empty($org_id))
            $org_id = $org_id = $this->Session->read('Org.Id');

        $this->loadModel('BasicModuleBasicInformation');
        $org_list = $this->BasicModuleBasicInformation->find('list', array('fields' => array('id', 'name_of_org'), 'recursive' => -1, 'order' => 'name_of_org'));

        $conditions = array();
        if (!empty($org_id))
            $conditions['BasicModuleBranchInfo.org_id'] = $org_id;

        if (!empty($branch_type_id))
            $conditions['BasicModuleBranchInfo.office_type_id'] = $branch_type_id;


        $this->loadModel("BasicModuleBranchInfo");

        $branch_types = $this->BasicModuleBranchInfo->LookupBasicOfficeType->find('list', array('fields' => array('id', 'office_type'), 'recursive' => -1, 'order' => 'id'));

        $this->BasicModuleBranchInfo->virtualFields["org_name"] = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->virtualFields["name_of_org"];
        $this->BasicModuleBranchInfo->virtualFields["contract_info"] = "CONCAT_WS(', ', CASE WHEN email_address IS NOT NULL AND REPLACE(email_address, ' ','') != '' THEN email_address ELSE NULL END, REPLACE(phone_no, ' ',''), CASE WHEN fax IS NOT NULL AND REPLACE(fax, ' ','') != '' THEN CONCAT(' fax: ', fax) ELSE NULL END)";
        $this->BasicModuleBranchInfo->unbindModel(array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza')), true);

        $fields = array('org_id', 'org_name', 'office_type_id', 'branch_name', 'branch_code', 'district_id', 'upazila_id', 'road_name_or_village',
            'mohalla_or_post_office', 'mailing_address', 'lat', 'long', 'contract_info', 'image_name');

        $branchInfos = $this->BasicModuleBranchInfo->find("all", array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0)); //, 'limit'=>100
        $branch_infos = array();
        foreach ($branchInfos as $branchInfo) {
            $branch_infos[] = array(
                'lat' => $branchInfo["BasicModuleBranchInfo"]["lat"],
                'lon' => $branchInfo["BasicModuleBranchInfo"]["long"],
                'org_id' => $branchInfo["BasicModuleBranchInfo"]["org_id"],
                'org_name' => $branchInfo["BasicModuleBranchInfo"]["org_name"],
                'branch_type_id' => $branchInfo["BasicModuleBranchInfo"]["office_type_id"],
                'branch_code' => $branchInfo["BasicModuleBranchInfo"]["branch_code"],
                'branch_name' => $branchInfo["BasicModuleBranchInfo"]["branch_name"],
                'dist_code' => $branchInfo["BasicModuleBranchInfo"]["district_id"],
                'upaz_code' => $branchInfo["BasicModuleBranchInfo"]["upazila_id"],
                'road_name_or_village' => $branchInfo["BasicModuleBranchInfo"]["road_name_or_village"],
                'mohalla_or_post_office' => $branchInfo["BasicModuleBranchInfo"]["mohalla_or_post_office"],
                'mailing_address' => $branchInfo["BasicModuleBranchInfo"]["mailing_address"],
                'file_name' => $branchInfo["BasicModuleBranchInfo"]["image_name"],
                'contract_info' => $branchInfo["BasicModuleBranchInfo"]["contract_info"]
            );
        }

        $this->set(compact('org_id', 'org_list', 'branch_type_id', 'branch_types', 'branch_infos'));
        return;
    }

    function branches_location_map_new() {

        $fields = array('org_id', 'org_name', 'branch_name', 'branch_code', 'district_id', 'upazila_id', 'union_id',
            'road_name_or_village', 'mohalla_or_post_office', 'mailing_address', 'contract_info', 'lat', 'long',
            'image_name');

        $conditions = array();
        $org_id = $org_id = $this->Session->read('Org.Id');
        if (!empty($org_id))
            $conditions = array('BasicModuleBranchInfo.org_id' => $org_id);

        debug("branchInfos:");

        debug(date('H:i:s'));

        $this->loadModel("BasicModuleBranchInfo");
        $this->BasicModuleBranchInfo->virtualFields["org_name"] = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->virtualFields["name_of_org"];
        $this->BasicModuleBranchInfo->unbindModel(array('belongsTo' => array('LookupBasicOfficeType', 'LookupAdminBoundaryDistrict', 'LookupAdminBoundaryUpazila', 'LookupAdminBoundaryUnion', 'LookupAdminBoundaryMauza')), true);
        $branchInfos = $this->BasicModuleBranchInfo->find("all", array('fields' => $fields, 'conditions' => $conditions, 'recursive' => 0, 'limit' => 100)); //
        debug(count($branchInfos));
        debug(date('H:i:s'));

        debug("org_list:");
        debug(date('Y-m-d H:i:s'));
        $this->loadModel('BasicModuleBasicInformation');
        $org_list = $this->BasicModuleBasicInformation->find('list', array('fields' => array('id', 'name_of_org'), 'recursive' => -1, 'order' => 'name_of_org'));
        debug(count($org_list));
        debug(date('Y-m-d H:i:s'));

        //debug($branchInfos);

        debug("Json:");
        debug(date('Y-m-d H:i:s'));
        $branch_infos = array();
        foreach ($branchInfos as $branchInfo) {
            $branch_infos[] = array(
                'lat' => $branchInfo["BasicModuleBranchInfo"]["lat"],
                'lon' => $branchInfo["BasicModuleBranchInfo"]["long"],
                'org_id' => $branchInfo["BasicModuleBranchInfo"]["org_id"],
                'org_name' => $branchInfo["BasicModuleBranchInfo"]["org_name"],
                'branch_code' => $branchInfo["BasicModuleBranchInfo"]["branch_code"],
                'branch_name' => $branchInfo["BasicModuleBranchInfo"]["branch_name"],
                'dist_code' => $branchInfo["BasicModuleBranchInfo"]["district_id"],
                'upaz_code' => $branchInfo["BasicModuleBranchInfo"]["upazila_id"],
                'union_id' => $branchInfo["BasicModuleBranchInfo"]["union_id"],
                'road_name_or_village' => $branchInfo["BasicModuleBranchInfo"]["road_name_or_village"],
                'mohalla_or_post_office' => $branchInfo["BasicModuleBranchInfo"]["mohalla_or_post_office"],
                'mailing_address' => $branchInfo["BasicModuleBranchInfo"]["mailing_address"],
                'file_name' => $branchInfo["BasicModuleBranchInfo"]["image_name"],
                'contract_info' => $branchInfo["BasicModuleBranchInfo"]["contract_info"]
            );
        }
        debug(count($branch_infos));
        debug(date('Y-m-d H:i:s'));


        //debug(date('Y-m-d H:i:s'));
        $this->set(compact('org_id', 'org_list', 'branch_infos'));
        debug(date('Y-m-d H:i:s'));
    }

    function branch_info() {

        $this->layout = 'ajax';
        $this->autoRender = false;

        $this->loadModel("BasicModuleBranchInfo");

        //$this->BasicModuleBranchInfo->
        $this->BasicModuleBranchInfo->virtualFields["name_of_org"] = $this->BasicModuleBranchInfo->BasicModuleBasicInformation->virtualFields["name_of_org"];
        $branchInfos = $this->BasicModuleBranchInfo->find("all");

        debug($branchInfos);
        return;

        $branch_infos = array();

        foreach ($branchInfos as $branchInfo) {
            $branch_infos[] = array(
                'lat' => $branchInfo["lat"],
                'lon' => $branchInfo["long"],
                'org_id' => $branchInfo["org_id"], //["org_name"],
                'org_name' => $branchInfo["org_id"], //["org_name"],
                'branch_code' => $branchInfo["branch_code"],
                'branch_name' => $branchInfo["branch_name"],
                'road_name_or_village' => $branchInfo["road_name_or_village"],
                'mohalla_or_post_office' => $branchInfo["mohalla_or_post_office"],
                'mailing_address' => $branchInfo["mailing_address"],
                'file_name' => $branchInfo["file_name"]
            );
        }

        json_encode($branch_infos);

        $this->set(compact('branch_infos'));
    }

    function report_viewer_by_selected_fields_ok() {

        if (empty($this->request->data['ReportModuleReportViewer']) || !array_filter($this->request->data['ReportModuleReportViewer'])) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Report fields are not selected !'
            );
            $this->set(compact('msg'));

            return;
        }

        $allInfo = $this->request->data['ReportModuleReportViewer'];
        //debug($allInfo);


        $this->loadModel('ReportModuleReportFieldDefinition');
        foreach ($allInfo as $report_id => $report_field_ids) {

            if (empty($report_field_ids)) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... ... !',
                    'msg' => 'Report fields are not selected !'
                );
                $this->set(compact('msg'));

                continue;
            }

            try {


                $condition = array('report_id' => $report_id);
                $report_details = $this->ReportModuleReportFieldDefinition->ReportModuleReportDefinition->find('first', array('conditions' => $condition, 'recursive' => 0, 'group' => 'report_id', 'order' => 'report_id'));

                //debug($report_details);
//            $base_model = $report_details['ReportModuleReportDefinition']['report_base_model'];
//            $report_condition = $report_details['ReportModuleReportDefinition']['report_query_options'];
//            $report_list = Hash::combine($report_list, '{n}.ReportModuleReportDefinition.report_id', '{n}.ReportModuleReportDefinition');
//            $report_list = Hash::remove($report_list, '{n}.report_id');
                //try {

                $condition = array('field_id' => $report_field_ids);
                $fields = array('field_id', 'field_detail', 'field_name', 'field_title', 'field_for_report', 'field_display_title', 'field_model', 'associated_field', 'associated_model', 'association_type', 'condition_for_association', 'aggregation_type', 'group_by_field', 'field_unit', 'field_unit_conversion_factor');
                $this->ReportModuleReportFieldDefinition->virtualFields['field_display_title'] = "CASE WHEN field_group_title IS NOT NULL AND TRIM(field_group_title) <> '' AND field_title IS NOT NULL AND TRIM(field_title) <> '' THEN CONCAT(field_group_title, ' (', field_title, ')') ELSE CONCAT_WS('', field_group_title, field_title) END";
                $this->ReportModuleReportFieldDefinition->virtualFields['field_for_report'] = "CASE WHEN field_model IS NOT NULL AND TRIM(field_model) <> '' AND field_detail IS NOT NULL AND TRIM(field_detail) <> '' THEN CONCAT_WS('.', field_model, field_detail) ELSE field_detail END";
                //$this->ReportModuleReportFieldDefinition->virtualFields['field_for_report'] = "CONCAT_WS('.', field_model, field_detail)";
                $all_field_details = $this->ReportModuleReportFieldDefinition->find('all', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 1, 'order' => 'field_id, field_sorting_order'));

                //debug($all_field_details);
                //debug($all_field_details);
//                if (!empty($aggregation_type)) {
//                    $aggregated_model_name = '';
//                    $aggregated_field_name = '';
//                    $aggregation_type = '';
//                    $group_by_field = '';
//                    $aggregation_result = $this->$aggregated_model_name->find('first', array());
//                }

                $report_field_model_list = Hash::extract($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_model');
                //debug($report_field_model_list);
                $report_field_model_list = array_unique(array_filter($report_field_model_list));

                $field_title_list = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_id', '{n}.ReportModuleReportFieldDefinition.field_display_title');

                $report_fields = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_id', '{n}.ReportModuleReportFieldDefinition');
                //debug($report_field_model_list);
                $report_fields = Hash::extract($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_for_report');
                //$report_fields = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_id', '{n}.ReportModuleReportFieldDefinition.field_display_title');

                $report_fields = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_for_report', '{n}.ReportModuleReportFieldDefinition.field_display_title');
                //'field_for_report' => 'field_display_title'
//                debug($report_fields);
                //debug($report_fields);



                $report_details = $report_details['ReportModuleReportDefinition'];
                $base_model = $report_details['report_base_model'];

                $this->loadModel($base_model);

                if ($base_model != "BasicModuleBasicInformation")
                    $this->$base_model->virtualFields['name_of_org'] = $this->$base_model->BasicModuleBasicInformation->virtualFields['name_of_org'];


                $find_option = empty($report_details['report_find_option']) ? 'all' : $report_details['report_find_option'];

//            $find_option = $report_details['report_find_option'];
//            $report_condition = $report_details['report_query_options'];


                $report_query_options = array();
                if (!empty($report_fields))
                    $report_query_options['fields'] = $report_fields;
                if (!empty($report_details['report_conditions']))
                    $report_query_options['conditions'] = $report_details['report_conditions'];
                if (!empty($report_details['report_group']))
                    $report_query_options['group'] = $report_details['report_group'];
                if (!empty($report_details['report_order']))
                    $report_query_options['order'] = $report_details['report_order'];

                $report_data = $this->$base_model->find($find_option, $report_query_options);
                //debug($report_data);
//                $aggregation_result;
            } catch (Exception $ex) {
                debug($ex->getMessage());
            }


            try {
                //$report_fields = array('Sl. No.', 'Particulars', 'As on June 2015');
                $report_details = array('report_data' => $report_data, 'report_fields' => $report_fields);

                if (empty($report_details)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Report data not available !'
                    );
                    $this->set(compact('msg'));

                    return;
                }
            } catch (Exception $ex) {
                debug($ex->getMessage());
            }

            $this->set(compact('report_details'));

            /*
              exit;


              if (empty($report_field_model_list) || count($report_field_model_list) < 1)
              return;


              $has_basic = false;
              if (in_array("BasicModuleBasicInformation", $report_field_model_list)) {
              $has_basic = true;
              unset($report_field_model_list["BasicModuleBasicInformation"]);
              }


              $report_data = array();

              if (count($report_field_model_list) > 0) {
              try {

              foreach ($report_field_model_list as $model_id => $model_name) {
              if (empty($model_name))
              continue;

              $this->loadModel($field_model);
              $field_values = $this->$field_model->find('all', array('fields' => array("$aggregation_type($field_detail) as $field_name"), 'conditions' => $condition, 'recursive' => -1, 'group' => 'org_id'));



              continue;

              //'field_id', 'field_detail', 'field_name', 'field_title',  'field_model', 'associated_field', 'associated_model', 'association_type', 'condition_for_association', 'aggregation_type', 'group_by_field'
              $field_id = $field_details['field_id'];
              $field_detail = $field_details['field_detail'];
              $field_name = $field_details['field_name'];
              $field_title = $field_details['field_title'];
              $field_model = $field_details['field_model'];
              $associated_field = $field_details['associated_field'];
              $associated_model = $field_details['associated_model'];
              $association_type = $field_details['association_type'];
              $condition_for_association = $field_details['condition_for_association'];
              $aggregation_type = $field_details['aggregation_type'];
              $group_by_field = $field_details['group_by_field'];

              $field_unit = $field_details['field_unit'];
              $field_unit_conversion_factor = $field_details['field_unit_conversion_factor'];

              if (empty($field_detail) || empty($field_name) || empty($field_model))
              continue;

              $condition = null;

              $this->loadModel($field_model);

              $field_value = $this->$field_model->find('first', array('fields' => array("$aggregation_type($field_detail) as $field_name"), 'conditions' => $condition, 'recursive' => -1, 'group' => $group_by_field));
              if (isset($field_value[0][$field_name])) {
              $field_value = $field_value[0][$field_name];
              if (!empty($field_unit_conversion_factor))
              $field_value = $field_value * $field_unit_conversion_factor;

              if (is_float($field_value))
              $field_value = number_format($field_value, 3);

              if (!empty($field_unit))
              $field_value .= " $field_unit";

              $report_data[$field_id] = array($field_title => $field_value);
              //$report_data[$field_id] = array($field_title => $field_value);
              } else
              $report_data[$field_id][$field_title] = null;
              }
              } catch (Exception $ex) {
              debug($ex->getMessage());
              }
              }


              $this->loadModel('BasicModuleBasicInformation'); //
              $this->BasicModuleBasicInformation->recursive = 1;
              $orgs = $this->BasicModuleBasicInformation->find('all'); //, array('fields' => $report_fields, 'conditions' => $condition));

              debug($orgs);

              $org_ids = $this->BasicModuleBasicInformation->find('list', array('fields' => array('id', 'id'), 'recursive' => -1));

              debug($org_ids);


              $report_data = array();

              foreach ($report_fields as $field_id => $field_details) {
              if (empty($field_details))
              continue;

              //'field_id', 'field_detail', 'field_name', 'field_title',  'field_model', 'associated_field', 'associated_model', 'association_type', 'condition_for_association', 'aggregation_type', 'group_by_field'
              $field_id = $field_details['field_id'];
              $field_detail = $field_details['field_detail'];
              $field_name = $field_details['field_name'];
              $field_title = $field_details['field_title'];
              $field_model = $field_details['field_model'];
              $associated_field = $field_details['associated_field'];
              $associated_model = $field_details['associated_model'];
              $association_type = $field_details['association_type'];
              $condition_for_association = $field_details['condition_for_association'];
              $aggregation_type = $field_details['aggregation_type'];
              $group_by_field = $field_details['group_by_field'];

              $field_unit = $field_details['field_unit'];
              $field_unit_conversion_factor = $field_details['field_unit_conversion_factor'];

              if (empty($field_detail) || empty($field_name) || empty($field_model))
              continue;

              $condition = null;

              $this->loadModel($field_model);

              $field_value = $this->$field_model->find('first', array('fields' => array("$aggregation_type($field_detail) as $field_name"), 'conditions' => $condition, 'recursive' => -1, 'group' => $group_by_field));
              if (isset($field_value[0][$field_name])) {
              $field_value = $field_value[0][$field_name];
              if (!empty($field_unit_conversion_factor))
              $field_value = $field_value * $field_unit_conversion_factor;

              if (is_float($field_value))
              $field_value = number_format($field_value, 3);

              if (!empty($field_unit))
              $field_value .= " $field_unit";

              $report_data[$field_id] = array($field_title => $field_value);
              //$report_data[$field_id] = array($field_title => $field_value);
              } else
              $report_data[$field_id][$field_title] = null;
              }
              } catch (Exception $ex) {
              debug($ex->getMessage());
              }

              try {
              $report_fields = array('Sl. No.', 'Particulars', 'As on June 2015');
              $report_details = array('report_data' => $report_data, 'report_fields' => $report_fields);

              if (empty($report_details)) {
              $msg = array(
              'type' => 'error',
              'title' => 'Error... ... !',
              'msg' => 'Report data not available !'
              );
              $this->set(compact('msg'));

              return;
              }
              } catch (Exception $ex) {
              debug($ex->getMessage());
              }
             */
            $this->set(compact('report_details'));
        }
    }

    function report_viewer_bb1() {

        return;
    }

    function report_viewer_bb2() {

        return;
    }

    function report_viewer_by_selected_fields() {

        if (empty($this->request->data['ReportModuleReportViewer'])) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Report fields are not selected !'
            );
            $this->set(compact('msg'));

            return;
        }

        $allInfo = $this->request->data['ReportModuleReportViewer'];

        foreach ($allInfo as $report_id => $report_field_ids) {

            try {
                $this->loadModel('ReportModuleReportFieldDefinition');

                $condition = array('field_id' => $report_field_ids);
                $fields = array('field_id', 'field_detail', 'field_name', 'field_title', 'field_display_title', 'field_model', 'associated_field', 'associated_model', 'association_type', 'condition_for_association', 'aggregation_type', 'group_by_field', 'field_unit', 'field_unit_conversion_factor');
                $this->ReportModuleReportFieldDefinition->virtualFields['field_display_title'] = "CASE WHEN field_group_title IS NOT NULL AND TRIM(field_group_title) <> '' AND field_title IS NOT NULL AND TRIM(field_title) <> '' THEN CONCAT(field_group_title, ' (', field_title, ')') ELSE CONCAT_WS('', field_group_title, field_title) END";
                $all_field_details = $this->ReportModuleReportFieldDefinition->find('all', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 1, 'order' => 'field_id, field_sorting_order'));

                $report_field_model_list = Hash::extract($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_model');
                //debug($report_field_model_list);
                $report_field_model_list = array_unique(array_filter($report_field_model_list));
                //debug($report_field_model_list);
                //debug($all_field_details);
                $report_field_title_list = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_id', '{n}.ReportModuleReportFieldDefinition.field_display_title');
                //debug($report_field_title_list);


                $report_field_list = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_id', '{n}.ReportModuleReportFieldDefinition');
                //debug($report_field_list);

                $report_data = array();

                foreach ($report_field_list as $field_id => $field_details) {
                    if (empty($field_details))
                        continue;

                    //'field_id', 'field_detail', 'field_name', 'field_title',  'field_model', 'associated_field', 'associated_model', 'association_type', 'condition_for_association', 'aggregation_type', 'group_by_field'
                    $field_id = $field_details['field_id'];
                    $field_detail = $field_details['field_detail'];
                    $field_name = $field_details['field_name'];
                    $field_title = $field_details['field_title'];
                    $field_model = $field_details['field_model'];
                    $associated_field = $field_details['associated_field'];
                    $associated_model = $field_details['associated_model'];
                    $association_type = $field_details['association_type'];
                    $condition_for_association = $field_details['condition_for_association'];
                    $aggregation_type = $field_details['aggregation_type'];
                    $group_by_field = $field_details['group_by_field'];

                    $field_unit = $field_details['field_unit'];
                    $field_unit_conversion_factor = $field_details['field_unit_conversion_factor'];

                    if (empty($field_detail) || empty($field_name) || empty($field_model))
                        continue;

                    $condition = null;

                    $this->loadModel($field_model);

                    $field_value = $this->$field_model->find('first', array('fields' => array("$aggregation_type($field_detail) as $field_name"), 'conditions' => $condition, 'recursive' => -1, 'group' => $group_by_field));
                    if (isset($field_value[0][$field_name])) {
                        $field_value = $field_value[0][$field_name];
                        if (!empty($field_unit_conversion_factor))
                            $field_value = $field_value * $field_unit_conversion_factor;

                        if (is_float($field_value))
                            $field_value = number_format($field_value, 3);

                        if (!empty($field_unit))
                            $field_value .= " $field_unit";

                        $report_data[$field_id] = array($field_title => $field_value);
                        //$report_data[$field_id] = array($field_title => $field_value);
                    } else
                        $report_data[$field_id][$field_title] = null;
                }
            } catch (Exception $ex) {
                debug($ex->getMessage());
            }

            try {
                $report_header_list = array('Sl. No.', 'Particulars', 'As on June 2015');
                $report_details = array('report_data' => $report_data, 'report_header_list' => $report_header_list);

                if (empty($report_details)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Report data not available !'
                    );
                    $this->set(compact('msg'));

                    return;
                }
            } catch (Exception $ex) {
                debug($ex->getMessage());
            }

            $this->set(compact('report_details'));
        }
    }

    function report_viewer_by_selected_fields2() {

        if (empty($this->request->data['ReportModuleReportViewer'])) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => 'Report fields are not selected !'
            );
            $this->set(compact('msg'));

            return;
        }

        $allInfo = $this->request->data['ReportModuleReportViewer'];


        $this->loadModel('ReportModuleReportFieldDefinition');

        foreach ($allInfo as $report_id => $report_field_ids) {

            $condition = array('report_id' => $report_id);
            $report_details = $this->ReportModuleReportFieldDefinition->ReportModuleReportDefinition->find('first', array('conditions' => $condition, 'recursive' => 0, 'group' => 'report_id', 'order' => 'report_id'));

            debug($report_details);
            $base_model = $report_details['ReportModuleReportDefinition']['report_base_model'];
//            $report_list = Hash::combine($report_list, '{n}.ReportModuleReportDefinition.report_id', '{n}.ReportModuleReportDefinition');
//            $report_list = Hash::remove($report_list, '{n}.report_id');


            try {

                $condition = array('field_id' => $report_field_ids);
                $fields = array('field_id', 'field_detail', 'field_name', 'field_title', 'field_for_report', 'field_display_title', 'field_model', 'associated_field', 'associated_model', 'association_type', 'condition_for_association', 'aggregation_type', 'group_by_field', 'field_unit', 'field_unit_conversion_factor');
                $this->ReportModuleReportFieldDefinition->virtualFields['field_display_title'] = "CASE WHEN field_group_title IS NOT NULL AND TRIM(field_group_title) <> '' AND field_title IS NOT NULL AND TRIM(field_title) <> '' THEN CONCAT(field_group_title, ' (', field_title, ')') ELSE CONCAT_WS('', field_group_title, field_title) END";
                $this->ReportModuleReportFieldDefinition->virtualFields['field_for_report'] = "CASE WHEN field_model IS NOT NULL AND TRIM(field_model) <> '' AND field_detail IS NOT NULL AND TRIM(field_detail) <> '' THEN CONCAT_WS('.', field_model, field_detail) ELSE field_detail END";
                //$this->ReportModuleReportFieldDefinition->virtualFields['field_for_report'] = "CONCAT_WS('.', field_model, field_detail)";
                $all_field_details = $this->ReportModuleReportFieldDefinition->find('all', array('fields' => $fields, 'conditions' => $condition, 'recursive' => 1, 'order' => 'field_id, field_sorting_order'));

                //debug($all_field_details);
                if (!empty($aggregation_type)) {
                    $aggregated_model_name = '';
                    $aggregated_field_name = '';
                    $aggregation_type = '';
                    $group_by_field = '';
                    $aggregation_result = $this->$aggregated_model_name->find('first', array());
                }

                $report_field_model_list = Hash::extract($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_model');
                //debug($report_field_model_list);
                $report_field_model_list = array_unique(array_filter($report_field_model_list));

                $report_field_title_list = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_id', '{n}.ReportModuleReportFieldDefinition.field_display_title');

                $report_field_list = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_id', '{n}.ReportModuleReportFieldDefinition');
                debug($report_field_model_list);
                $report_fields = Hash::extract($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_for_report');
                //$report_fields = Hash::combine($all_field_details, '{n}.ReportModuleReportFieldDefinition.field_id', '{n}.ReportModuleReportFieldDefinition.field_display_title');

                debug($report_fields);
                //debug($report_field_list);
                $this->loadModel($base_model);

                $this->$base_model->virtualFields['name_of_org'] = $this->$base_model->BasicModuleBasicInformation->virtualFields['name_of_org'];


                $report_values = $this->$base_model->find('all', array('fields' => $report_fields));
                debug($report_values);
                $aggregation_result;
                exit;


                if (empty($report_field_model_list) || count($report_field_model_list) < 1)
                    return;


                $has_basic = false;
                if (in_array("BasicModuleBasicInformation", $report_field_model_list)) {
                    $has_basic = true;
                    unset($report_field_model_list["BasicModuleBasicInformation"]);
                }

                $report_data = array();

                if (count($report_field_model_list) > 0) {
                    try {

                        foreach ($report_field_model_list as $model_id => $model_name) {
                            if (empty($model_name))
                                continue;

                            $this->loadModel($field_model);
                            $field_values = $this->$field_model->find('all', array('fields' => array("$aggregation_type($field_detail) as $field_name"), 'conditions' => $condition, 'recursive' => -1, 'group' => 'org_id'));

                            continue;

                            //'field_id', 'field_detail', 'field_name', 'field_title',  'field_model', 'associated_field', 'associated_model', 'association_type', 'condition_for_association', 'aggregation_type', 'group_by_field'
                            $field_id = $field_details['field_id'];
                            $field_detail = $field_details['field_detail'];
                            $field_name = $field_details['field_name'];
                            $field_title = $field_details['field_title'];
                            $field_model = $field_details['field_model'];
                            $associated_field = $field_details['associated_field'];
                            $associated_model = $field_details['associated_model'];
                            $association_type = $field_details['association_type'];
                            $condition_for_association = $field_details['condition_for_association'];
                            $aggregation_type = $field_details['aggregation_type'];
                            $group_by_field = $field_details['group_by_field'];

                            $field_unit = $field_details['field_unit'];
                            $field_unit_conversion_factor = $field_details['field_unit_conversion_factor'];

                            if (empty($field_detail) || empty($field_name) || empty($field_model))
                                continue;

                            $condition = null;

                            $this->loadModel($field_model);

                            $field_value = $this->$field_model->find('first', array('fields' => array("$aggregation_type($field_detail) as $field_name"), 'conditions' => $condition, 'recursive' => -1, 'group' => $group_by_field));
                            if (isset($field_value[0][$field_name])) {
                                $field_value = $field_value[0][$field_name];
                                if (!empty($field_unit_conversion_factor))
                                    $field_value = $field_value * $field_unit_conversion_factor;

                                if (is_float($field_value))
                                    $field_value = number_format($field_value, 3);

                                if (!empty($field_unit))
                                    $field_value .= " $field_unit";

                                $report_data[$field_id] = array($field_title => $field_value);
                                //$report_data[$field_id] = array($field_title => $field_value);
                            } else
                                $report_data[$field_id][$field_title] = null;
                        }
                    } catch (Exception $ex) {
                        debug($ex->getMessage());
                    }
                }



                $this->loadModel('BasicModuleBasicInformation'); //
                $this->BasicModuleBasicInformation->recursive = 1;
                $orgs = $this->BasicModuleBasicInformation->find('all'); //, array('fields' => $report_fields, 'conditions' => $condition));

                debug($orgs);

                $org_ids = $this->BasicModuleBasicInformation->find('list', array('fields' => array('id', 'id'), 'recursive' => -1));

                debug($org_ids);


                $report_data = array();

                foreach ($report_field_list as $field_id => $field_details) {
                    if (empty($field_details))
                        continue;

                    //'field_id', 'field_detail', 'field_name', 'field_title',  'field_model', 'associated_field', 'associated_model', 'association_type', 'condition_for_association', 'aggregation_type', 'group_by_field'
                    $field_id = $field_details['field_id'];
                    $field_detail = $field_details['field_detail'];
                    $field_name = $field_details['field_name'];
                    $field_title = $field_details['field_title'];
                    $field_model = $field_details['field_model'];
                    $associated_field = $field_details['associated_field'];
                    $associated_model = $field_details['associated_model'];
                    $association_type = $field_details['association_type'];
                    $condition_for_association = $field_details['condition_for_association'];
                    $aggregation_type = $field_details['aggregation_type'];
                    $group_by_field = $field_details['group_by_field'];

                    $field_unit = $field_details['field_unit'];
                    $field_unit_conversion_factor = $field_details['field_unit_conversion_factor'];

                    if (empty($field_detail) || empty($field_name) || empty($field_model))
                        continue;

                    $condition = null;

                    $this->loadModel($field_model);

                    $field_value = $this->$field_model->find('first', array('fields' => array("$aggregation_type($field_detail) as $field_name"), 'conditions' => $condition, 'recursive' => -1, 'group' => $group_by_field));
                    if (isset($field_value[0][$field_name])) {
                        $field_value = $field_value[0][$field_name];
                        if (!empty($field_unit_conversion_factor))
                            $field_value = $field_value * $field_unit_conversion_factor;

                        if (is_float($field_value))
                            $field_value = number_format($field_value, 3);

                        if (!empty($field_unit))
                            $field_value .= " $field_unit";

                        $report_data[$field_id] = array($field_title => $field_value);
                        //$report_data[$field_id] = array($field_title => $field_value);
                    } else
                        $report_data[$field_id][$field_title] = null;
                }
            } catch (Exception $ex) {
                debug($ex->getMessage());
            }

            try {
                $report_header_list = array('Sl. No.', 'Particulars', 'As on June 2015');
                $report_details = array('report_data' => $report_data, 'report_header_list' => $report_header_list);

                if (empty($report_details)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Report data not available !'
                    );
                    $this->set(compact('msg'));

                    return;
                }
            } catch (Exception $ex) {
                debug($ex->getMessage());
            }

            $this->set(compact('report_details'));
        }
    }

}
