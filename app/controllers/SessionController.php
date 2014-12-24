<?php

class SessionController extends \BaseController
{
    public function create()
    {
        return View::make('session.create');
    }

    public function store()
    {
        if (Auth::attempt(Input::only('email', 'password'))) {
            return Redirect::to('player/settings');
        }
        return Redirect::back()->withInput()->withErrors(['wrong credentials!']);
    }

    public function destroy()
    {
        Auth::logout();
        return Redirect::route('session.create');
    }
}
