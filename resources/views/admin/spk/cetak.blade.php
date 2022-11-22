<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SURAT PERMINTAAN KAPAL</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    
    <style>
        .border{
            border: solid;
        }
        .center{
            text-align: center;
        }
        .borderless td, .borderless th {
            border: none;
        }
    </style>
    <script>
        window.onload = function() { window.print(); }
    </script>
</head>
<body>
    <div class="container">
        <table class="table table-bordered center">
            <tr>
                <td rowspan="2" style="width: 20%">
                    <img src="{{asset('images/bahana.png')}}" style="width: 150px;margin-top:40px" alt="Logo" />
                </td>
                <td style="width: 40%"><h1>PT BAHANA LINE</h1></td>
                <td style="width: 20%">Form BL-0<br>PK-SET</td>
            </tr>
            <tr >
                <td style="width: 40%">DOKUMEN MANAJEMEN<br>KESELAMATAN KAPAL</td>
                <td style="width: 20%">Rev</td>
            </tr>
        </table>
        <div class="col-md-12 center">SURAT PERMINTAAN KAPAL</div>
        <table class="table borderless">
            <tbody>
                <tr>
                    <td style="width: 20%">MT/SPOB</td>
                    <td>: {{ strtoupper($spk->mt_or_spob)}}</td>
                </tr>
                <tr>
                    <td style="width: 20%">No</td>
                    <td>: {{ strtoupper($spk->no)}}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Keperluan</td>
                    <td>: {{ strtoupper($spk->keperluan)}}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Tanggal</td>
                    <td>: {{Carbon\Carbon::parse($spk->tanggal)->format('d-m-Y')}}</td>
                </tr>
                <tr>
                    <td colspan="2">Harap dibelikan Barang sbb:</td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Sisa Dikapal</th>
                    <th>Jumlah Diminta</th>
                    <th>Jumlah Disetujui</th>
                    <th>Keperluan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($spk->details as $item)
                    <tr>
                        <th>{{$loop->index+1}}</th>
                        <td>{{$item->barang_etc}} {{!empty($item->deleted_at)?'(DELETED)':''}}</td>
                        <td>-</td>
                        <td>{{$item->barang_diminta}}</td>
                        <td>{{$item->barang_disetujui}}</td>
                        <td>{{$item->ket}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"><center>Tidak ada data</center></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <table class="table borderless">
            <tr>
                <td>
                    Pengaju<br><br>
                    <img src="{{asset($spk->signature_applicant)}}" alt="" width="150px"><br>
                    Nama: {{$spk->user->name}}
                </td>
                @if (count($spk->approval)>0)
                    @foreach ($spk->approval as $item)
                        @if (!empty($item->signature))
                            <td>
                                {{$item->status=='approved_1'?'Nahkoda':'Manager'}}<br><br>
                                <img src="{{asset($item->signature)}}" alt="" width="150px"><br>
                                Nama: {{$item->approved_by_obj->name}} 
                            </td>
                        @else
                            <td>
                                {{$item->status=='approved_1'?'Nahkoda':'Manager'}}<br><br>
                                -
                                Nama: -
                            </td>
                        @endif
                    @endforeach
                @else
                    <td>
                        Nahkoda<br><br>
                        -<br><br>
                        Nama: -
                    </td>
                    <td>
                        Manager<br><br>
                        -<br><br>
                        Nama: -
                    </td>
                @endif
            </tr>
            <tr>
                <td colspan="3"><b>*Setiap permintaan spare part / barang wajib dijelaskan spesifikasinya secara jelas</b></td>
            </tr>
            <tr>
                <td colspan="3">Catatan: {{$spk->note}}</td>
            </tr>
        </table>
    </div>
</body>
</html>