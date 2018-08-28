<?php

namespace App\Common\Model;

class SettingModel extends \Phalcon\Mvc\Model
{
	public function getSource()
	{
		return 'setting';
	}

	public function columnMap()
	{
		return [
			'setting_id'       => 'settingId',
			'setting_group_id' => 'settingGroupId',
			'alias'            => 'alias',
			'value'            => 'value',
			'description'      => 'description',
			'title'            => 'title'
		];
	}


}