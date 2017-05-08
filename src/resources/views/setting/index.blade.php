@extends('TrungtnmBackend::layout.backend')

@section('content')
    @include('TrungtnmBackend::includes.moduleHeader')
    <!-- END HEADER -->
    <form method="post" action="" role="form" class="form-edit form-horizontal pb10"
          enctype="multipart/form-data">
        <div class="row">
            <div class="col-xs-12">
                <div class="tabbable">
                    <ul class="nav nav-tabs" id="myTab">
                        <?php $first = true; ?>
                        @foreach($settings as $tab => $setting)
                            <li class="{{ $first ? 'active' : '' }}">
                                <a data-toggle="tab" href="#{{str_slug($tab)}}">
                                    {{ $setting['label'] }}
                                </a>
                            </li>
                            <?php $first = false; ?>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        <?php $first = true; ?>
                        @foreach($settings as $tab => $setting)
                            <div id="{{str_slug($tab)}}" class="tab-pane clearfix fade {{ $first ? 'in active' : '' }}">
                                <div class="row">
                                    <div class="col-xs-12">
                                        @if(!empty($setting['fields']))
                                            @foreach ($setting['fields'] as $group => $fields)
                                                <?php
                                                $data = !empty($options['data']) ? ${$options['data']} : [];
                                                ?>
                                                @if(strlen($group) > 3)
                                                    <h3>{{$group}}</h3>
                                                @endif
                                                @foreach($fields as $key => $field)
                                                    <?php
                                                    $options['type'] = isset($field['type']) ? $field['type'] : 'text';
                                                    $options['help'] = isset($field['help']) ? $field['help'] : '';
                                                    $fieldName = "settings[{$tab}_{$key}]";
                                                    $value = isset($values["{$tab}_{$key}"]) ? $values["{$tab}_{$key}"] :
                                                        '';
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="{{ $fieldName }}" class="col-sm-2 control-label
                                            no-padding-right">
                                                            {{ $field['label'] }}
                                                        </label>
                                                        <div class="col-sm-10">
                                                            {!! $maker->makeInput($fieldName, $options, $value) !!}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <?php $first = false; ?>
                        @endforeach
                    </div>

                </div>
                <div class="box-footer mt40">
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" name="save" value="save" class="btn btn-primary btn-block">Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ csrf_field() }}
    </form>
@endsection


