<?php

namespace App\Admin\Controller;

class ControllerBase extends \Phalcon\Mvc\Controller
{


	/**
	 * @var \Redis $redis
	 */

	public $redis;

	public function onConstruct()
	{
		$this->redis = $this->getDI()
		                    ->get('redis');

		// 登录监测
		if (!$this->checkLogin())
		{
			$this->response->redirect('admin/admin/login')
			               ->sendHeaders();
		}
	}

	public function checkLogin()
	{
		return $this->session->has('admin');
	}

	public function error($msg)
	{

	}

	public function constructSelect($list, $name, $class = null, $selected = 0, $bindAction)
	{
		ksort($list);

		if ($bindAction)
		{
			$str = '<select class="form-control ' . $class . '" name = ' . $name . ' onChange="' . $bindAction . '">';
		}
		else
		{
			$str = '<select class="form-control ' . $class . '" name = ' . $name . '>';
		}

		foreach ($list as $value => $item)
		{
			if ($value == $selected)
			{
				$str .= '<option value="' . $value . '" selected>' . $item . '</option>';
			}
			else
			{
				$str .= '<option value="' . $value . '">' . $item . '</option>';
			}

		}
		$str .= '</select>';

		return $str;
	}

	/**
	 * 生成指定长度的随机字符串
	 *
	 * @param  int $length 字符串长度
	 *
	 * @return [type]         [description]
	 */
	public static function getRandChar($length)
	{
		$str    = '';
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		for ($i = 0; $i < $length; $i ++)
		{
			$str .= $strPol[rand(0, 61)];
		}

		return $str;
	}

	public function displayError($msg)
	{
		$this->view->setVar('msg', $msg);
		$this->view->pick('error');
	}
}