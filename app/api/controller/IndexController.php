<?php

namespace App\Api\Controller;


class IndexController extends ControllerBase
{

	public function indexAction()
	{
		$this->redis->set('a', 10);


		$this->data = [1,2,3,4,'f范围分为非'];
	}

}