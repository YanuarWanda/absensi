const hapusBtns = document.getElementsByClassName("btn-hapus");
Array.prototype.map.call(hapusBtns, (hapusBtn => {
    hapusBtn.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();

        if (confirm("Hapus data ini?")) {
            window.location.href = hapusBtn.getAttribute('href');
        }
    });
}));