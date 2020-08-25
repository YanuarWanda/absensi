DROP DATABASE IF EXISTS absensi;
CREATE DATABASE absensi;

USE absensi;

CREATE TABLE sekolah (
    id_sekolah INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    kontak VARCHAR(13) NOT NULL,
    email VARCHAR(50) NOT NULL,
    status CHAR(1) NOT NULL DEFAULT 'N'
    -- Status (N = Negeri, S = Swasta)
);

CREATE TABLE pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    id_sekolah INT NOT NULL,
    nama VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    kontak VARCHAR(13) NOT NULL,
    email VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin CHAR(1) NOT NULL DEFAULT 'L',
    -- Jenis Kelamin (L = Laki-laki, P = Perempuan)
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    peran CHAR(1) NOT NULL DEFAULT 'G',
    -- Peran (G = Guru, M = Murid)
    FOREIGN KEY (id_sekolah) REFERENCES sekolah(id_sekolah) ON DELETE CASCADE
);

CREATE TABLE kelas (
    id_kelas INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    id_sekolah INT NOT NULL,
    nama VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    kelas_mulai DATE NOT NULL,
    kelas_selesai DATE NOT NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
    FOREIGN KEY (id_sekolah) REFERENCES sekolah(id_sekolah) ON DELETE CASCADE
);

CREATE TABLE kelas_murid (
    id_kelas INT NOT NULL,
    id_pengguna INT NOT NULL,
    PRIMARY KEY (id_kelas, id_pengguna),
    FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE CASCADE,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE
);

CREATE TABLE pertemuan (
    id_pertemuan INT AUTO_INCREMENT PRIMARY KEY,
    id_kelas INT NOT NULL,
    nama VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    tanggal_mulai DATETIME NOT NULL,
    tanggal_selesai DATETIME NOT NULL,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE CASCADE
);

cREATE TABLE absensi (
    id_pertemuan INT NOT NULL,
    id_pengguna INT NOT NULL,
    status CHAR(1) NOT NULL DEFAULT 'A',
    -- Status (A = Alpa, I = Izin, S = Sakit, H = Hadir)
    valid boolean NOT NULL DEFAULT false,
    PRIMARY KEY (id_pertemuan, id_pengguna),
    FOREIGN KEY (id_pertemuan) REFERENCES pertemuan(id_pertemuan) ON DELETE CASCADE,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE
);

-- DATA
INSERT INTO sekolah (nama, alamat, kontak, email, status) VALUES
("SDN 273 Gempol Sari", "Komplek Bumi Asri E-40, Gempol Sari, Kecamatan Bandung Kulon, Kota Bandung", "6127050", "sdn273bdg@gmail.com", 'N'),
("SDN 093 Tunas Harapan Cijerah", "Jl. Cijerah No. 116, Cijerah, Kecamatan Bandung Kulon, Kota Bandung", "6127051", "sdn093bdg@gmail.com", 'N'),
("SDN 080 Bojong Indah Cibuntu", "Jl. Kawat No. 1, Caringin, Kecamatan Bandung Kulon, Kota Bandung", "6127052", "sdn080bdg@gmail.com", 'N'),
("SD Kristen BPK Penabur Taman Holis Indah", "Komp. Taman Holis Indah Blok A, Cigondewah Kidul, Kecamatan Bandung Kulon, Kota Bandung", "6127053", "sdbpkholis@gmail.com", 'S'),
("SD Baitul Hikmah", "Jl. Mekar Indah No. 2B", "6127054", "sdbaitulhikmah@gmail.com", "S"),
("SMP Negeri 57 Bandung", "Jl. Gempol Sari No. 142 RT.05/09, Gempol Sari, Kecamatan Bandung Kulon, Kota Bandung", "6127055", "smpn57bdg@gmail.com", "N"),
("SMP YPKKP", "Jl. Cijerah No. 230, Cijerah, Kecamatan Bandung Kulon, Kota Bandung", "6127056", "smpypkkp@gmail.com", "S"),
("SMP Negeri 23 Bandung", "Jl. Arjuna No. 20-22, Ciroyom, Kecamatan Andir, Kota Bandung", "6011128", "info@smpn23bdg.sch.id", "N"),
("SMP Negeri 41 Bandung", "Jl. Arjuna No. 18, Ciroyom, Kecamatan Andir, Kota Bandung", "6127057", "smpn41bdg@gmail.com", "N"),
("SMP Advent Cimindi", "Jl. Raya Cimindi No.74, Campaka, Kecamatan Andir, Kota Bandung", "6127058", "smpadventcimindi@gmail.com", "S"),
("SMKN 11 Bandung", "Jl. Budi Cilember, Sukaraja, Kecamatan Cicendo, Kota Bandung", "6127059", "smkn11bdg@gmail.com", "N"),
("SMKN 12 Bandung", "Jl. Pajajaran No. 92, Pamoyanan, Kecamatan Cicendo, Kota Bandung", "6127060", "smkn12bdg@gmail.com", "N"),
("SMAS Angkasa", "Jl. Lettu Subagio No. 22, Husen Sastranegara, Kecamatan Cicendo, Kota Bandung", "6127061", "smasangkasa@gmail.com", "S"),
("SMAN 6 Bandung", "Jl. Pasir Kaliki No.51, Arjuna, Kecamatan Cicendo, Kota Bandung", "6127062", "sman6bdg@gmail.com", "N"),
("SMAN 9 Bandung", "LMU I Suparmin No. 1 A Bandung, Pajajaran, Kecamatan Cicendo, Kota Bandung", "6127063", "sman9bdg@gmail.com", "N");

