<!DOCTYPE html>
<html>
<head>
	<title>Detail Form Surat Tugas</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
		div{
			font-size: 12px;
		}
	</style>
</head>
<body>
	<center>
		<h4>Detail Form Surat Tugas</h4>
	</center>
    <br>
    <div class="kt-portlet__body">
        <div class="form-group">
            <div class="kt-section__info black">No Surat Tugas</div>
            <div class="kt-section__content">{{$data->no}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nama PT</div>
            <div class="kt-section__content">{{$data->subcompany}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nama Bagian</div>
            <div class="kt-section__content">{{$data->department}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nama Pemberi Tugas</div>
            <div class="kt-section__content">{{$data->nama}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Jabatan</div>
            <div class="kt-section__content">{{$data->jabatan_satu}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">NIK</div>
            <div class="kt-section__content">{{$data->nik_satu}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nama yang Bertugas</div>
            <div class="kt-section__content">{{$data->name_bertugas}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Jabatan</div>
            <div class="kt-section__content">{{$data->jabatan_dua}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">NIK</div>
            <div class="kt-section__content">{{$data->nik_dua}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Rute Awal</div>
            <div class="kt-section__content">{{$data->rute_awal}}</div>
        </div>    
        <div class="form-group">
            <div class="kt-section__info black">Rute Akhir</div>
            <div class="kt-section__content">{{$data->rute_akhir}}</div>
        </div> 
        <div class="form-group">
            <div class="kt-section__info black">Keperluan</div>
            <div class="kt-section__content">{{$data->keperluan}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Tanggal Mulai</div>
            <div class="kt-section__content">{{$data->tanggal_mulai}}</div>
        </div>    <div class="form-group">
            <div class="kt-section__info black">Tanggal Selesai</div>
            <div class="kt-section__content">{{$data->tanggal_selesai}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Estimasi Biaya</div>
            <div class="kt-section__content">{{$data->estimasi_biaya}}</div>
        </div>
        {{-- <div class="form-group">
            <div class="kt-section__info black">Nominal</div>
            <div class="kt-section__content">{{$data->nominal}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Terbilang</div>
            <div class="kt-section__content">{{$data->terbilang}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Unsur PPH</div>
            <div class="kt-section__content">{{$data->unsur_pph}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nominal PPH</div>
            <div class="kt-section__content">{{$data->nominal_pph}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Approval Pajak</div>
            <div class="kt-section__content">{{$data->approval_pajak}}</div>
        </div> --}}
        <div class="form-group">
            <div class="kt-section__info black">ACC Atasan 1</div>
            <div class="kt-section__content">{{$data->acc_atasan_1}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">ACC Atasan 2</div>
            <div class="kt-section__content">{{$data->acc_atasan_2}}</div>
        </div>
        {{-- <div class="form-group">
            <div class="kt-section__info black">Note</div>
            <div class="kt-section__content">{{$data->note}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Attachment</div>
            <div class="kt-section__content">{{$data->image}}</div>
        </div> --}}
        {{-- <div class="form-group">
            <div class="kt-section__info black">Dibuat Oleh</div>
            <div class="kt-section__content">{{$data->name_pembuat}}</div>
        </div> --}}
        <div class="form-group">
            <div class="kt-section__info black">Status</div>
            <div class="kt-section__content">{{$data->status}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Pengajuan Terakhir</div>
            <div class="kt-section__content">{{empty($data->status_approval)?'-':$data->status_approval}}</div>
        </div>

</body>
</html>