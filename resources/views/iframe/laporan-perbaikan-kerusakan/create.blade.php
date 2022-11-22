@extends('iframe.layouts.index')

@section('title')
    Laporan Perbaikan Kerusakan
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('laporan-perbaikan-kerusakan.store',[$user_id,$laporanKerusakan->id])}}">
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
            <label for="exampleSelect1">Pembuat</label>
            <select class="form-control my-select2" id="exampleSelect1" name="pembuat">
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
        <h4>Tambah Perbaikan</h4>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="form-group">
                <label>Nama Bagian dan Posisi di Kapal</label>
                <input type="text" class="form-control"  id="nama_bagian_dan_posisi_di_kapal">
            </div>
            <div class="form-group">
                <label>Uraian Kerja Perbaikan</label>
                <input type="text" class="form-control"  id="uraian_kerja_perbaikan">
            </div>
            <div class="form-group">
                <label>Suku Cadang</label>
                <input type="text" class="form-control"  id="suku_cadang">
            </div>
            <div class="form-group">
                <label>Jumlah Satuan</label>
                <input type="number" class="form-control"  id="jumlah_satuan">
            </div>
            <div class="form-group">
                <label>Nomor Bagian Suku Cadang</label>
                <input type="text" class="form-control"  id="nomor_bagian_suku_cadang">
            </div>
            <div class="form-group">
                <label>Hasil Perbaikan</label>
                <input type="text" class="form-control"  id="hasil_perbaikan">
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
                            <th>Nama Bagian dan Posisi di Kapal</th>
                            <th>Uraian Kerja Perbaikan</th>
                            <th>Suku Cadang</th>
                            <th>Jumlah Satuan</th>
                            <th>Nomor Bagian Suku Cadang</th>
                            <th>Hasil Perbaikan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data">
                            <td colspan="8"><center>Tidak ada data</center></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if ($errors->has('nama_bagian_dan_posisi_di_kapal'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('nama_bagian_dan_posisi_di_kapal')}}</div>
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
                var nama_bagian_dan_posisi_di_kapal = $("#nama_bagian_dan_posisi_di_kapal").val();
                var uraian_kerja_perbaikan = $("#uraian_kerja_perbaikan").val();
                var suku_cadang = $("#suku_cadang").val();
                var jumlah_satuan = $("#jumlah_satuan").val();
                var nomor_bagian_suku_cadang = $("#nomor_bagian_suku_cadang").val();
                var hasil_perbaikan = $("#hasil_perbaikan").val();

                if (nama_bagian_dan_posisi_di_kapal==null || nama_bagian_dan_posisi_di_kapal=='') {
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
                        ${nama_bagian_dan_posisi_di_kapal}
                        <input type='hidden' name="nama_bagian_dan_posisi_di_kapal[]" value="${nama_bagian_dan_posisi_di_kapal}">
                    </td>
                    <td>
                        ${uraian_kerja_perbaikan}
                        <input type='hidden' name="uraian_kerja_perbaikan[]" value="${uraian_kerja_perbaikan}">
                    </td>
                    <td>
                        ${suku_cadang}
                        <input type='hidden' name="suku_cadang[]" value="${suku_cadang}">
                    </td>
                    <td>
                        ${jumlah_satuan}
                        <input type='hidden' name="jumlah_satuan[]" value="${jumlah_satuan}">
                    </td>
                    <td>
                        ${nomor_bagian_suku_cadang}
                        <input type='hidden' name="nomor_bagian_suku_cadang[]" value="${nomor_bagian_suku_cadang}">
                    </td>
                    <td>
                        ${hasil_perbaikan}
                        <input type='hidden' name="hasil_perbaikan[]" value="${hasil_perbaikan}">
                    </td>
                    <td><button type="button" class="btn btn-sm btn-warning btn-open-modal" data-toggle="modal" data-target="#kt_modal_1">Hapus</button></td>
                </tr>`;
                $(".target-table tbody").append(html);
                $("#nama_bagian_dan_posisi_di_kapal").val('');
                $("#uraian_kerja_perbaikan").val('');
                $("#suku_cadang").val('');
                $("#jumlah_satuan").val('');
                $("#nomor_bagian_suku_cadang").val('');
                $("#hasil_perbaikan").val('');

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