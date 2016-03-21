<script type="text/javascript">
    $(document).ready(function(){
        <?php
		  	 if(isset($data_profile) and $data_profile!="salah"){
			    foreach($data_profile as $row_print){?>
			    	var kode_print = "<?php echo $row_print->kode;?>";
			    	var value_print= "<?php echo $row_print->value; ?>";
                    if(kode_print.slice(-5)=="radio"){
			    		if(value_print=="1"){
			    	       $("#<?php echo $row_print->kode."_print_ya";?>").html("&#10003;");
                    	}else{
			    		   $("#<?php echo $row_print->kode."_print_tidak";?>").html("&#10003;");
			    		}
			    	}else{
				        $("#<?php echo $row_print->kode."_print";?>").html("<?php echo $row_print->value; ?>");
                	}
		<?php
		   	 }
		    }
	    ?>
    });
</script>
<style>
	#print-modal{
		width:85%;
        left: 42%;
	}
</style>
<div class="box box-success">
  <div class="box-body">
    <div class="div-grid">
            <table width="100%" border="0" cellpadding='2' cellspacing='2' style="font-size:12px;">
                <tr>
                    <td width='20%'><span style="font-size: 14px;font-weight: bold;background: #BFBFBF;border: 1px solid black;padding: 2px;">C. DATA KELUARGA</span></td>
                    <td width='40%'><span style="font-weight: bold;">Tanggal Pengisian (HHBBTTTT)</span> <?php echo date('dmY',strtotime($tanggal_pengisian)); ?></td>
                    <td width='40%'><span style="font-weight: bold;">Jam Mulai Mendata</br>(JJ:MM)</span> <?php echo date('H:i',strtotime($jam_data)); ?></td>
                </tr>
                <tr>
                    <td width='15%' style="font-weight: bold;">&nbsp;</td>
                    <td width='85%' colspan="2" style="text-align: left;">
                        <table width='100%' border='0' cellpadding='2' cellspacing='2' style="font-size:12px;">
                            <tr>
                                <td style="font-weight: bold;text-decoration: underline;">Propinsi</td>
                                <td style="font-weight: bold;text-decoration: underline;">Kab/Kota</td>
                                <td style="font-weight: bold;text-decoration: underline;">Kec.</td>
                                <td style="font-weight: bold;text-decoration: underline;">Desa/Kelurahan</td>
                                <td style="font-weight: bold;text-decoration: underline;">Dusun/RW</td>
                                <td style="font-weight: bold;text-decoration: underline;">RT</td>
                                <td style="font-weight: bold;text-decoration: underline;">No. Rumah/Rumah Tangga</td>
                                <td style="font-weight: bold;text-decoration: underline;">No. Urut Keluarga</td>
                                <td style="font-weight: bold;text-decoration: underline;">Kode Pos</td>
                            </tr>
                            <tr>
                                <td>{id_propinsi}</td>
                                <td>{id_kota}</td>
                                <td>{id_kecamatan}</td>
                                <td>{id_desa}</td>
                                <td>{rw}</td>
                                <td>{rt}</td>
                                <td>{norumah}</td>
                                <td>{nourutkel}</td>
                                <td>{id_kodepos}</td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width='15%' style="font-weight: bold;">Alamat</td>
                    <td width='85%' colspan="2" style="text-align: left;text-decoration: underline;">{alamat}</td>
                </tr>
                <tr>
                    <td width='15%' style="font-weight: bold;">Nama Komunitas</td>
                    <td width='85%' colspan="2" style="text-align: left;text-decoration: underline;">{nama_komunitas}</td>
                </tr>
                <tr>
                    <td width='15%' style="font-weight: bold;">Nama Kepala Rumah Tangga</td>
                    <td width='85%' colspan="2" style="text-align: left;text-decoration: underline;">{namakepalakeluarga}</td>
                </tr>
                <tr>
                    <td width='15%' style="font-weight: bold;">No. HP / Telepon</td>
                    <td width='85%' colspan="2" style="text-align: left;text-decoration: underline;">{notlp}</td>
                </tr>
                <tr>
                    <td width='15%' style="font-weight: bold;">Nama Dasa Wisma</td>
                    <td width='85%' colspan="2" style="text-align: left;text-decoration: underline;">{namadesawisma}</td>
                </tr>
                <tr>
                    <td width='15%' style="font-weight: bold;">Jabatan Struktural TP PKK</td>
                    <td width='85%' colspan="2" style="text-align: left;text-decoration: underline;"><?php echo $jabatan_pkk['value']; ?></td>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding='2' cellspacing='2' style="font-size:12px;margin-top: 5px;">
                <tr>
                    <td colspan="2"><span style="font-size: 14px;font-weight: bold;background: #BFBFBF;border: 1px solid black;padding: 2px;">D. PROFILE KELUARGA</span></td>
                </tr>
                <tr>
                    <td valign='top' width='50%'>
                        <table width="100%" border="1" cellpadding='2' cellspacing='2' style="border-collapse: collapse;font-size:12px;">
                            <tr bgcolor="#BFBFBF">					
            					<td rowspan="2" style="font-size:12px;font-weight: bold;width: 3%;" align='center'>A</td>			
            					<td rowspan="2" style="font-size:12px;font-weight: bold;width: 33%;">Kondisi Keluarga dan Rumah</td>
            					<td colspan="2" style="font-size:12px;font-weight: bold;width: 24%;" align='center'>Ada ?</td>
            					<td rowspan="2" colspan="2" style="font-size:12px;font-weight: bold;width: 40%;" align='center'>Catatan</td>
            				</tr>
            				<tr bgcolor="#BFBFBF">
            					<td align='center' width='5%' style="font-size:12px;font-weight: bold;">Iya</td>
            					<td align='center' width='5%' style="font-size:12px;font-weight: bold;">Tidak</td>
            				</tr>
            				<tr>
            					<td rowspan="6" align='center' valign='top' style="font-size:12px;">1</td>
            					<td style="font-size:12px;">Makanan pokok sehari-hari</td>
            					<td>&nbsp;</td>
            					<td>&nbsp;</td>
            					<td colspan="2">&nbsp;</td>
            				</tr>
            				<tr>
            					<td style="font-size:12px;">a. Beras</td>
            					<td align='center' id="profile_a_1_a_radio_print_ya"></td>
            					<td align='center' id="profile_a_1_a_radio_print_tidak"></td>
            					<td colspan="2" style="font-size:12px;" id="profile_a_1_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td style="font-size:12px;">b. Non Beras</td>
            					<td align='center' id="profile_a_1_b_radio_print_ya"></td>
            					<td align='center' id="profile_a_1_b_radio_print_tidak"></td>
            					<td colspan="2" style="font-size:12px;" id="profile_a_1_b_catatan_print"></td>
            				</tr>
            				<tr>
            					<td><table width='100%'><tr><td width='5%'>*.</td><td width='95%' style="font-size:12px;" id="profile_a_1_c_print"> </td></tr></table></td>
            					<td align='center' id="profile_a_1_pilihan1_radio_print_ya"></td>
            					<td align='center' id="profile_a_1_pilihan1_radio_print_tidak"></td>
            					<td colspan="2" style="font-size:12px;" id="profile_a_1_pilihan1_catatan_print"></td>
            				</tr>
            				<tr>
            					<td><table width='100%'><tr><td width='5%'>*.</td><td width='95%' style="font-size:12px;" id="profile_a_1_d_print"> </td></tr></table></td>
            					<td align='center' id="profile_a_1_pilihan2_radio_print_ya"></td>
            					<td align='center' id="profile_a_1_pilihan2_radio_print_tidak"></td>
            					<td colspan="2" style="font-size:12px;" id="profile_a_1_pilihan2_catatan_print"></td>
            				</tr>
            				<tr>
            					<td><table width='100%'><tr><td width='5%'>*.</td><td width='95%' style="font-size:12px;" id="profile_a_1_e_print"> </td></tr></table></td>
            					<td align='center' id="profile_a_1_pilihan3_radio_print_ya"></td>
            					<td align='center' id="profile_a_1_pilihan3_radio_print_tidak"></td>
            					<td colspan="2" style="font-size:12px;" id="profile_a_1_pilihan3_catatan_print"></td>
            				</tr>
            				<tr>
            					<td rowspan="5" align='center' valign='top' style="font-size:12px;">2</td>
            					<td style="font-size:12px;">Sumber Air keluarga</td>
            					<td></td>
            					<td></td>
            					<td bgcolor="#BFBFBF" align='center' style="font-size:12px;font-weight: bold;">Jumlah</td>
            					<td bgcolor="#BFBFBF" align='center' style="font-size:12px;font-weight: bold;">Catatan</td>
            				</tr>
            				<tr>
            					<td style="font-size:12px;">a. Pam / Ledeng / Kemasan</td>
            					<td align='center' id="profile_a_2_a_radio_print_ya"></td>
            					<td align='center' id="profile_a_2_a_radio_print_tidak"></td>
            					<td style="font-size:12px;text-align: center;" id="profile_a_2_a_jumlah_print"></td>
            					<td style="font-size:12px;" id="profile_a_2_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td style="font-size:12px;">b. Sumur Terlindung</td>
            					<td align='center' id="profile_a_2_b_radio_print_ya"></td>
            					<td align='center' id="profile_a_2_b_radio_print_tidak"></td>
            					<td style="font-size:12px;text-align: center;" id="profile_a_2_b_jumlah_print"></td>
            					<td style="font-size:12px;" id="profile_a_2_b_catatan_print"></td>
            				</tr>
            				<tr>
            					<td style="font-size:12px;">c. Air Hujan/ Air Sungai</td>
            					<td align='center' id="profile_a_2_c_radio_print_ya"></td>
            					<td align='center' id="profile_a_2_c_radio_print_tidak"></td>
            					<td style="font-size:12px;text-align: center;" id="profile_a_2_c_jumlah_print"></td>
            					<td style="font-size:12px;" id="profile_a_2_c_catatan_print"></td>
            				</tr>
            				<tr>
            					<td><table width='100%'><tr><td style="font-size:12px;">d. Lainnya</td><td style="font-size:12px;" id="profile_a_2_d_lainnya_print"> </td></tr></table></td>
            					<td align='center' id="profile_a_2_d_radio_print_ya"></td>
            					<td align='center' id="profile_a_2_d_radio_print_tidak"></td>
            					<td style="font-size:12px;text-align: center;" id="profile_a_2_d_jumlah_print"></td>
            					<td style="font-size:12px;" id="profile_a_2_d_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center' valign='top' style="font-size:12px;">3</td>
            					<td style="font-size:12px;">Jembatan Keluarga</td>
            					<td align='center' id="profile_a_3_a_radio_print_ya"></td>
            					<td align='center' id="profile_a_3_a_radio_print_tidak"></td>
            					<td style="font-size:12px;text-align: center;" id="profile_a_3_a_jumlah_print"></td>
            					<td style="font-size:12px;" id="profile_a_3_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center' valign='top' style="font-size:12px;">4</td>
            					<td style="font-size:12px;">Saluran Pembuangan Sampah</td>
            					<td align='center' id="profile_a_4_a_radio_print_ya"></td>
            					<td align='center' id="profile_a_4_a_radio_print_tidak"></td>
            					<td style="font-size:12px;text-align: center;" id="profile_a_4_a_jumlah_print"></td>
            					<td style="font-size:12px;" id="profile_a_4_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center' valign='top' style="font-size:12px;">5</td>
            					<td style="font-size:12px;">Saluran Pembuangan Air Limbah</td>
            					<td align='center' id="profile_a_5_a_radio_print_ya"></td>
            					<td align='center' id="profile_a_5_a_radio_print_tidak"></td>
            					<td style="font-size:12px;text-align: center;" id="profile_a_5_a_jumlah_print"></td>
            					<td style="font-size:12px;" id="profile_a_5_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center' valign='top' style="font-size:12px;">6</td>
            					<td style="font-size:12px;">Menempel Stiker P4K</td>
            					<td align='center' id="profile_a_6_a_radio_print_ya"></td>
            					<td align='center' id="profile_a_6_a_radio_print_tidak"></td>
            					<td style="font-size:12px;text-align: center;" id="profile_a_6_a_jumlah_print"></td>
            					<td style="font-size:12px;" id="profile_a_6_a_catatan_print"></td>
            				</tr>
                            </table>
                            
                    
                    </td>
                    <td valign='top' width='50%'>
                        <table width="100%" border="1" cellpadding='2' cellspacing='2' style="border-collapse: collapse;font-size: 12px;">
            				<tr bgcolor="#BFBFBF">
            					<td rowspan="2" align='center' style="font-size:12px;font-weight: bold;width: 4%;">B</td>
            					<td rowspan="2" style="font-size:12px;font-weight: bold;width: 38%;">Kegiatan Keluarga</td>
            					<td colspan="2" align='center' style="font-size:12px;font-weight: bold;width: 15%;">Ada ?</td>
            					<td rowspan="2" colspan="2" align='center' style="font-size:12px;font-weight: bold;width: 43%;">Catatan</td>
            				</tr>
            				<tr bgcolor="#BFBFBF"> 
            					<td align='center' style="font-size:12px;font-weight: bold;">Iya</td>
            					<td align='center' style="font-size:12px;font-weight: bold;">Tidak</td>
            				</tr>
            				<tr>
            					<td align='center'>1.</td>
            					<td>UP2K</td>
            					<td align='center' id="profile_b_1_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_1_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_1_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>2.</td>
            					<td>Usaha Kesehatan Lingkungan</td>
            					<td align='center' id="profile_b_2_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_2_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_2_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>3.</td>
            					<td>Penghayatan Kesehatan Lingkungan</td>
            					<td align='center' id="profile_b_3_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_3_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_3_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>4.</td>
            					<td>Kerja Bakti</td>
            					<td align='center' id="profile_b_4_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_4_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_4_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>5.</td>
            					<td>Rukun Kematian</td>
            					<td align='center' id="profile_b_5_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_5_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_5_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>6.</td>
            					<td>Keagamaan</td>
            					<td align='center' id="profile_b_6_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_6_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_6_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>7.</td>
            					<td>Jimpitan</td>
            					<td align='center' id="profile_b_7_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_7_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_7_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>8.</td>
            					<td>Arisan</td>
            					<td align='center' id="profile_b_8_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_8_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_8_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>9.</td>
            					<td>Koperasi</td>
            					<td align='center' id="profile_b_9_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_9_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_9_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td align='center'>10.</td>
            					<td>Lainnya</td>
            					<td align='center' id="profile_b_10_a_radio_print_ya"></td>
            					<td align='center' id="profile_b_10_a_radio_print_tidak"></td>
            					<td colspan="2" id="profile_b_10_a_catatan_print"></td>
            				</tr>
            				<tr bgcolor="#BFBFBF">
            					<td rowspan="2" align='center' style="font-size:12px;font-weight: bold;">C</td>
            					<td rowspan="2" style="font-size:12px;font-weight: bold;">Ekonomi</td>
            					<td colspan="2" align='center' style="font-size:12px;font-weight: bold;">Tersedia ?</td>
            					<td rowspan="2" colspan="2" align='center' style="font-size:12px;font-weight: bold;">Catatan</td>
            				</tr>
            				<tr bgcolor="#BFBFBF">
            					<td align='center' style="font-size:12px;font-weight: bold;">Iya</td>
            					<td align='center' style="font-size:12px;font-weight: bold;">Tidak</td>
            				</tr>
            				<tr>
            					<td align='center'>1.</td>
            					<td>Rata-rata Pendapatan Perbulan</td>
                                <td colspan="2" align='center'>Jumlah</td>
            					<td colspan="2" id="profile_c_1_a_jumlah_print"></td>
            				</tr>
            				<tr>
            					<td rowspan="4" align='center' valign='top'>2</td>
            					<td>Sumber Pendapatan</td>
            					<td></td>
            					<td></td>
            					<td  bgcolor="#BFBFBF" align='center' style="font-size:12px;font-weight: bold;">Persen</td>
            					<td  bgcolor="#BFBFBF" align='center' style="font-size:12px;font-weight: bold;">Catatan</td>
            				</tr>
            				<tr>
            					<td>a. Pekerjaan</td>
            					<td align='center' id="profile_c_2_a_radio_print_ya"></td>
            					<td align='center' id="profile_c_2_a_radio_print_tidak"></td>
            					<td style="text-align: center;" id="profile_c_2_a_jumlah_print"></td>
            					<td id="profile_c_2_a_catatan_print"></td>
            				</tr>
            				<tr>
            					<td>b. Sumbangan</td>
            					<td align='center' id="profile_c_2_b_radio_print_ya"></td>
            					<td align='center' id="profile_c_2_b_radio_print_tidak"></td>
            					<td style="text-align: center;" id="profile_c_2_b_jumlah_print"></td>
            					<td id="profile_c_2_b_catatan_print"></td>
            				</tr>
            				<tr>
            					<td>c. Lainnya</td>
            					<td align='center' id="profile_c_2_c_radio_print_ya"></td>
            					<td align='center' id="profile_c_2_c_radio_print_tidak"></td>
            					<td style="text-align: center;" id="profile_c_2_c_jumlah_print"></td>
            					<td id="profile_c_2_c_catatan_print"></td>
            				</tr>
                       </table>
                    </td>
                </tr>
			</table>
	</div>
  </div>
</div>