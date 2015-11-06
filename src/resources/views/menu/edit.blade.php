<div class="row-fluid">
    <label for="icon-preview" class="control-label col-sm-2">Select icon</label>
    <div class="col-sm-10">
        <a class="btn btn-info btn-lg dropdown-toggle" href="javascript:;" id="icon-preview" style="margin-left: 15px" data-toggle="dropdown">
            <i class="{{ $item->icon or "fa fa-file" }}"></i>
        </a>
        <ul class="dropdown-menu pull-left" role="menu" style="width:100%">
            <div style="padding: 5px">
                @include('TrungtnmBackend::menu.icons')
            </div><!-- /#ion-icons -->
        </ul>
    </div>
    <div class="clearfix"></div>
    <span style="font-size: 24px;padding-left:15px;line-height:30px;" id="add_icon" class=""></span>
    <input type="hidden" name="icon" id="icon" value="{{ $item->icon or "fa fa-file" }}">
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        $('#icon-table a').click(function() {
            var icon =  $(this).find('i').prop('class');
            $('#icon').val(icon);
            $('#icon-preview i').prop('class',icon);
        });

        $('#icon-table a').prop('href', "javascript:;");
    });
</script>