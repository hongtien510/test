<?php
namespace Admin\Controller;

use Admin\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
/**
 * Users Controller
 *
 * @property \Admin\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
	public $current_user = null;    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login']);
        
		$this->set('active_menu', 'users');
		$this->current_user = $this->Auth->user();
    }
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
		if ($this->current_user['role'] != 'admin') {
			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
		}
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
		if ($this->current_user['role'] != 'admin') {
			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
		}
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		if ($this->current_user['role'] != 'admin') {
			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
		}        
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		if ($this->current_user['role'] != 'admin') {
			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
		}        
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
		if ($this->current_user['role'] != 'admin') {
			return $this->redirect(['controller' => 'pages', 'action' => 'prohibited']);
		}
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	public function login() {
		if (isset($this->current_user)) {
			return $this->redirect($this->Auth->redirectUrl());
		}
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error( __('Username or password is incorrect') );
            }
         } 
	}

	public function logout() {
	    return $this->redirect($this->Auth->logout());
	}
    
    public function change_password()
    {
        if(!$this->request->session()->check('Auth.User')){
          $this->redirect(['controller' => 'users', 'action' => 'login']);    
        }
        
        if ($this->request->is('post')) {
            
            //echo Security::hash('123456', 'blowfish', '$10$1nMmKt/iCxao7L06zl1Xa.Ykq5n1S7CvjovwSNkRU5q2fNH53BLIK');
            //echo $password = AuthComponent::password($this->request->data['User']['password']);
            $password = $this->request->data['password'];
            $new_password = $this->request->data['new_password'];
            $id = $this->Auth->user('id');
            
            $user = $this->Users->get($id, [
                'fields' => ['password']
            ]);

            $correct = (new DefaultPasswordHasher)->check($password, $user['password']);
            if($correct)
            {
                $user = $this->Users->get($id);  
                $user->password = $new_password;              
                $this->Users->save($user);
                $this->Flash->success(__('Change password successfully'));
                $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error( __('The old password is not correct.'));
            }
        }
    }    
}