INSERT INTO pengguna (id_sekolah, nama, alamat, kontak, email, tanggal_lahir, jenis_kelamin, username, password, peran) VALUES
(6, "Lyta Amanda Nindya Putri", "Jl. Marga Asri VI G. Blok C No. 170", "087825418390", "lytaamanda@gmail.com", "2005-07-23", 'P', "lytaamanda", md5("lyta123"), 'M'),
(1, "Shella Putri Amalia", "Jl. Marga Asri VI G. Blok C No. 171", "087825418391", "shellaputri@gmail.com", "2007-06-29", 'P', "shellaputri", md5("shella123"), 'M'),
(1, "Mutiara Aprilianti", "Jl. Marga Asri VI G. Blok C No. 172", "087825418392", "mutiaraapril@gmail.com", "2007-05-14", 'P', "mutiaraapril", md5("mutiara123"), 'M'),
(1, "Revan Dwiki", "Jl. Marga Asri VI G. Blok C No. 173", "087825418393", "revandwiki@gmail.com", "2008-02-07", 'L', "revandwiki", md5("revan123"), 'M'),
(1, "Lutfi Nur Alifah", "Jl. Marga Asri VI G. Blok C No. 174", "087825418394", "lutfinuralifah@gmail.com", "2008-03-19", 'P', "lutfinur", md5("lutfi123"), 'M'),
(1, "Muhammad Ikhyar Qiram", "Jl. Marga Asri VI G. Blok C No. 175", "087825418395", "mikhyarqiram@gmail.com", "2008-09-01", 'L', "mqiram", md5("qiram123"), 'M'),
(1, "Hendra Suhendra", "Jl. Marga Asri VI G. Blok C No. 176", "087825418396", "hendrasuhendra@gmail.com", "1982-12-09", 'L', "hendrasuhendra", md5("hendra123"), 'G'),
(6, "Tasya Munlani", "Jl. Marga Asri VI G. Blok C No. 177", "087825418397", "tasyamunlani@gmail.com", "2006-01-22", 'P', "tasyamunlani", md5("tasya123"), 'M'),
(6, "Sarah Malika Putri", "Jl. Marga Asri VI G. Blok C No. 177", "087825418398", "sarahmalika@gmail.com", "2006-02-12", 'P', "sarahmalika", md5("sarah123"), 'M'),
(6, "Putri Fizillian", "Jl. Marga Asri VI G. Blok C No. 178", "087825418399", "fizzilian@gmail.com", "2006-03-19", 'P', "fizzilian", md5("fizzilian123"), 'M'),
(6, "Sri Wahyuni", "Jl. Marga Asri VI G. Blok C No. 179", "087825418400", "sriwahyuni@gmail.com", "2006-09-02", 'P', "sriwahyuni", md5("sriwahyuni123"), 'M'),
(6, "Martanti", "Jl. Marga Asri VI G. Blok C No. 180", "087825418401", "martanti@gmail.com", "1994-07-14", 'P', "martanti", md5("martanti123"), 'G'),
(11, "Yanuar Wanda Putra", "Jl. Marga Asri VI G. Blok C no. 170", "085155331623", "yanuar.wanda2@gmail.com", "2001-01-18", 'L', "yanuarwanda", md5("yanuar123"), 'M'),
(11, "Fahri Muhammad Zulkarnaen Iskandar", "Jl. Cigondewah Kidul", "087825418402", "mzfahri@gmail.com", "2000-08-12", 'L', "mzfahri", md5("fahri123"), 'M'),
(11, "Kukuh Mangku Hidayatullah", "Perum Jl. Sari Asih No.3, Sarijadi, Kec. Sukasari, Kota Bandung, Jawa Barat 40151", "087825418403", "kukuhpelog@gmail.com", "1999-08-16", 'L', "kukuhpelog", md5("pelog123"), 'M'),
(11, "Muhammad Syaiful Mahial Hakim", "Jl. Gunung Batu No.55", "087825418404", "syaiful@gmail.com", "2000-10-21", 'L', "msyaiful", md5("syaiful123"), 'M'),
(11, "Intan Nurmalasari", "Jalan Raya Batujajar RT/RW 02/09, Batujajar Tim.", "087825418405", "intannurmalasari@gmail.com", "2000-11-16", 'P', "intannurmalasari", md5("intan123"), 'M'),
(11, "Dea Fitri Handayani", "Jl. Jati Utama No.11, Margaasih", "0878254183406", "deafitrih@gmail.com", "2000-12-19", 'P', "deafitri", md5("dea123"), 'M'),
(11, "Yudi Subekti", "Jl Wastukencana 4", "087825418407", "yudisubekti@gmail.com", "1980-04-12", 'L', "yudisubekti", md5("yudi123"), 'G'),
(11, "Administrator", "Jl. Admin", "087722394860", "10118228.yanuarwanda@yanuarwanda.tech", "2001-01-18", 'L', 'admin', md5("admin123"), 'A');

