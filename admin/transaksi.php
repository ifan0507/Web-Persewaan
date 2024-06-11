<?php
$sukses_insert = false;
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $id_dekorasi = $_POST['id_dekorasi'];
    $id_customer = $_POST['id_customer'];
    $waktu_sewa = $_POST['waktu_sewa'];
    $total_harga = $_POST['total_harga'];
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

$sukses_hapus = false;
if (isset($_POST['hapus'])) {
    $icd = $_POST['h_id_customer'];
    $idt = $_POST['h_id_transaksi'];

    $is_ready = '';
    $lunas = '';
    $qd = mysqli_query($db, "SELECT tbl_dekorasi.is_ready, tbl_transaksi.lunas FROM tbl_transaksi tbl_transaksi
    LEFT JOIN tbl_dekorasi tbl_dekorasi ON tbl_dekorasi.id_dekorasi = tbl_transaksi.id_dekorasi");
    foreach ($qd as $row) {
        $is_ready  = $row['is_ready'];
        $lunas = $row['lunas'];

        if ($is_ready = '1' and $lunas = '0') {
            echo "<script>Swal.fire('Oops','Transaksi masih belum selesai!','error');</script>";
        } else {
            $q1 = mysqli_query($db, "UPDATE tbl_transaksi SET is_deleted ='1' WHERE id_transaksi = '$idt'");
            if ($q1) {
                $s2 = mysqli_query($db, "UPDATE tbl_customer SET is_deleted='1' WHERE id_customer = '$icd'");

                if ($s2) {
                    $sukses_hapus = true;
                } else {
                    echo mysqli_error($db);
                }
            } else {
                echo mysqli_error($db);
            }
        }
    }
}

$sukses_edit = false;
if (isset($_POST['edit'])) {
    $idc = $_POST['val_id_customer'];
    $idt = $_POST['val_id_transaksi'];
    $nama = $_POST['val_nama'];
    $alamat = $_POST['val_alamat'];
    $no_hp = $_POST['val_no_hp'];
    $tanggal_transaksi = $_POST['val_tanggal_transaksi'];
    $id_dekorasi = $_POST['val_id_dekorasi'];
    $waktu_sewa = $_POST['val_waktu_sewa'];
    $total_harga = $_POST['val_total_harga'];
    $htg_tgl = strtotime($tanggal_transaksi) + (intval($waktu_sewa) * 86400);
    $tgl_kembali = date('Y-m-d', $htg_tgl);

    $idd = '';
    $qs = mysqli_query($db, "SELECT id_dekorasi FROM tbl_transaksi WHERE id_transaksi = '$idt'");
    foreach ($qs as $qq) {
        $idd = $qq['id_dekorasi'];

        if ($idd != $id_dekorasi) {
            $qu = mysqli_query($db, "UPDATE tbl_dekorasi SET is_ready = '0' WHERE id_dekorasi = '$idd'");
        }
    }

    $q1 = mysqli_query($db, "UPDATE tbl_customer SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp' WHERE id_customer = '$idc'");
    $s2 = mysqli_query($db, "UPDATE tbl_transaksi SET tanggal_transaksi = '$tanggal_transaksi', id_dekorasi = '$id_dekorasi', waktu_sewa = '$waktu_sewa',tgl_kembali = '$tgl_kembali', total_harga = '$total_harga' WHERE id_transaksi = '$idt'");
    $q3 = mysqli_query($db, "UPDATE tbl_dekorasi SET is_ready = '1' WHERE id_dekorasi = '$id_dekorasi'");

    if ($s2) {
        $sukses_edit = true;
    } else {
        echo mysqli_error($db);
    }
}

if (isset($_POST['kembali'])) {
    $idt = $_POST['h_id_transaksi'];
    $query = mysqli_query($db, "SELECT id_dekorasi FROM tbl_transaksi WHERE id_transaksi = '$idt'");
    foreach ($query as $row) {
        $idk = $row['id_dekorasi'];

        $qu = mysqli_query($db, "UPDATE tbl_dekorasi SET is_ready = '0' WHERE id_dekorasi = '$idk'");
    }
}

if (isset($_POST['lunas'])) {
    $idt = $_POST['h_id_transaksi'];

    $qu = mysqli_query($db, "UPDATE tbl_transaksi SET lunas = '1' WHERE id_transaksi = '$idt'");
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

    $("#id_dekorasi").on('change', function() {
        var harga = $('#id_dekorasi').find(':selected').data('harga');

        $('#total_harga1').val(harga);
    });

    $("#val_id_dekorasi").on('change', function() {
        var harga = $('#val_id_dekorasi').find(':selected').data('harga');
        var waktu = $('#val_waktu_sewa').val();
        var kali = parseInt(harga) * parseInt(waktu);

        $('#val_total_harga1').val(harga);
        $('#val_total_harga').val(kali);
    });

    function hitung() {
        var harga = $('#id_dekorasi').find(':selected').data('harga');
        var waktu = $('#waktu_sewa').val();
        var total = parseInt(harga) * parseInt(waktu);
        if (!waktu) {
            $('#total_harga').val(harga);
        } else {
            $('#total_harga').val(total);
        }
    }

    function hitung2() {
        var harga = $('#val_id_dekorasi').find(':selected').data('harga');
        var waktu = $('#val_waktu_sewa').val();
        var total = parseInt(harga) * parseInt(waktu);
        if (!waktu) {
            $('#val_total_harga').val(harga);
        } else {
            $('#val_total_harga').val(total);
        }
    }

    function edit(a, b, c, d, f, g, h, i, j, k) {
        $('#modal_edit').modal('show');
        $('#modal_edit').on('shown.bs.modal', function(e) {
            $('#val_id_transaksi').val(a);
            $('#val_tanggal_transaksi').val(b.slice(0, 10));
            $('#val_waktu_sewa').val(c);
            $('#val_total_harga').val(d);
            $('#val_id_customer').val(g);
            $('#val_nama').val(h);
            $('#val_alamat').val(i);
            $('#val_no_hp').val(j);
            $('#val_id_dekorasi option[value=' + k + ']').prop('selected', true);
        });

    }
    <?php if ($sukses_insert) { ?>
        Swal.fire('Sukses', 'Data berhasil disimpan !', 'success');
    <?php } ?>
    <?php if ($sukses_hapus) { ?>
        Swal.fire('Sukses', 'Data berhasil dihapus !', 'success');
    <?php } ?>
    <?php if ($sukses_edit) { ?>
        Swal.fire('Sukses', 'Data berhasil diedit !', 'success');
    <?php } ?>
</script>

<div class="container">
    <br>
    <!-- Button trigger modal -->
    <div class="row">
        <div class="col">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa-regular fa-square-plus"></i> Tambah Transaksi
            </button>
        </div>
        <div class="col">
            <input type="text" class="form-control" id="search" placeholder="Search">
        </div>
    </div>
    <br>
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tanggal Transaksi</th>
                <th>Customer</th>
                <th>Paket Dekorasi</th>
                <th>Waktu Sewa</th>
                <th>Tanggal Kembali</th>
                <th>Total Harga</th>
                <th>Kembali</th>
                <th>Lunas</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($db, "SELECT tbl_transaksi.id_transaksi, tbl_customer.id_customer, tbl_dekorasi.id_dekorasi, tbl_transaksi.tanggal_transaksi, tbl_transaksi.is_deleted,tbl_customer.nama, tbl_customer.alamat, tbl_customer.no_hp, tbl_dekorasi.paket, tbl_transaksi.waktu_sewa, tbl_transaksi.total_harga, tbl_transaksi.lunas, tbl_transaksi.tgl_kembali, tbl_dekorasi.is_ready
            FROM tbl_transaksi LEFT JOIN tbl_customer ON tbl_transaksi.id_customer = tbl_customer.id_customer 
            LEFT JOIN tbl_dekorasi ON tbl_transaksi.id_dekorasi = tbl_dekorasi.id_dekorasi WHERE tbl_transaksi.is_deleted ='0' ORDER BY tbl_transaksi.id_transaksi");
            if (mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $data['tanggal_transaksi']; ?></td>
                        <td><?= $data['nama']; ?></td>
                        <td><?= $data['paket']; ?></td>
                        <td><?= $data['waktu_sewa']; ?></td>
                        <td><?= $data['tgl_kembali']; ?></td>
                        <td><?= $data['total_harga']; ?></td>
                        <td><?php if ($data['is_ready'] == '0') {
                                echo "<i class='fa-regular fa-circle-check text-success'></i>";
                            } else {
                                echo "<i class='fa-regular fa-circle-xmark text-danger'></i>";
                            } ?>

                        </td>
                        <td><?php if ($data['lunas'] == '1') {
                                echo "<i class='fa-regular fa-circle-check text-success'></i>";
                            } else {
                                echo "<i class='fa-regular fa-circle-xmark text-danger'></i>";
                            } ?></td>
                        <td>
                            <div style="display: flex;" class="gap-2">
                                <button type="button" class="btn btn-dark btn-sm" onclick="edit(<?= $data['id_transaksi']; ?>,'<?= $data['tanggal_transaksi']; ?>','<?= $data['waktu_sewa']; ?>','<?= $data['total_harga']; ?>','<?= $data['lunas']; ?>',<?= $data['id_customer']; ?>,'<?= $data['nama']; ?>','<?= $data['alamat']; ?>','<?= $data['no_hp']; ?>','<?= $data['id_dekorasi']; ?>');"><i class="fa-regular fa-pen-to-square"></i></button>
                                <form method='post' action=''>
                                    <input type="hidden" name="h_id_transaksi" value="<?= $data['id_transaksi']; ?>" />
                                    <input type="hidden" name="h_id_customer" value="<?= $data['id_customer']; ?>" />
                                    <button type="submit" name="hapus" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>

                                </form>
                                <form method='post' action=''>
                                    <input type="hidden" name="h_id_transaksi" value="<?= $data['id_transaksi']; ?>" />
                                    <input type="hidden" name="h_id_customer" value="<?= $data['id_customer']; ?>" />
                                    <button type="submit" name="kembali" class="btn btn-success btn-sm"><i class="fa-regular fa-circle-left"></i></button>
                                </form>
                                <form method='post' action=''>
                                    <input type="hidden" name="h_id_transaksi" value="<?= $data['id_transaksi']; ?>" />
                                    <input type="hidden" name="h_id_customer" value="<?= $data['id_customer']; ?>" />
                                    <button type="submit" name="lunas" class="btn btn-warning btn-sm"><i class="fa-solid fa-hand-holding-dollar"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="8">
                        <center>Tidak Ada Data</center>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" id="exampleFormControlInput1" placeholder="masukan nama">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Alamat</label>
                                <textarea class="form-control" col="3" name="alamat" style="resize:none;"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">No Handphone</label>
                                <input type="text" name="no_hp" class="form-control" id="exampleFormControlInput1" placeholder="masukan No Handphone">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Tanggal Transaksi</label>
                                <input type="date" name="tanggal_transaksi" class="form-control" id="exampleFormControlInput1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Paket Dekorasi</label>
                                <select name="id_dekorasi" id="id_dekorasi" class="form-select" placeholder="pilih paket">
                                    <option selected disabled value="">pilih paket</option>
                                    <?php

                                    $query = mysqli_query($db, "SELECT * FROM tbl_dekorasi WHERE is_ready = '0' AND is_deleted = '0' ORDER BY id_dekorasi");
                                    while ($data = mysqli_fetch_assoc($query)) { ?>
                                        <option value="<?= $data['id_dekorasi']; ?>" data-harga="<?= $data['harga']; ?>"><?= $data['paket']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Waktu Sewa</label>
                                <input type="hidden" class="form-control" name="id_customer" value="<?= $idc; ?>" />
                                <input type="text" name="waktu_sewa" class="form-control" id="waktu_sewa" onkeyup="hitung();" placeholder="masukan waktu sewa">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Total Harga</label>
                                <input type="hidden" name="total_harga1" class="form-control" id="total_harga1" value="0">
                                <input type="text" name="total_harga" class="form-control" id="total_harga" value="0">
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

<div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nama</label>
                                <input type="text" name="val_nama" class="form-control" id="val_nama" placeholder="masukan nama">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Alamat</label>
                                <textarea class="form-control" col="3" name="val_alamat" id="val_alamat" style="resize:none;"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">No Handphone</label>
                                <input type="text" name="val_no_hp" class="form-control" id="val_no_hp" placeholder="masukan No Handphone">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Tanggal Transaksi</label>
                                <input type="date" name="val_tanggal_transaksi" class="form-control" id="val_tanggal_transaksi">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Paket Dekorasi</label>
                                <select name="val_id_dekorasi" id="val_id_dekorasi" class="form-select" placeholder="pilih paket">
                                    <option disabled value="">Pilih Paket</option>
                                    <?php
                                    $query = mysqli_query($db, "SELECT * FROM tbl_dekorasi ORDER BY id_dekorasi ASC");
                                    while ($data = mysqli_fetch_assoc($query)) { ?>
                                        <option value="<?= $data['id_dekorasi']; ?>" data-harga="<?= $data['harga']; ?>"><?= $data['paket']; ?></option>
                                    <?php } ?>
                                </select>
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