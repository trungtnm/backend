@extends('TrungtnmBackend::layout.backend')

@section('content')
@include('TrungtnmBackend::includes.moduleHeader')
        <!-- form start -->
<form method="post" action="" role="form" class="form-edit form-horizontal pb10" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if(!empty($dataFields))
        <?php $counter = 0; ?>
        @foreach ($dataFields as $fieldName => $options)
            @if(!empty($options['onUpdate']) && empty($id) )
                <?php continue; ?>
            @endif
            <?php
            $data = !empty($options['data']) ? ${$options['data']} : [];
            ?>
            <div class="form-group <?php if($counter == 0) echo "first"; ?> ">
                <label for="{{ $fieldName }}" class="control-label col-sm-2">{{ $options['label'] }}</label>
                <div class="col-sm-10">
                    @if (!empty($langs) && count($langs) && !empty($item->translatedAttributes) && in_array($fieldName, $item->translatedAttributes))
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
                <div class="clearfix"></div>
            </div>
        @endforeach
    @endif
    <div id="preHiddenFields">
        {!! $customView or "" !!}
    </div>
    @include('TrungtnmBackend::includes.buttons')
</form>

@endsection