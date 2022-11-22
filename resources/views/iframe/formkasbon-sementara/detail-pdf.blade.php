<!DOCTYPE html>
<html>
<head>
	<title>Detail Form Kasbon Sementara</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
		div{
			font-size: 13px;
		}
	</style>
</head>
<body>
	<center>
		<h4>Detail Form Kasbon Sementara</h4>
	</center>
    <br>
    <div class="kt-portlet__body">
        <div class="form-group">
            <div class="kt-section__info black">No Kasbon Sementara</div>
            <div class="kt-section__content">{{$data->no}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nama PT</div>
            <div class="kt-section__content">{{$data->subcompany}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nama</div>
            <div class="kt-section__content">{{$data->nama}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Bagian</div>
            <div class="kt-section__content">{{$data->department}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Tanggal</div>
            <div class="kt-section__content">{{Carbon\Carbon::parse($data->tanggal)->format('d-m-Y')}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Keperluan</div>
            <div class="kt-section__content">{{$data->keperluan}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nominal</div>
            <div class="kt-section__content">{{$data->nominal}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Terbilang</div>
            <div class="kt-section__content">{{$data->terbilang}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Diperiksa</div>
            <div class="kt-section__content">{{$data->diperiksa_1}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Mengetahui</div>
            <div class="kt-section__content">{{$data->mengetahui}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Disetujui</div>
            <div class="kt-section__content">{{$data->disetujui_1}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Deadline PTJ</div>
            <div class="kt-section__content">{{$data->deadline_ptj}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Note</div>
            <div class="kt-section__content">{{$data->note}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Attachment</div>
            <div class="kt-section__content">{{$data->image}}</div>
        </div>
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