@extends('iframe.layouts.index')

@section('title')
    Sounding Pagi Perwir
@endsection

@section('body')
<style>
    table {
        table-layout: fixed;
        word-wrap: break-word;
    }
    span.select2.select2-container.select2-container{
        width: 220px !important;
    }
</style>
<form class="kt-form" id="form-store" method="POST" action="{{route('sounding-pagi-perwira.store',$user_id)}}">
    @csrf
    <div class="kt-portlet__body">
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
            <label for="exampleSelect1">Armada</label>
            <select class="form-control" id="exampleSelect1" name="armada">
                @foreach ($kapal as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('armada'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('armada')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Bagian</label>
            <select class="form-control" id="exampleSelect1" name="bagian">
                <option value="D">Deck</option>
                <option value="O">Operasional</option>
                <option value="E">Engine</option>
            </select>
            @if ($errors->has('bagian'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('bagian')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="exampleSelect1">Lokasi</label>
            <input type="text" name="lokasi" class="form-control">
            @if ($errors->has('lokasi'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('lokasi')}}</div>
            @endif
        </div>
        <h4>Srounding Table</h4>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="kt-section__content wrapper-scroll">
            <table class="table" style="display: block;overflow-x: auto;white-space: nowrap;width:1500px;">
                <thead>
                    <tr>
                        <th>Kompartment</th>
                        <th>Produk</th>
                        <th>Awal CM</th>
                        <th>Awal M2</th>
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
                        <td>S 1</td>
                        <td>
                            <select class="form-control my-select2" id="s_1_produk" name="s_1_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="s_1_awal_cm"></td>
                        <td><input type="text" class="form-control" name="s_1_awal_m2"></td>
                        <td><input type="text" class="form-control" name="s_1_1x"></td>
                        <td><input type="text" class="form-control" name="s_1_2x"></td>
                        <td><input type="text" class="form-control" name="s_1_3x"></td>
                        <td><input type="text" class="form-control" name="s_1_rata_rata"></td>
                        <td><input type="text" class="form-control" name="s_1_m2"></td>
                        <td><input type="text" class="form-control" name="s_1_catatan"></td>
                    </tr>
                    <tr>
                        <td>S 2</td>
                        <td>
                            <select class="form-control my-select2" id="s_2_produk" name="s_2_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="s_2_awal_cm"></td>
                        <td><input type="text" class="form-control" name="s_2_awal_m2"></td>
                        <td><input type="text" class="form-control" name="s_2_1x"></td>
                        <td><input type="text" class="form-control" name="s_2_2x"></td>
                        <td><input type="text" class="form-control" name="s_2_3x"></td>
                        <td><input type="text" class="form-control" name="s_2_rata_rata"></td>
                        <td><input type="text" class="form-control" name="s_2_m2"></td>
                        <td><input type="text" class="form-control" name="s_2_catatan"></td>
                    </tr>
                    <tr>
                        <td>S 3</td>
                        <td>
                            <select class="form-control my-select2" id="s_3_produk" name="s_3_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="s_3_awal_cm"></td>
                        <td><input type="text" class="form-control" name="s_3_awal_m2"></td>
                        <td><input type="text" class="form-control" name="s_3_1x"></td>
                        <td><input type="text" class="form-control" name="s_3_2x"></td>
                        <td><input type="text" class="form-control" name="s_3_3x"></td>
                        <td><input type="text" class="form-control" name="s_3_rata_rata"></td>
                        <td><input type="text" class="form-control" name="s_3_m2"></td>
                        <td><input type="text" class="form-control" name="s_3_catatan"></td>
                    </tr>
                    <tr>
                        <td>S 4</td>
                        <td>
                            <select class="form-control my-select2" id="s_4_produk" name="s_4_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="s_4_awal_cm"></td>
                        <td><input type="text" class="form-control" name="s_4_awal_m2"></td>
                        <td><input type="text" class="form-control" name="s_4_1x"></td>
                        <td><input type="text" class="form-control" name="s_4_2x"></td>
                        <td><input type="text" class="form-control" name="s_4_3x"></td>
                        <td><input type="text" class="form-control" name="s_4_rata_rata"></td>
                        <td><input type="text" class="form-control" name="s_4_m2"></td>
                        <td><input type="text" class="form-control" name="s_4_catatan"></td>
                    </tr>
                    <tr>
                        <td>S 5</td>
                        <td>
                            <select class="form-control my-select2" id="s_5_produk" name="s_5_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="s_5_awal_cm"></td>
                        <td><input type="text" class="form-control" name="s_5_awal_m2"></td>
                        <td><input type="text" class="form-control" name="s_5_1x"></td>
                        <td><input type="text" class="form-control" name="s_5_2x"></td>
                        <td><input type="text" class="form-control" name="s_5_3x"></td>
                        <td><input type="text" class="form-control" name="s_5_rata_rata"></td>
                        <td><input type="text" class="form-control" name="s_5_m2"></td>
                        <td><input type="text" class="form-control" name="s_5_catatan"></td>
                    </tr>
                    <tr>
                        <td>S 6</td>
                        <td>
                            <select class="form-control my-select2" id="s_6_produk" name="s_6_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="s_6_awal_cm"></td>
                        <td><input type="text" class="form-control" name="s_6_awal_m2"></td>
                        <td><input type="text" class="form-control" name="s_6_1x"></td>
                        <td><input type="text" class="form-control" name="s_6_2x"></td>
                        <td><input type="text" class="form-control" name="s_6_3x"></td>
                        <td><input type="text" class="form-control" name="s_6_rata_rata"></td>
                        <td><input type="text" class="form-control" name="s_6_m2"></td>
                        <td><input type="text" class="form-control" name="s_6_catatan"></td>
                    </tr>
                    <tr>
                        <td>P 1</td>
                        <td>
                            <select class="form-control my-select2" id="p_1_produk" name="p_1_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="p_1_awal_cm"></td>
                        <td><input type="text" class="form-control" name="p_1_awal_m2"></td>
                        <td><input type="text" class="form-control" name="p_1_1x"></td>
                        <td><input type="text" class="form-control" name="p_1_2x"></td>
                        <td><input type="text" class="form-control" name="p_1_3x"></td>
                        <td><input type="text" class="form-control" name="p_1_rata_rata"></td>
                        <td><input type="text" class="form-control" name="p_1_m2"></td>
                        <td><input type="text" class="form-control" name="p_1_catatan"></td>
                    </tr>
                    <tr>
                        <td>P 2</td>
                        <td>
                            <select class="form-control my-select2" id="p_2_produk" name="p_2_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="p_2_awal_cm"></td>
                        <td><input type="text" class="form-control" name="p_2_awal_m2"></td>
                        <td><input type="text" class="form-control" name="p_2_1x"></td>
                        <td><input type="text" class="form-control" name="p_2_2x"></td>
                        <td><input type="text" class="form-control" name="p_2_3x"></td>
                        <td><input type="text" class="form-control" name="p_2_rata_rata"></td>
                        <td><input type="text" class="form-control" name="p_2_m2"></td>
                        <td><input type="text" class="form-control" name="p_2_catatan"></td>
                    </tr>
                    <tr>
                        <td>P 3</td>
                        <td>
                            <select class="form-control my-select2" id="p_3_produk" name="p_3_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="p_3_awal_cm"></td>
                        <td><input type="text" class="form-control" name="p_3_awal_m2"></td>
                        <td><input type="text" class="form-control" name="p_3_1x"></td>
                        <td><input type="text" class="form-control" name="p_3_2x"></td>
                        <td><input type="text" class="form-control" name="p_3_3x"></td>
                        <td><input type="text" class="form-control" name="p_3_rata_rata"></td>
                        <td><input type="text" class="form-control" name="p_3_m2"></td>
                        <td><input type="text" class="form-control" name="p_3_catatan"></td>
                    </tr>
                    <tr>
                        <td>P 4</td>
                        <td>
                            <select class="form-control my-select2" id="p_4_produk" name="p_4_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="p_4_awal_cm"></td>
                        <td><input type="text" class="form-control" name="p_4_awal_m2"></td>
                        <td><input type="text" class="form-control" name="p_4_1x"></td>
                        <td><input type="text" class="form-control" name="p_4_2x"></td>
                        <td><input type="text" class="form-control" name="p_4_3x"></td>
                        <td><input type="text" class="form-control" name="p_4_rata_rata"></td>
                        <td><input type="text" class="form-control" name="p_4_m2"></td>
                        <td><input type="text" class="form-control" name="p_4_catatan"></td>
                    </tr>
                    <tr>
                        <td>P 5</td>
                        <td>
                            <select class="form-control my-select2" id="p_5_produk" name="p_5_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="p_5_awal_cm"></td>
                        <td><input type="text" class="form-control" name="p_5_awal_m2"></td>
                        <td><input type="text" class="form-control" name="p_5_1x"></td>
                        <td><input type="text" class="form-control" name="p_5_2x"></td>
                        <td><input type="text" class="form-control" name="p_5_3x"></td>
                        <td><input type="text" class="form-control" name="p_5_rata_rata"></td>
                        <td><input type="text" class="form-control" name="p_5_m2"></td>
                        <td><input type="text" class="form-control" name="p_5_catatan"></td>
                    </tr>
                    <tr>
                        <td>P 6</td>
                        <td>
                            <select class="form-control my-select2" id="p_6_produk" name="p_6_produk">
                                @foreach ($barang as $item)
                                    <option value="">Pilih Barang</option>
                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="p_6_awal_cm"></td>
                        <td><input type="text" class="form-control" name="p_6_awal_m2"></td>
                        <td><input type="text" class="form-control" name="p_6_1x"></td>
                        <td><input type="text" class="form-control" name="p_6_2x"></td>
                        <td><input type="text" class="form-control" name="p_6_3x"></td>
                        <td><input type="text" class="form-control" name="p_6_rata_rata"></td>
                        <td><input type="text" class="form-control" name="p_6_m2"></td>
                        <td><input type="text" class="form-control" name="p_6_catatan"></td>
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