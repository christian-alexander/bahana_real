<div class="white-box">
    <div class="table-responsive tableFixHead table-content-class" style="overflow-x: scroll;">
        <table class="table table-nowrap mb-0" id="custom-datatable">
            <thead >
                <tr>
                    <th>Tanggal Pembuatan Tugas</th>
                    <th>Pembuat Tugas</th>
                    <th>Penerima Tugas</th>
                    <th>Judul Tugas</th>
                    <th>Deskripsi Tugas</th>
                    <th>Catatan Tambahan</th>
                    <th>Department Penerima Tugas</th>
                    <th>Proyek</th>
                    <th>Batas Waktu</th>
                    <th>Tanggal Tugas Dikerjakan</th>
                    <th>Tanggal Selesai Tugas</th>
                    <th>Status Tugas</th>
                    <th>Tanggal Approve</th>
                    <th>Nama Approval</th>
                    <th>Status Approval</th>
                    <th>Leadtime Pengerjaan Tugas (jam)</th>
                    <th>Leadtime Approval Tugas (jam)</th>
                    <th>Leadtime Setelah Blokir Absen (jam)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaves as $leave)
                    <tr>
                        <td>{{$leave['tanggal_pembuatan_tugas']}}</td>
                        <td>{{$leave['pembuat_tugas']}}</td>
                        <td>{{$leave['penerima_tugas']}}</td>
                        <td>{{$leave['judul_tugas']}}</td>
                        <td>{{$leave['deskripsi_tugas']}}</td>
                        <td>{{$leave['catatan_tambahan']}}</td>
                        <td>{{$leave['department_penerima_tugas']}}</td>
                        <td>{{$leave['proyek']}}</td>
                        <td>{{$leave['batas_waktu']}}</td>
                        <td>{{$leave['tanggal_tugas_dikerjakan']}}</td>
                        <td>{{$leave['tanggal_selesai_tugas']}}</td>
                        <td>{{$leave['status_tugas']}}</td>
                        <td>{{$leave['tanggal_approve']}}</td>
                        <td>{{$leave['nama_approval']}}</td>
                        <td>{{$leave['status_approval']}}</td>
                        <td>{{$leave['leadtime_pengerjaan_tugas']}}</td>
                        <td>{{$leave['leadtime_approval_tugas']}}</td>
                        <td>{{$leave['leadtime_setelah_blokir_absen']}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>