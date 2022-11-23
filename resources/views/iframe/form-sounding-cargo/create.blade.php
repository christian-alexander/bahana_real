
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Form Audit Cargo</title>
    <style>
        body{ padding: 20px; }
    </style>
</head>
<body>
    <h2><label for="sounding-cargo" class="col-4 col-form-label">Sounding Cargo</label></h2>
    <hr style="height:2px;border-width:0;color:black;background-color:black;text-align:left;margin-left:0">
    <form method="post" action="form-sounding-cargo">
        @csrf
        @php
            use Carbon\Carbon;
            $current_timestamp = Carbon::now();
        @endphp
        <input id="start" name="start" placeholder="volume" type="hidden" class="form-control" value="{{$current_timestamp}}">

        <label for="text" class="col-4 col-form-label">No Form Sounding Cargo</label>
        <div class="form-group"> 
            <input id="text" name="no_form" placeholder="No Form" type="text" class="form-control" required>
        </div>
        <label for="date" class="col-4 col-form-label">Tanggal</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="date" name="date" type="date" class="form-control" required> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </div>
                </div>
            </div>
        </div> 
        <label for="auditor" class="col-4 col-form-label"> Nama Auditor</label>
        <div class="form-group"> 
            <input id="auditor" name="auditor" placeholder="Auditor Name" type="text" class="form-control" required>
        </div>
        <label for="kapal" class="col-4 col-form-label">Nama Kapal</label>
        <div class="form-group"> 
            <select id="kapal" name="kapal" class="custom-select">
                <option value="1">1</option>
                <option value="0">0</option>
                <option value="0">0</option>
            </select>
        </div>
        <label for="textarea" class="col-4 col-form-label">Posisi</label>
        <div class="form-group"> 
            <textarea id="textarea" name="posisi" cols="40" rows="5" class="form-control" required></textarea>
        </div>
        <label for="kompartemen" class="col-4 col-form-label">Kompartemen</label> 
        <div class="form-group">
            <select id="kompartemen" name="kompartemen" class="custom-select">
                <option value="s1">S1</option>
                <option value="s2">S2</option>
                <option value="s3">S3</option>
                <option value="s4">S4</option>
                <option value="s5">S5</option>
                <option value="s6">S6</option>
                <option value="p1">P1</option>
                <option value="p2">P2</option>
                <option value="p3">P3</option>
                <option value="p4">P4</option>
                <option value="p5">P5</option>
                <option value="p6">P6</option>
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
            <input id="ketinggian" name="ketinggian" placeholder="Ketinggian cairan" type="number" class="form-control" required>
        </div>
        <label for="volume" class="col-4 col-form-label">Volume</label>
        <div class="form-group"> 
            <input id="volume" name="volume" placeholder="volume" type="number" class="form-control" required>
        </div>
        <label for="text1" class="col-4 col-form-label">Upload Foto</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="text1" name="foto1" type="text" class="form-control" required> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-photo"></i>
                    </div>
                </div>
            </div>
        </div> 
        <h2><label for="sounding-oob" class="col-4 col-form-label">Sounding OOB/Perwira</label></h2>
        <hr style="height:2px;border-width:0;color:black;background-color:black;text-align:left;margin-left:0">
        <label for="ketinggian" class="col-4 col-form-label">Ketinggian</label>
        <div class="form-group"> 
            <input id="ketinggian" name="ketinggian2" placeholder="Ketinggian cairan" type="number" class="form-control">
        </div>
        <label for="volume" class="col-4 col-form-label">Volume</label>
        <div class="form-group"> 
            <input id="volume" name="volume2" placeholder="volume" type="number" class="form-control">
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
        <label for="lampiran" class="col-4 col-form-label">Tambah Lampiran</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="lampiran" name="lampiran" type="text" class="form-control"> 
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
            <button name="tambah-temuan" type="button" class="btn btn-primary" style="margin-top: 5px;">+</button>
        </div>
        <label for="ttd" class="col-4 col-form-label">Kolom TTD Perwira/OOB</label>
        <div class="form-group"> 
            <textarea id="ttd" name="ttd" cols="40" rows="5" class="form-control"></textarea>
        </div>
        <label for="upload" class="col-4 col-form-label">Upload Foto</label>
        <div class="form-group"> 
            <div class="input-group">
                <input id="upload" name="upload2" type="text" class="form-control"> 
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-image"></i>
                    </div>
                </div>
            </div>
        </div> 
        <div class="form-group">
            <button name="save-as" type="button" class="btn btn-primary">Save As</button>
            <button name="start" type="button" class="btn btn-primary">Start</button>
            <button name="stop" type="submit" class="btn btn-primary">Simpan Laporan</button>
        </div>
    </form>
</body>
</html>