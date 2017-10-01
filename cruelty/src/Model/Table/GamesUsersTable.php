<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * GamesUsers Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GamesTable|\Cake\ORM\Association\BelongsTo $Games
 *
 * @method \App\Model\Entity\GamesUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\GamesUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GamesUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GamesUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GamesUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GamesUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GamesUser findOrCreate($search, callable $callback = null, $options = [])
 */
class GamesUsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('games_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Games', [
            'foreignKey' => 'game_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->boolean('checked_box')
            ->allowEmpty('checked_box');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['game_id'], 'Games'));

        return $rules;
    }

    public function insertPlay($userId, $checkedBox)
    {
        $gamesTable = TableRegistry::get('Games');

        $currentGame = $gamesTable->find('all')->where([
            'complete' => false
        ])->first();

        if (empty($currentGame)) {
            return false;
        }

        $pastPlay = $this->find('all')->where([
            'user_id' => $userId,
            'game_id' => $currentGame->id
        ])->first();

        if (!empty($pastPlay)) {
            return false;
        }

        $newPlay = $this->newEntity();
        $newPlay->user_id = $userId;
        $newPlay->game_id = $currentGame->id;
        $newPlay->checked_box = $checkedBox;
        if ($this->save($newPlay)) {

            $currentGame->total_plays = $currentGame->total_plays + 1;
            $gamesTable->save($currentGame);

            return true;
        }
        return false;
    }
}
