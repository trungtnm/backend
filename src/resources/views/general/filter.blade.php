@section('filters')
@if(!empty($searchFields))
    <div class="relative pull-left">
        <label for="keyword">
            <span class="label label-info label-block">Keyword</span>
        </label>
        <input type="text" name="keyword" value="{{ request('keyword') }}" id="keyword"/>
        <label for="search__filterBy">
            <span class="label label-info label-block">by</span>
        </label>
        <select name="search__filterBy" id="" class="btn-primary">
            @foreach($searchFields as $val => $text)
                <option value="{{  $val }}">{{$text}}</option>
            @endforeach
        </select>
    </div>
@endif
{{-- SEARCH SELECTS --}}
@if(!empty($searchSelects))
    @foreach ($searchSelects as $field => $info)
        <div class="relative pull-left">
            <label for="search__{{ $field }}"><span class="label label-info label-block">{{$info['label']}}</span></label>
            <select name="search__{{ $field }}" id="" class="{{ !empty($info['class']) ? $info['class'] : 'btn-primary' }}">
                <option value="all">All</option>
                @foreach($$info['options'] as $val => $text)
                    <option value="{{  $val }}">{{$text}}</option>
                @endforeach
            </select>
        </div>
    @endforeach
@endif
{{-- END SEARCH SELECTS --}}
<div class="relative pull-left">
    <span class="label label-info label-block">Status</span>
    <select name="search__status" id="" class="btn-primary">
        <option value="all">All</option>
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>
@endsection

@section('buttons')
    <button type="submit" class="btn btn-info btn-round btn-filter-sm">
        <i class="fa fa-search"></i>&nbsp;Search
    </button>

    @if($showAddButton)
        <a href="{{ route($module . "Create") }}" class="btn btn-success btn-round" role="button">
            <i class="fa fa-lg fa-plus-square-o"></i>&nbsp;
            Add
        </a>
    @endif
@endsection

<div class="widget-box widget-color-blue">
    <div class="widget-header">
        <h5 class="widget-title bigger lighter">
            <i class="ace-icon fa fa-search"></i>
            Quick filter
        </h5>
    </div>
    <div class="widget-body">
        <div class="widget-main ">
            <form action="{{route($module . "Adapter")}}" id="filter-form" class="form-search form-horizontal" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <table width="100%">
                    <tbody>
                    <tr width="70%">
                        <td width="70%">
                            <div class="relative pull-left">
                                <label for="search_id"><span class="label label-info label-block">ID</span></label>
                                <input type="text" name="search_id" value="{{ request('search__id') }}" style="width: 50px;"/>
                            </div>
                            @yield('filters')
                        </td>
                        <td width="30%" class="text-right" style="border-left: 1px dashed #cecece">
                        @yield('buttons')
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        pagination.init({
            url : "{{ route($module . "Adapter") }}",
            defaultField : "{{ $defaultField }}",
            defaultOrder : "{{ $defaultOrder }}"
        });
    });
</script>