@extends('iframe.layouts.index')

@section('title')
    Form Purchase Order
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label>(PO NO. OTOMATIS)</label>
            <label>Wilayah :</label>
            <input type="text" class="form-control">
        </div>
        <div class="form-group">
            <label>Supplier :</label>
            <select class="form-control" id="" name="">
                <option value="bl">BL</option>
                <option value="bol">BOL</option>
                <option value="br">BR</option>
                <option value="po">PO</option>
            </select>
        </div>
        <div class="form-group">
            <label>Wilayah Program :</label>
            <select class="form-control" id="" name="">
                <option value="bl">Pilihan sesuai program pak achmad</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleTextarea">Customer :</label>
            <textarea class="form-control" id="exampleTextarea"  placeholder="Pilihan Otomatis"></textarea>
        </div>
        <div class="form-group">
            <label for="exampleTextarea">Nama Kapal :</label>
            <textarea class="form-control" id="exampleTextarea"  placeholder="Pilihan Otomatis"></textarea>
        </div>
        <div class="form-group">
            <label>Product :</label>
            <select class="form-control" id="" name="">
                <option value="hsd">HSD</option>
                <option value="mfo">MFO</option>
                <option value="mdf">MDF</option>
            </select>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12" style="margin-right: -80px;">Satuan :</label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
                <select class="form-control">
                    <option value="liter">Liter</option>
                    <option value="mt">MT</option>
                </select>
            </div>
            <label class="col-form-label col-lg-3 col-sm-12" style="margin-right: -70px;">Kuantitas :</label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
              <input class="form-control" type="number" value="" id="example-number-input"/>
            </div>
        </div>
        <div class="form-group">
            <label>Harga :</label>
            <input class="form-control" type="number" value="" id="example-number-input"/>
        </div>
        <div class="form-group">
            <label>Attachment PO :</label>
             <input type="file" name="attachment" style="margin-left:10px;" accept=".png, .jpg, .jpeg"/>
        </div>
        <div class="form-group form-group-last">
          <div class="form-group col-form-label">
            <div class="radio-inline">
                <label for="">Status PO :</label>
                <label class="radio" style="margin-left:10px;">
                    <input type="radio" name="radios5"/>
                    <span></span>
                    OK
                </label>
                <label class="radio" style="margin-left:10px;">
                    <input type="radio" name="radios5"/>
                    <span></span>
                    Pending
                </label>
                <textarea class="form-control" id="exampleTextarea" placeholder="Alasan pending"></textarea>
            </div>
        </div>
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function() {
    $("#satuan").on('change' , function() {
      $("#kuantitas").removeAttr('disabled');
    });
  });
</script>
@endsection
