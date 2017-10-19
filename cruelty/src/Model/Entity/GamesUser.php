<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GamesUser Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $game_id
 * @property bool $checked_box
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Game $game
 */
class GamesUser extends Entity
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
        'user_id' => false,
        'game_id' => false,
        'checked_box' => false,
        'user' => false,
        'game' => false
    ];
}
