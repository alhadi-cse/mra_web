<?php

App::uses('AppModel', 'Model');

class LookupEligibleDistrict extends AppModel {
    public $belongsTo = array(
        'LookupAdminBoundaryDistrict' => array(
        'className'  => 'LookupAdminBoundaryDistrict',
        'foreignKey' => 'district_id'
        )
    );
}
