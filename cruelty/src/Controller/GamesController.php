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

    public function about()
    {

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
        ])->limit(5)->all();
        $this->set('pastGames', $pastGames);

        $leaderBoardUsers = $this->Users->find('all')->order([
            'score' => 'DESC'
        ])->limit(10)->all();
        $this->set('users', $leaderBoardUsers);


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

        $apiKey = $this->request->getQuery('apiKey');

        $loggedUser = $this->Users->getUserByApiKey($apiKey);

        if (!empty($loggedUser) && $loggedUser->enabled == true) {
            $checkedBox = $this->request->getQuery('c');
            if ($checkedBox == '0') {
                $this->GamesUsers->insertPlay($loggedUser->id, 0);
            } else {
                $this->GamesUsers->insertPlay($loggedUser->id, 1);
            }
        }

        $content = ['success' => 1];
        $this->setJsonResponse($content);
    }

    public function canPlay()
    {
        $apiKey = $this->request->getQuery('apiKey');
        $loggedUser = $this->Users->getUserByApiKey($apiKey);

        $content = ['canPlay' => 0];
        if (!empty($loggedUser) && $loggedUser->enabled) {
            $currentGame = $this->Games->getCurrentGame();
            $play = $this->GamesUsers->find('all')->where([
                'user_id' => $loggedUser->id,
                'game_id' => $currentGame->id
            ]);
            if ($play->count() == 0) {
                $content = ['canPlay' => 1];
            }
        }

        $this->setJsonResponse($content);
    }

    public function history()
    {
        $apiKey = $this->request->getQuery('apiKey');
        $loggedUser = $this->Users->getUserByApiKey($apiKey);

        $pastGames = $this->Games->find('all')->where([])->order([
            'id' => 'DESC'
        ]);

        if (!empty($loggedUser)) {
            $pastGames->contain('GamesUsers', function ($q) use ($loggedUser)  {
                return $q->where(['GamesUsers.user_id' => $loggedUser->id]);
            });
        }

        $pastGames = $pastGames->limit(100)->hydrate(false)->toArray();

        foreach($pastGames as $key => $game) {
            if ($pastGames[$key]['complete'] == false) {
                $pastGames[$key]['total_checked'] = "???";
                $pastGames[$key]['ratio'] = "???";
            }
            if (!empty($pastGames[$key]['games_users'])) {
                $pastGames[$key]['you_checked_box'] = $pastGames[$key]['games_users'][0]['checked_box'];
            }
            unset($pastGames[$key]['games_users']);
        }

        $content = $pastGames;
        $this->setJsonResponse($content);
    }

    /*public function randomGame()
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

    public function newGame()
    {
        $this->Games->createNewGame();
        $this->redirect(['action' => 'play']);
    }*/

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
