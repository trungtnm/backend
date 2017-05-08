<input type="number"
       class="form-control"
       id="{{ $field }}"
       value="{{ $value or request($field) }}"
       name="{{ $field }}"
       @if (!empty($options['disabled']))
       disabled="disabled"
       @endif
       @if (!empty($options['step']))
       step="{{ $options['step'] }}"
       @else
       step="any"
       @endif
       placeholder="">
{!! $helpText !!}
