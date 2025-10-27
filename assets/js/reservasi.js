/**
 * FILE: reservasi.js
 *
 * Menggabungkan logika untuk tampilan Reservasi utama dan formulir Tambah Reservasi
 */

// =================================================================================
// 1. LOGIKA UTAMA FORMULIR TAMBAH RESERVASI (Dipanggil setelah konten dimuat via AJAX)
// =================================================================================

window.initializeTambahReservasiLogic = function() {
    const form = document.getElementById('form-tambah-reservasi');
    if (!form) return; 

    // --- Variabel Form (Menggunakan optional chaining untuk keamanan jika tersedia) ---
    const totalHargaDisplay = document.getElementById('total-harga-display');
    const totalHargaInput = document.getElementById('total-harga-input');
    const inputJumlahPendaki = document.getElementById('add_jumlah_pendaki');
    const inputJumlahParkir = document.getElementById('add_jumlah_tiket_parkir');
    const tableBody = document.querySelector('#rincian-sampah-table tbody');
    const btnTambahBarang = document.getElementById('btn-tambah-barang');
    const btnResetForm = document.getElementById('btn-reset-form');
    const inputRincianSampahJson = document.getElementById('rincian-sampah-json');

    let rincianSampah = [];
    const HARGA_PENDAKI = 10000; 
    const HARGA_PARKIR = 5000;  
    const submitEndpoint = 'proses_reservasi.php'; 

    // --- Fungsi Helper ---
    function formatRupiah(number) { return 'Rp ' + number.toLocaleString('id-ID'); }

    function hitungTotalHarga() {
        // Menggunakan optional chaining (?) dan nullish coalescing (||) untuk keamanan
        const pendaki = parseInt(inputJumlahPendaki?.value) || 0; 
        const parkir = parseInt(inputJumlahParkir?.value) || 0;
        const total = (pendaki * HARGA_PENDAKI) + (parkir * HARGA_PARKIR);
        if(totalHargaDisplay) totalHargaDisplay.textContent = formatRupiah(total);
        if(totalHargaInput) totalHargaInput.value = total;
    }

    function renderRincianSampah() {
        if (!tableBody) return;
        tableBody.innerHTML = '';
        rincianSampah.forEach((item, index) => {
            const row = tableBody.insertRow();
            row.innerHTML = `
                <td>${item.nama}</td>
                <td>${item.jenis}</td>
                <td>${item.jumlah}</td>
                <td>
                    <button type="button" class="btn red" style="padding: 5px 10px; font-size: 0.8rem;" onclick="window.hapusBarang(${index})"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                </td>
            `;
        });
        if(inputRincianSampahJson) inputRincianSampahJson.value = JSON.stringify(rincianSampah);
    }

    // --- Global Functions (diperlukan untuk tombol Hapus di tabel yang dimuat dinamis) ---
    window.hapusBarang = function(index) {
        rincianSampah.splice(index, 1);
        renderRincianSampah();
    };

    // --- Event Listeners Form Logic ---
    if (btnTambahBarang) btnTambahBarang.addEventListener('click', function() {
        const nama = document.getElementById('rincian_nama_barang')?.value.trim();
        const jenis = document.getElementById('rincian_jenis_sampah')?.value;
        const jumlah = parseInt(document.getElementById('rincian_jumlah')?.value) || 0;

        if (nama && jumlah > 0) {
            rincianSampah.push({ nama, jenis, jumlah });
            document.getElementById('rincian_nama_barang').value = '';
            document.getElementById('rincian_jumlah').value = '1';
            renderRincianSampah();
        } else {
            Swal.fire('Peringatan', 'Nama barang dan jumlah harus diisi dengan benar.', 'warning');
        }
    });

    if (btnResetForm) btnResetForm.addEventListener('click', function() {
        form.reset();
        rincianSampah = [];
        renderRincianSampah();
        hitungTotalHarga();
    });

    if (inputJumlahPendaki) inputJumlahPendaki.addEventListener('input', hitungTotalHarga);
    if (inputJumlahParkir) inputJumlahParkir.addEventListener('input', hitungTotalHarga);

    // --- Form Submission (AJAX) ---
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.set('rincian_sampah_json', JSON.stringify(rincianSampah)); 
        
        if (!formData.get('id_pengguna')) {
            Swal.fire('Peringatan', 'Mohon pilih Ketua Rombongan.', 'warning');
            return;
        }

        Swal.fire({ title: 'Memproses Reservasi...', text: 'Mohon tunggu sebentar.', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

        // Path fetch: relatif dari folder admin/
        fetch('reservasi/' + submitEndpoint, { method: 'POST', body: formData })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            Swal.close();
            if (status >= 200 && status < 300 && body.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: body.message + ' Anda akan diarahkan ke daftar reservasi.', }).then(() => {
                    window.location.href = 'index.php?page=reservasi';
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: body.error || 'Terjadi kesalahan saat membuat reservasi.', });
            }
        })
        .catch(error => { Swal.close(); console.error('Error:', error); Swal.fire('Error', 'Terjadi kesalahan jaringan atau server.', 'error'); });
    });
    
    hitungTotalHarga();
};

// =================================================================================
// 2. LOGIKA NAVIGASI (Halaman Reservasi - Berjalan pada DOMContentLoaded)
// =================================================================================

document.addEventListener('DOMContentLoaded', function() {
    const mainContentArea = document.getElementById('main-content-area');
    const navReservasi = document.getElementById('nav-reservasi');
    const navTambahReservasi = document.getElementById('nav-tambah-reservasi');
    
    if (!mainContentArea) return; 

    function loadTambahReservasiForm() {
        fetch('reservasi/tambah_reservasi.php') 
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Gagal memuat form. Status: ${response.status} ${response.statusText}`);
                }
                return response.text();
            })
            .then(html => {
                // Ganti konten area utama
                mainContentArea.innerHTML = html;
                
                // Update tampilan navigasi
                if (navReservasi) navReservasi.classList.remove('active');
                if (navTambahReservasi) navTambahReservasi.classList.add('active');
                
                // Panggil logika inisialisasi formulir
                if (window.initializeTambahReservasiLogic) {
                    window.initializeTambahReservasiLogic(); 
                }
            })
            .catch(error => {
                console.error('AJAX Load Error:', error);
                mainContentArea.innerHTML = `<div class="data-section" style="text-align:center; color:red; padding: 30px;">Error: ${error.message}. Cek konsol.</div>`;
            });
    }
    
    function showReservasiContent() {
         window.location.href = 'index.php?page=reservasi';
    }

    // --- Event Listeners Navigasi ---
    if (navTambahReservasi) navTambahReservasi.addEventListener('click', function(e) {
        e.preventDefault();
        loadTambahReservasiForm();
    });
    
    if (navReservasi) navReservasi.addEventListener('click', function(e) {
        e.preventDefault();
        showReservasiContent();
    });
    
    // --- Logika Global Lainnya ---
    document.querySelectorAll('.btn-validasi').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('modal-validasi').style.display = 'flex';
        });
    });
    
    document.querySelectorAll('.modal-close-btn').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal-overlay').style.display = 'none';
        });
    });
});