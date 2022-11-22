@extends('iframe.layouts.index')

@section('title')
    Internal Memo
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('sounding-bunker-pemakaian-bbm.store',$user_id)}}">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label for="exampleSelect1">Kapal</label>
            <select class="form-control" id="exampleSelect1" name="kapal">
                @foreach ($kapal as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('anak_perusahaan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('anak_perusahaan')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Bagian</label>
            <select class="form-control" id="exampleSelect1" name="bagian">
                <option value="D">Deck</option>
                <option value="O">Operasional</option>
                <option value="E">Engine</option>
            </select>
            @if ($errors->has('anak_perusahaan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('anak_perusahaan')}}</div>
            @endif
        </div>
        
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Tanggal</label>
            <div class="col-10">
                <input class="form-control" type="date" name="tanggal" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" id="example-date-input">
                @if ($errors->has('tanggal'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal')}}</div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="exampleSelect1">Jam</label>
            <input type="time" name="jam" class="form-control">
            @if ($errors->has('jam'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('jam')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="exampleSelect1">ROB (Awal)</label>
            <input type="text" name="rob_awal" class="form-control">
            @if ($errors->has('rob_awal'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('rob_awal')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">ROB (Akhir)</label>
            <input type="text" name="rob_akhir" class="form-control">
            @if ($errors->has('rob_akhir'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('rob_akhir')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="exampleSelect1">Port Lokasi Sounding</label>
            <input type="text" name="port_lokasi" class="form-control">
            @if ($errors->has('port_lokasi'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('port_lokasi')}}</div>
            @endif
        </div>
        <h4>Pemakaian</h4>
        <table class="table">
            <tr>
                <td>ME <input type="text" class="form-control" name="pemakaian_me"></td>
                <td>TEST <input type="text" class="form-control" name="pemakaian_test"></td>
            </tr>
        </table>

        <h4>Srounding Table</h4>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="kt-section__content wrapper-scroll">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kompartment</th>
                        <th>CM 1x</th>
                        <th>CM 2x</th>
                        <th>CM 3x</th>
                        <th>CM rata-rata</th>
                        <th>M2</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>S 2</td>
                        <td><input type="text" class="form-control" name="s_2_1x"></td>
                        <td><input type="text" class="form-control" name="s_2_2x"></td>
                        <td><input type="text" class="form-control" name="s_2_3x"></td>
                        <td><input type="text" class="form-control" name="s_2_rata_rata"></td>
                        <td><input type="text" class="form-control" name="s_2_m2"></td>
                        <td><input type="text" class="form-control" name="s_2_catatan"></td>
                    </tr>
                    <tr>
                        <td>S 3</td>
                        <td><input type="text" class="form-control" name="s_3_1x"></td>
                        <td><input type="text" class="form-control" name="s_3_2x"></td>
                        <td><input type="text" class="form-control" name="s_3_3x"></td>
                        <td><input type="text" class="form-control" name="s_3_rata_rata"></td>
                        <td><input type="text" class="form-control" name="s_3_m2"></td>
                        <td><input type="text" class="form-control" name="s_3_catatan"></td>
                    </tr>
                    <tr>
                        <td>C 1</td>
                        <td><input type="text" class="form-control" name="c_1_1x"></td>
                        <td><input type="text" class="form-control" name="c_1_2x"></td>
                        <td><input type="text" class="form-control" name="c_1_3x"></td>
                        <td><input type="text" class="form-control" name="c_1_rata_rata"></td>
                        <td><input type="text" class="form-control" name="c_1_m2"></td>
                        <td><input type="text" class="form-control" name="c_1_catatan"></td>
                    </tr>
                    <tr>
                        <td>P 2</td>
                        <td><input type="text" class="form-control" name="p_2_1x"></td>
                        <td><input type="text" class="form-control" name="p_2_2x"></td>
                        <td><input type="text" class="form-control" name="p_2_3x"></td>
                        <td><input type="text" class="form-control" name="p_2_rata_rata"></td>
                        <td><input type="text" class="form-control" name="p_2_m2"></td>
                        <td><input type="text" class="form-control" name="p_2_catatan"></td>
                    </tr>
                    <tr>
                        <td>P 3</td>
                        <td><input type="text" class="form-control" name="p_3_1x"></td>
                        <td><input type="text" class="form-control" name="p_3_2x"></td>
                        <td><input type="text" class="form-control" name="p_3_3x"></td>
                        <td><input type="text" class="form-control" name="p_3_rata_rata"></td>
                        <td><input type="text" class="form-control" name="p_3_m2"></td>
                        <td><input type="text" class="form-control" name="p_3_catatan"></td>
                    </tr>
                    <tr>
                        <td>H ME</td>
                        <td><input type="text" class="form-control" name="h_me_1x"></td>
                        <td><input type="text" class="form-control" name="h_me_2x"></td>
                        <td><input type="text" class="form-control" name="h_me_3x"></td>
                        <td><input type="text" class="form-control" name="h_me_rata_rata"></td>
                        <td><input type="text" class="form-control" name="h_me_m2"></td>
                        <td><input type="text" class="form-control" name="h_me_catatan"></td>
                    </tr>
                    <tr>
                        <td>H AE</td>
                        <td><input type="text" class="form-control" name="h_ae_1x"></td>
                        <td><input type="text" class="form-control" name="h_ae_2x"></td>
                        <td><input type="text" class="form-control" name="h_ae_3x"></td>
                        <td><input type="text" class="form-control" name="h_ae_rata_rata"></td>
                        <td><input type="text" class="form-control" name="h_ae_m2"></td>
                        <td><input type="text" class="form-control" name="h_ae_catatan"></td>
                    </tr>
                    <tr>
                        <td>Setling</td>
                        <td><input type="text" class="form-control" name="setling_1x"></td>
                        <td><input type="text" class="form-control" name="setling_2x"></td>
                        <td><input type="text" class="form-control" name="setling_3x"></td>
                        <td><input type="text" class="form-control" name="setling_rata_rata"></td>
                        <td><input type="text" class="form-control" name="setling_m2"></td>
                        <td><input type="text" class="form-control" name="setling_catatan"></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleSelect1">CC ke</label>
            <select class="form-control my-select2" name="cc[]" multiple="multiple">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>
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