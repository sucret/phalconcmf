<?php

namespace App\Common\Model;

class AdminRoleModel extends \Phalcon\Mvc\Model
{

	public function columnMap()
	{
		return [
			'admin_role_id' => 'adminRoleId',
			'role_name'     => 'roleName',
			'description'   => 'description'
		];
	}

	public function getSource()
	{
		return 'admin_role';
	}

}