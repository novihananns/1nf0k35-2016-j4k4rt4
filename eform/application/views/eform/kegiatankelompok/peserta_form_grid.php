
<script type="text/javascript">
  <?php $kodebarang_ = substr($kode, -14,-12);
      if($kodebarang_=='01') {?>  
            $("#status_sertifikat_tanggal").jqxDateTimeInput({ width: '300px', height: '25px' })
<?php  }else if($kodebarang_=='02') {?>
            $("#tanggal_bpkb").jqxDateTimeInput({ width: '300px', height: '25px' });
            $("#tanggal_perolehan").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php  }else if($kodebarang_=='03') {?>
            $("#dokumen_tanggal").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php  }else if($kodebarang_=='04') {?>
            $("#dokumen_tanggal1").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php  }else if($kodebarang_=='05') {?>
             $("#tahun_cetak_beli").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php  }else if($kodebarang_=='06') {?>
            $("#dokumen_tanggal2").jqxDateTimeInput({ width: '300px', height: '25px' });
            $("#tanggal_mulai").jqxDateTimeInput({ width: '300px', height: '25px' });
<?php }?>   
  
 
</script>
<?php
if(isset($disable)){if($disable='disable'){?>

<script type="text/javascript">
  $("#dateInput").jqxDateTimeInput({ width: '300px', height: '25px' });
</script>
<?php }} ?>
<script type="text/javascript">

  function toRp(a,b,c,d,e){e=function(f){return f.split('').reverse().join('')};b=e(parseInt(a,10).toString());for(c=0,d='';c<b.length;c++){d+=b[c];if((c+1)%3===0&&c!==(b.length-1)){d+='.';}}return'Rp.\t'+e(d)+',00'}


    $(function(){
      $('#btn-close').click(function(){
        close_popup();
      }); 
        $('#form-ss').submit(function(){
            var data = new FormData();
            $('#notice-content').html('<div class="alert">Mohon tunggu, proses simpan data....</div>');
            $('#notice').show();
            data.append('id_inventaris_barang', $('#id_inventaris_barang').val());
            data.append('id_mst_inv_barang', $('#v_kode_barang').val());
            data.append('tanggal_diterima', $('#dateInput').val());
            data.append('pilihan_status_invetaris', $('#status_invetaris').val());
            data.append('nama_barang', $('#v_nama_barang').val());
            data.append('jumlah', $('#jumlah').val());
            data.append('harga', $('#harga').val());
            //data.append('keterangan_pengadaan', $('#keterangan').val());
            var kd_barang = $('#v_kode_barang').val().substring(0,2);
            if(kd_barang=="01"){
                data.append('luas', $('#luas').val());
                data.append('alamat', $('#alamat').val());
                data.append('pilihan_satuan_barang', $('#pilihan_satuan_barang').val());
                data.append('pilihan_status_hak', $('#pilihan_status_hak').val());
                data.append('status_sertifikat_tanggal', $('#status_sertifikat_tanggal').val());
                data.append('pilihan_penggunaan', $('#pilihan_penggunaan').val());
                data.append('status_sertifikat_nomor', $('#status_sertifikat_nomor').val());
            }else if(kd_barang=="02"){
                data.append('merek_type', $('#merek_type').val());
                data.append('identitas_barang', $('#identitas_barang').val());
                data.append('pilihan_bahan', $('#pilihan_bahan').val());
                data.append('ukuran_barang', $('#ukuran_barang').val());
                data.append('pilihan_satuan', $('#pilihan_satuan').val());
                data.append('tanggal_bpkb', $('#tanggal_bpkb').val());
                data.append('nomor_bpkb', $('#nomor_bpkb').val());
                data.append('no_polisi', $('#no_polisi').val());
                data.append('tanggal_perolehan', $('#tanggal_perolehan').val());
            }else if(kd_barang=="03"){
                data.append('luas_lantai', $('#luas_lantai').val());
                data.append('letak_lokasi_alamat', $('#letak_lokasi_alamat').val());
                data.append('pillihan_status_hak', $('#pillihan_status_hak').val());
                data.append('nomor_kode_tanah', $('#nomor_kode_tanah').val());
                data.append('pilihan_kons_tingkat', $('#pilihan_kons_tingkat').val());
                data.append('pilihan_kons_beton', $('#pilihan_kons_beton').val());
                data.append('dokumen_tanggal', $('#dokumen_tanggal').val());
                data.append('dokumen_nomor', $('#dokumen_nomor').val());
            }else if(kd_barang=="04"){
                data.append('konstruksi', $('#konstruksi').val());
                data.append('panjang', $('#panjang').val());
                data.append('lebar', $('#lebar').val());
                data.append('luas', $('#luas1').val());
                data.append('letak_lokasi_alamat', $('#letak_lokasi_alamat1').val());
                data.append('dokumen_tanggal', $('#dokumen_tanggal1').val());
                data.append('dokumen_nomor', $('#dokumen_nomor1').val());
                data.append('pilihan_status_tanah', $('#pilihan_status_tanah').val());
                data.append('nomor_kode_tanah', $('#nomor_kode_tanah1').val());
            }else if(kd_barang=="05"){
                data.append('buku_judul_pencipta', $('#buku_judul_pencipta').val());
                data.append('buku_spesifikasi', $('#buku_spesifikasi').val());
                data.append('budaya_asal_daerah', $('#budaya_asal_daerah').val());
                data.append('budaya_pencipta', $('#budaya_pencipta').val());
                data.append('pilihan_budaya_bahan', $('#pilihan_budaya_bahan').val());
                data.append('flora_fauna_jenis', $('#flora_fauna_jenis').val());
                data.append('flora_fauna_ukuran', $('#flora_fauna_ukuran').val());
                data.append('pilihan_satuan', $('#pilihan_satuan1').val());
                data.append('tahun_cetak_beli', $('#tahun_cetak_beli').val());
            }else if(kd_barang=="06"){
                data.append('bangunan', $('#bangunan').val());
                data.append('pilihan_konstruksi_bertingkat', $('#pilihan_konstruksi_bertingkat').val());
                data.append('pilihan_konstruksi_beton', $('#pilihan_konstruksi_beton').val());
                data.append('luas', $('#luas2').val());
                data.append('lokasi', $('#lokasi').val());
                data.append('dokumen_tanggal', $('#dokumen_tanggal2').val());
                data.append('dokumen_nomor', $('#dokumen_nomor2').val());
                data.append('tanggal_mulai', $('#tanggal_mulai').val());
                data.append('pilihan_status_tanah', $('#pilihan_status_tanah1').val());
            }
            

            $.ajax({
                cache : false,
                contentType : false,
                processData : false,
                type : 'POST',
                url : '<?php echo base_url()."inventory/pengadaanbarang/".$action."_barang/".$id_pengadaan."/".$kode."/".$kode_proc ?>',
                data : data,
                
                success : function(response){
                
                
                  var res  = response.split("|");
                  if(res[0]=="OK"){
                      $('#notice').hide();
                      $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                      $('#notice').show();
                      $("#jqxgrid_barang").jqxGrid('updatebounddata', 'cells');
                      close_popup();
                  }
                  else if(res[0]=="Error"){
                      $('#notice').hide();
                      $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                      $('#notice').show();
                  }
                  else{
                      $('#popup_content').html(response);
                  }
              }
            });

            return false;
        });

        $("#jqxinput").jqxInput(
          {
          placeHolder: " Ketik Kode atau Nama Barang ",
          theme: 'classic',
          width: '100%',
          height: '30px',
          minLength: 2,
          source: function (query, response) {
            var dataAdapter = new $.jqx.dataAdapter
            (
              {
                datatype: "json",
                  datafields: [
                  { name: 'uraian', type: 'string'},
                  { name: 'code', type: 'string'},
                  { name: 'code_tampil', type: 'string'}
                ],
                url: '<?php echo base_url().'inventory/permohonanbarang/autocomplite_barang'; ?>'
              },
              {
                autoBind: true,
                formatData: function (data) {
                  data.query = query;
                  return data;
                },
                loadComplete: function (data) {
                  if (data.length > 0) {
                    response($.map(data, function (item) {
                      return item.code_tampil +' | '+item.uraian;
                    }));
                  }
                }
              });
          }
        });
      
        $("#jqxinput").select(function(){
            var codebarang = $(this).val();
            var res = codebarang.split(" | ");
            $("#v_nama_barang").val(res[1]);
            $("#v_kode_barang").val(res[0].replace(/\./g,""));
        });
        $("#harga").change(function(){
            var jumlah = document.getElementById("jumlah").value;
            var harga = document.getElementById("harga").value;
            var subtotal =jumlah*harga;
            document.getElementById("subtotal").value = toRp(subtotal);
        });
        $("#jumlah").change(function(){
            var jumlah = document.getElementById("jumlah").value;
            var harga = document.getElementById("harga").value;
            var subtotal =jumlah*harga;
            document.getElementById("subtotal").value = toRp(subtotal);
        });
        
    });
