<div class="page-header">
    <h1>
        <i id="moduleIcon" class=""></i>
        <span>{{ ucfirst($module) }}</span>
        @if (!empty($item))
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            <span>{{ !empty($item->id) ? "Edit ( #". $item->id . " )" : "Create" }}</span>
        </small>
        @endif
        @if (!empty($subHeader))
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <span>{!! $subHeader !!}</span>
            </small>
        @endif
    </h1>
</div>