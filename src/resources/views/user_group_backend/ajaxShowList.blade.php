<div class="clearfix"></div>
<div>
	@if($lists->getTotal() > 0 )
	{{$lists->links()}}
	@else
	<div style="height:34px;margin:20px;line-height:34px;padding: 0px 15px" class="pull-left label label-danger">
    No results found
  	</div>
	@endif
</div>
<table id="tableList" class="table table-bordered table-condensed table-responsive">
	
    <thead>
		<tr>
			<?php if( !empty($showField) ){ ?>
				<th>ID</th>
				<?php foreach( $showField as $field =>	$info ){ ?>
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
			<th><a href="javascript:;">Permissions</a></th>
			<th><a href="javascript:;">Action</a></th>
		</tr>
    </thead>

	<tbody>
		<?php if( count($lists) ){ 
			$stt = ($lists->getCurrentPage()-1) * $lists->getPerPage() ; 
		?>
			<?php foreach( $lists as $item ){ $stt++; ?>
			<tr>
				<td>{{$item->id}}</td>
				<?php 
					if( !empty($showField) ){ 
						foreach( $showField as $field => $info ){
							echo AdminGetTypeContent::make($item, $field, $info) ;
						} 
					} 
				?>
				<td>
					<a class="btn btn-xs btn-info" href="{{{ URL::to($defaultURL.'permission/'.$item->id) }}}">
					<i class="ace-icon fa fa-pencil bigger-120"></i>Permissions</a>
				</td>
				<td>
					@if($item->name != 'superuser')
						<a class="btn btn-xs btn-info" href="{{{ URL::to($defaultURL.'update/'.$item->id) }}}">
		                    <i class="ace-icon fa fa-pencil bigger-120"></i>
		                </a>
					@endif
	                &nbsp;
				@if($item->name != 'superuser')
	                <a class="btn btn-xs btn-danger" onclick="deleteItem({{{$item->id}}})" href="javascript:;">
	                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
	                </a>
				@endif
				</td>
			</tr>
			<?php } ?>
		<?php }else{ ?>
			<tr>
				<td colspan="5">Not found data</td>
			</tr>
		<?php } ?>
	</tbody>

</table>

