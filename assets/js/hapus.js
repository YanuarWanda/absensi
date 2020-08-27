const addHapusBtns = (pesan = "Hapus data ini?", kelas = "btn-hapus") => {
    const hapusBtns = document.getElementsByClassName(kelas);
    Array.prototype.map.call(hapusBtns, hapusBtn => {
        hapusBtn.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();

            if (confirm(pesan)) {
                window.location.href = hapusBtn.getAttribute("href");
            }
        });
    });
}