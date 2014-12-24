<!doctype html>
<html>
    <head></head>
    <body>
        @if($errors->any())
            <h4>{{$errors->first()}}</h4>
        @endif
        {{Form::open(['route' => 'session.store'])}}
            <div>
                {{Form::label('email', 'Email:')}}
                {{Form::email('email')}}
            </div>

            <div>
                {{Form::label('password', 'Password:')}}
                {{Form::password('password')}}
            </div>

            <div>
                {{Form::submit('Login')}}
            </div>
        {{Form::close()}}
    </body>
</html>