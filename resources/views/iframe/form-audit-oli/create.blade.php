@extends('iframe.layouts.index')

@section('title')
    Form Audit Oli
@endsection

@section('body')

    <form method="post" action="/form-audit-oli/create" enctype="multipart/form-data">
        @csrf
        @php
            $current_timestamp = Carbon\Carbon::now();
        @endphp
        <div class="kt-portlet__body">

            <input id="start" name="start_at" placeholder="volume" type="hidden" class="form-control" value="{{$current_timestamp}}">
            
            <label for="text" class="col-4 col-form-label">No Form</label>
            <div class="form-group">  
                <input id="text" name="no_form" placeholder="No Form" type="text" class="form-control">
                @if ($errors->has('no_form'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('no_form')}}</div>
                @endif
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
                <input type="hidden" name="users_id" value="{{ $user->id }}">
                <input type="text" value='{{ $user->name }}' class='form-control' disabled>
            </div>

            <label for="kapal" class="col-4 col-form-label">Nama Kapal</label>
            <div class="form-group"> 
                <select id="kapal" name="kapal_id" class="custom-select">
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            </div>

            <label for="posisi" class="col-4 col-form-label">Posisi</label>
            <div class="form-group"> 
                <textarea id="posisi" name="posisi" cols="40" rows="5" class="form-control"></textarea>
                @if ($errors->has('posisi'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('posisi')}}</div>
                @endif
            </div>


            <label for="engine_name" class="col-4 col-form-label">Engine Name</label> 
            <div class="form-group">
                <select id="engine_name" name="engine_name" class="custom-select">
                    <option value="MAIN ENGINE PORT">MAIN ENGINE PORT</option>
                    <option value="MAIN ENGINE STBD">MAIN ENGINE STBD</option>
                    <option value="GEAR BOX PORT">GEAR BOX PORT</option>
                    <option value="GEAR BOX STBD<">GEAR BOX STBD</option>
                    <option value="AUX ENGINE 1">AUX ENGINE 1</option>
                    <option value="AUX ENGINE 2">AUX ENGINE 2</option>
                    <option value="AUX ENGINE 3">AUX ENGINE 3</option>
                    <option value="CARGO PUMP P">CARGO PUMP P</option>
                    <option value="CARGO PUMP S">CARGO PUMP S</option>
                    <option value="STEERING GEAR">STEERING GEAR</option>
                    <option value="WINCH HYDRAULIC">WINCH HYDRAULIC</option>
                    <option value="CAPTAIN HYDRAULIC">CAPTAIN HYDRAULIC</option>
                    <option value="CARGO PUMP HYDRAULIC">CARGO PUMP HYDRAULIC</option>
                </select>
            </div>

            <label for="running_hours" class="col-4 col-form-label">Running Hours</label>
            <div class="form-group"> 
                <input id="running_hours" name="running_hours" placeholder="" type="number" class="form-control">
                @if ($errors->has('running_hours'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('running_hours')}}</div>
                @endif
            </div>

            <label for="volume" class="col-4 col-form-label">Sump Tank / Carter (Volume)</label>
            <div class="form-group"> 
                <input id="volume" name="volume" placeholder="" type="number" class="form-control">
                @if ($errors->has('volume'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('volume')}}</div>
                @endif
            </div>

            <label for="real_stock" class="col-4 col-form-label">Real Stock</label>
            <div class="form-group"> 
                <input id="real_stock" name="real_stock" placeholder="" type="number" class="form-control">
                @if ($errors->has('real_stock'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('real_stock')}}</div>
                @endif
            </div>

            <label for="audit_running_hours" class="col-4 col-form-label">Audit Running Hours</label>
            <div class="form-group"> 
                <input id="audit_running_hours" name="audit_running_hours" placeholder="" type="number" class="form-control">
                @if ($errors->has('audit_running_hours'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('audit_running_hours')}}</div>
                @endif
            </div>

            <label for="remark" class="col-4 col-form-label">Remark</label>
            <div class="form-group"> 
                <input id="remark" name="remark" placeholder="" type="text" class="form-control">
                @if ($errors->has('remark'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('remark')}}</div>
                @endif
            </div>

            {{-- <label for="item" class="col-4 col-form-label">Tambah Item</label>
            <div class="form-group"> 
                <div class="input-group">
                    <input id="item" name="item" type="text" class="form-control"> 
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-plus"></i>
                        </div>
                    </div>                  
                </div>
            </div> --}}

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
                @if ($errors->has('lampiran'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('lampiran')}}</div>
                @endif
            </div>

            <label for="catatan" class="col-4 col-form-label">Catatan</label>
            <div class="form-group">
                <textarea id="catatan" name="catatan" cols="40" rows="5" class="form-control"></textarea>
                @if ($errors->has('catatan'))
                   <div class="invalid-feedback" style="display: block">{{$errors->first('catatan')}}</div>
                @endif
            </div>

            <label for="temuan" class="col-4 col-form-label">Temuan</label>
            <div class="form-group"> 
                <textarea id="temuan" name="temuan" cols="40" rows="5" class="form-control"></textarea>
                {{-- <button name="tambah-temuan" type="submit" class="btn btn-primary" style="margin-top: 5px;">+</button> --}}
                @if ($errors->has('temuan'))
                   <div class="invalid-feedback" style="display: block">{{$errors->first('temuan')}}</div>
                @endif                
            </div>

            <div id="signature-pad" class="signature-pad" style="height: 250px;border: none;box-shadow: none;padding: 0">
                <label for="ttd" class="col-4 col-form-label">Kolom TTD Perwira Mesin</label>
                <div class="signature-pad--body">
                    <canvas style="width: 100%;height: 100%;border: solid 1px #ccc;"></canvas>
                </div>
                <textarea id="signature64" name="ttd" style="display: none"></textarea>
                <button type="button" id="clear" class="btn btn-primary btn-sm form-control" data-action="clear">Bersihkan</button>
                @if ($errors->has('ttd'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('ttd')}}</div>
                @endif
            </div>

            <label for="upload" class="col-4 col-form-label">Upload Foto Perwira Mesin</label>
            <div class="form-group"> 
                <div class="input-group">
                    <input id="upload" name="foto_perwira" type="file" class="form-control"> 
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-image"></i>
                        </div>
                    </div>
                </div>
                @if ($errors->has('foto_perwira'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('foto_perwira')}}</div>
                @endif
            </div>
            
            
            <div class="form-group">
                <button name="save-as" type="button" class="btn btn-primary">Save As</button>
                <button type="button" class="btn btn-primary" onclick="get_time_now()">Start</button>
                <button name="stop" type="submit" class="btn btn-primary" id='submitBtn'>Simpan Laporan</button>
            </div>

        </div>
    </form>
    

@endsection


@section('script')
    
    <script>
        function get_time_now(){
            alert("waktu pengisian form telah dimulai");
            let ms = Math.floor(Date.now() / 1000);
            document.getElementById("start_at").value = ms;
        }
    </script>

        @if (session()->has('success'))
            <script>
                alert("{{ session()->get('success') }}");
            </script>
        @endif
@endsection
