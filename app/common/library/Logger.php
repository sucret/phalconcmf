<?php

namespace App\Common\Library;

class Logger extends \Phalcon\Logger\Adapter\File
{

	public function __construct($name, $options = null)
	{
		parent::__construct($name, $options);
	}

	private function constructMessage($message)
	{
		$backtrace = debug_backtrace(2, 2);
		$file      = str_replace(ROOT_PATH . '/', '', $backtrace[1]['file']);

		if (is_array($message) || is_object($message))
		{
			$message = json_encode($message,
			                       JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

			return $message . ' @' . $file . ':' . $backtrace[1]['line'];
		}
		else
		{
			return '{' . $message . '} @' . $file . ':' . $backtrace[1]['line'];
		}
	}

	/**
	 * Sends/Writes a critical message to the log
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return \Phalcon\Logger\AdapterInterface
	 */
	public function critical($message, array $context = null)
	{
		parent::critical(self::constructMessage($message), $context);
	}

	/**
	 * Sends/Writes an emergency message to the log
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return \Phalcon\Logger\AdapterInterface
	 */
	public function emergency($message, array $context = null)
	{
		parent::emergency(self::constructMessage($message), $context);
	}

	/**
	 * Sends/Writes a debug message to the log
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return \Phalcon\Logger\AdapterInterface
	 */
	public function debug($message, array $context = null)
	{
		parent::debug(self::constructMessage($message), $context);
	}

	/**
	 * Sends/Writes an error message to the log
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return \Phalcon\Logger\AdapterInterface
	 */
	public function error($message, array $context = null)
	{
		parent::error(self::constructMessage($message), $context);
	}

	/**
	 * Sends/Writes an info message to the log
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return \Phalcon\Logger\AdapterInterface
	 */
	public function info($message, array $context = null)
	{
		parent::info(self::constructMessage($message), $context);
	}

	/**
	 * Sends/Writes a notice message to the log
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return \Phalcon\Logger\AdapterInterface
	 */
	public function notice($message, array $context = null)
	{
		parent::notice(self::constructMessage($message), $context);
	}

	/**
	 * Sends/Writes a warning message to the log
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return \Phalcon\Logger\AdapterInterface
	 */
	public function warning($message, array $context = null)
	{
		parent::warning(self::constructMessage($message), $context);
	}

	/**
	 * Sends/Writes an alert message to the log
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return \Phalcon\Logger\AdapterInterface
	 */
	public function alert($message, array $context = null)
	{
		parent::alert(self::constructMessage($message), $context);
	}


}