@extends('iframe.layouts.index')

@section('title')
    Surat Pengiriman dan Tanda Terima
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('laporan-kerusakan.update',[$user_id,$data->id])}}">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label>No</label>
            <input type="text" class="form-control" name="no" value="{{$data->nomor}}">
            @if ($errors->has('no'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('no')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Nama Kapal</label>
            <select class="form-control" id="exampleSelect1" name="nama_kapal">
                @foreach ($kapal as $item)
                    <option value="{{$item->name}}" {{$data->nama_kapal==$item->name?'selected':''}}>{{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Tanggal</label>
            <div class="col-10">
                <input class="form-control" type="date" name="tanggal" value="{{$data->tanggal->format('Y-m-d')}}" id="example-date-input">
                @if ($errors->has('tanggal'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal')}}</div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label>Bagian Kapal</label>
            <input type="text" class="form-control" name="bagian_kapal" value="{{$data->bagian_kapal}}">
            @if ($errors->has('bagian_kapal'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('bagian_kapal')}}</div>
            @endif
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Note</label>
            <div class="col-10">
                <textarea class="form-control" name="note" rows="3">{{$data->note}}</textarea>
            </div>
        </div>
        <hr>        
        <h4>Tambah Kerusakan</h4>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="form-group">
                <label>Posisi di Kapal</label>
                <textarea class="form-control" id="posisi_di_kapal" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Jumlah Kerusakan</label>
                <input type="number" class="form-control"  id="jumlah_kerusakan">
            </div>
            <div class="form-group">
                <label>Uraian Kerusakan</label>
                <textarea class="form-control" id="uraian_kerusakan" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Analisis Kerusakan</label>
                <textarea class="form-control" id="analisis_kerusakan" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Usaha Penanggulangan</label>
                <textarea class="form-control" id="usaha_penanggulangan" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Hal Yang Perlu Ditindak Lanjuti</label>
                <textarea class="form-control" id="hal_yang_perlu_ditindak_lanjuti" rows="3"></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-primary" id="tambah_data" style="margin-bottom: 20px;">Tambah</button>
        </div>
        <h4>Kerusakan yang ditambahkan</h4>
        <small>List kerusakan dibawah ini yang akan disimpan</small>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="kt-section__content wrapper-scroll">
                <table class="table table-bordered target-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Posisi di Kapal</th>
                            <th>Jumlah Kerusakan</th>
                            <th>Uraian Kerusakan</th>
                            <th>Analisis Kerusakan</th>
                            <th>Usaha Penanggulangan</th>
                            <th>Hal Yang Perlu Ditindak Lanjuti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                            @forelse ($data->details as $item)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>
                                        {{$item->posisi_di_kapal}}
                                        <input type='hidden' name="posisi_di_kapal[]" value="{{$item->posisi_di_kapal}}">
                                    </td>
                                    <td>
                                        {{$item->jumlah_kerusakan}}
                                        <input type='hidden' name="jumlah_kerusakan[]" value="{{$item->jumlah_kerusakan}}">
                                    </td>
                                    <td>
                                        {{$item->uraian_kerusakan}}
                                        <input type='hidden' name="uraian_kerusakan[]" value="{{$item->uraian_kerusakan}}">
                                    </td>
                                    <td>
                                        {{$item->analisis_kerusakan}}
                                        <input type='hidden' name="analisis_kerusakan[]" value="{{$item->analisis_kerusakan}}">
                                    </td>
                                    <td>
                                        {{$item->usaha_penanggulangan}}
                                        <input type='hidden' name="usaha_penanggulangan[]" value="{{$item->usaha_penanggulangan}}">
                                    </td>
                                    <td>
                                        {{$item->hal_yang_perlu_ditindak_lanjuti}}
                                        <input type='hidden' name="hal_yang_perlu_ditindak_lanjuti[]" value="{{$item->hal_yang_perlu_ditindak_lanjuti}}">
                                    </td>
                                    <td><button type="button" class="btn btn-sm btn-warning btn-open-modal" data-toggle="modal" data-target="#kt_modal_1">Hapus</button></td>
                                </tr>
                            @empty
                            <tr class="no-data">    
                                <td colspan="8"><center>Tidak ada data</center></td>
                            </tr>
                            @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($errors->has('posisi_di_kapal'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('posisi_di_kapal')}}</div>
        @endif
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</form>
<!--begin::Modal-->
<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-index="0">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Data akan dihapus.Anda yakin?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="button" class="btn btn-primary delete-confirm">Ya</button>
            </div>
        </div>
    </div>
</div>

<!--end::Modal-->

<!--begin::Modal Peringatan-->
<div class="modal fade" id="modal-peringatan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-index="0">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p id="pesan-peringatan"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<!--end::Modal-->
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            // var canvas = document.getElementById("canvas-selector");
            // var ctx = canvas.getContext("2d");
            // var ctx = canvas.getContext("2d");

            // var image = new Image();
            // image.onload = function() {
            // ctx.drawImage(image, 0, 0);
            // };
            // image.src = "{{$data->base64}}";

            $(document).on('click','.btn-open-modal', function(){
                var index = $(this).parents("tr").index();
                $("#kt_modal_1").attr("data-index",index);
            })
            $("#tambah_data").on('click', function(){
                var posisi_di_kapal = $("#posisi_di_kapal").val();
                var jumlah_kerusakan = $("#jumlah_kerusakan").val();
                var uraian_kerusakan = $("#uraian_kerusakan").val();
                var analisis_kerusakan = $("#analisis_kerusakan").val();
                var usaha_penanggulangan = $("#usaha_penanggulangan").val();
                var hal_yang_perlu_ditindak_lanjuti = $("#hal_yang_perlu_ditindak_lanjuti").val();

                if (posisi_di_kapal==null || posisi_di_kapal=='') {
                    //open modal
                    $('#modal-peringatan').modal('toggle');
                    $('#pesan-peringatan').text('Uraian tidak boleh kosong');
                    return;
                }

                var target_table = $(".target-table tbody");

                // check no-data
                var check_initial = target_table.find(".no-data");
                if (check_initial.length > 0) {
                    target_table.empty();
                }

                // get last number
                var number = target_table.find("tr:last th:first").text();
                if (number !="") {
                    number = parseInt(number)+1;
                }else{
                    number = 1;
                }
                
                var html = `<tr>
                    <th scope="row">${number}</th>
                    <td>
                        ${posisi_di_kapal}
                        <input type='hidden' name="posisi_di_kapal[]" value="${posisi_di_kapal}">
                    </td>
                    <td>
                        ${jumlah_kerusakan}
                        <input type='hidden' name="jumlah_kerusakan[]" value="${jumlah_kerusakan}">
                    </td>
                    <td>
                        ${uraian_kerusakan}
                        <input type='hidden' name="uraian_kerusakan[]" value="${uraian_kerusakan}">
                    </td>
                    <td>
                        ${analisis_kerusakan}
                        <input type='hidden' name="analisis_kerusakan[]" value="${analisis_kerusakan}">
                    </td>
                    <td>
                        ${usaha_penanggulangan}
                        <input type='hidden' name="usaha_penanggulangan[]" value="${usaha_penanggulangan}">
                    </td>
                    <td>
                        ${hal_yang_perlu_ditindak_lanjuti}
                        <input type='hidden' name="hal_yang_perlu_ditindak_lanjuti[]" value="${hal_yang_perlu_ditindak_lanjuti}">
                    </td>
                    <td><button type="button" class="btn btn-sm btn-warning btn-open-modal" data-toggle="modal" data-target="#kt_modal_1">Hapus</button></td>
                </tr>`;
                $(".target-table tbody").append(html);
                $("#posisi_di_kapal").val('');
                $("#jumlah_kerusakan").val('');
                $("#uraian_kerusakan").val('');
                $("#analisis_kerusakan").val('');
                $("#usaha_penanggulangan").val('');
                $("#hal_yang_perlu_ditindak_lanjuti").val('');

                 //open modal
                 $('#modal-peringatan').modal('toggle');
                $('#pesan-peringatan').text('Data berhasil ditambahkan');
            })
            $(document).on('click','.delete-confirm', function(){
                var index = $(this).parents("div#kt_modal_1").attr("data-index");
                var target_table = $(".target-table tbody");
                target_table.find('tr').eq(index).remove();

                var check_row = target_table.find("td");
                if (check_row.length==0) {
                    //append no data
                    var html = `<tr class="no-data">
                            <td colspan="8"><center>Tidak ada data</center></td>
                        </tr>`;
                    target_table.append(html);
                }else{
                    // re order number
                    var list_number = target_table.find('th');
                    list_number.map(function(i,val){
                        $(val).text(i+1);
                    })
                }
                $("#kt_modal_1").modal('toggle'); 
            })
        })
    </script>
@endsection