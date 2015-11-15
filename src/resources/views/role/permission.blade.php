<div class="row-fluid" data-order="1">
    <label for="icon-preview" class="control-label col-sm-2">Role Permissions</label>
    <div class="col-sm-10">
        <a class="btn btn-default" href="javascript:;" id="collapse-toggle">
            Hide / Show all
        </a>
    </div>

    <div class="col-sm-12">
        <div class="panel-group" id="accordion">
            <?php $stt = 0; ?>
            @foreach( $moduleData as $moduleID => $nameModule)
                <div class="panel panel-default modulePanel pull-left">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a style="display: block" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ ++$stt }}">
                                {{ $nameModule }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-{{ $stt }}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="form-group">
                                @foreach ($permissionMap[strtolower($nameModule)] as $permission)
                                    <div class="checkbox">
                                        <label class="permission-label">
                                            <?php
                                            $perm = $permission['module'] . $permission['permission'];
                                            $checked = !empty($item) && array_key_exists($perm, $rolePermissions)
                                                    ? 'checked="checked"'
                                                    : '';
                                            ?>
                                            <input class="permission-checkbox" type="checkbox"
                                                   name="permissions[{!! $perm !!}]"
                                                   value="true"
                                                   {!! $checked !!}>
                                            {{ ucfirst(trim($permission['permission'], '.')) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<script>
$(document).ready(function(){
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
})
</script>