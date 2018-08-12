<?php

namespace App\Api\Controller;

class ControllerBase extends \Phalcon\Mvc\Controller
{
	/**
	 * 构造方法
	 */
	public function onConstruct()
	{
		$this->msg       = '';
		$this->data      = [];
		$this->errorCode = 0;

		if(!$this->request->isPost()) die;

		$requestContent = file_get_contents("php://input");

		$requestObj = json_decode($requestContent);

		$this->logger->info($requestObj);

		$this->time = $_SERVER['REQUEST_TIME'];

		is_null($requestObj) && die;

		$this->param = $requestObj;
	}


	/**
	 * 返回信息
	 */
	public function afterExecuteRoute()
	{
		$this->response->setContentType('application/json');
		$response = [
			'msg'       => $this->msg,
			'date'      => $this->data,
			'errorCode' => $this->errorCode
		];

		$this->response->setJsonContent($response,
		                                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

		$this->logger->info($response);
		$this->response->send();
	}

}
