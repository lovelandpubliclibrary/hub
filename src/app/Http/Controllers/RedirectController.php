<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectController extends Controller
{
    public function scheduler()
    {
    	return Redirect::to('https://lpl-repository.com/scheduler');
    }
    
    public function helpdesk()
    {
    	return Redirect::to('http://192.168.1.34/portal');
    }
    
    public function cityemail()
    {
    	return Redirect::to('https://fw.ci.loveland.co.us/owa');
    }
}
