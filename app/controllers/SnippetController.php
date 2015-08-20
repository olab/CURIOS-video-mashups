<?php

use Illuminate\Support\Facades\URL;

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
        $audioName = '';

        if ($audio) {
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR;
            $audioName = time() . '_' .$audio->getClientOriginalName();
            $audio->move($destinationPath, $audioName);
            $src = '/audio/' . $audioName;
            $srcUrl = asset($src);
        }

        exit(json_encode(array('src' => $srcUrl, 'fileName' => $audioName)));
    }

    //when authors try to update snippet - always create new one
    public function generate()
    {
        $allData        = json_decode(Input::get('json'));
        $player         = $allData->player;
        $audio          = $allData->audio;
        $annotations    = $allData->annotations;

        // save youtube video
        $videoStart = $this->objToTime($player->start);
        $videoEnd = $this->objToTime($player->end);
        $role = Auth::user()->status;
        if($player->id && $role == 'superuser'){
            $videoId = $player->id;
            VideoSettings::updateEntry($videoId, $player->videoCode, $videoStart, $videoEnd, $player->volume);
        } else {
            $videoId = VideoSettings::createEntry($player->videoCode, $videoStart, $videoEnd, $player->volume);
            UserVideo::create([
                'user_id' => Auth::id(),
                'video_id' => $videoId
            ]);
        }

        //save audio
        $audioPath = $audio->path;
        if (!empty($audioPath)){
            $audioStart = $this->objToTime($audio->start);
            $audioEnd = $this->objToTime($audio->end);

            $audioId = $audio->id;
            if(empty($audioId) || (!empty($audioId) && $role == 'author')) {
                //insert
                $audioId = AudioSettings::createEntry($audio->note, $audioStart, $audioEnd, $audio->volume);
                VideoAudio::create([
                    'video_id' => $videoId,
                    'audio_id' => $audioId,
                ]);
            }else{
                //update
                $audioObj = AudioSettings::find($audioId);
                $audioObj->path = $audio->note;
                $audioObj->start_time = $audioStart;
                $audioObj->end_time = $audioEnd;
                $audioObj->volume = $audio->volume;
                $audioObj->save();
            }
        }elseif($role == 'superuser'){
            //delete
            $videoAudioCollection = VideoAudio::where('video_id', '=', $videoId)->get();
            if(count($videoAudioCollection) > 0){
                foreach($videoAudioCollection as $videoAudioObj){
                    $path = AudioSettings::find($videoAudioObj->audio_id)->path;
                    $file_usages = AudioSettings::getByPath($path)->get();
                    if(!empty($file_usages) && count($file_usages) > 1) {
                        AudioSettings::destroy($videoAudioObj->audio_id);
                    }else{
                        AudioSettings::deleteEntry($videoAudioObj->audio_id);
                    }
                    $videoAudioObj->delete();
                }
            }
        }

        //save annotations
        $annotations_ids = [];
        if(!empty($annotations)) {
            foreach ($annotations as $annotation) {
                if (!is_object($annotation) || empty($annotation)) continue;

                $annotationStart = $this->objToTime($annotation->start);
                $annotationEnd = $this->objToTime($annotation->end);
                $action = isset($annotation->action) ? $annotation->action : false;

                if(in_array($action, ['loaded']) && isset($annotation->id)){
                    $annotations_ids[] = $annotation->id;
                }

                if ($action == 'insert' || (in_array($action, ['loaded']) && $role == 'author')) {
                    $annotationId = AnnotationSetting::createEntry($annotation->form, $annotation->backGround, $annotation->x, $annotation->y,
                        $annotation->height, $annotation->width, $annotationStart, $annotationEnd, $annotation->text,
                        $annotation->transparency, $annotation->fontSize, $annotation->color);

                    $annotations_ids[] = $annotationId;

                    VideoAnnotation::create([
                        'video_id' => $videoId,
                        'annotation_id' => $annotationId
                    ]);
                }
            }
        }

        if($role == 'superuser'){

            $annotations_ids = array_unique($annotations_ids);

            $query = VideoAnnotation::where([
                'video_id' => $videoId,
            ]);

            if(!empty($annotations_ids)) {
                $query->whereNotIn('annotation_id', $annotations_ids);
            }

            $annotations_for_delete = $query->get();

            if(!empty($annotations_for_delete) && count($annotations_for_delete) > 0){

                $annotations_for_delete_ids = [];
                foreach($annotations_for_delete as $row){
                    $annotations_for_delete_ids[] = $row->annotation_id;
                }

                VideoAnnotation::where(['video_id' => $videoId])
                    ->whereIn('annotation_id', $annotations_for_delete_ids)
                    ->delete();

                AnnotationSetting::whereIn('id', $annotations_for_delete_ids)->delete();

            }
        }

        die(json_encode([
            'id' => $videoId,
            'idBase64' => VideoSettings::urlsafe_b64encode($videoId),
            'annot_ids' => $annotations_ids
        ]));
    }

    /**
     * @param $obj
     * @return int time in seconds
     */
    private function objToTime($obj){
        return (($obj->h * 60) + $obj->m) * 60 + $obj->s;
    }

    private function timeToObj($time){
        $obj = new stdClass();
        $date = explode(':', gmdate('G:i:s', $time));
        $obj->h = (int)$date[0];
        $obj->m = (int)$date[1];
        $obj->s = (int)$date[2];
        return $obj;
    }

    public function embed()
    {
        $videoId = VideoSettings::getIdBySlug(Input::get('slug'));

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

    public function uploadBySlug()
    {
        $result = array();
        $videoId = VideoSettings::getIdBySlug(Input::get('slug'));
        $videoObj = VideoSettings::find($videoId);

        if ($videoObj) {
            $result['playerInfo'] = $videoObj;
            $result['audioInfo'] = AudioSettings::getAudioByVideoId($videoId);
            $result['annotationInfo'] = [];
            $result['code'] = '<embed src="'. URL::to('/player/embed?slug=' . VideoSettings::urlsafe_b64encode($videoObj->id)) .'" width="480px" height="360px"></embed>';


            $annotationInfo = VideoAnnotation::getAnnotationByVideoId($videoId);
            if(!empty($annotationInfo)) {
                foreach ($annotationInfo as $annotation) {
                    $annotation->start = $this->timeToObj($annotation->start_time);
                    $annotation->end = $this->timeToObj($annotation->end_time);
                    $annotation->action = 'loaded';
                    $result['annotationInfo'][] = $annotation;
                }
            }
        } else {
            $result['error'] = 'Wrong slug';
        }

        exit(json_encode($result));
    }
}