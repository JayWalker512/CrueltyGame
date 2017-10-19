<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function isAuthorized($user)
    {
        return true;
    }

    public function initilialize()
    {
        parent::initialize();

        $this->Auth->allow(['login', 'logout', 'add']);
    }

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
    }

    public function api()
    {
        if ($this->Auth->user()) {
            $loggedUser = $this->Users->get($this->Auth->user('id'));
            if (!empty($loggedUser)) {
                $this->set('apiKey', $loggedUser->api_key);
            }
        }
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user && $user['enabled'] == true) {
                $this->Auth->setUser($user);
                $this->Flash->success('Successfully logged in!');
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect, or your account is disabled.');
        }
    }

    public function logout()
    {
        $this->Flash->success('You are now logged out.');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Users->sendActivation($user->id);
                $this->Flash->success(__('Your account has been created. Check your email for an activation link.'));

                return $this->redirect(['controller' => 'games', 'action' => 'play']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Games']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $games = $this->Users->Games->find('list', ['limit' => 200]);
        $this->set(compact('user', 'games'));
        $this->set('_serialize', ['user']);
    }

    public function activate($activationString) {
        $activationString = trim($activationString);
        if (!preg_match('/[^a-zA-Z0-9]+/', $activationString, $matches) && strlen($activationString) == 128) {

            $matchedUser = $this->Users->findByActivationString($activationString)->first();


            if ($matchedUser) {
                $matchedUser->enabled = true;

                if ($this->Users->save($matchedUser)) {
                    $this->Flash->success("Your account has been activated.");
                    return $this->redirect(['controller' => 'games', 'action' => 'play']);
                } else {
                    $this->Flash->error("Something went wrong...");
                    return $this->redirect($this->referer());
                }
            }
        }
        return $this->redirect($this->referer());
    }
}
