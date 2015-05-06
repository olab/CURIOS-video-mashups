<?php

class LtiController extends \BaseController
{

    public function index()
    {
        $consumers = LtiConsumer::all();
        return View::make('lti.index', [
            'consumers' => $consumers,
        ]);
    }

    public function show($id)
    {
        $consumer = LtiConsumer::findOrFail($id);
        $consumer->dateRefactor();
        return View::make('lti.view', [
            'consumer' => $consumer,
        ]);
    }

    public function create(){
        $consumer = new LtiConsumer;
        return View::make('lti.view', [
            'consumer' => $consumer,
        ]);
    }

    public function insertOrUpdate()
    {
        $request = Request::all();
        $consumer = LtiConsumer::findOrNew($request['consumer_key']);
        if(!empty($request['secret'])){
            $consumer->secret = $request['secret'];
        }
        $consumer->name = $request['name'];
        $consumer->enabled = $request['enabled'];
        $consumer->enable_from = $request['enable_from'];
        $consumer->enable_until = $request['enable_until'];

        if($consumer->save()){
            return Redirect::to(URL::to('/lti/show/'.$consumer->consumer_key))->withErrors(['Done!']);
        }
        return false;
    }

    public function remove($id)
    {
        LtiConsumer::destroy($id);
        return Redirect::back()->withErrors(['Done!']);
    }
}