@extends('iframe.layouts.index')

@section('title')
    Laporan Penangguhan Pekerjaan
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('laporan-penangguhan-pekerjaan.store',[$user_id,$laporanKerusakan->id])}}">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label>No</label>
            <input type="text" class="form-control" name="no">
            @if ($errors->has('no'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('no')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label>Ref Laporan Kerusakan No</label>
            <input type="text" class="form-control" disabled value="{{$laporanKerusakan->nomor}}">
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Nama Kapal</label>
            <input type="text" class="form-control" name="no" disabled value="{{$laporanKerusakan->nama_kapal}}">
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
            <label for="exampleSelect1">Bagian Kapal</label>
            <select class="form-control" id="my-select2" name="bagian_kapal">
                <option value="dek">Dek</option>
                <option value="mesin">Mesin</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Pelaksana</label>
            <select class="form-control my-select2" id="exampleSelect1" name="pelaksana">
                @foreach ($listUser as $key => $item)
                    <option value="{{$key}}">{{$item}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Note</label>
            <div class="col-10">
                <textarea class="form-control" name="note" rows="3"></textarea>
            </div>
        </div>
        <hr>        
        <h4>Tambah Pekerjaan</h4>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="form-group">
                <label>Item Pekerjaan</label>
                <input type="text" class="form-control"  id="item_pekerjaan">
            </div>
            <div class="form-group">
                <label>Posisi</label>
                <input type="text" class="form-control"  id="posisi">
            </div>
            <div class="form-group">
                <label>Alasan Penangguhan</label>
                <textarea class="form-control" id="alasan_penangguhan" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Target Perbaikan</label>
                <input type="text" class="form-control"  id="target_perbaikan">
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" id="keterangan" rows="3"></textarea>
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
                            <th>Item Pekerjaan</th>
                            <th>Posisi</th>
                            <th>Alasan Penangguhan</th>
                            <th>Target Perbaikan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data">
                            <td colspan="7"><center>Tidak ada data</center></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if ($errors->has('item_pekerjaan'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('item_pekerjaan')}}</div>
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
                var item_pekerjaan = $("#item_pekerjaan").val();
                var posisi = $("#posisi").val();
                var alasan_penangguhan = $("#alasan_penangguhan").val();
                var target_perbaikan = $("#target_perbaikan").val();
                var keterangan = $("#keterangan").val();

                if (item_pekerjaan==null || item_pekerjaan=='') {
                    //open modal
                    $('#modal-peringatan').modal('toggle');
                    $('#pesan-peringatan').text('Item Pekerjaan tidak boleh kosong');
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
                        ${item_pekerjaan}
                        <input type='hidden' name="item_pekerjaan[]" value="${item_pekerjaan}">
                    </td>
                    <td>
                        ${posisi}
                        <input type='hidden' name="posisi[]" value="${posisi}">
                    </td>
                    <td>
                        ${alasan_penangguhan}
                        <input type='hidden' name="alasan_penangguhan[]" value="${alasan_penangguhan}">
                    </td>
                    <td>
                        ${target_perbaikan}
                        <input type='hidden' name="target_perbaikan[]" value="${target_perbaikan}">
                    </td>
                    <td>
                        ${keterangan}
                        <input type='hidden' name="keterangan[]" value="${keterangan}">
                    </td>
                    <td><button type="button" class="btn btn-sm btn-warning btn-open-modal" data-toggle="modal" data-target="#kt_modal_1">Hapus</button></td>
                </tr>`;
                $(".target-table tbody").append(html);
                $("#item_pekerjaan").val('');
                $("#posisi").val('');
                $("#alasan_penangguhan").val('');
                $("#target_perbaikan").val('');
                $("#keterangan").val('');

                 //open modal
                 $('#modal-peringatan').modal('toggle');
                $('#pesan-peringatan').text('Data berhasil ditambahkan');
            })
            $(".delete-confirm").on('click', function(){
                var index = $(this).parents("div#kt_modal_1").attr("data-index");
                var target_table = $(".target-table tbody");
                target_table.find('tr').eq(index).remove();

                var check_row = target_table.find("td");
                if (check_row.length==0) {
                    //append no data
                    var html = `<tr class="no-data">
                            <td colspan="7"><center>Tidak ada data</center></td>
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