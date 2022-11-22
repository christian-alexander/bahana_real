@extends('iframe.layouts.index')

@section('title')
    Form Data Customer
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <h4>Data Perusahaan</h4>
      <hr>
      <div class="form-group row">
        <label  class="col-2 col-form-label">Customer</label>
        <div class="col-10">
         <input class="form-control" type="text" value="" placeholder="Ketik nama perusahaan"/>
        </div>
       </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">Alamat</label>
        <div class="col-10">
         <input class="form-control" type="text" value="" placeholder="Ketik alamat perusahaan"/>
        </div>
       </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">No. Telepon</label>
        <div class="col-10">
         <input class="form-control" type="text" value="" placeholder="Ketik telepon"/>
        </div>
       </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">No. Fax</label>
        <div class="col-10">
         <input class="form-control" type="text" value="" placeholder="Ketik fax"/>
        </div>
       </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">PIC</label>
        <div class="col-10">
         <input class="form-control" type="text" value="" placeholder="Ketik nama pic"/>
        </div>
       </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">Jabatan</label>
        <div class="col-10">
         <input class="form-control" type="text" value="" placeholder="Ketik jabatan pic"/>
        </div>
       </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">Mobile</label>
        <div class="col-10">
         <input class="form-control" type="text" value="" placeholder="Ketik nomor hp pic"/>
        </div>
       </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">Email</label>
        <div class="col-10">
         <input class="form-control" type="text" value="" placeholder="Ketik email"/>
        </div>
      </div>
       <div class="form-group row">
           <label class="col-2 col-form-label">Data Perpajakan</label>
           <div class="checkbox-inline" style="margin-top:15px; margin-left:30px;">
               <label class="checkbox">
                   <input type="checkbox" name=""/>
                   <span></span>
                   Centang jika ada
               </label>
           </div>
       </div>
       <div class="form-group row">
         <label  class="col-2 col-form-label">NPWP</label>
         <div class="col-10">
           <input class="form-control" type="text" value="" placeholder="Ketik npwp"/>
         </div>
       </div>
       <div class="form-group row">
         <label  class="col-2 col-form-label">Alamat NPWP</label>
         <div class="col-10">
           <input class="form-control" type="text" value="" placeholder="Ketik alamat npwp"/>
         </div>
       </div>
       <div class="form-group">
        <label>Attachment NPWP</label>
        <div></div>
          <div class="custom-file">
           <input type="file" class="custom-file-input"/>
           <label class="custom-file-label" for="customFile">Attach NPWP</label>
          </div>
       </div>
       <div class="form-group row">
           <label class="col-2 col-form-label">Jenis Perusahaan</label>
           <div class="checkbox-inline col-10 form-control border-0 m-left-30">
               <label class="checkbox m-right-15">
                   <input type="checkbox" name="bl"/>
                   <span></span>
                   PEMUNGUT
               </label>
               <label class="checkbox m-right-15">
                   <input type="checkbox" name="bol"/>
                   <span></span>
                   BENDAHARAWAN PEMUNGUT
               </label>
               <label class="checkbox">
                   <input type="checkbox" name="br"/>
                   <span></span>
                   NON
               </label>
           </div>
       </div>
       <div class="form-group row">
           <label class="col-2 col-form-label">Jenis Transaksi</label>
           <div class="checkbox-inline col-10 form-control border-0 m-left-30">
               <label class="checkbox">
                   <input type="checkbox" name="bl"/>
                   <span></span>
                   Bunker
               </label>
               <label class="checkbox m-left-40">
                   <input type="checkbox" name="bol"/>
                   <span></span>
                   Ongkos Angkut
               </label>
           </div>
       </div>
       <div class="form-group row">
           <label class="col-2 col-form-label">Potongan PPH</label>
           <div class="checkbox-inline col-10 form-control border-0 m-left-30">
               <label class="checkbox">
                   <input type="checkbox" name="bl"/>
                   <span></span>
                   PPH 15
               </label>
               <label class="checkbox m-left-44">
                   <input type="checkbox" name="bol"/>
                   <span></span>
                   PPH 23
               </label>
           </div>
       </div>
       <div class="form-group row">
           <label class="col-2 col-form-label">Pemesanan</label>
           <div class="checkbox-inline col-10 form-control border-0 m-left-30">
               <label class="checkbox m-right-20">
                   <input type="checkbox" name="bl"/>
                   <span></span>
                   Kontrak
               </label>
               <label class="checkbox m-left-16">
                   <input type="checkbox" name="bol"/>
                   <span></span>
                   PO/SPK
               </label>
           </div>
       </div>
        <hr>
        <h4>PIC Finance</h4>
        <hr>
        <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Nama</label>
          <div class=" col-lg-4 col-md-9 col-sm-12">
            <input class="form-control" type="text" name="" value="" placeholder="Ketik nama finance">
          </div>
          <label class="col-form-label col-lg-3 col-sm-12 m-right-80">Jabatan</label>
          <div class=" col-lg-4 col-md-9 col-sm-12">
            <input class="form-control" type="text" value="" placeholder="Ketik jabatan finance"/>
          </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label"></label>
            <div class="checkbox-inline col-10 form-control border-0">
                <label class="checkbox m-right-20">
                    <input type="checkbox" name="bl"/>
                    <span></span>
                    Centang Apabila Sama dengan PIC kantor
                </label>
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-2 col-form-label">Alamat Finance</label>
            <div class="col-10">
             <input class="form-control" type="text" value="" id="example-text-input" placeholder="Ketik alamat finance"/>
            </div>
         </div>
         <div class="form-group row">
             <label class="col-2 col-form-label"></label>
             <div class="checkbox-inline col-10 form-control border-0">
                 <label class="checkbox m-right-20">
                     <input type="checkbox" name="bl"/>
                     <span></span>
                     Centang Apabila Sama dengan alamat kantor
                 </label>
             </div>
         </div>
        <div class="form-group row">
            <label  class="col-2 col-form-label">No. Telepon</label>
            <div class="col-10">
             <input class="form-control" type="text" value="" id="example-text-input" placeholder="Ketik telp finance"/>
            </div>
         </div>
         <div class="form-group row">
             <label class="col-2 col-form-label"></label>
             <div class="checkbox-inline col-10 form-control border-0">
                 <label class="checkbox m-right-20">
                     <input type="checkbox" name="bl"/>
                     <span></span>
                     Centang Apabila Sama dengan telepon kantor
                 </label>
             </div>
         </div>
        <div class="form-group row">
            <label  class="col-2 col-form-label">Mobile</label>
            <div class="col-10">
             <input class="form-control" type="text" value="" id="example-text-input" placeholder="Ketik HP finance jika ada"/>
            </div>
         </div>
         <div class="form-group row">
             <label class="col-2 col-form-label"></label>
             <div class="checkbox-inline col-10 form-control border-0">
                 <label class="checkbox m-right-20">
                     <input type="checkbox" name="bl"/>
                     <span></span>
                     Centang Apabila Sama dengan PIC kantor
                 </label>
             </div>
         </div>
        <div class="form-group row">
            <label  class="col-2 col-form-label">Email</label>
            <div class="col-10">
             <input class="form-control" type="text" value="" id="example-text-input" placeholder="Ketik email"/>
            </div>
         </div>
         <div class="form-group row">
             <label class="col-2 col-form-label"></label>
             <div class="checkbox-inline col-10 form-control border-0">
                 <label class="checkbox m-right-20">
                     <input type="checkbox" name="bl"/>
                     <span></span>
                     Centang Apabila Sama dengan email kantor
                 </label>
             </div>
         </div>
         <hr>
         <h4>Pengiriman Dokumen</h4>
         <hr>
         <div class="form-group row">
             <label  class="col-2 col-form-label">Alamat Kirim</label>
             <div class="col-10">
               <input class="form-control" type="text" value="" id="example-text-input" placeholder="Ketik alamat kirim"/>
             </div>
          </div>
          <div class="form-group row">
              <label class="col-2 col-form-label"></label>
              <div class="checkbox-inline col-10 form-control border-0">
                  <label class="checkbox m-right-20">
                      <input type="checkbox" name="bl"/>
                      <span></span>
                      Centang Apabila sama dengan alamat kantor
                  </label>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-2 col-form-label"></label>
              <div class="checkbox-inline col-10 form-control border-0">
                  <label class="checkbox m-right-20">
                      <input type="checkbox" name="bl"/>
                      <span></span>
                      Centang Apabila sama dengan alamat finance
                  </label>
              </div>
          </div>
         <div class="form-group row">
             <label  class="col-2 col-form-label">No. Telepon</label>
             <div class="col-10">
               <input class="form-control" type="text" value="" id="example-text-input" placeholder="Ketik telepon"/>
             </div>
          </div>
          <div class="form-group row">
              <label class="col-2 col-form-label"></label>
              <div class="checkbox-inline col-10 form-control border-0">
                  <label class="checkbox m-right-20">
                      <input type="checkbox" name="bl"/>
                      <span></span>
                      Centang Apabila sama dengan telepon kantor
                  </label>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-2 col-form-label"></label>
              <div class="checkbox-inline col-10 form-control border-0">
                  <label class="checkbox m-right-20">
                      <input type="checkbox" name="bl"/>
                      <span></span>
                      Centang Apabila sama dengan telepon finance
                  </label>
              </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Penerima</label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
              <input class="form-control" type="text" name="" value="" placeholder="Ketik nama penerima">
            </div>
            <label class="col-form-label col-lg-3 col-sm-12 m-right-80">Jabatan</label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
              <input class="form-control" type="text" value="" id="example-number-input"/ placeholder="Ketik jabatan penerima">
            </div>
          </div>
          <div class="form-group row">
              <label  class="col-2 col-form-label">Mobile</label>
              <div class="col-10">
                <input class="form-control" type="text" value="" id="example-text-input" placeholder="Ketik HP penerima jika ada"/>
              </div>
           </div>
           <div class="form-group row">
               <label class="col-2 col-form-label"></label>
               <div class="checkbox-inline col-10 form-control border-0">
                   <label class="checkbox m-right-20">
                       <input type="checkbox" name="bl"/>
                       <span></span>
                       Centang Apabila sama dengan PIC kantor
                   </label>
               </div>
           </div>
           <div class="form-group row">
               <label class="col-2 col-form-label"></label>
               <div class="checkbox-inline col-10 form-control border-0">
                   <label class="checkbox m-right-20">
                       <input type="checkbox" name="bl"/>
                       <span></span>
                       Centang Apabila sama dengan PIC finance
                   </label>
               </div>
           </div>
          <div class="form-group row">
              <label  class="col-2 col-form-label">Email</label>
              <div class="col-10">
                <input class="form-control" type="text" value="" id="example-text-input" placeholder="Ketik email"/>
              </div>
           </div>
           <div class="form-group row">
               <label class="col-2 col-form-label"></label>
               <div class="checkbox-inline col-10 form-control border-0">
                   <label class="checkbox m-right-20">
                       <input type="checkbox" name="bl"/>
                       <span></span>
                       Centang Apabila sama dengan email kantor
                   </label>
               </div>
           </div>
           <div class="form-group row">
               <label class="col-2 col-form-label"></label>
               <div class="checkbox-inline col-10 form-control border-0">
                   <label class="checkbox m-right-20">
                       <input type="checkbox" name="bl"/>
                       <span></span>
                       Centang Apabila sama dengan email finance
                   </label>
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
