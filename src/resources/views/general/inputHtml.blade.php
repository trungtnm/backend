{!! $helpText !!}
<textarea class="form-control" rows="10" cols="80" id="{{ $field }}" name="{{ $field }}" placeholder="">{{  $value or request()->get($field)  }}</textarea>
<script>
    var editor = CKEDITOR.replace( '{{ $field }}' );
    CKFinder.setupCKEditor( editor, "{{ $assetURL }}ckfinder/" );
</script>