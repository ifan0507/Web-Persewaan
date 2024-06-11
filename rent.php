<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<?php
require "admin/conn.php";

$sukses_insert = false;
if (isset($_POST['pesan'])) {
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $no_hp = $_POST['no_hp'];
  $tanggal_transaksi = $_POST['tanggal'];
  $id_dekorasi = $_POST['id_dekorasi'];
  $waktu_sewa = $_POST['val_waktu_sewa'];
  $total_harga = $_POST['val_total_harga'];
  $htg_tgl = strtotime($tanggal_transaksi) + (intval($waktu_sewa) * 86400);
  $tgl_kembali = date('Y-m-d', $htg_tgl);

  $q1 = mysqli_query($db, "INSERT INTO tbl_customer(`nama`,`alamat`,`no_hp`,`is_deleted`) VALUES ('$nama', '$alamat', '$no_hp','0')");
  $id_customer = mysqli_insert_id($db);
  $s2 = mysqli_query($db, "INSERT INTO tbl_transaksi(`id_transaksi`,`tanggal_transaksi`,`id_customer`,`id_dekorasi`,`waktu_sewa`,`tgl_kembali`,`total_harga`,`lunas`) VALUES (null,'$tanggal_transaksi', '$id_customer', '$id_dekorasi', '$waktu_sewa','$tgl_kembali' ,'$total_harga', '0')");
  $q3 = mysqli_query($db, "UPDATE tbl_dekorasi SET is_ready = '1' WHERE id_dekorasi = '$id_dekorasi'");
  unset($_POST);

  if ($s2) {
    $sukses_insert = true;
  } else {
    echo mysqli_error($db);
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="author" content="Untree.co" />
  <link rel="shortcut icon" href="favicon.png" />

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/tiny-slider.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/all.min.css" />
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/all.min.js"></script>
  <script src="js/sweetalert2.all.min.js"></script>
  <title>Amanah Decoration</title>
</head>

<body>
  <!-- Start Header/Navigation -->
  <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
      <a class="navbar-brand" href="index.php">Amanah Deco<span>.</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="active"><a class="nav-link" href="rent.php">Rent</a></li>
          <li><a class="nav-link" href="galery.php">Galery</a></li>

          <li><a class="nav-link" href="contact.php">Contact us</a></li>
        </ul>

        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
          <li>
            <a class="nav-link" href="login.php"><img src="images/user.svg" /></a>
          </li>
          <li>
            <a class="nav-link" href="rent.php"><img src="images/cart.svg" /></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Header/Navigation -->

  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Available Rental</h1>
          </div>
        </div>
        <div class="col-lg-7"></div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <div class="untree_co-section product-section before-footer-section">
    <div class="container">
      <div class="row">
        <!-- Start Column 1 -->
        <?php
        $query = mysqli_query($db, "SELECT * FROM tbl_dekorasi WHERE is_deleted = '0' ORDER BY id_dekorasi");
        if (mysqli_num_rows($query) > 0) :
          while ($data = mysqli_fetch_assoc($query)) : ?>

            <div class="col-12 col-md-4 col-lg-3 mb-5">
              <div class="product-item" onclick="v_data(<?= $data['id_dekorasi']; ?>, '<?= $data['gambar']; ?>', '<?= $data['paket']; ?>', <?= $data['harga']; ?>,'<?= $data['is_ready'] ?>');">
                <img src="admin/img/<?= $data['gambar']; ?>" class="img-fluid product-thumbnail" />
                <h3 class="product-title">paket <?= $data['paket']; ?></h3>
                <strong class="product-price"><?= $data['harga']; ?></strong>
                <?php
                if ($data['is_ready'] == '1') : ?>
                  <h3 class="product-title" style="font-style: italic; color: red;">Sedang Disewa</h3>
                <?php endif;   ?>

                <span class="icon-cross">
                  <img src="images/cross.svg" class="img-fluid" />
                </span>
              </div>
            </div>
        <?php
          endwhile;
        endif; ?>
        <!-- End Column 1 -->
      </div>
    </div>
  </div>


  <!-- modal pesan -->

  <div class="modal fade" id="modal-tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Transaksi Persewaan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <div id="gambar"></div>
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Paket Dekorasi</label>
                  <input type="text" name="paket" class="form-control" id="paket" readonly>
                  <input type="hidden" name="id_dekorasi" id="id_dekorasi">
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Harga</label>
                  <input type="text" name="harga" class="form-control" id="harga" readonly>
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Tanggal Sewa</label>
                  <input type="date" name="tanggal" class="form-control" id="tanggal">
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Waktu Sewa</label>
                  <input type="hidden" class="form-control" name="val_id_customer" id="val_id_customer" />
                  <input type="hidden" class="form-control" name="val_id_transaksi" id="val_id_transaksi" />
                  <input type="text" name="val_waktu_sewa" class="form-control" id="val_waktu_sewa" onkeyup="hitung2();" placeholder="masukan waktu sewa">
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Total Harga</label>
                  <input type="hidden" name="val_total_harga1" class="form-control" id="val_total_harga1" value="0">
                  <input type="text" name="val_total_harga" class="form-control" id="val_total_harga" value="0">
                </div>
              </div>
              <div class="col">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama" class="form-control" id="nama">
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Alamat</label>
                  <textarea name="alamat" id="alamat" style="resize: none;" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">No Handpone</label>
                  <input type="text" name="no_hp" class="form-control" id="no_hp">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" name="pesan" class="btn btn-success">Pesan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>



  <!-- Start Footer Section -->
  <footer class="footer-section">
    <div class="container relative">
      <div class="sofa-img">
        <img src="images/canva2.png" alt="Image" class="img-fluid" />
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="subscription-form">
            <h3 class="d-flex align-items-center">
              <span class="me-1"><img src="images/envelope-outline.svg" alt="Image" class="img-fluid" /></span><span>Subscribe to Newsletter</span>
            </h3>

            <form action="#" class="row g-3">
              <div class="col-auto">
                <input type="text" class="form-control" placeholder="Enter your name" />
              </div>
              <div class="col-auto">
                <input type="email" class="form-control" placeholder="Enter your email" />
              </div>
              <div class="col-auto">
                <button class="btn btn-primary">
                  <span class="fa fa-paper-plane"></span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="row g-5 mb-5">
        <div class="col-lg-4">
          <div class="mb-4 footer-logo-wrap">
            <a href="#" class="footer-logo">Amanah Deco<span>.</span></a>
          </div>
          <p class="mb-4">WE PROVIDE THE BEST DECORATION FOR YOU | Yuk sewa dekorasi, di.. Amanah!</p>

          <ul class="list-unstyled custom-social">
            <li>
              <a href="#"><span class="fa fa-brands fa-facebook-f"></span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-brands fa-twitter"></span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-brands fa-instagram"></span></a>
            </li>
            <li>
              <a href="#"><span class="fa fa-brands fa-linkedin"></span></a>
            </li>
          </ul>
        </div>

        <div class="col-lg-8">
          <div class="row links-wrap">
            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">About us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Contact us</a></li>
              </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Support</a></li>
                <li><a href="#">Knowledge base</a></li>
                <li><a href="#">Live chat</a></li>
              </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Jobs</a></li>
                <li><a href="#">Our team</a></li>
                <li><a href="#">Leadership</a></li>
                <li><a href="#">Privacy Policy</a></li>
              </ul>
            </div>

            <div class="col-6 col-sm-6 col-md-3">
              <ul class="list-unstyled">
                <li><a href="#">Wedding</a></li>
                <li><a href="#">Graduation</a></li>
                <li><a href="#">Birthday</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="border-top copyright">
        <div class="row pt-4">
          <div class="col-lg-6">
            <p class="mb-2 text-center text-lg-start">
              Copyright &copy;
              <script>
                document.write(new Date().getFullYear());
              </script>
              . All Rights Reserved.
            </p>
          </div>

          <div class="col-lg-6 text-center text-lg-end">
            <ul class="list-unstyled d-inline-flex ms-auto">
              <li class="me-4"><a href="#">Terms &amp; Conditions</a></li>
              <li><a href="#">Privacy Policy</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer Section -->

  <script>
    function v_data(a, b, c, d, f) {
      if (f == '1') {
        Swal.fire("Maaf product sudah disewa!");
      } else {
        $('#modal-tambah').modal('show');
        $('#gambar').html('<img src="admin/img/' + b + '" class="img-fluid product-thumbnail" />');
        $('#paket').val(c);
        $('#harga').val(d);
        $('#id_dekorasi').val(a);
      }
    }

    function hitung() {
      var harga = $('#harga').val();
      var waktu = $('#val_waktu_sewa').val();
      var total = parseInt(harga) * parseInt(waktu);
      if (!waktu) {
        $('#val_total_harga').val(harga);
      } else {
        $('#val_total_harga').val(total);
      }
    }


    function hitung2() {
      var harga = $('#harga').val();
      var waktu = $('#val_waktu_sewa').val();
      var total = parseInt(harga) * parseInt(waktu);
      if (!waktu) {
        $('#val_total_harga').val(harga);
      } else {
        $('#val_total_harga').val(total);
      }

      $("#waktu_sewa").on('change', function() {
        var harga = $('#harga').val();
        var waktu = $('#waktu_sewa').val();
        var total = parseInt(harga) * parseInt(waktu);
        if (!waktu) {
          $('#val_total_harga').val(harga);
        } else {
          $('#val_total_harga').val(total);
        }
      });
    }



    $("#val_id_dekorasi").on('change', function() {
      var harga = $('#harga');
      var waktu = $('#val_waktu_sewa').val();
      var kali = parseInt(harga) * parseInt(waktu);

      $('#val_total_harga1').val(harga);
      $('#val_total_harga').val(kali);
    });

    <?php if ($sukses_insert) { ?>
      Swal.fire('Sukses', 'Data berhasil disimpan !', 'success');
    <?php } ?>
  </script>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/tiny-slider.js"></script>
  <script src="js/custom.js"></script>
</body>

</html>