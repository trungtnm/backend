<div class="box box-primary">
    <!-- form start -->
    {{ Form::open(array('role'=>'form')) }}
        <div class="box-body">
            @if( !empty($message) && isset($status) )
                <div class="{{{ ($status) ? 'text-success' : 'text-warning' }}}"> {{{$message}}} </div>
            @endif
            @if(Sentry::getUser()->hasAccess($module . "-publish"))
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1" <?php if( isset($item->status) &&  $item->status == 1 ){ echo "selected='selected'"; }?> >Open</option>
                            <option value="0" <?php if( isset($item->status) &&  $item->status == 0 ){ echo "selected='selected'"; }?>>Close</option>
                        </select>
                    </div>
                </div>
            </div>
            @endif

            <div class="form-group">
                <label>Name Group</label>
                <input type="text" class="form-control" id="name" value="{{{ $item->name or Input::get('name') }}}" name="name" placeholder="Name Group">
            </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
            <div class="row">

                <div class="col-xs-2">
                    <button type="submit" name="save" value="save-return" class="btn btn-primary btn-block">Save & Return</button>
                </div>

                <div class="col-xs-2">
                    <button type="submit" name="save" value="save-new" class="btn btn-primary btn-block">Save & Create New</button>
                </div>
                
                <div class="col-xs-2">
                    <button type="submit" name="save" value="save" class="btn btn-primary btn-block">Save</button>
                </div>

            </div>
        </div>
    {{ Form::close() }}
</div>





