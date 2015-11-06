<?php
    $menus = App::make('Trungtnm\Backend\Http\Controller\MenuController')->getMenu();
    $backendUrl = url(config('trungtnm.backend.uri')) . "/";
?>
<div id="sidebar" class="sidebar responsive">
<script type="text/javascript">
    try {
        ace.settings.check('sidebar', 'fixed')
    } catch (e) {
    }
</script>        
<ul class="nav nav-list" style="top: 0px;">
    <?php
        $module = request()->segment(2);
    ?>
    @foreach ($menus as $menu)
    @if( empty($menu['children'] ))
    <li class="hsub @if( $module == head(explode("/", $menu->slug)) ){{ "active" }}@endif">
        <a class="" href="{{ $backendUrl.$menu->slug }}" >
            <i class="menu-icon {{{ $menu->icon }}}"></i>
            <span class="menu-text"> {{{ $menu->name }}} </span>
            {{-- <b class="arrow fa fa-angle-down"></b> --}}
        </a>
        <b class="arrow"></b>
    @else
    <li class="hsub @if( $module == head(explode("/", $menu->slug)) ){{ "active" }}@endif">
        <a class="dropdown-toggle" href="#" >
            <i class="menu-icon {{  $menu->icon  }}"></i>
            <span class="menu-text">{{  $menu->name  }}</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
    @endif
        @if( !empty($menu['children'] ))
        <ul class="submenu">
        @foreach ($menu['children'] as $child)
                <li class="menu-child @if( $module == head(explode("/", $child->slug)) ){{ "active" }}@endif">
                    <a href="{{  $backendUrl.$child->slug  }}">
                        <i class="menu-icon {{  $child->icon  }}"></i>{{  $child->name }}
                    </a>
                    <b class="arrow"></b>
                </li>
        @endforeach
        </ul>
        @endif
    </li>

    @endforeach

</ul>
    <!-- /.sidebar -->
<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
    <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
</div>

<script type="text/javascript">
    try {
        ace.settings.check('sidebar', 'collapsed')
    } catch (e) {
    }
    $('.submenu li.active').parent().parent().addClass('active');
</script>
</div>

