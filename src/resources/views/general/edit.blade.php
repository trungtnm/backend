@extends('TrungtnmBackend::layout.backend')

@section('content')
    @include('TrungtnmBackend::includes.moduleHeader')
    <!-- form start -->
    <div class="row">
        <div class="col-xs-12">
            <form method="post" action="" role="form" class="form-edit form-horizontal pb10" enctype="multipart/form-data">
                {{ csrf_field() }}
                @if(!empty($dataFields))
                    @foreach ($dataFields as $fieldName => $options)
                        @if(!empty($options['onUpdate']) && empty($id) )
                            <?php continue; ?>
                        @endif
                        <?php
                        $data = !empty($options['data']) ? ${$options['data']} : [];
                        ?>
                        <div class="form-group">
                            <label for="{{ $fieldName }}" class="col-sm-2 control-label no-padding-right">{{ $options['label'] }}</label>
                            <div class="col-sm-10">
                                @if (!empty($item->translatedAttributes) && in_array($fieldName, $item->translatedAttributes) && !empty($langs))
                                    @foreach($langs as $k => $lang)
                                        <?php
                                        $maker->setLocale($lang->locale);
                                        $itemTranslation = $item->translate($lang->locale);
                                        if (!empty($itemTranslation)) {
                                            $value = !empty($options['alias']) ? $maker->aliasFieldValue($options['alias'], $itemTranslation) :
                                                    $itemTranslation->{$fieldName};
                                        } else {
                                            $value = null;
                                        }
                                        ?>
                                        <div class="language-input language-{{ $lang->locale }} {{$k == 0 ? 'active' : ''}}">
                                            {!! $maker->makeInput($fieldName, $options, $value, $data ) !!}
                                            {!! $maker->showError(!empty($validate) ? $validate : null, $fieldName) !!}
                                        </div>
                                    @endforeach
                                @else
                                    <?php
                                    $maker->setLocale('');
                                    if (!empty($item)) {
                                        $value = !empty($options['alias']) ? $maker->aliasFieldValue($options['alias'], $item) :
                                                $item->{$fieldName};
                                    } else {
                                        $value = null;
                                    }
                                    ?>
                                    {!! $maker->makeInput($fieldName, $options, $value, $data ) !!}
                                    {!! $maker->showError(!empty($validate) ? $validate : null, $fieldName) !!}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                @include('TrungtnmBackend::includes.buttons')
            </form>
        </div>
    </div>

    <div id="preHiddenFields">
        {!! $customView or "" !!}
    </div>
    @if(!empty($scripts))
        {!! $scripts !!}
    @endif
@endsection