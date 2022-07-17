<?php 

namespace Framework;


/**
 * 
 */
class Routes
{
	public static $routes = [];

	public static function get($url,$params=[])
	{
		self::$routes[] = [
			'url'=>$url,
			'controller'=>$params[0],
			'method'=>$params[1],
			'id'=>$params[2],
			'get'=>true,
		];
	}
	public static function post($url,$params)
	{
		self::$routes[] = [
			'url'=>$url,
			'controller'=>$params[0],
			'method'=>$params[1],
			'post'=>true,
		];
	}
	public static function patch($url,$params)
	{
		self::$routes[] = [
			'url'=>$url,
			'controller'=>$params[0],
			'method'=>$params[1],
			'patch'=>true,
		];
	} 
	public static function delete($url,$params)
	{
		self::$routes[] = [
			'url'=>$url,
			'controller'=>$params[0],
			'method'=>$params[1],
			'delete'=>true,
		];
	}
	public static function notFound()
	{
		$arr = [
			'status'=>'not found',
			'code'=>'404'
		];
		return json_encode($arr);
	}

	public static function run()
	{
		$q = $_GET['q'];
		foreach (self::$routes as $route)
		{

			if ($route['url'] == '/'.$q) {
				// if($route['get'] === true  and $_SERVER['REQUSET_METHOD'] === 'GET')
				// {
				// 	$controller = new $route['controller'];
				// 	$method = $route['method'];
				// 	$controller->$method();
				// }
				// else if ($route['post'] == true and $_SERVER['REQUSET_METHOD'] == 'POST') {
				// 	$controller = new $route['controller'];
				// 	$method = $route['method'];
				// 	$controller->$method();	
				// }
				if($route['get'] === true)
				{
					
					$controller = new $route['controller'];
					$method = $route['method'];
					$controller->$method();	
				}
				if($route['post'] == true)
				{
					if ($_SERVER['REQUEST_METHOD'] === 'POST') {

						$controller = new $route['controller'];
						$method = $route['method'];
						$controller->$method();		
					}
				}
				if ($route['patch'] == true) {
					if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
						$controller = new $route['controller'];
						$method = $route['method'];
						$controller->$method();
					}
				}
				if ($route['delete'] == true) {
					if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
						$controller = new $route['controller'];
						$method = $route['method'];
						$controller->$method();
					}
				}
			}
		}
		die();
		static::notFound();
	}
}



?>