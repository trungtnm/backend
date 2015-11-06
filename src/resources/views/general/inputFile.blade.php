<div class="col-sm-3">
	<input id="inputFile_{{ $field }}" type="file" name="{{ $field }}">
</div>
<div class="col-sm-9">
	<div class="checkbox inputURL_{{ $field }}">
	    <label>
	      <input value="1" type="checkbox" name="inputURL_{{ $field }}" id="inputURL_{{ $field }}"> Nhập URL
	    </label>
	  </div>
	<input style="display: none" id="{{ $field }}" type="text" name="{{ $field }}" value="{{ $value }}" class="form-control">
</div>


@if(isset($value))
<div>
    <a class="{{ !empty($option['useFancybox']) ? 'fancy' : '' }}" target="_blank" href="{{ URL::to($value) }}">Review</a>
	{!! $helpText !!}
</div>
@endif
<script type="text/javascript">
	$(document).ready(function(){
		$('.checkbox.inputURL_{{ $field }}').click(function(e){
			isCheck = $('#inputURL_{{ $field }}').is(':checked');
			if(isCheck){
				$('#{{ $field }}').show();
				$('#inputFile_{{ $field }}').prop('disabled','disabled');
			}
			else{
				$('#{{ $field }}').hide();
				$('#inputFile_{{ $field }}').removeProp('disabled');
			}
		})
	})
</script>