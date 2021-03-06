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
        $loggedUser = $this->Auth->user();
        if (!empty($loggedUser)) {
            $this->set('loggedUser', $this->Users->get($loggedUser['id']));
        }
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
        $this->set('currentGame', $currentGame);

        $userPlays = $this->GamesUsers->find('list', [
            'keyField' => 'game_id',
            'valueField' => 'checked_box'
        ])->where([
            'user_id' => $loggedUser['id'],
            'game_id >= ' => $currentGame['id'] - 10
        ])->order([
            'game_id' => 'DESC'
        ])->limit(10)->toArray();
        $this->set('userPlays', $userPlays);

        $bCanPlay = false;
        if (!isset($userPlays[$currentGame['id']])) {
            $bCanPlay = true;
        }
        $this->set('bCanPlay', $bCanPlay);


        $pastGames = $this->Games->find('all')->order([
            'id' => 'DESC'
        ])->limit(10)->all();
        $this->set('pastGames', $pastGames);

        $leaderBoardUsers = $this->Users->find('all')->where([
            'enabled' => true
        ])->order([
            'score' => 'DESC',
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

        $success = false;
        if (!empty($loggedUser) && $loggedUser->enabled == true) {
            $checkedBox = $this->request->getQuery('c');
            if ($checkedBox == '0') {
                $success = $this->GamesUsers->insertPlay($loggedUser->id, 0);
            } else {
                $success = $this->GamesUsers->insertPlay($loggedUser->id, 1);
            }
        }

        $content = ['success' => $success];
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
}
