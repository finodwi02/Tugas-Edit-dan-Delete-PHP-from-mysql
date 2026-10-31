<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard Buku</title>
</head>
<body>
    <header class="bg-primary text-white p-3">
        <h1 class="h3 m-0">Website Praktik</h1>
    </header>

    <nav class="navbar navbar-dark bg-info">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Perpustakaan</a>
        </div>
    </nav>

    <div class="container-fluid my-4">
        <div class="row">
            <aside class="col-md-3 mb-3">
                <div class="bg-light border rounded p-3">
                    <h2 class="h5">Menu</h2>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="dashboard.php" class="text-decoration-none text-black">Dashboard</a></li>
                        <li class="list-group-item"><a href="#daftar-buku" class="text-decoration-none text-black">Daftar Buku</a></li>
                    </ul>
                </div>
            </aside>

            <main class="col-md-9">
                <?php
                $pesan = [
                    'tambah' => ['success', 'Buku berhasil ditambahkan.'],
                    'edit'   => ['warning', 'Data buku berhasil diperbarui.'],
                    'hapus'  => ['danger',  'Buku berhasil dihapus.'],
                    'gagal'  => ['secondary', 'Data tidak valid, coba lagi.'],
                ];
                if (isset($_GET['pesan'], $pesan[$_GET['pesan']])) {
                    [$warna, $teks] = $pesan[$_GET['pesan']];
                    echo "<div class='alert alert-$warna alert-dismissible fade show'>$teks
                          <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
                }
                ?>

                <section>
                    <h2 id="form-title" class="h4">Form Tambah Buku</h2>
                    <form action="proses.php" method="POST" id="form-buku">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="judul" class="form-label">Judul Buku</label>
                                <input type="text" class="form-control" name="judul" id="judul" required minlength="3">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="penulis" class="form-label">Penulis</label>
                                <input type="text" class="form-control" name="penulis" id="penulis" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="terbit" class="form-label">Tahun Terbit</label>
                                <input type="number" class="form-control" name="terbit" id="terbit" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" name="stok" id="stok" min="0" required>
                            </div>
                        </div>
                        <button type="submit" id="btn-submit" class="btn btn-primary">Simpan Buku</button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
                    </form>
                </section>

                <section class="border rounded p-3 mt-4" id="daftar-buku">
                    <h2 class="h4">Daftar Buku</h2>
                    <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th><th>Judul</th><th>Penulis</th><th>Tahun Terbit</th><th>Stok</th><th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
                            while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['judul']); ?></td>
                                <td><?= htmlspecialchars($row['penulis']); ?></td>
                                <td><?= (int) $row['terbit']; ?></td>
                                <td><?= (int) $row['stok']; ?></td>
                                <td>
                                    <!-- EDIT: data dikirim lewat atribut data-* (aman dari karakter kutip) -->
                                    <button type="button" class="btn btn-sm btn-warning btn-edit"
                                        data-id="<?= (int) $row['id'] ?>"
                                        data-judul="<?= htmlspecialchars($row['judul'], ENT_QUOTES) ?>"
                                        data-penulis="<?= htmlspecialchars($row['penulis'], ENT_QUOTES) ?>"
                                        data-terbit="<?= (int) $row['terbit'] ?>"
                                        data-stok="<?= (int) $row['stok'] ?>">Edit</button>

                                    <!-- DELETE: lewat POST -->
                                    <form action="proses.php" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                        <input type="hidden" name="hapus" value="<?= (int) $row['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <footer class="bg-info text-black text-center p-2 m-4">
        <p class="mb-0">&copy; 2026 Website Praktik. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll(".btn-edit").forEach(function (btn) {
            btn.addEventListener("click", function () {
                var d = btn.dataset;
                document.getElementById("id").value = d.id;
                document.getElementById("judul").value = d.judul;
                document.getElementById("penulis").value = d.penulis;
                document.getElementById("terbit").value = d.terbit;
                document.getElementById("stok").value = d.stok;

                document.getElementById("form-title").innerText = "Form Edit Buku";
                document.getElementById("btn-submit").innerText = "Update Buku";
                document.getElementById("btn-submit").classList.replace("btn-primary", "btn-warning");
                window.scrollTo(0, 0);
            });
        });

        function resetForm() {
            document.getElementById("form-buku").reset();
            document.getElementById("id").value = "";
            document.getElementById("form-title").innerText = "Form Tambah Buku";
            document.getElementById("btn-submit").innerText = "Simpan Buku";
            document.getElementById("btn-submit").classList.replace("btn-warning", "btn-primary");
        }
    </script>
</body>
</html>
