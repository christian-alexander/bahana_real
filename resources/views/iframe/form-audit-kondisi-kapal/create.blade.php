<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Form Audit Kondisi Kapal</title>
    <style>
        body{ padding: 20px; }
    </style>
</head>
<body>
    <h2><label for="sounding-cargo" class="col-4 col-form-label">Form Audit Kondisi Kapal</label></h2>
    <hr style="height:2px;border-width:0;color:black;background-color:black;text-align:left;margin-left:0">
    <form method="post" action='/form-audit-kondisi-kapal'>
        @csrf
        <input type="hidden" name="start_at" value="2022-11-18 09:00:00">
        <input type="hidden" name="stop_at" value="2022-11-18 09:00:00">
        <input type="hidden" name="foto" value="foto">
        <label for="no-form" class="col-4 col-form-label">No Form</label>
        <div class="form-group"> 
            <input id="no-form" name="no_form" placeholder="No Form" type="text" class="form-control">
        </div>
        <label for="date" class="col-4 col-form-label">Tanggal</label>
        <div class="form-group"> 
            <input type="hidden" name="tanggal" value="{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}">
            <input type="text" class='form-control' value="{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}" disabled>
        </div>
        </div> 
        <label for="auditor" class="col-4 col-form-label"> Nama Auditor</label>
        <div class="form-group"> 
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="text" value='{{ $user->name }}' class='form-control' disabled>
        </div>
        <label for="office_id" class="col-4 col-form-label">Nama Kapal</label>
        <div class="form-group"> 
            <select id="office_id" name="office_id" class="custom-select">
                @foreach ($offices as $office)
                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                @endforeach
            </select>
        </div>
        <label for="posisi" class="col-4 col-form-label">Posisi</label>
        <div class="form-group"> 
            <textarea id="posisi" name="posisi" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <label for="catatan" class="col-4 col-form-label">Catatan</label>
        <div class="form-group">
            <textarea id="catatan" name="catatan" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <label for="text1" class="col-4 col-form-label">Upload Foto</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="text1" name="text1" type="text" class="form-control"> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-photo"></i>
                    </div>
                </div>
            </div>
        </div>
        <label for="temuan" class="col-4 col-form-label">Temuan</label>
        <div class="form-group"> 
            <textarea id="temuan" name="temuan" cols="40" rows="5" class="form-control"></textarea>
            <button name="tambah-temuan" type="submit" class="btn btn-primary" style="margin-top: 5px;">+</button>
        </div>
        <label for="ttd" class="col-4 col-form-label">Kolom Tanda Tangan</label>
        <div class="form-group"> 
            <textarea id="ttd" name="ttd" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <button name="save-as" type="button" class="btn btn-primary">Save As</button>
            <button type="button" class="btn btn-primary">Start</button>
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        </div>
    </form>
</body>

</html>