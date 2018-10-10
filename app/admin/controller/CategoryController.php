<?php

namespace App\Admin\Controller;

use App\Common\Model\CategoryModel;

/**
 * 节点控制器
 *
 * Class CategoryController
 * @package App\Admin\Controller
 */
class CategoryController extends ControllerBase
{

	/**
	 * @var \Redis $redis
	 */


	public function indexAction()
	{
		$this->response->redirect('admin/category/list');
	}

	/**
	 * 分类列表
	 */
	public function listAction()
	{
		$this->redis->get('a');
	}

	public function editAction()
	{
		if ($this->request->isPost())
		{
			$parentId   = $this->request->get('parentId');
			$categoryId = $this->request->get('categoryId');

			if ($categoryId & $category = CategoryModel::findFirst(['conditions' => "categoryId = $categoryId"]))
			{
				$category->title = trim($this->request->getPost('title'));
				$category->alias = strtolower(trim($this->request->getPost('alias')));

			}
			elseif ($parentId)
			{
				$category           = new CategoryModel();
				$category->title    = trim($this->request->getPost('title'));
				$category->alias    = strtolower(trim($this->request->getPost('alias')));
				$category->parentId = $this->request->getPost('parentId', 'int');

				$category->save();
			}
		}
		else
		{
			$parentId   = $this->request->get('parentId');
			$categoryId = $this->request->get('categoryId');

			if ($categoryId)
			{
				$category = CategoryModel::findFirst(['conditions' => "categoryId = $categoryId"]);
				$this->view->setVar('category', $category);
			}

			if ($parentId)
			{
				$parent = CategoryModel::findFirst(['conditions' => "categoryId = $parentId"]);
				$this->view->setVar('parent', $parent);
			}
		}
	}
}
