@extends('TrungtnmBackend::layout.backend')

@section('filter')
    @include('TrungtnmBackend::general.filter')
@endsection

@section('content')
@include('TrungtnmBackend::includes.moduleHeader')
<div class="row-fluid">
    @yield('filter')
</div>
<!-- END HEADER -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-body table-responsive">
                <div class="wrap-table">
                    <!-- Data table will be filled here -->
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
@endsection


