<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $buku->nama_buku }} | E-Book Reader</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #0f2444;
            --navy-mid: #1a3c6b;
            --gold: #c9930a;
            --gold-light: #f0b429;
            --cream: #faf8f3;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #525659;
            color: var(--text);
            overflow: hidden;
        }

        /* HEADER */
        .reader-header {
            background: var(--navy);
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            position: relative;
            z-index: 100;
        }
        .book-title {
            font-family: 'Fraunces', serif;
            font-size: 1.1rem;
            font-weight: 700;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0 20px;
        }
        .btn-back {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .page-info {
            background: rgba(255,255,255,0.1);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            font-family: monospace;
        }

        /* CONTROLS */
        .reader-controls {
            background: #f8fafc;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
        }
        .control-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .btn-control {
            background: white;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-control:hover {
            background: var(--navy);
            color: white;
            border-color: var(--navy);
        }
        .btn-control:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .btn-control:disabled:hover {
            background: white;
            color: var(--text);
            border-color: var(--border);
        }
        .btn-verify {
            background: var(--gold);
            border-color: var(--gold);
            color: white;
            font-weight: 600;
        }
        .btn-verify:hover {
            background: var(--gold-light);
            border-color: var(--gold-light);
        }
        .access-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .access-preview {
            background: #fef3c7;
            color: #92400e;
        }
        .access-full {
            background: #d1fae5;
            color: #065f46;
        }

        /* PDF VIEWER */
        .pdf-container {
            height: calc(100vh - 110px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background: #525659;
        }
        .pdf-page {
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            max-width: 100%;
            background: white;
        }
        .pdf-loader {
            color: white;
            text-align: center;
            padding: 60px 20px;
        }
        .pdf-loader i {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
        }

        /* MODAL */
        .modal-content {
            border-radius: 16px;
            border: none;
        }
        .modal-body {
            padding: 32px;
            text-align: center;
        }
        .lock-icon {
            width: 72px;
            height: 72px;
            background: #fef3c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            color: var(--gold);
        }
        .modal-title {
            font-family: 'Fraunces', serif;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .modal-text {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 24px;
        }
        .form-control {
            border-radius: 10px;
            border: 1.5px solid var(--border);
            padding: 12px 16px;
            font-size: 14px;
            text-align: center;
            font-weight: 600;
        }
        .form-control:focus {
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(26,60,107,0.1);
        }
        .btn-verify-modal {
            background: var(--navy);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-verify-modal:hover {
            background: var(--navy-mid);
        }
        .error-text {
            color: #dc2626;
            font-size: 13px;
            margin-top: 8px;
            display: none;
        }

        @media (max-width: 768px) {
            .book-title {
                font-size: 0.95rem;
            }
            .reader-controls {
                flex-wrap: wrap;
                gap: 8px;
            }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="reader-header">
    <a href="/" class="btn-back">
        <i class="fas fa-arrow-left"></i>
        <span class="d-none d-md-inline">Kembali</span>
    </a>
    <div class="book-title">{{ $buku->nama_buku }}</div>
    <div class="page-info" id="pageInfo">Hal: 0/0</div>
</div>

<!-- CONTROLS -->
<div class="reader-controls">
    <div class="control-group">
        <button class="btn-control" id="btnPrev" onclick="prevPage()">
            <i class="fas fa-chevron-left"></i>
            <span class="d-none d-md-inline">Sebelumnya</span>
        </button>
        <button class="btn-control" id="btnNext" onclick="nextPage()">
            <span class="d-none d-md-inline">Selanjutnya</span>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    
    <div class="control-group">
        <span class="access-badge" id="accessBadge">
            <i class="fas fa-lock me-1"></i>Preview (25%)
        </span>
        <button class="btn-control btn-verify" id="btnVerify" onclick="showVerifyModal()">
            <i class="fas fa-unlock"></i>
            <span class="d-none d-md-inline">Buka Akses Full</span>
        </button>
    </div>
</div>

<!-- PDF VIEWER -->
<div class="pdf-container" id="pdfContainer">
    <div class="pdf-loader">
        <i class="fas fa-circle-notch fa-spin"></i>
        <p>Memuat dokumen...</p>
    </div>
</div>

<!-- MODAL VERIFIKASI NIM -->
<div class="modal fade" id="modalVerify" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="lock-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h5 class="modal-title">Akses Terbatas</h5>
                <p class="modal-text">
                    Silakan verifikasi NIM Anda untuk membaca buku ini secara lengkap.
                </p>
                <div class="mb-3">
                    <input type="text" id="nimInput" class="form-control" placeholder="Masukkan NIM Anda" autocomplete="off">
                    <div class="error-text" id="errorTextNim">NIM tidak terdaftar!</div>
                </div>
                <div class="mb-3">
                    <input type="text" id="tokenInput" class="form-control" placeholder="Masukkan Token Referral" autocomplete="off">
                    <div class="error-text" id="errorTextToken">Token tidak valid!</div>
                </div>
                <button class="btn-verify-modal" onclick="verifyNim()">
                    <i class="fas fa-check-circle me-2"></i>Verifikasi NIM & Token
                </button>
                <button class="btn btn-link btn-sm mt-2 text-muted text-decoration-none" data-bs-dismiss="modal">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Set PDF.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    // State
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.5;
    let isFullAccess = false;
    let maxPages = 0;
    let previewLimit = 0;

    const bookId = {{ $buku->id }};
    const streamUrl = `/ebook/stream/` + bookId;

    // Load PDF
    function loadPdf() {
        pdfjsLib.getDocument({
            url: streamUrl,
            withCredentials: true,
            disableRange: true
        }).promise.then(pdf => {
            pdfDoc = pdf;
            maxPages = pdf.numPages;
            previewLimit = Math.ceil(maxPages / 4);
            
            updateAccessStatus();
            renderPage(pageNum);
        }).catch(err => {
            console.error('Error loading PDF:', err);
            document.getElementById('pdfContainer').innerHTML = `
                <div class="pdf-loader">
                    <i class="fas fa-exclamation-triangle" style="color:#f59e0b"></i>
                    <p>Gagal memuat dokumen. Silakan refresh halaman.</p>
                </div>
            `;
        });
    }

    // Render page
    function renderPage(num) {
        if (!pdfDoc) return;
        
        // Check access restriction
        if (!isFullAccess && num > previewLimit) {
            showVerifyModal();
            return;
        }

        pageRendering = true;
        document.getElementById('btnPrev').disabled = true;
        document.getElementById('btnNext').disabled = true;

        pdfDoc.getPage(num).then(page => {
            const viewport = page.getViewport({ scale: scale });
            const canvas = document.createElement('canvas');
            canvas.className = 'pdf-page';
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            const container = document.getElementById('pdfContainer');
            container.innerHTML = '';
            container.appendChild(canvas);

            page.render(renderContext).promise.then(() => {
                pageRendering = false;
                updatePageInfo();
                updateButtons();
                
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    function prevPage() {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    }

    function nextPage() {
        if (pageNum >= maxPages) return;
        
        if (!isFullAccess && pageNum >= previewLimit) {
            showVerifyModal();
            return;
        }
        
        pageNum++;
        queueRenderPage(pageNum);
    }

    function updatePageInfo() {
        document.getElementById('pageInfo').textContent = `Hal: ${pageNum}/${maxPages}`;
    }

    function updateButtons() {
        document.getElementById('btnPrev').disabled = pageNum <= 1;
        document.getElementById('btnNext').disabled = pageNum >= maxPages || (!isFullAccess && pageNum >= previewLimit);
    }

    function updateAccessStatus() {
        const badge = document.getElementById('accessBadge');
        const btnVerify = document.getElementById('btnVerify');
        
        if (isFullAccess) {
            badge.className = 'access-badge access-full';
            badge.innerHTML = '<i class="fas fa-unlock me-1"></i>Akses Full';
            btnVerify.style.display = 'none';
        } else {
            badge.className = 'access-badge access-preview';
            badge.innerHTML = `<i class="fas fa-lock me-1"></i>Preview (${previewLimit} Hal)`;
            btnVerify.style.display = 'flex';
        }
    }

    function showVerifyModal() {
        const modal = new bootstrap.Modal(document.getElementById('modalVerify'));
        modal.show();
        setTimeout(() => {
            document.getElementById('nimInput').focus();
        }, 500);
    }

    function verifyNim() {
        const nim = document.getElementById('nimInput').value.trim();
        const token = document.getElementById('tokenInput').value.trim();
        const errorTextNim = document.getElementById('errorTextNim');
        const errorTextToken = document.getElementById('errorTextToken');
        
        let isValid = true;
        
        // Reset error messages
        errorTextNim.style.display = 'none';
        errorTextToken.style.display = 'none';
        
        if (!nim) {
            errorTextNim.style.display = 'block';
            errorTextNim.textContent = 'NIM tidak boleh kosong!';
            isValid = false;
        }
        
        if (!token) {
            errorTextToken.style.display = 'block';
            errorTextToken.textContent = 'Token tidak boleh kosong!';
            isValid = false;
        }
        
        if (!isValid) {
            return;
        }

        // Send both NIM and token to the backend
        fetch('{{ route("publik.cek-nim") }}?nim=' + encodeURIComponent(nim) + '&referral_token=' + encodeURIComponent(token))
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    isFullAccess = true;
                    bootstrap.Modal.getInstance(document.getElementById('modalVerify')).hide();
                    updateAccessStatus();
                    updateButtons();
                    showToast('Verifikasi berhasil! Akses full dibuka.', 'success');
                } else {
                    if (data.message && data.message.includes('Token')) {
                        errorTextToken.style.display = 'block';
                        errorTextToken.textContent = data.message;
                    } else {
                        errorTextNim.style.display = 'block';
                        errorTextNim.textContent = data.message || 'NIM tidak terdaftar!';
                    }
                }
            })
            .catch(err => {
                errorTextNim.style.display = 'block';
                errorTextNim.textContent = 'Terjadi kesalahan koneksi.';
            });
    }

    function showToast(msg, type) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position:fixed;top:80px;right:20px;z-index:9999;
            background:${type === 'success' ? '#d1fae5' : '#fef3c7'};
            color:${type === 'success' ? '#065f46' : '#92400e'};
            border:1px solid ${type === 'success' ? '#a7f3d0' : '#fde68a'};
            padding:12px 20px;border-radius:12px;font-size:14px;
            font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,0.2);
            animation:slideIn 0.3s ease;
        `;
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') prevPage();
        if (e.key === 'ArrowRight') nextPage();
    });

    // Enter key on NIM input
    document.getElementById('nimInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') verifyNim();
    });
    
    // Enter key on Token input
    document.getElementById('tokenInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') verifyNim();
    });

    // Initialize
    loadPdf();
</script>

<style>
@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}
</style>

</body>
</html>