@if( isset($validate) && $validate->has($fieldName)  )
    <span class="text-warning">{!! $validate->first($fieldName) !!}</span>
@endif