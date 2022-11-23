<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Form Audit Kas Cabang</title>
    <style>
        body{ padding: 20px; }
    </style>
</head>
<body>
    <h2><label for="sounding-cargo" class="col-4 col-form-label">Form Audit Kas Cabang</label></h2>
    <hr style="height:2px;border-width:0;color:black;background-color:black;text-align:left;margin-left:0">
    <form method="post" action="form-audit-kas-cabang">
        @csrf
        @php
        use Carbon\Carbon;
        $current_timestamp = Carbon::now();
        @endphp
        <input id="start" name="start_at" placeholder="volume" type="hidden" class="form-control" value="{{$current_timestamp}}">
        <label for="no-form" class="col-4 col-form-label">No Form</label>
        <div class="form-group"> 
            <input id="no-form" name="no-form" placeholder="No Form" type="text" class="form-control">
        </div>
        <label for="date" class="col-4 col-form-label">Tanggal</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="date" name="tanggal" type="date" class="form-control"> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </div>
                </div>
            </div>
        </div> 
        <label for="auditor" class="col-4 col-form-label"> Nama Auditor</label>
        <div class="form-group"> 
            <input id="auditor" name="auditor" placeholder="Auditor Name" type="text" class="form-control">
        </div>
        <label for="lokasi-cabang" class="col-4 col-form-label">Lokasi Cabang</label>
        <div class="form-group"> 
            <select id="lokasi-cabang" name="lokasi-cabang" class="custom-select">
                <option value="kapal1">Kapal1</option>
                <option value="kapal2">Kapal2</option>
                <option value="kapal3">Kapal3</option>
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
                <input id="text1" name="foto" type="text" class="form-control"> 
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
            <button name="start" type="button" class="btn btn-primary">Start</button>
            <button name="stop" type="submit" class="btn btn-primary">Simpan Laporan</button>
        </div>
    </form>
</body>