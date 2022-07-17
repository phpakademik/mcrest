<?php 
namespace Framework\Http;

use Framework\Service\Json;

class Controller
{
	public function json($data)
	{
		echo Json::to($data);
	}
	public function platform($platform = '' )
	{
		$arr = [
			'Windows'=>'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
			'Android'=>'',
		];
		if ($_SERVER['HTTP_USER_AGENT'] == $arr[$platform]) 
		{
			
		}	
		else
		{
			exit;
		}
	}
	public function post($param = '')
	{
		if (strlen($param)>0) {
			return $_POST[$param];
		}
		return $_POST;
	}
}