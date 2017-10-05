<?php

App::uses('AppController', 'Controller');

class AdminModuleDynamicNonMfiCrudFormGeneratorsController extends AppController {

    public $helpers = array('Html', 'Form', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');
    public $org_name = "";

    public function add_multi($model_id = null, $is_edit = null) {

        $this->loadModel('LookupModelFieldDefinition');
        $this->loadModel('AdminModulePeriodDetail');

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $data_type_id = $this->Session->read('AdminModulePeriodDataType.DataTypeId');
        $org_id = $this->Session->read('Org.Id');


        if ((empty($org_id) && !empty($user_group_ids) && !in_array(1, $user_group_ids)) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        //$period_id = $this->AdminModulePeriodDetail->find('list', array('fields' => array('id', 'period_id'), 'conditions' => array('AdminModulePeriodDetail.is_current_period' => 1, 'AdminModulePeriodDetail.data_type_id' => $data_type_id), 'recursive' => -1));
        $period_id = $this->AdminModulePeriodDetail->field('period_id', array('AdminModulePeriodDetail.is_current_period' => 1, 'AdminModulePeriodDetail.data_type_id' => $data_type_id));

        $model_details = $this->LookupModelFieldDefinition->LookupModelDefinition->find('first', array('fields' => array('model_name', 'model_description', 'cat_group_id'), 'conditions' => array('LookupModelDefinition.id' => $model_id), 'recursive' => -1));
        if (empty($model_details) || empty($model_details['LookupModelDefinition'])) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid model information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $title = $model_details['LookupModelDefinition']['model_description'];
        $model_name = $model_details['LookupModelDefinition']['model_name'];
        $cat_group_id = $model_details['LookupModelDefinition']['cat_group_id'];

        $this->loadModel($model_name);
        if ($this->request->is('post') && !empty($this->request->data[$model_name])) {
            $basic_data = $this->request->data['basicInfo'];
            $all_mandatory_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('field_name', 'field_title_to_view_in_crud'), 'conditions' => array('model_id' => $model_id, 'is_mandatory_for_add' => 1), 'recursive' => -1));
            if (!empty($all_mandatory_fields) && count($all_mandatory_fields) > 0) {
                foreach ($all_mandatory_fields as $field_name => $field_title) {
                    $this->$model_name->validate[$field_name] = array(
                        'required' => true,
                        'allowEmpty' => false,
                        'rule' => array('notBlank'),
                        'on' => 'null',
                        'message' => "$field_title is required"
                    );
                }
            }

            $base_is_ok = true;
            if (!empty($basic_data)) {
                foreach ($basic_data as $basic_field_name => $b_data) {
                    if (isset($all_mandatory_fields[$basic_field_name]) && empty($b_data)) {

                        $base_is_ok = false;
                        $this->request->data = $this->request->data;
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => "Invalid/Incomplete data in top !"
                        );
                        $data_index = -1;
                        $this->set(compact('data_index', 'msg'));
                        break;
                    }
                }
            }

            if ($base_is_ok) {
                $reqAllData = $this->request->data[$model_name];

                //debug($reqAllData);
                $cat_level = isset($this->request->data['FormInfo']['cat_level']) ? $this->request->data['FormInfo']['cat_level'] : 0;

                $rc = 0;
                $data_index = '-1';
                $forSaveDatas = array();
                $all_is_ok = true;
                foreach ($reqAllData as $data_index => $reqData) {
                    $rc++;
                    if (empty($reqData) || !is_array($reqData))
                        continue;
                    if (!array_filter($reqData) || count(array_filter($reqData)) < $cat_level + 1)
                        continue;

                    if ($basic_data)
                        $reqData = array_merge($basic_data, $reqData);

                    $this->$model_name->set($reqData);
                    if ($this->$model_name->validates()) {
                        $forSaveDatas[] = $reqData;
//                        debug($reqData);
//                    $this->$model_name->create();
//                    $this->$model_name->save($reqData);
                    } else {
                        //debug($reqData);
                        //debug($this->$model_name->validationErrors);
                        //debug($this->$model_name->invalidFields());
                        $all_is_ok = false;
                        $this->request->data = $this->request->data;
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => "Invalid/Incomplete data at Row No.: $rc."
                        );
                        $this->set(compact('data_index', 'msg', 'is_edit'));
                        break;
                    }
                }

                if ($all_is_ok) {
                    try {
                        if (!empty($period_id) && !empty($org_id)) {
                            $conditions = array("$model_name.period_id" => $period_id, "$model_name.org_id" => $org_id);
                            if ($this->$model_name->hasField('submission_status'))
                                $conditions["$model_name.submission_status !="] = 1;

                            $this->$model_name->recursive = -1;
                            $this->$model_name->deleteAll($conditions, false);
                        }
                    } catch (Exception $ex) {
                        ////debug($ex->getMessage());
                    }

                    if (count($forSaveDatas) > 0) {
                        $this->$model_name->create();
                        $isDone = $this->$model_name->saveAll($forSaveDatas);
                        //debug($isDone);
                        if ($isDone) {
                            $is_submit = $this->Session->read('SubmissionStatus.IsSubmit');
                            if (!empty($is_submit) && $is_submit == '1') {
                                $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view_for_final_submission?model_id=' . $model_id . '&data_type_id=' . $data_type_id . '&is_submit=' . $is_submit));
                            } else {
                                $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view?model_id=' . $model_id . '&data_type_id=' . $data_type_id));
                            }
                            return;
                        }
                    } else {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => "No data to save !"
                        );
                        $this->set(compact('msg', 'is_edit'));
                    }
                }
            }
        }

        $from_date = '';
        $to_date = '';

        $month_list = array();
        if (!empty($period_id)) {
            $fields = array('id', 'from_date', 'to_date', 'period', 'as_on');
            $period_details = $this->AdminModulePeriodDetail->AdminModulePeriodList->find('first', array('fields' => $fields, 'conditions' => array('id' => $period_id), 'recursive' => -1));
            if (!empty($period_details['AdminModulePeriodList'])) {
                $period_id = $period_details['AdminModulePeriodList']['id'];
                $period = $period_details['AdminModulePeriodList']['as_on'];
                $this->set(compact('period_id', 'period'));

                $from_date = $period_details['AdminModulePeriodList']['from_date'];
                $to_date = $period_details['AdminModulePeriodList']['to_date'];

//                debug($period_details);

                $start = new DateTime($from_date);
                $end = new DateTime($to_date);
                $interval = new DateInterval('P1M');

                $month_period = new DatePeriod($start, $interval, $end);
                foreach ($month_period as $month_date) {
                    $month_list[$month_date->format('M')] = $month_date->format('M-Y');
                    //$month_list[$month_date->format('M')] = $month_date->format('F-Y');
                }
//                debug($month_list);
//                $first = strtotime($from_date);
//                $months = array();
//                for ($i = 6; $i >= 1; $i--) {
//                    array_push($months, date('M', strtotime("-$i month", $first)));
//                }
//                debug($months);
//
//                $months = array();
//                for ($i = 0; $i < 6; $i++) {
//                    $months[] = date("F Y", strtotime(date('Y-m-01') . " -$i months"));
//                }
//                debug($months);
            }
        } elseif (empty($period_id) && (!empty($user_group_ids) && in_array(3, $user_group_ids))) {
            $redirect_url = array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add', $model_id);
            $this->Session->write('Current.RedirectUrl', $redirect_url);

            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Data Period Not Yet Set!'
            );
            $this->set(compact('msg'));
            return;
        }

        $fields = array('id', 'model_id', 'child_model_id', 'parent_model_id', 'parent_field_id',
            'field_group_id', 'field_sub_group_id', 'field_name', 'field_description', 'field_sub_title_for_report',
            'data_type', 'field_size', 'field_title_to_view_in_crud',
            'control_type_for_add', 'is_mandatory_for_add', 'containable_model_names',
            'is_group_title_in_one_to_many', 'has_notes', 'model_name_for_select_option',
            'dropdown_display_field', 'dropdown_value_field', 'dropdown_condition_field',
            'model_name_for_dependent_select_option', 'dependent_dropdown_display_field', 'dependent_dropdown_condition_field',
            'dependent_dropdown_value_field', 'parent_or_child_control_id');

        $all_fields_details = $this->LookupModelFieldDefinition->find('all', array('fields' => $fields, 'conditions' => array('model_id' => $model_id, 'display_in_add_page' => 1), 'recursive' => -1, 'order' => array('field_sorting_order' => 'asc')));
        if (!empty($all_fields_details)) {

            //debug($all_fields_details);
            $new_field_details = array();
            $field_details_list = array();
            foreach ($all_fields_details as $field_details) {
                $field_details = $field_details['LookupModelFieldDefinition'];

                $field_id = $field_details['id'];
                $field_group_id = isset($field_details['field_group_id']) ? $field_details['field_group_id'] : -1;
                $field_sub_group_id = isset($field_details['field_sub_group_id']) ? $field_details['field_sub_group_id'] : -1;

                $new_field_details['field_name'] = $field_name = $field_details['field_name'];

                $new_field_details['child_model_id'] = $field_details['child_model_id'];
                $new_field_details['field_label'] = $field_details['field_title_to_view_in_crud'];
                $new_field_details['control_type'] = $control_type = $field_details['control_type_for_add'];
                $new_field_details['is_mandatory_for_add'] = $is_mandatory_for_add = $field_details['is_mandatory_for_add'];
                $new_field_details['data_type'] = $field_details['data_type'];
                $new_field_details['parent_or_child_control_id'] = $field_details['parent_or_child_control_id'];

                $new_field_details['has_notes'] = $has_notes = (!empty($field_details['has_notes']) && $field_details['has_notes'] == '1');
                $new_field_details['is_note_added'] = false;

                if ($field_name == 'from_date') {
                    $date_value = $from_date;
                } elseif ($field_name == 'to_date') {
                    $date_value = $to_date;
                } else {
                    $date_value = '';
                }
                $new_field_details['date_value'] = $date_value;

                if ($field_name == 'org_id') {
                    $field_value_from_session = $org_id;
                } else {
                    $field_value_from_session = '';
                }
                $new_field_details['field_value_from_session'] = $field_value_from_session;
                if ($control_type == "current_date") {
                    $current_date = date('Y-m-d');
                } else {
                    $current_date = '';
                }
                $new_field_details['current_date'] = $current_date;

                $labels = array();
                $options = array();
                switch ($control_type) {
                    case "select":
                    case "select_or_label":
                    case "dependent_dropdown":
                    case "checkbox":
                    case "radio": {

                            if (($field_name == 'month_id' || $field_name == 'month_code') && !empty($month_list)) {
                                $options = $month_list;
                                break;
                            }

                            $select_option_model = $field_details['model_name_for_select_option'];
                            $dropdown_display_field = $field_details['dropdown_display_field'];
                            $dropdown_value_field = $field_details['dropdown_value_field'];
                            $dropdown_condition_field = $field_details['dropdown_condition_field'];

                            $containable_model_names = $field_details['containable_model_names'];
                            $this->loadModel($select_option_model);
                            $fields = array("$select_option_model.$dropdown_value_field", "$select_option_model.$dropdown_display_field");
                            $containable_model_name_list = explode(',', $containable_model_names);

                            $order_by = $fields[1];
                            $value_exist_conditions = array();

                            if ($field_name == 'org_id' && !empty($org_id) && !empty($dropdown_value_field)) {
                                //$order_by = $fields[1];
                                $value_exist_conditions["$select_option_model.$dropdown_value_field"] = $org_id;
                            }

                            if (!empty($select_option_model) && !empty($fields)) {
                                if ($control_type == "dependent_dropdown") {
                                    if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                        $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => 0, 'order' => $order_by)); //array($fields[2] => $org_id)
                                    } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                        $dependent_dropdown_condition_field = $field_details['dependent_dropdown_condition_field'];
                                        $fields[2] = "$select_option_model.$dependent_dropdown_condition_field";
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'contain' => $containable_model_name_list, 'recursive' => 0, 'order' => $order_by));
//                                $options_list = $this->$select_option_model->find('all', array('fields' => $fields, 'contain' => $containable_model_name_list, 'recursive' => -1, 'order' => $order_by));
//                                $options = Hash::combine($options_list, "{n}.$select_option_model.$dropdown_value_field", "{n}.$select_option_model.$dropdown_display_field", "{n}.$select_option_model.$dependent_dropdown_condition_field");
                                    } elseif (!empty($dropdown_condition_field) && !empty($containable_model_name_list) && !empty($org_id)) {
                                        $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'contain' => $containable_model_name_list, 'recursive' => 0, 'order' => $order_by));
                                    } else {
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => 0, 'order' => $order_by));
                                    }
                                }

                                if ($control_type != "dependent_dropdown") {
                                    if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                        $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => 0, 'order' => $order_by));
                                    } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => 0, 'order' => $order_by, 'contain' => $containable_model_name_list));
                                    } elseif (!empty($dropdown_condition_field) && !empty($containable_model_name_list) && !empty($org_id)) {
                                        $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => 0, 'order' => $order_by, 'contain' => $containable_model_name_list));
                                    } else {
                                        $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => 0, 'order' => $order_by));
                                    }
                                }
                            }
                        }
                        break;

                    case "label": {
                            $associated_model_name = $field_details['associated_model_name'];
                            $associated_field_name_to_show = $field_details['associated_field_name_to_show'];
                        }
                        break;

                    default :
                        break;
                }

                $new_field_details['options'] = $options;
                $new_field_details['label'] = $labels;

                $field_details_list[$field_group_id][$field_id] = $new_field_details;
            }

            $field_groups = $this->LookupModelFieldDefinition->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => array('LookupModelFieldGroup.model_id' => $model_id), 'recursive' => -1));

