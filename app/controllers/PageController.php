<?php

class PageController extends \BaseController
{
    public function about()
    {
        return View::make('page.about');
    }
}