<!doctype html>
<html>
    <head>
        {{HTML::style('css/snippet.css')}}
    </head>
    <body>
        <h1>
            CURIOS video mashup service
        </h1>

        <div class="body-bl">
            @if($errors->any())
                <h4>{{$errors->first()}}</h4>
            @endif
            <div class="step step-login">
                {{Form::open(['route' => 'session.store'])}}
                    <div class="setting-row">
                        {{Form::label('email', 'Email:', array('class' => 'label-column'))}}
                        {{Form::email('email')}}
                    </div>

                    <div class="setting-row">
                        {{Form::label('password', 'Password:', array('class' => 'label-column'))}}
                        {{Form::password('password')}}
                    </div>

                    <div>
                        {{Form::submit('Login', array('class' => 'btn-green'))}}
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </body>
</html>