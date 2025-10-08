document.addEventListener("DOMContentLoaded", () => {
  const modalOverlay = document.getElementById("modalOverlay");
  const modalBody = document.getElementById("modalBody");
  const closeModal = document.getElementById("closeModal");

  function closeModalNow() {
    modalOverlay.style.animation = "fadeOut 0.25s ease";
    setTimeout(() => { modalOverlay.style.display = "none"; modalBody.innerHTML = ""; }, 260);
  }

  // edit
  document.addEventListener("click", (e) => {
    if (e.target && e.target.matches(".edit-btn")) {
      const id = e.target.getAttribute("data-id");
      modalOverlay.style.display = "flex";
      modalOverlay.style.animation = "fadeIn 0.25s ease";
      modalBody.innerHTML = "<p>🔄 Memuat data pengguna...</p>";

      fetch(`/simaksi/admin/management_user/edit_user.php?id=${id}`)
        .then(res => {
          if (!res.ok) throw new Error("Gagal memuat edit form");
          return res.text();
        })
        .then(html => {
          modalBody.innerHTML = html;
          const form = modalBody.querySelector("#editUserForm");
          if (form) {
            form.addEventListener("submit", (ev) => {
              ev.preventDefault();
              const formData = new FormData(form);
              fetch('/simaksi/admin/management_user/update_user.php', { method: 'POST', body: formData })
                .then(r => r.text())
                .then(text => {
                  if (text.trim() === 'success') {
                    const userId = formData.get('id_pengguna');
                    updateRowInTable(userId, formData);
                    closeModalNow();
                  } else {
                    alert('Gagal memperbarui: ' + text);
                  }
                })
                .catch(err => {
                  console.error(err);
                  alert('Terjadi kesalahan saat mengupdate.');
                });
            });
          }
        })
        .catch(err => {
          modalBody.innerHTML = `<p style="color:red;">${err.message}</p>`;
        });
    }
  });

  // delete
  document.addEventListener("click", (e) => {
    if (e.target && e.target.matches(".delete-btn")) {
      const btn = e.target;
      const id = btn.getAttribute("data-id");

      const confirmBox = document.createElement("div");
      confirmBox.className = "confirm-box show";
      confirmBox.innerHTML = `
        <div class="confirm-content">
          <h3>⚠️ Konfirmasi Hapus</h3>
          <p>Hapus pengguna ini?</p>
          <div style="display:flex;gap:12px;justify-content:center;margin-top:8px;">
            <button class="btn red confirm-yes">Hapus</button>
            <button class="btn dark confirm-no">Batal</button>
          </div>
        </div>`;
      document.body.appendChild(confirmBox);

      confirmBox.querySelector(".confirm-no").addEventListener("click", () => {
        confirmBox.classList.remove("show");
        setTimeout(() => confirmBox.remove(), 220);
      });

      confirmBox.querySelector(".confirm-yes").addEventListener("click", () => {
        fetch(`/simaksi/admin/management_user/hapus_user.php?id=${id}`)
          .then(r => r.text())
          .then(text => {
            if (text.trim() === 'success') {
              const row = btn.closest("tr");
              row.style.transition = "all 0.45s ease";
              row.style.backgroundColor = "#ffdddd";
              row.style.opacity = "0";
              setTimeout(() => row.remove(), 420);
            } else {
              alert('Gagal menghapus: ' + text);
            }
          })
          .catch(err => {
            console.error(err);
            alert('Kesalahan koneksi saat menghapus.');
          })
          .finally(() => {
            confirmBox.classList.remove("show");
            setTimeout(() => confirmBox.remove(), 220);
          });
      });
    }
  });

  closeModal.addEventListener("click", () => closeModalNow());
  window.addEventListener("click", (e) => { if (e.target === modalOverlay) closeModalNow(); });

  function updateRowInTable(id, formData) {
    const rows = document.querySelectorAll("#userTable tbody tr");
    for (const r of rows) {
      if (r.dataset.id === String(id)) {
        r.cells[1].textContent = formData.get('nama_lengkap') || '';
        r.cells[2].textContent = formData.get('email') || '';
        r.cells[3].textContent = formData.get('nomor_telepon') || '';
        r.cells[4].textContent = formData.get('alamat') || '';
        r.cells[5].textContent = formData.get('peran') || '';
        break;
      }
    }
  }
});

document.getElementById('applyFilter').addEventListener('click', function() {
    const date = document.getElementById('filterDate').value;
    const name = document.getElementById('filterName').value;
    const role = document.getElementById('filterRole').value;

    const query = new URLSearchParams({
        date: date,
        name: name,
        role: role
    }).toString();

     window.location.href = `index.php?page=management_user&date=${encodeURIComponent(date)}&name=${encodeURIComponent(name)}&role=${encodeURIComponent(role)}`;
});

