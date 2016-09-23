@extends('TrungtnmBackend::layout.login')

@section('content')
    <div class="panel-heading">
        <h3 class="panel-title">Please Sign In</h3>
    </div>
    <div class="panel-body">
        <form action="" role="form" method="post">
            {!! csrf_field() !!}
            <fieldset>
                @if( !empty($message) )
                    <div class="row-fluid">
                        <div class="label label-danger pb10">{{ $message }}</div>
                    </div>
                @endif
                <div class="form-group">
                    <input class="form-control" placeholder="Email" name="loginEmail" type="text" autofocus>
                    @if( isset($validate) && $validate->has('loginEmail') )
                        <span class="label label-danger">{{ $validate->first('loginEmail') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Password" name="loginPassword" type="password" value="">
                    @if( isset($validate) && $validate->has('loginPassword')  )
                        <span class="label label-danger">{{ $validate->first('loginPassword') }}</span>
                    @endif
                </div>
                <div class="checkbox">
                    <label>
                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                    </label>
                </div>
                <!-- Change this to a button or input when using this as a form -->
                <button type="submit" class="btn btn-primary btn-block">Log me in</button>
            </fieldset>
        </form>
    </div>
@endsection