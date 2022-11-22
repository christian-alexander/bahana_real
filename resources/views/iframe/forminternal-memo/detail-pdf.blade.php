<!DOCTYPE html>
<html>
<head>
	<title>Detail Form Internal Memo</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
		div{
			font-size: 13px;
		}
	</style>
</head>
<body>
	<center>
		<h4>Detail Form Internal Memo</h4>
	</center>
    <br>

    <div class="kt-portlet__body">
        <div class="form-group">
            <div class="kt-section__info black">No Internal Memo</div>
            <div class="kt-section__content">{{$data->no}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Dari</div>
            <div class="kt-section__content">{{$data->name_from}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nama PT</div>
            <div class="kt-section__content">{{$data->subcompany}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Divisi</div>
            <div class="kt-section__content">{{$data->department}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Kepada</div>
            <div class="kt-section__content">{{$data->name_to}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Nama PT</div>
            <div class="kt-section__content">{{$data->subcompany_2}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Divisi</div>
            <div class="kt-section__content">{{$data->department_2 }}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Atasan Langsung 1</div>
            <div class="kt-section__content">{{$data->atasan_langsung_1}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Atasan Langsung 2</div>
            <div class="kt-section__content">{{$data->atasan_langsung_2}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Dibuat Tanggal</div>
            <div class="kt-section__content">{{Carbon\Carbon::parse($data->tanggal)->format('d-m-Y')}}</div>
        </div>
        {{-- <div class="form-group">
            <div class="kt-section__info black">Tempat</div>
            <div class="kt-section__content">{{$data->tempat}}</div>
        </div> --}}
        <div class="form-group">
            <div class="kt-section__info black">Perihal</div>
            <div class="kt-section__content">{{$data->perihal}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Sifat</div>
            <div class="kt-section__content">{{$data->sifat}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Berita</div>
            <div class="kt-section__content">{{$data->berita}}</div>
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