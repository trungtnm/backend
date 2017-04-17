<div class="clearfix"></div>
<div>
	@if(count($lists) > 0 )
	    {!! $lists->render() !!}
	@else
	<div style="height:34px;margin:20px;line-height:34px;padding: 0px 15px" class="pull-left label label-danger">
    No results found
  	</div>
	@endif
</div>
<table id="tableList" class="table table-bordered table-condensed table-responsive">
    <thead>
		<tr>
			<?php if( !empty($showFields) ){ ?>
				<th width="50">
					<div class="inline position-relative w40">
	                    <label class="inline middle chbAdvertiseAllItem">
	                        <input type="checkbox" class="ace chbAdvertiseAllItem" name="" >
	                        <span class="lbl"></span>
	                    </label>
	                   	<div class="inline position-relative form-group">
	                        <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle">
	                            <i class="ace-icon fa fa-caret-down bigger-125 middle"></i>
	                        </a>

	                        <ul class="dropdown-menu dropdown-lighter dropdown-100">
	                            <li>
	                                <a id="id-select-message-all" href="javascript:;">Active</a>
	                            </li>

	                            <li>
	                                <a id="id-select-message-none" href="javascript:;">Unactive</a>
	                            </li>
	                            <li>
	                                <a id="id-select-message-none" href="javascript:;">Delete</a>
	                            </li>
	                        </ul>
	                    </div>

	                </div>
				</th>
				<?php foreach( $showFields as $field =>	$info ){ ?>
                    <?php
                        $nextOrder = "desc";
                        $orderClass = "sorting";
                        if( $field == $defaultField ){
                            if( $defaultOrder == "desc" ){
                                $nextOrder = "asc";
                                $orderClass = "sorting_asc";
                            }else{
                                $orderClass = "sorting_desc";
                            }
                        }
                    ?>
                    <th class="<?=$orderClass?>"><a href="javascript:;" onclick="pagination.sort('<?=$field?>','<?=$nextOrder?>')"><?=$info['label']?></a></th>
				<?php } ?>
			<?php } ?>
			@if(!empty($showEditButton) || !empty($showDeleteButton))
			<th><a href="javascript:;">Action</a></th>
			@endif
		</tr>
    </thead>

	<tbody>
		<?php
		if( count($lists) ){
			$stt = ($lists->currentPage()-1) * $lists->perPage() ;
		?>
			<?php foreach( $lists as $item ){ $stt++; ?>
			<tr>
				<td>
					<label class="position-relative text-center">
	                    <input type="checkbox" class="ace chbItemAdvertise" name="chbItemAdvertise[]" value="{{ $item->id }}">
	                    <span class="lbl"></span>
	                </label>
				</td>
				<?php 
					if( !empty($showFields) ){
						foreach( $showFields as $field => $info ){
							echo $maker->make($item, $field, $info) ;
						} 
					} 
				?>
                @if(!empty($showEditButton) || !empty($showDeleteButton))
                <td width="200">
					<div class="col-md-5">
						@if(!empty($showEditButton))
		                <a class="btn btn-sm btn-info" href="{{ route($module.'Update', $item->id) }}">
		                    <i class="ace-icon fa fa-pencil bigger-120"></i>Edit
		                </a>
						@endif
		            </div>
		            <div class="col-md-5">
						@if(!empty($showDeleteButton))
		            	<a class="btn btn-sm btn-danger" onclick="deleteItem({{$item->id}})" href="javascript:;">
		                    <i class="ace-icon fa fa-trash-o bigger-120"></i>Delete
		                </a>
						@endif
		            </div>
		        </td>
				@endif
			</tr>
			<?php } ?>
		<?php }else{ ?>
			<tr>
				<td class="no-data" >No data</td>
			</tr>
		<?php } ?>
	</tbody>

</table>
<div class="clearfix"></div>
<script type="text/javascript">
	if( $(".no-data").length > 0 ){
		var colspan = $("#tableList th").length;
		$(".no-data").attr("colspan", colspan);
	} 
</script>

@section('script')
    {{-- your scripts for index page goes here --}}
@endsection
