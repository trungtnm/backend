<div id="navbar" class="navbar navbar-default">
<script type="text/javascript">
    try {
        ace.settings.check('navbar', 'fixed')
    } catch (e) {
    }
</script>

<div class="navbar-container" id="navbar-container">
<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
    <span class="sr-only">Toggle sidebar</span>

    <span class="icon-bar"></span>

    <span class="icon-bar"></span>

    <span class="icon-bar"></span>
</button>

<div class="navbar-header pull-left">
    <a href="#" class="navbar-brand">
        <small>
            Control Panel
        </small>
    </a>
</div>

<div class="navbar-buttons navbar-header pull-right" role="navigation">
<ul class="nav ace-nav" style="">




<li class="light-blue">
    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
        <span class="user-info">
            <small>Welcome,</small>
            {{ Sentinel::getUser()->email }}
        </span>

        <i class="ace-icon fa fa-caret-down"></i>
    </a>

    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
      
        <li>
            <a href=" {{ route('changePassword') }} " class="btn btn-default btn-flat">Change Password</a>
        </li>

        <li>
            <a href="{{ route('logoutBackend') }}">
                <i class="ace-icon fa fa-power-off"></i>
                Logout
            </a>
        </li>
    </ul>
</li>
</ul>
</div>
</div><!-- /.navbar-container -->
</div>