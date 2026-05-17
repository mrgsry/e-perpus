<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Form Peminjaman Buku – SiPusaka</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
  :root {
    --navy: #0f2444;
    --navy-mid: #1a3c6b;
    --navy-light: #234d82;
    --gold: #c9930a;
    --gold-light: #f0b429;
    --cream: #faf8f3;
    --white: #ffffff;
    --gray: #64748b;
    --gray-light: #f1f5f9;
    --green: #059669;
    --red: #dc2626;
    --border: #e2e8f0;
    --shadow: 0 4px 32px rgba(15,36,68,0.10);
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    color: var(--navy);
    min-height: 100vh;
  }

  /* ── HEADER ── */
  .header {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 60%, #0d3460 100%);
    padding: 36px 40px 32px;
    position: relative;
    overflow: hidden;
  }
  .header::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: radial-gradient(circle, rgba(201,147,10,0.18) 0%, transparent 70%);
    border-radius: 50%;
  }
  .header::after {
    content: '';
    position: absolute;
    bottom: -50px; left: -50px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(201,147,10,0.10) 0%, transparent 70%);
    border-radius: 50%;
  }
  .header-inner {
    max-width: 680px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
  }
  .header-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(201,147,10,0.2);
    color: var(--gold-light);
    border: 1px solid rgba(201,147,10,0.35);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 14px;
  }
  .header h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(22px, 3.5vw, 36px);
    color: var(--white);
    margin-bottom: 6px;
    line-height: 1.2;
  }
  .header h1 span { color: var(--gold-light); }
  .header-sub {
    color: rgba(255,255,255,0.55);
    font-size: 13px;
    letter-spacing: 0.5px;
  }

  /* ── PROGRESS BAR ── */
  .progress-wrap {
    max-width: 680px;
    margin: 0 auto;
    padding: 28px 20px 0;
  }
  .progress-steps {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 8px;
  }
  .step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
  }
  .step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 17px;
    left: 50%;
    right: -50%;
    height: 2px;
    background: var(--border);
    z-index: 0;
    transition: background 0.4s;
  }
  .step.done:not(:last-child)::after,
  .step.active:not(:last-child)::after {
    background: linear-gradient(to right, var(--gold), var(--border));
  }
  .step.done:not(:last-child)::after { background: var(--gold); }
  .step-circle {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 600;
    color: var(--gray);
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
  }
  .step.active .step-circle {
    background: var(--navy);
    border-color: var(--navy);
    color: var(--gold-light);
    box-shadow: 0 0 0 4px rgba(201,147,10,0.15);
  }
  .step.done .step-circle {
    background: var(--green);
    border-color: var(--green);
    color: white;
  }
  .step-label {
    margin-top: 6px;
    font-size: 11px;
    color: var(--gray);
    font-weight: 500;
    text-align: center;
    white-space: nowrap;
  }
  .step.active .step-label { color: var(--navy); font-weight: 600; }
  .step.done .step-label { color: var(--green); }

  /* ── FORM CARD ── */
  .form-wrap {
    max-width: 680px;
    margin: 24px auto 60px;
    padding: 0 20px;
  }
  .form-card {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
  }

  /* ── SECTION PANELS ── */
  .form-section {
    display: none;
    padding: 36px 36px 28px;
    animation: fadeSlide 0.35s ease;
  }
  .form-section.active { display: block; }
  @keyframes fadeSlide {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .section-label {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
  }
  .section-num {
    background: var(--navy);
    color: var(--gold-light);
    width: 28px; height: 28px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    font-weight: 700;
    font-family: 'JetBrains Mono', monospace;
    flex-shrink: 0;
  }
  .section-title-text {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: var(--navy);
  }

  /* ── FIELD GROUPS ── */
  .field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
  }
  .field-row.full { grid-template-columns: 1fr; }
  @media (max-width: 520px) { .field-row { grid-template-columns: 1fr; } }

  .field-group { display: flex; flex-direction: column; gap: 6px; }
  .field-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--gray);
  }
  .required-dot {
    display: inline-block;
    width: 5px; height: 5px;
    background: var(--gold);
    border-radius: 50%;
    margin-left: 4px;
    vertical-align: middle;
    margin-bottom: 2px;
  }

  input[type="text"],
  input[type="email"],
  input[type="date"],
  input[type="number"],
  select,
  textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--navy);
    background: var(--gray-light);
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    outline: none;
    appearance: none;
  }
  input:focus, select:focus, textarea:focus {
    border-color: var(--navy-light);
    background: white;
    box-shadow: 0 0 0 3px rgba(35,77,130,0.10);
  }
  input.valid { border-color: var(--green); background: #f0fdf4; }
  input.error { border-color: var(--red); background: #fef2f2; }
  textarea { resize: vertical; min-height: 80px; }
  select { cursor: pointer; }

  .field-hint {
    font-size: 11.5px;
    color: var(--gray);
    line-height: 1.4;
  }
  .field-error {
    font-size: 11.5px;
    color: var(--red);
    display: none;
  }
  .field-error.show { display: block; }

  /* ── BOOK SEARCH ── */
  .book-search-wrap { position: relative; }
  .book-search-wrap input { padding-left: 42px; }
  .search-icon {
    position: absolute;
    left: 14px; top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
    font-size: 16px;
    pointer-events: none;
  }
  .book-dropdown {
    position: absolute;
    top: calc(100% + 6px); left: 0; right: 0;
    background: white;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(15,36,68,0.12);
    z-index: 100;
    overflow: hidden;
    display: none;
  }
  .book-dropdown.open { display: block; }
  .book-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: background 0.15s;
    border-bottom: 1px solid var(--border);
  }
  .book-option:last-child { border-bottom: none; }
  .book-option:hover { background: var(--gray-light); }
  .book-cover {
    width: 36px; height: 48px;
    border-radius: 4px;
    object-fit: cover;
    flex-shrink: 0;
    background: var(--navy);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    color: var(--gold-light);
    font-weight: 700;
    font-family: 'Playfair Display', serif;
  }
  .book-info { flex: 1; min-width: 0; }
  .book-title-opt { font-size: 13px; font-weight: 600; color: var(--navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .book-meta-opt { font-size: 11.5px; color: var(--gray); margin-top: 2px; }
  .book-stock {
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: 600;
    flex-shrink: 0;
  }
  .stock-ok { background: #d1fae5; color: #065f46; }
  .stock-low { background: #fef3c7; color: #92400e; }
  .stock-empty { background: #fee2e2; color: #991b1b; }

  /* ── SELECTED BOOK CARD ── */
  .selected-book {
    display: none;
    background: linear-gradient(135deg, #f0f7ff, #e8f4fd);
    border: 1.5px solid #bfd9f2;
    border-radius: 12px;
    padding: 16px;
    margin-top: 10px;
    gap: 14px;
    align-items: flex-start;
  }
  .selected-book.show { display: flex; }
  .selected-book-cover {
    width: 48px; height: 64px;
    border-radius: 6px;
    background: var(--navy);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    color: var(--gold-light);
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    flex-shrink: 0;
  }
  .selected-book-info { flex: 1; }
  .selected-book-title { font-size: 14px; font-weight: 600; color: var(--navy); margin-bottom: 4px; }
  .selected-book-detail { font-size: 12px; color: var(--gray); margin-bottom: 2px; }
  .book-id-tag {
    display: inline-block;
    background: rgba(15,36,68,0.08);
    color: var(--navy-mid);
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 6px;
    margin-top: 4px;
  }
  .remove-book {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--gray);
    font-size: 18px;
    padding: 2px;
    line-height: 1;
    transition: color 0.2s;
  }
  .remove-book:hover { color: var(--red); }

  /* ── DURATION SELECTOR ── */
  .duration-chips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 4px;
  }
  .dur-chip {
    padding: 7px 18px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    background: var(--gray-light);
    font-size: 13px;
    font-weight: 500;
    color: var(--gray);
    cursor: pointer;
    transition: all 0.2s;
  }
  .dur-chip:hover { border-color: var(--navy-light); color: var(--navy); }
  .dur-chip.selected {
    background: var(--navy);
    border-color: var(--navy);
    color: var(--gold-light);
  }

  /* ── DATE PREVIEW ── */
  .date-preview {
    display: none;
    margin-top: 10px;
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    color: #92400e;
  }
  .date-preview.show { display: flex; align-items: center; gap: 8px; }

  /* ── TERMS BOX ── */
  .terms-box {
    background: var(--gray-light);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    font-size: 13px;
    line-height: 1.7;
    color: var(--gray);
    max-height: 150px;
    overflow-y: auto;
    border: 1px solid var(--border);
  }
  .terms-box h4 { color: var(--navy); font-size: 13px; margin-bottom: 8px; }
  .terms-item { display: flex; gap: 8px; margin-bottom: 4px; }
  .terms-num {
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    color: var(--gold);
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .checkbox-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
  }
  .custom-checkbox {
    width: 18px; height: 18px;
    border: 2px solid var(--border);
    border-radius: 5px;
    background: white;
    flex-shrink: 0;
    margin-top: 1px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
    position: relative;
  }
  .custom-checkbox.checked {
    background: var(--navy);
    border-color: var(--navy);
  }
  .custom-checkbox.checked::after {
    content: '✓';
    color: var(--gold-light);
    font-size: 11px;
    font-weight: 700;
  }
  .checkbox-label { font-size: 13px; color: var(--navy); line-height: 1.5; cursor: pointer; }
  .checkbox-label span { color: var(--navy-light); font-weight: 600; text-decoration: underline; cursor: pointer; }

  /* ── REVIEW PANEL ── */
  .review-group {
    background: var(--gray-light);
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 14px;
    border: 1px solid var(--border);
  }
  .review-group-title {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--gray);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .review-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    padding: 7px 0;
    border-bottom: 1px solid var(--border);
    font-size: 13.5px;
  }
  .review-row:last-child { border-bottom: none; padding-bottom: 0; }
  .review-key { color: var(--gray); flex-shrink: 0; }
  .review-val { color: var(--navy); font-weight: 500; text-align: right; }
  .review-book-card {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 10px 0;
  }
  .denda-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #fef3c7;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 12px 14px;
    margin-top: 14px;
    font-size: 12.5px;
    color: #92400e;
    line-height: 1.5;
  }

  /* ── SUCCESS STATE ── */
  .success-section {
    display: none;
    padding: 48px 36px 40px;
    text-align: center;
    animation: fadeSlide 0.4s ease;
  }
  .success-section.active { display: block; }
  .success-icon {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px;
    margin: 0 auto 20px;
    box-shadow: 0 0 0 12px rgba(5,150,105,0.08);
  }
  .success-section h2 {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: var(--navy);
    margin-bottom: 8px;
  }
  .success-section p { font-size: 14px; color: var(--gray); margin-bottom: 24px; }
  .booking-id-display {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--navy);
    color: var(--gold-light);
    font-family: 'JetBrains Mono', monospace;
    font-size: 18px;
    font-weight: 600;
    padding: 14px 28px;
    border-radius: 12px;
    margin-bottom: 28px;
    letter-spacing: 2px;
  }
  .qr-placeholder {
    width: auto;
    background: var(--gray-light);
    border: 2px dashed var(--border);
    border-radius: 12px;
    margin: 0 auto 20px;
    display: inline-flex; align-items: center; justify-content: center;
    flex-direction: column;
    gap: 6px;
    color: var(--gray);
    font-size: 12px;
    padding: 16px;
  }
  #qr-code-svg img, #qr-code-svg canvas {
    border-radius: 6px;
    display: block;
  }

  /* ── BUTTONS ── */
  .btn-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 36px 28px;
    border-top: 1px solid var(--border);
    gap: 12px;
  }
  .btn {
    padding: 11px 28px;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
    display: flex; align-items: center; gap: 8px;
  }
  .btn-back {
    background: white;
    border: 1.5px solid var(--border);
    color: var(--gray);
  }
  .btn-back:hover { border-color: var(--navy); color: var(--navy); }
  .btn-next {
    background: var(--navy);
    color: var(--gold-light);
    margin-left: auto;
  }
  .btn-next:hover { background: var(--navy-mid); box-shadow: 0 4px 16px rgba(15,36,68,0.25); transform: translateY(-1px); }
  .btn-next:disabled { background: var(--border); color: var(--gray); transform: none; box-shadow: none; cursor: not-allowed; }
  .btn-submit {
    background: linear-gradient(135deg, var(--green), #047857);
    color: white;
    margin-left: auto;
  }
  .btn-submit:hover { box-shadow: 0 4px 16px rgba(5,150,105,0.35); transform: translateY(-1px); }

  /* ── MISC ── */
  .divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 24px 0;
  }
  .nim-status {
    display: none;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 500;
    margin-top: 6px;
  }
  .nim-status.found { display: block; background: #d1fae5; color: #065f46; }
  .nim-status.not-found { display: block; background: #fee2e2; color: #991b1b; }

  @media (max-width: 480px) {
    .form-section { padding: 24px 20px 20px; }
    .btn-row { padding: 16px 20px 20px; }
    .header { padding: 28px 20px 24px; }
  }
</style>
</head>
<body>

<!-- ── HEADER ── -->
<div class="header">
  <div class="header-inner">
    <div class="header-badge">📚 SiPusaka</div>
    <h1>Form <span>Peminjaman Buku</span></h1>
    <p class="header-sub">Perpustakaan Digital · Isi data dengan lengkap dan benar</p>
  </div>
</div>

<!-- ── PROGRESS ── -->
<div class="progress-wrap">
  <div class="progress-steps">
    <div class="step active" id="step-1">
      <div class="step-circle">01</div>
      <div class="step-label">Data Diri</div>
    </div>
    <div class="step" id="step-2">
      <div class="step-circle">02</div>
      <div class="step-label">Pilih Buku</div>
    </div>
    <div class="step" id="step-3">
      <div class="step-circle">03</div>
      <div class="step-label">Durasi</div>
    </div>
    <div class="step" id="step-4">
      <div class="step-circle">04</div>
      <div class="step-label">Konfirmasi</div>
    </div>
  </div>
</div>

<!-- ── FORM CARD ── -->
<div class="form-wrap">
<div class="form-card">

  <!-- ════════════════════════════════════
       STEP 1 · DATA DIRI MAHASISWA
  ════════════════════════════════════ -->
  <div class="form-section active" id="section-1">
    <div class="section-label">
      <div class="section-num">01</div>
      <span class="section-title-text">Data Diri Mahasiswa</span>
    </div>

    <div class="field-row">
      <div class="field-group">
        <label class="field-label">NIM <span class="required-dot"></span></label>
        <input type="text" id="nim" placeholder="contoh: 12345678" maxlength="12"
               oninput="checkNIM(this)">
        <div class="nim-status" id="nim-status"></div>
        <div class="field-error" id="nim-error">NIM tidak valid atau tidak ditemukan.</div>
      </div>
      <div class="field-group">
        <label class="field-label">Nama Lengkap <span class="required-dot"></span></label>
        <input type="text" id="nama" placeholder="Nama sesuai KTM">
        <div class="field-error" id="nama-error">Nama tidak boleh kosong.</div>
      </div>
    </div>

    <div class="field-row">
      <div class="field-group">
        <label class="field-label">Program Studi <span class="required-dot"></span></label>
        <select id="prodi">
          <option value="">— Pilih Prodi —</option>
          <option>Teknik Informatika</option>
          <option>Sistem Informasi</option>
          <option>Ilmu Komputer</option>
          <option>Teknik Elektro</option>
          <option>Manajemen Informatika</option>
          <option>Akuntansi</option>
          <option>Manajemen</option>
          <option>Hukum</option>
          <option>Psikologi</option>
          <option>Kedokteran</option>
          <option>Farmasi</option>
          <option>Lainnya</option>
        </select>
        <div class="field-error" id="prodi-error">Pilih program studi Anda.</div>
      </div>
      <div class="field-group">
        <label class="field-label">Semester</label>
        <select id="semester">
          <option value="">— Pilih —</option>
          <option>Semester 1</option>
          <option>Semester 2</option>
          <option>Semester 3</option>
          <option>Semester 4</option>
          <option>Semester 5</option>
          <option>Semester 6</option>
          <option>Semester 7</option>
          <option>Semester 8+</option>
        </select>
      </div>
    </div>

    <div class="field-row full">
      <div class="field-group">
        <label class="field-label">Email Aktif <span class="required-dot"></span></label>
        <input type="email" id="email" placeholder="email@kampus.ac.id">
        <div class="field-hint">Konfirmasi akan dikirim ke email ini.</div>
        <div class="field-error" id="email-error">Format email tidak valid.</div>
      </div>
    </div>

    <div class="field-row">
        <div class="field-group">
            <label class="field-label">Nomor Telepon / WhatsApp</label>
            <input type="text" id="telp" placeholder="08xxxxxxxxxx" maxlength="14">
            <div class="field-hint">Opsional · Untuk notifikasi jatuh tempo.</div>
        </div>

        <div class="field-group">
            <label class="field-label">Kode Referral <span class="required-dot"></span></label>
            <input type="text" id="referral_token" placeholder="Masukkan kode referral (6 digit)" maxlength="6" required oninput="validateReferralToken(this)">
            <div class="field-hint">Wajib diisi untuk mengakses ebook setelah persetujuan.</div>
            <div class="field-error" id="referral-error">Token referral tidak valid.</div>
        </div>
    </div>

    <!-- Alert for referral token validation -->
    <div class="alert alert-danger d-flex align-items-center" role="alert" id="referral-alert" style="display: none !important; margin-top: 16px; padding: 12px 14px; border-radius: 10px; background: #fee2e2; border: 1.5px solid #fca5a5; color: #991b1b; font-size: 13px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:" style="margin-right: 8px; flex-shrink: 0;">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </svg>
      <div id="referral-alert-text">Token referral tidak valid atau tidak ditemukan.</div>
    </div>
  </div>
  </div>

  <!-- ════════════════════════════════════
       STEP 2 · PILIH BUKU
  ════════════════════════════════════ -->
  <div class="form-section" id="section-2">
    <div class="section-label">
      <div class="section-num">02</div>
      <span class="section-title-text">Pilih Buku yang Akan Dipinjam</span>
    </div>

    <div class="field-group" style="margin-bottom:16px">
      <label class="field-label">Cari Judul / Pengarang / ISBN <span class="required-dot"></span></label>
      <div class="book-search-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" id="book-search" placeholder="Ketik judul buku..." autocomplete="off"
               oninput="searchBooks(this.value)">
        <div class="book-dropdown" id="book-dropdown"></div>
      </div>
      <div class="field-error" id="book-error">Pilih buku dari daftar pencarian.</div>
    </div>

    <div class="selected-book" id="selected-book">
      <div class="selected-book-cover" id="sel-cover">📖</div>
      <div class="selected-book-info">
        <div class="selected-book-title" id="sel-title">—</div>
        <div class="selected-book-detail" id="sel-author">—</div>
        <div class="selected-book-detail" id="sel-category">—</div>
        <div class="book-id-tag" id="sel-id">—</div>
      </div>
      <button class="remove-book" onclick="clearBook()" title="Hapus pilihan">✕</button>
    </div>

    <hr class="divider">

    <div class="field-row full">
      <div class="field-group">
        <label class="field-label">Tujuan / Keperluan Peminjaman</label>
        <select id="keperluan">
          <option value="">— Pilih keperluan —</option>
          <option>Referensi Tugas / Skripsi</option>
          <option>Bahan Perkuliahan</option>
          <option>Penelitian / Karya Ilmiah</option>
          <option>Bacaan Umum / Pengayaan</option>
          <option>Praktikum / Proyek</option>
        </select>
      </div>
    </div>

    <div class="field-row full">
      <div class="field-group">
        <label class="field-label">Catatan Tambahan</label>
        <textarea id="catatan" placeholder="Kondisi buku yang diinginkan, edisi tertentu, dll. (opsional)"></textarea>
      </div>
    </div>
  </div>

  <!-- ════════════════════════════════════
       STEP 3 · DURASI & TANGGAL
  ════════════════════════════════════ -->
  <div class="form-section" id="section-3">
    <div class="section-label">
      <div class="section-num">03</div>
      <span class="section-title-text">Durasi Peminjaman</span>
    </div>

    <div class="field-group" style="margin-bottom:20px">
      <label class="field-label">Pilih Durasi <span class="required-dot"></span></label>
      <div class="duration-chips">
        <div class="dur-chip" onclick="setDuration(3, this)">3 Hari</div>
        <div class="dur-chip" onclick="setDuration(7, this)">7 Hari</div>
        <div class="dur-chip selected" onclick="setDuration(14, this)">14 Hari</div>
        <div class="dur-chip" onclick="setDuration(21, this)">21 Hari</div>
      </div>
      <div class="field-error" id="dur-error">Pilih durasi peminjaman.</div>
    </div>

    <div class="field-row">
      <div class="field-group">
        <label class="field-label">Tanggal Pengambilan <span class="required-dot"></span></label>
        <input type="date" id="tgl-ambil" onchange="updateReturnDate()">
        <div class="field-hint">Minimal besok · Sesuai jam operasional.</div>
        <div class="field-error" id="tgl-error">Pilih tanggal pengambilan yang valid.</div>
      </div>
      <div class="field-group">
        <label class="field-label">Tanggal Kembali (Otomatis)</label>
        <input type="date" id="tgl-kembali" readonly style="background:#f8f8f8;color:var(--gray)">
        <div class="field-hint">Dihitung otomatis dari durasi.</div>
      </div>
    </div>

    <div class="date-preview" id="date-preview">
      <span>📅</span>
      <span id="date-preview-text"></span>
    </div>

    <hr class="divider">

    <div class="field-row full">
      <div class="field-group">
        <label class="field-label">Metode Pengambilan</label>
        <select id="metode">
          <option value="langsung">Ambil Langsung di Perpustakaan</option>
          <option value="antar">Antar ke Ruang / Gedung (jika tersedia)</option>
        </select>
        <div class="field-hint">Pengambilan langsung di loket perpustakaan lantai 1.</div>
      </div>
    </div>
  </div>

  <!-- ════════════════════════════════════
       STEP 4 · KONFIRMASI & SYARAT
  ════════════════════════════════════ -->
  <div class="form-section" id="section-4">
    <div class="section-label">
      <div class="section-num">04</div>
      <span class="section-title-text">Ringkasan &amp; Konfirmasi</span>
    </div>

    <!-- Review: Data Mahasiswa -->
    <div class="review-group">
      <div class="review-group-title">🎓 Data Mahasiswa</div>
      <div class="review-row">
        <span class="review-key">NIM</span>
        <span class="review-val" id="rv-nim">—</span>
      </div>
      <div class="review-row">
        <span class="review-key">Nama</span>
        <span class="review-val" id="rv-nama">—</span>
      </div>
      <div class="review-row">
        <span class="review-key">Prodi</span>
        <span class="review-val" id="rv-prodi">—</span>
      </div>
      <div class="review-row">
        <span class="review-key">Email</span>
        <span class="review-val" id="rv-email">—</span>
      </div>
    </div>

    <!-- Review: Buku -->
    <div class="review-group">
      <div class="review-group-title">📚 Buku yang Dipinjam</div>
      <div class="review-book-card">
        <div class="selected-book-cover" id="rv-cover" style="width:40px;height:52px;font-size:18px">📖</div>
        <div>
          <div class="selected-book-title" id="rv-book-title" style="font-size:14px">—</div>
          <div class="selected-book-detail" id="rv-book-author" style="font-size:12px">—</div>
          <div class="book-id-tag" id="rv-book-id">—</div>
        </div>
      </div>
    </div>

    <!-- Review: Peminjaman -->
    <div class="review-group">
      <div class="review-group-title">📅 Detail Peminjaman</div>
      <div class="review-row">
        <span class="review-key">Tgl. Pengambilan</span>
        <span class="review-val" id="rv-tgl-ambil">—</span>
      </div>
      <div class="review-row">
        <span class="review-key">Tgl. Kembali</span>
        <span class="review-val" id="rv-tgl-kembali">—</span>
      </div>
      <div class="review-row">
        <span class="review-key">Durasi</span>
        <span class="review-val" id="rv-durasi">14 Hari</span>
      </div>
      <div class="review-row">
        <span class="review-key">Metode Ambil</span>
        <span class="review-val" id="rv-metode">—</span>
      </div>
    </div>

    <!-- Denda Info -->
    <div class="denda-note">
      <span>⚠️</span>
      <span>Keterlambatan pengembalian dikenakan denda <strong>Rp10.000 / hari</strong>. Booking ID akan dikirim ke email Anda setelah pengajuan disetujui admin.</span>
    </div>

    <hr class="divider">

    <!-- Terms -->
    <div class="terms-box">
      <h4>Syarat &amp; Ketentuan Peminjaman</h4>
      <div class="terms-item"><span class="terms-num">1.</span><span>Mahasiswa wajib menunjukkan KTM atau QR Booking saat pengambilan buku.</span></div>
      <div class="terms-item"><span class="terms-num">2.</span><span>Maksimal 1 (satu) buku aktif dipinjam dalam satu waktu.</span></div>
      <div class="terms-item"><span class="terms-num">3.</span><span>Buku harus dikembalikan dalam kondisi baik. Kerusakan akan dikenakan biaya penggantian.</span></div>
      <div class="terms-item"><span class="terms-num">4.</span><span>Keterlambatan pengembalian dikenai denda Rp10.000 per hari kalender.</span></div>
      <div class="terms-item"><span class="terms-num">5.</span><span>Peminjaman otomatis batal jika buku tidak diambil dalam 2 hari kerja setelah disetujui.</span></div>
      <div class="terms-item"><span class="terms-num">6.</span><span>Mahasiswa yang memiliki denda aktif tidak dapat mengajukan peminjaman baru.</span></div>
    </div>

    <div class="checkbox-row">
      <div class="custom-checkbox" id="cb1" onclick="toggleCheck('cb1')"></div>
      <div class="checkbox-label" onclick="toggleCheck('cb1')">
        Saya telah membaca dan menyetujui <span>Syarat &amp; Ketentuan</span> peminjaman buku di atas.
      </div>
    </div>
    <div class="checkbox-row">
      <div class="custom-checkbox" id="cb2" onclick="toggleCheck('cb2')"></div>
      <div class="checkbox-label" onclick="toggleCheck('cb2')">
        Data yang saya isi adalah <span>benar dan valid</span>. Saya bertanggung jawab atas peminjaman ini.
      </div>
    </div>
    <div class="field-error" id="cb-error" style="margin-top:-4px">Centang kedua pernyataan di atas.</div>
  </div>

  <!-- SUCCESS -->
  <div class="success-section" id="section-success">
    <div class="success-icon">✅</div>
    <h2>Pengajuan Terkirim!</h2>
    <p>Permintaan peminjaman Anda sedang diproses oleh admin perpustakaan.<br>Admin akan scan QR Code ini untuk menyetujui atau menolak peminjaman Anda.</p>
    <div class="booking-id-display">
      📋 <span id="booking-id-text">—</span>
    </div>
    <div class="qr-placeholder" id="qr-container" style="display: flex; flex-direction: column; align-items: center;">
      <div id="qr-code-svg" style="border: 2px solid var(--navy); border-radius: 8px; padding: 8px; background: white; line-height: 0;"></div>
      <p style="margin-top: 12px; font-size: 12px; color: var(--gray);">Tunjukkan QR Code ini ke Admin Perpustakaan</p>
      <a href="#" id="btn-download-qr" class="btn btn-next" style="margin-top: 12px;">
        ⬇ Download QR Code
      </a>
    </div>
    <p style="font-size:12px;color:var(--gray);margin-top:16px;">Booking ID dan QR Code juga dikirimkan ke email Anda.</p>
  </div>

  <!-- ── BUTTON ROW ── -->
  <div class="btn-row" id="btn-row">
    <button class="btn btn-back" id="btn-back" onclick="prevStep()" style="display:none">
      ← Kembali
    </button>
    <button class="btn btn-next" id="btn-next" onclick="nextStep()">
      Lanjut →
    </button>
  </div>

</div><!-- /form-card -->
</div><!-- /form-wrap -->

<script>
// ── STATE ──────────────────────────────────
let currentStep = 1;
let selectedDuration = 14;
let selectedBook = null;
const checkboxes = { cb1: false, cb2: false };

// ── FAKE BOOK DATA ──────────────────────────
const BOOKS = [
  { id: 'BK-001', title: 'Algoritma & Pemrograman', author: 'Rinaldi Munir', category: 'Teknik Informatika', stock: 3, emoji: 'A' },
  { id: 'BK-002', title: 'Basis Data', author: 'Fathansyah', category: 'Sistem Informasi', stock: 2, emoji: 'B' },
  { id: 'BK-003', title: 'Kecerdasan Buatan', author: 'Suyanto', category: 'Teknik Informatika', stock: 1, emoji: 'K' },
  { id: 'BK-004', title: 'Struktur Data & Algoritma', author: 'Thomas H. Cormen', category: 'Ilmu Komputer', stock: 0, emoji: 'S' },
  { id: 'BK-005', title: 'Jaringan Komputer', author: 'Andrew S. Tanenbaum', category: 'Teknik Informatika', stock: 4, emoji: 'J' },
  { id: 'BK-006', title: 'Rekayasa Perangkat Lunak', author: 'Roger S. Pressman', category: 'Teknik Informatika', stock: 2, emoji: 'R' },
  { id: 'BK-007', title: 'Sistem Operasi', author: 'William Stallings', category: 'Ilmu Komputer', stock: 1, emoji: 'O' },
  { id: 'BK-008', title: 'Manajemen Proyek IT', author: 'Kathy Schwalbe', category: 'Manajemen Informatika', stock: 3, emoji: 'M' },
];

// ── REFERRAL TOKEN VALIDATION ──────────────
function validateReferralToken(input) {
  const val = input.value.trim().toUpperCase();
  input.value = val;
  const alertEl = document.getElementById('referral-alert');
  const alertText = document.getElementById('referral-alert-text');
  
  if (val.length === 0) {
    alertEl.style.display = 'none';
    input.classList.remove('valid', 'error');
    showErr('referral-error', false);
    return;
  }
  
  if (val.length < 6) {
    input.classList.remove('valid');
    input.classList.add('error');
    return;
  }
  
  // Validate token via API
  fetch(`{{ route('publik.cek-nim') }}?referral_token=${val}`)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.data && data.data.referral_token === val) {
        // Token valid
        input.classList.add('valid');
        input.classList.remove('error');
        alertEl.style.display = 'none';
        showErr('referral-error', false);
      } else {
        // Token invalid
        input.classList.add('error');
        input.classList.remove('valid');
        alertText.textContent = 'Token referral tidak valid atau tidak ditemukan dalam sistem.';
        alertEl.style.display = 'flex';
        showErr('referral-error', true);
      }
    })
    .catch(err => {
      input.classList.add('error');
      input.classList.remove('valid');
      alertText.textContent = 'Error memvalidasi token referral.';
      alertEl.style.display = 'flex';
      showErr('referral-error', true);
    });
}

