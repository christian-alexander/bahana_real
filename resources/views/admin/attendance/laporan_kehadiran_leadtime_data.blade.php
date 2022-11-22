<div class="white-box">
    <div class="table-responsive tableFixHead table-content-class" style="overflow-x: scroll;">
        <table class="table table-nowrap mb-0" id="custom-datatable">
            <thead >
                <tr>
                    <th>Tanggal Pembuatan Ijin</th>
                    <td>Nama PT</td>
                    <td>Departemen</td>
                    <td>Pembuat Ijin</td>
                    <td>Jenis Ijin</td>
                    <td>Deskripsi Ijin</td>
                    <td>Tanggal Awal Ijin</td>
                    <td>Tanggal Akhir Ijin</td>
                    <td>Status</td>
                    <td>Approval 1 Nama</td>
                    <td>Approval 1 Tanggal</td>
                    <td>Approval 1 Waktu/Jam</td>
                    <td>Lead Time (day)</td>
                    <td>Approval 2 Nama</td>
                    <td>Approval 2 Tanggal</td>
                    <td>Approval 2 Waktu/Jam</td>
                    <td>Lead Time (day)</td>
                    <td>Approval HRD Nama</td>
                    <td>Approval HRD Tanggal</td>
                    <td>Approval HRD Waktu/Jam</td>
                    <td>Lead Time (day)</td>
                    <td>Total Lead Time (day)</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaves as $leave)
                    <tr>
                        {{-- 23 --}}
                        <td>{{$leave['tanggal_pembuatan_ijin']}}</td>
                        <td>{{$leave['nama_pt']}}</td>
                        <td>{{$leave['departemen']}}</td>
                        <td>{{$leave['pembuat_ijin']}}</td>
                        <td>{{$leave['jenis_ijin']}}</td>
                        <td>{{$leave['deskripsi_ijin']}}</td>
                        <td>{{$leave['tgl_awal_ijin']}}</td>
                        <td>{{$leave['tgl_akhir_ijin']}}</td>
                        <td>{{$leave['status']}}</td>
                        <td>{{$leave['approval_1']['nama']}}</td>
                        <td>{{$leave['approval_1']['tanggal']}}</td>
                        <td>{{$leave['approval_1']['jam']}}</td>
                        <td>{{$leave['approval_1']['leadtime']}}</td>
                        <td>{{$leave['approval_2']['nama']}}</td>
                        <td>{{$leave['approval_2']['tanggal']}}</td>
                        <td>{{$leave['approval_2']['jam']}}</td>
                        <td>{{$leave['approval_2']['leadtime']}}</td>
                        <td>{{$leave['hrd']['nama']}}</td>
                        <td>{{$leave['hrd']['tanggal']}}</td>
                        <td>{{$leave['hrd']['jam']}}</td>
                        <td>{{$leave['hrd']['leadtime']}}</td>
                        <td>{{$leave['total']}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>