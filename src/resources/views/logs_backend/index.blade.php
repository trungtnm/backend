 <div class="page-content-area">
        <div class="page-header">
            <h1>
                Show list logs
            </h1>
        </div>

        <div class="row">
            <div class="col-xs-12">
            {{  Form::open(array( 'class' => 'formSearchLogs' , 'id' => 'formSearchLogs')) }}
                <div class="row">
                    <div class="widget-box form-filter-advertise">
                        <div class="widget-header widget-header-small">
                            <h5 class="widget-title lighter">Form Search Logs</h5>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main"> 
                                {{ Form::text('search' , isset($searchkey) ? $searchkey : ''  , array('class' => 'col-xs-8 col-sm-3' , 'placeholder' => 'Title Search')) }}&nbsp;
                                
                                <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 8px;margin-left:5px; border: 1px solid #ccc">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <span></span> <b class="caret"></b>
                                </div>
                                <input type="hidden" name="date_start" id="date_start" value="">
                                <input type="hidden" name="date_end" id="date_end" value="">

                                <button type="submit" name="submit" value="search" class="btn btn-xs btn-primary" style="height: 30px;margin: 0px 5px;width: 120px;">
                                    <i class="ace-icon fa fa-search icon-on-right bigger-110"></i> Search
                                </button>
                                <button type="submit" name="submit" value="delete" class="btn btn-xs btn-primary" style="height: 30px;margin: 0px 5px;width: 120px;">
                                    <i class="ace-icon fa fa-trash-o bigger-110"></i>Dell All
                                </button>

                            </div>
                        </div>
                    </div>
                </div> 
            {{ $logs->links() }}
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                           <th>
                               <label class="inline middle">
                                    <input type="checkbox" class="ace chbAdvertiseAllItem" name="" >
                                    <span class="lbl"></span>
                                </label>
                           </th>                          
                           <th width="6%">Id</th>                           
                           <th width="10%">Module</th>  
                           <th width="10%">Actions</th>    
                           <th>Title</th>
                           <th width="20%">Account</th> 
                           <th width="15%">Created at</th>                        
                           <th width="10%">IP</th>
                           <th width="6%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $result)
                        <tr>
                            <td>
                                <label class="position-relative">
                                    <input type="checkbox" class="ace chbItemAdvertise" name="chbItemAdvertise[{{$result->id}}]" value="{{$result->id}}">
                                    <span class="lbl"></span>
                                </label>
                            </td>
                            <td>{{ $result->id }} </td> 
                            <td>{{ $result->module }} </td> 
                            <td>{{ $result->type_task }} </td>
                            <td>{{ $result->title }} </td> 
                            <td>{{ $result->username }} </td> 
                            <td>{{ date('H:i:s d-m-Y', strtotime($result->created_at)) }} </td> 
                            <td>{{ $result->ipaddress }} </td> 
                            <td align="center">
                            <div class="hidden-sm hidden-xs btn-group">                                                                      
                                <a class="btn btn-xs btn-danger" onclick="if (!confirm('Bạn có chắc xóa không?')) {
                                            return false;
                                        }
                                        ;" href="{{ URL::to(Config::get('backend.uri') . "/logs/delete/{$result->id}") }}">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                </a>
                            </div>
                        </td>                                                      
                        </tr>
                        @endforeach
                    </tbody>                    
                </table>
               {{  Form::close() }}
               {{ $logs->links() }}
            </div>
        </div>
    </div>

{{  HTML::style("{$assetURL}css/daterangepicker/daterangepicker-bs3.css")  }}
{{  HTML::script("{$assetURL}js/daterangepicker/moment.js") }}
{{  HTML::script("{$assetURL}js/daterangepicker/daterangepicker.js") }}

<script type="text/javascript">
$(document).ready(function() {

    $("#formSearchLogs .chbAdvertiseAllItem").click(function (e) {
        $("#formSearchLogs .chbItemAdvertise").prop('checked', $(this).prop("checked"));
    });

    var cb = function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var date_start = start.format('YYYY-MM-DD');
        var date_end   = end.format('YYYY-MM-DD');
        $('#date_start').empty();
        $('#date_end').empty();
        $('#date_start').val(date_start);
        $('#date_end').val(date_end);
    }

    var optionSet1 = {
    startDate: moment().subtract(29, 'days'),
    endDate: moment(),
    //minDate: '01/01/2012',
    //maxDate: '12/31/2014',
    dateLimit: { days: 60 },
    showDropdowns: true,
    showWeekNumbers: true,
    timePicker: false,
    timePickerIncrement: 1,
    timePicker12Hour: true,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    opens: 'left',
    buttonClasses: ['btn btn-default'],
    applyClass: 'btn-small btn-primary',
    cancelClass: 'btn-small',
    format: 'MM/DD/YYYY',
    separator: ' to ',
    locale: {
        applyLabel: 'Submit',
        cancelLabel: 'Clear',
        fromLabel: 'From',
        toLabel: 'To',
        customRangeLabel: 'Custom',
        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        firstDay: 1
    }
    };

    $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
    var date_start = moment().subtract(29, 'days').format('YYYY-MM-DD');
    var date_end   = moment().format('YYYY-MM-DD');
    $('#date_start').empty();
    $('#date_end').empty();
    $('#date_start').val(date_start);
    $('#date_end').val(date_end);

    $('#reportrange').daterangepicker(optionSet1, cb);

    $('#reportrange').on('show.daterangepicker', function() { console.log("show event fired"); });
    $('#reportrange').on('hide.daterangepicker', function() { console.log("hide event fired"); });
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
    console.log("apply event fired, start/end dates are " 
      + picker.startDate.format('MMMM D, YYYY') 
      + " to " 
      + picker.endDate.format('MMMM D, YYYY')
    ); 
    });
    $('#reportrange').on('cancel.daterangepicker', function(ev, picker) { console.log("cancel event fired"); });

});
</script>
