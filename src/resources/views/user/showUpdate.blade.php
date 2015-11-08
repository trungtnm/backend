<div class="box box-primary">
    <!-- form start -->
    {{ Form::open(array('role'=>'form')) }}
        <div class="box-body">
            <div class="row-fluid">
                @if(Sentry::getUser()->hasAccess($module . "-publish"))
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1" <?php if( isset($item->status) &&  $item->status == 1 ){ echo "selected='selected'"; }?> >Open</option>
                            <option value="0" <?php if( isset($item->status) &&  $item->status == 0 ){ echo "selected='selected'"; }?>>Close</option>
                        </select>
                    </div>
                </div>
                @endif
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Select Group</label>
                        <select class="form-control" id="group" name="group">
                            @if( count($groups) )
                                <option value="0">- Select group -</option>
                                @foreach ($groups as $group)
                                    <option value="{{{ $group->id }}}" {{{( isset($item->group->id) && $item->group->id == $group->id ) ? 'selected="selected"' : '' }}} > {{{ $group->name }}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="row-fluid">
                <div class="form-group">
                    <label>Email login</label>
                    <input type="text" class="form-control" id="email" value="{{{ $item->email or Input::get('email') }}}" name="email" placeholder="Input email login">
                    @if( isset($validate) && $validate->has('email')  )
                    <span class="text-warning">{{{ $validate->first('email') }}}</span>
                    @endif
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="password" value="" name="password" placeholder="Input password">
                    @if( isset($validate) && $validate->has('password')  )
                    <span class="text-warning">{{{ $validate->first('password') }}}</span>
                    @endif
                </div>
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





