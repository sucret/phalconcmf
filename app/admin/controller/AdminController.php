<?php

namespace App\Admin\Controller;

use \Phalcon\Mvc\Controller;
use App\Common\Model\AdminUserModel;

class AdminController extends Controller
{

	public function indexAction()
	{
		$this->response->redirect('login');
	}

	/**
	 * 登录
	 */
	public function loginAction()
	{
		$this->view->pick('admin/login');
	}

	public function doLoginAction()
	{
		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		$AdminUserModel = new AdminUserModel();

		$adminUser = $AdminUserModel::findFirst(['conditions' => 'username = ?1', 'bind' => ['1' => $username]]);

		if ($adminUser)
		{
			if($adminUser->password == md5(md5($password) . $adminUser->salt))
			{
				$this->session->set('admin', $adminUser->toArray());
				$this->response->redirect('admin/index/index');
			}else{
				die('密码错误');
			}
		}
		else
		{
			die('账号不存在');
		}
	}

	/**
	 * 退出
	 */
	public function logoutAction()
	{
		$this->session->destroy('admin');
		$this->response->redirect('admin/index/index');
	}

	public function addAction()
	{

	}

}