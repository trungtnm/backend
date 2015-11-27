@extends('TrungtnmBackend::layout.backend')

@section('content')
@include('TrungtnmBackend::includes.moduleHeader')
<!-- form start -->
<form method="post" action="" role="form" class="form-edit form-horizontal pb10" enctype="multipart/form-data">
{{ csrf_field() }}
@if(!empty($dataFields))
    @foreach ($dataFields as $fieldName => $options)
    <?php 
    $data = !empty($options['data']) ? ${$options['data']} : [];
    $valueField = !empty($options['alias']) ? $options['alias'] : $fieldName;
    $value = !empty( $item->$valueField) ?  $item->$valueField : Input::get($valueField)
    ?>
    <div class="row-fluid">
        <label for="{{ $fieldName }}" class="control-label col-sm-2">{{ $options['label'] }}</label>
        <div class="col-sm-10">
            {!! $maker->makeInput($fieldName, $options, $value, $data ) !!}
            {!! $maker->showError(!empty($validate) ? $validate : null, $fieldName) !!}
        </div>
        <div class="clearfix"></div>
    </div>
    @endforeach
@endif

@include('TrungtnmBackend::includes.buttons')
</form>

<div id="preHiddenFields">
{!! $customView or "" !!}
</div>
@endsection