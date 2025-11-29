document.addEventListener('DOMContentLoaded', function() {
     
    const modalOverlay = document.getElementById('pembukuan-modal-overlay');
    const modalBody = document.getElementById('pembukuan-modal-body');
    const modalCloseBtn = document.getElementById('pembukuan-modal-close');
    const modalTitle = document.getElementById('pembukuan-modal-title');

    function openModal() {
        if (modalOverlay) modalOverlay.classList.add('show');
    }
    function closeModal() {
        if (modalOverlay) modalOverlay.classList.remove('show');
        if (modalBody) modalBody.innerHTML = '';  
    }
     
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', closeModal);
    }
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
    }
    const tambahForm = document.getElementById('tambahPengeluaranForm');
    if (tambahForm) {
        tambahForm.addEventListener('submit', function(e) {
            e.preventDefault();  
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            submitButton.disabled = true;

            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action'); 
            let responseText = ''; 
            
            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => { 
                const clonedResponse = response.clone();
                 
                clonedResponse.text().then(text => {
                    responseText = text;
                    console.error('RESPONS SERVER MENTAH (Untuk Debugging JSON Error):', responseText);
                }).catch(err => {
                    console.error('Gagal membaca respons sebagai teks:', err);
                });

                return response.json(); 
            })
            .then(data => {
                if (data.status === 'success') { 
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,  
                        showConfirmButton: false,
                        timer: 2000,
                        showClass: { popup: 'animate__animated animate__fadeIn' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    }).then(() => {
                        window.location.reload();  
                    });
                } else { 
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Gagal menyimpan data pengeluaran.',
                        showClass: { popup: 'animate__animated animate__shakeX' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    });
                }
            })
            .catch(error => {
                console.error('Error Submit Tambah Pengeluaran:', error);
                
                let errorMessage = 'Terjadi kesalahan saat memproses permintaan.';
                 
                if (error.name === 'SyntaxError') {
                    errorMessage = 'Respon server tidak valid (Bukan JSON). Silakan cek konsol browser (F12 -> Tab Console) untuk melihat respons server mentah dari proses_pembukuan.php.';
                    Swal.fire({
                        icon: 'error',
                        title: 'JSON Tidak Valid!',
                        html: errorMessage,
                        showClass: { popup: 'animate__animated animate__shakeX' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    });
                    return;  
                }
                 
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    text: errorMessage,
                    showClass: { popup: 'animate__animated animate__shakeX' },
                    hideClass: { popup: 'animate__animated animate__fadeOut' }
                });
            })
            .finally(() => { 
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            });
        });
    }
 
    const allEditButtons = document.querySelectorAll('.btn-edit');
    allEditButtons.forEach(button => {
        button.addEventListener('click', function() {
            
            const id = this.getAttribute('data-id');
            if (!id) return;

            if (modalTitle) modalTitle.textContent = 'Edit Pengeluaran';
  
            openModal();
            if(modalBody) { 
                modalBody.innerHTML = '<div class="loading-spinner" style="text-align: center; padding: 40px 0; font-size: 24px; color: #007bff;"><i class="fa-solid fa-spinner fa-spin"></i> Memuat Data...</div>';
            }
 
            const fetchUrl = `pembukuan/form_edit_pengeluaran.php?id=${id}`;

            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Gagal memuat data (HTTP ${response.status})`);
                    }
                    return response.text();
                })
                .then(html => {
                    if (modalBody) modalBody.innerHTML = html;
                    setupModalFormSubmit(); 
                })
                .catch(error => {
                    console.error('Fetch error:', error); 
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Memuat Data',
                        text: 'Gagal memuat data untuk diedit.',
                        showClass: { popup: 'animate__animated animate__shakeX' },  
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    }); 
                    if (modalBody) modalBody.innerHTML = `<p style='color: red; text-align: center; padding: 20px;'>${error.message}</p>`;
                });
        });
    });
 
    function setupModalFormSubmit() { 
        const modalForm = document.getElementById('editPengeluaranForm'); 
        if (modalForm) { 
            modalForm.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                submitButton.disabled = true; 
                const formData = new FormData(this);
                const actionUrl = this.getAttribute('action'); 
 
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) 
                .then(data => {
                    if (data.status === 'success') {
                        closeModal(); 
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message, 
                            showConfirmButton: false,
                            timer: 2000,
                            showClass: { popup: 'animate__animated animate__fadeIn' },  
                            hideClass: { popup: 'animate__animated animate__fadeOut' }
                        }).then(() => {
                            window.location.reload(); 
                        });
                    } else { 
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Gagal menyimpan data pengeluaran.',
                            showClass: { popup: 'animate__animated animate__shakeX' },
                            hideClass: { popup: 'animate__animated animate__fadeOut' }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error Submit Edit:', error); 
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Jaringan',
                        text: 'Terjadi kesalahan saat memproses permintaan.',
                        showClass: { popup: 'animate__animated animate__shakeX' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    });
                })
                .finally(() => { 
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                });
            });
        }
    }
 
    const allDeleteButtons = document.querySelectorAll('.btn-delete');
    allDeleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            const id = this.getAttribute('data-id');
            const keterangan = this.getAttribute('data-keterangan');
            const table = this.getAttribute('data-table');  
            
            if (!id || !table) {
                console.error("ID atau nama tabel tidak ditemukan di tombol.");
                return;
            } 
            const deleteUrl = `pembukuan/proses_hapus.php?id=${id}&table=${table}`; 
 
            Swal.fire({
                title: 'Konfirmasi Soft Delete',
                html: `Anda yakin ingin mengarsipkan data ${table}: <br><strong>"${keterangan}"</strong>? <br><small class="text-gray-500">(Data tidak akan dihapus permanen, hanya disembunyikan.)</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Arsipkan!',  
                cancelButtonText: 'Batal',
                showClass: {  
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {  
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
 
    const kategoriModalOverlay = document.getElementById('kategori-modal-overlay');
    const kategoriModalCloseBtn = document.getElementById('kategori-modal-close');
    const kategoriForm = document.getElementById('form-tambah-kategori');
    const bukaKategoriModalBtn = document.getElementById('buka-modal-kategori-btn');

    function openModalKategori() {
        if (kategoriModalOverlay) kategoriModalOverlay.classList.add('show');
    }
    function closeModalKategori() {
        if (kategoriModalOverlay) kategoriModalOverlay.classList.remove('show');
        if (kategoriForm) kategoriForm.reset(); // Reset form saat ditutup
    }

    if (bukaKategoriModalBtn) {
        bukaKategoriModalBtn.addEventListener('click', openModalKategori);
    }
    if (kategoriModalCloseBtn) {
        kategoriModalCloseBtn.addEventListener('click', closeModalKategori);
    }
    if (kategoriModalOverlay) {
        kategoriModalOverlay.addEventListener('click', (e) => {
            if (e.target === kategoriModalOverlay) {
                closeModalKategori();
            }
        });
    }

    if (kategoriForm) {
        kategoriForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action'); 
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            submitButton.disabled = true;

            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) 
            .then(data => {
                if (data.status === 'success' && data.new_kategori) {
                    closeModalKategori(); 
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kategori baru berhasil ditambahkan.',
                        showConfirmButton: false,  
                        timer: 2000,
                        showClass: { popup: 'animate__animated animate__bounceIn' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    }).then(() => { 
                        window.location.reload(); 
                    });
 
                    const newOption = new Option(data.new_kategori.nama_kategori, data.new_kategori.id_kategori);
                     
                    const selectTambah = document.getElementById('tambah_kategori');
                    if (selectTambah) {
                        selectTambah.appendChild(newOption.cloneNode(true)); 
                        selectTambah.value = data.new_kategori.id_kategori;  
                    }
                    
                } else { 
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Tidak bisa menyimpan kategori.',
                        showClass: { popup: 'animate__animated animate__shakeX' },
                        hideClass: { popup: 'animate__animated animate__fadeOut' }
                    });
                }
            })
            .catch(error => { 
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan jaringan.',
                    showClass: { popup: 'animate__animated animate__shakeX' },
                    hideClass: { popup: 'animate__animated animate__fadeOut' }
                });
                console.error('Error:', error);
            })
            .finally(() => { 
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            });
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    
    let title = '';
    let text = '';
    let icon = '';
    let showSwal = false;

    if (status === 'success') {
        title = 'Berhasil!';
        text = 'Data berhasil dihapus dari sistem.';
        icon = 'success';
        showSwal = true;
    } else if (status === 'error_delete') {
        title = 'Gagal!';
        text = 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.';
        icon = 'error';
        showSwal = true;
    } else if (status === 'invalid_params' || status === 'missing_params') {
        title = 'Peringatan!';
        text = 'Parameter yang dikirim tidak valid atau hilang.';
        icon = 'warning';
        showSwal = true;
    }

    if (showSwal) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            timer: 3000, 
            showConfirmButton: false
        }).then(() => {
            history.replaceState(null, '', window.location.pathname + window.location.search.replace(/[\?&]status=[^&]*/, ""));
        });
    }
});