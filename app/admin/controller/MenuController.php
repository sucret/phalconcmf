<?php

namespace App\Admin\Controller;

use App\Common\Model\AdminRoleAuthModel;
use App\Common\Model\MenuModel;

/**
 * 节点控制器
 *
 * Class MenuController
 * @package App\Admin\Controller
 */
class MenuController extends ControllerBase
{

	/**
	 * @var \App\Common\Model\MenuModel
	 */
	protected $MenuModel = null;

	public function onConstruct()
	{
		parent::onConstruct();

		$this->MenuModel = new MenuModel();
	}

	public function indexAction()
	{
		$this->response->redirect('admin/menu/list');
	}

	/**
	 * 菜单列表
	 */
	public function listAction()
	{
		$list = $this->MenuModel->find()
		                        ->toArray();

		$data = [];

		if ($list)
		{
			$_allMenu = array_column($list, null, 'menuId');

			$pidList = array_column($list, 'pid', 'menuId');

			foreach ($_allMenu as $menu)
			{
				$path = [];
				$this->getPath($pidList, $menu['menuId'], $path);

				$menu['menuDesc'] = $this->getMenuName($path) . ' ' . $menu['name'];

				$path[]                    = $menu['menuId'];
				$data[implode('-', $path)] = $menu;
			}

			ksort($data);
		}

		$this->view->setVar('menuList', $data);

		$this->view->pick('menu/list');
	}

	/**
	 * 编辑/添加菜单
	 */
	public function editAction()
	{
		if ($this->request->isPost())
		{
			$pid = $this->request->getPost('pid');
			$this->request->getPost('menuId') && $this->MenuModel->menuId = (int) $this->request->getPost('menuId');

			$this->MenuModel->name        = trim($this->request->getPost('name'));
			$this->MenuModel->controller  = strtolower(trim($this->request->getPost('controller')));
			$this->MenuModel->action      = strtolower(trim($this->request->getPost('action')));
			$this->MenuModel->pid         = count($pid) > 1 && end($pid) == 0 ? $pid[count($pid) - 2] : (int) end($pid);
			$this->MenuModel->description = trim($this->request->getPost('description'));
			$this->MenuModel->isShow      = (int) $this->request->getPost('isShow');
			$this->MenuModel->icon        = $this->request->getPost('icon');

			$this->MenuModel->save();

			$this->response->redirect('admin/menu/list');
		}
		else
		{
			$menuId = (int) $this->request->get('id');
			if ($menuId)
			{
				$menuInfo = $this->MenuModel->findFirst($menuId);
			}
			$menuList = $this->MenuModel->find('pid = 0')
			                            ->toArray();

			$selectList    = array_column($menuList, 'name', 'menuId');
			$selectList[0] = '作为顶级菜单';

			$menuSelect = $this->constructSelect($selectList, 'pid[]', null, $menuInfo->pid, 'menuSelected(this)');

			$this->view->setVar('menuInfo', $menuInfo);
			$this->view->setVar('menuSelect', $menuSelect);
			$this->view->pick('menu/edit');
		}
	}

	/**
	 * 异步获取
	 */
	public function getChildSelectAction()
	{
		$menuId = $this->request->getPost('menuId');
		$pid    = $this->request->getPost('pid');

		$menuList = $this->MenuModel->find("pid = $pid AND menuId != $menuId")
		                            ->toArray();

		if ($menuList)
		{
			$selectList    = array_column($menuList, 'name', 'menuId');
			$selectList[0] = '请选择上级菜单';

			$menuSelect = $this->constructSelect($selectList, 'pid[]', null, 0, 'menuSelected(this)');
			$data       = ['status' => 1, 'html' => $menuSelect];
		}
		else
		{
			$data = ['status' => 0];
		}

		$this->response->setJsonContent($data);

		return $this->response->send();
	}

	/**
	 * 删除菜单
	 * 删除的时候，判断是否有角色在使用这个菜单以及是否有菜单将他作为父级菜单
	 */
	public function deleteAction()
	{
		$menuId   = $this->request->get('id');
		$hasChild = $this->MenuModel->findFirst("pid = $menuId");

		$using = AdminRoleAuthModel::findFirst("menuId = $menuId");

		if ($hasChild || $using)
		{

		}
		else
		{
			$menuInfo = $this->MenuModel->findFirst("menuId = $menuId");

			if ($menuInfo)
			{
				$menuInfo->delete();
			}
		}

		$this->response->redirect('admin/menu/index');
	}

	/**
	 * 根据menuId生成下拉列表
	 * 排除它自己以及它的子菜单
	 *
	 * @param $menuId
	 */
	private function constructParentSelected($menuId)
	{
		// 先获取菜单的pid列表
		$menuList = $this->MenuModel->find()
		                            ->toArray();

		$menuList = array_column($menuList, 'pid', 'menuId');

		$pidList = [];

		$this->getParent($menuList, $menuId, $pidList);

		foreach ($pidList as $pid)
		{

		}

		return $pidList;
	}

	/**
	 * 递归获取所有父级
	 *
	 * @param $menuList
	 * @param $menuId
	 * @param $pidList
	 */
	private function getParent($menuList, $menuId, &$pidList)
	{
		if ($menuList[$menuId])
		{
			array_unshift($pidList, $menuList[$menuId]);
			$this->getParent($menuList, $menuList[$menuId], $pidList);
		}
		else
		{
			array_unshift($pidList, 0);
		}
	}

	/**
	 * 获取所有pid
	 *
	 * @param $pidList
	 * @param $menuId
	 * @param $path
	 */
	private function getPath($pidList, $menuId, &$path)
	{
		if ($pidList[$menuId])
		{
			array_unshift($path, $pidList[$menuId]);
			$this->getPath($pidList, $pidList[$menuId], $path);
		}
		else
		{
			array_unshift($path, 0);
		}
	}

	/**
	 * 生成可视路径
	 *
	 * @param $path
	 *
	 * @return string
	 */
	private function getMenuName($path)
	{
		$count = count($path);

		$name = '';

		for ($i = 1; $i < $count; $i ++)
		{
			$name .= '└─';
		}

		return $name;
	}


}