INSERT INTO kelas (id_pengguna, id_sekolah, nama, deskripsi, kelas_mulai, kelas_selesai) VALUES
(7, 1, "IPA Kelas A", "Kelas untuk mempelajari ilmu pengetahuan alam", "2020-01-18", "2021-01-18"),
(12, 6, "IPA Kelas 9-5", "Kelas untuk mempelajari ilmu pengetahuan alam", "2020-06-23", "2021-06-23"),
(19, 11, "Basis Data", "Kelas untuk mempelajari basis data", "2020-06-23", "2021-06-23");

INSERT INTO kelas_murid(id_kelas, id_pengguna) VALUES
(1, 2), (1, 3), (1, 4), (1, 5), (1, 6),
(2, 8), (2, 9), (2, 10), (2, 11), (2, 1), 
(3, 14), (3, 15), (3, 16), (3, 17), (3, 18), (3, 13);

INSERT INTO pertemuan(id_kelas, nama, deskripsi, tanggal_mulai, tanggal_selesai) VALUES
(1, "Rangka dan Panca Indera Manusia", "Mempelajari Rangka dan Panca Indera Manusia", "2020-01-18 07:00:00", "2020-01-18 08:30:00"),
(2, "Reproduksi", "Mempelajari tentang Reproduksi", "2020-06-23 07:00:00", "2020-06-23 08:30:00"),
(3, "Pendahuluan", "Pertemuan ini menjelaskan tentang konsep dasar sistem berkas dan akses file.", "2020-06-23 07:00:00", "2020-06-23 08:30:00");

INSERT INTO absensi (id_pertemuan, id_pengguna, status, valid) VALUES
(1, 2, 'H', 1),
(1, 3, 'H', 1),
(1, 4, 'H', 1),
(1, 5, 'H', 1),
(1, 6, 'H', 1),
(2, 8, 'H', 1),
(2, 9, 'H', 1),
(2, 10, 'H', 1),
(2, 11, 'H', 1),
(2, 1, 'H', 1),
(3, 13, 'H', 1),
(3, 14, 'H', 1),
(3, 15, 'H', 1),
(3, 16, 'H', 1),
(3, 17, 'H', 1),
(3, 18, 'H', 1);