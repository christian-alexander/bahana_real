@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang("app.menu.home")</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
@endpush

@section('content')
<style type="text/css">

    #mymap {

        border:1px solid red;

        width: 800px;

        height: 500px;

    }
  
#legend {
  font-family: Arial, sans-serif;
  background: #fff;
  padding: 10px;
  margin: 10px;
  border: 3px solid #000;
    overflow: scroll;
    height: 400px;
}

#legend h3 {
  margin-top: 0;
}

#legend a {
  cursor: default;
}

#legend div {
  margin-bottom:5px;
}
</style>
    @section('filter-section')
        <div class="row">
            {!! Form::open(['id'=>'storePayments','method'=>'POST','action'=>'Admin\AdminReportTrackerController@find']) !!}
            @csrf
            <div class="col-md-12">
                <div class="example">
                    <h5 class="box-title m-t-20">@lang('app.selectDateRange')</h5>

                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" id="start-date" name="startDate" placeholder="@lang('app.startDate')"
                        value="{{$todayStart}}"/>
                        <span class="input-group-addon bg-info b-0 text-white">@lang('app.to')</span>
                        <input type="text" class="form-control" id="end-date" name="endDate" placeholder="@lang('app.endDate')"
                        value="{{$todayEnd}}"/>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <h5 class="box-title m-t-20">Nama Karyawan</h5>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <select class="select2 form-control" data-placeholder="@lang('app.select') @lang('app.employee')" id="employee_id" name="employee_id">
                                <option value=""></option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{isset($employeeSelected)?($employeeSelected==$employee->id?'selected':''):''}}>{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-success" id="filter-results"><i class="fa fa-check"></i> @lang('app.apply')
                </button>
            </div>
            {!! Form::close() !!}

        </div>
    @endsection

    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
                <h3 class="box-title">Laporan Tracking</h3>
                @if (!isset($employeeSelected))
                    <center>
                        <h5>Tidak ada data</h5>
                    </center>
                @else
                    @if (!empty($arr_tracker) && $arr_tracker != '[]')
                        <div id="address-map-container" style="width:100%;height:600px; ">
                            <div style="width: 100%; height: 100%" id="map"></div>
                        </div>
                        <div id="legend"><h3>Track History</h3></div>
                    @else
                        <h2><center>Tidak ada data</center></h2>
                    @endif
                @endif
            </div>

        </div>

    </div>

@endsection

@push('footer-script')

    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/morrisjs/morris.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    {{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='{{ env('GOOGLE_MAPS_API_KEY') }}'"></script> --}}
    <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"></script>


    <script>
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        jQuery('#date-range').datepicker({
            toggleActive: true,
            weekStart:'{{ $global->week_start }}',
            format: '{{ $global->date_picker_format }}',
        });
        $('#filter-results').on('click', function(e){
            var employee = $('#employee_id').val();
            if (employee=='') {
                alert('Karyawan tidak boleh kosong');
                e.preventDefault();
            }
        })
        @if (!empty($arr_tracker))
            var tracker = "{{$arr_tracker}}";
            var trackerSort = "{{$tracker_sort}}";
            var label = "{{$arr_label}}";
            tracker = JSON.parse(tracker.replace(/&quot;/g,'"'));
            trackerSort = JSON.parse(trackerSort.replace(/&quot;/g,'"'));
            label = JSON.parse(label.replace(/&quot;/g,'"'));
      		var map;

            console.log(trackerSort);

            function initMap() {

              map = new google.maps.Map(document.getElementById("map"), {
                  zoom: 12,
                  center: tracker[0],
                  mapTypeId: "terrain"
              });
              const flightPlanCoordinates = tracker;
              const flightPath = new google.maps.Polyline({
                  path: flightPlanCoordinates,
                  geodesic: true,
                  strokeColor: "#FF0000",
                  strokeOpacity: 1.0,
                  strokeWeight: 2
              });
              flightPath.setMap(map);
              var count = 1;
                const legend = document.getElementById("legend");
              
              for (var i = 0; i < label.length; i++) {
                // if(i%25==0){
                  new google.maps.Marker({
                    position: tracker[i],
                    label: count.toString(),
                    title:label[i],
                    map:map,
                  });
                  const div = document.createElement("div");

                  var str_manual = (trackerSort[i].is_manual == '1') ? ' (Lacak Manual)' : '';

                    if (trackerSort[i].type != null) {
                        if (trackerSort[i].type=='clock_in') {
                            div.innerHTML = '<a onclick="changeCenter('+tracker[i].lat+','+tracker[i].lng+')">' + count + '.' + label[i] + '( Clock In )'+str_manual+'</a>';
                        }else if(trackerSort[i].type=='clock_out'){
                            div.innerHTML = '<a onclick="changeCenter('+tracker[i].lat+','+tracker[i].lng+')">' + count + '.' + label[i] + '( Clock Out )'+str_manual+'</a>';
                        }else{
                            div.innerHTML = '<a onclick="changeCenter('+tracker[i].lat+','+tracker[i].lng+')">' + count + '.' + label[i] + '( '+trackerSort[i].type+' )'+str_manual+'</a>';
                        }
                    }else{
                        div.innerHTML = '<a onclick="changeCenter('+tracker[i].lat+','+tracker[i].lng+')">' + count + '.' + label[i] + str_manual+'</a>';
                    }
                  legend.appendChild(div);
                  
                  
                  count++;
                // }
                
              }
                // map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

                // Define the symbol, using one of the predefined paths ('CIRCLE')
              // supplied by the Google Maps JavaScript API.
              const lineSymbol = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 4,
                strokeColor: "#900",
              };
              // Create the polyline and add the symbol to it via the 'icons' property.
              const line = new google.maps.Polyline({
                path: tracker,
                icons: [
                  {
                    icon: lineSymbol,
                    offset: "100%",
                  },
                ],
                map: map,
              });
              animateCircle(line);
              
            }
      		// Use the DOM setInterval() function to change the offset of the symbol
            // at fixed intervals.
            function animateCircle(line) {
              let count = 0;
              window.setInterval(() => {
                count = (count + 1) % 200;
                const icons = line.get("icons");
                icons[0].offset = count / 2 + "%";
                line.set("icons", icons);
              }, 20);
            }
      		function changeCenter(lat, long){
                  map.setCenter(new google.maps.LatLng(lat,long));
                  map.setZoom(18);
            }
        @endif
    </script>
@endpush