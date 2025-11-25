let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");
let toggle = document.querySelector(".toggle");
let hoverArea = navigation;
if (toggle) {
  toggle.onclick = function (e) {
    e.preventDefault(); 
    navigation.classList.toggle("active");
    main.classList.toggle("active");
  };
}

// fungsi hover untuk membuka sidebar saat mouse berada di area sidebar
if (hoverArea) {
  let closeTimeout;
  const hoverDelay = 100; 

  hoverArea.addEventListener("mouseenter", function () {
    clearTimeout(closeTimeout); 
    // Buka sidebar hanya jika saat ini dalam mode tertutup (active)
    if (navigation.classList.contains("active")) {
        navigation.classList.remove("active");
        main.classList.remove("active");
    }
  });

  hoverArea.addEventListener("mouseleave", function () {
    // Tetapkan timeout untuk menutup setelah jeda singkat
    closeTimeout = setTimeout(() => {
        navigation.classList.add("active");
        main.classList.add("active");
    }, hoverDelay); 
  });
}

// untuk menampilkan tanggal dan waktu serta sapaan berdasarkan waktu
function updateDateTime() {
    const now = new Date();
    const hour = now.getHours(); 

    let greeting = 'Selamat Malam';

    if (hour >= 5 && hour < 11) {
        greeting = 'Selamat Pagi'; 
    } else if (hour >= 11 && hour < 15) {
        greeting = 'Selamat Siang'; 
    } else if (hour >= 15 && hour < 18) {
        greeting = 'Selamat Sore'; 
    } else if (hour >= 18 && hour < 21) {
        greeting = 'Selamat Malam'; 
    }

    // Update elemen sapaan di HTML
    const greetingElement = document.getElementById('topbar-greeting');
    if (greetingElement) {
        greetingElement.textContent = greeting;
    }
    
    const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
    const currentTime = now.toLocaleTimeString('id-ID', timeOptions); 
    document.getElementById('current-time').textContent = currentTime.replace(/\./g, ':');
    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const currentDate = now.toLocaleDateString('id-ID', dateOptions);
    document.getElementById('current-date').textContent = currentDate;

    // Perbarui setiap detik
    setTimeout(updateDateTime, 1000);
}
function updateActiveMenuTitle() {
}

// Jalankan fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    updateDateTime();
    updateActiveMenuTitle(); // Dipanggil untuk inisialisasi
});