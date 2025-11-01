import { supabase } from './config.js';

const tableBody = document.getElementById('reservasi-body');
const loading = document.getElementById('loading');
const container = document.getElementById('reservasi-container');

// Ambil data dari tabel reservasi
async function loadReservasi() {
    const { data, error } = await supabase
        .from('reservasi')
        .select('*')
        .order('id', { ascending: false });

    if (error) {
        console.error('Gagal mengambil data:', error.message);
        loading.innerHTML = `<p class="text-red-500">Gagal memuat data reservasi</p>`;
        return;
    }

    if (!data || data.length === 0) {
        loading.innerHTML = `<p class="text-gray-600">Belum ada data reservasi.</p>`;
        return;
    }

    // Hapus loader dan tampilkan tabel
    loading.style.display = 'none';
    container.classList.remove('hidden');

    // Isi tabel dengan data
    data.forEach((row, i) => {
        const tr = document.createElement('tr');
        tr.className = i % 2 === 0 ? 'bg-gray-50 hover:bg-green-50 transition-all duration-200' : 'hover:bg-green-50 transition-all duration-200';
        tr.innerHTML = `
            <td class="py-3 px-4">${row.id}</td>
            <td class="py-3 px-4">${row.tanggal_pendakian || '-'}</td>
            <td class="py-3 px-4">${row.nama_ketua || '-'}</td>
            <td class="py-3 px-4">${row.jumlah_pendaki || 0}</td>
            <td class="py-3 px-4 font-semibold text-green-700">Rp ${Number(row.total_harga || 0).toLocaleString()}</td>
            <td class="py-3 px-4">
                <span class="px-3 py-1 rounded-full text-sm font-medium ${
                    row.status === 'selesai'
                        ? 'bg-green-100 text-green-700'
                        : row.status === 'dibatalkan'
                        ? 'bg-red-100 text-red-600'
                        : 'bg-yellow-100 text-yellow-700'
                }">${row.status || 'Belum Dikonfirmasi'}</span>
            </td>
        `;
        tableBody.appendChild(tr);
    });
}

// Jalankan saat halaman dimuat
loadReservasi();
