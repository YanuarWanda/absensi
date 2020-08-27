const getKeteranganAbsensi = status => {
    switch (status) {
        case "H": return "Hadir";
        case "A": return "Alpa";
        case "I": return "Izin";
        case "S": return "Sakit";
        default: return "Alpa";
    }
}

const addEventKeterangan = (pesan, kelas = "keterangan", murid = false) => {
    const keterangans = document.getElementsByClassName(kelas);
    Array.prototype.map.call(keterangans, keterangan => {
        keterangan.setAttribute("data-default", keterangan.value);
        keterangan.addEventListener("change", e => {
            if (murid) {
                const isValid = e.target.getAttribute("data-valid");
                console.log(isValid);
                console.log(typeof (isValid));
                if (isValid == 1) {
                    e.target.value = e.target.getAttribute("data-default");
                    return;
                }
            }
            const form = document.getElementById(`form${e.target.getAttribute("id")}`);

            if (confirm(pesan)) {
                form.submit();
            } else {
                e.target.value = e.target.getAttribute("data-default");
            }
        });
    });
}

const addEventValid = (pesan, kelas = "btn-valid") => {
    const btnValids = document.getElementsByClassName(kelas);
    Array.prototype.map.call(btnValids, btn => {
        btn.addEventListener("click", e => {
            const target = e.target.getAttribute("href");
            if (target) {
                if (confirm(pesan)) {
                    window.location.href = e.target.getAttribute("href");
                }
            }
        });
    });
}