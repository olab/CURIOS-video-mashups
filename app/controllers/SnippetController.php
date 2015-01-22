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

    public function generate()
    {
        $allData        = json_decode(Input::get('json'));
        $player         = $allData->player;
        $audio          = $allData->audio;
        $annotations    = $allData->annotations;

        // save youtube video
        $videoStart = $this->objToTime($player->start);
        $videoEnd = $this->objToTime($player->end);
        if($player->id){
            $videoId = $player->id;
            VideoSettings::updateEntry($videoId, $player->videoCode, $videoStart, $videoEnd, $player->volume);
        } else {
            $videoId = VideoSettings::createEntry($player->videoCode, $videoStart, $videoEnd, $player->volume);
        }

        UserVideo::create([
            'user_id' => Auth::id(),
            'video_id' => $videoId
        ]);

        //save audio
        if ($audio->path){
            $audioStart = $this->objToTime($audio->start);
            $audioEnd = $this->objToTime($audio->end);
            $audioId = AudioSettings::createEntry($audio->note, $audioStart, $audioEnd, $audio->volume);

            VideoAudio::create([
                'video_id' => $videoId,
                'audio_id' => $audioId,
            ]);
        }

        //save annotation
        foreach ($annotations as $annotation) {
            $annotationStart = $this->objToTime($annotation->start);
            $annotationEnd = $this->objToTime($annotation->end);
            $annotationId = AnnotationSetting::createEntry($annotation->form, $annotation->backGround, $annotation->x, $annotation->y,
                $annotation->height, $annotation->width, $annotationStart, $annotationEnd, $annotation->text,
                $annotation->transparency, $annotation->fontSize, $annotation->color);

            VideoAnnotation::create([
                'video_id' => $videoId,
                'annotation_id' => $annotationId
            ]);
        }

        exit(json_encode(['id' => $videoId, 'idBase64' =>base64_encode($videoId)]));
    }

    /**
     * @param $obj
     * @return int time in seconds
     */
    private function objToTime($obj){
        return (($obj->h * 60) + $obj->m) * 60 + $obj->s;
    }


    public function embed()
    {
        $videoId = base64_decode(Input::get('slug'));

        $videoObj = VideoSettings::find($videoId);
        $audioObj = AudioSettings::getAudioByVideoId($videoId);
        $annotationsObj = AnnotationSetting::getAnnotationsByVideoId($videoId);

        return View::make('embed')
            ->with(array(
                'videoJSON' => json_encode($videoObj),
                'audioJSON' => json_encode($audioObj),
                'annotationsJSON' => json_encode($annotationsObj)
            ));
    }
}
