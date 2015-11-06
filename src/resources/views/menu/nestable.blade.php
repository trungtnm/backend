<div class="box box-primary">
    <div class="text-success" style="display:none"></div>

    <div class="box-footer">
        <button id="save-btn" type="button" class="btn btn-primary">Save</button>
    </div>

	<h2>Kéo thả để sắp xếp Menu</h2>
    <!-- form start -->
    <div class="box-body">
        @if( !empty($message) && isset($status) )
            <div class="{{{ ($status) ? 'text-success' : 'text-warning' }}}"> {{{$message}}} </div>
        @endif
			
		<ol class="sortable">
		@foreach( $listMenus as $menu )
			@if( !isset($menu['children']) )
		    	<li id="menu-{{{$menu['id']}}}"><div>{{{$menu['name']}}}</div></li>
			@else
			    <li id="menu-{{{$menu['id']}}}">
			        <div>{{{$menu['name']}}}</div>
			        <ol>
			        	@foreach( $menu['children'] as $child )
			            <li id="menu-{{{$child['id']}}}"><div>{{{$child['name']}}}</div></li>
			            @endforeach
			        </ol>
			    </li>
			@endif
		@endforeach
		</ol>


    </div><!-- /.box-body -->

</div>


{{ HTML::script("{$assetURL}js/jquery-ui.min.js") }}
{{ HTML::script("{$assetURL}js/jquery.mjs.nestedSortable.js") }}

<script type="text/javascript">
	
    $(document).ready(function(){

        $('ol.sortable').nestedSortable({
            forcePlaceholderSize: true,
			handle: 'div',
			items: 'li',
			opacity: .6,
			placeholder: 'placeholder',
			tolerance: 'pointer',
			toleranceElement: '> div',
			maxLevels: 2,
        });

        $('#save-btn').click(function(){
			serialized = $('ol.sortable').nestedSortable('serialize');
			$.post(
				"{{{ $defaultURL }}}postNestable",
				serialized
				,
				function(data){
					if(data){
						$(".text-success").html("Cập nhật thành công").stop().slideDown(200).delay(1000).slideUp(200);
					}
				}
			)
        })

    });

</script>
