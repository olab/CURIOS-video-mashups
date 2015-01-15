<?php

class SnippetController extends \BaseController
{
    public function create()
    {
        return View::make('snippet.index');
    }
}
