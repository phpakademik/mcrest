<?php 

namespace App\Controllers;

use Framework\Http\Controller;
use App\Models\Users;


class SiteController extends Controller
{
	public function __construct()
	{
		$this->platform('Windows');
	}
	public function index()
	{
		$model = new Users();
		return $this->json($model->all());
	}
	public function create()
	{
		$model = new Users;	
	}
	public function update()
	{
		$model = new Users();
	}

	public function delete()
	{
		$model = new Users();
	}
	
}