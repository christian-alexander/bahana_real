<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" ><i class="icon-clock"></i> @lang('app.menu.attendance') @lang('app.details') </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Hadir <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_date_hadir)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_date_hadir as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{{formatedDateTimeWithoutHI($item)}}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>WFO <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_wfo)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_wfo as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{!!formatedDateTimeWithoutHIForWFO($item)!!}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>WFO Weekend<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_wfo_weekend)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_wfo_weekend as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{!!formatedDateTimeWithoutHIForWFO($item)!!}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>WFH <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_wfh)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_wfh as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{{formatedDateTimeWithoutHI($item)}}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>WFH Dengan Dinas Sementara <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_wfh_with_dinas)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_wfh_with_dinas as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{!!formatedDateTimeWithoutHIForWFO($item)!!}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>WFH Dengan Dinas Sementara Weekend <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_wfh_with_dinas_weekend)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_wfh_with_dinas_weekend as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{!!formatedDateTimeWithoutHIForWFO($item)!!}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>GPS <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_gps)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_gps as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{{formatedDateTimeWithoutHI($item)}}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Jam Kerja <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($cluster_type=='daily')
                                @php
                                    $idx=1;
                                    $cluster_json = json_decode($cluster_json);
                                @endphp
                                @foreach ($cluster_json as $key => $val)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>
                                            {{$key}} {{$val->jam_masuk}} - {{$val->jam_pulang}}
                                        </td>
                                    </tr>
                                @php
                                    $idx++;
                                @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td>1</td>
                                    <td>
                                        {{$cluster_start_hour}} - {{$cluster_end_hour}}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Terlambat <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_date_late)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_date_late as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>
                                            Jam absen: {{formatedDateTime($item["value"])}}<br>
                                            Jam masuk kantor: {{$item['office_start_time']}}<br>
                                            Total lama terlambat: {{$item["late"]}} 
                                        </td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Lembur <small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($data_lembur)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($data_lembur as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>
                                            
                                            Jam absen masuk: {{formatedDateTime($item["clock_in_time"])}}<br>
                                            Jam absen keluar: {{formatedDateTime($item["value"])}}<br>
                                            Jam pulang kantor: {{$item["office_end_time"]}}<br>
                                            Total lama lembur: {{$item["lembur"]}} 
                                        </td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Pulang Tidak Absen<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_date_pulang_tidak_absen)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_date_pulang_tidak_absen as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{{formatedDate($item)}}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Ijin Tidak Masuk<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($type_ijin_date['tidak-masuk']))
                                @if (count($type_ijin_date['tidak-masuk'])>0)
                                    @php
                                        $idx=1;
                                    @endphp
                                    @foreach ($type_ijin_date['tidak-masuk'] as $item)
                                        <tr>
                                            <td>{{$idx}}</td>
                                            <td>{{formatedDate($item)}}</td>
                                        </tr>
                                        @php
                                            $idx++;
                                        @endphp
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Ijin Terlambat<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($type_ijin_date['datang-terlambat']))
                                @if (count($type_ijin_date['datang-terlambat'])>0)
                                    @php
                                        $idx=1;
                                    @endphp
                                    @foreach ($type_ijin_date['datang-terlambat'] as $item)
                                        <tr>
                                            <td>{{$idx}}</td>
                                            <td>{{formatedDate($item)}}</td>
                                        </tr>
                                        @php
                                            $idx++;
                                        @endphp
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Ijin Pulang Awal<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($type_ijin_date['pulang-awal']))
                                @if (count($type_ijin_date['pulang-awal'])>0)
                                    @php
                                        $idx=1;
                                    @endphp
                                    @foreach ($type_ijin_date['pulang-awal'] as $item)
                                        <tr>
                                            <td>{{$idx}}</td>
                                            <td>{{formatedDate($item)}}</td>
                                        </tr>
                                        @php
                                            $idx++;
                                        @endphp
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Ijin Pulang Awal By System<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($type_ijin_date['pulang-awal-system']))
                                @if (count($type_ijin_date['pulang-awal-system'])>0)
                                    @php
                                        $idx=1;
                                    @endphp
                                    @foreach ($type_ijin_date['pulang-awal-system'] as $item)
                                        <tr>
                                            <td>{{$idx}}</td>
                                            <td>{{formatedDate($item)}}</td>
                                        </tr>
                                        @php
                                            $idx++;
                                        @endphp
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Ijin Keluar Kantor<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($type_ijin_date['keluar-kantor']))
                                @if (count($type_ijin_date['keluar-kantor'])>0)
                                    @php
                                        $idx=1;
                                    @endphp
                                    @foreach ($type_ijin_date['keluar-kantor'] as $item)
                                        <tr>
                                            <td>{{$idx}}</td>
                                            <td>{{formatedDate($item)}}</td>
                                        </tr>
                                        @php
                                            $idx++;
                                        @endphp
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Sakit<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($type_ijin_date['sakit']))
                                @if (count($type_ijin_date['sakit'])>0)
                                    @php
                                        $idx=1;
                                    @endphp
                                    @foreach ($type_ijin_date['sakit'] as $item)
                                        <tr>
                                            <td>{{$idx}}</td>
                                            <td>{{formatedDate($item)}}</td>
                                        </tr>
                                        @php
                                            $idx++;
                                        @endphp
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Cuti<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_date_cuti)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_date_cuti as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{{formatedDate($item)}}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>Alpha<small class="text-muted"></small></h4>
                    <table class="table table-nowrap mb-0" id="custom-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($arr_date_tidak_hadir)>0)
                                @php
                                    $idx=1;
                                @endphp
                                @foreach ($arr_date_tidak_hadir as $item)
                                    <tr>
                                        <td>{{$idx}}</td>
                                        <td>{{formatedDate($item)}}</td>
                                    </tr>
                                    @php
                                        $idx++;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td colspan="2" style="text-align: center">@lang('modules.attendance.noData')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>