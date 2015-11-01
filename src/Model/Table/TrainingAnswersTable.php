<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Logos Model
 */
class TrainingAnswersTable extends Table {

public function initialize(array $config)
    {
        $this->belongsTo('Profiles', [
            'foreignKey' => 'UserID',
            'className' =>'Profiles',
            ]);
    }
	
/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	

}
