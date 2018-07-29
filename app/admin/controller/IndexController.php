<?php

namespace App\Admin\Controller;

use Phalcon\Mvc\View;
use App\Common\Model\MenuModel;

class IndexController extends ControllerBase
{
	public function indexAction()
	{

		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

		// 获取管理员权限
		$admin = $this->session->get('admin');
		$menuList = (new MenuModel())->getAdminMenu($admin['adminUserId']);

		$this->view->setVar('admin', $admin);
		$this->view->setVar('menuList', $menuList);

		$this->view->pick('home');
	}

	public function notFoundAction()
	{
		die('not found');
	}

	public function aaaAction()
	{
		echo 444;
	}

	public function err404Action()
	{
		echo 333;
	}
}