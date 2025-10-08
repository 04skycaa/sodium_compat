document.addEventListener('DOMContentLoaded', () => {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.querySelector('.toggle-password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClose = document.getElementById('eye-close');

        if (toggleButton && passwordInput && eyeOpen && eyeClose) {
            toggleButton.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'text') {
                    eyeOpen.style.display = 'none';
                    eyeClose.style.display = 'block';
                } else {
                    eyeOpen.style.display = 'block';
                    eyeClose.style.display = 'none';
                }
            });

            toggleButton.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleButton.click();
                }
            });
        }
    });

document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchInput");
  const rows = document.querySelectorAll(".data-table tbody tr");
  const cards = document.querySelectorAll(".card");

  // ✨ Fade-in animasi untuk setiap card
  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";
    setTimeout(() => {
      card.style.transition = "all 0.6s ease";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, 150 * index);
  });

  // 🔍 Live search
  searchInput.addEventListener("keyup", function () {
    const value = this.value.toLowerCase();
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(value) ? "" : "none";
    });
  });

  // 🌿 Efek hover tabel
  rows.forEach(row => {
    row.addEventListener("mouseenter", () => {
      row.style.backgroundColor = "#eaf7ea";
      row.style.transition = "background 0.3s";
    });
    row.addEventListener("mouseleave", () => {
      row.style.backgroundColor = "";
    });
  });

  // 🧩 Tombol aksi (placeholder)
  document.getElementById("addBtn").addEventListener("click", () => {
    alert("Fitur tambah pengeluaran akan ditambahkan nanti 🚀");
  });

  document.getElementById("editBtn").addEventListener("click", () => {
    alert("Fitur edit data pengeluaran");
  });

  document.getElementById("deleteBtn").addEventListener("click", () => {
    const checked = document.querySelectorAll(".select-row:checked");
    if (checked.length === 0) {
      alert("Pilih data pengeluaran yang ingin dihapus!");
      return;
    }
    alert(`Menghapus ${checked.length} data pengeluaran`);
  });

  document.getElementById("excelBtn").addEventListener("click", () => {
    alert("Unduh dalam format Excel atau PDF akan ditambahkan 💾");
  });
});

