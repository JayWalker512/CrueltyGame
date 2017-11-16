<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

use Cake\Mailer\Email;
use Cake\Log\Log;
use Cake\Network\Exception\SocketException;
use Cake\Core\Configure;

/**
 * Games Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\Game get($primaryKey, $options = [])
 * @method \App\Model\Entity\Game newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Game[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Game|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Game patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Game[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Game findOrCreate($search, callable $callback = null, $options = [])
 */
class GamesTable extends Table
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

        $this->setTable('games');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Users', [
            'foreignKey' => 'game_id',
            'targetForeignKey' => 'user_id',
            //'joinTable' => 'games_users'
            'through' => 'GamesUsers'
        ]);
        $this->hasMany('GamesUsers', [
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
            ->dateTime('start_time')
            ->allowEmpty('start_time');

        $validator
            ->dateTime('end_time')
            ->allowEmpty('end_time');

        $validator
            ->integer('total_plays')
            ->allowEmpty('total_plays');

        $validator
            ->integer('total_checked')
            ->allowEmpty('total_checked');

        $validator
            ->decimal('ratio')
            ->allowEmpty('ratio');

        $validator
            ->boolean('complete')
            ->allowEmpty('complete');

        return $validator;
    }

    public function runGame()
    {
        //get the current game
        $currentGame = $this->find('all')->where([
            'complete' => false
        ])->contain(['Users'])->first();

        //get all the plays counts
        $gamesUsersTable = TableRegistry::get('GamesUsers');
        $currentGameCheckedCount = $gamesUsersTable->find();
        $currentGameCheckedCount = $currentGameCheckedCount->select([
            'count' => $currentGameCheckedCount->func()->count('*')
        ])->where([
            'game_id' => $currentGame->id,
            'checked_box' => true
        ])->first()->count;

        $currentGameUncheckedCount = $gamesUsersTable->find();
        $currentGameUncheckedCount = $currentGameUncheckedCount->select([
            'count' => $currentGameUncheckedCount->func()->count('*')
        ])->where([
            'game_id' => $currentGame->id,
            'checked_box' => false
        ])->first()->count;

        $totalPlays = $currentGameCheckedCount + $currentGameUncheckedCount;

        //If not enough players, extend the end time and bail;
        if ($totalPlays < 2) {
            $currentGame->end_time = Time::now()->addHour(1);
            $this->save($currentGame);
            return false;
        }

        //update current game fields
        $currentGame->total_checked = $currentGameCheckedCount;
        $currentGame->total_plays = $totalPlays;
        $currentGame->ratio = round((float)$currentGameCheckedCount / (float)$totalPlays, 2);

        //save game as 'complete'
        $currentGame->complete = true;
        $this->save($currentGame);

        //get all the users that played this round

        $usersTable = TableRegistry::get('Users');
        $usersWhoCheckedThisGameIdArray = $gamesUsersTable->find('list', [
            'valueField' => 'user_id'
        ])->where([
            'game_id' => $currentGame->id,
            'checked_box' => true
        ])->toArray();
        $currentGameCheckedUsers = $usersTable->find('all')->where([
            'id IN' => (!empty($usersWhoCheckedThisGameIdArray) ? $usersWhoCheckedThisGameIdArray : [0])
        ]);

        //update users scores
        foreach ($currentGameCheckedUsers as $user) {
            if ($currentGame->ratio > 0.5) {
                if ($user->score != 0) {
                    $user->score = (int)$user->score - 10;
                }
            } else {
                $user->score = (int)$user->score + 10;
            }
            $usersTable->save($user);
        }

        //create next incomplete game & save
        $this->createNewGame();

        //send notification emails
        $email = new Email('default');

        $email->setTemplate('end_game', 'default')
            ->setEmailFormat('html')
            ->setFrom("admin@brandonfoltz.com", "Cruelty Game")
            ->setSubject('Cruelty Game Results')
            ->setViewVars([
                'ratio' => $currentGame->ratio,
                'gameDomain' => Configure::read('GameDomain')
            ]);

        foreach ($currentGame->users as $user) {
            if ($user->receive_emails && $user->enabled) {
                $email->addBcc($user->email);
            }
        }

        try {
            $email->send();
        } catch (\Cake\Network\Exception\SocketException $e) {
            Log::write("error", "Couldn't send game result email!");
        }
    }

    public function createNewGame()
    {
        $newGame = $this->newEntity();
        $newGame->complete = false;
        $newGame->start_time = Time::now();
        $newGame->end_time = new Time('now +1 hours');
        $newGame->total_plays = 0;
        $newGame->total_checked = 0;
        $this->save($newGame);
        return $newGame;
    }

    public function getCurrentGame()
    {
        $currentGame = $this->find('all')->where([
            'complete' => false
        ])->first();

        return $currentGame;
    }
}