//            $cat_group_id = $model_id . '01';
            $this->set(compact('title', 'model_id', 'model_name', 'cat_group_id', 'data_type_id', 'field_groups', 'field_details_list'));

            //$is_edit = false;
            if (!empty($cat_group_id))
                $cat_fields = Hash::extract($field_details_list[$cat_group_id], "{n}.field_name");
            else
                $cat_fields = null;

            if (!$this->request->is('post') && !empty($cat_fields)) {
                $conditions = array();
                $conditions['period_id'] = $period_id;
                $conditions['org_id'] = $org_id;
                if ($this->$model_name->hasField('submission_status')) {
                    $conditions['submission_status !='] = '1';
                }

                $all_data = $this->$model_name->find('all', array('conditions' => $conditions, 'recursive' => -1));

                $posted_data = array();
                foreach ($all_data as $data) {
                    $data = $data[$model_name];
                    $data_key = '';
                    foreach ($cat_fields as $cat_field) {
                        $data_key .= $data[$cat_field];
                    }
                    $posted_data[$data_key] = $data;
                }

                if (!empty($posted_data)) {
                    $is_edit = true;
                    $posted_data = array($model_name => $posted_data);
                    $this->request->data = $posted_data;
                }
            }
            $this->set(compact('is_edit'));
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid field definition !'
            );
            $this->set(compact('msg', 'is_edit'));
            return;
        }
    }

    public function add_multi_cat($model_id = null, $cat_id = null, $is_edit = null) {

        $this->loadModel('LookupModelFieldDefinition');
        $this->loadModel('AdminModulePeriodDetail');

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $data_type_id = $this->Session->read('AdminModulePeriodDataType.DataTypeId');
        $org_id = $this->Session->read('Org.Id');

        $branch_office_type_id = 3;

        if (empty($cat_id))
            $cat_id = $this->Session->read('Org.DataCatId');
        else
            $this->Session->write('Org.DataCatId', $cat_id);

        if ((empty($org_id) && !empty($user_group_ids) && !in_array(1, $user_group_ids)) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        //$period_id = $this->AdminModulePeriodDetail->find('list', array('fields' => array('id', 'period_id'), 'conditions' => array('AdminModulePeriodDetail.is_current_period' => 1, 'AdminModulePeriodDetail.data_type_id' => $data_type_id), 'recursive' => -1));
        $period_id = $this->AdminModulePeriodDetail->field('period_id', array('AdminModulePeriodDetail.is_current_period' => 1, 'AdminModulePeriodDetail.data_type_id' => $data_type_id));

        $model_details = $this->LookupModelFieldDefinition->LookupModelDefinition->find('first', array('fields' => array('model_name', 'model_description', 'cat_group_id'), 'conditions' => array('LookupModelDefinition.id' => $model_id), 'recursive' => -1));
        if (empty($model_details) || empty($model_details['LookupModelDefinition'])) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid model information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $title = $model_details['LookupModelDefinition']['model_description'];
        $model_name = $model_details['LookupModelDefinition']['model_name'];
        $cat_group_id = $model_details['LookupModelDefinition']['cat_group_id'];

        if (!empty($cat_group_id)) {
            $cat_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('id', 'field_name'), 'conditions' => array('model_id' => $model_id, 'field_group_id' => $cat_group_id, 'display_in_add_page' => 1), 'recursive' => -1, 'order' => array('field_sorting_order' => 'asc')));
            if (!empty($cat_fields))
                $cat_fields = array_values($cat_fields);
        }

        $this->loadModel($model_name);
        if ($this->request->is('post') && !empty($this->request->data[$model_name])) {
            $basic_data = $this->request->data['basicInfo'];
            $all_mandatory_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('field_name', 'field_title_to_view_in_crud'), 'conditions' => array('model_id' => $model_id, 'is_mandatory_for_add' => 1), 'recursive' => -1));
            if (!empty($all_mandatory_fields) && count($all_mandatory_fields) > 0) {
                foreach ($all_mandatory_fields as $field_name => $field_title) {
                    $this->$model_name->validate[$field_name] = array(
                        'required' => true,
                        'allowEmpty' => false,
                        'rule' => array('notBlank'),
                        'on' => 'null',
                        'message' => "$field_title is required"
                    );
                }
            }

            $base_is_ok = true;
            if (!empty($basic_data)) {
                foreach ($basic_data as $basic_field_name => $b_data) {
                    if (isset($all_mandatory_fields[$basic_field_name]) && empty($b_data)) {

                        $base_is_ok = false;
                        $this->request->data = $this->request->data;
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => "Invalid/Incomplete data in top !"
                        );
                        $data_index = -1;
                        $this->set(compact('data_index', 'msg', 'is_edit'));
                        break;
                    }
                }
            }

            if ($base_is_ok) {
                $reqAllData = $this->request->data[$model_name];

                $cat_level = isset($this->request->data['FormInfo']['cat_level']) ? $this->request->data['FormInfo']['cat_level'] : 0;

                $rc = 0;
                $data_index = '-1';
                $forSaveDatas = array();
                $all_is_ok = true;
                foreach ($reqAllData as $data_index => $reqData) {
                    $rc++;
                    if (empty($reqData) || !is_array($reqData))
                        continue;
                    if (!array_filter($reqData) || count(array_filter($reqData)) < $cat_level + 1)
                        continue;

                    if ($basic_data)
                        $reqData = array_merge($basic_data, $reqData);

                    $this->$model_name->set($reqData);
                    if ($this->$model_name->validates()) {
                        $forSaveDatas[] = $reqData;
                    } else {
                        $all_is_ok = false;
                        $this->request->data = $this->request->data;
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => "Invalid/Incomplete data at Row No.: $rc."
                        );
                        $this->set(compact('data_index', 'msg', 'is_edit'));
                        break;
                    }
                }

                if ($all_is_ok) {
                    try {
                        if (!empty($period_id) && !empty($org_id)) {
                            $conditions = array("$model_name.period_id" => $period_id, "$model_name.org_id" => $org_id);
                            if (!empty($cat_fields[0]) && !empty($cat_id))
                                $conditions ["$model_name.$cat_fields[0]"] = $cat_id;
                            if ($this->$model_name->hasField('submission_status'))
                                $conditions["$model_name.submission_status !="] = 1;

                            $this->$model_name->recursive = -1;
                            $this->$model_name->deleteAll($conditions, false);
                        }
                    } catch (Exception $ex) {
                        //debug($ex->getMessage());
                    }

                    if (count($forSaveDatas) > 0) {
                        try {
                            $this->$model_name->create();
                            $isDone = $this->$model_name->saveAll($forSaveDatas);

                            if ($isDone) {
                                $is_submit = $this->Session->read('SubmissionStatus.IsSubmit');
                                if (!empty($is_submit) && $is_submit == '1') {
                                    $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view_for_final_submission?model_id=' . $model_id . '&data_type_id=' . $data_type_id . '&is_submit=' . $is_submit));
                                } else {
                                    $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view?model_id=' . $model_id . '&data_type_id=' . $data_type_id));
                                }
                                return;
                            }
                        } catch (Exception $ex) {
                            //debug($ex->getMessage());
                        }
                    } else {
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => "No data to save !"
                        );
                        $this->set(compact('msg', 'is_edit'));
                    }
                }
            }
        }

        $from_date = '';
        $to_date = '';

        if (!empty($period_id)) {
            $fields = array('id', 'from_date', 'to_date', 'period', 'as_on');
            $period_details = $this->AdminModulePeriodDetail->AdminModulePeriodList->find('first', array('fields' => $fields, 'conditions' => array('id' => $period_id), 'recursive' => -1));
            if (!empty($period_details['AdminModulePeriodList'])) {
                $period_id = $period_details['AdminModulePeriodList']['id'];
                $period = $period_details['AdminModulePeriodList']['as_on'];
                $this->set(compact('period_id', 'period'));

                $from_date = $period_details['AdminModulePeriodList']['from_date'];
                $to_date = $period_details['AdminModulePeriodList']['to_date'];
            }
        } elseif (empty($period_id) && (!empty($user_group_ids) && in_array(3, $user_group_ids))) {
            $redirect_url = array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add', $model_id);
            $this->Session->write('Current.RedirectUrl', $redirect_url);

            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Data Period Not Yet Set!'
            );
            $this->set(compact('msg'));
            return;
        }
        //$this->set(compact('model_id', 'org_id', 'cat_id', 'data_type_id'));

        $this->loadModel('BasicModuleBranchInfo');
        $this->BasicModuleBranchInfo->virtualFields['branch_with_address'] = $this->branch_with_code_address;

        $fields = array('id', 'model_id', 'child_model_id', 'parent_model_id', 'parent_field_id',
            'field_group_id', 'field_sub_group_id', 'field_name', 'field_description', 'field_sub_title_for_report',
            'data_type', 'field_size', 'field_title_to_view_in_crud',
            'control_type_for_add', 'is_mandatory_for_add', 'containable_model_names',
            'is_group_title_in_one_to_many', 'has_notes', 'model_name_for_select_option',
            'dropdown_display_field', 'dropdown_value_field', 'dropdown_condition_field',
            'model_name_for_dependent_select_option', 'dependent_dropdown_display_field', 'dependent_dropdown_condition_field',
            'dependent_dropdown_value_field', 'parent_or_child_control_id');

        $all_fields_details = $this->LookupModelFieldDefinition->find('all', array('fields' => $fields, 'conditions' => array('model_id' => $model_id, 'display_in_add_page' => 1), 'recursive' => -1, 'order' => array('field_sorting_order' => 'asc')));
        if (!empty($all_fields_details)) {
            $new_field_details = array();
            $field_details_list = array();

//            $cat_fields = Hash::extract($all_fields_details, "{n}.LookupModelFieldDefinition[field_group_id=$cat_group_id].field_name");

            $conditions = array("$model_name.org_id" => $org_id, "$model_name.period_id" => $period_id, "$model_name.period_id" => $period_id);
            if (!empty($cat_fields[0]) && !empty($cat_id))
                $conditions ["$model_name.$cat_fields[0]"] = $cat_id;

            foreach ($all_fields_details as $field_details) {
                $field_details = $field_details['LookupModelFieldDefinition'];

                $field_id = $field_details['id'];
                $field_group_id = isset($field_details['field_group_id']) ? $field_details['field_group_id'] : -1;
                $field_sub_group_id = isset($field_details['field_sub_group_id']) ? $field_details['field_sub_group_id'] : -1;

                $new_field_details['field_name'] = $field_name = $field_details['field_name'];

                $new_field_details['child_model_id'] = $field_details['child_model_id'];
                $new_field_details['field_label'] = $field_details['field_title_to_view_in_crud'];
                $new_field_details['control_type'] = $control_type = $field_details['control_type_for_add'];
                $new_field_details['is_mandatory_for_add'] = $is_mandatory_for_add = $field_details['is_mandatory_for_add'];
                $new_field_details['data_type'] = $field_details['data_type'];
                $new_field_details['parent_or_child_control_id'] = $field_details['parent_or_child_control_id'];

                $new_field_details['has_notes'] = $has_notes = (!empty($field_details['has_notes']) && $field_details['has_notes'] == '1');
                $new_field_details['is_note_added'] = false;

                if ($field_name == 'from_date') {
                    $date_value = $from_date;
                } elseif ($field_name == 'to_date') {
                    $date_value = $to_date;
                } else {
                    $date_value = '';
                }
                $new_field_details['date_value'] = $date_value;

                if ($field_name == 'org_id') {
                    $field_value_from_session = $org_id;
                } else {
                    $field_value_from_session = '';
                }
                $new_field_details['field_value_from_session'] = $field_value_from_session;
                if ($control_type == "current_date") {
                    $current_date = date('Y-m-d');
                } else {
                    $current_date = '';
                }
                $new_field_details['current_date'] = $current_date;

                $labels = array();
                $options = array();
                switch ($control_type) {

                    case "label": {
                            $associated_model_name = $field_details['associated_model_name'];
                            $associated_field_name_to_show = $field_details['associated_field_name_to_show'];
                        }
                        break;

                    case "radio":
                    case "select":
                    case "checkbox":
                    case "select_or_label":
                    case "dependent_dropdown":
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

                        if ($field_name == 'org_id' && !empty($org_id) && !empty($dropdown_value_field)) {
                            $order_by = $fields[1];
                            $value_exist_conditions["$select_option_model.$dropdown_value_field"] = $org_id;
                        }

                        if (!empty($select_option_model) && !empty($fields)) {
                            if ($control_type == "dependent_dropdown") {
                                if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'recursive' => -1, 'order' => $order_by)); //array($fields[2] => $org_id)
                                } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                    $dependent_dropdown_condition_field = $field_details['dependent_dropdown_condition_field'];
                                    $fields[2] = "$select_option_model.$dependent_dropdown_condition_field";
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'contain' => $containable_model_name_list, 'recursive' => -1, 'order' => $order_by));

