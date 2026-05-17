/**
 * Modal Utilities for SiPusaka
 * Mengganti window.alert() dan window.confirm() dengan modal Bootstrap
 */

class ModalUtils {
    constructor() {
        this.initModalElements();
    }

    /**
     * Inisialisasi elemen modal jika belum ada
     */
    initModalElements() {
        // Modal untuk alert
        if (!document.getElementById("modal-alert")) {
            const alertModal = document.createElement("div");
            alertModal.id = "modal-alert";
            alertModal.className = "modal fade";
            alertModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Informasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p id="modal-alert-message"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(alertModal);
        }

        // Modal untuk confirm
        if (!document.getElementById("modal-confirm")) {
            const confirmModal = document.createElement("div");
            confirmModal.id = "modal-confirm";
            confirmModal.className = "modal fade";
            confirmModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p id="modal-confirm-message"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="modal-confirm-ok">OK</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(confirmModal);
        }

        // Modal untuk success
        if (!document.getElementById("modal-success")) {
            const successModal = document.createElement("div");
            successModal.id = "modal-success";
            successModal.className = "modal fade";
            successModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Berhasil</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p id="modal-success-message"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(successModal);
        }

        // Modal untuk error
        if (!document.getElementById("modal-error")) {
            const errorModal = document.createElement("div");
            errorModal.id = "modal-error";
            errorModal.className = "modal fade";
            errorModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title"><i class="fas fa-exclamation-circle me-2"></i>Error</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p id="modal-error-message"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(errorModal);
        }

        // Modal untuk warning
        if (!document.getElementById("modal-warning")) {
            const warningModal = document.createElement("div");
            warningModal.id = "modal-warning";
            warningModal.className = "modal fade";
            warningModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Peringatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p id="modal-warning-message"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(warningModal);
        }
    }

    /**
     * Tampilkan modal alert
     * @param {string} message - Pesan yang akan ditampilkan
     * @param {string} title - Judul modal (opsional)
     * @returns {Promise} Promise yang resolve ketika modal ditutup
     */
    alert(message, title = "Informasi") {
        return new Promise((resolve) => {
            const modalElement = document.getElementById("modal-alert");
            const modalTitle = modalElement.querySelector(".modal-title");
            const modalMessage = document.getElementById("modal-alert-message");

            modalTitle.textContent = title;
            modalMessage.textContent = message;

            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            modalElement.addEventListener(
                "hidden.bs.modal",
                () => {
                    resolve();
                },
                { once: true },
            );
        });
    }

    /**
     * Tampilkan modal confirm
     * @param {string} message - Pesan konfirmasi
     * @param {string} title - Judul modal (opsional)
     * @returns {Promise<boolean>} Promise yang resolve dengan true jika OK, false jika Batal
     */
    confirm(message, title = "Konfirmasi") {
        return new Promise((resolve) => {
            const modalElement = document.getElementById("modal-confirm");
            const modalTitle = modalElement.querySelector(".modal-title");
            const modalMessage = document.getElementById(
                "modal-confirm-message",
            );
            const okButton = document.getElementById("modal-confirm-ok");

            modalTitle.textContent = title;
            modalMessage.textContent = message;

            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            const handleOk = () => {
                cleanup();
                modal.hide();
                resolve(true);
            };

            const handleCancel = () => {
                cleanup();
                modal.hide();
                resolve(false);
            };

            const cleanup = () => {
                okButton.removeEventListener("click", handleOk);
                modalElement.removeEventListener(
                    "hidden.bs.modal",
                    handleCancel,
                );
            };

            okButton.addEventListener("click", handleOk, { once: true });
            modalElement.addEventListener("hidden.bs.modal", handleCancel, {
                once: true,
            });
        });
    }

    /**
     * Tampilkan modal success
     * @param {string} message - Pesan sukses
     * @returns {Promise} Promise yang resolve ketika modal ditutup
     */
    success(message) {
        return new Promise((resolve) => {
            const modalElement = document.getElementById("modal-success");
            const modalMessage = document.getElementById(
                "modal-success-message",
            );

            modalMessage.textContent = message;

            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            modalElement.addEventListener(
                "hidden.bs.modal",
                () => {
                    resolve();
                },
                { once: true },
            );
        });
    }

    /**
     * Tampilkan modal error
     * @param {string} message - Pesan error
     * @returns {Promise} Promise yang resolve ketika modal ditutup
     */
    error(message) {
        return new Promise((resolve) => {
            const modalElement = document.getElementById("modal-error");
            const modalMessage = document.getElementById("modal-error-message");

            modalMessage.textContent = message;

            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            modalElement.addEventListener(
                "hidden.bs.modal",
                () => {
                    resolve();
                },
                { once: true },
            );
        });
    }

