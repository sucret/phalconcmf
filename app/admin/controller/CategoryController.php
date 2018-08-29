<?php

namespace App\Admin\Controller;

use App\Common\Model\AdminRoleAuthModel;
use App\Common\Model\MenuModel;

/**
 * 节点控制器
 *
 * Class CategoryController
 * @package App\Admin\Controller
 */
class CategoryController extends ControllerBase
{
	public function indexAction()
	{
		$this->response->redirect('admin/category/list');
	}

	/**
	 * 分类列表
	 */
	public function listAction()
	{

	}
}
