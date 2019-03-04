
		<div class="span12 pass">
			<?php
			if ($aksi=="gantipassword"){
				$subjudul = "Ganti Password";
				$nama_tombol = "b_simpan";
				$value_tombol = "Save";
				$value_class = "btn btn-primary btn-small";
				$catatan = "";
			}elseif ($aksi=="loginpertama"){
				$subjudul = "Ganti Password Login Pertama";
				$nama_tombol = "b_simpan";
				$value_tombol = "Save";
				$value_class = "btn btn-warning btn-small";
				$catatan = "";
			}
			?>
			<form class="form-horizontal" method="post" action="" name="form_user">
			<fieldset>
				<legend><?php echo $subjudul; ?></legend>	
						<div class="control-group">
							<label class="control-label" for="f_password_lama">Password Lama</label>
							<div class="controls">
								<input type="password" name="f_password_lama" id="f_password_lama" 
								value="<?php echo set_value("f_password_lama");?>"/>
								<?php echo form_error("f_password_lama"); ?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="f_password_baru">Password Baru</label>
							<div class="controls">
								<input type="password" name="f_password_baru" id="f_password_baru" value="<?php echo set_value("f_password_baru");?>" />
								<?php echo form_error("f_password_baru"); ?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="f_password_barux">Ulangi Password Baru</label>
							<div class="controls">
								<input type="password" name="f_password_barux" id="f_password_barux" value="<?php echo set_value("f_password_barux");?>" />
								<?php echo form_error("f_password_barux"); ?>
							</div>
						</div>
						
			<!--- tombol -->
				<div class="control-group">
					<label class="control-label" for="tabel"></label>
					<div class="controls">
						<?php echo $catatan;?>
						<button type="submit" name="<?php echo $nama_tombol;?>" value="<?php echo $value_tombol;?>" class="<?php echo $value_class;?>"/><i class="icon-envelope icon-white"></i> <?php echo $value_tombol;?></button>
						<button type="reset" value="Reset" class="<?php echo $value_class;?>"><i class="icon-refresh icon-white"></i> Reset</button>
					</div>
				</div>
			</fieldset>
			</form>
		</div>