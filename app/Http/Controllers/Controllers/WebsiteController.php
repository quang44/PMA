<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
	public function header(Request $request)
	{
		return view('backend.website_settings.header');
	}
	public function footer(Request $request)
	{
		$lang = $request->lang;
		return view('backend.website_settings.footer', compact('lang'));
	}
	public function pages(Request $request)
	{
        $pages = Page::all();
		return view('backend.website_settings.pages.index', compact('pages'));
	}
	public function appearance(Request $request)
	{
		return view('backend.website_settings.appearance');
	}
}
