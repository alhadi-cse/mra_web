<?php

App::uses('AppModel', 'Model');

class LookupAdminBoundaryDistrict extends AppModel {

    public $virtualFields = array('district_with_code' => "CONCAT(LookupAdminBoundaryDistrict.district_name, ' (', LookupAdminBoundaryDistrict.id, ')')");

}
