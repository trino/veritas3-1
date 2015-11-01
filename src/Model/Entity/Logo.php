<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Logo Entity.
 */
class Logo extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'logo' => true,
		'active' => true,
        'secondary' => true,
	];

}
