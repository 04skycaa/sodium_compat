document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formEditUser");

  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("../../admin/management_user/update_user.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        // ✅ Cek status dari server
        if (data.status === "success") {
          Swal.fire({
            title: "Berhasil!",
            text: "Data pengguna berhasil diperbarui.",
            icon: "success",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            position: "center",
            background: "#fff",
            color: "#000",
            showClass: {
              popup: "animate__animated animate__fadeInDown",
            },
            hideClass: {
              popup: "animate__animated animate__fadeOutUp",
            },
          });

          // Delay agar animasi terlihat
          setTimeout(() => {
            window.location.href = "management_user.php";
          }, 1600);
        } else {
          Swal.fire({
            title: "Gagal!",
            text: data.message || "Data gagal diperbarui.",
            icon: "error",
            confirmButtonText: "Coba Lagi",
            showClass: {
              popup: "animate__animated animate__shakeX",
            },
          });
        }
      })
      .catch((error) => {
        Swal.fire({
          title: "Kesalahan!",
          text: "Terjadi kesalahan koneksi ke server.",
          icon: "error",
          confirmButtonText: "OK",
          showClass: {
            popup: "animate__animated animate__shakeX",
          },
        });
        console.error("Error:", error);
      });
  });
});
