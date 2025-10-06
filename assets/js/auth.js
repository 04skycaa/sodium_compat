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

document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("status-modal");
    const modalTitle = document.getElementById("modal-title");
    const modalMessage = document.getElementById("modal-message");
    const closeBtn = document.querySelector(".close-btn");

    if (registerStatus) {
        modal.style.display = "block";
        modalMessage.textContent = registerMessage;

        if (registerStatus === "success") {
            modalTitle.textContent = "Berhasil!";
            modal.querySelector(".modal-content").classList.add("success");

            // Redirect otomatis ke login setelah 2 detik
            setTimeout(() => {
                window.location.href = "../auth/login.php";
            }, 2000);

        } else if (registerStatus === "error") {
            modalTitle.textContent = "Gagal!";
            modal.querySelector(".modal-content").classList.add("error");
        }
    }

    closeBtn.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
});