// Helper function to set select value
function setSelectValue(id, value) {
  const select = document.getElementById(id);
  if (!select) return;
  for (let i = 0; i < select.options.length; i++) {
    if (select.options[i].value === value || select.options[i].text === value) {
      select.selectedIndex = i;
      break;
    }
  }
}

// ── NIM LOOKUP ─────────────────────────────
function checkNIM(input) {
  const val = input.value.replace(/\D/g, '');
  input.value = val;
  const statusEl = document.getElementById('nim-status');
  
  if (val.length < 8) {
    if (val.length > 0) {
      statusEl.textContent = 'NIM minimal 8 digit.';
      statusEl.className = 'nim-status not-found';
      input.classList.add('error');
    } else {
      statusEl.className = 'nim-status';
      input.classList.remove('valid', 'error');
    }
    return;
  }

  // Fetch data mahasiswa dari API
  statusEl.textContent = '⏳ Memeriksa NIM...';
  statusEl.className = 'nim-status';
  input.classList.remove('valid', 'error');

  fetch(`/pinjam/get-mahasiswa/${val}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Auto-fill form
        document.getElementById('nama').value = data.data.nama;
        setSelectValue('prodi', data.data.jurusan);
        document.getElementById('telp').value = data.data.no_telepon || '';

        statusEl.textContent = '✓ Mahasiswa ditemukan dalam sistem.';
        statusEl.className = 'nim-status found';
        input.classList.add('valid');
        input.classList.remove('error');
      } else {
        statusEl.textContent = '✗ NIM tidak ditemukan dalam sistem.';
        statusEl.className = 'nim-status not-found';
        input.classList.add('error');
        input.classList.remove('valid');
      }
    })
    .catch(err => {
      statusEl.textContent = '✗ Error memeriksa NIM.';
      statusEl.className = 'nim-status not-found';
      input.classList.add('error');
    });
}

// ── BOOK SEARCH ─────────────────────────────
function searchBooks(query) {
  const dropdown = document.getElementById('book-dropdown');
  if (!query || query.length < 2) { dropdown.classList.remove('open'); return; }
  const results = BOOKS.filter(b =>
    b.title.toLowerCase().includes(query.toLowerCase()) ||
    b.author.toLowerCase().includes(query.toLowerCase()) ||
    b.id.toLowerCase().includes(query.toLowerCase())
  );
  if (results.length === 0) {
    dropdown.innerHTML = `<div class="book-option" style="color:var(--gray);cursor:default">Tidak ada hasil ditemukan.</div>`;
  } else {
    dropdown.innerHTML = results.map(b => {
      const stockClass = b.stock > 2 ? 'stock-ok' : b.stock === 0 ? 'stock-empty' : 'stock-low';
      const stockLabel = b.stock === 0 ? 'Habis' : `${b.stock} tersedia`;
      return `<div class="book-option" onclick="selectBook('${b.id}')">
        <div class="book-cover">${b.emoji}</div>
        <div class="book-info">
          <div class="book-title-opt">${b.title}</div>
          <div class="book-meta-opt">${b.author} · ${b.category}</div>
        </div>
        <span class="book-stock ${stockClass}">${stockLabel}</span>
      </div>`;
    }).join('');
  }
  dropdown.classList.add('open');
}

function selectBook(id) {
  const book = BOOKS.find(b => b.id === id);
  if (!book) return;
  if (book.stock === 0) { alert('Stok buku ini sedang habis. Pilih buku lain.'); return; }
  selectedBook = book;
  document.getElementById('book-search').value = book.title;
  document.getElementById('book-dropdown').classList.remove('open');
  document.getElementById('sel-cover').textContent = book.emoji;
  document.getElementById('sel-title').textContent = book.title;
  document.getElementById('sel-author').textContent = book.author;
  document.getElementById('sel-category').textContent = book.category;
  document.getElementById('sel-id').textContent = book.id;
  document.getElementById('selected-book').classList.add('show');
  document.getElementById('book-error').classList.remove('show');
}

function clearBook() {
  selectedBook = null;
  document.getElementById('book-search').value = '';
  document.getElementById('selected-book').classList.remove('show');
}

// Close dropdown on outside click
document.addEventListener('click', e => {
  if (!e.target.closest('.book-search-wrap')) {
    document.getElementById('book-dropdown').classList.remove('open');
  }
});

// ── DURATION ─────────────────────────────────
function setDuration(days, el) {
  selectedDuration = days;
  document.querySelectorAll('.dur-chip').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  updateReturnDate();
}

function updateReturnDate() {
  const tglAmbil = document.getElementById('tgl-ambil').value;
  if (!tglAmbil) return;
  const d = new Date(tglAmbil);
  d.setDate(d.getDate() + selectedDuration);
  const ret = d.toISOString().split('T')[0];
  document.getElementById('tgl-kembali').value = ret;
  const preview = document.getElementById('date-preview');
  const fmt = date => new Date(date + 'T00:00:00').toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
  document.getElementById('date-preview-text').textContent =
    `Ambil: ${fmt(tglAmbil)} → Kembali: ${fmt(ret)} (${selectedDuration} hari)`;
  preview.classList.add('show');
}

// Set min date = tomorrow
(function() {
  const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 1);
  document.getElementById('tgl-ambil').min = tomorrow.toISOString().split('T')[0];
})();

// ── CHECKBOXES ────────────────────────────────
function toggleCheck(id) {
  checkboxes[id] = !checkboxes[id];
  const el = document.getElementById(id);
  el.classList.toggle('checked', checkboxes[id]);
}

// ── NAVIGATION ───────────────────────────────
function nextStep() {
  if (!validateStep(currentStep)) return;
  if (currentStep === 4) { submitForm(); return; }
  setStep(currentStep + 1);
}

function prevStep() {
  if (currentStep > 1) setStep(currentStep - 1);
}

function setStep(n) {
  document.getElementById('section-' + currentStep).classList.remove('active');
  document.getElementById('step-' + currentStep).classList.remove('active');
  document.getElementById('step-' + currentStep).classList.add('done');

  currentStep = n;

  document.getElementById('section-' + currentStep).classList.add('active');
  document.getElementById('step-' + currentStep).classList.remove('done');
  document.getElementById('step-' + currentStep).classList.add('active');

  document.getElementById('btn-back').style.display = currentStep > 1 ? 'flex' : 'none';
  const btnNext = document.getElementById('btn-next');
  if (currentStep === 4) {
    fillReview();
    btnNext.textContent = '✅ Kirim Pengajuan';
    btnNext.className = 'btn btn-submit';
  } else {
    btnNext.innerHTML = 'Lanjut →';
    btnNext.className = 'btn btn-next';
  }
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── VALIDATION ────────────────────────────────
function showErr(id, show) {
  const el = document.getElementById(id);
  if (el) el.classList.toggle('show', show);
}

function validateStep(step) {
  let ok = true;
  if (step === 1) {
    const nim = document.getElementById('nim').value.trim();
    const nama = document.getElementById('nama').value.trim();
    const prodi = document.getElementById('prodi').value;
    const email = document.getElementById('email').value.trim();
    const referralToken = document.getElementById('referral_token').value.trim();
    
    if (nim.length < 8) { showErr('nim-error', true); ok = false; } else showErr('nim-error', false);
    if (!nama) { showErr('nama-error', true); ok = false; } else showErr('nama-error', false);
    if (!prodi) { showErr('prodi-error', true); ok = false; } else showErr('prodi-error', false);
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRe.test(email)) { showErr('email-error', true); ok = false; } else showErr('email-error', false);
    
    // Validate referral token
    if (!referralToken || referralToken.length !== 6) {
      showErr('referral-error', true);
      document.getElementById('referral_token').classList.add('error');
      ok = false;
    } else {
      showErr('referral-error', false);
      // Check if token has valid class (was validated successfully)
      if (!document.getElementById('referral_token').classList.contains('valid')) {
        showErr('referral-error', true);
        ok = false;
      }
    }
  }
  if (step === 2) {
    if (!selectedBook) { showErr('book-error', true); ok = false; } else showErr('book-error', false);
  }
  if (step === 3) {
    const tgl = document.getElementById('tgl-ambil').value;
    if (!tgl) { showErr('tgl-error', true); ok = false; } else showErr('tgl-error', false);
  }
  if (step === 4) {
    if (!checkboxes.cb1 || !checkboxes.cb2) { showErr('cb-error', true); ok = false; } else showErr('cb-error', false);
  }
  return ok;
}

// ── FILL REVIEW ───────────────────────────────
function fillReview() {
  const fmt = v => new Date(v + 'T00:00:00').toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
  const metode = document.getElementById('metode').value;
  document.getElementById('rv-nim').textContent = document.getElementById('nim').value;
  document.getElementById('rv-nama').textContent = document.getElementById('nama').value;
  document.getElementById('rv-prodi').textContent = document.getElementById('prodi').value;
  document.getElementById('rv-email').textContent = document.getElementById('email').value;
  if (selectedBook) {
    document.getElementById('rv-cover').textContent = selectedBook.emoji;
    document.getElementById('rv-book-title').textContent = selectedBook.title;
    document.getElementById('rv-book-author').textContent = selectedBook.author;
    document.getElementById('rv-book-id').textContent = selectedBook.id;
  }
  const tglA = document.getElementById('tgl-ambil').value;
  const tglK = document.getElementById('tgl-kembali').value;
  document.getElementById('rv-tgl-ambil').textContent = tglA ? fmt(tglA) : '—';
  document.getElementById('rv-tgl-kembali').textContent = tglK ? fmt(tglK) : '—';
  document.getElementById('rv-durasi').textContent = selectedDuration + ' Hari';
  document.getElementById('rv-metode').textContent = metode === 'langsung' ? 'Ambil Langsung' : 'Antar ke Ruang';
}

// ── SUBMIT ────────────────────────────────────
// ── SUBMIT ────────────────────────────────────
function submitForm() {
  if (!validateStep(4)) return;

  const nim = document.getElementById('nim').value.trim();
  const nama = document.getElementById('nama').value.trim();
  const prodi = document.getElementById('prodi').value;
  const telp = document.getElementById('telp').value.trim();
  const email = document.getElementById('email').value.trim();

  if (!selectedBook) {
    alert('Pilih buku terlebih dahulu');
    return;
  }

  // Submit ke server
  const formData = new FormData();
  formData.append('nim', nim);
  formData.append('nama', nama);
  formData.append('jurusan', prodi);
  formData.append('no_telepon', telp);
  formData.append('referral_token', document.getElementById('referral_token').value.trim());
  formData.append('buku_ids[]', selectedBook.id);
  formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

  fetch('{{ route("publik.pinjam") }}', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const bookingId = data.booking_ids[0];
      document.getElementById('booking-id-text').textContent = bookingId;

      // Generate QR Code using QRCode library
      const qrContainer = document.getElementById('qr-code-svg');
      qrContainer.innerHTML = '';
      new QRCode(qrContainer, {
        text: bookingId,
        width: 200,
        height: 200,
        colorDark: '#0f2444',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
      });
      // Resize qr-container to fit content
      document.getElementById('qr-container').style.width = 'auto';

      // Setup download button
      document.getElementById('btn-download-qr').onclick = function(e) {
        e.preventDefault();
        downloadQRCode(bookingId);
      };

      // Show success section
      for (let i = 1; i <= 4; i++) {
        document.getElementById('section-' + i).classList.remove('active');
        document.getElementById('step-' + i).classList.remove('active');
        document.getElementById('step-' + i).classList.add('done');
      }
      document.getElementById('section-success').classList.add('active');
      document.getElementById('btn-row').style.display = 'none';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(err => {
    console.error(err);
    alert('Error submitting form: ' + err.message);
  });
}

function downloadQRCode(bookingId) {
  // qrcodejs renders either a canvas or an img depending on browser support
  const qrCanvas = document.querySelector('#qr-code-svg canvas');
  const qrImg = document.querySelector('#qr-code-svg img');

  if (qrCanvas) {
    const link = document.createElement('a');
    link.href = qrCanvas.toDataURL('image/png');
    link.download = `QR-${bookingId}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } else if (qrImg) {
    const link = document.createElement('a');
    link.href = qrImg.src;
    link.download = `QR-${bookingId}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } else {
    showAlert('QR Code belum siap. Tunggu sebentar lalu coba lagi.');
  }
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

</body>
</html>