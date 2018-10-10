<?php

namespace App\Common\Model;

class CategoryModel extends \Phalcon\Mvc\Model
{

	public function columnMap()
	{
		return [
			'category_id' => 'categoryId',
			'alias'       => 'alias',
			'parent_id'   => 'title',
			'deleted'     => 'deleted'
		];
	}

	public function getSource()
	{
		return 'category';
	}

}