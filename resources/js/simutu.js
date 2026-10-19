/**
 * =========================================================
 * SIMUTU-TMA
 * JavaScript Application
 * PT Sinergi Gula Nusantara
 * =========================================================
 */

document.addEventListener("DOMContentLoaded", function () {

    initializeImageModal();

    initializeAlertAutoClose();

    initializeConfirmAction();

    initializeFilePreview();

    initializeMobileSidebar();

    initializePasswordToggle();

    initializeAutoHideToast();

});


/**
 * =========================================================
 * IMAGE MODAL
 * =========================================================
 */

function initializeImageModal() {

    if (document.getElementById("simutuImageModal")) {
        return;
    }

    const modal = document.createElement("div");

    modal.id = "simutuImageModal";

    modal.className = "image-modal";

    modal.innerHTML = `
        <div class="image-modal-content">

            <button
                type="button"
                class="image-modal-close"
                onclick="closeImageModal()"
                aria-label="Tutup"
            >
                ×
            </button>

            <img
                id="simutuModalImage"
                class="image-modal-image"
                src=""
                alt=""
            >

        </div>
    `;

    document.body.appendChild(modal);


    modal.addEventListener("click", function (event) {

        if (event.target === modal) {
            closeImageModal();
        }

    });


    document.addEventListener("keydown", function (event) {

        if (event.key === "Escape") {
            closeImageModal();
        }

    });

}


/**
 * Open Image Modal
 */

window.openImageModal = function (src, alt = "Preview") {

    const modal =
        document.getElementById("simutuImageModal");

    const image =
        document.getElementById("simutuModalImage");

    if (!modal || !image) {
        return;
    }

    image.src = src;

    image.alt = alt;

    modal.classList.add("active");

    document.body.style.overflow = "hidden";

};


/**
 * Close Image Modal
 */

window.closeImageModal = function () {

    const modal =
        document.getElementById("simutuImageModal");

    const image =
        document.getElementById("simutuModalImage");

    if (!modal) {
        return;
    }

    modal.classList.remove("active");

    document.body.style.overflow = "";

    if (image) {
        image.src = "";
    }

};


/**
 * =========================================================
 * ALERT AUTO CLOSE
 * =========================================================
 */

function initializeAlertAutoClose() {

    const alerts =
        document.querySelectorAll(
            ".alert[data-auto-close]"
        );

    alerts.forEach(function (alert) {

        const duration =
            parseInt(
                alert.dataset.autoClose || "5000"
            );

        setTimeout(function () {

            alert.style.opacity = "0";

            alert.style.transform =
                "translateY(-5px)";

            alert.style.transition =
                "all .3s ease";

            setTimeout(function () {

                alert.remove();

            }, 300);

        }, duration);

    });

}


/**
 * =========================================================
 * CONFIRM ACTION
 * =========================================================
 */

function initializeConfirmAction() {

    document.addEventListener(
        "click",
        function (event) {

            const element =
                event.target.closest(
                    "[data-confirm]"
                );

            if (!element) {
                return;
            }

            const message =
                element.dataset.confirm;

            if (!confirm(message)) {

                event.preventDefault();

            }

        }
    );

}


/**
 * =========================================================
 * FILE PREVIEW
 * =========================================================
 */

function initializeFilePreview() {

    const inputs =
        document.querySelectorAll(
            'input[type="file"][data-preview]'
        );

    inputs.forEach(function (input) {

        input.addEventListener(
            "change",
            function () {

                const previewId =
                    input.dataset.preview;

                const preview =
                    document.getElementById(
                        previewId
                    );

                if (!preview) {
                    return;
                }

                preview.innerHTML = "";


                Array.from(input.files)
                    .forEach(function (file) {

                        if (
                            !file.type
                                .startsWith("image/")
                        ) {
                            return;
                        }

                        const reader =
                            new FileReader();


                        reader.onload =
                            function (event) {

                                const wrapper =
                                    document.createElement(
                                        "div"
                                    );

                                wrapper.style.position =
                                    "relative";

                                wrapper.style.display =
                                    "inline-block";

                                wrapper.style.margin =
                                    "5px";


                                const image =
                                    document.createElement(
                                        "img"
                                    );

                                image.src =
                                    event.target.result;

                                image.alt =
                                    file.name;

                                image.style.width =
                                    "120px";

                                image.style.height =
                                    "90px";

                                image.style.objectFit =
                                    "cover";

                                image.style.borderRadius =
                                    "8px";

                                image.style.border =
                                    "1px solid #e3e8e5";


                                wrapper.appendChild(
                                    image
                                );

                                preview.appendChild(
                                    wrapper
                                );

                            };


                        reader.readAsDataURL(file);

                    });

            }
        );

    });

}


/**
 * =========================================================
 * MOBILE SIDEBAR
 * =========================================================
 */

