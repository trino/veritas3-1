<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Logos Model
 */
class OrdersTable extends Table {

/**
 * Initialize method
 * 
 * 
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
public function initialize(array $config)
    {
        /*$this->belongsTo('Profiles', [
           'foreignKey' => 'user_id',]);
        */
        $this->belongsTo('Profiles', [
            'foreignKey' => 'uploaded_for',
            'className' =>'Profiles'
            ]);
        $this->hasMany('PreScreening', [
            'className' => 'PreScreening',
            'dependent' => true,

        ]);
        $this->hasMany('Survey', [
            'className' => 'Survey',
            'dependent' => true,
            
        ]);
        $this->hasMany('Feedbacks', [
            'className' => 'Feedbacks',
            'dependent' => true,
            
        ]);
        $this->hasMany('Attachments', [
            'className' => 'Attachments',
            'dependent' => true,
            
        ]);
        $this->hasMany('Audits', [
            'className' => 'Audits',
            'dependent' => true,
            
        ]);
        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',]);
       
        $this->hasMany('DriverApplication', [
            'className' => 'DriverApplication',
            'dependent' => true,
            
        ]);
        $this->hasMany('RoadTest', [
            'className' => 'RoadTest',
            'dependent' => true,
            
        ]);
        $this->hasMany('ConsentForm', [
            'className' => 'ConsentForm',
            'dependent' => true,
            
        ]);
         
    }
	
/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	

}
