<link rel="stylesheet" href="{{ $assetURL }}css/jquery.datetimepicker.css">
<script type="text/javascript" src="{{ $assetURL }}js/jquery.datetimepicker.js"></script>
<div class="input-group col-xs-5">
	<input type="text" class="form-control {{!empty($options['class']) ? $options['class'] : 'my_datetimepicker'}} " name="{{ $field }}" value="{{$value == "0000-00-00 00:00:00" || ( !empty($value->timestamp) && $value->timestamp < 0) ? "" : $value}}">
	{{-- this field data type preferred is timestamps --}}
	<span class="input-group-addon">
		<i class="fa fa-clock-o bigger-110"></i>
	</span>
</div>
{!! $helpText !!}