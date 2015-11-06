<div class="clearfix"></div>
<div>
	@if($lists->getLastPage() > 1 )
	{{$lists->links()}}
	@else
	<div style="padding-bottom:20px;width100%"></div>
	@endif
</div>
<table id="tableList" class="table table-bordered table-condensed table-responsive">
	
    <thead>
		<tr>
			<?php if( !empty($showField) ){ ?>
				<th>#</th>
				<?php foreach( $showField as $field =>	$title ){ ?>
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
					<th class="<?=$orderClass?>"><a href="javascript:;" onclick="pagination.sort('<?=$field?>','<?=$nextOrder?>')"><?=$title?></a></th>
				<?php } ?>
			<?php } ?>
			<th><a href="javascript:;">Status</a></th>
			<th><a href="javascript:;">Action</a></th>
		</tr>
    </thead>

	<tbody>
		<?php if( count($lists) ){ 
			$stt = ($lists->getCurrentPage()-1) * $lists->getPerPage() ; ;
		?>
		
			<?php foreach( $lists as $item ){ if($item->id == 1) continue; $stt++; ?>			
			
			<tr>
				<td>{{$stt}}</td>
				<?php if( !empty($showField) ){ ?>
					<?php foreach( $showField as $field =>	$title ){ ?>
						<td>{{ $item->{$field} }}</td>
					<?php } ?>
				<?php } ?>
				
				<?php
					if( $item->activated == 1 ){
						$status = "fa fa-check fa-check-right";
					}else{
						$status = "fa fa-times fa-times-wrong";
					}
				?>
				@if( !$item->isSuperUser() )
				<td class="status-{{{$item->id}}}"><a href="javascript:;" onclick="changeStatus('{{$item->id}}', '{{$item->activated}}')"><i class="{{{ $status }}}"></i></a></td>
				<td>
					<a class="btn btn-xs btn-info" href="{{{ URL::to($defaultURL.'update/'.$item->id) }}}">
		                    <i class="ace-icon fa fa-pencil bigger-120"></i>
	                </a>&nbsp;
	                <a class="btn btn-xs btn-danger" onclick="deleteItem({{{$item->id}}})" href="javascript:;">
	                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
	                </a>
				</td>
				@else
				<td><i class="{{{ $status }}}"></i></td>
				<td></td>
				@endif
			</tr>
			<?php } ?>
		<?php }else{ ?>
			<tr>
				<td colspan="6">Not found data</td>
			</tr>
		<?php } ?>
	</tbody>

</table>
<div>
	{{ $lists->links() }}
	<div class="clearfix"></div>
</div>
