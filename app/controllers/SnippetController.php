<?php

class SnippetController extends \BaseController
{
    public function create()
    {
        return View::make('snippet.index');
    }

    public function ajaxUploadAudio()
    {
        $audio  = Input::file('file');
        $srcUrl = '';

        if ($audio) {
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR;
            $audioName = $audio->getClientOriginalName();
            $audio->move($destinationPath, $audioName);
            $src = '/audio/' . $audioName;
            $srcUrl = asset($src);
        }

        exit(json_encode(array('src' => $srcUrl)));
    }
}
