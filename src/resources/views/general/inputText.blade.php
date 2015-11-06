<input type="text" class="form-control" id="{{ $field }}" value="{{ $value or Input::get($field) }}" name="{{ $field }}" placeholder="">
{!! $helpText !!}
