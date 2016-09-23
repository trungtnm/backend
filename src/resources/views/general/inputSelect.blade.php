<?php
$class = !empty($options['useChosen']) ? 'chosen' : 'form-control';
$defaultOption = !empty($options['defaultOption']) ? $options['defaultOption'] : [0 => "Select an option"];
?>
<select class="{{ $class }}" id="{{ $field }}" name="{{ $field }}">
	@if(!empty($defaultOption))
	@foreach($defaultOption  as $v => $t)
	<option value="{{ $v }}" {{ isSelected($value, $v) }} >{{ $t }}</option>
	@endforeach
	@endif

	@if(!empty($data))
	@foreach($data  as $v => $t)
	<option value="{{ $v }}" {{ isSelected($value, $v) }}>{{ $t }}</option>
	@endforeach
	@endif
</select>
{!! $helpText !!}

