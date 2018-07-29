<?php

namespace App\Common\Model;

class MenuModel extends \Phalcon\Mvc\Model
{

	public function columnMap()
	{
		return [
			'menu_id'     => 'menuId',
			'name'        => 'name',
			'controller'  => 'controller',
			'action'      => 'action',
			'pid'         => 'pid',
			'is_show'     => 'isShow',
			'description' => 'description',
			'order'       => 'order',
			'icon'        => 'icon'
		];
	}

	public function getSource()
	{
		return 'menu';
	}

	/**
	 * 获取菜单树状结构
	 *
	 * @param $type
	 */
	public static function getMenuTree(): array
	{
		$list = self::find()
		            ->toArray();

		$childArr = [];
		foreach ($list as $item)
		{
			$childArr[$item['pid']][] = $item;
		}

		foreach ($childArr[0] as &$child)
		{
			$child['level'] = 1;
			self::getChild($childArr, $child['menuId'], 1, $child);
		}

		return $childArr[0];
	}

	/**
	 * 递归返回树状结构
	 *
	 * @param $childArr
	 * @param $pid
	 * @param $child
	 */
	private static function getChild($childArr, $pid, $level, &$child)
	{
		if (!empty($childArr[$pid]))
		{
			$level ++;

			foreach ($childArr[$pid] as $childItem)
			{


				$childItem['level'] = $level;

				$child['child'][$childItem['menuId']] = $childItem;

				if (!empty($childArr[$childItem['menuId']]))
				{
					self::getChild($childArr, $childItem['menuId'], $level, $child['child'][$childItem['menuId']]);
				}
			}
		}
	}

	public static function getMenuCheckboxList($selectedArr)
	{

	}

	/**
	 * 以树状结构的形式返回指定管理员的菜单
	 *
	 * @param int $adminUserId
	 *
	 * @return array
	 */
	public function getAdminMenu(int $adminUserId): array
	{
		if ($adminUserId == 1)
		{
			$menuList = $this->find(['conditions' => 'isShow = 1'])
			                 ->toArray();

			//			echo '<pre>';
			//			print_r($menuList);die;
		}
		else
		{
			//			$menuIdList =
		}


		if ($menuList)
		{

			$childArr = [];
			foreach ($menuList as $item)
			{
				$childArr[$item['pid']][] = $item;
			}

			foreach ($childArr[0] as &$child)
			{
				$child['level'] = 1;
				self::getChild($childArr, $child['menuId'], 1, $child);
			}
		}

		return $childArr[0];
	}


}