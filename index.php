<?php
include_once 'assets/inc/Settings.php';

use Thedudeguy\Rcon;

?>
<!DOCTYPE html>
<html>
<head>
	<title>RCON - Yönetim Paneli</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script type="text/javascript" src="assets/js/sweetalert2.all.min.js"></script>
</head>
<body>
	<?php
	$rcon = new Rcon($host, $port, $password, $timeout);

	if (!$rcon->connect()) 
	{
		error_reporting(0);
		?>
		<div class="alert alert-danger" role="alert">
			Rcon Bağlantınızda bir sorun gözüküyor!
		</div>
		<?php
		exit;
	}
	if (empty($_GET)) 
	{
		?>	<div class="container">
			<div class="row">
				<div class="col text-center">
					<h1>Yapmak istediğiniz işlem nedir?</h1>
					<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							İşlem Seç
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" href="?command=">Sunucuya Komut Gönder</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else if (isset($_GET['command'])) 
	{
		?>	<div class="container">
			<div class="row">
				<div class="col text-center">
					<h1>Tekli Komut Gönderme</h1>
					<form action="" method="POST">
						<div class="input-group input-group-lg">
							<div class="input-group-prepend">
								<span class="input-group-text" id="inputGroup-sizing-lg">/</span>
							</div>
							<input type="text" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" placeholder="Buraya bir komut girebilirsiniz..." name="solocommand">&nbsp;&nbsp;
							<button type="submit" class="btn btn-primary" name="submit">Gönder</button>
						</div>
					</form>
					<br>
					<?php
					if (isset($_POST['submit'])) 
					{

						if (isset($_POST['solocommand'])) 
						{
							$komut = $_POST['solocommand'];
							if (!empty($komut)) 
							{
								$komutver = $rcon->sendCommand($komut);
								if (strstr($komutver, "Usage:")) 
								{
									echo "<script>Swal.fire({
										title: 'Bilgilendirme',
										text: 'Hatalı parametre girmiş olabilirsiniz!',
										icon: 'question',
										confirmButtonText: 'Tamam'
									})</script>";
									echo "<div class='alert alert-info' role='alert'>".$komutver."</div>";
								}
								else if (strstr($komutver, "Unknown command")) 
								{
									echo "<script>Swal.fire({
										icon: 'error',
										title: 'Hata',
										text: 'Sunucuda böyle bir komut yok!',
										confirmButtonText: 'Tamam'
									})</script>";
								}
								else
								{
									echo "<script>Swal.fire({
										icon: 'success',
										title: 'Başarılı',
										text: '/$komut Komutunu başarıyla gönderdiniz!',
										confirmButtonText: 'Tamam'
									})</script>";
									echo "<div class='alert alert-info' role='alert'>".$komutver."</div>";
								}
							}
							else
							{
								echo "<script>Swal.fire({
									icon: 'error',
									title: 'Hata',
									text: 'Boş komut gönderemezsiniz!',
									confirmButtonText: 'Tamam'
								})</script>";
							}
						}

					}
					?>
					<br>
					<div class="col text-center">
						<h1>Çoklu Komut Gönderme</h1>
						<form method="POST" action="">
							<div class="input-group input-group-lg">
								<div class="input-group-prepend">
									<span class="input-group-text" id="inputGroup-sizing-lg">/</span>
								</div>
								<input type="text" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" placeholder="Buraya bir komut girebilirsiniz..." name="multicommand">
								<div class="form-group col-2">
									<select class="form-control" name="komutmiktar" id="exampleFormControlSelect1">
										<?php
										for ($i=1; $i <= 100 ; $i++) 
										{ 
											echo "<option value='$i'>$i</option>";
										}
										?>
									</select>
								</div>
								<button type="submit" class="btn btn-primary" name="submit">Gönder</button>
							</form>
						</div>
						<br>
						<?php
						if (isset($_POST['submit'])) 
						{
							if (isset($_POST['multicommand'])) 
							{
								$komut = $_POST['multicommand'];
								$komutmiktar = $_POST['komutmiktar'];
								if (!empty($komut)) 
								{
									$komutver = $rcon->sendCommand($komut);
									if (strstr($komutver, "Usage:")) 
									{
										echo "<script>Swal.fire({
											title: 'Bilgilendirme',
											text: 'Hatalı parametre girmiş olabilirsiniz!',
											icon: 'question',
											confirmButtonText: 'Tamam'
										})</script>";
										echo "<div class='alert alert-info' role='alert'>".$komutver."</div>";
									}
									else if (strstr($komutver, "Unknown command")) 
									{
										echo "<script>Swal.fire({
											icon: 'error',
											title: 'Hata',
											text: 'Sunucuda böyle bir komut yok!',
											confirmButtonText: 'Tamam'
										})</script>";
									}
									else if ($komutmiktar > 100) {
										echo "<script>Swal.fire({
											icon: 'error',
											title: 'Hata',
											text: 'Maksimum 100 defa komut gönderebilirsiniz!',
											confirmButtonText: 'Tamam'
										})</script>";
									}
									else
									{
										for ($i=0; $i < $komutmiktar-1 ; $i++) 
										{ 
											$rcon->sendCommand($komut);
										}
										echo "<script>Swal.fire({
											icon: 'success',
											title: 'Başarılı',
											text: '/$komut Komutunu başarıyla $komutmiktar defa gönderdiniz!',
											confirmButtonText: 'Tamam'
										})</script>";
										echo "<div class='alert alert-info' role='alert'><b>".$komutmiktar."X"."</b> ".$komutver."</div>";
									}
								}
								else
								{
									echo "<script>Swal.fire({
										icon: 'error',
										title: 'Hata',
										text: 'Boş komut gönderemezsiniz!',
										confirmButtonText: 'Tamam'
									})</script>";
								}
							}
						}
						?>
					</div>
					<a href="index.php"><button type="button" class="btn btn-warning">Geri Dön</button></a>
				</div><br>

			</div>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="container">
			<div class="row">
				<div class="col text-center">
					<h1>Error!</h1>
					<div class="alert alert-danger" role="alert">
						Bir hata meydana geldi maalesef burayı görüntüleme iznin yok!
					</div>
					<a href="index.php"><button type="button" class="btn btn-warning">Geri Dön</button></a>
				</div>
			</div>
		</div>
		<?php
	}

	?>
	<!--Begin Body-->

	<!--End Body-->

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>