<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\LinksController;
// use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class HomepageController extends Controller
{

    public function index()
    {
        return View::make('front.homepage.shorten_url');
    }

    public function getRedirect($url)
    {
        $links = new LinksController;
        $links = $links->show($url);
        $data  = json_decode($links->getContent(), true);
        if ($data['status'] == 'error') {
            return App::abort(404);
        } else {
            $links = new LinksController;
            $links->updateCounter($data['data']['id']);
            return Redirect::to($data['data']['url']);
        }
    }

}
