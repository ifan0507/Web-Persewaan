<?php
$tambah = false;
if (isset($_POST['tambah'])) {
    $tipe_dekorasi = $_POST['tipe_dekorasi'];
    $paket = $_POST['paket'];
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp_name, "img/" . $gambar);
    $harga = $_POST['harga'];

    $query = "INSERT INTO tbl_dekorasi (tipe, paket, gambar, harga) VALUES ('$tipe_dekorasi', '$paket', '$gambar', '$harga')";
    $sql = mysqli_query($db, $query);

    if ($sql) {
        $tambah = true;
    } else {
        echo mysqli_error($db);
    }
}

$edit = false;
if (isset($_POST['edit'])) {
    $e_idd = $_POST['id_dekorasi'];
    $e_tipe = $_POST['e_tipe_dekorasi'];
    $e_paket = $_POST['e_paket'];
    $e_gambar = $_FILES['gambar_edit']['name'];
    $e_tmp_name = $_FILES['gambar_edit']['tmp_name'];
    $gbr = 'paket-' . $e_paket . "-" . date('Y-m-d H-i-s') . ".jpg";
    $vgbr = $_POST['gambar_ori'];
    move_uploaded_file($e_tmp_name, "img/" . $gbr);
    $e_harga = $_POST['e_harga'];
    unlink("img/" . $vgbr);

    $qe = mysqli_query($db, "SELECT * FROM tbl_transaksi WHERE id_dekorasi = '$e_idd' AND lunas = '0'");
    if (mysqli_num_rows($qe) > 0) {
        echo "<script>Swal.fire('Oops', 'Masih ada transaksi berjalan!', 'error');</script>";
        $edit = false;
    } else {
        $query = mysqli_query($db, "UPDATE tbl_dekorasi SET tipe='$e_tipe', paket='$e_paket', gambar='$gbr', harga='$e_harga' WHERE id_dekorasi='$e_idd'");
        if ($query) {
            $edit = true;
        } else {
            echo mysqli_error($db);
        }
    }
} else {
    echo mysqli_error($db);
}

$hapus = false;
if (isset($_POST['hapus'])) {
    $hapus = true;
    $id = $_POST['h_id_dekorasi'];

    $q1 = mysqli_query($db, "SELECT * FROM tbl_transaksi WHERE id_dekorasi = '$id' AND lunas = '0'");
    if (mysqli_num_rows($q1) > 0) {
        echo "<script>Swal.fire('Oops', 'Masih ada transaksi berjalan!', 'error');</script>";
        $hapus = false;
    } else {
        $query = mysqli_query($db, "UPDATE tbl_dekorasi SET is_deleted = '1' WHERE id_dekorasi= '$id'");
        if ($query) {
            $hapus = true;
        } else {
            echo mysqli_error($db);
        }
    }
} else {
    echo mysqli_error($db);
}
?>
<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var nilai = $(this).val().toLowerCase();
            $('#list tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(nilai) > -1);
            });
        });
    });

    <?php if ($tambah) { ?>
        Swal.fire('Sukses', 'Data berhasil disimpan !', 'success');
    <?php } ?>

    <?php if ($hapus) : ?>
        Swal.fire('Hapus', 'Data berhasil dihapus !', 'success');
        //     const swalWithBootstrapButtons = Swal.mixin({
        //         customClass: {
        //             confirmButton: "btn btn-success",
        //             cancelButton: "btn btn-danger"
        //         },
        //         buttonsStyling: false
        //     });
        //     swalWithBootstrapButtons.fire({
        //         title: "Are you sure?",
        //         text: "You won't be able to revert this!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonText: "Yes, delete it!",
        //         cancelButtonText: "No, cancel!",
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             <?php
                        //             $id = $_POST['h_id_dekorasi'];
                        //             $query = mysqli_query($db, "DELETE FROM tbl_dekorasi WHERE id_dekorasi= '$id'");
                        //             if ($query) { 
                        ?>
        //                 swalWithBootstrapButtons.fire({
        //                     title: "Hapus",
        //                     text: "Data berhasil dihapus!",
        //                     icon: "success"
        //                 });
        //             <?php //} else {
                        //                 echo mysqli_error($db);
                        //             }
                        //             
                        ?>
        //         } else if (
        //             /* Read more about handling dismissals below */
        //             result.dismiss === Swal.DismissReason.cancel
        //         ) {
        //             swalWithBootstrapButtons.fire({
        //                 title: "Cancelled",
        //                 text: "Your imaginary file is safe :)",
        //                 icon: "error"
        //             });
        //         }
        //     });
    <?php endif; ?>

    <?php if ($edit) : ?>
        Swal.fire('Sukses', 'Data berhasil diedit !', 'success');
    <?php endif; ?>


    function edit1(element) {
        var id_dekorasi = $(element).closest('tr').find('td').eq(1).text();
        var tipe = $(element).closest('tr').find('td').eq(2).text();
        var paket = $(element).closest('tr').find('td').eq(3).text();
        var gambar = $(element).closest('tr').find('td').eq(5).find('img').attr('src');
        var harga = $(element).closest('tr').find('td').eq(6).text();
        var vgbr = $(element).closest('tr').find('td').eq(7).text();

        var modal = $('#modal-edit');
        modal.modal('show')
        modal.find('[name=id_dekorasi]').val(id_dekorasi);
        modal.find('[name=e_tipe_dekorasi]').val(tipe).change();
        modal.find('[name=e_paket]').val(paket);
        modal.find('#preview-edit').attr('src', gambar).show();
        modal.find('[name=e_harga]').val(harga);
        modal.find('[name=gambar_ori]').val(vgbr);


    }

    $('#gambar-edit').on('change', function() {
        const file = this.files;
        if (file) {
            const fileReader = new FileReader();
            const preview = document.getElementById('preview-edit');
            $('#preview-edit').show();
            fileReader.onload = function(event) {
                console.log('bisa');
                preview.setAttribute('src', event.target.result);
                console.log(event.target.result);
            }
            fileReader.readAsDataURL(files[0]);
        }

    });


    $('#gambar-tambah').on('change', function() {
        const file = this.files;
        if (file) {
            const fileReader = new FileReader();
            const preview = document.getElementById('preview-tambah');
            $('#preview-tambah').show();
            fileReader.onload = function(event) {
                console.log('bisa');
                preview.setAttribute('src', event.target.result);
                console.log(event.target.result);
            }
            fileReader.readAsDataURL(files[0]);
        }
    })
