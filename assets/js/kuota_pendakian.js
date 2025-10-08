document.addEventListener("DOMContentLoaded", () => {
  const modalOverlay = document.getElementById("modalOverlay");
  const modalBody = document.getElementById("modalBody");

  // === TAMPILKAN MODAL TAMBAH ===
  document.getElementById("tambahKuota").addEventListener("click", async () => {
    const response = await fetch("kuota_pendakian/tambah.php");
    const html = await response.text();
    modalBody.innerHTML = html;
    modalOverlay.style.display = "flex";
    modalOverlay.style.animation = "fadeIn 0.4s ease";
  });

  // === TAMPILKAN MODAL EDIT ===
  document.body.addEventListener("click", async (e) => {
    if (e.target.classList.contains("btn-edit")) {
      const id = e.target.dataset.id;
      const response = await fetch(`kuota_pendakian/edit.php?id_kuota=${id}`);
      const html = await response.text();
      modalBody.innerHTML = html;
      modalOverlay.style.display = "flex";
      modalOverlay.style.animation = "fadeIn 0.4s ease";
    }
  });

  // === TUTUP MODAL (klik X atau area luar) ===
  document.body.addEventListener("click", (e) => {
    if (e.target.id === "closeModal" || e.target === modalOverlay) {
      modalOverlay.style.animation = "fadeOut 0.3s ease";
      setTimeout(() => {
        modalOverlay.style.display = "none";
        modalBody.innerHTML = "";
      }, 300);
    }
  });

  // === SUBMIT FORM TAMBAH KUOTA ===
  document.body.addEventListener("submit", async (e) => {
    if (e.target && e.target.id === "formTambahKuota") {
      e.preventDefault();

      const formData = new FormData(e.target);
      const response = await fetch("kuota_pendakian/tambah.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.status === "success") {
        Swal.fire({
          icon: "success",
          title: result.message,
          showConfirmButton: false,
          timer: 1500,
        });

        modalOverlay.style.animation = "fadeOut 0.3s ease";
        setTimeout(() => {
          modalOverlay.style.display = "none";
          modalBody.innerHTML = "";
          location.reload();
        }, 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: result.message,
        });
      }
    }
  });

  // === SUBMIT FORM EDIT KUOTA ===
  document.body.addEventListener("submit", async (e) => {
    if (e.target && e.target.id === "formEditKuota") {
      e.preventDefault();

      const formData = new FormData(e.target);
      const response = await fetch("kuota_pendakian/edit.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.status === "success") {
        Swal.fire({
          icon: "success",
          title: result.message,
          showConfirmButton: false,
          timer: 1500,
        });

        modalOverlay.style.animation = "fadeOut 0.3s ease";
        setTimeout(() => {
          modalOverlay.style.display = "none";
          modalBody.innerHTML = "";
          location.reload();
        }, 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: result.message,
        });
      }
    }
  });

  // hapus kuota
  document.body.addEventListener("click", async (e) => {
    if (e.target.classList.contains("btn-hapus")) {
      const id = e.target.dataset.id;

      const confirmResult = await Swal.fire({
        title: "Hapus Data?",
        text: "Data yang dihapus tidak bisa dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#35542E",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
      });

      if (confirmResult.isConfirmed) {
        const formData = new FormData();
        formData.append("id_kuota", id);

        const response = await fetch("kuota_pendakian/hapus.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
          Swal.fire({
            icon: "success",
            title: result.message,
            showConfirmButton: false,
            timer: 1200,
          });
          setTimeout(() => location.reload(), 1300);
        } else {
          Swal.fire({
            icon: "error",
            title: "Gagal",
            text: result.message,
          });
        }
      }
    }
  });
});

// hapus
document.body.addEventListener("click", async (e) => {
  if (e.target.classList.contains("btn-delete")) {
    const id = e.target.dataset.id;
    const response = await fetch(`kuota_pendakian/hapus.php?id_kuota=${id}`);
    const html = await response.text();
    modalBody.innerHTML = html;
    modalOverlay.style.display = "flex";
    modalOverlay.style.animation = "fadeIn 0.4s ease";
  }
});