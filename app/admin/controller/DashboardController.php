<?php

namespace App\Admin\Controller;

use App\Common\Model\AdminRoleModel;
use \Phalcon\Mvc\Controller;
use App\Common\Model\AdminUserModel;

class DashboardController extends Controller
{

	public function indexAction()
	{
		$this->view->pick('dashboard');
	}

}
