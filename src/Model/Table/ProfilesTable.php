<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Logos Model
 */
class ProfilesTable extends Table {

/**
 * Initialize method
 * 
 * 
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
public function initialize(array $config) {
        $this->hasMany('Orders', [
            'className' => 'Orders',
        ]);
        $this->hasOne('Sidebar' ,[
            'className' => 'Sidebar',
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            //'conditions' => ['Addresses.primary' => '1'],
            'dependent' => true
        ]);
        
    }

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	

}