//                                $options_list = $this->$select_option_model->find('all', array('fields' => $fields, 'contain' => $containable_model_name_list, 'recursive' => -1, 'order' => $order_by));
//                                $options = Hash::combine($options_list, "{n}.$select_option_model.$dropdown_value_field", "{n}.$select_option_model.$dropdown_display_field", "{n}.$select_option_model.$dependent_dropdown_condition_field");
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
                        break;

                    default :
                        break;
                }

                $new_field_details['label'] = $labels;
                $new_field_details['options'] = $options;

                $field_details_list[$field_group_id][$field_id] = $new_field_details;
            }

            $field_groups = $this->LookupModelFieldDefinition->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => array('LookupModelFieldGroup.model_id' => $model_id), 'recursive' => -1));

            $this->set(compact('title', 'model_id', 'model_name', 'org_id', 'cat_id', 'cat_group_id', 'data_type_id', 'field_groups', 'field_details_list'));

            //$is_edit = false;
            if (!$this->request->is('post') && !empty($cat_fields) && !empty($cat_fields[0]) && !empty($cat_id)) {
                $conditions = array();
                $conditions['period_id'] = $period_id;
                $conditions['org_id'] = $org_id;
                $conditions[$cat_fields[0]] = $cat_id;

                if ($this->$model_name->hasField('submission_status')) {
                    $conditions['submission_status !='] = '1';
                }

                $all_data = $this->$model_name->find('all', array('conditions' => $conditions, 'recursive' => -1));

                $posted_data = array();
                foreach ($all_data as $data) {
                    $data = $data[$model_name];
                    $data_key = '';
                    foreach ($cat_fields as $cat_field) {
                        $data_key .= $data[$cat_field];
                    }
                    $posted_data[$data_key] = $data;
                }

                if (!empty($posted_data)) {
                    $is_edit = true;
                    $posted_data = array($model_name => $posted_data);
                    $this->request->data = $posted_data;
                }
            }

            $this->set(compact('is_edit'));
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid field definition !'
            );
            $this->set(compact('msg', 'is_edit'));
            return;
        }
    }

    public function view($opt = null) {
        $this->Session->write('Org.DataCatId', null);

        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_type = $this->Session->read('User.Type');
        $user_group_ids = $this->Session->read('User.GroupIds');

        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $opt_all = false;
        if ($user_group_ids && $user_group_ids == 1) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }
        $hide_add = $this->request->query('hide_add');
        $hide_edit = $this->request->query('hide_edit');
        $hide_delete = $this->request->query('hide_delete');

        if (!empty($hide_add)) {
            $this->Session->write('Status.IsAdd', $hide_add);
        } else {
            $this->Session->write('Status.IsAdd', null);
        }
        if (!empty($hide_edit)) {
            $this->Session->write('Status.IsEdit', $hide_edit);
        } else {
            $this->Session->write('Status.IsEdit', null);
        }
        if (!empty($hide_delete)) {
            $this->Session->write('Status.IsDelete', $hide_delete);
        } else {
            $this->Session->write('Status.IsDelete', null);
        }

        $org_id = $this->Session->read('Org.Id');

        $title = '';
        $model_name = '';
        $form_name = '';
        $search_options = array();
        $lookup_or_detail_id = '';
        $values = array();
        $primary_key_field_name = '';
        if (!$this->request->is('post')) {
            if (!empty($this->request->query['model_id'])) {
                $this->Session->write('LookupModelDefinition.ModelId', $this->request->query['model_id']);
            } else {
                $this->Session->write('LookupModelDefinition.ModelId', '');
            }
            if (!empty($this->request->query['data_type_id'])) {
                $this->Session->write('AdminModulePeriodDataType.DataTypeId', $this->request->query['data_type_id']);
            } else {
                $this->Session->write('AdminModulePeriodDataType.DataTypeId', '');
            }
            if (!empty($this->request->query['is_submit'])) {
                $this->Session->write('SubmissionStatus.IsSubmit', $this->request->query['is_submit']);
            } else {
                $this->Session->write('SubmissionStatus.IsSubmit', '');
            }
        }

        $model_id = $this->Session->read('LookupModelDefinition.ModelId');
        $model_details = $this->LookupModelDefinition->find('first', array('conditions' => array('LookupModelDefinition.id' => $model_id)));
        if (!empty($model_details)) {
            $model_description = $model_details['LookupModelDefinition']['model_description'];
            $title = $model_description;
            $model_name = $model_details['LookupModelDefinition']['model_name'];
            $value_exist_conditions = array();
            $submitted_condition = array();

            $this->loadModel($model_name);

            $primary_key_field_name = $this->LookupModelFieldDefinition->field('field_name', array('model_id' => $model_id, 'is_primary_key' => 1));
            $field_values = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('model_id' => $model_id), 'recursive' => -1));

            $conditions = array('model_id' => $model_id, 'display_in_view_page' => 1);

            if (!empty($org_id))
                $conditions['NOT'] = array('field_name' => 'org_id');

            $fields_to_display_in_view = $this->LookupModelFieldDefinition->find('all', array(
                'fields' => array('field_name', 'field_description', 'associated_field_name_to_show', 'associated_model_name', 'is_primary_key'),
                'conditions' => $conditions, //array('model_id' => $model_id, 'display_in_view_page' => 1), // array('NOT' => array('field_name' => 'org_id')), array('NOT' => array('field_name' => 'period_id'))),
                'recursive' => -1
            ));

            $fields = array();
            if (!empty($primary_key_field_name))
                $fields[] = "$model_name.$primary_key_field_name";

            //debug($fields_to_display_in_view);
            $field_list = array();
            foreach ($fields_to_display_in_view as $field_to_display) {
                if (empty($field_to_display['LookupModelFieldDefinition']))
                    continue;

                $field_to_display = $field_to_display['LookupModelFieldDefinition'];
                $field_name = $field_to_display['field_name'];
                $field_title = $field_to_display['field_description'];
                $asso_model_name = $field_to_display['associated_model_name'];
                $asso_field_name = $field_to_display['associated_field_name_to_show'];

                if (!empty($asso_model_name) && !empty($asso_field_name)) {
                    if (!empty($field_name) && $this->$model_name->hasField($field_name, true)) {
                        $fields[] = "$model_name.$field_name";
                    }
                    if (!$this->loadModel($asso_model_name))
                        continue;
                    if ($this->$asso_model_name->hasField($asso_field_name)) {
                        $fields[] = "$asso_model_name.$asso_field_name";
                        $field_list[$field_title] = "$asso_model_name.$asso_field_name";
                    } elseif ($this->$asso_model_name->isVirtualField($asso_field_name)) {
                        $this->$model_name->virtualFields[$asso_field_name] = $this->$asso_model_name->virtualFields[$asso_field_name];
                        $fields[] = "$model_name.$asso_field_name";
                        $field_list[$field_title] = "$model_name.$asso_field_name";
                    }
                } elseif (!empty($field_name) && $this->$model_name->hasField($field_name, true)) {
                    $fields[] = "$model_name.$field_name";
                    $field_list[$field_title] = "$model_name.$field_name";
                }
            }

            $field_values_for_searching = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.display_in_search_params' => 1)));

            foreach ($field_values as $field_value) {
                $is_available_in_view_condition = $field_value['LookupModelFieldDefinition']['is_available_in_view_condition'];
                if ($is_available_in_view_condition == '1') {
                    $condition_field_name = $field_value['LookupModelFieldDefinition']['field_name'];
                    if ($condition_field_name == 'org_id' && !empty($org_id)) {
                        $value_exist_conditions["$model_name.$condition_field_name"] = $org_id;
                        $submitted_condition["$model_name.$condition_field_name"] = $org_id;
                    }
                    if ($condition_field_name == 'submission_status') {
                        $value_exist_conditions["$model_name.$condition_field_name"] = '0';
                        $submitted_condition["$model_name.$condition_field_name"] = '1';
                    }
                }
            }

            if (!empty($field_values_for_searching)) {
                foreach ($field_values_for_searching as $field_value) {
                    $model_name_for_searching = $field_value['LookupModelFieldDefinition']['model_name_for_searching'];
                    $this->loadModel($model_name_for_searching);
                    $key = $model_name_for_searching . '.' . $field_value['LookupModelFieldDefinition']['field_name_for_searching'];
                    $value = $field_value['LookupModelFieldDefinition']['field_description'];
                    $search_options[$key] = $value;
                }
            }

            $lookup_or_detail_id = $model_details['LookupModelDefinition']['lookup_or_detail_id'];
            $this->$model_name->recursive = 1;
            //if (!empty($value_exist_conditions)) {
            //debug($fields);
            //$order_by = !empty($fields[1]) ? $fields[1] : $fields[0];

            $paginate_opts = array('maxLimit' => 5000, 'limit' => 5000, 'fields' => $fields, 'conditions' => $value_exist_conditions, 'order' => !empty($fields[1]) ? $fields[1] : null);

            //$model_list = array(70, 71, 72, 80, 81);
            if (!empty($model_details['LookupModelDefinition']['group_fields_for_view']))
                $paginate_opts['group'] = explode(",", $model_details['LookupModelDefinition']['group_fields_for_view']);

            $unbind_models_list = array();
            if (!empty($model_details['LookupModelDefinition']['unbind_models_for_view'])) {
                $unbind_models_details = explode(";", $model_details['LookupModelDefinition']['unbind_models_for_view']);
                foreach ($unbind_models_details as $unbind_model_detail) {
                    $unbind_model_detail = explode("=>", $unbind_model_detail);
                    if (!empty($unbind_model_detail[0]) && !empty($unbind_model_detail[1]))
                        $unbind_models_list[$unbind_model_detail[0]] = explode(",", $unbind_model_detail[1]);
                }
            }

            $this->$model_name->unbindModel($unbind_models_list, true);

            $this->Paginator->settings = $paginate_opts;
            $values = $this->Paginator->paginate($model_name);
            //debug($values);
            //$pending_values = $this->Paginator->paginate($model_name);
//                debug($fields);
//                debug($pending_values);
            //}
//            if (!empty($submitted_condition)) {
//                //$this->Paginator->settings = array('maxLimit' => 5000, 'limit' => 5000, 'fields' => $fields, 'conditions' => $submitted_condition, 'order' => !empty($fields[1]) ? $fields[1] : null);
//                $paginate_opts['conditions'] = $submitted_condition;
//                $this->Paginator->settings = $paginate_opts;
//                $submitted_values = $this->Paginator->paginate($model_name);
//            }

            if ($this->request->is('post')) {
                $submitted_model_name = $model_name . "_submitted";
                $search_condition_submitted = array();
                if (!empty($this->request->data[$submitted_model_name])) {
                    $submitted_option = $this->request->data[$submitted_model_name]['search_option_submitted'];
                    $submitted_keyword = $this->request->data[$submitted_model_name]['search_keyword_submitted'];
                    $search_condition_submitted = array("$submitted_option LIKE '%$submitted_keyword%'");
                    $search_condition_submitted = array_merge($search_condition_submitted, $submitted_condition);
                    //$this->paginate = array('maxLimit' => 5000, 'limit' => 5000, 'fields' => $fields, 'conditions' => $search_condition_submitted, 'order' => !empty($fields[1]) ? $fields[1] : null);

                    $paginate_opts['conditions'] = $search_condition_submitted;
                    $this->Paginator->settings = $paginate_opts;
                    $submitted_values = $this->Paginator->paginate($model_name);
                }
                $pending_model_name = $model_name . "_pending";
                $search_condition_pending = array();
                if (!empty($this->request->data[$pending_model_name])) {
                    $pending_option = $this->request->data[$pending_model_name]['search_option_pending'];
                    $pending_keyword = $this->request->data[$pending_model_name]['search_keyword_pending'];
                    $search_condition_pending = array("$pending_option LIKE '%$pending_keyword%'");
                    $search_condition_pending = array_merge($search_condition_pending, $value_exist_conditions);
                    //$this->paginate = array('maxLimit' => 5000, 'limit' => 5000, 'fields' => $fields, 'conditions' => $search_condition_pending, 'order' => !empty($fields[1]) ? $fields[1] : null);

                    $paginate_opts['conditions'] = $search_condition_pending;
                    $this->Paginator->settings = $paginate_opts;
                    $pending_values = $this->Paginator->paginate($model_name);
                }
            }
        }

        //debug($field_list);
        $this->set(compact('title', 'model_id', 'model_name', 'org_id', 'model_description', 'search_options', 'field_list', 'values', 'lookup_or_detail_id', 'previous_model_id', 'next_model_id', 'next_controller_name', 'next_action_name', 'previous_controller_name', 'previous_action_name', 'primary_key_field_name', 'fields_to_display_in_view', 'group_title_in_one_to_many_field_name', 'group_title_in_one_to_many_field_description', 'associated_model_name', 'associated_field_name_to_show', 'record_count', 'finished_deadline'));
    }

    public function view_for_final_submission($opt = null) {
        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');
        $this->loadModel('AdminModulePeriodDetail');
        $this->loadModel('CDBNonMfiBasicInfo');

        $IsValidUser = $this->Session->read('User.IsValid');

        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $org_id = $this->Session->read('Org.Id');
        $user_type = $this->Session->read('User.Type');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $data_type_id = $this->Session->read('AdminModulePeriodDataType.DataTypeId');

        $opt_all = false;
        if (!empty($user_group_ids) && in_array(1, $user_group_ids)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }

        $title = '';
        $model_name = '';
        $form_name = '';
        $search_options = array();
        $lookup_or_detail_id = '';
        $pending_values = array();
        $primary_key_field_name = '';

        if (!$this->request->is('post')) {
            if (!empty($this->request->query['model_id'])) {
                $this->Session->write('LookupModelDefinition.ModelId', $this->request->query['model_id']);
            } else {
                $this->Session->write('LookupModelDefinition.ModelId', '');
            }
            if (!empty($this->request->query['data_type_id'])) {
                $data_type_id = $this->request->query['data_type_id'];
                $this->Session->write('AdminModulePeriodDataType.DataTypeId', $this->request->query['data_type_id']);
            }
//            else {
//                //$this->Session->write('AdminModulePeriodDataType.DataTypeId', '');
//            }

            if (!empty($this->request->query['is_submit'])) {
                $this->Session->write('SubmissionStatus.IsSubmit', $this->request->query['is_submit']);
            } else {
                $this->Session->write('SubmissionStatus.IsSubmit', '');
            }
        }

        $period_values = $this->AdminModulePeriodDetail->find('first', array('conditions' => array('AdminModulePeriodDetail.is_current_period' => 1, 'AdminModulePeriodDetail.data_type_id' => $data_type_id), 'recursive' => 1));

        if (!empty($period_values['AdminModulePeriodList'])) {
            $period_values = $period_values['AdminModulePeriodList'];
            $period = $period_values['as_on'];
            $this->set(compact('period'));
        }
        $org_values = $this->CDBNonMfiBasicInfo->find('first', array('conditions' => array('CDBNonMfiBasicInfo.id' => $org_id), 'recursive' => -1));
        if (!empty($org_values['CDBNonMfiBasicInfo'])) {
            $name_of_org = $org_values['CDBNonMfiBasicInfo']['name_of_org'];
            $this->set(compact('name_of_org'));
        }

        $model_id = $this->Session->read('LookupModelDefinition.ModelId');
        $model_details = $this->LookupModelDefinition->find('first', array('conditions' => array('LookupModelDefinition.id' => $model_id)));
        if (!empty($model_details)) {
            $model_description = $model_details['LookupModelDefinition']['model_description'];
            $title = $model_description;
            $model_name = $model_details['LookupModelDefinition']['model_name'];
            $value_exist_conditions = array();
            $submitted_condition = array();

            $this->loadModel($model_name);

            $field_values = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('model_id' => $model_id), 'recursive' => -1));
            $fields_to_display_in_view = $this->LookupModelFieldDefinition->find('all', array(
                'fields' => array('field_name', 'field_description', 'associated_field_name_to_show', 'associated_model_name'),
                'conditions' => array('model_id' => $model_id, 'display_in_view_page' => 1, array('NOT' => array('field_name' => 'org_id')), array('NOT' => array('field_name' => 'period_id'))),
                'recursive' => -1
            ));

            $fields = array();
            $field_list = array();
            foreach ($fields_to_display_in_view as $field_to_display) {
                if (empty($field_to_display['LookupModelFieldDefinition']))
                    continue;

                $field_to_display = $field_to_display['LookupModelFieldDefinition'];
                $field_name = $field_to_display['field_name'];
                $field_title = $field_to_display['field_description'];
                $asso_model_name = $field_to_display['associated_model_name'];
                $asso_field_name = $field_to_display['associated_field_name_to_show'];

                if (!empty($asso_model_name) && !empty($asso_field_name)) {
                    if (!empty($field_name) && $this->$model_name->hasField($field_name, true)) {
                        $fields[] = "$model_name.$field_name";
                    }
                    if (!$this->loadModel($asso_model_name))
                        continue;
                    if ($this->$asso_model_name->hasField($asso_field_name)) {
                        $fields[] = "$asso_model_name.$asso_field_name";
                        $field_list[$field_title] = "$asso_model_name.$asso_field_name";
                    } elseif ($this->$asso_model_name->isVirtualField($asso_field_name)) {
                        $this->$model_name->virtualFields[$asso_field_name] = $this->$asso_model_name->virtualFields[$asso_field_name];
                        $fields[] = "$model_name.$asso_field_name";
                        $field_list[$field_title] = "$model_name.$asso_field_name";
                    }
                } elseif (!empty($field_name) && $this->$model_name->hasField($field_name, true)) {
                    $fields[] = "$model_name.$field_name";
                    $field_list[$field_title] = "$model_name.$field_name";
                }
            }


