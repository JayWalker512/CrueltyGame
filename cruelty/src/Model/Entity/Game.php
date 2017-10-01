<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Game Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $start_time
 * @property \Cake\I18n\FrozenTime $end_time
 * @property int $total_plays
 * @property int $total_checked
 * @property float $ratio
 * @property bool $complete
 *
 * @property \App\Model\Entity\User[] $users
 */
class Game extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'start_time' => true,
        'end_time' => true,
        'total_plays' => true,
        'total_checked' => true,
        'ratio' => true,
        'complete' => true,
        'users' => true
    ];
}
