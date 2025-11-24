document.addEventListener('DOMContentLoaded', () => {
    const modalOverlay = document.getElementById('modalOverlay');
    const closeModalBtn = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    const tableBody = document.querySelector('.data-table tbody');
    let lastFocusedElement = null;

    const openModal = (title, contentHTML) => {
        modalTitle.textContent = title;
        modalBody.innerHTML = contentHTML;
        modalOverlay.classList.add('show'); 
    };

    const closeModal = () => {
        modalOverlay.classList.remove('show'); 
        if (lastFocusedElement) {
            lastFocusedElement.focus();
            lastFocusedElement = null; 
        }
        setTimeout(() => {
            modalBody.innerHTML = ''; 
        }, 300);
    };

    closeModalBtn.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    const buildForm = (data = {}) => {
        let formHTML = `
            <form id="pendakianForm">
                <!-- PERBAIKAN: Kirim KEDUA Primary Key (PK) -->
                <input type="hidden" name="id_reservasi" value="${data.id_reservasi ?? ''}">
                <input type="hidden" name="id_pendaki" value="${data.id_pendaki ?? ''}">

                
                <label for="id_pendaki_display">ID Pendaki (Tidak dapat diubah):</label>
                <!-- Ini hanya untuk tampilan, data dikirim lewat input 'hidden' di atas -->
                <input type="number" id="id_pendaki_display" value="${data.id_pendaki ?? ''}" disabled>

                <label for="nama_lengkap">Nama Lengkap:</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="${data.nama_lengkap ?? ''}" disabled>

                <label for="nik">NIK:</label>
                <input type="text" id="nik" name="nik" value="${data.nik ?? ''}" disabled>

                <label for="alamat">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="${data.alamat ?? ''}" disabled>

                <label for="nomor_telepon">No. Telepon:</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" value="${data.nomor_telepon ?? ''}" disabled>

                <label for="kontak_darurat">Kontak Darurat:</label>
                <input type="text" id="kontak_darurat" name="kontak_darurat" value="${data.kontak_darurat ?? ''}" disabled>
                
                <p style="font-size: 0.8rem; color: #999;">Catatan: Pembaruan Surat Sehat harus dilakukan terpisah.</p>
                <div class="form-actions">
                    <button type="button" class="btn red" id="cancelForm">Batal</button>
                </div>
            </form>
        `;
        return formHTML;
    };
    
    const setupFormSubmission = () => {
        const form = document.getElementById('pendakianForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const url = 'manage_pendakian/update_pendakian.php'; 

            try {
                const response = await fetch(url, {
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && !result.error) {
                    Swal.fire('Berhasil!', `Data berhasil diubah.`, 'success').then(() => {
                        closeModal();
                        window.location.reload(); // Muat ulang tabel
                    });
                } else {
                    throw new Error(result.message || `Gagal mengubah data.`);
                }
            } catch (error) {
                Swal.fire('Gagal', error.message, 'error');
            }
        });
    };

    tableBody.addEventListener('click', async (e) => {
        const target = e.target.closest('button');
        if (!target) return; 
        const row = target.closest('tr');
        if (target.classList.contains('btn-edit')) {
            
            lastFocusedElement = target;
            const rowData = {
                id_reservasi: row.children[0].textContent,
                id_pendaki: row.children[1].textContent,
                nama_lengkap: row.children[2].textContent,
                nik: row.children[3].textContent,
                alamat: row.children[4].textContent,
                nomor_telepon: row.children[5].textContent,
                kontak_darurat: row.children[6].textContent,
            };

            openModal('Detail Data Anggota Rombongan', buildForm(rowData));

            document.getElementById('cancelForm').addEventListener('click', closeModal);
            
            setupFormSubmission(); 
        } 
        
    });
});