</script>

<div class="container">
    <br>
    <div class="row">
        <!-- Button trigger modal -->
        <div class="col">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah">
                <i class="fa-regular fa-square-plus"></i> Tambah Dekorasi
            </button>
        </div>
        <div class="col">
            <input type="text" class="form-control" id="search" placeholder="Search"><br>
        </div>
    </div>
    <table class="table table-striped table-hover table-sm" id="list">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>Paket</th>
                <th>Ready</th>
                <th>Gambar</th>
                <th>Harga</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($db, "SELECT * FROM tbl_dekorasi WHERE is_deleted = '0' ORDER BY id_dekorasi");
            if (mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td style="display: none;"><?= $data['id_dekorasi']; ?></td>
                        <td><?= $data['tipe']; ?></td>
                        <td><?= $data['paket']; ?></td>
                        <td><?php if ($data['is_ready'] == '0') {
                                echo "<i class='fa-regular fa-circle-check text-success'></i>";
                            } else {
                                echo "<i class='fa-regular fa-circle-xmark text-danger'></i>";
                            } ?>
                        </td>
                        <td><img src="img/<?= $data['gambar']; ?>" width="100"></td>
                        <td><?= $data['harga']; ?></td>
                        <td style="display: none;"><?= $data['gambar']; ?></td>
                        <td>
                            <div style="display: flex;" class="gap-2">
                                <button type="button" class="btn btn-warning btn-sm" onclick="edit1(this);" data-id="<?= $data['id_dekorasi'] ?>"><i class="fa-regular fa-pen-to-square"></i></button>

                                <form method="post" action=''>
                                    <input type="hidden" name="h_id_dekorasi" value="<?= $data['id_dekorasi']; ?>" />
                                    <button type="submit" name="hapus" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6">
                        <center>Tidak ada data</center>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah-->
<div class="modal fade" id="modal-tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Dekorasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Paket Dekorasi</label>
                                <select name="tipe_dekorasi" id="tipe_dekorasi" class="form-select" placeholder="pilih paket" required>
                                    <option selected value="">Pilih Tipe</option>
                                    <option value="indoor">Indor</option>
                                    <option value="outdoor">Outdor</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Paket</label>
                                <input type="text" name="paket" class="form-control" id="exampleFormControlInput1" placeholder="masukan paket">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Input file gambar</label>
                                <input type="file" name="gambar" class="form-control" id="gambar-tambah">
                                <img src="#" alt="preview-tambah" id="preview-tambah" style="display: none;" width="100">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Harga</label>
                                <input type="text" name="harga" class="form-control" id="exampleFormControlInput1" placeholder="masukan harga">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Dekorasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_dekorasi" value="<? $data['id_dekorasi'] ?>;">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Paket Dekorasi</label>
                                <select name="e_tipe_dekorasi" id="tipe_dekorasi" class="form-select" placeholder="pilih paket" required>
                                    <option selected value="">Pilih Tipe</option>
                                    <option value="indoor">Indor</option>
                                    <option value="outdoor">Outdor</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Paket</label>
                                <input type="text" name="e_paket" class="form-control" id="exampleFormControlInput1" placeholder="masukan paket">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Input file gambar</label>
                                <input type="file" name="gambar_edit" class="form-control" id="gambar-edit" required>
                                <img src="#" alt="preview-edit" id="preview-edit" style="display: none;" width="100">
                                <input type="hidden" name="gambar_ori" />
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Harga</label>
                                <input type="text" name="e_harga" class="form-control" id="exampleFormControlInput1" placeholder="masukan harga">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="edit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>