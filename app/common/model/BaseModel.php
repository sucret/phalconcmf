<?php


use Phalcon\Mvc\Model\Behavior\SoftDelete;

class BaseModel extends \Phalcon\Mvc\Model
{
	const DELETED = 1;

	const NOT_DELETED = 2;

	public function initialize()
	{
		$this->useDynamicUpdate(true);

		$this->addBehavior(
			new SoftDelete(
				array(
					'field' => 'isdelete',
					'value' => self::DELETED
				)
			)
		);
		//$this->setReadConnectionService('readdb');
		//$this->setWriteConnectionService('db');

	}

	public function beforeCreate()
	{
	}
}
