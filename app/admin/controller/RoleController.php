<?php

namespace App\Admin\Controller;

use App\Common\Model\AdminRoleModel;
use App\Common\Model\MenuModel;
use App\Common\Model\AdminRoleAuthModel;

/**
 * 角色控制器
 *
 * Class RoleController
 * @package App\Admin\Controller
 */
class RoleController extends ControllerBase
{

	/**
	 * @var \App\Common\Model\AdminRoleModel
	 */
	protected $RoleModel;

	public function onConstruct()
	{
		parent::onConstruct();

		$this->RoleModel = new AdminRoleModel();
	}

	public function indexAction()
	{
		$this->response->redirect('admin/role/list');
	}

	/**
	 * 角色列表
	 */
	public function listAction()
	{

		$roleList = $this->RoleModel->find()
		                            ->toArray();

		$this->view->setVar('roleList', $roleList);

		$this->view->pick('admin/roleList');

	}

	/**
	 * 编辑角色
	 */
	public function editAction()
	{

		if ($this->request->isPost())
		{
			$this->request->getPost('adminRoleId') && $this->RoleModel->adminRoleId = (int) $this->request->getPost('adminRoleId');

			$this->RoleModel->roleName    = $this->request->getPost('roleName');
			$this->RoleModel->description = $this->request->getPost('description');

			if ($this->RoleModel->save())
			{
				$this->response->redirect('admin/role/list');
			}
		}
		else
		{
			$adminRoleId = $this->request->get('id');

			if ($adminRoleId)
			{
				$roleInfo = $this->RoleModel->findFirst($adminRoleId);

				$this->view->setVar('roleInfo', $roleInfo);
			}

			$this->view->pick('admin/roleEdit');
		}
	}

	/**
	 * 删除角色
	 */
	public function deleteAction()
	{
		$id = (int) $this->request->get('id');

		if ($id)
		{
			$roleInfo = $this->RoleModel->findFirst($id);

			if ($roleInfo)
			{
				$roleInfo->delete();
				$this->response->redirect('admin/role/list');
			}
		}
	}

	/**
	 * 角色授权
	 */
	public function authAction()
	{
		$roleId = (int) $this->request->get('id');

		if ($this->request->isPost())
		{

			$menuIds = $this->request->getPost('menuId');

			$adminRoleAuthModel = new AdminRoleAuthModel();

			foreach ($adminRoleAuthModel->find(['conditions' => "adminRoleId = $roleId"]) as $menu)
			{
				$menu->delete();
			}

			foreach ($menuIds as $menuId)
			{
				unset($adminRoleAuthModel->adminRoleAuthId);

				$adminRoleAuthModel->save(['adminRoleId' => $roleId, 'menuId' => $menuId]);
			}

			$this->response->redirect('admin/role/list');
		}
		else
		{
			$roleInfo = $this->RoleModel->findFirst($roleId);

			if ($roleInfo)
			{
				// 获取授权的菜单节点
				$authMenu = AdminRoleAuthModel::getAuthMenu($roleId);

				// 获取菜单树状结构
				$menuList = MenuModel::getMenuTree();

				$checkboxHtml = '';

				$this->createAuthCheckboxHtml($menuList, $authMenu, $checkboxHtml);


				$this->view->setVar('checkboxHtml', $checkboxHtml);
				$this->view->setVar('menuList', $menuList);
				$this->view->pick('admin/roleAuth');
			}
		}
	}


	/**
	 * 生成授权checkbox html
	 *
	 * 样式如下
	 *
	 * <div class="checkbox">
	 *      123  &nbsp;&nbsp;
	 *      <label>
	 *          <input type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
	 *          Option one is this and that&mdash;be sure to include why it's great
	 *      </label>
	 * </div>
	 * <div class="checkbox">
	 *      123  &nbsp;&nbsp;
	 *      <label>
	 *          <input type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
	 *          Option one is this and that&mdash;be sure to include why it's great
	 *      </label>
	 * </div>
	 *
	 * @param $menuList
	 * @param $checked
	 * @param $html
	 */
	private function createAuthCheckboxHtml($menuList, $checked, &$html)
	{

		foreach ($menuList as $menu)
		{
			$path = $this->getPath($menu['level']);

			$html .= '<div class="checkbox">' . $path . '<label>
							<input type="checkbox" name="menuId[]" value="' . $menu['menuId'] . '" ';

			$html .= in_array($menu['menuId'], $checked) ? 'checked' : '';

			$html .= '> ' . $menu['name'] . '</label>
					</div>';

			if (!empty($menu['child']))
			{
				$this->createAuthCheckboxHtml($menu['child'], $checked, $html);
			}
		}
	}

	private function getPath($level)
	{
		$path = '';

		for ($i = 1; $i < $level; $i ++)
		{
			$path .= '└─ ';
		}

		return $path;
	}


}