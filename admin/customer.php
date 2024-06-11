<div class="container">
    <br>
    <table class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No HandPhone</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($db, "SELECT * FROM tbl_customer WHERE is_deleted = '0' ORDER BY id_customer");
            if (mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $data['nama']; ?></td>
                        <td><?= $data['alamat']; ?></td>
                        <td><?= $data['no_hp']; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">
                        <center>Tidak ada data</center>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script src="js/bootstrap.bundle.min.js"></script>