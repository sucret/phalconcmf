<?php

namespace App\Common\Model;

class AdminRoleAuthModel extends \Phalcon\Mvc\Model
{

	public function columnMap()
	{
		return [
			'admin_role_auth_id' => 'adminRoleAuthId',
			'admin_role_id'      => 'adminRoleId',
			'menu_id'            => 'menuId'
		];
	}

	public function getSource()
	{
		return 'admin_role_auth';
	}

	public static function getAuthMenu($adminRoleId, $field = null)
	{
		$checked = self::find([
			                      'conditions' => "adminRoleId = $adminRoleId",
			                      'columns'    => 'menuId'
		                      ])
		               ->toArray();

		return array_column($checked, 'menuId');
	}

}