//            $fields = Hash::extract($field_list, "{n}.field_name");
//            debug($model_name);
//            debug($fields);
//            debug($field_list);

            /*
              $field_list = array();
              foreach ($fields_to_display_in_view as $field_to_display) {
              if (empty($field_to_display['LookupModelFieldDefinition']))
              continue;

              $field_to_display = $field_to_display['LookupModelFieldDefinition'];
              $field_name = $field_to_display['field_name'];
              $field_title = $field_to_display['field_description'];
              $asso_model_name = $field_to_display['associated_model_name'];
              $asso_field_name = $field_to_display['associated_field_name_to_show'];

              if (!empty($asso_model_name) && !empty($asso_field_name)) {
              if (!empty($field_name) && $this->$model_name->hasField($field_name, true)) {
              $field_list[] = array('view_opt' => 0, 'field_name' => "$model_name.$field_name", 'field_title' => $field_title);
              }
              $this->loadModel($asso_model_name);
              if ($this->$asso_model_name->hasField($asso_field_name)) {
              $field_list[] = array('view_opt' => 1, 'field_name' => "$asso_model_name.$asso_field_name", 'field_title' => $field_title);
              } elseif ($this->$asso_model_name->isVirtualField($asso_field_name)) {
              $this->$model_name->virtualFields[$asso_field_name] = $this->$asso_model_name->virtualFields[$asso_field_name]; //$this->$model_name->getVirtualField($asso_field_name);
              $field_list[] = array('view_opt' => 1, 'field_name' => "$model_name.$asso_field_name", 'field_title' => $field_title);
              }
              } elseif (!empty($field_name) && $this->$model_name->hasField($field_name, true)) {
              $field_list[] = array('view_opt' => 1, 'field_name' => "$model_name.$field_name", 'field_title' => $field_title);
              }
              }
              $fields = Hash::extract($field_list, "{n}.field_name");


              foreach ($fields_to_display_in_view as $field_to_display) {
              if (empty($field_to_display['LookupModelFieldDefinition']))
              continue;

              $field_to_display = $field_to_display['LookupModelFieldDefinition'];
              $field_name = $field_to_display['field_name'];
              $field_title = $field_to_display['field_description'];
              $asso_model_name = $field_to_display['associated_model_name'];
              $asso_field_name = $field_to_display['associated_field_name_to_show'];

              if (!empty($asso_model_name) && !empty($asso_field_name)) {

              $this->loadModel($asso_model_name);
              if ($this->$asso_model_name->hasField($asso_field_name))
              $field_list[$field_title] = "$asso_model_name.$asso_field_name";
              elseif ($this->$asso_model_name->isVirtualField($asso_field_name)) {
              $this->$model_name->virtualFields[$asso_field_name] = $this->$asso_model_name->virtualFields[$asso_field_name]; //$this->$model_name->getVirtualField($asso_field_name);
              $field_list[$field_title] = "$model_name.$asso_field_name";
              }
              } elseif (!empty($field_name) && $this->$model_name->hasField($field_name, true)) {
              $field_list[$field_title] = "$model_name.$field_name";
              }
              }
              //            debug($fields_to_display_in_view);
              //            debug($fields);
             */


            $field_values_for_searching = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.display_in_search_params' => 1)));

            foreach ($field_values as $field_value) {
                $is_available_in_view_condition = $field_value['LookupModelFieldDefinition']['is_available_in_view_condition'];
                if ($is_available_in_view_condition == '1') {
                    $condition_field_name = $field_value['LookupModelFieldDefinition']['field_name'];
                    if ($condition_field_name == 'org_id' && !empty($org_id)) {
                        $value_exist_conditions["$model_name.$condition_field_name"] = $org_id;
                        $submitted_condition["$model_name.$condition_field_name"] = $org_id;
                    }
                    if ($condition_field_name == 'submission_status') {
                        $value_exist_conditions["$model_name.$condition_field_name"] = '0';
                        $submitted_condition["$model_name.$condition_field_name"] = '1';
                    }
                }
            }

            if (!empty($field_values_for_searching)) {
                foreach ($field_values_for_searching as $field_value) {
                    $model_name_for_searching = $field_value['LookupModelFieldDefinition']['model_name_for_searching'];
                    $this->loadModel($model_name_for_searching);
                    $key = $model_name_for_searching . '.' . $field_value['LookupModelFieldDefinition']['field_name_for_searching'];
                    $value = $field_value['LookupModelFieldDefinition']['field_description'];
                    $search_options[$key] = $value;
                }
            }

            $lookup_or_detail_id = $model_details['LookupModelDefinition']['lookup_or_detail_id'];
            $this->$model_name->recursive = 1;
            if (!empty($value_exist_conditions)) {
                $paginate_opts = array('maxLimit' => 5000, 'limit' => 5000, 'fields' => $fields, 'conditions' => $value_exist_conditions, 'order' => !empty($fields[1]) ? $fields[1] : null);

                //$model_list = array(70, 71, 72, 80, 81);
                if (!empty($model_details['LookupModelDefinition']['group_fields_for_view']))
                    $paginate_opts['group'] = explode(",", $model_details['LookupModelDefinition']['group_fields_for_view']);

                $unbind_models_list = array();
                if (!empty($model_details['LookupModelDefinition']['unbind_models_for_view'])) {
                    $unbind_models_details = explode(";", $model_details['LookupModelDefinition']['unbind_models_for_view']);
                    foreach ($unbind_models_details as $unbind_model_detail) {
                        $unbind_model_detail = explode("=>", $unbind_model_detail);
                        if (!empty($unbind_model_detail[0]) && !empty($unbind_model_detail[1]))
                            $unbind_models_list[$unbind_model_detail[0]] = explode(",", $unbind_model_detail[1]);
                    }
                }

//                debug($paginate_opts['group']);
//                debug($unbind_models_list);
                $this->$model_name->unbindModel($unbind_models_list, true);

//                if (!empty($model_details['LookupModelDefinition']['unbind_models_for_view'])) {
//                    $unbind_models_details = explode(";", $model_details['LookupModelDefinition']['unbind_models_for_view']);
//                    foreach ($unbind_models_details as $unbind_model_detail) {
//                        $unbind_model_detail = explode("=>", $unbind_model_detail);
//                        if (!empty($unbind_model_detail[0]) && !empty($unbind_model_detail[1])) {
//                            $bind_type = $unbind_model_detail[0];
//                                debug($this->$model_name->$bind_type['CDBNonMfiBasicInfo']);
//                                unset($this->$model_name->$bind_type['LookupLoanSizePartitionOnDisbursment']);
//                                debug($this->$model_name->$bind_type);
//                            $unbind_models = explode(",", $unbind_model_detail[1]);
//                            foreach ($unbind_models as $unbind_model) {
//                                unset($this->$model_name->$bind_type["$unbind_model"]);
//                            }
//                        }
//                    }
//                }

                $this->Paginator->settings = $paginate_opts;
                $pending_values = $this->Paginator->paginate($model_name);

//                debug($fields);
//                debug($pending_values);
            }
            if (!empty($submitted_condition)) {
                //$this->Paginator->settings = array('maxLimit' => 5000, 'limit' => 5000, 'fields' => $fields, 'conditions' => $submitted_condition, 'order' => !empty($fields[1]) ? $fields[1] : null);
                $paginate_opts['conditions'] = $submitted_condition;
                $this->Paginator->settings = $paginate_opts;
                $submitted_values = $this->Paginator->paginate($model_name);
            }

            if ($this->request->is('post')) {
                $submitted_model_name = $model_name . "_submitted";
                $search_condition_submitted = array();
                if (!empty($this->request->data[$submitted_model_name])) {
                    $submitted_option = $this->request->data[$submitted_model_name]['search_option_submitted'];
                    $submitted_keyword = $this->request->data[$submitted_model_name]['search_keyword_submitted'];
                    $search_condition_submitted = array("$submitted_option LIKE '%$submitted_keyword%'");
                    $search_condition_submitted = array_merge($search_condition_submitted, $submitted_condition);
                    //$this->paginate = array('maxLimit' => 5000, 'limit' => 5000, 'fields' => $fields, 'conditions' => $search_condition_submitted, 'order' => !empty($fields[1]) ? $fields[1] : null);

                    $paginate_opts['conditions'] = $search_condition_submitted;
                    $this->Paginator->settings = $paginate_opts;
                    $submitted_values = $this->Paginator->paginate($model_name);
                }
                $pending_model_name = $model_name . "_pending";
                $search_condition_pending = array();
                if (!empty($this->request->data[$pending_model_name])) {
                    $pending_option = $this->request->data[$pending_model_name]['search_option_pending'];
                    $pending_keyword = $this->request->data[$pending_model_name]['search_keyword_pending'];
                    $search_condition_pending = array("$pending_option LIKE '%$pending_keyword%'");
                    $search_condition_pending = array_merge($search_condition_pending, $value_exist_conditions);
                    //$this->paginate = array('maxLimit' => 5000, 'limit' => 5000, 'fields' => $fields, 'conditions' => $search_condition_pending, 'order' => !empty($fields[1]) ? $fields[1] : null);

                    $paginate_opts['conditions'] = $search_condition_pending;
                    $this->Paginator->settings = $paginate_opts;
                    $pending_values = $this->Paginator->paginate($model_name);
                }
            }
            foreach ($field_values as $field_value) {
                $is_primary_key_field = $field_value['LookupModelFieldDefinition']['is_primary_key'];
                if ($is_primary_key_field == '1') {
                    $primary_key_field_name = $field_value['LookupModelFieldDefinition']['field_name'];
                    break;
                }
            }
        }

        $this->set(compact('title', 'model_id', 'model_name', 'field_list', 'search_options', 'pending_values', 'submitted_values', 'primary_key_field_name'));
    }

    public function view_for_mra($opt = null) {
        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_type = $this->Session->read('User.Type');
        $user_group_ids = $this->Session->read('User.GroupIds');
        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $opt_all = false;
        if (!empty($user_group_ids) && in_array(1, $user_group_ids)) {
            if ($opt && $opt == 'all')
                $this->Session->write('Org.Id', null);
            else
                $opt_all = true;
        }

        $title = '';
        $model_name = '';
        $form_name = '';
        $search_options = array();
        $lookup_or_detail_id = '';
        $pending_values = array();
        $primary_key_field_name = '';
        if (!$this->request->is('post')) {
            if (!empty($this->request->query['model_id'])) {
                $this->Session->write('LookupModelDefinition.ModelId', $this->request->query['model_id']);
            } else {
                $this->Session->write('LookupModelDefinition.ModelId', '');
            }
            if (!empty($this->request->query['data_type_id'])) {
                $this->Session->write('AdminModulePeriodDataType.DataTypeId', $this->request->query['data_type_id']);
            } else {
                $this->Session->write('AdminModulePeriodDataType.DataTypeId', '');
            }
            if (!empty($this->request->query['is_submit'])) {
                $this->Session->write('SubmissionStatus.IsSubmit', $this->request->query['is_submit']);
            } else {
                $this->Session->write('SubmissionStatus.IsSubmit', '');
            }
        }
        $model_id = $this->Session->read('LookupModelDefinition.ModelId');
        $model_details = $this->LookupModelDefinition->find('first', array('conditions' => array('LookupModelDefinition.id' => $model_id)));
        $field_values = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('LookupModelFieldDefinition.model_id' => $model_id)));
        $fields_to_display_in_view = $this->LookupModelFieldDefinition->find('all', array('fields' => array(
                'LookupModelFieldDefinition.field_name',
                'LookupModelFieldDefinition.field_description',
                'LookupModelFieldDefinition.associated_field_name_to_show',
                'LookupModelFieldDefinition.associated_model_name'),
            'conditions' => array(
                'LookupModelFieldDefinition.model_id' => $model_id,
                'LookupModelFieldDefinition.display_in_view_page' => 1)
                )
        );
        $field_values_for_searching = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.display_in_search_params' => 1)));

        if (!empty($model_details)) {
            $model_description = $model_details['LookupModelDefinition']['model_description'];
            $title = $model_description;
            $model_name = $model_details['LookupModelDefinition']['model_name'];
            $this->loadModel($model_name);
            $submitted_condition = array();
            foreach ($field_values as $field_value) {
                $is_available_in_view_condition = $field_value['LookupModelFieldDefinition']['is_available_in_view_condition'];
                if ($is_available_in_view_condition == '1') {
                    $condition_field_name = $field_value['LookupModelFieldDefinition']['field_name'];
                    if ($condition_field_name == 'submission_status') {
                        $submitted_condition["$model_name.$condition_field_name"] = '1';
                    }
                }
            }
            if (!empty($field_values_for_searching)) {
                foreach ($field_values_for_searching as $field_value) {
                    $key = $field_value['LookupModelFieldDefinition']['model_name_for_searching'] . "." . $field_value['LookupModelFieldDefinition']['field_name_for_searching'];
                    $value = $field_value['LookupModelFieldDefinition']['field_description'];
                    $search_options[$key] = $value;
                }
            }
            $lookup_or_detail_id = $model_details['LookupModelDefinition']['lookup_or_detail_id'];
            $this->$model_name->recursive = 1;

            if (!empty($submitted_condition)) {
                $this->Paginator->settings = array('limit' => 7, 'conditions' => $submitted_condition);
                $submitted_values = $this->Paginator->paginate($model_name);
            }

            if ($this->request->is('post')) {
                $option = $this->request->data[$model_name]['search_option'];
                $keyword = $this->request->data[$model_name]['search_keyword'];

                $search_condition = array("$option LIKE '%$keyword%'");
                $search_condition = array_merge($search_condition, $submitted_condition);
                $this->Paginator->settings = array('limit' => 7, 'conditions' => $search_condition);
                $submitted_values = $this->Paginator->paginate($model_name);
            }
            foreach ($field_values as $field_value) {
                $is_primary_key_field = $field_value['LookupModelFieldDefinition']['is_primary_key'];
                if ($is_primary_key_field == '1') {
                    $primary_key_field_name = $field_value['LookupModelFieldDefinition']['field_name'];
                    break;
                }
            }
        }
        $this->set(compact('model_details', 'field_values', 'title', 'model_name', 'model_description', 'search_options', 'submitted_values', 'lookup_or_detail_id', 'model_id', 'primary_key_field_name', 'fields_to_display_in_view'));
    }

    function intToRoman($number) {

        if (empty($number))
            return '';

        $number = intval($number);
        $roman_number = '';

        $roman_base = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        foreach ($roman_base as $roman => $int_value) {
            $matches = intval($number / $int_value);
            $roman_number .= str_repeat($roman, $matches);
            $number = $number % $int_value;
        }

        return $roman_number;
    }

    public function add($model_id = null) {

        $this->loadModel('LookupModelFieldDefinition');
        $this->loadModel('AdminModulePeriodDetail');
        $this->loadModel('AdminModulePeriodList');

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');

        $data_type_id = $this->Session->read('AdminModulePeriodDataType.DataTypeId');

        if ((empty($org_id) && !empty($user_group_ids) && !in_array(1, $user_group_ids)) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $model_details = $this->LookupModelFieldDefinition->LookupModelDefinition->find('first', array('fields' => array('model_name', 'model_description'), 'conditions' => array('LookupModelDefinition.id' => $model_id), 'recursive' => -1));
        if (empty($model_details) || empty($model_details['LookupModelDefinition'])) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid model information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $model_name = $model_details['LookupModelDefinition']['model_name'];
        $title = $model_details['LookupModelDefinition']['model_description'];

        if ($this->request->is('post')) {

            $this->loadModel($model_name);

            $is_ok = true;
            $check_exist_fields = $this->LookupModelFieldDefinition->find('list', array('fields' => array('id', 'field_name'), 'conditions' => array('model_id' => $model_id, 'display_in_add_page' => 1, 'is_exists_validity_check' => 1), 'recursive' => -1, 'order' => array('field_sorting_order' => 'asc')));
            if (!empty($check_exist_fields)) {
                $value_exist_conditions = array();
                foreach ($check_exist_fields as $check_exist_field) {

                    if (empty($this->request->data[$model_name][$check_exist_field])) {
                        $message = 'Vital information missing !';
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => $message
                        );
                        $this->set(compact('msg'));

                        $is_ok = false;
                        $value_exist_conditions = null;
                        break;
                    }

                    if ($this->$model_name->hasField($check_exist_field))
                        $value_exist_conditions[$check_exist_field] = $this->request->data[$model_name][$check_exist_field];
                }

                if (!empty($value_exist_conditions)) {
                    $this->$model_name->recursive = -1;
                    if ($this->$model_name->hasAny($value_exist_conditions)) {
                        $message = 'Information already exists !';
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... . . !',
                            'msg' => $message
                        );
                        $this->set(compact('msg'));

                        $is_ok = false;
                    }
                }
            }

            if ($is_ok) {
                $reqData = $this->request->data;
                if (!empty($reqData)) {
                    $all_fields_details = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('model_id' => $model_id, 'display_in_add_page' => 1), 'recursive' => -1, 'order' => array('field_sorting_order' => 'asc')));
                    if (!empty($all_fields_details)) {
                        $new_field_details = array();
                        foreach ($all_fields_details as $field_details) {
                            $field_details = $field_details['LookupModelFieldDefinition'];
                            $field_id = $field_details['id'];
                            $field_name = $field_details['field_name'];
                            $field_title_to_view_in_crud = $field_details['field_title_to_view_in_crud'];
                            $is_mandatory_for_add = $field_details['is_mandatory_for_add'];
                            if (!empty($field_details) && $is_mandatory_for_add == 1) {
                                $this->$model_name->validate[$field_name] = array(
                                    'required' => true,
                                    'allowEmpty' => false,
                                    'rule' => array('notBlank'),
                                    'on' => 'null',
                                    'message' => "$field_title_to_view_in_crud is required"
                                );
                            }
                        }
                    }
                }
                $this->$model_name->set($reqData);
                $savedData = array();
                if ($this->$model_name->validates()) {
                    $this->$model_name->create();
                    $savedData = $this->$model_name->save($this->request->data);
                } else {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... ... !',
                        'msg' => 'Enter required information first.'
                    );
                    $this->set(compact('msg'));
                }
                if ($savedData) {
                    $all_notes_data = $this->Session->read('Notes.Data');
                    if (!empty($all_notes_data)) {
                        $note_model_id = $this->LookupModelFieldDefinition->field('child_model_id', array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.child_model_id IS NOT NULL'));
                        if (!empty($note_model_id)) {
                            if (isset($savedData[$model_name]['statement_year_id']))
                                $statement_year_period_id = $savedData[$model_name]['statement_year_id'];
                            elseif (isset($savedData[$model_name]['period_id']))
                                $statement_year_period_id = $savedData[$model_name]['period_id'];
                            else
                                $statement_year_period_id = null;

                            $this->save_note($model_id, $note_model_id, $statement_year_period_id, $all_notes_data);
                        }
                    }

                    $is_submit = $this->Session->read('SubmissionStatus.IsSubmit');
                    if (!empty($is_submit) && $is_submit == '1') {
                        $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view_for_final_submission?model_id=' . $model_id . '&data_type_id=' . $data_type_id . '&is_submit=' . $is_submit));
                    } else {
                        $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view?model_id=' . $model_id . '&data_type_id=' . $data_type_id));
                    }
                    return;
                }
            }
        } else {
            $this->Session->delete('Notes.Data');
        }

        $from_date = '';
        $to_date = '';
        $period_values = $this->AdminModulePeriodDetail->find('first', array('conditions' => array('AdminModulePeriodDetail.is_current_period' => 1, 'AdminModulePeriodDetail.data_type_id' => $data_type_id), 'recursive' => 1));
        if (!empty($period_values['AdminModulePeriodList'])) {
            $period_values = $period_values['AdminModulePeriodList'];
            $period_id = $period_values['id'];
            $period = $period_values['as_on']; //$period_values['period'];            
            $this->set(compact('period_id', 'period'));

            $from_date = $period_values['from_date'];
            $to_date = $period_values['to_date'];
        } elseif (empty($period_values) && (!empty($user_group_ids) && in_array(3, $user_group_ids))) {
            $redirect_url = array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add', $model_id);
            $this->Session->write('Current.RedirectUrl', $redirect_url);

            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Data Period Not Yet Set!'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->set(compact('model_id', 'data_type_id'));

        $this->loadModel('BasicModuleBranchInfo');
        $this->BasicModuleBranchInfo->virtualFields['branch_with_address'] = $this->branch_with_code_address; //array('branch_with_address' => "CONCAT_WS(', ', BasicModuleBranchInfo.branch_name, LookupAdminBoundaryUpazila.upazila_name, LookupAdminBoundaryDistrict.district_name)");

        $all_fields_details = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('model_id' => $model_id, 'display_in_add_page' => 1), 'recursive' => -1, 'order' => array('field_sorting_order' => 'asc')));
        ////debug($all_fields_details);
        if (!empty($all_fields_details)) {
            $new_field_details = array();
            $field_details_list = array();

            foreach ($all_fields_details as $field_details) {
                $field_details = $field_details['LookupModelFieldDefinition'];

                $field_id = $field_details['id'];
                $field_group_id = isset($field_details['field_group_id']) ? $field_details['field_group_id'] : -1;
                $field_sub_group_id = isset($field_details['field_sub_group_id']) ? $field_details['field_sub_group_id'] : -1;

                $new_field_details['field_name'] = $field_name = $field_details['field_name'];
                $new_field_details['child_model_id'] = $field_details['child_model_id'];
                $new_field_details['field_label'] = $field_details['field_title_to_view_in_crud'];
                $new_field_details['control_type'] = $control_type = $field_details['control_type_for_add'];
                $new_field_details['is_mandatory_for_add'] = $is_mandatory_for_add = $field_details['is_mandatory_for_add'];
                $new_field_details['data_type'] = $field_details['data_type'];
                $new_field_details['parent_or_child_control_id'] = $field_details['parent_or_child_control_id'];

                $new_field_details['has_notes'] = $has_notes = (!empty($field_details['has_notes']) && $field_details['has_notes'] == '1');
                $new_field_details['is_note_added'] = false;

                if ($field_name == 'from_date') {
                    $date_value = $from_date;
                } elseif ($field_name == 'to_date') {
                    $date_value = $to_date;
                } else {
                    $date_value = '';
                }
                $new_field_details['date_value'] = $date_value;

                if ($field_name == 'org_id') {
                    $field_value_from_session = $org_id;
                } else {
                    $field_value_from_session = '';
                }

                $new_field_details['field_value_from_session'] = $field_value_from_session;
                if ($control_type == "current_date") {
                    $current_date = date('Y-m-d');
                } else {
                    $current_date = '';
                }
                $new_field_details['current_date'] = $current_date;
                $labels = array();
                if ($control_type == "label") {
                    $associated_model_name = $field_details['associated_model_name'];
                    $associated_field_name_to_show = $field_details['associated_field_name_to_show'];
                }

                $options = array();
                if ($control_type == "select" || $control_type == "select_or_label" || $control_type == "dependent_dropdown" || $control_type == "radio" || $control_type == "checkbox") {
                    $select_option_model = $field_details['model_name_for_select_option'];
                    $dropdown_display_field = $field_details['dropdown_display_field'];
                    $dropdown_value_field = $field_details['dropdown_value_field'];
                    $dropdown_condition_field = $field_details['dropdown_condition_field'];

                    $containable_model_names = $field_details['containable_model_names'];
                    $this->loadModel($select_option_model);
                    $fields = array();
                    $fields[0] = "$select_option_model.$dropdown_value_field";
                    $fields[1] = "$select_option_model.$dropdown_display_field";
                    $containable_model_name_list = explode(',', $containable_model_names);
                    if (!empty($select_option_model)) {
                        if (!empty($fields)) {
                            if ($control_type != "dependent_dropdown") {
                                if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                    $value_exist_conditions = array();
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => array($fields[2] => $org_id)));
                                } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'contain' => $containable_model_name_list));
                                } elseif (!empty($dropdown_condition_field) && !empty($containable_model_name_list) && !empty($org_id)) {
                                    $value_exist_conditions = array();
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'contain' => $containable_model_name_list));
                                } else {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields));
                                }
                            }
                        }
                    }
                }
                $new_field_details['options'] = $options;
                $new_field_details['label'] = $labels;

                $field_details_list[$field_group_id][$field_sub_group_id][$field_id] = $new_field_details;
            }
            $legend_groups = $this->LookupModelFieldDefinition->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => array('LookupModelFieldGroup.model_id' => $model_id)));
            $legend_sub_groups = $this->LookupModelFieldDefinition->LookupModelFieldSubGroup->find('list', array('fields' => array('id', 'field_sub_group_title', 'field_group_id'), 'conditions' => array('LookupModelFieldSubGroup.model_id' => $model_id)));

            $this->set(compact('title', 'model_id', 'model_name', 'legend_groups', 'legend_sub_groups', 'field_details_list'));
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid field definition !'
            );
            $this->set(compact('msg'));
            return;
        }
    }

    public function edit($model_id = null, $unique_data_id = null, $submission_status_id = null) {
        $this->loadModel('LookupModelFieldDefinition');
        $this->loadModel('AdminModulePeriodDetail');
        $this->loadModel('AdminModulePeriodList');

        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');
        $data_type_id = $this->Session->read('AdminModulePeriodDataType.DataTypeId');

        if ((empty($org_id) && (!empty($user_group_ids) && !in_array(1, $user_group_ids)) ) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($org_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }

        $model_details = $this->LookupModelFieldDefinition->LookupModelDefinition->find('first', array('fields' => array('model_name', 'model_description'), 'conditions' => array('LookupModelDefinition.id' => $model_id), 'recursive' => -1));
        if (empty($model_details) || empty($model_details['LookupModelDefinition'])) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid model information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $model_name = $model_details['LookupModelDefinition']['model_name'];
        $title = $model_details['LookupModelDefinition']['model_description'];

        $this->loadModel($model_name);
        if ($this->request->is(array('post', 'put'))) {
            $primary_key_field_name = $this->LookupModelFieldDefinition->field('field_name', array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.is_primary_key' => 1));
            $this->$model_name->$primary_key_field_name = $unique_data_id;
            if ($this->$model_name->save($this->request->data)) {
                $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view?model_id=' . $model_id));
//                if (!empty($data_type_id)) {
//                    $is_submit = $this->Session->read('SubmissionStatus.IsSubmit');
//
//                    if (!empty($user_group_ids) && in_array(3, $user_group_ids)) {
//                        $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view?model_id=' . $model_id . '&data_type_id=' . $data_type_id));
//                    } elseif (!empty($user_group_ids) && in_array(2, $user_group_ids)) {
//                        if ($submission_status_id == '1') {
//                            $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view_for_final_submission?model_id=' . $model_id . '&data_type_id=' . $data_type_id . '&is_submit=' . $is_submit));
//                        } elseif ($submission_status_id == '0') {
//                            $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view?model_id=' . $model_id . '&data_type_id=' . $data_type_id));
//                        }
//                    }
//                } else {
//                }
                return;
            }
        }

        $from_date = '';
        $to_date = '';
        $period_values = $this->AdminModulePeriodDetail->find('first', array('conditions' => array('AdminModulePeriodDetail.is_current_period' => 1, 'AdminModulePeriodDetail.data_type_id' => $data_type_id), 'recursive' => 1));
        if (!empty($period_values['AdminModulePeriodList'])) {
            $period_values = $period_values['AdminModulePeriodList'];
            $period_id = $period_values['id'];
            $period = $period_values['as_on'];

            $this->set(compact('period_id', 'period'));
            $from_date = $period_values['from_date'];
            $to_date = $period_values['to_date'];
        } elseif (empty($period_values) && (!empty($user_group_ids) && in_array(3, $user_group_ids))) {
            $redirect_url = array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'edit', $model_id);
            $this->Session->write('Current.RedirectUrl', $redirect_url);
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Data Period Not Yet Set!'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->set(compact('model_id', 'data_type_id', 'unique_data_id'));
        $this->loadModel('BasicModuleBranchInfo');
        $all_fields_details = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('model_id' => $model_id, 'display_in_edit_page' => 1), 'recursive' => -1, 'order' => array('field_sorting_order' => 'asc')));

        if (!empty($all_fields_details)) {
            $post = $this->$model_name->findById($unique_data_id);
            if (empty($this->request->data)) {
                $this->request->data = $post;
            }

            $statement_year_period_field = '';
            $statement_year_period_id = '';
            if (isset($post[$model_name]['statement_year_id'])) {
                $statement_year_period_field = 'statement_year_id';
                $statement_year_period_id = $post[$model_name]['statement_year_id'];
            } elseif (isset($post[$model_name]['period_id'])) {
                $statement_year_period_field = 'period_id';
                $statement_year_period_id = $post[$model_name]['period_id'];
            }

            if (!empty($statement_year_period_field) && !empty($statement_year_period_id)) {
                $note_model_id = $this->LookupModelFieldDefinition->field('child_model_id', array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.child_model_id IS NOT NULL'));
                $note_model_name = $this->LookupModelFieldDefinition->LookupModelDefinition->field('model_name', array('LookupModelDefinition.id' => $note_model_id));

                if (!empty($note_model_name)) {
                    $conditions = array("$note_model_name.org_id" => $org_id, "$note_model_name.parent_model_id" => $model_id, "$note_model_name.model_id" => $note_model_id);

                    $this->loadModel($note_model_name);
                    if ($this->$note_model_name->hasField($statement_year_period_field)) {
                        $conditions["$note_model_name.$statement_year_period_field"] = $statement_year_period_id;
                    }
                }
            }

            $new_field_details = array();
            $field_details_list = array();

            foreach ($all_fields_details as $field_details) {
                $field_details = $field_details['LookupModelFieldDefinition'];
                $field_id = $field_details['id'];
                $field_group_id = isset($field_details['field_group_id']) ? $field_details['field_group_id'] : -1;
                $field_sub_group_id = isset($field_details['field_sub_group_id']) ? $field_details['field_sub_group_id'] : -1;
                $new_field_details['field_name'] = $field_name = $field_details['field_name'];
                $new_field_details['child_model_id'] = $field_details['child_model_id'];
                $new_field_details['field_label'] = $field_details['field_title_to_view_in_crud'];
                $new_field_details['control_type'] = $control_type = $field_details['control_type_for_edit'];
                $new_field_details['is_mandatory_for_edit'] = $is_mandatory_for_edit = $field_details['is_mandatory_for_edit'];
                $new_field_details['data_type'] = $field_details['data_type'];
                $new_field_details['parent_or_child_control_id'] = $field_details['parent_or_child_control_id'];
                $new_field_details['has_notes'] = $has_notes = (!empty($field_details['has_notes']) && $field_details['has_notes'] == '1');
                $new_field_details['is_note_added'] = false;

                if ($field_name == 'from_date') {
                    $date_value = $from_date;
                } elseif ($field_name == 'to_date') {
                    $date_value = $to_date;
                } else {
                    $date_value = '';
                }
                $new_field_details['date_value'] = $date_value;

                if ($field_name == 'org_id') {
                    $field_value_from_session = $org_id;
                } else {
                    $field_value_from_session = '';
                }

                $new_field_details['field_value_from_session'] = $field_value_from_session;
                if ($control_type == "current_date") {
                    $current_date = date('Y-m-d');
                } else {
                    $current_date = '';
                }
                $new_field_details['current_date'] = $current_date;
                $labels = array();
                if ($control_type == "label") {
                    $associated_model_name = $field_details['associated_model_name'];
                    $associated_field_name_to_show = $field_details['associated_field_name_to_show'];

                    if (!empty($post[$associated_model_name][$associated_field_name_to_show]) && !empty($associated_model_name) && !empty($associated_field_name_to_show)) {
                        $labels = $post[$associated_model_name][$associated_field_name_to_show];
                    }
                }

                if ($has_notes && !empty($conditions)) {
                    $conditions["$note_model_name.parent_field_id"] = $field_id;
                    $new_field_details['is_note_added'] = $this->$note_model_name->hasAny($conditions);
                }

                $options = array();
                if ($control_type == "select" || $control_type == "select_or_label" || $control_type == "dependent_dropdown" || $control_type == "radio" || $control_type == "checkbox") {
                    $select_option_model = $field_details['model_name_for_select_option'];
                    $dropdown_display_field = $field_details['dropdown_display_field'];
                    $dropdown_value_field = $field_details['dropdown_value_field'];
                    $dropdown_condition_field = $field_details['dropdown_condition_field'];
                    $containable_model_names = $field_details['containable_model_names'];
                    $this->loadModel($select_option_model);

                    $fields = array();
                    $fields[0] = "$select_option_model.$dropdown_value_field";
                    $fields[1] = "$select_option_model.$dropdown_display_field";
                    $containable_model_name_list = explode(',', $containable_model_names);
                    if (!empty($select_option_model)) {
                        if (!empty($fields)) {
                            if ($control_type != "dependent_dropdown") {
                                if (!empty($dropdown_condition_field) && empty($containable_model_name_list)) {
                                    $value_exist_conditions = array();
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => array($fields[2] => $org_id)));
                                } elseif (empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'contain' => $containable_model_name_list));
                                } elseif (!empty($dropdown_condition_field) && !empty($containable_model_name_list)) {
                                    $value_exist_conditions = array();
                                    $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'contain' => $containable_model_name_list));
                                } else {
                                    $options = $this->$select_option_model->find('list', array('fields' => $fields));
                                }
                            } else {
                                $dependent_dropdown_condition_field = $field_details['dependent_dropdown_condition_field'];
                                $dependent_dropdown_condition_value = $post[$model_name][$dependent_dropdown_condition_field];
                                $conditions = array();
                                $conditions[$select_option_model . "." . $dependent_dropdown_condition_field] = $dependent_dropdown_condition_value;
                                $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $conditions));
                            }
                        }
                    }
                }
                $new_field_details['options'] = $options;
                $new_field_details['label'] = $labels;
                $field_details_list[$field_group_id][$field_sub_group_id][$field_id] = $new_field_details;
            }

            $legend_groups = $this->LookupModelFieldDefinition->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => array('LookupModelFieldGroup.model_id' => $model_id)));
            $legend_sub_groups = $this->LookupModelFieldDefinition->LookupModelFieldSubGroup->find('list', array('fields' => array('id', 'field_sub_group_title', 'field_group_id'), 'conditions' => array('LookupModelFieldSubGroup.model_id' => $model_id)));

