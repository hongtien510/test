<?php
namespace Admin\Controller;

use App\Controller\AppController as BaseController;
use Cake\Event\Event;
use Cake\Routing\Router;

class AppController extends BaseController
{
    public function initialize()
    {
        $this->viewBuilder()->layout('Admin.admin');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginAction' => [
                  'controller' => 'Users',
                  'action' => 'login',
                  //'plugin' => 'Admin'
            ],
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'index',
                //'plugin' => 'Admin'   
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login',
                //'plugin' => 'Admin',
                //'home'
            ],
            'authenticate' => [
            'Form' => [
                'fields' => ['username' => 'email']
                ]
             ] ,
            //'storage' => 'Session',
			'authError' => 'You can not access that page',
			'authorize' => ['Controller']
        ]);
    }
	//public $logged_in = false;
	public $current_user = null;    
    public function beforeFilter(Event $event)
    {
		$this->current_user = $this->Auth->user();
		//$this->set('logged_in', $this->logged_in);
		$this->set('current_user', $this->current_user);

		$this->set('menu_items', [
			'users' =>  [
                            'text' => 'Users', 'link' => Router::url(['controller' => 'users','action' => 'index']), 'allow_access' => ['admin']
                        ],
            'LoanRates' =>  [
                            'text' => 'Loan Rates', 'link' => Router::url(['controller' => 'loan_rates','action' => 'index']), 'allow_access' => ['admin']
                        ],
		]);
		$this->set('active_menu_item', $this->request->params['controller']);
    }
	public function isAuthorized($user){
		return true;
	}
}