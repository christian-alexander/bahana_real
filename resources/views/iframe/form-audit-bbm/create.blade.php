<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Form Audit BBM</title>
    <style>
        body{ padding: 20px; }
    </style>
</head>
<body>
    <h2><label for="audit-oli-kapal" class="col-4 col-form-label">Sounding BBM Harian</label></h2>
    <hr style="height:2px;border-width:0;color:black;background-color:black;text-align:left;margin-left:0">
    <form method="post" action="/form-audit-bbm/create" enctype="multipart/form-data">
        @csrf
        @php
        $current_timestamp = Carbon\Carbon::now();
        @endphp
        <input id="start" name="start_at" placeholder="volume" type="hidden" class="form-control" value="{{$current_timestamp}}">
        <label for="text" class="col-4 col-form-label">No Form Sounding BBM</label>
        <div class="form-group">  
            <input id="text" name="no_form" placeholder="No Form" type="text" class="form-control">
        </div>
        <label for="date" class="col-4 col-form-label">Tanggal</label>
        <div class="form-group"> 
            <div class="input-group">
                <input type="hidden" name="tanggal" value="{{ Carbon\Carbon::now()->translatedFormat('Y-m-d') }}">
                <input type="text" class='form-control' value="{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}" disabled>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </div>
                </div>
            </div>
        </div> 
        <label for="auditor" class="col-4 col-form-label"> Nama Auditor</label>
        <div class="form-group"> 
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="text" value='{{ $user->name }}' class='form-control' disabled>
        </div>
        <label for="kapal" class="col-4 col-form-label">Nama Kapal</label>
        <div class="form-group"> 
            <select id="kapal" name="kapal" class="custom-select">
                @foreach ($offices as $office)
                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                @endforeach
            </select>
        </div>
        <label for="posisi" class="col-4 col-form-label">Posisi</label>
        <div class="form-group"> 
            <textarea id="posisi" name="posisi" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <label for="kompartemen" class="col-4 col-form-label">kompartemen</label>
        <div class="form-group"> 
            <select id="kompartemen" name="kompartemen" class="custom-select">
            <option value="s1">S1</option>
            <option value="s2">S2</option>
            <option value="s3">S3</option>
            <option value="p1">P1</option>
            <option value="p2">P2</option>
            <option value="p3">P3</option>
            <option value="ae">AE</option>
            <option value="me">ME</option>
            <option value="daily tank">Daily Tank</option>
            <option value="cargo pump">Cargo Pump</option>
            </select>
        </div>
        <label for="produk" class="col-4 col-form-label">Produk</label> 
        <div class="form-group">
            <select id="produk" name="produk" class="custom-select">
                <option value="hsd">HSD</option>
                <option value="b20">B20</option>
                <option value="b30">B30</option>
                <option value="mfo">MFO</option>
                <option value="lsfo">LSFO</option>
            </select>
        </div>
        <label for="ketinggian" class="col-4 col-form-label">Ketinggian</label>
        <div class="form-group"> 
            <input id="ketinggian" name="ketinggian" placeholder="Ketinggian cairan" type="text" class="form-control">
        </div>
        <label for="volume" class="col-4 col-form-label">Volume</label>
        <div class="form-group"> 
            <input id="volume" name="volume" placeholder="volume" type="text" class="form-control">
        </div>
        <label for="upload2" class="col-4 col-form-label">Upload Foto Per Kompartemen</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="upload2" name="upload2" type="file" class="form-control"> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-image"></i>
                    </div>
                </div>
            </div>
        </div> 
        <label for="sounding" class="col-4 col-form-label">Sounding OOB/Perwira</label>
        <div class="form-group"> 
            <input id="sounding" name="sounding" placeholder="" type="text" class="form-control">
        </div>
        <label for="volume2" class="col-4 col-form-label">Volume</label>
        <div class="form-group"> 
            <input id="volume2" name="volume2" placeholder="volume" type="text" class="form-control">
        </div>
        <label for="item" class="col-4 col-form-label">Tambah Item</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="item" name="item" type="text" class="form-control"> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        <label for="lampiran" class="col-4 col-form-label">tambah lampiran</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="lampiran" name="lampiran" type="file" class="form-control"> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-image"></i>
                    </div>
                </div>
            </div>
        </div>
        <label for="catatan" class="col-4 col-form-label">Catatan</label>
        <div class="form-group">
            <textarea id="catatan" name="catatan" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <label for="temuan" class="col-4 col-form-label">Temuan</label>
        <div class="form-group"> 
            <textarea id="temuan" name="temuan" cols="40" rows="5" class="form-control"></textarea>
            {{-- <button name="tambah-temuan" type="submit" class="btn btn-primary" style="margin-top: 5px;">+</button> --}}
        </div>
        <label for="ttd" class="col-4 col-form-label">Kolom TTD Perwira Mesin</label>
        <div class="form-group"> 
            <textarea id="ttd" name="ttd" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <label for="upload" class="col-4 col-form-label">Upload Foto Perwira Mesin</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="upload" name="upload" type="file" class="form-control"> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-image"></i>
                    </div>
                </div>
            </div>
        </div> 
        <div class="form-group">
            <button name="save-as" type="button" class="btn btn-primary">Save As</button>
            <button type="button" class="btn btn-primary" onclick="get_time_now()">Start</button>
            <button name="stop" type="submit" class="btn btn-primary">Simpan Laporan</button>
        </div>
    </form>


    <script>
        function get_time_now(){
            alert("waktu pengisian form telah dimulai");
            let ms = Math.floor(Date.now() / 1000);
            document.getElementById("start").value = ms;
        }
    </script>
</body>
</html>