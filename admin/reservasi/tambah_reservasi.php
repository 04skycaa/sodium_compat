<?php
// ==========================================================
// FILE: simaksi/admin/reservasi/tambah_reservasi.php
// Halaman formulir untuk menambah reservasi baru.
// ==========================================================

// Tentukan path base URL untuk API agar path di JavaScript selalu absolut dan benar.
// Asumsi: Aplikasi berjalan di /simaksi/
$base_url = '/simaksi/admin'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Reservasi | Admin SIMAKSI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Custom Styles */
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        .form-input:focus {
            border-color: #10b981;
            outline: none;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.5);
        }
        .animate-spin-fast {
            animation: spin 0.5s linear infinite;
        }
        @keyframes spin {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">

    <!-- Kontainer Utama -->
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white shadow-xl rounded-xl p-6 md:p-10">

            <h1 class="text-3xl font-extrabold text-gray-800 mb-8 flex items-center border-b pb-3">
                <i class="fas fa-calendar-plus text-green-600 mr-3"></i> Form Tambah Reservasi Pendakian
            </h1>

            <!-- Message Box (Alert) -->
            <div id="statusMessage" class="hidden border px-4 py-3 rounded relative mb-6" role="alert">
                <p class="font-bold inline-block mr-2" id="messageTitle"></p>
                <p class="inline-block" id="messageText"></p>
            </div>

            <form id="reservasiForm" class="space-y-8">
                
                <!-- Bagian 1.A: Pilih Ketua Rombongan (Dari Tabel Profiles) -->
                <div class="border border-gray-200 p-6 rounded-lg bg-gray-50">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Ketua Rombongan</h3>
                    <label for="id_pengguna" class="block text-sm font-medium text-gray-700 mb-1">Pilih Ketua Rombongan (Dari Data Profiles)</label>
                    <select id="id_pengguna" required class="form-input" disabled>
                        <option value="" disabled selected>-- Pilih Pengguna --</option>
                        <!-- Pilihan akan dimuat oleh JavaScript -->
                    </select>
                    <p id="loadingProfiles" class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="fas fa-spinner animate-spin mr-2"></i> Memuat daftar pengguna...
                    </p>
                </div>

                <!-- Bagian 1.B: Informasi Dasar & Perhitungan Harga -->
                <div class="border border-gray-200 p-6 rounded-lg bg-gray-50">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Detail Pemesanan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="tanggal_pendakian" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pendakian</label>
                            <input type="date" id="tanggal_pendakian" required class="form-input">
                            <p id="kuotaStatus" class="text-xs mt-1 text-gray-500"></p>
                        </div>

                        <div>
                            <label for="jumlah_pendaki" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pendaki (Total)</label>
                            <input type="number" id="jumlah_pendaki" required min="1" value="1" class="form-input">
                        </div>

                        <div>
                            <label for="jumlah_tiket_parkir" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tiket Parkir (Unit)</label>
                            <input type="number" id="jumlah_tiket_parkir" min="0" value="0" class="form-input">
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-md">
                        <p class="text-lg font-bold text-green-700">Total Harga Estimasi:</p>
                        <p id="totalHargaDisplay" class="text-2xl font-extrabold text-green-800 mt-1">Rp 0</p>
                        <input type="hidden" id="total_harga" value="0">
                        <input type="hidden" id="harga_tiket_per_orang">
                        <input type="hidden" id="harga_parkir_per_unit">
                    </div>
                </div>

                <!-- Bagian 2: Data Anggota Rombongan (pendaki_rombongan) -->
                <div class="border border-gray-200 p-6 rounded-lg bg-gray-50">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Data Anggota Rombongan</h3>
                    
                    <div id="anggotaRombonganContainer" class="space-y-4">
                        <!-- Formulir Anggota akan dibuat di sini oleh JS -->
                    </div>
                </div>

                <!-- Bagian 3: Barang Bawaan (barang_bawaan_sampah) -->
                <div class="border border-gray-200 p-6 rounded-lg bg-gray-50">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Barang Bawaan (Potensi Sampah)</h3>
                    
                    <div id="barangBawaanContainer" class="space-y-4">
                        <!-- Barang Bawaan akan ditambahkan di sini -->
                    </div>
                    
                    <button type="button" id="addBarangBtn" class="mt-4 text-sm bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-md transition duration-150">
                        <i class="fas fa-plus mr-1"></i> Tambah Barang
                    </button>

                    <div class="mt-4 p-3 bg-yellow-100 border-l-4 border-yellow-500 text-sm text-yellow-800 rounded-md">
                        Total Potensi Sampah: <span id="totalPotensiSampah" class="font-bold">0</span> Unit
                        <input type="hidden" id="jumlah_potensi_sampah" value="0">
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-6">
                    <button type="submit" id="submitBtn" disabled
                            class="w-full py-3 px-4 rounded-md shadow-lg text-lg font-medium text-white 
                                   bg-gray-400 cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i> Buat Reservasi
                    </button>
                </div>
            </form>

        </div>
    </div>
    
    <!-- Bagian JavaScript -->
    <script>
        // ======================================================================
        // KONFIGURASI PATH API PHP (PENTING!)
        // Menggunakan path absolut relatif ke domain root (/simaksi/admin/api/...)
        // ======================================================================
        const BASE_URL = '<?php echo $base_url; ?>'; 
        const RESERVATION_API_URL = `${BASE_URL}/api/bikin_reservasi.php`; 
        const QUOTA_API_URL = `${BASE_URL}/api/kuota.php`; 
        const PROFILES_API_URL = `${BASE_URL}/api/pengguna.php`;

        // --- Variabel Global ---
        let hargaTiket = 0;
        let hargaParkir = 0;
        let isQuotaSufficient = false;

        // --- DOM Elements ---
        const idPenggunaSelect = document.getElementById('id_pengguna');
        const tanggalInput = document.getElementById('tanggal_pendakian');
        const jumlahPendakiInput = document.getElementById('jumlah_pendaki');
        const jumlahParkirInput = document.getElementById('jumlah_tiket_parkir');
        const totalHargaDisplay = document.getElementById('totalHargaDisplay');
        const totalHargaInput = document.getElementById('total_harga');
        const kuotaStatus = document.getElementById('kuotaStatus');
        const anggotaContainer = document.getElementById('anggotaRombonganContainer');
        const barangContainer = document.getElementById('barangBawaanContainer');
        const submitBtn = document.getElementById('submitBtn');
        const addBarangBtn = document.getElementById('addBarangBtn');
        const totalPotensiSampahInput = document.getElementById('jumlah_potensi_sampah');
        const loadingProfiles = document.getElementById('loadingProfiles');

        // --- Fungsi Helper ---

        /** Memformat angka menjadi Rupiah */
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        /** Menampilkan pesan status di atas formulir */
        function displayMessage(type, title, text) {
            const statusDiv = document.getElementById('statusMessage');
            const titleEl = document.getElementById('messageTitle');
            const textEl = document.getElementById('messageText');

            statusDiv.className = 'border px-4 py-3 rounded relative mb-6';

            if (type === 'success') {
                statusDiv.classList.add('bg-green-100', 'border-green-400', 'text-green-700');
            } else if (type === 'error') {
                statusDiv.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
            } else {
                statusDiv.classList.add('bg-yellow-100', 'border-yellow-400', 'text-yellow-700');
            }

            titleEl.textContent = title;
            textEl.textContent = text;
            statusDiv.classList.remove('hidden');
            setTimeout(() => statusDiv.classList.add('hidden'), 10000); 
        }

        // --- Logika Anggota Rombongan & Barang Bawaan ---

        /** Membuat markup HTML untuk satu form anggota pendaki */
        function createMemberForm(index, isLeader = false) {
            const memberId = `member_${index}`;
            const isLeaderText = isLeader ? '<span class="text-xs text-red-500 font-semibold">(Ketua Rombongan / Diri Sendiri)</span>' : '';
            
            const html = `
                <div id="${memberId}" class="p-4 border border-gray-200 rounded-md bg-white shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-3">Pendaki ${index + 1} ${isLeaderText}</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">Nama Lengkap</label>
                            <input type="text" data-member-field="nama_lengkap" required 
                                class="form-input text-sm" placeholder="Nama Lengkap" />
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">NIK</label>
                            <input type="text" data-member-field="nik" required 
                                class="form-input text-sm" placeholder="Nomor Induk Kependudukan" />
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">Alamat</label>
                            <input type="text" data-member-field="alamat" required 
                                class="form-input text-sm" placeholder="Alamat Sesuai KTP" />
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">Nomor Telepon</label>
                            <input type="tel" data-member-field="nomor_telepon" required 
                                class="form-input text-sm" placeholder="Nomor Telepon Aktif" />
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">Kontak Darurat</label>
                            <input type="tel" data-member-field="kontak_darurat" required 
                                class="form-input text-sm" placeholder="Nomor Kontak Darurat" />
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">URL Surat Sehat (Opsional)</label>
                            <input type="url" data-member-field="url_surat_sehat" 
                                class="form-input text-sm" placeholder="Link ke Dokumen Surat Sehat" />
                        </div>
                    </div>
                </div>
            `;
            const div = document.createElement('div');
            div.innerHTML = html.trim();
            return div.firstChild;
        }

        function updateMemberForms(count) {
            anggotaContainer.innerHTML = '';
            // Batasi jumlah anggota minimal 1
            const actualCount = Math.max(1, count); 
            for (let i = 0; i < actualCount; i++) {
                anggotaContainer.appendChild(createMemberForm(i, i === 0)); 
            }
            checkFormValidity();
        }

        /** Membuat markup HTML untuk satu form barang bawaan (potensi sampah) */
        function createBarangForm(index) {
            const barangId = `barang_${index}`;
            const html = `
                <div id="${barangId}" class="p-3 border border-gray-200 rounded-md bg-white shadow-sm flex items-center space-x-3">
                    <div class="flex-grow grid grid-cols-3 gap-3">
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">Nama Barang</label>
                            <input type="text" data-barang-field="nama_barang" required 
                                class="form-input text-sm" placeholder="Cth: Botol Plastik" />
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">Jenis Sampah</label>
                            <select data-barang-field="jenis_sampah" required class="form-input text-sm">
                                <option value="">Pilih Jenis</option>
                                <option value="organik">Organik</option>
                                <option value="anorganik">Anorganik</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-600">Jumlah Unit</label>
                            <input type="number" data-barang-field="jumlah" required min="1" value="1"
                                class="form-input text-sm barang-jumlah" />
                        </div>
                    </div>
                    <button type="button" data-remove-id="${barangId}" 
                        class="remove-barang-btn text-red-500 hover:text-red-700 p-2 transition duration-150">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
            const div = document.createElement('div');
            div.innerHTML = html.trim();
            return div.firstChild;
        }

        function updatePotensiSampah() {
            const total = Array.from(document.querySelectorAll('.barang-jumlah')).reduce((sum, input) => {
                return sum + (parseInt(input.value) || 0);
            }, 0);
            document.getElementById('totalPotensiSampah').textContent = total;
            totalPotensiSampahInput.value = total;
            checkFormValidity();
        }

        // --- Logika API Data Harga, Kuota, & Profiles ---

        async function fetchProfiles() {
            loadingProfiles.classList.add('flex');
            idPenggunaSelect.disabled = true;

            try {
                const response = await fetch(PROFILES_API_URL); 
                if (!response.ok) {
                    throw new Error(`Gagal mengambil data profiles. Status HTTP: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.status === 'success' && result.data && result.data.length > 0) {
                    result.data.forEach(profile => {
                        const option = document.createElement('option');
                        option.value = profile.id;
                        option.textContent = `${profile.nama_lengkap} (${profile.id.substring(0, 8)}...)`;
                        idPenggunaSelect.appendChild(option);
                    });
                    loadingProfiles.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-2"></i> ${result.data.length} pengguna dimuat.`;
                    idPenggunaSelect.disabled = false;
                } else {
                    loadingProfiles.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> Tidak ada data pengguna ditemukan! (Cek: api/pengguna.php)';
                    displayMessage('error', 'Error Pengguna:', 'API Pengguna tidak mengembalikan data. Pastikan tabel Profiles terisi.');
                }
            } catch (error) {
                loadingProfiles.innerHTML = `<i class="fas fa-times-circle text-red-500 mr-2"></i> Error: ${error.message}. Path: ${PROFILES_API_URL}`;
                displayMessage('error', 'Error Pengguna:', `Gagal memuat daftar Ketua Rombongan. Cek Konsol (F12) untuk detail URL yang salah.`);
            } finally {
                loadingProfiles.classList.remove('flex');
                checkFormValidity();
            }
        }
        
        async function fetchPricing() {
            try {
                const response = await fetch(RESERVATION_API_URL); 
                if (!response.ok) throw new Error('Gagal mengambil data harga.');
                const result = await response.json(); // Mengambil seluruh body respons
                
                if (result.status === 'success' && result.data) {
                    const pricingMap = result.data;
                    
                    // --- MEMBACA HARGA DARI MAP SESUAI NAMA_ITEM DATABASE ---
                    hargaTiket = pricingMap['tiket_masuk'] || 0; 
                    hargaParkir = pricingMap['tiket_parkir'] || 0;
                    
                    document.getElementById('harga_tiket_per_orang').value = hargaTiket;
                    document.getElementById('harga_parkir_per_unit').value = hargaParkir;
                    calculateTotal(); 
                } else {
                    displayMessage('warning', 'Konfigurasi Harga Belum Ada:', 'Menggunakan harga 0. Cek data di tabel pengaturan_biaya.');
                }
            } catch (error) {
                console.error('Error fetching pricing:', error);
                displayMessage('error', 'Error Harga:', 'Gagal mengambil data harga tiket. Cek API Bikin Reservasi GET.');
            }
        }

        async function checkQuota() {
            const tanggal = tanggalInput.value;
            const jumlahPendaki = parseInt(jumlahPendakiInput.value) || 0;

            if (!tanggal || jumlahPendaki === 0) {
                kuotaStatus.textContent = '';
                isQuotaSufficient = false;
                checkFormValidity();
                return;
            }

            kuotaStatus.innerHTML = '<i class="fas fa-spinner animate-spin-fast mr-1"></i> Memeriksa kuota...';
            kuotaStatus.classList.replace('text-red-600', 'text-gray-500');


            try {
                const response = await fetch(`${QUOTA_API_URL}?tanggal=${tanggal}`);
                const data = await response.json();

                if (data.status === 'error') throw new Error(data.message);
                
                const kuotaData = data.data; 
                let kuotaMaksimal = 50; 
                let kuotaTerpesan = 0;

                if (kuotaData && kuotaData.length > 0) {
                    kuotaMaksimal = kuotaData[0].kuota_maksimal;
                    kuotaTerpesan = kuotaData[0].kuota_terpesan;
                }

                const sisaKuota = kuotaMaksimal - kuotaTerpesan;
                
                if (jumlahPendaki > sisaKuota) {
                    kuotaStatus.innerHTML = `❌ Kuota tidak cukup. Sisa: ${sisaKuota} / ${kuotaMaksimal}`;
                    kuotaStatus.classList.replace('text-gray-500', 'text-red-600');
                    isQuotaSufficient = false;
                } else {
                    kuotaStatus.innerHTML = `✅ Kuota tersedia: ${sisaKuota} / ${kuotaMaksimal}`;
                    kuotaStatus.classList.replace('text-gray-500', 'text-green-600');
                    isQuotaSufficient = true;
                }

            } catch (error) {
                kuotaStatus.innerHTML = `⚠️ Gagal memeriksa kuota.`;
                kuotaStatus.classList.replace('text-gray-500', 'text-red-600');
                isQuotaSufficient = false;
            }
            checkFormValidity();
        }

        // --- Logika Perhitungan & Validasi Lanjutan ---

        function calculateTotal() {
            const jumlahPendaki = parseInt(jumlahPendakiInput.value) || 0;
            const jumlahParkir = parseInt(jumlahParkirInput.value) || 0;

            const totalTiket = jumlahPendaki * hargaTiket;
            const totalParkir = jumlahParkir * hargaParkir;
            const grandTotal = totalTiket + totalParkir;

            totalHargaInput.value = grandTotal;
            totalHargaDisplay.textContent = formatRupiah(grandTotal);
        }

        function checkFormValidity() {
            const form = document.getElementById('reservasiForm');
            const isFormComplete = form.checkValidity();
            const isPendakiCountValid = parseInt(jumlahPendakiInput.value) > 0;
            const isTanggalSelected = tanggalInput.value.length > 0;
            const isKetuaSelected = idPenggunaSelect.value.length > 0; 
            
            // Periksa apakah semua field anggota rombongan terisi
            const allMembersComplete = Array.from(anggotaContainer.querySelectorAll('[required]')).every(input => input.value.trim().length > 0);
            
            // Periksa apakah semua field barang bawaan terisi
            const allBarangComplete = Array.from(barangContainer.querySelectorAll('[required]')).every(input => input.value.trim().length > 0);

            const isDataValid = isFormComplete && isPendakiCountValid && isTanggalSelected && isKetuaSelected && isQuotaSufficient && allMembersComplete && allBarangComplete;

            submitBtn.disabled = !isDataValid;
            if (isDataValid) {
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            }
        }

        /** Handler saat form disubmit */
        async function handleFormSubmit(event) {
            event.preventDefault();
            
            if (submitBtn.disabled) {
                displayMessage('warning', 'Validasi Gagal:', 'Pastikan semua field terisi, Ketua Rombongan terpilih, dan kuota mencukupi.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i> Memproses...';
            
            const formData = {
                tanggal_pendakian: tanggalInput.value,
                jumlah_pendaki: parseInt(jumlahPendakiInput.value) || 0,
                jumlah_tiket_parkir: parseInt(jumlahParkirInput.value) || 0,
                total_harga: parseInt(totalHargaInput.value) || 0,
                jumlah_potensi_sampah: parseInt(totalPotensiSampahInput.value) || 0,
                id_pengguna: idPenggunaSelect.value, 
                anggota_rombongan: getAnggotaData(),
                barang_bawaan: getBarangBawaanData()
            };
            
            try {
                const response = await fetch(RESERVATION_API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json' 
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();
                
                if (response.ok) { 
                    displayMessage('success', 'Reservasi Berhasil!', `Kode Reservasi: ${result.kode_reservasi}.`);
                    
                    // Reset formulir setelah sukses
                    document.getElementById('reservasiForm').reset();
                    updateMemberForms(1); 
                    barangContainer.innerHTML = ''; 
                    calculateTotal();
                } else {
                    throw new Error(result.error || 'Terjadi kesalahan saat membuat reservasi.');
                }
            } catch (error) {
                console.error('Submission Error:', error);
                displayMessage('error', 'Gagal:', error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Buat Reservasi';
                checkFormValidity();
            }
        }

        // Fungsi untuk mengumpulkan data anggota
        function getAnggotaData() {
            const anggotaRombongan = [];
            Array.from(anggotaContainer.children).forEach(memberDiv => {
                const member = {};
                memberDiv.querySelectorAll('[data-member-field]').forEach(input => {
                    member[input.dataset.memberField] = input.value;
                });
                anggotaRombongan.push(member);
            });
            return anggotaRombongan;
        }

        // Fungsi untuk mengumpulkan data barang bawaan
        function getBarangBawaanData() {
            const barangBawaan = [];
            Array.from(barangContainer.children).forEach(barangDiv => {
                const barang = {};
                barangDiv.querySelectorAll('[data-barang-field]').forEach(input => {
                    barang[input.dataset.barangField] = input.value;
                });
                barangBawaan.push(barang);
            });
            return barangBawaan;
        }


        // --- Event Listeners & Inisialisasi ---
        document.addEventListener('DOMContentLoaded', () => {
            fetchProfiles(); 
            fetchPricing();
            
            tanggalInput.min = new Date().toISOString().split('T')[0];

            idPenggunaSelect.addEventListener('change', checkFormValidity);
            tanggalInput.addEventListener('change', checkQuota);
            
            // PENTING: Event Listeners untuk Perhitungan Otomatis
            // Listener untuk Jumlah Pendaki
            jumlahPendakiInput.addEventListener('input', () => {
                updateMemberForms(parseInt(jumlahPendakiInput.value) || 0);
                checkQuota();
                calculateTotal(); 
            });
            // Listener untuk Jumlah Tiket Parkir
            jumlahParkirInput.addEventListener('input', calculateTotal);
            
            addBarangBtn.addEventListener('click', () => {
                const count = barangContainer.children.length;
                barangContainer.appendChild(createBarangForm(count));
                updatePotensiSampah();
            });

            barangContainer.addEventListener('click', (e) => {
                if (e.target.closest('.remove-barang-btn')) {
                    const idToRemove = e.target.closest('button').dataset.removeId;
                    document.getElementById(idToRemove).remove();
                    updatePotensiSampah();
                }
            });
            barangContainer.addEventListener('input', (e) => {
                if (e.target.classList.contains('barang-jumlah') || e.target.closest('[data-barang-field]')) {
                    updatePotensiSampah();
                }
            });

            // Delegasi event untuk validitas form
            document.getElementById('reservasiForm').addEventListener('input', checkFormValidity);

            // Form Submit
            document.getElementById('reservasiForm').addEventListener('submit', handleFormSubmit);

            // Inisialisasi form anggota pertama
            updateMemberForms(1); 
        });
    </script>

</body>
</html>
