<?php

namespace App\Common\Model;

class SettingGroupModel extends \Phalcon\Mvc\Model
{

	public function getSource()
	{
		return 'setting_group';
	}

	public function columnMap()
	{
		return [
			'setting_group_id' => 'settingGroupId',
			'name'             => 'name'
		];
	}

}