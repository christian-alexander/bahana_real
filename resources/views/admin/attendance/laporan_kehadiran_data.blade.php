<?php
    use App\Leave;
    $leave_taken = 0;
    $leaveTaken = Leave::leaveTaken($employees[0]->id);
    if ($leaveTaken['status']==200) {
        $leave_taken = $leaveTaken['data'];
    }
?>

<div class="white-box">
    <div class="table-responsive tableFixHead table-content-class" style="overflow-x: scroll;">
        <table class="table table-nowrap mb-0" id="custom-datatable">
            <thead >
                <tr>
                    <th>@lang('app.employee')</th>
                    <td>Hadir</td>
                    <td>WFO</td>
                    <td>WFH</td>
                    <td>WFH Dengan Dinas Sementara</td>
                    <td>WFH Dengan Dinas Sementara Weekend</td>
                    <td>WFO Weekend</td>
                    <td>GPS</td>
                    <td>Tidak Absen Masuk</td>
                    <td>Terlambat</td>
                    <td>Lembur</td>
                    {{-- <td>Ijin Terlambat</td>
                    <td>Ijin Pulang Awal</td>
                    <td>Ijin Tidak Masuk</td> --}}
                    <td>Pulang Tidak Absen</td>
                    {{-- <td>Sakit</td> --}}
                    <td>Ijin Tidak Masuk</td>
                    <td>Ijin Terlambat</td>
                    <td>Ijin Pulang Awal</td>
                    <td>Ijin Pulang Awal By System</td>
                    <td>Ijin Keluar Kantor</td>
                    <td>Sakit</td>
                    {{-- @foreach ($type_cuti as $val)
                        <td>{{$val->name}}</td>
                        @endforeach --}}
                        
                    <td>Cuti</td>
                    <td>Alpha</td>
                    @foreach ($leave_taken as $key => $item)
                        <td>{{$key}} Diambil</td>
                        <td>{{$key}} Tersisa</td>
                    @endforeach
                    <th>@lang('app.action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)

                

                    <tr>
                        <td>{!!$employee->name!!}</td>
                        <td>{{$employee->hadir}}</td>
                        <td>{{$employee->wfo}}</td>
                        <td>{{$employee->wfh}}</td>
                        <td>{{$employee->wfh_with_dinas}}</td>
                        <td>{{$employee->wfh_with_dinas_weekend}}</td>
                        <td>{{$employee->wfo_weekend}}</td>
                        <td>{{$employee->gps}}</td>
                        <td>{{$employee->tidak_absen_masuk}}</td>
                        <td>{{$employee->terlambat}}</td>
                        <td>{{$employee->lembur}} menit</td>
                        {{-- <td>{{$employee->ijin_terlambat}}</td>
                        <td>{{$employee->ijin_pulang_awal}}</td>
                        <td>{{$employee->ijin_tidak_masuk}}</td> --}}
                        <td>{{$employee->pulang_tidak_absen}}</td>
                        {{-- <td>{{$employee->sakit}}</td> --}}
                        @foreach ($type_ijin as $val)
                            @if (isset($employee->type_ijin[$val]) && !empty($employee->type_ijin))
                                <td>{{$employee->type_ijin[$val]}}</td>
                            @else    
                                <td>0</td>
                            @endif
                        @endforeach
                        {{-- @foreach ($type_cuti as $val)
                            @if (isset($employee->type_cuti[$val->name]) && !empty($employee->type_cuti))
                                <td>{{$employee->type_cuti[$val->name]}}</td>
                            @else    
                                <td>0</td>
                            @endif
                        @endforeach --}}
                        <td>{{$employee->cuti}}</td>
                        <td>{{$employee->alpha}}</td>
                        @foreach ($leave_taken as $key => $item)
                            <td>{{$item['leave_taken']}}</td>
                            <td>{{$item['leave_remaining']}}</td>
                        @endforeach
                        <td>
                            <button type="button" class="btn btn-sm btn-primary btn-detail" data-user="{{$employee->id}}">Detail</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>