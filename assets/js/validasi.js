const validasiPengguna = (form, edit = false) => {
    let valid = true;

    const pesan = {
        sekolah: document.getElementById('sekolah-invalid'),
        nama: document.getElementById('nama-invalid'),
        tanggalLahir: document.getElementById('tanggalLahir-invalid'),
        alamat: document.getElementById('alamat-invalid'),
        kontak: document.getElementById('kontak-invalid'),
        email: document.getElementById('email-invalid'),
        username: document.getElementById('username-invalid'),
        peran: document.getElementById('peran-invalid'),
        password: document.getElementById('password-invalid'),
        konfirmasi: document.getElementById('konfirmasi-invalid'),
    };

    // Validasi sekolah
    if (!form.sekolah.value) {
        pesan.sekolah.innerHTML = "Asal sekolah wajib diisi";
        valid = false;
    }

    // Validasi nama
    if (!form.nama.value) {
        pesan.nama.innerHTML = "Nama pengguna wajib diisi";
        valid = false;
    }

    // Validasi tanggal lahir
    if (!form.tanggalLahir.value) {
        pesan.tanggalLahir.innerHTML = "Tanggal lahir pengguna wajib diisi";
        valid = false;
    }

    // Validasi alamat
    if (!form.alamat.value) {
        pesan.alamat.innerHTML = "Alamat pengguna wajib diisi";
        valid = false;
    }

    // Validasi kontak
    const kontakRegex = /^\d+$/;
    if (!form.kontak.value) {
        pesan.kontak.innerHTML = "No telepon pengguna wajib diisi";
        valid = false;
    } else if (!kontakRegex.test(form.kontak.value)) {
        pesan.kontak.innerHTML = "No telepon pengguna tidak valid";
        valid = false;
    }

    // Validasi email
    const emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!form.email.value) {
        pesan.email.innerHTML = "Email pengguna wajib diisi";
        valid = false;
    } else if (!emailRegex.test(form.email.value)) {
        pesan.email.innerHTML = "Email pengguna tidak valid";
        valid = false;
    }

    // Validasi username
    if (!form.username.value) {
        pesan.username.innerHTML = "Username wajib diisi";
        valid = false;
    }

    // Validasi peran
    if (!form.peran.value) {
        pesan.peran.innerHTML = "Peran wajib diisi";
        valid = false;
    }

    if (!edit || form.password.value.length > 0) {
        // Validasi password
        if (!form.password.value) {
            pesan.password.innerHTML = "Password wajib diisi";
            valid = false;
        }

        // Validasi konfirmasi password
        const inputKonfirmasi = document.getElementById("konfirmasi");
        if (!form.konfirmasi.value) {
            pesan.konfirmasi.innerHTML = "Konfirmasi password wajib diisi";
            valid = false;
        } else if (form.password.value != form.konfirmasi.value) {
            pesan.konfirmasi.innerHTML = "Password dan konfirmasi password tidak sama";
            inputKonfirmasi.setCustomValidity("something")
            valid = false;
        } else {
            inputKonfirmasi.setCustomValidity("");
        }
    }

    return valid;
}

const validasiSekolah = form => {
    let valid = true;

    const pesan = {
        nama: document.getElementById('nama-invalid'),
        alamat: document.getElementById('alamat-invalid'),
        kontak: document.getElementById('kontak-invalid'),
        email: document.getElementById('email-invalid'),
    };

    // Validasi nama
    if (!form.nama.value) {
        pesan.nama.innerHTML = "Nama sekolah wajib diisi";
        valid = false;
    }

    // Validasi alamat
    if (!form.alamat.value) {
        pesan.alamat.innerHTML = "Alamat sekolah wajib diisi";
        valid = false;
    }

    // Validasi kontak
    const kontakRegex = /^\d+$/;
    if (!form.kontak.value) {
        pesan.kontak.innerHTML = "No telepon sekolah wajib diisi";
        valid = false;
    } else if (!kontakRegex.test(form.kontak.value)) {
        pesan.kontak.innerHTML = "No telepon sekolah tidak valid";
        valid = false;
    }

    // Validasi email
    const emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!form.email.value) {
        pesan.email.innerHTML = "Email sekolah wajib diisi";
        valid = false;
    } else if (!emailRegex.test(form.email.value)) {
        pesan.email.innerHTML = "Email sekolah tidak valid";
        valid = false;
    }

    return valid;
}

const validasiKelas = form => {
    let valid = true;

    const pesan = {
        nama: document.getElementById('nama-invalid'),
        kelasMulai: document.getElementById('kelasMulai-invalid'),
        kelasSelesai: document.getElementById('kelasSelesai-invalid'),
    };

    // Validasi nama
    if (!form.nama.value) {
        pesan.nama.innerHTML = "Nama kelas wajib diisi";
        valid = false;
    }

    // Validasi tanggal kelas mulai
    if (!form.kelasMulai.value) {
        pesan.kelasMulai.innerHTML = "Tanggal kelas mulai wajib diisi";
        valid = false;
    }

    // Validasi tanggal kelas selesai
    if (!form.kelasSelesai.value) {
        pesan.kelasSelesai.innerHTML = "Tanggal kelas selesai wajib diisi";
        valid = false;
    } else if (form.kelasSelesai.value < form.kelasSelesai.min) {
        pesan.kelasSelesai.innerHTML = "Tanggal kelas selesai tidak valid";
        valid = false;
    }

    return valid;
}