</script>

<div style="padding:15px">
  <div id="notice" class="alert alert-success alert-dismissable" <?php if ($notice==""){ echo 'style="display:none"';} ?> >
    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
    <i class="icon fa fa-check"></i>
    Information!
    </h4>
    <div id="notice-content">{notice}</div>
  </div>
	<div class="row">
    <?php echo form_open(current_url(), 'id="form-ss"') ?>
    <div class="col-md-6">
    <div class="box box-primary">
          <div class="box-body">
          <div class="form-group">
              <label>Kode Lokasi</label>
              <input type="text" class="form-control" id="id_inventaris_barang" name="id_inventaris_barang"  placeholder="Kode Inventaris Barang" value="<?php
              if(set_value('id_inventaris_barang')=="" && isset($id_inventaris_barang)){
                  $s = array();
                  $s[0] = substr($id_inventaris_barang, 0,2);
                  $s[1] = substr($id_inventaris_barang, 2,2);
                  $s[2] = substr($id_inventaris_barang, 4,2);
                  $s[3] = substr($id_inventaris_barang, 6,2);
                  $s[4] = substr($id_inventaris_barang, 8,2);
                  $s[5] = substr($id_inventaris_barang, 10,2);
                  $s[6] = substr($id_inventaris_barang, 12,2);
                  $s[7] = substr($id_inventaris_barang, 14,2);
                  $s[8] = substr($id_inventaris_barang, 16,2);
                  $s[9] = substr($id_inventaris_barang, 18,2);
                  $s[10] = substr($id_inventaris_barang, 20,2);
                  $s[11] = substr($id_inventaris_barang, 22,2);
                  $s[12] = substr($id_inventaris_barang, 24,4);
                  echo implode(".", $s);
                }else{
                  echo  set_value('id_inventaris_barang');
                }
                ?>" disabled>
            </div>
            <div class="form-group"> 
              <label>Register</label>
              <input type="text" class="form-control" name="register" id="register" placeholder="Register" value="<?php 
                if(set_value('register')=="" && isset($register)){
                  echo $register.' s/d '.sprintf("%03s", $register+$jumlah);
                }else{
                  echo  set_value('register');
                }
                ?>" disabled>
              <input id="jqxinput" class="form-control" autocomplete="off" name="code_mst_inv" type="hidden" value="<?php 
                if(set_value('code_mst_inv')=="" && isset($id_mst_inv_barang)){ 
                  $s = array();
                  $s[0] = substr($id_mst_inv_barang, 0,2);
                  $s[1] = substr($id_mst_inv_barang, 2,2);
                  $s[2] = substr($id_mst_inv_barang, 4,2);
                  $s[3] = substr($id_mst_inv_barang, 6,2);
                  $s[4] = substr($id_mst_inv_barang, 8,2);
                  echo implode(".", $s).' | '.$nama_barang;
                }else{
                  echo  set_value('code_mst_inv');
                }
                ?>" <?php if(isset($disable)){if($disable='disable'){echo "readonly";}} ?>/>
              <input id="v_kode_barang" class="form-control" name="code_mst_inv_barang" type="hidden" value="<?php 
                if(set_value('code_mst_inv_barang')=="" && isset($id_mst_inv_barang)){
                  echo $id_mst_inv_barang;
                }else{
                  echo  set_value('code_mst_inv_barang');
                }
                ?>" />
            </div>
            <div class="form-group">
              <label>Nama Barang</label>
              <input type="text" class="autocomplete form-control" id="v_nama_barang" name="nama_barang"  placeholder="Nama Barang" value="<?php
              if(set_value('nama_barang')=="" && isset($nama_barang)){
                  echo $nama_barang;
                }else{
                  echo  set_value('nama_barang');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Jumlah</label>
              <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php 
                if(set_value('jumlah')=="" && isset($jumlah)){
                  echo $jumlah;
                }else{
                  echo  set_value('jumlah');
                }
                ?>" disabled>
            </div>
            <div class="form-group">
              <label>Harga Satuan</label>
              <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga Satuan" value="<?php 
                if(set_value('harga')=="" && isset($harga)){
                  echo $harga;
                }else{
                  echo  set_value('harga');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Sub Total</label>
              <input type="text" class="form-control" name="subtotal"  id="subtotal" placeholder="Sub Total" readonly="" value="<?php
              if(set_value('subtotal')=="" && isset($harga)){
                  echo $jumlah*$harga;
                }else{
                  echo  set_value('subtotal');
                }
                ?>">
            </div>
            <?php if(isset($disable)){if($disable='disable'){?>
            <div class="form-group">
              <label>Tanggal Diterima</label>
              <div id='dateInput' name="tanggal_diterima" value="<?php
              echo (!empty($tanggal_diterima)) ? date("Y-m-d",strtotime($tanggal_diterima)) :  date("d-m-Y");
            ?>"></div>
            </div>
            <div class="form-group">
              <label>Status Inventaris</label>
            <select  name="status_invetaris" type="text" class="form-control" id="status_invetaris">
                <option value="">Pilih Status Inventaris</option>
                </option>
                <?php foreach($kodestatus_inv as $stat) : ?>
                  <?php $select = $stat->code == $pilihan_status_invetaris ? 'selected' : '' ?>
                  <option value="<?php echo $stat->code ?>" <?php echo $select ?>><?php echo $stat->value ?></option>
                <?php endforeach ?>
            </select>
            </div>
            <?php }} ?>
            <!--<div class="form-group">
              <label>Keterangan</label>
              <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan"><?php 
                /*  if(set_value('keterangan')=="" && isset($keterangan_pengadaan)){
                    echo $keterangan_pengadaan;
                  }else{
                    echo  set_value('keterangan');
                  }*/
                  ?></textarea>
            </div>-->
        </div>
        </div>
        </div>
    <div class="col-md-6">
    <div class="box box-warning">
    <div class="box-body">

    <!--body from edit-->
    <?php 
    $kodebarang_ = substr($kode, -14,-12);
    if($kodebarang_=='01') {?>
      <div class="form-group">
        <label>Luas</label>
        <input type="number" class="form-control" name="luas" id="luas" placeholder="Luas"  value="<?php
        if(set_value('luas')=="" && isset($luas)){
            echo $luas;
          }else{
            echo  set_value('luas');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" placeholder="alamat"><?php 
        if(set_value('alamat')=="" && isset($alamat)){
          echo $alamat;
        }else{
          echo  set_value('alamat');
        }
        ?></textarea>
      </div>
      <div class="form-group">
        <label>Pilihan Status Barang</label>
        <select  name="pilihan_satuan_barang" type="text" class="form-control" id="pilihan_satuan_barang">
            <option value="">Pilih Satuan Barang</option>
            </option>
            <?php foreach($pilihan_satuan_barang_ as $barang) : ?>
              <?php $select = $barang->code == $pilihan_satuan_barang ? 'selected' : '' ?>
              <option value="<?php echo $barang->code ?>" <?php echo $select ?>><?php echo $barang->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Pilihan Status Hak</label>
        <select  name="pilihan_status_hak" type="text" class="form-control" id="pilihan_status_hak">
            <option value="">Pilih Status Hak</option>
            </option>
            <?php foreach($pilihan_status_hak_ as $hak) : ?>
              <?php $select = $hak->code == $pilihan_status_hak ? 'selected' : '' ?>
              <option value="<?php echo $hak->code ?>" <?php echo $select ?>><?php echo $hak->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tanggal Sertifikat</label>
        <div id='status_sertifikat_tanggal' name="status_sertifikat_tanggal" value="<?php
        echo (!empty($status_sertifikat_tanggal)) ? date("Y-m-d",strtotime($status_sertifikat_tanggal)) :  date("d-m-Y");
      ?>"></div>
      </div>
      <div class="form-group">
        <label>Nomor Sertifikat</label>
        <input type="text" class="form-control" name="status_sertifikat_nomor" id="status_sertifikat_nomor" placeholder="Nomor Sertifikat"  value="<?php
        if(set_value('status_sertifikat_nomor')=="" && isset($status_sertifikat_nomor)){
            echo $status_sertifikat_nomor;
          }else{
            echo  set_value('status_sertifikat_nomor');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Pilihan Penggunaan</label>
        <select  name="pilihan_penggunaan" type="text" class="form-control" id="pilihan_penggunaan">
            <option value="">Pilih Status Penggunaan</option>
            </option>
            <?php foreach($pilihan_penggunaan_ as $pengguna) : ?>
              <?php $select = $pengguna->code == $pilihan_penggunaan ? 'selected' : '' ?>
              <option value="<?php echo $pengguna->code ?>" <?php echo $select ?>><?php echo $pengguna->value ?></option>
            <?php endforeach ?>
        </select>
      </div>

      <?php  }else if($kodebarang_=='02') {?>

      <div class="form-group">
        <label>Merek Tipe</label>
        <textarea class="form-control"  name="merek_type" placeholder="Merek Tipe" id="merek_type"><?php 
        if(set_value('merek_type')=="" && isset($merek_type)){
          echo $merek_type;
        }else{
          echo  set_value('merek_type');
        }
        ?></textarea>
      </div>
      <div class="form-group">
        <label>Identitas Barang</label>
        <textarea class="form-control"  name="identitas_barang" id="identitas_barang" placeholder="Identitas Barang"><?php 
        if(set_value('identitas_barang')=="" && isset($identitas_barang)){
          echo $identitas_barang;
        }else{
          echo  set_value('identitas_barang');
        }
        ?></textarea>
      </div>
      <div class="form-group">
        <label>Pilihan Barang</label>
        <select  name="pilihan_bahan" type="text" class="form-control" id="pilihan_bahan">
            <option value="">Pilih Status Barang</option>
            </option>
            <?php foreach($pilihan_bahan_ as $bahan) : ?>
              <?php $select = $bahan->code == $pilihan_bahan ? 'selected' : '' ?>
              <option value="<?php echo $bahan->code ?>" <?php echo $select ?>><?php echo $bahan->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Ukuran Barang</label>
        <input type="text" class="form-control" name="ukuran_barang" id="ukuran_barang" placeholder="Ukuran Barang"  value="<?php
        if(set_value('ukuran_barang')=="" && isset($ukuran_barang)){
            echo $ukuran_barang;
          }else{
            echo  set_value('ukuran_barang');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Pilihan Satuan</label>
        <select  name="pilihan_satuan" type="text" class="form-control" id="pilihan_satuan">
            <option value="">Pilih Status Satuan</option>
            </option>
            <?php foreach($pilihan_satuan_ as $satuan) : ?>
              <?php $select = $satuan->code == $pilihan_satuan ? 'selected' : '' ?>
              <option value="<?php echo $satuan->code ?>" <?php echo $select ?>><?php echo $satuan->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tanggal BPKB</label>
        <div id='tanggal_bpkb' name="tanggal_bpkb" value="<?php
        echo (!empty($tanggal_bpkb)) ? date("Y-m-d",strtotime($tanggal_bpkb)) :  date("d-m-Y");
      ?>"></div>
      </div>
      <div class="form-group">
        <label>Nomor BPKB</label>
        <input type="text" class="form-control" name="nomor_bpkb" id="nomor_bpkb"  placeholder="Nomor BPKB"  value="<?php
        if(set_value('nomor_bpkb')=="" && isset($nomor_bpkb)){
            echo $nomor_bpkb;
          }else{
            echo  set_value('nomor_bpkb');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Nomor Polisi</label>
        <input type="text" class="form-control" name="no_polisi" id="no_polisi" placeholder="Nomor Polisi"  value="<?php
        if(set_value('no_polisi')=="" && isset($no_polisi)){
            echo $no_polisi;
          }else{
            echo  set_value('no_polisi');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Tanggal Perolehan</label>
        <div id='tanggal_perolehan' name="tanggal_perolehan" value="<?php
        echo (!empty($tanggal_perolehan)) ? date("Y-m-d",strtotime($tanggal_perolehan)) :  date("d-m-Y");
      ?>"></div>
      </div>


      <?php  }else if($kodebarang_=='03') {?>
        
      <div class="form-group">
        <label>Luas Lantai</label>
        <input type="number" class="form-control" name="luas_lantai" id="luas_lantai"  placeholder="Luas Lantai"  value="<?php
        if(set_value('luas_lantai')=="" && isset($luas_lantai)){
            echo $luas_lantai;
          }else{
            echo  set_value('luas_lantai');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Alamat Lokasi</label>
        <textarea class="form-control" id="letak_lokasi_alamat" name="letak_lokasi_alamat" placeholder="Lokasi Alamat"><?php 
            if(set_value('letak_lokasi_alamat')=="" && isset($letak_lokasi_alamat)){
              echo $letak_lokasi_alamat;
            }else{
              echo  set_value('letak_lokasi_alamat');
            }
            ?></textarea>
      </div>
      <div class="form-group">
        <label>Pilihan Status Hak</label>
        <select  name="pillihan_status_hak" type="text" class="form-control" id="pillihan_status_hak">
            <option value="">Pilih Status Hak</option>
            </option>
            <?php foreach($pillihan_status_hak_ as $hak) : ?>
              <?php $select = $hak->code == $pillihan_status_hak ? 'selected' : '' ?>
              <option value="<?php echo $hak->code ?>" <?php echo $select ?>><?php echo $hak->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Nomor Kode Tanah</label>
        <input type="text" class="form-control" name="nomor_kode_tanah" id="nomor_kode_tanah" placeholder="Nomor Kode Tanah"  value="<?php
        if(set_value('nomor_kode_tanah')=="" && isset($nomor_kode_tanah)){
            echo $nomor_kode_tanah;
          }else{
            echo  set_value('nomor_kode_tanah');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Pilihan Kontruksi Tingkat</label>
        <select  name="pilihan_kons_tingkat" type="text" class="form-control" id="pilihan_kons_tingkat">
            <option value="">Pilih Kontruksi Tingkat</option>
            </option>
            <?php foreach($pilihan_kons_tingkat_ as $tingkat) : ?>
              <?php $select = $tingkat->code == $pilihan_kons_tingkat ? 'selected' : '' ?>
              <option value="<?php echo $tingkat->code ?>" <?php echo $select ?>><?php echo $tingkat->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Pilihan Kontruksi Beton</label>
        <select  name="pilihan_kons_beton" type="text" class="form-control" id="pilihan_kons_beton">
            <option value="">Pilihan Kontruksi Beton</option>
            </option>
            <?php foreach($pilihan_kons_beton_ as $beton) : ?>
              <?php $select = $beton->code == $pilihan_kons_beton ? 'selected' : '' ?>
              <option value="<?php echo $beton->code ?>" <?php echo $select ?>><?php echo $beton->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tanggal Dokumen</label>
        <div id='dokumen_tanggal' name="dokumen_tanggal" value="<?php
        echo (!empty($dokumen_tanggal)) ? date("Y-m-d",strtotime($dokumen_tanggal)) :  date("d-m-Y");
      ?>"></div>
      </div>
      <div class="form-group">
        <label>Nomor Dokumen</label>
        <input type="text" class="form-control" name="dokumen_nomor" id="dokumen_nomor" placeholder="Nomor Dokumen"  value="<?php
        if(set_value('dokumen_nomor')=="" && isset($dokumen_nomor)){
            echo $dokumen_nomor;
          }else{
            echo  set_value('dokumen_nomor');
          }
          ?>">
      </div>


      <?php  }else if($kodebarang_=='04') {?>
      
      <div class="form-group">
        <label>Kontruksi</label>
        <textarea class="form-control" id="konstruksi" name="konstruksi" placeholder="Kontruksi"><?php 
            if(set_value('konstruksi')=="" && isset($konstruksi)){
              echo $konstruksi;
            }else{
              echo  set_value('konstruksi');
            }
            ?></textarea>
      </div>
      <div class="form-group">
        <label>Panjang</label>
        <input type="number" class="form-control" name="panjang" id="panjang" placeholder="Panjang"  value="<?php
        if(set_value('panjang')=="" && isset($panjang)){
            echo $panjang;
          }else{
            echo  set_value('panjang');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Lebar</label>
        <input type="number" class="form-control" name="lebar" id="lebar" placeholder="Lebar"  value="<?php
        if(set_value('lebar')=="" && isset($lebar)){
            echo $lebar;
          }else{
            echo  set_value('lebar');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Luas</label>
        <input type="number" class="form-control" name="luas" id="luas1"  placeholder="Luas"  value="<?php
        if(set_value('luas')=="" && isset($luas)){
            echo $luas;
          }else{
            echo  set_value('luas');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Alamat Lokasi</label>
        <textarea class="form-control" id="letak_lokasi_alamat1" name="letak_lokasi_alamat" placeholder="Alamat Lokasi"><?php 
            if(set_value('letak_lokasi_alamat')=="" && isset($letak_lokasi_alamat)){
              echo $letak_lokasi_alamat;
            }else{
              echo  set_value('letak_lokasi_alamat');
            }
            ?></textarea>
      </div>
      <div class="form-group">
              <label>Tanggal Dokumen</label>
              <div id='dokumen_tanggal1' name="dokumen_tanggal" value="<?php
              echo (!empty($dokumen_tanggal)) ? date("Y-m-d",strtotime($dokumen_tanggal)) :  date("d-m-Y");
            ?>"></div>
            </div>
      <div class="form-group">
        <label>Nomor Dokumen</label>
        <input type="text" class="form-control" name="dokumen_nomor" id="dokumen_nomor1" placeholder="Nomor Dokumen"  value="<?php
        if(set_value('dokumen_nomor')=="" && isset($dokumen_nomor)){
            echo $dokumen_nomor;
          }else{
            echo  set_value('dokumen_nomor');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Pilihan Status Tanah</label>
        <select  name="pilihan_status_tanah" type="text" class="form-control" id="pilihan_status_tanah">
            <option value="">Pilihan Status Tanah</option>
            </option>
            <?php foreach($pilihan_status_tanah_ as $tanah) : ?>
              <?php $select = $tanah->code == $pilihan_status_tanah ? 'selected' : '' ?>
              <option value="<?php echo $tanah->code ?>" <?php echo $select ?>><?php echo $tanah->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Nomor Kode Tanah</label>
        <input type="text" class="form-control" name="nomor_kode_tanah" id="nomor_kode_tanah1" placeholder="Nomor Kode Tanah"  value="<?php
        if(set_value('nomor_kode_tanah')=="" && isset($nomor_kode_tanah)){
            echo $nomor_kode_tanah;
          }else{
            echo  set_value('nomor_kode_tanah');
          }
          ?>">
      </div>


      <?php  }else if($kodebarang_=='05') {?>
      
      <div class="form-group">
        <label>Judul Buku Pencipta</label>
        <input type="text" class="form-control" name="buku_judul_pencipta" id="buku_judul_pencipta" placeholder="Judul Buku Pencipta"  value="<?php
        if(set_value('buku_judul_pencipta')=="" && isset($buku_judul_pencipta)){
            echo $buku_judul_pencipta;
          }else{
            echo  set_value('buku_judul_pencipta');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Spesifikasi Buku</label>
        <textarea class="form-control" id="buku_spesifikasi" name="buku_spesifikasi" placeholder="Spesifikasi Buku"><?php 
            if(set_value('buku_spesifikasi')=="" && isset($buku_spesifikasi)){
              echo $buku_spesifikasi;
            }else{
              echo  set_value('buku_spesifikasi');
            }
            ?></textarea>
      </div>
      <div class="form-group">
        <label>Budaya Asal Daerah</label>
        <input type="text" class="form-control" name="budaya_asal_daerah" id="budaya_asal_daerah"  placeholder="Budaya Asal Daerah"  value="<?php
        if(set_value('budaya_asal_daerah')=="" && isset($budaya_asal_daerah)){
            echo $budaya_asal_daerah;
          }else{
            echo  set_value('budaya_asal_daerah');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Pencipta Budaya</label>
        <input type="text" class="form-control" name="budaya_pencipta" id="budaya_pencipta"  placeholder="Pencipta Budaya"  value="<?php
        if(set_value('budaya_pencipta')=="" && isset($budaya_pencipta)){
            echo $budaya_pencipta;
          }else{
            echo  set_value('budaya_pencipta');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Pilihan Budaya Bahan</label>
        <select  name="pilihan_budaya_bahan" type="text" class="form-control" id="pilihan_budaya_bahan">
            <option value="">Pilihan Budaya Bahan</option>
            </option>
            <?php foreach($pilihan_budaya_bahan_ as $bahan) : ?>
              <?php $select = $bahan->code == $pilihan_budaya_bahan ? 'selected' : '' ?>
              <option value="<?php echo $bahan->code ?>" <?php echo $select ?>><?php echo $bahan->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Jenis Flora dan Fauna</label>
        <input type="text" class="form-control" name="flora_fauna_jenis" id="flora_fauna_jenis" placeholder="Jenis Flora dan Fauna"  value="<?php
        if(set_value('flora_fauna_jenis')=="" && isset($flora_fauna_jenis)){
            echo $flora_fauna_jenis;
          }else{
            echo  set_value('flora_fauna_jenis');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Ukuran Flora dan Fauna</label>
        <input type="number" class="form-control" name="flora_fauna_ukuran" id="flora_fauna_ukuran"  placeholder="Ukuran Flora dan Fauna"  value="<?php
        if(set_value('buku_judul_pencipta')=="" && isset($flora_fauna_ukuran)){
            echo $flora_fauna_ukuran;
          }else{
            echo  set_value('flora_fauna_ukuran');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Pilihan Satuan</label>
        <select  name="pilihan_satuan" type="text" class="form-control" id="pilihan_satuan1">
            <option value="">Pilihan Status Tanah</option>
            </option>
            <?php foreach($pilihan_satuan_ as $satuan) : ?>
              <?php $select = $satuan->code == $pilihan_satuan ? 'selected' : '' ?>
              <option value="<?php echo $satuan->code ?>" <?php echo $select ?>><?php echo $satuan->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tahun Cetak Beli</label>
        <div id='tahun_cetak_beli' name="tahun_cetak_beli" value="<?php
        echo (!empty($tahun_cetak_beli)) ? date("Y-m-d",strtotime($tahun_cetak_beli)) :  date("d-m-Y");
      ?>"></div>
      </div>


      <?php  }else if($kodebarang_=='06') {?>
      
      <div class="form-group">
        <label>Bangunan</label>
        <input type="text" class="form-control" name="bangunan" id="bangunan" placeholder="Bangunan"  value="<?php
        if(set_value('bangunan')=="" && isset($bangunan)){
            echo $bangunan;
          }else{
            echo  set_value('bangunan');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Pilihan Kontruksi Bertingkat</label>
        <select  name="pilihan_konstruksi_bertingkat" type="text" class="form-control" id="pilihan_konstruksi_bertingkat">
            <option value="">Pilihan Kontruksi Bertingkat</option>
            </option>
            <?php foreach($pilihan_konstruksi_bertingkat_ as $tingkat) : ?>
              <?php $select = $tingkat->code == $pilihan_konstruksi_bertingkat ? 'selected' : '' ?>
              <option value="<?php echo $tingkat->code ?>" <?php echo $select ?>><?php echo $tingkat->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Pilihan Kontruksi Beton</label>
        <select  name="pilihan_konstruksi_beton" type="text" class="form-control" id="pilihan_konstruksi_beton">
            <option value="">Pilihan Kontruksi Beton</option>
            </option>
            <?php foreach($pilihan_konstruksi_beton_ as $beton) : ?>
              <?php $select = $beton->code == $pilihan_konstruksi_beton ? 'selected' : '' ?>
              <option value="<?php echo $beton->code ?>" <?php echo $select ?>><?php echo $beton->value ?></option>
            <?php endforeach ?>
        </select>
      </div>
      <div class="form-group">
        <label>Luas</label>
        <input type="text" class="form-control" name="luas"  placeholder="Luas" id="luas2" value="<?php
        if(set_value('luas')=="" && isset($luas)){
            echo $luas;
          }else{
            echo  set_value('luas');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Lokasi</label>
        <textarea class="form-control" id="lokasi" name="lokasi" id="lokasi" placeholder="Lokasi"><?php 
            if(set_value('lokasi')=="" && isset($lokasi)){
              echo $lokasi;
            }else{
              echo  set_value('lokasi');
            }
            ?></textarea>
      </div>
      <div class="form-group">
        <label>Tanggal Dokumen</label>
        <div id='dokumen_tanggal2' name="dokumen_tanggal" value="<?php
        echo (!empty($dokumen_tanggal)) ? date("Y-m-d",strtotime($dokumen_tanggal)) :  date("d-m-Y");
      ?>"></div>
      <div class="form-group">
        <label>Nomor Dokumen</label>
        <input type="text" class="form-control" name="dokumen_nomor" id="dokumen_nomor2" placeholder="Nomor Dokumen"  value="<?php
        if(set_value('dokumen_nomor')=="" && isset($dokumen_nomor)){
            echo $dokumen_nomor;
          }else{
            echo  set_value('dokumen_nomor');
          }
          ?>">
      </div>
      <div class="form-group">
        <label>Tanggal Mulai</label>
        <div id='tanggal_mulai' name="tanggal_mulai" value="<?php
        echo (!empty($tanggal_mulai)) ? date("Y-m-d",strtotime($tanggal_mulai)) :  date("d-m-Y");
      ?>"></div>
      <div class="form-group">
        <label>Pilihan Status Tanah</label>
        <select  name="pilihan_status_tanah" type="text" class="form-control" id="pilihan_status_tanah1">
            <option value="">Pilihan Status Tanah</option>
            </option>
            <?php foreach($pilihan_status_tanah_ as $tanah) : ?>
              <?php $select = $tanah->code == $pilihan_status_tanah ? 'selected' : '' ?>
              <option value="<?php echo $tanah->code ?>" <?php echo $select ?>><?php echo $tanah->value ?></option>
            <?php endforeach ?>
        </select>
      </div>

      <?php } ?>
<!--end from edit-->
    </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-close" class="btn btn-warning">Batal</button>
        </div>
    </div>
    </div>
    </div>
</form>
</div>
