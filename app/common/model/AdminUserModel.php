<?php

namespace App\Common\Model;

class AdminUserModel extends \Phalcon\Mvc\Model
{


	public function columnMap()
	{
		return [
			'admin_user_id' => 'adminUserId',
			'username'      => 'username',
			'password'      => 'password',
			'nickname'      => 'nickname',
			'salt'          => 'salt'
		];
	}

	public function getSource()
	{
		return 'admin_user';
	}

}