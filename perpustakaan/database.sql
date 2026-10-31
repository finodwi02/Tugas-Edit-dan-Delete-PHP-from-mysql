CREATE DATABASE IF NOT EXISTS perpustakaan;
USE perpustakaan;

CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    terbit INT NOT NULL,
    stok INT NOT NULL DEFAULT 0
);

INSERT INTO buku (judul, penulis, terbit, stok) VALUES
('Laskar Pelangi', 'Andrea Hirata', 2005, 10),
('Bumi Manusia', 'Pramoedya Ananta Toer', 1980, 5);
