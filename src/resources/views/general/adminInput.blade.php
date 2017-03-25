@if($type == 'html')
<textarea class="form-control" rows="10" cols="80" id="{{ $field }}" name="{{ $field }}" placeholder="">{!! $value or request()->get($field)  !!}</textarea>
<script>
    var editor = CKEDITOR.replace( '{{ $field }}' );
    CKFinder.setupCKEditor( editor, "{{{asset('public/backend/ckfinder/')}}}/" );
</script>
@elseif ($type == 'file')
<input id="fileupload" type="file" name="{{ $field }}">
<?php if(isset($value)){ ?>
<div>
    <a class="fancy" target="_blank" href="{{ URL::to($value) }}">Review</a>
</div>
<?php } ?>
@else
<input type="text" class="form-control" id="{{ $field }}" value="{{{ $value or request()->get($field) }}}" name="{{ $field }}" placeholder="">
@endif