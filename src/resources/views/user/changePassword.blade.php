@extends('TrungtnmBackend::layout.backend')

@section('content')
    @include('TrungtnmBackend::includes.moduleHeader', ['subHeader' => 'Change Password'])
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <!-- form start -->
            <form role="form" method="post">
                {!! csrf_field() !!}
                <div class="box-body">
                    <div class="form-group">
                        <label for="oldPassword">Old password </label>
                        <input type="password" name="oldPassword" class="form-control" id="oldPassword" placeholder="Old password">
                        @if( isset($validate) && $validate->has('oldPassword')  )
                        <span class="text-warning">{!! $validate->first('oldPassword') !!} </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New pasword </label>
                        <input type="password" name="newPassword" class="form-control" id="newPassword" placeholder="New pasword">
                        @if( isset($validate) && $validate->has('newPassword')  )
                        <span class="text-warning">{!! $validate->first('newPassword') !!} </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="vertifyNewPassword">New password confirm</label>
                        <input type="password" class="form-control" id="vertifyNewPassword"
                               name="newPassword_confirmation"
                               placeholder="New password confirm">
                        @if( isset($validate) && $validate->has('newPassword_confirmation')  )
                        <span class="text-warning">{!! $validate->first('newPassword_confirmation') !!} </span>
                        @endif
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection