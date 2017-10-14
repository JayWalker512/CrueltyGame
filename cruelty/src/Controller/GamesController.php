<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Games Controller
 *
 * @property \App\Model\Table\GamesTable $Games
 *
 * @method \App\Model\Entity\Game[] paginate($object = null, array $settings = [])
 */
class GamesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('GamesUsers');
        $this->loadModel('Users');
    }

    public function isAuthorized()
    {
        return true;
    }

    public function play()
    {
        $loggedUser = $this->Auth->user();
        if (!empty($loggedUser)) {
            $loggedUser = $this->Users->get($loggedUser['id']);
        }
        $this->set('loggedUser', $loggedUser);

        $currentGame = $this->Games->find('all')->where([
            'complete' => false
        ])->first();
        if (empty($currentGame)) {
            $currentGame = $this->Games->createNewGame();
        }

        $usersPlay = $this->GamesUsers->find('all')->where([
            'user_id' => $loggedUser['id'],
            'game_id' => $currentGame->id
        ])->first();
        $this->set('usersPlay', $usersPlay);

        $bCanPlay = false;
        if (empty($usersPlay)) {
            $bCanPlay = true;
        }
        $this->set('bCanPlay', $bCanPlay);


        $pastGames = $this->Games->find('all')->order([
            'id' => 'DESC'
        ])->all();
        $this->set('pastGames', $pastGames);


        if ($this->request->is('post')) {
            if (!empty($loggedUser) && !empty($currentGame)) {

                if ($this->GamesUsers->insertPlay($loggedUser['id'], ($this->request->getData('checked_box') == "0" ? 0 : 1))) {
                    $this->Flash->success('Successfully recorded your decision!');

                    $this->redirect([
                        'controller' => 'Games',
                        'action' => 'play'
                    ]);
                } else {
                    $this->Flash->error('Failed to save your move!');
                    $this->redirect([
                        'controller' => 'Games',
                        'action' => 'play'
                    ]);
                }
            }
        }
    }

    public function botPlay()
    {
        //api key definition
        //128 characters long
        //letters (U and l), and numbers. No whitespace.

        $apiKey = $this->request->getQuery('api_key');
        $apiKey = trim($apiKey);


        if (preg_match('/[^a-zA-Z0-9]+/', $apiKey, $matches)) {
            //return $this->redirect($this->referer());
        } else {
            $loggedUser = $this->Users->find('all')->where([
                'api_key' => $apiKey
            ])->first();

            $checkedBox = $this->request->getQuery('c');

            if ($checkedBox == '0') {

            } else {

            }
        }


    }

    public function randomGame()
    {
        $users = $this->Users->find('all');
        foreach ($users as $user) {
            $this->GamesUsers->insertPlay($user->id, rand(0,1));
        }
        $this->Flash->success("Entered random plays for all users");
        return $this->redirect(['action' => 'play']);
    }

    public function runGame()
    {
        $this->Games->runGame();
        $this->redirect(['action' => 'play']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $games = $this->paginate($this->Games);

        $this->set(compact('games'));
        $this->set('_serialize', ['games']);
    }

    /**
     * View method
     *
     * @param string|null $id Game id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $game = $this->Games->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('game', $game);
        $this->set('_serialize', ['game']);
    }

}