    /**
     * Tampilkan modal warning
     * @param {string} message - Pesan peringatan
     * @returns {Promise} Promise yang resolve ketika modal ditutup
     */
    warning(message) {
        return new Promise((resolve) => {
            const modalElement = document.getElementById("modal-warning");
            const modalMessage = document.getElementById(
                "modal-warning-message",
            );

            modalMessage.textContent = message;

            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            modalElement.addEventListener(
                "hidden.bs.modal",
                () => {
                    resolve();
                },
                { once: true },
            );
        });
    }

    /**
     * Tampilkan modal dengan konfigurasi kustom
     * @param {Object} options - Opsi konfigurasi
     * @param {string} options.title - Judul modal
     * @param {string} options.message - Pesan modal
     * @param {string} options.type - Tipe modal (info, success, error, warning)
     * @param {Array} options.buttons - Array tombol kustom
     * @returns {Promise<string>} Promise yang resolve dengan id tombol yang diklik
     */
    custom(options) {
        return new Promise((resolve) => {
            // Buat modal dinamis
            const modalId = "modal-custom-" + Date.now();
            const modalElement = document.createElement("div");
            modalElement.id = modalId;
            modalElement.className = "modal fade";

            let headerClass = "";
            let icon = "";

            switch (options.type) {
                case "success":
                    headerClass = "bg-success text-white";
                    icon = '<i class="fas fa-check-circle me-2"></i>';
                    break;
                case "error":
                    headerClass = "bg-danger text-white";
                    icon = '<i class="fas fa-exclamation-circle me-2"></i>';
                    break;
                case "warning":
                    headerClass = "bg-warning text-dark";
                    icon = '<i class="fas fa-exclamation-triangle me-2"></i>';
                    break;
                default:
                    headerClass = "bg-primary text-white";
                    icon = '<i class="fas fa-info-circle me-2"></i>';
            }

            let buttonsHtml = "";
            if (options.buttons && options.buttons.length > 0) {
                buttonsHtml = options.buttons
                    .map(
                        (btn) =>
                            `<button type="button" class="btn ${btn.class || "btn-secondary"}" data-button-id="${btn.id}">${btn.text}</button>`,
                    )
                    .join("");
            } else {
                buttonsHtml =
                    '<button type="button" class="btn btn-primary" data-button-id="ok">OK</button>';
            }

            modalElement.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header ${headerClass}">
                            <h5 class="modal-title">${icon}${options.title || "Informasi"}</h5>
                            <button type="button" class="btn-close ${options.type === "success" || options.type === "error" ? "btn-close-white" : ""}" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>${options.message}</p>
                        </div>
                        <div class="modal-footer">
                            ${buttonsHtml}
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modalElement);

            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            // Handle tombol klik
            const handleButtonClick = (e) => {
                const buttonId = e.target.getAttribute("data-button-id");
                cleanup();
                modal.hide();
                setTimeout(() => {
                    modalElement.remove();
                }, 300);
                resolve(buttonId);
            };

            const cleanup = () => {
                modalElement
                    .querySelectorAll("[data-button-id]")
                    .forEach((btn) => {
                        btn.removeEventListener("click", handleButtonClick);
                    });
                modalElement.removeEventListener(
                    "hidden.bs.modal",
                    handleHidden,
                );
            };

            const handleHidden = () => {
                cleanup();
                setTimeout(() => {
                    modalElement.remove();
                }, 300);
                resolve("dismiss");
            };

            modalElement.querySelectorAll("[data-button-id]").forEach((btn) => {
                btn.addEventListener("click", handleButtonClick);
            });

            modalElement.addEventListener("hidden.bs.modal", handleHidden, {
                once: true,
            });
        });
    }
}

// Inisialisasi global
window.ModalUtils = new ModalUtils();

// Override window.alert dan window.confirm
window.originalAlert = window.alert;
window.originalConfirm = window.confirm;

window.alert = function (message, title) {
    return window.ModalUtils.alert(message, title);
};

window.confirm = function (message, title) {
    return window.ModalUtils.confirm(message, title);
};

// Fungsi helper untuk penggunaan mudah
window.showAlert = window.ModalUtils.alert.bind(window.ModalUtils);
window.showConfirm = window.ModalUtils.confirm.bind(window.ModalUtils);
window.showSuccess = window.ModalUtils.success.bind(window.ModalUtils);
window.showError = window.ModalUtils.error.bind(window.ModalUtils);
window.showWarning = window.ModalUtils.warning.bind(window.ModalUtils);
window.showCustomModal = window.ModalUtils.custom.bind(window.ModalUtils);

// Export untuk penggunaan module
if (typeof module !== "undefined" && module.exports) {
    module.exports = ModalUtils;
}
