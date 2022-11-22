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
<form class="kt-form" id="form-store" method="POST" action="{{route('rencana-pelayanan.update',[$user_id,$data->id])}}" enctype="multipart/form-data">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label for="exampleSelect1">Input PO</label>
            <select class="form-control" id="input_po" name="input_po">
                <option value="" selected>Pilih Salah Satu</option>
                @foreach ($input_po as $item)
                    <option value="{{$item->id}}" {{$data->input_po_id==$item->id?'selected':''}}>{{$item->no_po}}</option>
                @endforeach
            </select>
            @if ($errors->has('input_po'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('input_po')}}</div>
            @endif
        </div>
        <div class="form-group" id="container-detail" style="display: none">
            <label for="exampleSelect1">Detail PO</label>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="kt-section__info black">No SAO</div>
                        <div class="kt-section__content" id="no_sao"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">Wilayah</div>
                        <div class="kt-section__content" id="wilayah"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">Jenis Kegiatan</div>
                        <div class="kt-section__content" id="jenis_kegiatan"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">Tanggal PO</div>
                        <div class="kt-section__content" id="tanggal_po"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">Perusahaan</div>
                        <div class="kt-section__content" id="perusahaan"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">No PO</div>
                        <div class="kt-section__content" id="no_po"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="kt-section__info black">Nama Customer</div>
                        <div class="kt-section__content" id="nama_customer"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">Kapal</div>
                        <div class="kt-section__content" id="office"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">Contact Person</div>
                        <div class="kt-section__content" id="contact_person"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">Jenis Produk</div>
                        <div class="kt-section__content" id="jenis_produk"></div>
                    </div>
                    <div class="form-group">
                        <div class="kt-section__info black">Qty</div>
                        <div class="kt-section__content" id="qty"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Tanggal Rencana Bunker</label>
            <div class="col-10">
                <input class="form-control" type="date" name="tanggal_rencana_bunker" value="{{Carbon\Carbon::parse($data->tanggal_rencana_bunker)->format('Y-m-d')}}" id="example-date-input">
                @if ($errors->has('tanggal_rencana_bunker'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal_rencana_bunker')}}</div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Nama OOB</label>
            <input type="text" name="nama_oob" class="form-control" value="{{$data->nama_oob}}">
            @if ($errors->has('nama_oob'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('nama_oob')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Kapal</label>
            <select class="form-control" id="exampleSelect1" name="kapal">
                @foreach ($kapal as $item)
                    <option value="{{$item->id}}" {{$data->kapal_id==$item->id?'selected':''}}>{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('kapal'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('kapal')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Nomor RFB</label>
            <input type="text" name="nomor_rfb" class="form-control" value="{{$data->no_rfb}}">
            @if ($errors->has('nomor_rfb'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('nomor_rfb')}}</div>
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
        $(document).on('change',"#input_po", function(){
            var id = $(this).val();
            if (id=='') {
                $('#container-detail').hide();
            }else{
                ajaxCall(id);
            }
        })
        var defaultValue = $('#input_po').val();
        if (defaultValue !='') {
            ajaxCall(defaultValue);
        }
        function ajaxCall(id){
            var url = "{{route('rencana-pelayanan.detailPO',':id')}}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token : "{{csrf_token()}}"
                    },
                success:function(data){
                    $('#container-detail').show();
                    $('#no_sao').text(data.no_sao);
                    $('#wilayah').text(data.wilayah);
                    $('#jenis_kegiatan').text(data.jenis_kegiatan);
                    $('#tanggal_po').text(data.tanggal_po);
                    $('#perusahaan').text(data.perusahaan);
                    $('#no_po').text(data.no_po);
                    $('#nama_customer').text(data.nama_customer);
                    $('#office').text(data.office);
                    $('#contact_person').text(data.contact_person);
                    $('#jenis_produk').text(data.jenis_produk);
                    $('#qty').text(data.jenis_produk);
                }, 
            });
        }
    })
</script>
@endsection