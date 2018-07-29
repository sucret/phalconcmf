<?php

namespace App\Admin\Controller;

use App\Common\Model\AdminUserModel;
use App\Common\Model\AdminUserRoleModel;
use App\Common\Model\AdminRoleModel;

class AdminUserController extends ControllerBase
{
	/**
	 * @var \App\Common\Model\AdminUserModel
	 */
	protected $adminUserModel = null;

	public function onConstruct()
	{
		parent::onConstruct();

		$this->adminUserModel = new AdminUserModel();
	}

	public function indexAction()
	{
		$this->response->redirect('admin/adminuser/list');
	}

	public function listAction()
	{

		$adminUserList = $this->adminUserModel->find()
		                                      ->toArray();

		$adminUserRole = [];
		$roleList      = [];

		if ($adminUserList)
		{
			$condition = "adminUserId in(" . implode(',',
			                                         array_column($adminUserList,
			                                                      'adminUserId')) . ')';

			$adminUserRole = AdminUserRoleModel::find([$condition])
			                                   ->toArray();
			if ($adminUserRole)
			{
				$roleList = AdminRoleModel::find([
					                                 "adminRoleId in(" . implode(',',
					                                                             array_column($adminUserRole,
					                                                                          'adminRoleId')) . ')'
				                                 ])
				                          ->toArray();
			}
		}

		$userRoleList = [];

		foreach ($adminUserRole as $info)
		{
			$userRoleList[$info['adminUserId']][] = $info;
		}

		$this->view->setVar('roleList', array_column($roleList, null, 'adminRoleId'));
		$this->view->setVar('userRoleList', $userRoleList);
		$this->view->setVar('adminUserList', $adminUserList);

		$this->view->pick('admin/userList');
	}

	public function editAction()
	{
		if ($this->request->isPost())
		{
			$this->request->getPost('adminUserId') && $this->adminUserModel->adminUserId = (int) $this->request->getPost('adminUserId');
			$this->adminUserModel->username = $this->request->getPost('username');
			$this->adminUserModel->nickname = $this->request->getPost('nickname');

			if ($password = $this->request->getPost('password'))
			{
				$this->adminUserModel->salt     = self::getRandChar(10);
				$this->adminUserModel->password = md5(md5($password) . $this->adminUserModel->salt);
			}
			else if ($this->adminUserModel->adminUserId)
			{
				$adminUserInfo                  = $this->adminUserModel->findFirst($this->adminUserModel->adminUserId);
				$this->adminUserModel->salt     = $adminUserInfo->salt;
				$this->adminUserModel->password = $adminUserInfo->password;
			}

			$this->adminUserModel->save();

			$this->response->redirect('admin/adminuser/list');
		}
		else
		{
			if ($id = $this->request->get('id'))
			{
				$adminUser = $this->adminUserModel->findFirst($id);
				$this->view->setVar('adminUser', $adminUser);
			}
			$this->view->pick('admin/userEdit');
		}
	}

	/**
	 * 给管理员账号设置角色
	 */
	public function setRoleAction()
	{

		if ($this->request->isPost())
		{
			$role        = $this->request->getPost('role');
			$adminUserId = $this->request->get('adminUserId');

			$adminUserRole = new AdminUserRoleModel();
			foreach ($adminUserRole->find("adminUserId = $adminUserId") as $roleInfo)
			{
				$roleInfo->delete();
			}

			foreach ($role as $adminRoleId)
			{
				unset($adminUserRole->adminUserRoleId);

				$adminUserRole->adminUserId = $adminUserId;
				$adminUserRole->adminRoleId = $adminRoleId;
				$adminUserRole->save();
			}

			$this->response->redirect('admin/adminuser/list');
		}
		else
		{
			$adminUserId   = $this->request->get('id');
			$adminUserInfo = $this->adminUserModel->findFirst($adminUserId);

			if (empty($adminUserInfo))
			{
				return $this->displayError('管理员信息不存在');
			}

			$roleList = (new AdminRoleModel())->find()
			                                  ->toArray();

			$checkedRole = (new AdminUserRoleModel())->find("adminUserId = $adminUserId")
			                                         ->toArray();

			$this->view->setVar('checked', array_column($checkedRole, 'adminRoleId'));
			$this->view->setVar('roleList', $roleList);
			$this->view->setVar('adminUser', $adminUserInfo->toArray());
			$this->view->pick('admin/setRole');
		}
	}


	public function deleteAction()
	{
		$adminUserId = $this->request->get('id');
		// 判断该用户有没有配置角色，如果没有可以直接删除

		$adminUserInfo = AdminUserModel::findFirst($adminUserId);

		$hasRole = AdminUserRoleModel::findFirst("adminUserId = $adminUserId");

		if ($hasRole)
		{
			return $this->displayError('该账号已经配置了角色，请先取消再删除');
		}
		else
		{
			$adminUserInfo->delete();
		}

		$this->response->redirect('admin/adminuser/list');
	}
}