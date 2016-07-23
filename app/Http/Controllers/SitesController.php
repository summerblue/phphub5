<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Banner;

class SitesController extends Controller
{
    public function index()
    {
        $sites = Site::allFromCache();
        $banners = Banner::allByPosition();
        return view('sites.index', compact('sites', 'banners'));
    }
}
