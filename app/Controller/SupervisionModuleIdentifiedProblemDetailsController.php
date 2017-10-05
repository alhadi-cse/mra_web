<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class SupervisionModuleIdentifiedProblemDetailsController extends AppController {
    public $helpers = array('Form', 'Html', 'Js', 'Paginator', 'Time');
    public $components = array('Paginator');    
    var $uses = array('SupervisionModuleBasicInformation','SupervisionModuleIdentifiedProblemDetail','AdminModuleUserProfile');

    public function view($opt = null) {
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_ids = $this->Session->read('User.GroupIds');        
        $viewable_user_groups = $this->request->query('viewable_user_groups');
        $this->set(compact('user_group_id','viewable_user_groups'));
        $user_groups = explode('_', $viewable_user_groups);

        if (empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this_state_ids = $this->request->query('this_state_ids');

        if (!empty($this_state_ids))
            $this->Session->write('Current.StateIds', $this_state_ids);
        else
            $this_state_ids = $this->Session->read('Current.StateIds');

        if (!empty($this_state_ids)) {
            $thisStateIds = split('_', $this_state_ids);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }

        if (!empty($viewable_user_groups))
            $this->Session->write('ViewableUserGroups', $viewable_user_groups);

        $opt_all = false;
        if ($user_group_id && $user_group_id == 1) {
            $opt_all = true;
        }
        $options['limit'] = 10;
        $options['order'] = array('BasicModuleBasicInformation.full_name_of_org' => 'ASC');
        $options['joins'] = array(             
            array('table' => 'supervision_module_basic_informations',
                'alias' => 'SupervisionModuleBasicInformation',
                'type' => 'LEFT',
                'conditions' => array(
                    'SupervisionModuleIdentifiedProblemDetail.supervision_basic_id = SupervisionModuleBasicInformation.id'
                )
            ),
            array('table' => 'supervision_module_org_selection_details',
                'alias' => 'SupervisionModuleOrgSelectionDetail',
                'type' => 'LEFT',
                'conditions' => array(
                    'SupervisionModuleBasicInformation.supervision_case_id = SupervisionModuleOrgSelectionDetail.id',
                    'SupervisionModuleOrgSelectionDetail.is_running_case'=>1
                )
            ),
            array('table' => 'lookup_supervision_categories',
                'alias' => 'LookupSupervisionCategory',
                'type' => 'LEFT',
                'conditions' => array(
                    'SupervisionModuleOrgSelectionDetail.supervision_category_id = LookupSupervisionCategory.id'                    
                )
            ),            
            array('table' => 'basic_module_basic_informations',
                'alias' => 'BasicModuleBasicInformation',
                'type' => 'LEFT',
                'conditions' => array(
                    'SupervisionModuleBasicInformation.org_id = BasicModuleBasicInformation.id'
                )
            ),
            array('table' => 'lookup_supervision_type_of_problems',
                'alias' => 'LookupSupervisionTypeOfProblem',
                'type' => 'LEFT',
                'conditions' => array(
                    'SupervisionModuleIdentifiedProblemDetail.type_of_problem_id = LookupSupervisionTypeOfProblem.id'
                )
            ),
            array('table' => 'lookup_supervision_title_of_problems',
                'alias' => 'LookupSupervisionTitleOfProblem',
                'type' => 'LEFT',
                'conditions' => array(
                    'SupervisionModuleIdentifiedProblemDetail.title_of_problem_id = LookupSupervisionTitleOfProblem.id'
                )
            )
        );
        $options['fields'] = array('SupervisionModuleOrgSelectionDetail.*','LookupSupervisionCategory.*','SupervisionModuleBasicInformation.*','BasicModuleBasicInformation.*','SupervisionModuleIdentifiedProblemDetail.*','LookupSupervisionTypeOfProblem.*','LookupSupervisionTitleOfProblem.*');
               
        if ($this->request->is('post')) {
            $option = $this->request->data['SupervisionModuleIdentifiedProblemDetail']['search_option'];
            $keyword = $this->request->data['SupervisionModuleIdentifiedProblemDetail']['search_keyword'];
            if(!empty($option) && !empty($keyword)) {                
                $options['conditions'] = array("$option LIKE '%$keyword%'");              
            }
        }
        $this->paginate = $options;        
        $this->Paginator->settings = $this->paginate;
        $values = $this->Paginator->paginate('SupervisionModuleIdentifiedProblemDetail');        
        //debug($values);
        $this->set(compact('values','IsValidUser', 'supervision_basic_id', 'user_group_id', 'thisStateIds', 'opt_all'));
    }

    public function preview($supervision_basic_id = null) {
        $this->set(compact('supervision_basic_id'));
    }

    public function details($id = null) { 
        $this->SupervisionModuleIdentifiedProblemDetail->bindModel(
            array('belongsTo' => array(
                    'SupervisionModuleBasicInformation' => array(
                        'foreignKey' => 'supervision_basic_id'
                    ),
                    'LookupSupervisionTypeOfProblem' => array(
                        'foreignKey' => 'type_of_problem_id'
                    ),
                    'LookupSupervisionTitleOfProblem' => array(
                        'foreignKey' => 'title_of_problem_id'
                    )
                )
            )
        );
        $this->SupervisionModuleIdentifiedProblemDetail->recursive = 2;
        $allDetails = $this->SupervisionModuleIdentifiedProblemDetail->findById($id);
        $this->set(compact('allDetails'));
    }
    
    public function all_details($supervision_basic_id = null) {
        $this->SupervisionModuleIdentifiedProblemDetail->bindModel(
            array('belongsTo' => array(
                    'SupervisionModuleBasicInformation' => array(
                        'foreignKey' => 'supervision_basic_id'
                    ),
                    'LookupSupervisionTypeOfProblem' => array(
                        'foreignKey' => 'type_of_problem_id'
                    ),
                    'LookupSupervisionTitleOfProblem' => array(
                        'foreignKey' => 'title_of_problem_id'
                    )
                )
            )
        );
        $this->SupervisionModuleIdentifiedProblemDetail->recursive = 0;
        $values = $this->SupervisionModuleIdentifiedProblemDetail->find('all',array('SupervisionModuleIdentifiedProblemDetail.supervision_basic_id' => $supervision_basic_id));
        $this->set(compact('values'));
    }

    public function add_problems() {
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupId');
        $viewable_user_groups = $this->request->query('viewable_user_groups');
        
        if ((!empty($user_group_id) && $user_group_id!=1) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($supervision_basic_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }
        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = split('_', $this_state_ids);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }      
        $thisStateId = $this->request->query('thisStateIds');  
        $org_name_options = array();
        if(!empty($thisStateId)) {
            $org_values = $this->SupervisionModuleBasicInformation->find('all', array('fields' => array('SupervisionModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org'),
                                'conditions'=>array('SupervisionModuleBasicInformation.supervision_state_id'=>$thisStateId),'recursive' => 0));
            $org_name_options = Hash::combine($org_values, '{n}.SupervisionModuleBasicInformation.id', '{n}.BasicModuleBasicInformation.full_name_of_org');        
        }
        $this->SupervisionModuleIdentifiedProblemDetail->bindModel(
            array('belongsTo' => array(
                    'SupervisionModuleBasicInformation' => array(
                        'foreignKey' => 'supervision_basic_id'
                    ),
                    'LookupSupervisionTypeOfProblem' => array(
                        'foreignKey' => 'type_of_problem_id'
                    ),
                    'LookupSupervisionTitleOfProblem' => array(
                        'foreignKey' => 'title_of_problem_id'
                    )
                )
            )
        );
        $type_of_problem_options = $this->SupervisionModuleIdentifiedProblemDetail->LookupSupervisionTypeOfProblem->find('list', array('fields' => array('LookupSupervisionTypeOfProblem.id', 'LookupSupervisionTypeOfProblem.type_of_problems'), 'order' => array('LookupSupervisionTypeOfProblem.type_of_problems')));
        $this->set(compact('IsValidUser', 'supervision_basic_id', 'org_name_options','this_state_ids','viewable_user_groups','type_of_problem_options'));

        if ($this->request->is('post')) {            
            $reqData = $this->request->data; 
            $supervision_basic_id = $reqData['SupervisionModuleIdentifiedProblemDetail']['supervision_basic_id'];
            $type_of_problem_id = $reqData['SupervisionModuleIdentifiedProblemDetail']['type_of_problem_id'];
            $title_of_problem_id = $reqData['SupervisionModuleIdentifiedProblemDetail']['title_of_problem_id'];
            $condition = array('supervision_basic_id' => $supervision_basic_id,'type_of_problem_id'=>$type_of_problem_id,'title_of_problem_id'=>$title_of_problem_id);
            $existing_values = $this->SupervisionModuleIdentifiedProblemDetail->find('first', array('conditions' => $condition, 'recursive' => -1));
            if(empty($existing_values)) {
                $this->SupervisionModuleIdentifiedProblemDetail->create();
                $newData = $this->SupervisionModuleIdentifiedProblemDetail->save($reqData);
                if ($newData) {
                    return $this->redirect(array('action' => 'view?this_state_ids='.$this_state_ids.'&viewable_user_groups='.$viewable_user_groups));
                }
            }
            else {
                $message = 'Information already exists !';
                $msg = array(
                    'type' => 'error',
                    'title' => 'Error... . . !',
                    'msg' => $message
                );
                $this->set(compact('msg'));
            }
        }
    }
    
    public function edit($id = null, $supervision_basic_id = null) { 
        $IsValidUser = $this->Session->read('User.IsValid');
        $user_group_id = $this->Session->read('User.GroupId');
        $viewable_user_groups = $this->request->query('viewable_user_groups');        
        if ((empty($supervision_basic_id) && !empty($user_group_id) && $user_group_id!=1) || empty($IsValidUser)) {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid user information !'
            );

            if (empty($supervision_basic_id)) {
                $msg['msg'] = 'Invalid organization information !';
            }

            $this->set(compact('msg'));
            return;
        }
        $this_state_ids = $this->Session->read('Current.StateIds');
        if (!empty($this_state_ids)) {
            $thisStateIds = split('_', $this_state_ids);
        } else {
            $msg = array(
                'type' => 'warning',
                'title' => 'Warning... . . !',
                'msg' => 'Invalid State Information !'
            );
            $this->set(compact('msg'));
            return;
        }
        $this->SupervisionModuleIdentifiedProblemDetail->bindModel(
            array('belongsTo' => array(
                    'SupervisionModuleBasicInformation' => array(
                        'foreignKey' => 'supervision_basic_id'
                    ),
                    'LookupSupervisionTypeOfProblem' => array(
                        'foreignKey' => 'type_of_problem_id'
                    ),
                    'LookupSupervisionTitleOfProblem' => array(
                        'foreignKey' => 'title_of_problem_id'
                    )
                )
            )
        );
        $post = $this->SupervisionModuleIdentifiedProblemDetail->findById($id);
        $this->loadModel('BasicModuleBasicInformation');        
        $org_id = $post['SupervisionModuleBasicInformation']['org_id'];
        $org_name_options = $this->BasicModuleBasicInformation->find('list', array('fields' => array('BasicModuleBasicInformation.id', 'BasicModuleBasicInformation.full_name_of_org'),
                                'conditions'=>array('BasicModuleBasicInformation.id'=>$org_id)));
        $type_of_problem_options = $this->SupervisionModuleIdentifiedProblemDetail->LookupSupervisionTypeOfProblem->find('list', array('fields' => array('LookupSupervisionTypeOfProblem.id', 'LookupSupervisionTypeOfProblem.type_of_problems'), 'order' => array('LookupSupervisionTypeOfProblem.type_of_problems')));
        $type_of_problem_id = $post['SupervisionModuleIdentifiedProblemDetail']['type_of_problem_id'];
        $title_of_problem_id = $post['SupervisionModuleIdentifiedProblemDetail']['title_of_problem_id'];
        $title_of_problem_options = $this->SupervisionModuleIdentifiedProblemDetail->LookupSupervisionTitleOfProblem->find('list', array(
            'fields' => array('LookupSupervisionTitleOfProblem.id', 'LookupSupervisionTitleOfProblem.title_of_problems'),
            'conditions' => array('LookupSupervisionTitleOfProblem.type_of_problem_id' => $type_of_problem_id),
            'order' => array('LookupSupervisionTitleOfProblem.title_of_problems'),
            'recursive' => -1
        ));        
        $this->set(compact('IsValidUser', 'supervision_basic_id', 'org_id', 'type_of_problem_id', 'title_of_problem_id', 'org_name_options','this_state_ids','viewable_user_groups','type_of_problem_options','title_of_problem_options'));
        if ($this->request->is(array('post', 'put'))) {
            $this->SupervisionModuleIdentifiedProblemDetail->id = $id;
            if ($this->SupervisionModuleIdentifiedProblemDetail->save($this->request->data)) {                
                return $this->redirect(array('action' => 'view?this_state_ids='.$this_state_ids.'&viewable_user_groups='.$viewable_user_groups));            
            }            
        }        
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }
    
    function update_title_of_problem_selection() {
        $this->SupervisionModuleIdentifiedProblemDetail->bindModel(
            array('belongsTo' => array(                    
                    'LookupSupervisionTitleOfProblem' => array(
                        'foreignKey' => 'title_of_problem_id'
                    )
                )
            )
        );
        $type_of_problem_id = $this->request->data['SupervisionModuleIdentifiedProblemDetail']['type_of_problem_id'];
        $title_of_problem_options = $this->SupervisionModuleIdentifiedProblemDetail->LookupSupervisionTitleOfProblem->find('list', array(
            'fields' => array('LookupSupervisionTitleOfProblem.id', 'LookupSupervisionTitleOfProblem.title_of_problems'),
            'conditions' => array('LookupSupervisionTitleOfProblem.type_of_problem_id' => $type_of_problem_id),
            'order' => array('LookupSupervisionTitleOfProblem.title_of_problems'),
            'recursive' => -1
        ));
        $this->set(compact('title_of_problem_options'));
        $this->layout = 'ajax';
    }
}