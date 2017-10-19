<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Mailer\Email;
use Cake\Log\Log;
use Cake\Network\Exception\SocketException;

/**
 * Users Model
 *
 * @property \App\Model\Table\GamesTable|\Cake\ORM\Association\BelongsToMany $Games
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Games', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'game_id',
            'joinTable' => 'games_users'
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
            ->scalar('username')
            ->lengthBetween('username', [1, 32])
            ->alphaNumeric('username')
            ->notEmpty('username');

        $validator
            ->email('email')
            ->notEmpty('email');

        $validator
            ->scalar('password')
            ->lengthBetween('password', [8, 64])
            ->notEmpty('password');

        $validator
            ->scalar('activation_string')
            ->allowEmpty('activation_string');

        $validator
            ->boolean('enabled')
            ->allowEmpty('enabled');

        $validator
            ->integer('score')
            ->allowEmpty('score');

        $validator
            ->scalar('api_key')
            ->allowEmpty('api_key');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }


    /**
    * Generate and return a random characters string
    *
    * Useful for generating passwords or hashes.
    *
    * The default string returned is 8 alphanumeric characters string.
    *
    * The type of string returned can be changed with the "type" parameter.
    * Seven types are - by default - available: basic, alpha, alphanum, num, nozero, unique and md5.
    *
    * I pulled this function from https://gist.github.com/irazasyed/5382685
    *
    * @param   string  $type    Type of random string.  basic, alpha, alphanum, num, nozero, unique and md5.
    * @param   integer $length  Length of the string to be generated, Default: 8 characters long.
    * @return  string
    */
    private function random_str($type = 'alphanum', $length = 8)
    {
        switch($type)
        {
            case 'basic'    : return mt_rand();
                break;
            case 'alpha'    :
            case 'alphanum' :
            case 'num'      :
            case 'nozero'   :
                $seedings             = array();
                $seedings['alpha']    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['alphanum'] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['num']      = '0123456789';
                $seedings['nozero']   = '123456789';

                $pool = $seedings[$type];

                $str = '';
                for ($i=0; $i < $length; $i++)
                {
                    $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
                }
                return $str;
                break;
            case 'unique'   :
            case 'md5'      :
                return md5(uniqid(mt_rand()));
                break;
        }
    }

    public function beforeSave($event, $entity, $options) {
        if ($entity->isNew()) {
            $entity->activation_string = $this->random_str('alphanum', 128);
            $entity->api_key = $this->random_str('alphanum', 128);
            $entity->enabled = false;
        }
    }

    public function sendActivation($userId)
    {
        $user = $this->get($userId);

        $email = new Email('default');

        $email->setTemplate('activate', 'default')
            ->setEmailFormat('html')
            ->setFrom("admin@brandonfoltz.com", "Cruelty Game")
            ->setSubject('Activate your account.')
            ->setViewVars([
                'user' => $user
            ]);

        $email->addTo($user->email);

        try {
            $email->send();
        } catch (\Cake\Network\Exception\SocketException $e) {
            Log::write("error", "Couldn't send activation email!");
        }
    }

    public function getUserByApiKey($apiKey)
    {
        $apiKey = trim($apiKey);

        if (preg_match('/[^a-zA-Z0-9]+/', $apiKey, $matches)) {
            return false; //api key invalid, bail out
        }

        $loggedUser = $this->find('all')->where([
            'api_key' => $apiKey
        ])->first();

        if (empty($loggedUser)) {
            return false;
        }

        return $loggedUser;
    }



}
