@extends('iframe.layouts.index')

@section('title')
    Surat Pengiriman dan Tanda Terima
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('sptt.update',[$user_id,$data->id])}}">
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
            <label for="exampleSelect1">Type</label>
            <select class="form-control" id="exampleSelect1" name="type">
                <option value="mt" {{$data->mt_spob=='mt'?'selected':''}}>MT</option>
                <option value="spob" {{$data->mt_spob=='spob'?'selected':''}}>SPOB</option>
            </select>
        </div>
        <div class="form-group">
            <label>Posisi Kapal</label>
            <input type="text" class="form-control" name="posisi_kapal" value="{{$data->posisi_kapal}}">
            @if ($errors->has('posisi_kapal'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('posisi_kapal')}}</div>
            @endif
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
            <label for="exampleSelect1">Akan diterima oleh</label>
            <select class="form-control my-select2" name="penerima">
                @foreach ($listUser as $key => $item)
                    <option value="{{$key}}" {{$data->penerima==$key?'selected':''}}>{{$item}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Note</label>
            <div class="col-10">
                <textarea class="form-control" name="note" rows="3">{{$data->note}}</textarea>
            </div>
        </div>
        <hr>        
        <h4>Tambah Uraian</h4>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="form-group">
                <label>Uraian</label>
                <textarea class="form-control" id="uraian" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <input type="text" class="form-control"  id="satuan">
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" class="form-control"  id="jumlah">
            </div>
            <button type="button" class="btn btn-sm btn-primary" id="tambah_data" style="margin-bottom: 20px;">Tambah</button>
        </div>
        <h4>Barang yang ditambahkan</h4>
        <small>List barang dibawah ini yang akan disimpan</small>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="kt-section__content wrapper-scroll">
                <table class="table table-bordered target-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Uraian</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                            @forelse ($data->details as $item)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>
                                        {{$item->uraian}}
                                        <input type='hidden' name="uraian[]" value="{{$item->uraian}}">
                                    </td>
                                    <td>
                                        {{$item->satuan}}
                                        <input type='hidden' name="satuan[]" value="{{$item->satuan}}">
                                    </td>
                                    <td>
                                        {{$item->jumlah}}
                                        <input type='hidden' name="jumlah[]" value="{{$item->jumlah}}">
                                    </td>
                                    <td><button type="button" class="btn btn-sm btn-warning btn-open-modal" data-toggle="modal" data-target="#kt_modal_1">Hapus</button></td>
                                </tr>
                            @empty
                            <tr class="no-data">    
                                <td colspan="5"><center>Tidak ada data</center></td>
                            </tr>
                            @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($errors->has('uraian_disimpan'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('uraian_disimpan')}}</div>
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

            $(document).on('click','.btn-open-modal', function(){
                var index = $(this).parents("tr").index();
                $("#kt_modal_1").attr("data-index",index);
            })
            $("#tambah_data").on('click', function(){
                var uraian = $("#uraian").val();
                var satuan = $("#satuan").val();
                var jumlah = $("#jumlah").val();

                if (uraian==null || uraian=='') {
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
                        ${uraian}
                        <input type='hidden' name="uraian[]" value="${uraian}">
                    </td>
                    <td>
                        ${satuan}
                        <input type='hidden' name="satuan[]" value="${satuan}">
                    </td>
                    <td>
                        ${jumlah}
                        <input type='hidden' name="jumlah[]" value="${jumlah}">
                    </td>
                    <td><button type="button" class="btn btn-sm btn-warning btn-open-modal" data-toggle="modal" data-target="#kt_modal_1">Hapus</button></td>
                </tr>`;
                $(".target-table tbody").append(html);
                $("#uraian").val('');
                $("#satuan").val('');
                $("#jumlah").val('');

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
                            <td colspan="5"><center>Tidak ada data</center></td>
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