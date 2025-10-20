document.addEventListener('DOMContentLoaded', function() {

    // elemen modal validasi reservasi
    const modalValidasi = document.querySelector('#modal-validasi');
    const modalValidasiBody = modalValidasi ? modalValidasi.querySelector('.modal-body') : null;
    const modalValidasiCloseBtn = modalValidasi ? modalValidasi.querySelector('.modal-close-btn') : null;

    // elemen modal tambah reservasi
    const modalTambah = document.querySelector('#modal-tambah');
    const modalTambahCloseBtn = modalTambah ? modalTambah.querySelector('.modal-close-btn') : null;
    const btnTambahReservasi = document.querySelector('#btn-tambah-reservasi');
    const formTambahReservasi = document.querySelector('#form-tambah-reservasi');

    let currentReservasiId = null;

    // fungsi untuk menampilkan dan menyembunyikan modal validasi
    function showValidasiModal() {
        if (modalValidasi) modalValidasi.classList.add('show');
    }
    function hideValidasiModal() {
        if (modalValidasi) {
             modalValidasi.classList.remove('show');
             if(modalValidasiBody) modalValidasiBody.innerHTML = '<p>Loading...</p>';
             currentReservasiId = null;
        }
    }

    // fungsi untuk menampilkan dan menyembunyikan modal tambah reservasi
    function showTambahModal() {
        if (modalTambah) modalTambah.classList.add('show');
    }
    function hideTambahModal() {
        if (modalTambah) modalTambah.classList.remove('show');
        if(formTambahReservasi) formTambahReservasi.reset();
    }

    function reloadModalValidasiContent() {
        if (!currentReservasiId || !modalValidasiBody) return;

        modalValidasiBody.innerHTML = '<p>Memuat ulang...</p>'; // Tanda loading
        fetch(`reservasi/get_reservasi.php?id=${currentReservasiId}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.text();
            })
            .then(html => {
                modalValidasiBody.innerHTML = html;
                // untuk sembunyikan form tambah barang setelah reload
                const formAddItem = modalValidasiBody.querySelector('#form-tambah-item');
                if (formAddItem) formAddItem.style.display = 'none';
                 // untuk tambah barang kembali muncul
                const showButton = modalValidasiBody.querySelector('#btn-show-add-item-form');
                if(showButton) showButton.style.display = 'inline-flex';
            })
            .catch(error => {
                modalValidasiBody.innerHTML = `<p>Gagal memuat ulang data.</p><p class="error-message"><i>${error}</i></p>`;
                console.error('Error reloading modal:', error);
            });
    }

    document.querySelectorAll('.btn-validasi').forEach(button => {
        button.addEventListener('click', function() {
            currentReservasiId = this.dataset.id; 
            reloadModalValidasiContent();
            showValidasiModal(); 
        });
    });

    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            const reservasiId = this.dataset.id;
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data reservasi ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('reservasi/proses_reservasi.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ action: 'delete', id: reservasiId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Terhapus!', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Gagal menghapus data.', 'error');
                    });
                }
            });
        });
    });

    if (modalValidasiBody) {
        modalValidasiBody.addEventListener('click', function(event) {
            const target = event.target;
            const button = target.closest('button');

            if (!button) return;

            const reservasiId = button.dataset.id;
            const nextStatus = button.dataset.nextStatus;
            let actionText = '';

            // Aksi Setujui / Bayar / Selesai / Batal / Reopen
            if (button.classList.contains('btn-setujui') || button.classList.contains('btn-selesai') || button.classList.contains('btn-batal') || button.classList.contains('btn-reopen')) {
                 if (button.classList.contains('btn-setujui')) actionText = 'mengkonfirmasi pembayaran';
                 else if (button.classList.contains('btn-selesai')) actionText = 'menandai reservasi selesai';
                 else if (button.classList.contains('btn-batal')) actionText = 'membatalkan reservasi';
                 else if (button.classList.contains('btn-reopen')) actionText = `membuka kembali reservasi (status menjadi ${nextStatus.replace('_',' ')})`;

                 if (reservasiId && nextStatus && actionText) {
                    Swal.fire({
                        title: `Anda yakin ingin ${actionText}?`, icon: 'question',
                        showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('reservasi/proses_reservasi.php', {
                                method: 'POST', headers: {'Content-Type': 'application/json'},
                                body: JSON.stringify({ action: 'update_status', id: reservasiId, status: nextStatus })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Berhasil!', data.message, 'success').then(() => {
                                        hideValidasiModal(); location.reload();
                                    });
                                } else { Swal.fire('Gagal!', data.message, 'error'); }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error!', 'Gagal mengubah status.', 'error');
                            });
                        }
                    });
                 }
                 return;
            }

            // Aksi Tampilkan Form Tambah Barang
            else if (button.id === 'btn-show-add-item-form') {
                const form = modalValidasiBody.querySelector('#form-tambah-item');
                if (form) form.style.display = 'block';
                button.style.display = 'none';
            }
            // Aksi Batal Tambah Barang
            else if (button.id === 'btn-cancel-add-item') {
                const form = modalValidasiBody.querySelector('#form-tambah-item');
                const showButton = modalValidasiBody.querySelector('#btn-show-add-item-form');
                if (form) { form.style.display = 'none'; form.reset(); } // Reset form juga
                if (showButton) showButton.style.display = 'inline-flex';
            }
            // Aksi Hapus Barang Bawaan
            else if (button.classList.contains('btn-delete-item')) {
                const itemId = button.dataset.itemId;
                 Swal.fire({ title: 'Hapus Barang Ini?', text: "Tindakan ini tidak bisa dibatalkan.", icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
                 }).then((result) => {
                    if (result.isConfirmed) {
                         fetch('reservasi/proses_reservasi.php', {
                            method: 'POST', headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({ action: 'delete_barang', item_id: itemId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                reloadModalValidasiContent();
                            } else { Swal.fire('Gagal!', data.message, 'error'); }
                        })
                         .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Gagal menghapus barang.', 'error');
                        });
                    }
                 });
            }

        }); 

        modalValidasiBody.addEventListener('submit', function(event){
            if(event.target.id === 'form-tambah-item') {
                event.preventDefault();

                const namaBarang = modalValidasiBody.querySelector('#add-nama-barang').value.trim();
                const jumlah = modalValidasiBody.querySelector('#add-jumlah-barang').value;
                const jenisSampah = modalValidasiBody.querySelector('#add-jenis-sampah').value;

                if (!namaBarang || !jumlah || !jenisSampah) {
                     Swal.fire('Error', 'Semua field barang harus diisi.', 'error');
                     return;
                }

                fetch('reservasi/proses_reservasi.php', {
                    method: 'POST', headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'add_barang',
                        id: currentReservasiId,
                        nama_barang: namaBarang,
                        jumlah: jumlah,
                        jenis_sampah: jenisSampah
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success'); 
                        reloadModalValidasiContent(); 
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                 .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Gagal menambah barang.', 'error');
                });
            }
        });

    } 

    // untuk menutup modal validasi
     if (modalValidasiCloseBtn) { modalValidasiCloseBtn.addEventListener('click', hideValidasiModal); }
     if (modalValidasi) { modalValidasi.addEventListener('click', function(event) { if (event.target === modalValidasi) hideValidasiModal(); }); }

    // untuk menampilkan modal tambah reservasi
    if (btnTambahReservasi) { btnTambahReservasi.addEventListener('click', showTambahModal); }

    // untuk submit form tambah reservasi
    if (formTambahReservasi) {
        formTambahReservasi.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(formTambahReservasi);
            const data = Object.fromEntries(formData.entries());
            data.action = 'create';

            fetch('reservasi/proses_reservasi.php', {
                method: 'POST', headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire('Berhasil!', result.message, 'success').then(() => {
                        hideTambahModal(); location.reload();
                    });
                } else { Swal.fire('Gagal!', result.message, 'error'); }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Tidak dapat terhubung ke server.', 'error');
            });
        });
    }

    // untuk menutup modal tambah reservasi
    if (modalTambahCloseBtn) { modalTambahCloseBtn.addEventListener('click', hideTambahModal); }
    if (modalTambah) { modalTambah.addEventListener('click', function(event) { if (event.target === modalTambah) hideTambahModal(); }); }

}); 