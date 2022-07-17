<?php 

namespace App\Models;

use Framework\Db\Model;

/**
 * 
 */
class Users extends Model
{

	protected $table = 'users';

	public function all()
	{
		return static::findAll();
	}

}