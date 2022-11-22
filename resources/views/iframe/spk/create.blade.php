@extends('iframe.layouts.index')

@section('title')
    Surat Permintaan Kapal
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('spk.store',$user_id)}}">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label for="exampleSelect1">MT / SPOB</label>
            <select class="form-control" id="exampleSelect1" name="mt_or_spob">
                <option value="mt">MT</option>
                <option value="spob">SPOB</option>
            </select>
        </div>
        <div class="form-group">
            <label>No</label>
            <input type="text" class="form-control" name="no">
            @if ($errors->has('no'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('no')}}</div>
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
            <label for="exampleSelect1">Keperluan</label>
            <select class="form-control" id="exampleSelect1" name="keperluan">
                <option value="mesin">Mesin</option>
                <option value="dek">Dek</option>
            </select>
        </div>
        <div class="form-group">
            <label>Catatan</label>
            <textarea class="form-control" name="note" rows="3"></textarea>
        </div>
        <hr>        
        <h4>Tambah Barang</h4>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="form-group row">
                <label class="col-form-label col-lg-3 col-sm-12">Barang</label>
                <div class=" col-lg-4 col-md-9 col-sm-12">
                    <select class="form-control kt-select2" id="kt_select2_1">
                        @foreach ($barang as $item)
                            <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                        @endforeach
                        <option value="lain-lain">Lain - lain</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="container-barang-etc" style="display: none">
                <label>Barang lain-lain (optional)</label>
                <input type="text" class="form-control" id="barang_etc">
            </div>
            <div class="form-group">
                <label>Qty</label>
                <input type="number" class="form-control"  id="barang_diminta">
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" id="ket" rows="3"></textarea>
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
                            <th>Nama barang</th>
                            <th>Qty</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data">
                            <td colspan="5"><center>Tidak ada data</center></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if ($errors->has('barang_diminta'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('barang_diminta')}}</div>
        @endif
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
            $('#kt_select2_1').on('change', function(){
                var value = $(this).val();
                if (value=='lain-lain') {
                    $("#container-barang-etc").show();
                }else{
                    $("#container-barang-etc").hide();
                }
            })
            // var signaturePad = $('#signaturePad').signature({syncField: '#signature64', syncFormat: 'PNG'});
            // $('#clear').click(function(e) {
            //     e.preventDefault();
            //     signaturePad.signature('clear');
            //     $("#signature64").val('');
            // });

            $("#tambah_data").on('click', function(){
                
                var selected_barang_name='';
                var selected_barang_id='';
                var selected_type='';

                var barang_id = $("#kt_select2_1 :selected").val();
                var barang_id_name = $("#kt_select2_1 :selected").text();
                var barang_etc = $("#barang_etc").val();
                
                if (barang_id == 'lain-lain') {
                    selected_barang_id = barang_etc;
                    selected_barang_name = barang_etc;
                    selected_type = 'barang_etc';
                    if (barang_etc==null || barang_etc=='') {
                        //open modal
                        $('#modal-peringatan').modal('toggle');
                        $('#pesan-peringatan').text('Barang lain-lain tidak boleh kosong');
                        return;
                    }
                }else{
                    selected_barang_id = barang_id;
                    selected_barang_name = barang_id_name;
                    selected_type = 'barang_id';
                }
                var barang_diminta = $("#barang_diminta").val();
                var ket = $("#ket").val();

                if (barang_diminta==null || barang_diminta=='' || barang_diminta==0) {
                    //open modal
                    $('#modal-peringatan').modal('toggle');
                    $('#pesan-peringatan').text('Qty tidak boleh kosong');
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
                        ${selected_barang_name} 
                        <input type='hidden' name="type[]" value="${selected_type}">
                        <input type='hidden' name="barang[]" value="${selected_barang_id}">
                        <input type='hidden' name="barang_name[]" value="${selected_barang_name}">
                    </td>
                    <td>
                        ${barang_diminta}
                        <input type='hidden' name="barang_diminta[]" value="${barang_diminta}">
                    </td>
                    <td>
                        ${ket}
                        <input type='hidden' name="ket[]" value="${ket}">
                    </td>
                    <td><button type="button" class="btn btn-sm btn-warning btn-open-modal" data-toggle="modal" data-target="#kt_modal_1">Hapus</button></td>
                </tr>`;
                $(".target-table tbody").append(html);
                $("#barang_etc").val('');
                $("#barang_diminta").val('');
                $("#ket").val('');

                 //open modal
                 $('#modal-peringatan').modal('toggle');
                $('#pesan-peringatan').text('Data berhasil ditambahkan');
            })
            $(document).on('click','.btn-open-modal', function(){
                var index = $(this).parents("tr").index();
                $("#kt_modal_1").attr("data-index",index);
            })
            $(".delete-confirm").on('click', function(){
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
            // $("#form-store").on('submit', function(e){
            //     e.preventDefault();
            //     var data = $('input[name!=tanda_tangan]', this).serialize(); 
            //     window.history.replaceState(null, null, "?"+data);
            //     var fullReqeust = $(this).serialize();
            //     $.ajax({
            //         url: "{{route('spk.store',$user_id)}}",
            //         type: "POST",
            //         data: fullReqeust ,
            //         success: function (response) {
            //             window.history.replaceState(null, null, "?"+data+"&status="+response.success+"&msg="+response.msg);
            //             // $(':input').not(':button, :submit, :reset, :hidden, :checkbox, :radio, :select').val('');
            //         // You will get response from your PHP page (what you echo or print)
            //         },
            //         error: function(response) {
            //             console.log(response);
            //         }
            //     });
            // })
            
        })
    </script>
@endsection