//            $sub_groups = $this->LookupModelFieldDefinition->LookupModelFieldSubGroup->find('all', array('fields' => array('id', 'field_group_id', 'field_sub_group_title'), 'conditions' => array('LookupModelFieldSubGroup.model_id' => $model_id)));
//            $legend_sub_groups = Hash::combine($sub_groups, '{n}.LookupModelFieldSubGroup.id', '{n}.LookupModelFieldSubGroup.field_sub_group_title', '{n}.LookupModelFieldSubGroup.field_group_id');

            $this->set(compact('title', 'model_id', 'model_name', 'statement_year_period_id', 'legend_groups', 'legend_sub_groups', 'field_details_list'));
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid field definition !'
            );
            $this->set(compact('msg'));
            return;
        }
    }

    public function submit($model_id = null, $unique_data_id = null, $option = null) {
        $this->loadModel('LookupModelFieldDefinition');
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');
        $branch_office_type_id = 3;
        $data_type_id = $this->Session->read('AdminModulePeriodDataType.DataTypeId');
        $is_submit = $this->Session->read('SubmissionStatus.IsSubmit');

        if ((empty($org_id) && (!empty($user_group_ids) && !in_array(1, $user_group_ids))) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $from_date = '';
        $to_date = '';
        $title = '';
        if (!$this->request->is('post')) {
            $this->Session->write('LookupModelDefinition.ModelId', $model_id);
        }
        $pending_values = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.display_in_edit_page' => 1), 'order' => array('LookupModelFieldDefinition.field_sorting_order' => 'asc')));

        if (!empty($pending_values)) {
            $model_name = $pending_values[0]['LookupModelDefinition']['model_name'];
            $this->loadModel($model_name);
            $post = $this->$model_name->findById($unique_data_id);

            $this->loadModel('BasicModuleBranchInfo');
            $this->BasicModuleBranchInfo->virtualFields['branch_with_address'] = $this->branch_with_code_address;

            $field_values = array();
            $field_merged_values = array();
            foreach ($pending_values as $field_value) {
                $title = $field_value['LookupModelDefinition']['model_description'];
                $model_id = $field_value['LookupModelDefinition']['id'];
                $field_values['id'] = $field_value['LookupModelFieldDefinition']['id'];
                $field_values['field_name'] = $field_name = $field_value['LookupModelFieldDefinition']['field_name'];
                $field_values['field_title_to_view_in_crud'] = $field_value['LookupModelFieldDefinition']['field_title_to_view_in_crud'];
                $field_values['control_type_for_edit'] = $control_type = $field_value['LookupModelFieldDefinition']['control_type_for_edit'];
                $new_field_details['is_mandatory_for_edit'] = $is_mandatory_for_edit = $field_details['is_mandatory_for_edit'];
                $field_values['data_type'] = $field_value['LookupModelFieldDefinition']['data_type'];
                $field_values['parent_or_child_control_id'] = $field_value['LookupModelFieldDefinition']['parent_or_child_control_id'];
                $is_exists = $field_value['LookupModelFieldDefinition']['is_exists_validity_check'];
                $field_values['field_group_id'] = $field_value['LookupModelFieldDefinition']['field_group_id'];
                $field_values['field_sub_group_id'] = $field_value['LookupModelFieldDefinition']['field_sub_group_id'];

                if ($field_name == 'from_date') {
                    $date_value = $from_date;
                } elseif ($field_name == 'to_date') {
                    $date_value = $to_date;
                } else {
                    $date_value = '';
                }
                $field_values['date_value'] = $date_value;
                $labels = array();
                if ($control_type == "label") {
                    $associated_model_name = $field_value['LookupModelFieldDefinition']['associated_model_name'];
                    $associated_field_name_to_show = $field_value['LookupModelFieldDefinition']['associated_field_name_to_show'];
                    $labels = $post[$associated_model_name][$associated_field_name_to_show];
                }

                if ($control_type == "current_date") {
                    $current_date = date('Y-m-d');
                } else {
                    $current_date = '';
                }
                $field_values['current_date'] = $current_date;

                $options = array();
                if (($control_type == "select")) {
                    $select_option_model = $field_value['LookupModelFieldDefinition']['model_name_for_select_option'];
                    $model_name_for_dependent_select_option = $field_value['LookupModelFieldDefinition']['model_name_for_dependent_select_option'];
                    $dropdown_display_field = $field_value['LookupModelFieldDefinition']['dropdown_display_field'];
                    $dropdown_value_field = $field_value['LookupModelFieldDefinition']['dropdown_value_field'];
                    $containable_model_names = $field_value['LookupModelFieldDefinition']['containable_model_names'];

                    $this->loadModel($select_option_model);
                    $fields = array();
                    $fields[0] = $select_option_model . "." . $dropdown_value_field;
                    $fields[1] = $select_option_model . "." . $dropdown_display_field;
                    $containable_model_name_list = explode(',', $containable_model_names);
                    if ($select_option_model != '') {
                        if (!empty($fields)) {
                            $options = array();
                            $options = $this->$select_option_model->find('list', array('fields' => $fields));
                        }
                    }
                }
                $field_values['options'] = $options;
                $field_values['label'] = $labels;
                $field_merged_values = array_merge($field_merged_values, array($field_values));
            }
        }
        $this->loadModel('LookupModelFieldGroup');
        $legend_groups = $this->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => array('LookupModelFieldGroup.model_id' => $model_id)));

        $this->loadModel('LookupModelFieldSubGroup');
        $legend_sub_group_values = array();
        $sub_groups = $this->LookupModelFieldSubGroup->find('all', array('fields' => array('id', 'field_group_id', 'field_sub_group_title'), 'conditions' => array('LookupModelFieldSubGroup.model_id' => $model_id)));
        foreach ($sub_groups as $sub_group) {
            $legend_sub_group_values[$sub_group['LookupModelFieldSubGroup']['field_group_id']][$sub_group['LookupModelFieldSubGroup']['id']] = $sub_group['LookupModelFieldSubGroup']['field_sub_group_title'];
        }

        $field_merged_value_group_wise = array();
        foreach ($field_merged_values as $field_merged_value) {
            $field_merged_value_group_wise[$field_merged_value['field_group_id']][$field_merged_value['field_sub_group_id']][] = $field_merged_value;
        }

        $primary_key_field_value = $this->LookupModelFieldDefinition->find('first', array('fields' => array('field_name'), 'conditions' => array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.is_primary_key' => 1)));
        $primary_key_field_name = $primary_key_field_value['LookupModelFieldDefinition']['field_name'];

        if ($this->request->is(array('post', 'put'))) {
            $flag = 1;
            if ($option == '0') {
                $this->request->data[$model_name]['submission_status'] = '0';
            } elseif ($option == '1') {
                $period_id = $post[$model_name]['period_id'];
                $total_sent_values = $this->$model_name->find('all', array(
                    'fields' => array("$model_name.branch_id"),
                    'conditions' => array("$model_name.org_id" => $org_id, "$model_name.period_id" => $period_id),
                    'group' => array("$model_name.org_id", "$model_name.branch_id", "$model_name.period_id")));
                $no_of_branches_sent = count($total_sent_values);

                $total_branch_values = $this->BasicModuleBranchInfo->find('all', array(
                    'fields' => array('BasicModuleBranchInfo.id'),
                    'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id' => $branch_office_type_id)));
                $total_no_of_branches = count($total_branch_values);

                if ($total_no_of_branches == $no_of_branches_sent) {
                    $flag = 1;
                } else {
                    $flag = 0;
                }
            }
            if ($flag == 1) {
                $this->$model_name->$primary_key_field_name = $unique_data_id;
                if ($this->$model_name->save($this->request->data)) {
                    $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view_for_final_submission?model_id=' . $model_id . '&data_type_id=' . $data_type_id . '&is_submit=' . $is_submit));
                }
            } else {
                $message = 'Some Branches not yet finished data submission.You cannot submit data to MRA until all of the Branches finished data submission!';
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
            }
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
        $this->set(compact('field_merged_value_group_wise', 'selected_value_option', 'title', 'model_id', 'model_name', 'legend_groups', 'legend_sub_group_values', 'unique_data_id', 'data_type_id'));
    }

    public function submit_all($model_id = null) {
        //$this->autoRender = false;
        $this->loadModel('LookupModelFieldDefinition');
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_ids = $this->Session->read('User.GroupIds');
        $org_id = $this->Session->read('Org.Id');
        $data_type_id = $this->Session->read('AdminModulePeriodDataType.DataTypeId');
        $is_submit = $this->Session->read('SubmissionStatus.IsSubmit');
        $branch_office_type_id = 3;

        if ((empty($org_id) && (!empty($user_group_ids) && in_array(1, $user_group_ids))) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }

        $field_values = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.display_in_edit_page' => 1), 'order' => array('LookupModelFieldDefinition.field_sorting_order' => 'asc')));

        if (!empty($field_values)) {
            $model_name = $field_values[0]['LookupModelDefinition']['model_name'];
            $this->loadModel($model_name);
        }
        $this->loadModel('AdminModulePeriodDetail');
        $flag = 0;
        $temp = 0;
        $period_values = $this->AdminModulePeriodDetail->find('first', array('conditions' => array('AdminModulePeriodDetail.data_type_id' => $data_type_id, 'AdminModulePeriodDetail.is_current_period' => 1)));
        if (!empty($period_values)) {
            $period_id = $period_values['AdminModulePeriodDetail']['period_id'];

            $total_sent_values = $this->$model_name->find('all', array(
                'fields' => array("$model_name.branch_id"),
                'conditions' => array("$model_name.org_id" => $org_id, "$model_name.period_id" => $period_id),
                'group' => array("$model_name.org_id", "$model_name.branch_id", "$model_name.period_id")));
            $no_of_branches_sent = count($total_sent_values);

            $this->loadModel('BasicModuleBranchInfo');
            $total_branch_values = $this->BasicModuleBranchInfo->find('all', array(
                'fields' => array('BasicModuleBranchInfo.id'),
                'conditions' => array('BasicModuleBranchInfo.org_id' => $org_id, 'BasicModuleBranchInfo.office_type_id' => $branch_office_type_id)));
            $total_no_of_branches = count($total_branch_values);

            if ($total_no_of_branches == $no_of_branches_sent) {
                $flag = 1;
            } else {
                $flag = 0;
            }
            if ($flag == 1) {
                $this->$model_name->updateAll(array('submission_status' => 1), array("$model_name.org_id" => $org_id, "$model_name.period_id" => $period_id));
                $message = 'Data submitted to MRA successfully';
                $type = 'success';
                $title = 'Confirm... . . !';
            } else {
                $message = 'Some Branches not yet finished data submission.You cannot submit data to MRA until all of the Branches finished data submission!';
                $type = 'error';
                $title = 'Error... . . !';
            }
            $msg = array(
                'type' => $type,
                'title' => $title,
                'msg' => $message
            );
            $this->set(compact('msg'));
        } else {
            $redirect_url = array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'add', $model_id);
            $this->Session->write('Current.RedirectUrl', $redirect_url);

            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Data Period Not Yet Set!'
            );
            $this->set(compact('msg'));
            return;
        }
    }

    public function dependent_option_select() {
        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');
        $this->loadModel('BasicModuleBranchInfo');
        $this->BasicModuleBranchInfo->virtualFields['branch_with_address'] = $this->branch_with_code_address;
        //$this->BasicModuleBranchInfo->virtualFields = array('branch_with_address' => "CONCAT_WS(', ', BasicModuleBranchInfo.branch_name, LookupAdminBoundaryUpazila.upazila_name, LookupAdminBoundaryDistrict.district_name)");

        $field_values = array();
        $field_merged_values = array();
        $options = array();
        $fields = array();
        $value_exist_conditions = array();
        $model_name = key($this->request->data);
        $field_name = key($this->request->data[$model_name]);
        $this->LookupModelFieldDefinition->recursive = 0;
        $field_value = $this->LookupModelFieldDefinition->find('first', array('conditions' => array('LookupModelDefinition.model_name' => $model_name, 'LookupModelFieldDefinition.field_name' => $field_name)));

        $model_name_for_dependent_select_option = $field_value['LookupModelFieldDefinition']['model_name_for_dependent_select_option'];
        $dependent_dropdown_display_field = $field_value['LookupModelFieldDefinition']['dependent_dropdown_display_field'];
        $dependent_dropdown_value_field = $field_value['LookupModelFieldDefinition']['dependent_dropdown_value_field'];
        $dependent_dropdown_condition_field = $field_value['LookupModelFieldDefinition']['dependent_dropdown_condition_field'];
        $containable_model_names = $field_value['LookupModelFieldDefinition']['containable_model_names'];
        $this->loadModel($model_name_for_dependent_select_option);
        $fields[0] = $model_name_for_dependent_select_option . "." . $dependent_dropdown_value_field;
        $fields[1] = $model_name_for_dependent_select_option . "." . $dependent_dropdown_display_field;
        $unique_data_id = $this->request->data[$model_name][$field_name];
        $value_exist_conditions[$model_name_for_dependent_select_option . "." . $dependent_dropdown_condition_field] = $unique_data_id;
        $this->loadModel($model_name_for_dependent_select_option);

        if (!empty($value_exist_conditions)) {
            $containable_array = split(',', $containable_model_names);
            if (!empty($containable_array)) {
                $options = $this->$model_name_for_dependent_select_option->find('list', array(
                    'fields' => $fields,
                    'conditions' => $value_exist_conditions,
                    'contain' => $containable_array,
                    'recursive' => -1
                ));
            } else {
                $options = $this->$model_name_for_dependent_select_option->find('list', array(
                    'fields' => $fields,
                    'conditions' => $value_exist_conditions,
                    'recursive' => -1
                ));
            }
            $this->set(compact('options'));
        }
        $this->layout = 'ajax';
    }

    public function details($model_id = null, $unique_data_id = null) {
        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');

        if (empty($model_id))
            $model_id = $this->Session->read('LookupModelDefinition.ModelId');

        $fields = array('id', 'model_id', 'field_group_id', 'field_sub_group_id', 'field_name', 'field_description', 'control_type_for_add', 'associated_field_name_to_show', 'associated_model_name');
        $fields_details = $this->LookupModelFieldDefinition->find('all', array('fields' => $fields, 'conditions' => array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.display_in_details_page' => 1), 'recursive' => -1));

        if (!empty($fields_details)) {
            $model_name = $this->LookupModelFieldDefinition->LookupModelDefinition->field('model_name', array('LookupModelDefinition.id' => $model_id));
            $this->loadModel($model_name);

            $asso_models_list = $this->$model_name->getAssociated();

            $field_details_list = array();
            foreach ($fields_details as $field_detail) {
                $field_detail = $field_detail['LookupModelFieldDefinition'];

                $field_id = $field_detail['id'];
                $field_group_id = isset($field_detail['field_group_id']) ? $field_detail['field_group_id'] : -1;
                $field_sub_group_id = isset($field_detail['field_sub_group_id']) ? $field_detail['field_sub_group_id'] : -1;

                $field = array();
                $field['field_description'] = $field_detail['field_description'];

                $field_model_name = $field_detail['associated_model_name'];
                $field_name = $field_detail['associated_field_name_to_show'];

                if (empty($field_name) || empty($field_model_name)) {
                    $field_name = $field_detail['field_name'];
                    if (empty($field_name))
                        continue;

                    $field['model_name'] = $model_name;

                    if ($this->$model_name->hasField($field_name) || $this->$model_name->isVirtualField($field_name)) {
                        $field['field_name'] = $field_name;
                    } else {

                        $control_type = $field_detail['control_type_for_add'];
                        if ($control_type == "calculated_label" || $control_type == "aggregate_label") {
                            $str_sum = str_replace(' ', '', $field_name);

                            $old_char = array('(', ')', '+', '-', '*', '/', ':', '\\');
                            $new_char = array('', '', ', ', ', ', ', ', ', ', ', ', ', ');
                            $str_ids = str_replace($old_char, $new_char, $str_sum);
                            $ctr_ids = explode(', ', $str_ids);

                            foreach ($ctr_ids as $ctr_id) {
                                $str_sum = str_replace($ctr_id, "COALESCE($ctr_id, 0)", $str_sum);
                            }

                            $field_name = $str_sum;
                            $virtual_field_name = "vf_cal_$field_id";
                        } else
                            $virtual_field_name = "vf_$field_id";

                        $this->$model_name->virtualFields[$virtual_field_name] = "$field_name";
                        $field['field_name'] = $virtual_field_name;
                    }
                } else {
                    if (!isset($asso_models_list[$field_model_name]))
                        continue;

                    if (!$this->$model_name->$field_model_name->hasField($field_name) && !$this->$model_name->$field_model_name->isVirtualField($field_name))
                        continue;

                    if ($this->$model_name->$field_model_name->isVirtualField($field_name)) {
                        $this->$model_name->virtualFields[$field_name] = $this->$model_name->$field_model_name->virtualFields[$field_name];
                        $field['model_name'] = $model_name;
                        $field['field_name'] = $field_name;
                    } else {
                        $field['model_name'] = $field_model_name;
                        $field['field_name'] = $field_name;
                    }
                }

                $field_details_list[$field_group_id][$field_sub_group_id][$field_id] = $field;
            }

            $legend_groups = $this->LookupModelFieldDefinition->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => array('LookupModelFieldGroup.model_id' => $model_id)));

            $legend_sub_groups = $this->LookupModelFieldDefinition->LookupModelFieldSubGroup->find('list', array('fields' => array('id', 'field_sub_group_title', 'field_group_id'), 'conditions' => array('LookupModelFieldSubGroup.model_id' => $model_id)));


//            $sub_groups = $this->LookupModelFieldDefinition->LookupModelFieldSubGroup->find('all', array('fields' => array('id', 'field_group_id', 'field_sub_group_title'), 'conditions' => array('LookupModelFieldSubGroup.model_id' => $model_id)));
//            $legend_sub_groups = Hash::combine($sub_groups, '{n}.LookupModelFieldSubGroup.id', '{n}.LookupModelFieldSubGroup.field_sub_group_title', '{n}.LookupModelFieldSubGroup.field_group_id');

            $this->$model_name->recursive = 0;
            $field_values = $this->$model_name->findById($unique_data_id);

            if (!$field_values) {
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => 'Invalid Information!'
                );
                $this->set(compact('msg'));
                return;
            }

            $this->set(compact('model_id', 'model_name', 'field_details_list', 'legend_groups', 'legend_sub_groups', 'field_values'));
        }
    }

    public function details_1($model_id = null, $unique_data_id = null) {
        $this->loadModel('LookupModelFieldDefinition');
        $this->loadModel('AdminModulePeriodDetail');
        $this->loadModel('AdminModulePeriodList');

        $this->loadModel('BasicModuleBranchInfo');
        $this->BasicModuleBranchInfo->virtualFields['branch_with_address'] = $this->branch_with_code_address;
        //$this->BasicModuleBranchInfo->virtualFields = array('branch_with_address' => "CONCAT_WS(', ', BasicModuleBranchInfo.branch_name, LookupAdminBoundaryUpazila.upazila_name, LookupAdminBoundaryDistrict.district_name)");

        $all_fields_details = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('model_id' => $model_id, 'display_in_edit_page' => 1), 'recursive' => -1, 'order' => array('field_sorting_order' => 'asc')));
        if (!empty($all_fields_details)) {

            $post = $this->$model_name->findById($unique_data_id);
            if ($post && !$this->request->data) {
                $this->request->data = $post;
            }

            $statement_year_period_field = '';
            $statement_year_period_id = '';
            if (isset($post[$model_name]['statement_year_id'])) {
                $statement_year_period_field = 'statement_year_id';
                $statement_year_period_id = $post[$model_name]['statement_year_id'];
            } elseif (isset($post[$model_name]['period_id'])) {
                $statement_year_period_field = 'period_id';
                $statement_year_period_id = $post[$model_name]['period_id'];
            }

            if (!empty($statement_year_period_field) && !empty($statement_year_period_id)) {
                $note_model_id = $this->LookupModelFieldDefinition->field('child_model_id', array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.child_model_id IS NOT NULL'));
                $note_model_name = $this->LookupModelFieldDefinition->LookupModelDefinition->field('model_name', array('LookupModelDefinition.id' => $note_model_id));

                if (!empty($note_model_name)) {
                    $conditions = array("$note_model_name.org_id" => $org_id, "$note_model_name.parent_model_id" => $model_id, "$note_model_name.model_id" => $note_model_id);

                    $this->loadModel($note_model_name);
                    if ($this->$note_model_name->hasField($statement_year_period_field)) {
                        $conditions["$note_model_name.$statement_year_period_field"] = $statement_year_period_id;
                    }
                }
            }

            $new_field_details = array();
            $field_details_list = array();

            foreach ($all_fields_details as $field_details) {
                $field_details = $field_details['LookupModelFieldDefinition'];

                $field_id = $field_details['id'];
                $field_group_id = isset($field_details['field_group_id']) ? $field_details['field_group_id'] : -1;
                $field_sub_group_id = isset($field_details['field_sub_group_id']) ? $field_details['field_sub_group_id'] : -1;

                $new_field_details['field_name'] = $field_name = $field_details['field_name'];
                $new_field_details['child_model_id'] = $field_details['child_model_id'];
                $new_field_details['field_label'] = $field_details['field_title_to_view_in_crud'];
                $new_field_details['control_type'] = $control_type = $field_details['control_type_for_edit'];
                $new_field_details['data_type'] = $field_details['data_type'];
                $new_field_details['parent_or_child_control_id'] = $field_details['parent_or_child_control_id'];

                $new_field_details['has_notes'] = $has_notes = (!empty($field_details['has_notes']) && $field_details['has_notes'] == '1');
                $new_field_details['is_note_added'] = false;

                if ($field_name == 'from_date') {
                    $date_value = $from_date;
                } elseif ($field_name == 'to_date') {
                    $date_value = $to_date;
                } else {
                    $date_value = '';
                }
                $new_field_details['date_value'] = $date_value;

                if ($field_name == 'org_id') {
                    $field_value_from_session = $org_id;
                } else {
                    $field_value_from_session = '';
                }
                $new_field_details['field_value_from_session'] = $field_value_from_session;
                if ($control_type == "current_date") {
                    $current_date = date('Y-m-d');
                } else {
                    $current_date = '';
                }
                $new_field_details['current_date'] = $current_date;
                $labels = array();
                if ($control_type == "label") {
                    $associated_model_name = $field_details['associated_model_name'];
                    $associated_field_name_to_show = $field_details['associated_field_name_to_show'];

                    if (!empty($post[$associated_model_name][$associated_field_name_to_show]) && !empty($associated_model_name) && !empty($associated_field_name_to_show)) {
                        $labels = $post[$associated_model_name][$associated_field_name_to_show];
                    }
                }

                if ($has_notes && !empty($conditions)) {
                    $conditions["$note_model_name.parent_field_id"] = $field_id;
                    $new_field_details['is_note_added'] = $this->$note_model_name->hasAny($conditions);
                }

                $options = array();
                if ($control_type == "select" || $control_type == "select_or_label" || $control_type == "dependent_dropdown" || $control_type == "radio" || $control_type == "checkbox") {
                    $select_option_model = $field_details['model_name_for_select_option'];
                    $dropdown_display_field = $field_details['dropdown_display_field'];
                    $dropdown_value_field = $field_details['dropdown_value_field'];
                    $dropdown_condition_field = $field_details['dropdown_condition_field'];
                    $containable_model_names = $field_details['containable_model_names'];
                    $this->loadModel($select_option_model);
                    $fields = array();
                    $fields[0] = "$select_option_model.$dropdown_value_field";
                    $fields[1] = "$select_option_model.$dropdown_display_field";
                    $containable_model_name_list = explode(',', $containable_model_names);
                    if (!empty($select_option_model)) {
                        if (!empty($fields)) {
                            if ($dropdown_condition_field != '' && empty($containable_model_name_list)) {
                                $value_exist_conditions = array();
                                $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;

                                $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => array($fields[2] => $org_id)));
                            } elseif ($dropdown_condition_field == '' && !empty($containable_model_name_list)) {
                                $options = $this->$select_option_model->find('list', array('fields' => $fields, 'contain' => $containable_model_name_list));
                            } elseif ($dropdown_condition_field != '' && !empty($containable_model_name_list)) {
                                $value_exist_conditions = array();
                                $value_exist_conditions["$select_option_model.$dropdown_condition_field"] = $org_id;
                                $options = $this->$select_option_model->find('list', array('fields' => $fields, 'conditions' => $value_exist_conditions, 'contain' => $containable_model_name_list));
                            } elseif ($org_id == null) {
                                $options = null;
                            } else {
                                $options = $this->$select_option_model->find('list', array('fields' => $fields));
                            }
                        }
                    }
                }
                $new_field_details['options'] = $options;
                $new_field_details['label'] = $labels;

                $field_details_list[$field_group_id][$field_sub_group_id][$field_id] = $new_field_details;
            }

            $legend_groups = $this->LookupModelFieldDefinition->LookupModelFieldGroup->find('list', array('fields' => array('id', 'field_group_title'), 'conditions' => array('LookupModelFieldGroup.model_id' => $model_id)));
            $legend_sub_groups = $this->LookupModelFieldDefinition->LookupModelFieldSubGroup->find('list', array('fields' => array('id', 'field_sub_group_title', 'field_group_id'), 'conditions' => array('LookupModelFieldSubGroup.model_id' => $model_id)));

