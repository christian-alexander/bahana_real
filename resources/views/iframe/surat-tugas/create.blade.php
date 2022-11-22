@extends('iframe.layouts.index')

@section('title')
    Form Surat Tugas
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('surat-tugas.store',$user_id)}}" enctype="multipart/form-data">
    @csrf
    <div class="kt-portlet__body">
        {{-- <div class="form-group">
            <label>No Permintaan Dana</label>
            <input type="text" class="form-control" name="no_internal_memo" value="">
        </div> --}}
        <div class="form-group">
            <label for="exampleSelect1">Nama PT</label>
            <select class="form-control kt-select2" id="kt_select2_1" name="subcompany">
                @foreach ($subCompany as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('subcompany'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('subcompany')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Nama Bagian</label>
            <select class="form-control kt-select2" id="kt_select2_3" name="department">
                @foreach ($department as $item)
                    <option value="{{$item->id}}">{{$item->team_name}}</option>
                @endforeach
            </select>
            @if ($errors->has('department'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('department')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Nama Pemberi Tugas</label>
            <select class="form-control kt-select2" id="kt_select2_2" name="user_id">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('user_id'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('user_id')}}</div>
            @endif
        </div>
        {{-- <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Tanggal</label>
            <div class="col-10">
                <input class="form-control" type="date" name="tanggal" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" id="example-date-input">
                @if ($errors->has('tanggal'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal')}}</div>
                @endif
            </div>
        </div> --}}
        <div class="form-group">
            <label>Jabatan</label>
            <input type="text" class="form-control" name="jabatan_satu" value="">
          </div>
          <div class="form-group">
            <label>NIK</label>
            <input type="text" class="form-control" name="nik_satu" value="">
          </div>
          <div class="form-group">
            <label for="exampleSelect1">Nama yang Bertugas</label>
            <select class="form-control kt-select2" id="kt_select2_4" name="nama_bertugas">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('nama_bertugas'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('nama_bertugas')}}</div>
            @endif
        </div>
          <div class="form-group">
            <label>Jabatan</label>
            <input type="text" class="form-control" name="jabatan_dua" value="">
          </div>
          <div class="form-group">
            <label>NIK</label>
            <input type="text" class="form-control" name="nik_dua" value="">
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Rute Awal</label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
              <input class="form-control" type="text" name="rute_awal" id="kt_datepicker_1" placeholder="" />
            </div>
            <label class="col-form-label col-lg-3 col-sm-12 m-right-80">Rute Akhir</label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
              <input class="form-control" type="text" name="rute_akhir" id="kt_datepicker_1_validate" placeholder="" />
            </div>
          </div>
        <div class="form-group">
            <label>Keperluan</label>
            <textarea class="form-control" name="keperluan" rows="3"></textarea>
            @if ($errors->has('keperluan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('keperluan')}}</div>
            @endif
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Tanggal Mulai</label>
            <div class=" col-lg-3 col-md-9 col-sm-12">
              <input class="form-control" type="text" name="tanggal_mulai" id="kt_datepicker_1" placeholder="Input Date" />
            </div>
            <label class="col-form-label col-lg-3 col-sm-10 pl-5">Tanggal Selesai</label><br>
            <div class=" col-lg-3 col-md-9 col-sm-12">
              <input class="form-control" type="text" name="tanggal_selesai" id="kt_datepicker_1_validate" placeholder="Input Date" />
            </div>
          </div>
          <div class="form-group">
            <label>Estimasi Biaya</label>
            <input type="text" class="form-control" name="estimasi_biaya" value="">
          </div>
        {{-- <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Nominal</label>
            <div class="col-10">
                <input class="form-control" type="text" name="nominal" id="example-date-input">
                @if ($errors->has('nominal'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('nominal')}}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Terbilang</label>
            <div class="col-10">
                <input class="form-control" type="text" name="terbilang" id="example-date-input">
                @if ($errors->has('terbilang'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('terbilang')}}</div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label>Unsur PPH</label>
            <select class="form-control kt-select2"  id="kt_select2" name="unsur_pph">
                <option value="pph-4">PPH 4 ayat 2</option>
                <option value="pph-23">PPH 23</option>
                <option value="pph-15">PPH 15</option>
                <option value="non-pph">NON-PPH</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nominal PPH</label>
            <input type="text" class="form-control" name="nominal_pph" value="">
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Approval Pajak</label>
            <select class="form-control kt-select2"  id="kt_select2_5" name="approval_pajak">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('approval_pajak'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('approval_pajak')}}</div>
            @endif
        </div>  --}}
        {{-- <div class="form-group">
            <label>Peminta Dana</label>
            <select class="form-control" id="exampleSelect1" name="peminta_dana">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group">
            <label>ACC Atasan 1</label>
            <select class="form-control kt-select2" name="acc_atasan_1">
                <option name="atasan1" value="{{ $atasan1->id }}">{{$atasan1->name}}</option>
                <option name="atasan2" value="{{ $atasan2->id }}">{{$atasan2->name}}</option>
            </select>
        </div>
        <div class="form-group">
            <label>ACC Atasan 2</label>
            <select class="form-control kt-select2" name="acc_atasan_2">
                <option name="atasan2" value="{{ $atasan2->id }}">{{$atasan2->name}}</option>
                <option name="atasan1" value="{{ $atasan1->id }}">{{$atasan1->name}}</option>
            </select>
        </div>
        {{-- <div class="form-group">
            <label>Mengetahui</label>
            <select class="form-control kt-select2" name="mengetahui">
                <option name="atasan1" value="{{ $atasan1->id }}">{{$atasan1->name}}</option>
                <option name="atasan2" value="{{ $atasan2->id }}">{{$atasan2->name}}</option>
            </select>
        </div>
        <div class="form-group">
            <label>Disetujui</label>
            <input type="text" class="form-control" name="disetujui" value="">
        </div>  --}}
        {{-- <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="note" rows="3"></textarea>
            @if ($errors->has('note'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('note')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label>Attachment</label>
              <div class="custom-file">
                 <input type="file" class="custom-file-input" name="file"/>
                 <label class="custom-file-label" for="customFile">Pilih attachment</label>
              </div>
        </div> --}}
        {{-- <div class="form-group">
            <label for="exampleSelect1">CC ke</label>
            <select class="form-control my-select2" name="cc[]" multiple="multiple">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div> --}}
        <label>Tanda tangan</label>
        <div id="signature-pad" class="signature-pad" style="height: 250px;border: none;box-shadow: none;padding: 0">
            <div class="signature-pad--body">
                <canvas style="width: 100%;height: 100%;border: solid 1px #ccc;"></canvas>
            </div>
            <textarea id="signature64" name="tanda_tangan" style="display: none"></textarea>
            <button type="button" id="clear" class="btn btn-primary btn-sm form-control" data-action="clear">Bersihkan</button>
            @if ($errors->has('tanda_tangan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('tanda_tangan')}}</div>
            @endif
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</form>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            // code
        })
    </script>
@endsection
