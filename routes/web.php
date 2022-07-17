<?php 

use Framework\Routes;

use App\Controllers\SiteController;

Routes::get('/',[SiteController::class,'index']);
Routes::post('/create',[SiteController::class,'create']);
Routes::patch('/update',[SiteController::class,'update']);
Routes::delete('/delete/{id}',[SiteController::class,'delete']);






Routes::run();