const validasiPertemuan = form => {
    let valid = true;

    const pesan = {
        nama: document.getElementById("nama-invalid"),
        tanggalPertemuan: document.getElementById("tanggalPertemuan-invalid"),
        waktuPertemuanMulai: document.getElementById("waktuPertemuanMulai-invalid"),
        waktuPertemuanSelesai: document.getElementById("waktuPertemuanSelesai-invalid"),
    };

    // Validasi nama
    if (!form.nama.value) {
        pesan.nama.innerHTML = "Nama pertemuan wajib diisi";
        valid = false;
    }

    // Validasi tanggal pertemuan
    if (!form.tanggalPertemuan.value) {
        pesan.tanggalPertemuan.innerHTML = "Tanggal pertemuan wajib diisi";
        valid = false;
    }

    // Validasi waktu pertemuan mulai
    if (!form.waktuPertemuanMulai.value) {
        pesan.waktuPertemuanMulai.innerHTML = "Waktu pertemuan mulai wajib diisi";
        valid = false;
    }

    // Validasi waktu pertemuan selesai
    if (!form.waktuPertemuanSelesai.value) {
        pesan.waktuPertemuanSelesai.innerHTML = "Waktu pertemuan selesai wajib diisi";
        valid = false;
    } else if (form.waktuPertemuanSelesai.value < form.waktuPertemuanMulai.value) {
        pesan.waktuPertemuanSelesai.innerHTML = "Waktu pertemuan selesai tidak valid";
        valid = false;
    }

    return valid;
}

const validasi = (form, tipe, edit = false) => {
    form.addEventListener('submit', e => {
        e.preventDefault();
        e.stopPropagation();

        let valid = true;

        switch (tipe) {
            case "sekolah":
                valid = validasiSekolah(form);
                break;
            case "pengguna":
                valid = validasiPengguna(form, edit);
                break;
            case "kelas":
                valid = validasiKelas(form);
                break;
            case "pertemuan":
                valid = validasiPertemuan(form);
                break;
        }

        if (valid) {
            form.submit();
        }

        form.classList.add('was-validated');
    })
}

// Tanggal
const formatTanggalInput = tanggalFull => {
    const tahun = tanggalFull.getFullYear();
    const bulan = (tanggalFull.getMonth() + 1) < 10 ? `0${tanggalFull.getMonth() + 1}` : (tanggalFull.getMonth() + 1);
    const tanggal = (tanggalFull.getDate()) < 10 ? `0${tanggalFull.getDate()}` : (tanggalFull.getDate());
    return `${tahun}-${bulan}-${tanggal}`;
}

const tanggalBesok = tanggal => {
    let hasil = new Date(tanggal);
    hasil.setDate(hasil.getDate() + 1);
    return hasil;
}

const tanggalSelesai = (tglMulaiEl, tglSelesaiEl, edit = false) => {
    if (edit) {
        tglSelesaiEl.setAttribute("min", formatTanggalInput(tanggalBesok(tglMulaiEl.value)));
        tglSelesaiEl.removeAttribute("disabled");
    }

    tglMulaiEl.addEventListener("change", e => {
        if (e.target.value) {
            tglSelesaiEl.setAttribute("min", formatTanggalInput(tanggalBesok(tglMulaiEl.value)));
            tglSelesaiEl.removeAttribute("disabled");
        } else {
            tglSelesaiEl.removeAttribute("min");
            tglSelesaiEl.removeAttribute("value");
            tglSelesaiEl.setAttribute("disabled", true);
        }
    });

    document.getElementById("btn-reset").addEventListener('click', e => {
        tglSelesaiEl.setAttribute("disabled", true);
    });
}

const waktuSelesai = (waktuMulaiEl, waktuSelesaiEl, edit = false) => {
    if (edit) {
        waktuSelesaiEl.setAttribute("min", waktuMulaiEl.value);
        waktuSelesaiEl.removeAttribute("disabled");
    }

    waktuMulaiEl.addEventListener("change", e => {
        if (e.target.value) {
            waktuSelesaiEl.setAttribute("min", e.target.value);
            waktuSelesaiEl.removeAttribute("disabled");
        } else {
            waktuSelesaiEl.removeAttribute("min");
            waktuSelesaiEl.removeAttribute("value");
            waktuSelesaiEl.setAttribute("disabled", true);
        }
    });

    document.getElementById("btn-reset").addEventListener("click", e => {
        waktuSelesaiEl.setAttribute("disabled", true);
    });
}