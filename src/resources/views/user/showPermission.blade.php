<div class="box box-primary">
    <!-- form start -->
    {{ Form::open(array('role'=>'form')) }}
        <div class="box-body">
            @if( !empty($message) && isset($status) )
                <div class="{{{ ($status) ? 'text-success' : 'text-warning' }}}"> {{{$message}}} </div>
            @endif
			
			<div class="form-group">
	        	<a class="btn btn-default" href="javascript:;" id="collapse-toggle">
	        		Hide / Show all
	        	</a>
        	</div>

			<div class="panel-group" id="accordion">
				<?php $stt = 0; ?>
				@foreach( $permissionMap as $key => $permission )
					<?php $stt++; ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse-{{{$stt}}}">
									{{ $moduleData[$key] }}
								</a>
							</h4>
						</div>
						<div id="collapse-{{{$stt}}}" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="form-group"> 
									@foreach( $permission as $item )

									<div class="checkbox">
										<label>
											<?php $value = isset( $userPermissions[$item['slug']] ) ? $userPermissions[$item['slug']] : 0 ?>
											{{Form::checkbox($item['slug'], '1', $value)}}
											{{ucfirst($item['action'])}}
											<input type="hidden" name="{{$item['slug']}}" value="{{$value}}" />


										</label>
									</div>

									@endforeach									


                                </div>

							</div>
						</div>
					</div>

				@endforeach


			</div>
	

			</div><!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    {{ Form::close() }}
</div>

<script type="text/javascript">
	$().ready(function(){

		var toggle = "show";

		$("#collapse-toggle").click(function(){
			$('.collapse').collapse(toggle);
			if( toggle == "show" ){
				toggle = "hide";
			}else{
				toggle = "show";
			}
		});

		$('.collapse').collapse({
			toggle: false
		});

		$('input[type="checkbox"]').on('change', function(e){
	        if($(this).prop('checked'))
	        {
	            $(this).next().val(1);
	        } else {
	            $(this).next().val(0);
	        }
		});

		
	});

</script>





