<?php

// .---------------------------------------------------------------
// | PhalconCMS
// | 系统设置
// |
// | 公司的项目中，有一些配置会在运营过程中随时修改，所以会有一个系统设置的功能
// | 具体做法是把这些设置放在数据库里面，然后缓存起来，感觉很方便
// | 故在这里借鉴一下 ~~
// +---------------------------------------------------------------
// | @Author : Sucret
// | @Email  : sucret@163.com
// +---------------------------------------------------------------
// | @Date: 2018-07-30
// .---------------------------------------------------------------

namespace App\Admin\Controller;

use App\Common\Model\SettingGroupModel;
use App\Common\Model\SettingModel;

/**
 * Class SettingController
 * @package App\Admin\Controller
 */
class SettingController extends ControllerBase
{
	public function indexAction()
	{
		$this->response->redirect('admin/setting/detail');
	}

	public function detailAction()
	{
		$groupId = $this->request->get('groupId');
		if (!$groupId)
		{
			$groupInfo = SettingGroupModel::findFirst();
		}
		else
		{
			$groupInfo = SettingGroupModel::findFirst(['conditions' => 'settingGroupId = ' . $groupId]);
		}

		if (!empty($groupInfo))
		{
			$groupList = SettingGroupModel::find()
			                              ->toArray();

			$settingList = SettingModel::find(["settingGroupId = {$groupInfo->settingGroupId}"])
			                           ->toArray();

			$this->view->setVar('settingList', $settingList);

			$this->view->setVar('groupList', $groupList);
			$this->view->setVar('currentGroupId', $groupInfo->toArray()['settingGroupId']);
		}
		$this->view->pick('setting/detail');
	}

	/**
	 * 添加/编辑设置组
	 */
	public function editGroupAction()
	{
		if ($this->request->isPost())
		{
			$name           = trim($this->request->getPost('name'));
			$settingGroupId = (int) $this->request->getPost('settingGroupId');

			// 查看name是否可用

			$repeat = SettingGroupModel::findFirst([
				                                       'conditions' => 'name = ?1 AND settingGroupId != ?2',
				                                       'bind'       => [
					                                       '1' => $name,
					                                       '2' => $settingGroupId
				                                       ]
			                                       ]);
			if (!empty($repeat) || empty($name))
			{
				$this->displayError('名字不能为空');
			}
			else
			{
				$settingGroupModel = new SettingGroupModel();

				$settingGroupModel->name = $name;

				if ($settingGroupId)
				{
					$settingGroupModel->settingGroupId = $settingGroupId;
				}

				if ($settingGroupModel->save())
				{
					$this->response->redirect('admin/setting/detail?groupId=' . $settingGroupModel->settingGroupId);
				}
				else
				{
					$this->displayError('编辑失败，请重试');
				}
			}
		}
		else
		{
			$settingGroupId = $this->request->get('settingGroupId');
			if ($settingGroupId)
			{
				$groupInfo = SettingGroupModel::findFirst(['conditions' => 'settingGroupId = ' . $settingGroupId]);
				if ($groupInfo)
				{
					$this->view->setVar('groupInfo', $groupInfo->toArray());
				}
				else
				{
					$this->displayError('设置组不存在');
				}
			}

			$this->view->pick('setting/editGroup');
		}
	}

	/**
	 * 删除设置组
	 */
	public function deleteGroupAction()
	{

	}

	/**
	 * 添加/编辑设置
	 */
	public function editItemAction()
	{
		if ($this->request->isPost())
		{
			$title          = $this->request->getPost('title', 'string');
			$settingGroupId = $this->request->getPost('settingGroupId', 'int');
			$alias          = $this->request->getPost('alias', 'string');
			$value          = $this->request->getPost('value', 'string');
			$description    = $this->request->getPost('description', 'string');
			$settingId      = $this->request->getPost('settingId', 'int');

			$settingModel = new SettingModel();

			$data = [
				'settingGroupId' => $settingGroupId,
				'title'          => $title,
				'alias'          => $alias,
				'value'          => $value,
				'description'    => $description
			];

			$settingId && $data['settingId'] = $settingId;

			$settingModel->save($data);

			$this->response->redirect('admin/setting/detail?groupId=' . $settingGroupId);
		}
		else
		{
			$settingGroupId = $this->request->get('settingGroupId');

			$settingId = $this->request->get('settingId');

			if (!$settingGroupId && !$settingId)
			{
				$this->displayError('参数错误');
			}

			if ($settingId)
			{
				$setting = SettingModel::findFirst(['conditions' => "settingId = {$settingId}"]);
				if (empty($setting))
				{
					return $this->displayError('设置信息不存在');
				}

				$this->view->setVar('settingInfo', $setting->toArray());
			}

			if ($settingGroupId)
			{
				$groupInfo = SettingGroupModel::findFirst(['conditions' => 'settingGroupId = ' . $settingGroupId]);

				if (empty($groupInfo))
				{
					return $this->displayError('设置组不存在');
				}
			}

			$this->view->setVar('groupInfo', $groupInfo->toArray());

		}
	}

	/**
	 * 删除设置
	 */
	public function deleteItemAction()
	{
		$settingId = $this->request->get('settingId');

		$setting = SettingModel::findFirst(['conditions' => $settingId]);

		if ($setting)
		{
			$settingGroupId = $setting->settingGroupId;

			if ($setting->delete())
			{
				$this->response->redirect('admin/setting/detail?groupId=' . $settingGroupId);
			}
			else
			{
				$this->displayError('删除失败，请重试');
			}
		}
		else
		{
			$this->displayError('设置信息不存在');
		}
	}


}