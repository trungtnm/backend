<div class="row text-center">
    @foreach($langs as $k => $lang)
    <a href="javascript:;" class="language-switcher {{ $k == 0 ? 'active' : ''  }}" data-locale="{{ $lang->locale }}">
        <img src="{{ asset($lang->flag) }}" alt="{{ $lang->name }}" height="40">
        <p>{{ $lang->name }}</p>
    </a>
    @endforeach
</div>