// ==========================
// SIDEBAR NAVIGASI
// ==========================
let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => item.classList.remove("hovered"));
  this.classList.add("hovered");
}
list.forEach((item) => item.addEventListener("click", activeLink));

let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

if (toggle) {
  toggle.onclick = function () {
    navigation.classList.toggle("active");
    main.classList.toggle("active");
  };
}

// ==========================
// ANIMASI BARIS TABEL + FILTER PENCARIAN
// ==========================
document.addEventListener("DOMContentLoaded", () => {
  const rows = document.querySelectorAll(".data-table tbody tr");

  rows.forEach((row, i) => {
    row.style.opacity = 0;
    setTimeout(() => {
      row.style.transition = "0.5s";
      row.style.opacity = 1;
      row.style.transform = "translateY(0)";
    }, 100 * i);
  });

  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("keyup", () => {
      const val = searchInput.value.toLowerCase();
      rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
      });
    });
  }
});
document.addEventListener("DOMContentLoaded", function () {
  const modalOverlay = document.getElementById("modalOverlay");
  const modalBody = document.getElementById("modalBody");
  const closeModal = document.getElementById("closeModal");
  const addBtn = document.getElementById("addBtn");

  function openModal(url) {
    modalOverlay.style.display = "flex";
    modalBody.innerHTML = "Memuat...";

    fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
      .then(res => res.text())
      .then(html => {
        modalBody.innerHTML = html;

        // Tambahkan listener submit form AJAX
        const form = modalBody.querySelector("#formPengeluaran");
        if (form) {
          form.addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(form.action || url, {
              method: "POST",
              body: formData,
              headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === "success") {
                alert(data.message);
                modalOverlay.style.display = "none";
                window.location.reload(); // reload halaman agar tabel update
              } else {
                alert(data.message);
              }
            })
            .catch(err => console.error(err));
          });
        }
      })
      .catch(err => {
        modalBody.innerHTML = "<p>Gagal memuat form.</p>";
        console.error(err);
      });
  }

  // Tombol Tambah
  if (addBtn) {
    addBtn.addEventListener("click", () => openModal("pembukuan/tambah_pengeluaran.php"));
  }

  // Tombol Edit (di kolom aksi)
  document.querySelectorAll(".edit-btn").forEach(btn => {
    btn.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      openModal(`pembukuan/tambah_pengeluaran.php?id=${id}`);
    });
  });

  // Tutup modal
  if (closeModal) {
    closeModal.addEventListener("click", () => modalOverlay.style.display = "none");
  }
  if (modalOverlay) {
    modalOverlay.addEventListener("click", e => {
      if (e.target === modalOverlay) modalOverlay.style.display = "none";
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const rows = document.querySelectorAll(".data-table tbody tr");

  rows.forEach((row, i) => {
    row.style.opacity = 0;
    row.style.transform = "translateY(15px)";
    setTimeout(() => {
      row.style.transition = "0.5s";
      row.style.opacity = 1;
      row.style.transform = "translateY(0)";
    }, 100 * i);
  });

  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("keyup", () => {
      const val = searchInput.value.toLowerCase();
      rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
      });
    });
  }
});

// ==========================
// MODAL POPUP (EDIT / HAPUS / TAMBAH)
// ==========================
//document.addEventListener("DOMContentLoaded", function () {
// const modalOverlay = document.getElementById("modalOverlay");
//  const modalBody = document.getElementById("modalBody");
//  const closeModal = document.getElementById("closeModal");

// function openModal(url) {
//    modalOverlay.style.display = "flex";
//   modalBody.innerHTML = "Memuat...";

 //   fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    //  .then(res => res.text())
     // .then(html => (modalBody.innerHTML = html))
     // .catch(() => (modalBody.innerHTML = "<p>Gagal memuat konten.</p>"));
 // }

 // document.querySelectorAll(".edit-btn").forEach(btn => {
   // btn.addEventListener("click", function () {
 //     const id = this.getAttribute("data-id");
  //    openModal(`edit_user.php?id=${id}`);
 //   });
 // });

//  document.querySelectorAll(".delete-btn").forEach(btn => {
  //  btn.addEventListener("click", function () {
 //     const id = this.getAttribute("data-id");
  //    if (confirm("Yakin ingin menghapus user ini?")) {
      //  fetch(`hapus_user.php?id=${id}`)
    //      .then(res => res.json())
        //  .then(data => {
       //     alert(data.message);
       //     if (data.status === "success") window.location.reload();
     //     });
  //    }
  //  });
//  });

 // if (closeModal) {
 //   closeModal.addEventListener("click", () => (modalOverlay.style.display = "none"));
 // }

 ///if (modalOverlay) {
  //  modalOverlay.addEventListener("click", e => {
   //   if (e.target === modalOverlay) modalOverlay.style.display = "none";
   // });
 // }
//});