function initializeMobileSidebar() {

    const sidebar =
        document.querySelector(
            ".sidebar"
        );

    const toggle =
        document.querySelector(
            "[data-sidebar-toggle]"
        );

    const overlay =
        document.querySelector(
            ".sidebar-overlay"
        );


    if (!sidebar || !toggle) {
        return;
    }


    toggle.addEventListener(
        "click",
        function () {

            sidebar.classList.toggle(
                "sidebar-open"
            );

            if (overlay) {

                overlay.classList.toggle(
                    "active"
                );

            }

        }
    );


    if (overlay) {

        overlay.addEventListener(
            "click",
            function () {

                sidebar.classList.remove(
                    "sidebar-open"
                );

                overlay.classList.remove(
                    "active"
                );

            }
        );

    }

}


/**
 * =========================================================
 * PASSWORD TOGGLE
 * =========================================================
 */

function initializePasswordToggle() {

    const buttons =
        document.querySelectorAll(
            "[data-password-toggle]"
        );


    buttons.forEach(function (button) {

        button.addEventListener(
            "click",
            function () {

                const targetId =
                    button.dataset.passwordToggle;

                const input =
                    document.getElementById(
                        targetId
                    );

                if (!input) {
                    return;
                }


                if (input.type === "password") {

                    input.type = "text";

                    button.innerHTML = "🙈";

                } else {

                    input.type = "password";

                    button.innerHTML = "👁";

                }

            }
        );

    });

}


/**
 * =========================================================
 * TOAST
 * =========================================================
 */

window.showSimutuToast = function (
    message,
    type = "default",
    duration = 3500
) {

    const oldToast =
        document.querySelector(
            ".simutu-toast"
        );

    if (oldToast) {
        oldToast.remove();
    }


    const toast =
        document.createElement("div");

    toast.className =
        "simutu-toast";


    let icon = "✓";


    if (type === "success") {
        icon = "✓";
    }

    if (type === "error") {
        icon = "!";
    }

    if (type === "warning") {
        icon = "⚠";
    }

    if (type === "info") {
        icon = "i";
    }


    toast.innerHTML = `
        <strong>${icon}</strong>
        <span>${escapeHtml(message)}</span>
    `;


    document.body.appendChild(toast);


    setTimeout(function () {

        toast.style.opacity = "0";

        toast.style.transform =
            "translateY(10px)";

        toast.style.transition =
            "all .3s ease";


        setTimeout(function () {

            toast.remove();

        }, 300);

    }, duration);

};


/**
 * =========================================================
 * AUTO HIDE TOAST
 * =========================================================
 */

function initializeAutoHideToast() {

    const toast =
        document.querySelector(
            ".simutu-toast"
        );

    if (!toast) {
        return;
    }


    setTimeout(function () {

        toast.style.opacity = "0";

        toast.style.transform =
            "translateY(10px)";

        toast.style.transition =
            "all .3s ease";


        setTimeout(function () {

            toast.remove();

        }, 300);

    }, 3500);

}


/**
 * =========================================================
 * ESCAPE HTML
 * =========================================================
 */

function escapeHtml(value) {

    const div =
        document.createElement("div");

    div.textContent = value;

    return div.innerHTML;

}


/**
 * =========================================================
 * FORM SUBMIT LOADING
 * =========================================================
 */

document.addEventListener(
    "submit",
    function (event) {

        const form =
            event.target;

        if (
            form.hasAttribute(
                "data-loading"
            )
        ) {

            const button =
                form.querySelector(
                    'button[type="submit"]'
                );

            if (button) {

                button.disabled = true;

                button.dataset.originalText =
                    button.innerHTML;

                button.innerHTML = `
                    <span>
                        Memproses...
                    </span>
                `;

            }

        }

    }
);


/**
 * =========================================================
 * DELETE CONFIRMATION
 * =========================================================
 */

window.confirmDelete = function (
    message =
        "Apakah Anda yakin ingin menghapus data ini?"
) {

    return confirm(message);

};


/**
 * =========================================================
 * FORMAT NUMBER
 * =========================================================
 */

window.formatNumber = function (number) {

    return new Intl.NumberFormat(
        "id-ID"
    ).format(number);

};


/**
 * =========================================================
 * FORMAT DATE
 * =========================================================
 */

window.formatDateIndonesia = function (
    date
) {

    const parsedDate =
        new Date(date);

    return parsedDate.toLocaleDateString(
        "id-ID",
        {
            day: "2-digit",
            month: "long",
            year: "numeric"
        }
    );

};


/**
 * =========================================================
 * COPY TO CLIPBOARD
 * =========================================================
 */

window.copyToClipboard = function (
    text
) {

    navigator.clipboard
        .writeText(text)
        .then(function () {

            showSimutuToast(
                "Berhasil disalin.",
                "success"
            );

        })
        .catch(function () {

            showSimutuToast(
                "Gagal menyalin.",
                "error"
            );

        });

};