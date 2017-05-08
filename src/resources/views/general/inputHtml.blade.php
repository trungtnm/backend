{!! $helpText !!}
<textarea class="form-control" rows="10" cols="80" id="{{ $field }}" name="{{ $field }}" placeholder="">
    {{  $value or old($field)  }}
</textarea>
<script>
    var editor = CKEDITOR.replace( '{{ $field }}', CkOptions );
</script>