//            $sub_groups = $this->LookupModelFieldDefinition->LookupModelFieldSubGroup->find('all', array('fields' => array('id', 'field_group_id', 'field_sub_group_title'), 'conditions' => array('LookupModelFieldSubGroup.model_id' => $model_id)));
//            $legend_sub_groups = Hash::combine($sub_groups, '{n}.LookupModelFieldSubGroup.id', '{n}.LookupModelFieldSubGroup.field_sub_group_title', '{n}.LookupModelFieldSubGroup.field_group_id');

            $this->set(compact('title', 'model_id', 'model_name', 'statement_year_period_id', 'legend_groups', 'legend_sub_groups', 'field_details_list'));
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid field definition !'
            );
            $this->set(compact('msg'));
            return;
        }
    }

    public function application_preview_before_submit_not_working() {
        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');
        $org_id = $this->Session->read('Org.Id');
        ini_set('memory_limit', '-1');

        $fields_to_display_in_details = array();
        $fields_detail_values = $this->LookupModelFieldDefinition->find('all', array('order' => array('LookupModelFieldDefinition.model_id' => 'asc'), 'conditions' => array('LookupModelFieldDefinition.model_id between ? and ?' => array(3, 25), 'LookupModelFieldDefinition.display_in_details_page' => 1)));
        foreach ($fields_detail_values as $field_value) {
            $model_name = $field_value['LookupModelDefinition']['model_name'];
            $this->loadModel($model_name);
            $this->$model_name->recursive = 0;
            $detailsValue = $this->$model_name->find('all', array('conditions' => array($model_name . ".org_id" => $org_id)));
            $field_value['detailsValue'] = $detailsValue;
            $fields_to_display_in_details[$field_value['LookupModelDefinition']['model_description']][] = $field_value;
        }
        $this->set(compact('fields_to_display_in_details'));
    }

    public function individual_preview($model_id = null) {
        $this->loadModel('LookupModelDefinition');
        $model_details = $this->LookupModelDefinition->findById($model_id);
        $model_description = $model_details['LookupModelDefinition']['model_description'];
        $this->set(compact('model_id', 'model_description'));
        try {
            if (empty($org_id)) {
                $org_id = $this->Session->read('Org.Id');
                if (empty($org_id)) {
                    $msg = array(
                        'type' => 'error',
                        'title' => 'Error... . . !',
                        'msg' => 'Invalid organization information !'
                    );
                    $this->set(compact('msg'));
                    return;
                }
            }
            $this->loadModel('CDBNonMfiBasicInfo');
            $this->CDBNonMfiBasicInfo->recursive = 2;
            $allDetails = $this->CDBNonMfiBasicInfo->find('first', array('conditions' => array('CDBNonMfiBasicInfo.id' => $org_id)));
            $mfiDetails = $allDetails['CDBNonMfiBasicInfo'];
            $this->loadModel('BasicModulePrimaryRegActDetail');
            $primaryRegActDetails = $this->BasicModulePrimaryRegActDetail->find('all', array('conditions' => array('BasicModulePrimaryRegActDetail.org_id' => $org_id)));
            $this->loadModel('LookupBasicProposedAddressType');
            $proposed_address_types = $this->LookupBasicProposedAddressType->find('all');
            $allProposedAddressDetails = $allDetails['BasicModuleProposedAddress'];
            $allRegistrationDetails = $allDetails['BasicModuleRegistrationDetail'];
            $allProposedBranchDetails = $allDetails['BasicModuleProposedBranchInfo'];
            $allBankInfoForTransactionDetails = $allDetails['BasicModuleBankInfoForTransaction'];
            $allRevolvingLoanFundDetails = $allDetails['BasicModuleRevolvingLoanFund'];
            $allProposedSavingDetails = $allDetails['BasicModuleProposedSavingsOrDepositInfo'];
            $allProposedLoanDetails = $allDetails['BasicModuleProposedLoanInfo'];
            $this->loadModel('LookupBasicStatementYear');
            $total_income_exp_balance_sheet_years = $this->LookupBasicStatementYear->find('all');
            $allIncomeExpenditureDetails = $allDetails['BasicModuleEstimatedIncomeExpenditureStatement'];
            $allBalanceSheetDetails = $allDetails['BasicModuleEstimatedBalanceSheet'];
            $commencementDateDetails = $allDetails['BasicModuleProposedDateOfCommencementMcOperation'];
            $this->loadModel('LookupBasicPlanForMcYear');
            $total_years = $this->LookupBasicPlanForMcYear->find('all');
            $allMcActivitiDetails = $allDetails['BasicModulePlanForMicroCreditActivity'];
            $this->loadModel('LookupBasicOfficeUsageType');
            $usage_types = $this->LookupBasicOfficeUsageType->find('all');
            $allOfficeSpaceUsageDetails = $allDetails['BasicModuleOfficeSpaceUsage'];
            $allImmovablePropertyDetails = $allDetails['BasicModuleOtherImmovableProperty'];
            $allGeneralBodyMemberDetails = $allDetails['BasicModuleGeneralBodyMemberInfo'];
            $allGBMemberEducationDetails = $allDetails['BasicModuleGeneralBodyMembersEducationDetail'];
            $allGBMemberFinancialInvolvmentDetails = $allDetails['BasicModuleGeneralBodyMembersFinancialInvolvement'];
            $allGBMemberCaseOrSuitDetails = $allDetails['BasicModulGeneralBodyMembersCaseOrSuitInfo'];
            $allGBMemberOtherBusinessInvolvmentDetails = $allDetails['BasicModulGeneralBodyMembersOtherBusinessInvolvment'];
            $allMembersOfCouncilDirectorDetails = $allDetails['BasicModulMembersOfCouncilDirectorsInformation'];
            $allProposedOrActiveCeoDetails = $allDetails['BasicModuleProposedOrActiveCeoInformation'];
            $allEmployeeDetails = $allDetails['BasicModuleEmployeeInformation'];
            $allSisterOrganizationDetails = $allDetails['BasicModuleSisterOrganizationInformation'];
            $allOtherProgramDetails = $allDetails['BasicModuleOtherProgramsInformation'];
            $allAuditAndRejectionDetails = $allDetails['BasicModuleAuditAndRejectionInformation'];

            $this->set(compact('org_id', 'mfiDetails', 'primaryRegActDetails', 'proposed_address_types', 'allProposedAddressDetails', 'allRegistrationDetails', 'allProposedBranchDetails', 'allBankInfoForTransactionDetails', 'allRevolvingLoanFundDetails', 'allProposedSavingDetails', 'allProposedLoanDetails', 'total_income_exp_balance_sheet_years', 'allIncomeExpenditureDetails', 'allBalanceSheetDetails', 'commencementDateDetails', 'total_years', 'allMcActivitiDetails', 'usage_types', 'allOfficeSpaceUsageDetails', 'allImmovablePropertyDetails', 'allGeneralBodyMemberDetails', 'allGBMemberEducationDetails', 'allGBMemberFinancialInvolvmentDetails', 'allGBMemberCaseOrSuitDetails', 'allGBMemberOtherBusinessInvolvmentDetails', 'allMembersOfCouncilDirectorDetails', 'allProposedOrActiveCeoDetails', 'allEmployeeDetails', 'allSisterOrganizationDetails', 'allOtherProgramDetails', 'allAuditAndRejectionDetails'));
        } catch (Exception $ex) {
            ////debug($ex->getMessage());
        }
    }

    public function preview($model_id = null, $unique_data_id = null) {
        $this->set(compact('model_id', 'unique_data_id'));
    }

    public function note_details($model_id = null, $field_id = null) {
        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');
//        //debug($model_id);
//        //debug($field_id);
//        $model_id = $this->Session->read('LookupModelDefinition.ModelId');
//        $fields_to_display_in_details = $this->LookupModelFieldDefinition->find('all', array('conditions' => array('LookupModelFieldDefinition.model_id' => $model_id, 'LookupModelFieldDefinition.display_in_details_page' => 1)));
//        if (!empty($fields_to_display_in_details)) {
//            $model_description = $fields_to_display_in_details[0]['LookupModelDefinition']['model_description'];
//            $title = $model_description;
//            $model_name = $fields_to_display_in_details[0]['LookupModelDefinition']['model_name'];
//            $this->loadModel($model_name);
//            $this->$model_name->recursive = 0;
//            $detailsValue = $this->$model_name->findById($unique_data_id);
//            
//            if (!$detailsValue) {
//                $msg = array(
//                    'type' => 'error',
//                    'title' => 'Error... . . !',
//                    'msg' => 'Invalid Information!'
//                );
//                $this->set(compact('msg'));
//                return;
//            }
//            $this->set(compact('detailsValue', 'fields_to_display_in_details', 'model_name'));
//        }
    }

    public function delete($model_id = null, $unique_data_id = null) {
        $this->loadModel('LookupModelDefinition');
        $values = $this->LookupModelDefinition->find('first', array('conditions' => array('LookupModelDefinition.id' => $model_id)));
        $model_name = $values['LookupModelDefinition']['model_name'];
        $this->loadModel($model_name);
        if ($this->$model_name->delete($unique_data_id)) {
            if (empty($data_type_id)) {
                $this->redirect(array('controller' => 'AdminModuleDynamicNonMfiCrudFormGenerators', 'action' => 'view?model_id=' . $model_id));
            }
        }
    }

}
