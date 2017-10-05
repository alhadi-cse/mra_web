<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class ProductModuleNewProductManagementsController extends AppController {
    public $helpers = array('Html','Form','Js','Paginator');
    public $components = array('Paginator');
    public $paginate = array(
        'limit' => 7,
        'order' => array('LookupModelDefinition.model_name' => 'ASC')
   );

    /* public function view(){
        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');        
        $sql = "SELECT `LookupModelFieldDefinition`.`model_id`,
            `LookupModelFieldDefinition`.`field_id`,
            `LookupModelFieldDefinition`.`control_type`,
            `LookupModelFieldDefinition`.`css_class_name`,
            `LookupModelFieldDefinition`.`data_type`,
            `LookupModelFieldDefinition`.`field_description`,
            `LookupModelFieldDefinition`.`field_name`,
            `LookupModelFieldDefinition`.`field_size`,
            `LookupModelFieldDefinition`.`field_sorting_order`,
            `LookupModelDefinition`.`module_id`,
            `LookupModelDefinition`.`model_name`,
            `LookupModelDefinition`.`model_description`,
            `LookupModelDefinition`.`lookup_or_detail_id`,
            `LookupModelDefinition`.`model_wise_table_name`
        FROM
            `mra_web_db`.`lookup_model_definitions` as `LookupModelDefinition`
            RIGHT JOIN `mra_web_db`.`lookup_field_definitions` as `LookupModelFieldDefinition`
            ON (`LookupModelFieldDefinition`.`model_id` = `LookupModelDefinition`.`id`)
            WHERE `LookupModelDefinition`.`module_id`=7";
        try {
            $values = $this->executeQuery($sql);
            $this->set(compact('values'));
        } catch (Exception $ex) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => $e->getMessage()
            );
            $this->set(compact('msg'));            
        }        
    } */

    public function view(){
        $this->loadModel('LookupModelDefinition');
        $this->loadModel('LookupModelFieldDefinition');        
        
        try {
            if ($this->request->is('post'))
            {
                $option = $this->request->data['ProductModuleNewProductManagement']['search_option'];  
                $keyword = $this->request->data['ProductModuleNewProductManagement']['search_keyword'];
                $condition = array("$option LIKE '%$keyword%'");
                $this->paginate = array(                 
                'limit' => 6,
                'conditions' => $condition);
            }
            $this->LookupModelFieldDefinition->recursive = 0;
            $this->Paginator->settings = $this->paginate;
            $values = $this->Paginator->paginate('LookupModelFieldDefinition');
            $this->set(compact('values'));
        } catch (Exception $ex) {
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => $e->getMessage()
            );
            $this->set(compact('msg'));            
        }        
    }
    
    public function add(){
        try{
            $this->loadModel('LookupModelDefinition');
            $this->loadModel('LookupModelFieldDefinition');
            $this->loadModel('AdminModuleModule');
            $this->loadModel('AdminModuleSubMenu');
            $this->loadModel('AdminModuleDetailSubMenuGroup');
            $this->loadModel('AdminModuleUserGroup');

            $module_options = $this->AdminModuleModule->find('list',array('fields'=>array('AdminModuleModule.module_id','AdminModuleModule.module_name'),'conditions'=>array('AdminModuleModule.module_id'=>7)));
            $user_group_options = $this->AdminModuleUserGroup->find('list',array('fields'=>array('AdminModuleUserGroup.id','AdminModuleUserGroup.group_name'),'conditions'=>array('AdminModuleUserGroup.id'=>array(1,2))));
            $model_options = $this->LookupModelDefinition->find('list',array('fields'=>array('LookupModelDefinition.model_name','LookupModelDefinition.model_description'),'conditions'=>array('LookupModelDefinition.lookup_or_detail_id'=>1)));
            $this->Session->write('LookupModelDefinition.ModelOptions', $model_options);
            $this->set(compact('module_options','user_group_options'));

            if(!empty($this->request->data)){
                $posted_data = $this->request->data['ProductModuleNewProductManagement'];
//                debug($posted_data);
//                exit;
/*            $posted_data = array(
                'module_id' => '7',
                'lookup_or_detail_id' => '1',
                'model_wise_table_name' => 'lookup_test1_names',
                'model_description' => 'Table 1 Name Information',
                (int) 0 => array(
                        'field_sorting_order' => '1',
                        'field_name' => 'id',
                        'field_description' => 'Id',
                        'data_type' => 'integer',
                        'field_size' => '',
                        'is_primary_key' => '1',
                        'is_auto_increment' => '1',
                        'is_exists_validity' => '0',
                        'is_mandatory' => '0',
                        'control_type' => '',
                        'select_option_sources' => '',
                        'dropdown_display_field' => '',
                        'dropdown_value_field' => '',
                        'dropdown_condition_field' => '',
                        'containable_model_names' => '',
                        'parent_model_name_for_select_option' => '',
                        'dependent_dropdown_display_field' => '',
                        'dependent_dropdown_value_field' => '',
                        'css_class_name' => '',
                        'is_published' => '1',
                        'display_in_search_params' => '1',
                        'display_in_view_page' => '1',
                        'display_in_add_page' => '0',
                        'display_in_edit_page' => '0',
                        'display_in_details_page' => '1'
                ),
                (int) 1 => array(
                        'field_sorting_order' => '1',
                        'field_name' => 'test1',
                        'field_description' => 'test 1',
                        'data_type' => 'varchar',
                        'field_size' => '50',
                        'is_primary_key' => '0',
                        'is_auto_increment' => '0',
                        'is_exists_validity' => '0',
                        'is_mandatory' => '0',
                        'control_type' => 'text',
                        'select_option_sources' => '',
                        'dropdown_display_field' => '',
                        'dropdown_value_field' => '',
                        'dropdown_condition_field' => '',
                        'containable_model_names' => '',
                        'parent_model_name_for_select_option' => '',
                        'dependent_dropdown_display_field' => '',
                        'dependent_dropdown_value_field' => '',
                        'css_class_name' => 'text',
                        'is_published' => '1',
                        'display_in_search_params' => '1',
                        'display_in_view_page' => '1',
                        'display_in_add_page' => '1',
                        'display_in_edit_page' => '1',
                        'display_in_details_page' => '1'
                )
            );
//*/
                if(!empty($posted_data)){
                    $model_data = array_slice($posted_data,0,4);
                    $field_data = array_slice($posted_data,4,count($posted_data)-1);

                    //create table
                    $model_wise_table_name = Inflector::tableize($model_data['model_wise_table_name']);
                    $max_model_id = $this->LookupModelDefinition->find('first',array('fields'=>array('MAX(LookupModelDefinition.id) AS max_value')));
                    if($max_model_id[0]['max_value']==null){
                        $model_id = 1;
                    }else{
                        $model_id = $max_model_id[0]['max_value']+1;
                    }
                    $model_name =  Inflector::classify($model_wise_table_name);
                    $table_name_from_model = Inflector::tableize($model_name);
                    $controller = 'AdminModuleDynamicCrudFormGenerators';
                    $module_id = $model_data['module_id'];
                    $menu_id = $model_data['lookup_or_detail_id'];
                    $model_description = $model_data['model_description'];
                    $user_group_ids = array();
                    if($menu_id=='1'){
                        $user_group_ids = array('1');
                    }elseif($menu_id=='2'){
                        $user_group_ids = array('1','2');
                    }

                    $table_desc_sql = "SHOW TABLES LIKE '".$table_name_from_model."'";
                    $existing_table_data = $this->executeQuery($table_desc_sql);
                    $existing_modelwise_table_name = $this->LookupModelDefinition->find('first',array('fields'=>array('LookupModelDefinition.model_wise_table_name'),'conditions'=>array('LookupModelDefinition.model_wise_table_name'=>$table_name_from_model)));

                    $data_to_save_in_model_definition = array();
                    if(empty($existing_table_data)&&empty($existing_modelwise_table_name)){
                        $data_to_save_in_model_definition = array('id' => $model_id,'module_id'=>$module_id,'model_wise_table_name'=>$table_name_from_model,'model_name' => $model_name,'model_description' => $model_description);
                        $this->LookupModelDefinition->create();
                        $this->LookupModelDefinition->save($data_to_save_in_model_definition);
                        $field_id = 0;
                        $max_field_id = $this->LookupModelFieldDefinition->find('first',array('fields'=>array('MAX(LookupModelFieldDefinition.field_id) AS max_value'),'conditions'=>array('LookupModelFieldDefinition.model_id'=>$model_id)));
                        if($max_field_id[0]['max_value']==null){
                            $field_id = 1;
                        }else{
                            $field_id = $max_field_id[0]['max_value']+1;
                        }
                        $fields = "";                        
                        $data_to_save_in_field_definition = array();
                        foreach($field_data as $value){
                            $data_to_save_in_field_definition = array_merge(array('id' => $field_id,'field_id' => $field_id,'model_id' => $model_id),$value);
                            $this->LookupModelFieldDefinition->create();
                            $this->LookupModelFieldDefinition->save($data_to_save_in_field_definition);
                            $field_id++;
                            $field_name = $value['field_name'];
                            $data_type = $value['data_type'];
                            $field_size = $value['field_size'];
                            $is_primary_key = $value['is_primary_key'];
                            $is_auto_increment = $value['is_auto_increment'];
                            if($is_primary_key==1){                                
                                $this->Session->write('Table.PrimaryKey', 'PRIMARY KEY ('.$field_name.')');                                
                            }
                            if($is_auto_increment==1){
                                $auto_increment = "NOT NULL AUTO_INCREMENT";
                            }else{
                                $auto_increment = "";
                            }
                            
                            if($data_type=="varchar"){
                                $field_size = "($field_size)";
                            }else{
                                $field_size = "";
                            }                            
                            $fields = $fields.$field_name." ".$data_type." ".$field_size." ".$auto_increment.",";                          
                        }        
                        
                        $max_sub_menu_id = $this->AdminModuleSubMenu->find('first',array('fields'=>array('MAX(AdminModuleSubMenu.sub_menu_id) AS max_value'),'conditions'=>array('AdminModuleSubMenu.module_id' => $module_id, 'AdminModuleSubMenu.menu_id' => $menu_id)));
                        if($max_sub_menu_id[0]['max_value']==null){
                            $sub_menu_id = 1;
                        }else{
                            $sub_menu_id = $max_sub_menu_id[0]['max_value']+1;
                        }
                        $action = "view";
                        //add to submenu
                        $data_to_save_in_submenu = array(
                            'module_id' => $module_id,
                            'menu_id' => $menu_id,
                            'sub_menu_id' => $sub_menu_id,
                            'sub_menu_title' => $model_description,
                            'controller' => $controller,
                            'controller_action' => $action,
                            'controller_parameters' => 'model_id='.$model_id
                        );
                        $this->AdminModuleSubMenu->create();
                        $this->AdminModuleSubMenu->save($data_to_save_in_submenu);
                       
                        //add to submenu group
                        foreach($user_group_ids as $group_id){
                            $data_to_save_in_submenu_group = array();
                            $data_to_save_in_submenu_group['module_id'] = $module_id;
                            $data_to_save_in_submenu_group['menu_id'] = $menu_id;
                            $data_to_save_in_submenu_group['sub_menu_id'] = $sub_menu_id;
                            $data_to_save_in_submenu_group['user_group_id'] = $group_id;
                            $this->AdminModuleDetailSubMenuGroup->create();
                            $this->AdminModuleDetailSubMenuGroup->save($data_to_save_in_submenu_group); 
                        }
                        
                        $primary_key = $this->Session->read('Table.PrimaryKey');
                        if($primary_key!=null){
                            $fields = $fields." ".$primary_key.",";
                        }
                        $fields = rtrim($fields, ",");
                        $create_table_sql = "CREATE TABLE ".$model_wise_table_name ." (".$fields.")";  
                        
                        $this->executeQuery($create_table_sql);                        
                        //create model with association
                        $dir = new Folder(ROOT);
                        $file = new File($dir->path.'/app/Model/'.$model_name.'.php', false, 0644);
                        if(!$file->exists()){
                            $file = new File($dir->path.'/app/Model/'.$model_name.'.php', true, 0644);
                            $file_data =  "<?php \r\n\r\n"
                                        . "App::uses('AppModel', 'Model'); \r\n\r\n"
                                        . "class ".$model_name." extends AppModel {\r\n"
                                        . "    //public "."$"."belongsTo = array(\r\n"
                                        . "\t//\r\n"
                                        . "    //);\r\n"
                                        . "}";
                            $file->append($file_data);
                        }
                        //$this->redirect(array('action' => 'view'));
                        echo "<script> location.reload(); </script>";                         
                    }else{
                        $msg = array(
                            'type' => 'error',
                            'title' => 'Error... ... !',
                            'msg' => 'Table information already exists'
                        );
                        $this->set(compact('msg')); 
                    }
                }
            }
        }catch(Exception $e){
            $msg = array(
                'type' => 'error',
                'title' => 'Error... ... !',
                'msg' => $e->getMessage()
            );
            $this->set(compact('msg'));            
//          debug($e->getMessage());
//          exit;
        }
    }
}