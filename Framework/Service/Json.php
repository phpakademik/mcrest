<?php 

namespace Framework\Service;


class Json
{
	public static function to($data)
	{
		return json_encode($data);
	}
}