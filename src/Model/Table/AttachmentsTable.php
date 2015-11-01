<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Logos Model
 */
class AttachmentsTable extends Table {

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
        $this->belongsTo('Orders', [
            'className' => 'Orders',
            'foreignKey' => 'order_id',
            
        ]);
    }
	
/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	

}
