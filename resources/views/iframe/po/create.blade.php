@extends('iframe.layouts.index')

@section('title')
    Form Purchase Order
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Wilayah</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<select class="form-control kt-select2" id="kt_select2_1_wilayah_po" name="param">
							<option value="Pilihan Wilayah">Pilihan Wilayah</option>
						</select>
					</div>
				</div>
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Supplier</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<select class="form-control kt-select2" id="kt_select2_1_supplier" name="param">
							<option value="bl">BL</option>
							<option value="bol">BOL</option>
							<option value="br">BR</option>
							<option value="po">PO</option>
						</select>
					</div>
				</div>
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Wilayah Program</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<select class="form-control kt-select2" id="kt_select2_1_wilayah" name="param">
							<option value="Program pak achmad">Pilihan sesuai program pak achmad</option>
							<option value="Program pak achmad">Pilihan sesuai program pak achmad</option>
							<option value="Program pak achmad">Pilihan sesuai program pak achmad</option>
							<option value="Program pak achmad">Pilihan sesuai program pak achmad</option>
						</select>
					</div>
				</div>
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Customer</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<select class="form-control kt-select2" id="kt_select2_1_customer" name="param">
							<option value="Pilihan otomatis">Pilihan otomatis</option>
						</select>
					</div>
				</div>
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Nama Kapal</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<select class="form-control kt-select2" id="kt_select2_1_kapal" name="param">
							<option value="Pilihan otomatis">Pilihan otomatis</option>
						</select>
					</div>
				</div>
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Product</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<select class="form-control kt-select2" id="kt_select2_1_product" name="param">
							<option value="HSD">HSD</option>
							<option value="MFO">MFO</option>
							<option value="MDF">MDF</option>
						</select>
					</div>
				</div>
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Satuan</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<select class="form-control kt-select2" id="kt_select2_1_satuan" name="param">
							<option value="liter">Liter</option>
							<option value="mt">MT</option>
						</select>
					</div>
				</div>
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Kuantitas</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<input class="form-control" type="number" value="" id="example-number-input"/>
					</div>
				</div>
        <div class="form-group row">
					<label class="col-form-label col-lg-3 col-sm-12">Harga</label>
					<div class=" col-lg-4 col-md-9 col-sm-12">
						<input class="form-control" type="number" value="" id="example-number-input"/>
					</div>
				</div>
        <div class="form-group">
         <label>Attachment PO</label>
         <div></div>
           <div class="custom-file">
            <input type="file" class="custom-file-input" id="customFile"/>
            <label class="custom-file-label" for="customFile">Attach PO</label>
           </div>
        </div>
        <div class="form-group form-group-last">
          <div class="form-group col-form-label">
            <div class="radio-inline">
                <label for="">Status PO</label>
                <label class="radio m-left-10">
                    <input type="radio" name="radios5"/>
                    <span></span>
                    OK
                </label>
                <label class="radio pending m-left-10">
                    <input type="radio" name="radios5"/>
                    <span></span>
                    Pending
                </label>
                <textarea class="form-control rm_disable" placeholder="Alasan pending" disabled></textarea>
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
    $(".pending").on('click' , function() {
      $(".rm_disable").removeAttr('disabled');
    });
  });

  $('#kt_select2_1_satuan, #kt_select2_1_product, #kt_select2_1_wilayah, #kt_select2_1_supplier, #kt_select2_1_customer, #kt_select2_1_kapal, #kt_select2_1_wilayah_po').select2({
      placeholder: "Select a state"
  });
</script>
@endsection
