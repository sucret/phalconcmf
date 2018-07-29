<?php

namespace App\Common\Model;

class AdminUserRoleModel extends \Phalcon\Mvc\Model
{

	public function columnMap()
	{
		return [
			'admin_user_role_id' => 'adminUserRoleId',
			'admin_user_id'      => 'adminUserId',
			'admin_role_id'      => 'adminRoleId'
		];
	}

	public function getSource()
	{
		return 'admin_user_role';
	}

}