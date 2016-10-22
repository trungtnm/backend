<input type="number"
       class="form-control"
       id="{{ $field }}"
       value="{{ $value or Input::get($field) }}"
       name="{{ $field }}"
       @if (!empty($options['disabled']))
       disabled="disabled"
       @endif
       placeholder="">
{!! $helpText !!}
