<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku | SiPusaka</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    :root {
        --ink: #0d1117;
        --ink-mid: #1c2a3a;
        --paper: #f7f5f0;
        --paper-dark: #eeebe3;
        --amber: #d97706;
        --amber-lt: #fbbf24;
        --teal: #0d9488;
        --teal-lt: #2dd4bf;
        --red: #dc2626;
        --muted: #6b7280;
        --border: #e5e0d8;
        --white: #ffffff;
        --radius-lg: 20px;
        --radius-md: 12px;
        --shadow-card: 0 2px 16px rgba(13, 17, 23, .07);
        --shadow-hover: 0 12px 40px rgba(13, 17, 23, .14);
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--paper);
        color: var(--ink);
        min-height: 100vh;
    }

    /* ─── SCROLLBAR ─── */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 99px;
    }

    /* ─── NAVBAR ─── */
    .nav-wrap {
        background: var(--ink);
        position: sticky;
        top: 0;
        z-index: 100;
        border-bottom: 1px solid rgba(255, 255, 255, .06);
        backdrop-filter: blur(12px);
    }

    .nav-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
        height: 62px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .brand {
        font-family: 'Lora', serif;
        font-size: 1.45rem;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        letter-spacing: -.5px;
    }

    .brand em {
        color: var(--amber-lt);
        font-style: italic;
    }

    .nav-links {
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .nav-links a {
        color: rgba(255, 255, 255, .55);
        font-size: 13.5px;
        font-weight: 500;
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        transition: all .18s;
    }

    .nav-links a:hover {
        color: #fff;
        background: rgba(255, 255, 255, .08);
    }

    .cart-pill {
        display: none;
        align-items: center;
        gap: 8px;
        background: var(--amber);
        color: #fff;
        border: none;
        border-radius: 99px;
        padding: 7px 18px 7px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
    }

    .cart-pill:hover {
        background: var(--amber-lt);
        transform: scale(1.03);
    }

    .cart-pill .count {
        background: #fff;
        color: var(--amber);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ─── HERO ─── */
    .hero {
        background: var(--ink);
        padding: 72px 24px 80px;
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 60% at 10% 20%, rgba(13, 148, 136, .22) 0%, transparent 60%),
            radial-gradient(ellipse 50% 70% at 90% 80%, rgba(217, 119, 6, .18) 0%, transparent 55%);
        pointer-events: none;
    }

    .hero-inner {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 48px;
        align-items: center;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(13, 148, 136, .18);
        border: 1px solid rgba(13, 148, 136, .35);
        color: var(--teal-lt);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 99px;
        margin-bottom: 22px;
    }

    .hero-title {
        font-family: 'Lora', serif;
        font-size: clamp(2.2rem, 5vw, 3.8rem);
        font-weight: 700;
        line-height: 1.12;
        color: #fff;
        margin-bottom: 18px;
    }

    .hero-title em {
        color: var(--amber-lt);
        font-style: italic;
    }

    .hero-desc {
        color: rgba(255, 255, 255, .55);
        font-size: 15px;
        line-height: 1.7;
        max-width: 500px;
        margin-bottom: 36px;
    }

    .hero-stats {
        display: flex;
        gap: 36px;
    }

    .stat-item strong {
        display: block;
        font-family: 'Lora', serif;
        font-size: 2.4rem;
        font-weight: 700;
        color: var(--amber-lt);
        line-height: 1;
    }

    .stat-item span {
        color: rgba(255, 255, 255, .45);
        font-size: 12px;
        font-weight: 500;
        margin-top: 4px;
        display: block;
    }

    .hero-art {
        font-size: 9rem;
        opacity: .06;
        user-select: none;
        line-height: 1;
    }

    /* ─── FILTER BAR ─── */
    .filter-wrap {
        background: var(--white);
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 62px;
        z-index: 90;
        box-shadow: 0 1px 0 0 var(--border), 0 4px 16px rgba(13, 17, 23, .04);
    }

    .filter-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 14px 24px;
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 16px;
        align-items: center;
    }

    .search-box {
        position: relative;
    }

    .search-box .icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 13px;
        pointer-events: none;
    }

    .search-box input {
        width: 100%;
        padding: 9px 14px 9px 38px;
        border: 1.5px solid var(--border);
        border-radius: 99px;
        font-size: 13.5px;
        font-family: inherit;
        background: var(--paper);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .search-box input:focus {
        border-color: var(--teal);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .1);
    }

    .chips {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .chips::-webkit-scrollbar {
        display: none;
    }

    .chip {
        padding: 6px 16px;
        border-radius: 99px;
        border: 1.5px solid var(--border);
        background: var(--white);
        color: var(--ink);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: all .18s;
        user-select: none;
    }

    .chip:hover {
        border-color: var(--ink);
        background: var(--paper);
    }

    .chip.active {
        background: var(--ink);
        color: #fff;
        border-color: var(--ink);
    }

    .chip.teal.active {
        background: var(--teal);
        border-color: var(--teal);
    }

    .chip.amber.active {
        background: var(--amber);
        border-color: var(--amber);
    }

    .chip-divider {
        width: 1px;
        background: var(--border);
        margin: 0 2px;
        align-self: stretch;
    }

    /* ─── MAIN CONTENT ─── */
    .main {
        max-width: 1280px;
        margin: 0 auto;
        padding: 40px 24px 100px;
    }

    /* ─── SECTION HEADING ─── */
    .sec-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .sec-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sec-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .sec-icon.physical {
        background: rgba(13, 17, 23, .08);
    }

    .sec-icon.digital {
        background: rgba(13, 148, 136, .1);
    }

    .sec-label {
        font-family: 'Lora', serif;
        font-size: 1.4rem;
        font-weight: 700;
    }

    .sec-count {
        background: var(--paper-dark);
        color: var(--muted);
        font-size: 12px;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 99px;
    }

    .sec-divider {
        height: 1px;
        background: var(--border);
        margin: 0 0 32px;
        flex: 1;
        margin-left: 16px;
    }

    /* ─── TAB SWITCHER ─── */
    .tab-switch {
        display: flex;
        gap: 0;
        background: var(--paper-dark);
        border-radius: 99px;
        padding: 4px;
        margin-bottom: 36px;
        width: fit-content;
    }

    .tab-btn {
        padding: 8px 24px;
        border-radius: 99px;
        border: none;
        font-size: 13.5px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: all .22s;
        color: var(--muted);
        background: transparent;
    }

    .tab-btn.active {
        background: var(--white);
        color: var(--ink);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
    }

    /* ─── BOOK GRID ─── */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 20px;
    }

    /* ─── BOOK CARD ─── */
    .book-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-card);
        overflow: hidden;
        cursor: pointer;
        transition: transform .22s, box-shadow .22s, border-color .22s;
        position: relative;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(13, 17, 23, .18);
    }

    .book-card.selected {
        border-color: var(--amber);
        box-shadow: 0 0 0 3px rgba(217, 119, 6, .15), var(--shadow-hover);
    }

    .book-card.out-of-stock {
        opacity: .55;
        cursor: not-allowed;
    }

    .book-card.out-of-stock:hover {
        transform: none;
        box-shadow: var(--shadow-card);
    }

    .card-cover {
        width: 100%;
        height: 195px;
        object-fit: cover;
        display: block;
        background: linear-gradient(135deg, #e5e7eb, #d1d5db);
    }

    .card-cover-ph {
        width: 100%;
        height: 195px;
        background: linear-gradient(135deg, #f3f4f6, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
        color: #9ca3af;
    }

    /* Ebook shimmer cover */
    .card-cover-ebook {
        width: 100%;
        height: 195px;
        background: linear-gradient(135deg, #0f4c75, #1b7daa, #0d9488);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 38px;
        position: relative;
        overflow: hidden;
    }

    .card-cover-ebook::before {
        content: '';
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(45deg, transparent, transparent 8px, rgba(255, 255, 255, .03) 8px, rgba(255, 255, 255, .03) 16px);
    }

    .card-cover-ebook span.label {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, .6);
        text-transform: uppercase;
        font-family: inherit;
    }

    .selected-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--amber);
        color: #fff;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 2px 8px rgba(217, 119, 6, .5);
        z-index: 2;
    }

    .book-card.selected .selected-badge {
        display: flex;
    }

    /* Digital badge */
    .digital-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: var(--teal);
        color: #fff;
        font-size: 9.5px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 3px 9px;
        border-radius: 6px;
        z-index: 2;
    }

    .card-body {
        padding: 14px 15px 15px;
    }

    .card-jenis {
        display: inline-block;
        font-size: 9.5px;
        font-weight: 800;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: var(--teal);
        background: rgba(13, 148, 136, .1);
        padding: 2px 9px;
        border-radius: 5px;
        margin-bottom: 8px;
    }

    .card-title {
        font-family: 'Lora', serif;
        font-size: 13.5px;
        font-weight: 700;
        line-height: 1.45;
        color: var(--ink);
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-pub {
        font-size: 11.5px;
        color: var(--muted);
        margin-bottom: 10px;
    }

    .card-meta {
        display: flex;
        gap: 12px;
        margin-bottom: 10px;
    }

    .card-meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: var(--muted);
    }

    .card-stars {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        margin-bottom: 10px;
    }

    .stars {
        color: #f59e0b;
        font-size: 12px;
        letter-spacing: .5px;
    }

    .rating-val {
        font-weight: 700;
        color: var(--ink);
    }

    .rating-cnt {
        color: var(--muted);
    }

    .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stok {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 6px;
    }

    .stok-ok {
        background: #dcfce7;
        color: #15803d;
    }

    .stok-low {
        background: #fef3c7;
        color: #b45309;
    }

    .stok-out {
        background: #fee2e2;
        color: #b91c1c;
    }

    .stok-ebook {
        background: rgba(13, 148, 136, .12);
        color: var(--teal);
    }

    .pick-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: var(--muted);
        cursor: pointer;
        transition: all .18s;
        flex-shrink: 0;
    }

    .pick-btn:hover {
        border-color: var(--amber);
        color: var(--amber);
    }

    .book-card.selected .pick-btn {
        background: var(--amber);
        border-color: var(--amber);
        color: #fff;
    }

    .read-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--teal);
        color: #fff;
        padding: 5px 13px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .18s;
    }

    .read-btn:hover {
        background: #0f766e;
        color: #fff;
        transform: scale(1.04);
    }

    /* ─── FLOATING CART ─── */
    .float-cart {
        position: fixed;
        bottom: 24px;
        left: 50%;
        z-index: 200;
        transform: translateX(-50%) translateY(120px);
        background: var(--ink);
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 20px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 20px 60px rgba(13, 17, 23, .45);
        transition: transform .4s cubic-bezier(.34, 1.56, .64, 1);
        min-width: 360px;
    }

    .float-cart.show {
        transform: translateX(-50%) translateY(0);
    }

    .float-cart-info strong {
        display: block;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
    }

    .float-cart-info span {
        color: rgba(255, 255, 255, .45);
        font-size: 11.5px;
    }

    .float-cart-preview {
        display: flex;
        gap: 6px;
        margin-top: 8px;
    }

    .mini-cover {
        width: 34px;
        height: 44px;
        border-radius: 6px;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, .15);
    }

    .mini-ph {
        width: 34px;
        height: 44px;
        border-radius: 6px;
        background: rgba(255, 255, 255, .08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .submit-btn {
        background: var(--amber);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 22px;
        font-size: 13.5px;
        font-weight: 800;
        font-family: inherit;
        cursor: pointer;
        white-space: nowrap;
        transition: all .2s;
        flex-shrink: 0;
    }

    .submit-btn:hover {
        background: var(--amber-lt);
        transform: scale(1.04);
    }

    /* ─── EMPTY STATE ─── */
    .empty {
        text-align: center;
        padding: 80px 16px;
        color: var(--muted);
        grid-column: 1 / -1;
    }

    .empty-icon {
        font-size: 60px;
        margin-bottom: 16px;
        opacity: .3;
    }

    /* ─── MODAL ─── */
    .modal-content {
        border-radius: var(--radius-lg);
        border: none;
    }

    .modal-head {
        background: var(--ink);
        color: #fff;
        padding: 24px 28px;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .modal-head-title {
        font-family: 'Lora', serif;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .modal-head p {
        color: rgba(255, 255, 255, .5);
        font-size: 13px;
        margin-top: 4px;
    }

    .modal-body {
        padding: 28px;
    }

    .book-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 24px;
    }

    .book-chip {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--paper);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 500;
    }

    .book-chip img {
        width: 30px;
        height: 38px;
        border-radius: 4px;
        object-fit: cover;
    }

    .rm-chip {
        width: 20px;
        height: 20px;
        background: #fee2e2;
        color: var(--red);
        border: none;
        border-radius: 50%;
        font-size: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-field {
        margin-bottom: 16px;
    }

    .form-field label {
        font-size: 13px;
        font-weight: 700;
        display: block;
        margin-bottom: 6px;
        color: #374151;
    }

    .form-field input {
        width: 100%;
        padding: 11px 14px;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        font-size: 14px;
        font-family: inherit;
        background: var(--paper);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .form-field input:focus {
        border-color: var(--teal);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .1);
    }

    .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 12.5px;
        color: #1e40af;
        margin: 16px 0;
    }

    .big-btn {
        width: 100%;
        padding: 13px;
        border-radius: 12px;
        border: none;
        background: var(--ink);
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        font-family: inherit;
        cursor: pointer;
        transition: all .2s;
    }

    .big-btn:hover {
        background: var(--ink-mid);
        transform: translateY(-1px);
    }

    .big-btn:disabled {
        opacity: .55;
        cursor: not-allowed;
        transform: none;
    }

    /* ─── SUCCESS ─── */
    .success-wrap {
        text-align: center;
        padding: 16px 0;
    }

    .success-circle {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #dcfce7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 16px;
    }

    .booking-codes {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-top: 12px;
    }

    .booking-code {
        background: var(--paper-dark);
        border: 1px solid var(--border);
        padding: 6px 16px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 13px;
        font-weight: 700;
        color: var(--ink);
    }

    /* ─── CHAT ─── */
    .chat-wrap {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .chat-fab {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: none;
        background: var(--teal);
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(13, 148, 136, .45);
        transition: all .25s;
        position: relative;
    }

    .chat-fab:hover {
        transform: scale(1.08);
    }

    .chat-fab svg {
        width: 24px;
        height: 24px;
    }

    .chat-notif {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 18px;
        height: 18px;
        background: var(--red);
        border: 2px solid var(--paper);
        border-radius: 50%;
        font-size: 10px;
        color: #fff;
        font-weight: 800;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .chat-panel {
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 340px;
        height: 440px;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 16px 60px rgba(13, 17, 23, .18);
        border: 1px solid var(--border);
        display: none;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-panel.open {
        display: flex;
    }

    .chat-panel-head {
        background: var(--teal);
        color: #fff;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-panel-head strong {
        font-size: 14px;
        font-weight: 700;
    }

    .chat-panel-head small {
        font-size: 11px;
        opacity: .7;
        display: block;
        margin-top: 1px;
    }

    .chat-x {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
    }

    .chat-x:hover {
        background: rgba(255, 255, 255, .2);
    }

    .chat-msgs {
        flex: 1;
        padding: 14px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: #f8fafc;
    }

    .msg {
        max-width: 78%;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .msg.user {
        align-self: flex-end;
    }

    .msg.bot,
    .msg.admin {
        align-self: flex-start;
    }

    .msg-text {
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.55;
        word-wrap: break-word;
    }

    .msg.user .msg-text {
        background: var(--teal);
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .msg.bot .msg-text,
    .msg.admin .msg-text {
        background: #fff;
        color: var(--ink);
        border: 1px solid var(--border);
        border-bottom-left-radius: 4px;
    }

    .msg-time {
        font-size: 10.5px;
        color: var(--muted);
    }

    .msg.user .msg-time {
        text-align: right;
    }

    .chat-input-wrap {
        padding: 12px 14px;
        border-top: 1px solid var(--border);
        background: #fff;
        display: flex;
        gap: 8px;
    }

    .chat-input-wrap input {
        flex: 1;
        padding: 9px 14px;
        border: 1.5px solid var(--border);
        border-radius: 99px;
        font-size: 13px;
        font-family: inherit;
        outline: none;
        transition: border-color .2s;
    }

    .chat-input-wrap input:focus {
        border-color: var(--teal);
    }

    .chat-send {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 50%;
        background: var(--teal);
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .18s;
        flex-shrink: 0;
    }

    .chat-send:hover {
        background: #0f766e;
    }

    .chat-send svg {
        width: 15px;
        height: 15px;
    }

    .typing {
        display: flex;
        gap: 4px;
        padding: 10px 14px;
    }

    .typing span {
        width: 7px;
        height: 7px;
        background: var(--muted);
        border-radius: 50%;
        animation: t 1.3s infinite ease-in-out both;
    }

    .typing span:nth-child(1) {
        animation-delay: -.28s;
    }

    .typing span:nth-child(2) {
        animation-delay: -.14s;
    }

    @keyframes t {

        0%,
        80%,
        100% {
            transform: scale(0);
        }

        40% {
            transform: scale(1);
        }
    }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 768px) {
        .filter-inner {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .books-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .float-cart {
            min-width: calc(100vw - 32px);
        }

        .hero-art {
            display: none;
        }

        .hero-inner {
            grid-template-columns: 1fr;
        }

        .hero-stats {
            gap: 24px;
        }
    }

    @media (max-width: 480px) {
        .chat-panel {
            width: calc(100vw - 32px);
            height: 55vh;
        }
    }

    /* ─── SECTION SEPARATOR ─── */
    .section-sep {
        display: flex;
        align-items: center;
        gap: 16px;
        margin: 52px 0 32px;
    }

    .sep-line {
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .sep-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 99px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .sep-badge.physical {
        background: var(--paper-dark);
        color: var(--ink-mid);
        border: 1.5px solid var(--border);
    }

    .sep-badge.digital {
        background: rgba(13, 148, 136, .12);
        color: var(--teal);
        border: 1.5px solid rgba(13, 148, 136, .25);
    }

    /* ─── TOAST ─── */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ─── ANIMASI HERO (TAMBAHAN PRABOWO) ─── */

    /* 1. Animasi untuk Judul Utama */
    .hero-title {
        opacity: 0;
        transform: translateY(30px);
        animation: heroReveal 1s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    /* 2. Animasi khusus untuk teks Emphasize (Favoritmu) */
    .hero-title em {
        display: inline-block;
        position: relative;
        animation: textPulse 2.5s ease-in-out infinite alternate;
        animation-delay: 1s;
        /* Mulai setelah judul muncul */
    }

    /* 3. Efek Staggered untuk Deskripsi agar muncul setelah Judul */
    .hero-desc {
        opacity: 0;
        transform: translateY(20px);
        animation: heroReveal 1s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        animation-delay: 0.3s;
    }

    /* 4. Efek Staggered untuk Stats */
    .hero-stats {
        opacity: 0;
        transform: translateY(20px);
        animation: heroReveal 1s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        animation-delay: 0.5s;
    }

    /* Keyframes */
    @keyframes heroReveal {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes textPulse {
        0% {
            text-shadow: 0 0 0px transparent;
            transform: scale(1);
        }

        50% {
            text-shadow: 0 0 15px rgba(251, 191, 36, 0.3);
            transform: scale(1.02);
        }

        100% {
            text-shadow: 0 0 5px rgba(251, 191, 36, 0.1);
            transform: scale(1);
        }
    }

    .static-line {
        display: block;
    }

    .rotating-wrap {
        display: block;
        height: 1.18em;
        overflow: hidden;
        position: relative;
    }

    .rotating-word {
        display: block;
        font-style: italic;
        color: var(--amber-lt);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        transform: translateY(100%);
        opacity: 0;
    }

    .rotating-word.active {
        animation: rotateIn 0.55s cubic-bezier(.22, 1, .36, 1) forwards;
    }

    .rotating-word.exit {
        animation: rotateOut 0.45s cubic-bezier(.55, 0, .45, 1) forwards;
    }

    @keyframes rotateIn {
        from {
            transform: translateY(60%);
            opacity: 0;
            filter: blur(6px);
        }

        to {
            transform: translateY(0);
            opacity: 1;
            filter: blur(0);
        }
    }

    @keyframes rotateOut {
        from {
            transform: translateY(0);
            opacity: 1;
            filter: blur(0);
        }

        to {
            transform: translateY(-50%);
            opacity: 0;
            filter: blur(4px);
        }
    }

    .cursor {
        display: inline-block;
        width: 3px;
        height: 0.75em;
        background: var(--amber-lt);
        margin-left: 4px;
        vertical-align: middle;
        animation: blink 1s step-end infinite;
        border-radius: 2px;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }
    </style>
</head>

<body>

    {{-- ─── NAVBAR ─── --}}
    <nav class="nav-wrap">
        <div class="nav-inner">
            <a href="/" class="brand">📚 Si<em>Pusaka</em></a>
            <div class="nav-links d-none d-md-flex">
                <a href="/"><i class="fas fa-home me-1"></i>Beranda</a>
                <a href="/cek-status"><i class="fas fa-search me-1"></i>Cek Status</a>
                <a href="/mahasiswa/login"><i class="fas fa-user me-1"></i>Mahasiswa</a>
            </div>
            <button class="cart-pill" id="cartBtn" onclick="bukaModalPinjam()">
                <i class="fas fa-book-open"></i>
                <span id="cartLabel">Pinjam Buku</span>
                <div class="count" id="cartCount">0</div>
            </button>
        </div>
    </nav>

    {{-- ─── HERO ─── --}}
    <section class="hero">
        <div class="hero-inner">
            <div>
                <div class="hero-eyebrow">
                    <i class="fas fa-sparkles" style="font-size:10px"></i> Perpustakaan Digital
                </div>
                <h1 class="hero-title">
                    <span class="static-line">Temukan Buku</span>
                    <span class="rotating-wrap" id="rotatingWrap">
                        <span class="rotating-word active" id="rw-0">Favoritmu</span>
                        <span class="rotating-word" id="rw-1">Terpopuler</span>
                        <span class="rotating-word" id="rw-2">Terbaru</span>
                        <span class="rotating-word" id="rw-3">Pilihanmu</span>
                        <span class="cursor"></span>
                    </span>
                    <span class="static-line" style="font-style:normal">di Sini</span>
                </h1>
                <p class="hero-desc">
                    Jelajahi koleksi buku fisik dan e-book perpustakaan kami. Pinjam hingga 3 buku sekaligus atau baca
                    e-book langsung di browser — kapan saja, di mana saja.
                </p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <strong>{{ $bukus->count() }}</strong>
                        <span>Total Koleksi</span>
                    </div>
                    <div class="stat-item">
                        <strong>{{ $bukus->where('stok_tersedia', '>', 0)->count() }}</strong>
                        <span>Tersedia</span>
                    </div>
                    <div class="stat-item">
                        <strong>{{ $jenisBuku->count() }}</strong>
                        <span>Kategori</span>
                    </div>
                    <div class="stat-item">
                        <strong>{{ $bukus->whereIn('genre_buku', ['Ebook','Hybrid'])->count() }}</strong>
                        <span>E-Book</span>
                    </div>
                </div>
            </div>
            <div class="hero-art d-none d-lg-block">📚</div>
        </div>
    </section>

    {{-- ─── FILTER BAR ─── --}}
    <div class="filter-wrap">
        <div class="filter-inner">
            <div class="search-box">
                <i class="fas fa-search icon"></i>
                <input type="text" id="searchInput" placeholder="Cari judul atau penerbit…">
            </div>
            <div class="chips" id="filterChips">
                <button class="chip active" onclick="filterJenis('semua', this)">Semua</button>
                @foreach($jenisBuku as $jenis)
                <button class="chip" onclick="filterJenis('{{ $jenis }}', this)">{{ $jenis }}</button>
                @endforeach
                <button class="chip teal" onclick="filterJenis('tersedia', this)">✅ Tersedia</button>
                <div class="chip-divider"></div>
                <button class="chip" id="r-semua" onclick="filterRating('semua',this)">⭐ Semua</button>
                <button class="chip" id="r-4" onclick="filterRating('4',this)">⭐ 4+</button>
                <button class="chip" id="r-3" onclick="filterRating('3',this)">⭐ 3+</button>
            </div>
        </div>
    </div>

    {{-- ─── MAIN CATALOG ─── --}}
    <main class="main">

        {{-- Tab Switcher --}}
        <div class="tab-switch" id="tabSwitch">
            <button class="tab-btn active" onclick="switchTab('semua', this)">📦 Semua</button>
            <button class="tab-btn" onclick="switchTab('fisik', this)">📗 Buku Fisik</button>
            <button class="tab-btn" onclick="switchTab('digital', this)">💻 E-Book</button>
        </div>

        {{-- ── BUKU FISIK SECTION ── --}}
        <div id="sectionFisik">
            <div class="section-sep">
                <div class="sep-badge physical">
                    <i class="fas fa-book"></i> Buku Fisik
                </div>
                <div class="sep-line"></div>
                <span class="sec-count" id="countFisik">
                    {{ $bukus->whereNotIn('genre_buku', ['Ebook','Hybrid'])->count() }} buku
                </span>
            </div>

            <div class="books-grid" id="gridFisik">
                @forelse($bukus->whereNotIn('genre_buku', ['Ebook','Hybrid']) as $buku)
                <div class="book-card {{ $buku->stok_tersedia < 1 ? 'out-of-stock' : '' }}" id="card-{{ $buku->id }}"
                    data-id="{{ $buku->id }}" data-nama="{{ $buku->nama_buku }}" data-penerbit="{{ $buku->penerbit }}"
                    data-jenis="{{ $buku->jenis_buku }}" data-genre="Fisik" data-stok="{{ $buku->stok_tersedia }}"
                    data-rating="{{ $buku->average_rating ?? 0 }}"
                    data-sampul="{{ $buku->sampul_buku ? asset('storage/'.$buku->sampul_buku) : '' }}"
                    onclick="window.location.href='{{ route('publik.buku.show', ['id' => $buku->id]) }}'">
                    <div class="selected-badge" id="badge-{{ $buku->id }}">✓</div>
                    @if($buku->sampul_buku)
                    <img src="{{ asset('storage/'.$buku->sampul_buku) }}" alt="{{ $buku->nama_buku }}"
                        class="card-cover">
                    @else
                    <div class="card-cover-ph">📖</div>
                    @endif
                    <div class="card-body">
                        <span class="card-jenis">{{ $buku->jenis_buku }}</span>
                        <div class="card-title">{{ $buku->nama_buku }}</div>
                        <div class="card-pub">{{ $buku->penerbit }}</div>
                        <div class="card-meta">
                            <div class="card-meta-item">
                                <i class="fas fa-eye" style="color:#3b82f6;font-size:10px"></i>
                                {{ $buku->view_count ?? 0 }}
                            </div>
                            <div class="card-meta-item">
                                <i class="fas fa-book-open" style="color:#10b981;font-size:10px"></i>
                                {{ $buku->borrow_count ?? 0 }}
                            </div>
                        </div>
                        @if($buku->review_count > 0)
                        <div class="card-stars">
                            <span
                                class="stars">{{ str_repeat('★', round($buku->average_rating)) }}{{ str_repeat('☆', 5 - round($buku->average_rating)) }}</span>
                            <span class="rating-val">{{ number_format($buku->average_rating, 1) }}</span>
                            <span class="rating-cnt">({{ $buku->review_count }})</span>
                        </div>
                        @endif
                        <div class="card-footer">
                            @if($buku->stok_tersedia < 1) <span class="stok stok-out">Habis</span>
                                @elseif($buku->stok_tersedia <= 3) <span class="stok stok-low">Sisa
                                    {{ $buku->stok_tersedia }}</span>
                                    @else
                                    <span class="stok stok-ok">Tersedia {{ $buku->stok_tersedia }}</span>
                                    @endif
                                    @if($buku->stok_tersedia > 0)
                                    <div class="pick-btn" id="pickbtn-{{ $buku->id }}"
                                        onclick="event.stopPropagation(); togglePilih(document.getElementById('card-{{ $buku->id }}'))">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty" style="grid-column:1/-1">
                    <div class="empty-icon">📚</div>
                    <p style="font-weight:600;margin-bottom:4px">Belum ada buku fisik</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ── E-BOOK SECTION ── --}}
        <div id="sectionDigital">
            <div class="section-sep">
                <div class="sep-badge digital">
                    <i class="fas fa-laptop-code"></i> E-Book Digital
                </div>
                <div class="sep-line"></div>
                <span class="sec-count" id="countDigital">
                    {{ $bukus->whereIn('genre_buku', ['Ebook','Hybrid'])->count() }} buku
                </span>
            </div>

            <div class="books-grid" id="gridDigital">
                @forelse($bukus->whereIn('genre_buku', ['Ebook','Hybrid']) as $buku)
                <div class="book-card" id="card-{{ $buku->id }}" data-id="{{ $buku->id }}"
                    data-nama="{{ $buku->nama_buku }}" data-penerbit="{{ $buku->penerbit }}"
                    data-jenis="{{ $buku->jenis_buku }}" data-genre="{{ $buku->genre_buku }}" data-stok="99"
                    data-rating="{{ $buku->average_rating ?? 0 }}"
                    data-sampul="{{ $buku->sampul_buku ? asset('storage/'.$buku->sampul_buku) : '' }}"
                    onclick="window.location.href='{{ route('publik.buku.show', ['id' => $buku->id]) }}'">
                    <span class="digital-badge">E-Book</span>
                    @if($buku->sampul_buku)
                    <img src="{{ asset('storage/'.$buku->sampul_buku) }}" alt="{{ $buku->nama_buku }}"
                        class="card-cover">
                    @else
                    <div class="card-cover-ebook">
                        <span>📱</span>
                        <span class="label">Digital</span>
                    </div>
                    @endif
                    <div class="card-body">
                        <span class="card-jenis" style="color:var(--teal)">{{ $buku->jenis_buku }}</span>
                        <div class="card-title">{{ $buku->nama_buku }}</div>
                        <div class="card-pub">{{ $buku->penerbit }}</div>
                        <div class="card-meta">
                            <div class="card-meta-item">
                                <i class="fas fa-eye" style="color:#3b82f6;font-size:10px"></i>
                                {{ $buku->view_count ?? 0 }}
                            </div>
                            <div class="card-meta-item">
                                <i class="fas fa-book-open" style="color:#10b981;font-size:10px"></i>
                                {{ $buku->borrow_count ?? 0 }}
                            </div>
                        </div>
                        @if($buku->review_count > 0)
                        <div class="card-stars">
                            <span
                                class="stars">{{ str_repeat('★', round($buku->average_rating)) }}{{ str_repeat('☆', 5 - round($buku->average_rating)) }}</span>
                            <span class="rating-val">{{ number_format($buku->average_rating, 1) }}</span>
                            <span class="rating-cnt">({{ $buku->review_count }})</span>
                        </div>
                        @endif
                        <div class="card-footer">
                            <span class="stok stok-ebook">E-Book</span>
                            <a href="{{ route('publik.ebook.baca', ['id' => $buku->id]) }}" class="read-btn"
                                onclick="event.stopPropagation()" target="_blank">
                                <i class="fas fa-book-reader" style="font-size:10px"></i>Baca
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty" style="grid-column:1/-1">
                    <div class="empty-icon">💻</div>
                    <p style="font-weight:600;margin-bottom:4px">Belum ada e-book</p>
                </div>
                @endforelse
            </div>
        </div>

    </main>

    {{-- ─── FLOATING CART ─── --}}
    <div class="float-cart" id="floatCart">
        <div style="flex:1">
            <div class="float-cart-info">
                <strong id="floatTitle">0 Buku Dipilih</strong>
                <span>Maks. 3 buku fisik per peminjaman</span>
            </div>
            <div class="float-cart-preview" id="cartPreview"></div>
        </div>
        <button class="submit-btn" onclick="bukaModalPinjam()">
            <i class="fas fa-paper-plane me-2"></i>Ajukan Pinjam
        </button>
    </div>

    {{-- ─── MODAL PEMINJAMAN ─── --}}
    <div class="modal fade" id="modalPinjam" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-head">
                    <div>
                        <div class="modal-head-title">📋 Form Peminjaman Buku</div>
                        <p>Isi data diri untuk mengajukan peminjaman</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="formState">
                        <label style="font-size:13px;font-weight:700;display:block;margin-bottom:10px">
                            <i class="fas fa-book me-2" style="color:var(--teal)"></i>Buku yang Dipilih
                        </label>
                        <div class="book-chips" id="selectedBooksList"></div>
                        <div id="alertPinjam"></div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label>Nama Lengkap <span style="color:var(--red)">*</span></label>
                                    <input type="text" id="f_nama" placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label>NIM <span style="color:var(--red)">*</span></label>
                                    <input type="text" id="f_nim" placeholder="Nomor Induk Mahasiswa"
                                        oninput="fetchMahasiswaForModal(this.value)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label>Jurusan <span style="color:var(--red)">*</span></label>
                                    <input type="text" id="f_jurusan" placeholder="Program studi / jurusan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label>No. Telepon <span style="color:var(--red)">*</span></label>
                                    <input type="text" id="f_telepon" placeholder="08xx-xxxx-xxxx">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-field">
                                    <label>Kode Referral <span style="color:var(--red)">*</span></label>
                                    <input type="text" id="f_referral_token" placeholder="6 digit kode referral"
                                        maxlength="6" oninput="validateReferralTokenModal(this)"
                                        style="text-transform:uppercase;letter-spacing:2px">
                                    <small style="color:var(--muted);font-size:12px;display:block;margin-top:4px">
                                        Wajib diisi untuk mengakses e-book setelah persetujuan.
                                    </small>
                                    <div id="referral_token_error"
                                        style="display:none;color:var(--red);font-size:12px;margin-top:4px">
                                        Token referral tidak valid.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="info-box">
                            <i class="fas fa-info-circle me-2"></i>
                            NIM harus sudah terdaftar di sistem. Batas peminjaman <strong>7 hari</strong>.
                            Keterlambatan dikenakan denda <strong>Rp10.000/hari</strong>.
                        </div>
                        <button class="big-btn" id="btnSubmit" onclick="submitPinjam()">
                            <i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman
                        </button>
                    </div>
                    <div id="successState" style="display:none">
                        <div class="success-wrap">
                            <div class="success-circle">✅</div>
                            <h5 style="font-family:'Lora',serif;font-weight:700;margin-bottom:8px">Peminjaman Berhasil
                                Diajukan!</h5>
                            <p style="color:var(--muted);font-size:14px">
                                Permintaan kamu sudah diterima. Tunggu konfirmasi dan QR Code dari admin.
                            </p>
                            <div
                                style="background:var(--paper);border-radius:12px;padding:16px;margin:16px 0;text-align:left">
                                <div
                                    style="font-size:11px;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;font-weight:700">
                                    Booking ID kamu:
                                </div>
                                <div class="booking-codes" id="bookingChips"></div>
                            </div>
                            <p style="font-size:12px;color:var(--muted)">Simpan Booking ID untuk cek status peminjaman.
                            </p>
                            <button onclick="resetForm()"
                                style="margin-top:12px;padding:10px 24px;border-radius:10px;border:1.5px solid var(--border);background:var(--paper);font-weight:700;cursor:pointer;font-family:inherit">
                                <i class="fas fa-redo me-2"></i>Pinjam Lagi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── CHAT WIDGET ─── --}}
    <div class="chat-wrap">
        <button class="chat-fab" id="chatToggle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
            <div class="chat-notif" id="chatBadge"></div>
        </button>
        <div class="chat-panel" id="chatBox">
            <div class="chat-panel-head">
                <div>
                    <strong>💬 SIPUSAKA Assistant</strong>
                    <small>Tanya apa saja tentang koleksi kami</small>
                </div>
                <button class="chat-x" id="chatClose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" style="width:16px;height:16px">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="chat-msgs" id="chatMessages"></div>
            <form class="chat-input-wrap" id="chatForm">
                @csrf
                <input type="hidden" id="chatSessionId" value="">
                <input type="hidden" id="lastMessageId" value="0">
                <input type="text" id="chatInput" placeholder="Ketik pesan…" autocomplete="off" required>
                <button type="submit" class="chat-send">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13" />
                        <polygon points="22 2 15 22 11 13 2 9 22 2" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    /* ─── STATE ─── */
    let selectedBooks = [];
    const MAX = 3;
    let currentRating = 'semua';
    let currentTab = 'semua';

    /* ─── PICK / UNPICK ─── */
    function togglePilih(card) {
        const id = parseInt(card.dataset.id);
        const idx = selectedBooks.findIndex(b => b.id === id);
        if (idx > -1) {
            selectedBooks.splice(idx, 1);
            card.classList.remove('selected');
        } else {
            if (selectedBooks.length >= MAX) {
                showToast('Maksimal 3 buku yang bisa dipilih!', 'warn');
                return;
            }
            selectedBooks.push({
                id,
                nama: card.dataset.nama,
                penerbit: card.dataset.penerbit,
                sampul: card.dataset.sampul
            });
            card.classList.add('selected');
        }
        updateCartUI();
    }

    function updateCartUI() {
        const n = selectedBooks.length;
        const btn = document.getElementById('cartBtn');
        document.getElementById('cartCount').textContent = n;
        document.getElementById('cartLabel').textContent = n === 1 ? '1 Dipilih' : n + ' Dipilih';
        btn.style.display = n > 0 ? 'flex' : 'none';

        const fc = document.getElementById('floatCart');
        document.getElementById('floatTitle').textContent = n + ' Buku Dipilih';
        document.getElementById('cartPreview').innerHTML = selectedBooks.map(b =>
            b.sampul ?
            `<img src="${b.sampul}" class="mini-cover" title="${b.nama}">` :
            `<div class="mini-ph" title="${b.nama}">📖</div>`
        ).join('');
        n > 0 ? fc.classList.add('show') : fc.classList.remove('show');
    }

    /* ─── TAB SWITCH ─── */
    function switchTab(tab, btn) {
        currentTab = tab;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const sf = document.getElementById('sectionFisik');
        const sd = document.getElementById('sectionDigital');

        if (tab === 'semua') {
            sf.style.display = '';
            sd.style.display = '';
        } else if (tab === 'fisik') {
            sf.style.display = '';
            sd.style.display = 'none';
        } else {
            sf.style.display = 'none';
            sd.style.display = '';
        }
    }

    /* ─── FILTER ─── */
    function filterJenis(jenis, btn) {
        document.querySelectorAll('#filterChips .chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        applyFilters(jenis);
    }

    function filterRating(r, btn) {
        document.querySelectorAll('#r-semua,#r-4,#r-3').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        currentRating = r;
        applyFilters();
    }

    function applyFilters(jenis) {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const activeChip = document.querySelector('#filterChips .chip.active');
        const activeJenis = jenis || (activeChip ? activeChip.textContent.trim() : 'Semua');

        document.querySelectorAll('.book-card').forEach(card => {
            const matchName = card.dataset.nama.toLowerCase().includes(search) || card.dataset.penerbit
                .toLowerCase().includes(search);
            const matchJenis = activeJenis === 'Semua' || activeJenis === 'semua' ?
                true : activeJenis === '✅ Tersedia' ?
                parseInt(card.dataset.stok) > 0 :
                card.dataset.jenis === activeJenis;
            const cardRating = parseFloat(card.dataset.rating) || 0;
            const matchRating = currentRating === 'semua' ? true : cardRating >= parseFloat(currentRating);
            card.style.display = (matchName && matchJenis && matchRating) ? '' : 'none';
        });
        updateCounts();
    }

    function updateCounts() {
        const vf = [...document.querySelectorAll('#gridFisik .book-card')].filter(c => c.style.display !== 'none')
            .length;
        const vd = [...document.querySelectorAll('#gridDigital .book-card')].filter(c => c.style.display !== 'none')
            .length;
        document.getElementById('countFisik').textContent = vf + ' buku';
        document.getElementById('countDigital').textContent = vd + ' buku';
    }
    document.getElementById('searchInput').addEventListener('input', () => applyFilters());

    /* ─── MODAL PINJAM ─── */
    function bukaModalPinjam() {
        if (!selectedBooks.length) return;
        renderChips();
        new bootstrap.Modal(document.getElementById('modalPinjam')).show();
    }

    function renderChips() {
        document.getElementById('selectedBooksList').innerHTML = selectedBooks.map(b => `
        <div class="book-chip" id="chip-${b.id}">
            ${b.sampul ? `<img src="${b.sampul}" alt="${b.nama}">` : '<div style="width:30px;height:38px;background:#e5e7eb;border-radius:4px;display:flex;align-items:center;justify-content:center">📖</div>'}
            <div><div style="font-weight:700;font-size:13px">${b.nama}</div><div style="font-size:11px;color:var(--muted)">${b.penerbit}</div></div>
            <button class="rm-chip" onclick="removeFromCart(${b.id})">✕</button>
        </div>`).join('');
    }

    function removeFromCart(id) {
        const card = document.getElementById('card-' + id);
        if (card) card.classList.remove('selected');
        selectedBooks = selectedBooks.filter(b => b.id !== id);
        updateCartUI();
        renderChips();
        if (!selectedBooks.length) bootstrap.Modal.getInstance(document.getElementById('modalPinjam'))?.hide();
    }

    /* ─── VALIDATE TOKEN ─── */
    function validateReferralTokenModal(input) {
        const token = input.value.trim().toUpperCase();
        input.value = token;
        if (token.length !== 6) {
            document.getElementById('referral_token_error').style.display = 'none';
            input.style.borderColor = '';
            return;
        }
        fetch(`{{ route('publik.validate-token') }}?token=${token}`)
            .then(r => r.json())
            .then(d => {
                const err = document.getElementById('referral_token_error');
                if (d.valid) {
                    err.style.display = 'none';
                    input.style.borderColor = 'var(--teal)';
                } else {
                    err.style.display = 'block';
                    input.style.borderColor = 'var(--red)';
                }
            }).catch(() => {
                document.getElementById('referral_token_error').style.display = 'block';
            });
    }

    /* ─── SUBMIT PINJAM ─── */
    function submitPinjam() {
        const v = id => document.getElementById(id).value.trim();
        const [nama, nim, jurusan, telepon, ref] = ['f_nama', 'f_nim', 'f_jurusan', 'f_telepon', 'f_referral_token'].map
            (v);
        const alert = document.getElementById('alertPinjam');
        if (!nama || !nim || !jurusan || !telepon || !ref) {
            alert.innerHTML =
                '<div style="background:#fee2e2;border-radius:10px;padding:12px;font-size:13px;color:#b91c1c;margin-bottom:12px"><i class="fas fa-exclamation-circle me-2"></i>Semua field wajib diisi!</div>';
            return;
        }
        if (ref.length !== 6) {
            alert.innerHTML =
                '<div style="background:#fee2e2;border-radius:10px;padding:12px;font-size:13px;color:#b91c1c;margin-bottom:12px"><i class="fas fa-exclamation-circle me-2"></i>Token referral harus 6 digit!</div>';
            return;
        }
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses…';
        fetch('{{ route("publik.pinjam") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nama,
                nim,
                jurusan,
                no_telepon: telepon,
                referral_token: ref,
                buku_ids: selectedBooks.map(b => b.id)
            })
        }).then(r => r.json()).then(res => {
            if (res.success) {
                document.getElementById('formState').style.display = 'none';
                document.getElementById('successState').style.display = 'block';
                document.getElementById('bookingChips').innerHTML = res.booking_ids.map(id =>
                    `<div class="booking-code">${id}</div>`).join('');
                selectedBooks.forEach(b => document.getElementById('card-' + b.id)?.classList.remove(
                    'selected'));
                selectedBooks = [];
                updateCartUI();
            } else {
                alert.innerHTML =
                    `<div style="background:#fee2e2;border-radius:10px;padding:12px;font-size:13px;color:#b91c1c;margin-bottom:12px">${res.message}</div>`;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
            }
        }).catch(() => {
            alert.innerHTML =
                '<div style="background:#fee2e2;border-radius:10px;padding:12px;font-size:13px;color:#b91c1c;margin-bottom:12px">Terjadi kesalahan jaringan.</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
        });
    }

    function resetForm() {
        document.getElementById('formState').style.display = 'block';
        document.getElementById('successState').style.display = 'none';
        ['f_nama', 'f_nim', 'f_jurusan', 'f_telepon', 'f_referral_token'].forEach(id => document.getElementById(id)
            .value = '');
        document.getElementById('alertPinjam').innerHTML = '';
        const btn = document.getElementById('btnSubmit');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
        bootstrap.Modal.getInstance(document.getElementById('modalPinjam'))?.hide();
    }

    /* ─── AUTO-FILL NIM ─── */
    let nimTimer;

    function fetchMahasiswaForModal(nim) {
        clearTimeout(nimTimer);
        if (nim.replace(/\D/g, '').length < 5) {
            resetMhsFields();
            return;
        }
        nimTimer = setTimeout(() => {
            fetch(`/pinjam/get-mahasiswa/${nim.trim().replace(/\D/g,'')}`)
                .then(r => r.json()).then(d => {
                    if (d.success) {
                        document.getElementById('f_nama').value = d.data.nama;
                        document.getElementById('f_jurusan').value = d.data.jurusan;
                        document.getElementById('f_telepon').value = d.data.no_telepon || '';
                        ['f_nama', 'f_jurusan', 'f_telepon'].forEach(id => document.getElementById(id)
                            .readOnly = true);
                    } else resetMhsFields();
                }).catch(resetMhsFields);
        }, 400);
    }

    function resetMhsFields() {
        ['f_nama', 'f_jurusan', 'f_telepon'].forEach(id => {
            document.getElementById(id).value = '';
            document.getElementById(id).readOnly = false;
        });
    }

    /* ─── TOAST ─── */
    function showToast(msg, type) {
        const t = document.createElement('div');
        t.style.cssText =
            `position:fixed;top:76px;right:20px;z-index:9999;background:${type==='warn'?'#fef3c7':'#dcfce7'};color:${type==='warn'?'#92400e':'#15803d'};border:1px solid ${type==='warn'?'#fde68a':'#86efac'};padding:11px 20px;border-radius:12px;font-size:13.5px;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.1);animation:slideIn .28s ease;`;
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }

    /* ─── CHAT ─── */
    (function() {
        const toggle = document.getElementById('chatToggle');
        const close = document.getElementById('chatClose');
        const box = document.getElementById('chatBox');
        const form = document.getElementById('chatForm');
        const input = document.getElementById('chatInput');
        const msgs = document.getElementById('chatMessages');
        const sessId = document.getElementById('chatSessionId');
        const lastId = document.getElementById('lastMessageId');
        let open = false,
            verified = false,
            awaitNim = false,
            pollTimer = null,
            adminConnected = false;
        const csrf = '{{ csrf_token() }}';

        function addMsg(sender, text) {
            const d = document.createElement('div');
            d.className = 'msg ' + sender;
            const time = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const esc = document.createElement('div');
            esc.textContent = text;
            const escapedHtml = esc.innerHTML.replace(/\n/g, '<br>');
            d.innerHTML = `<div class="msg-text">${escapedHtml}</div><div class="msg-time">${time}</div>`;
            msgs.appendChild(d);
            msgs.scrollTop = msgs.scrollHeight;
        }

        function showTyping() {
            const t = document.createElement('div');
            t.className = 'msg bot';
            t.id = 'typing';
            t.innerHTML = '<div class="typing"><span></span><span></span><span></span></div>';
            msgs.appendChild(t);
            msgs.scrollTop = msgs.scrollHeight;
        }

        function hideTyping() {
            document.getElementById('typing')?.remove();
        }

        function openChat() {
            open = true;
            box.classList.add('open');
            if (!msgs.children.length) {
                addMsg('bot', 'Halo! Selamat datang di SIPUSAKA Assistant. 👋');
                setTimeout(() => {
                    addMsg('bot', 'Untuk memulai, silakan masukkan NIM Anda.');
                    awaitNim = true;
                }, 500);
            }
            input.focus();
        }

        function closeChat() {
            open = false;
            box.classList.remove('open');
            stopPoll();
        }

        function verifyNim(nim) {
            showTyping();
            fetch('{{ route("chat.verify-nim") }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        nim
                    })
                })
                .then(r => r.json()).then(d => {
                    hideTyping();
                    if (d.success) {
                        verified = true;
                        awaitNim = false;
                        addMsg('bot', 'Terima kasih! Anda terdaftar sebagai ' + d.mahasiswa.nama + ' ✓');
                        setTimeout(() => {
                            addMsg('bot', 'Ada yang bisa saya bantu?');
                            startPoll();
                        }, 700);
                    } else {
                        addMsg('bot', '❌ ' + (d.message || 'NIM tidak terdaftar.'));
                        awaitNim = true;
                    }
                }).catch(() => {
                    hideTyping();
                    addMsg('bot', '❌ Terjadi kesalahan.');
                    awaitNim = true;
                });
        }

        function sendMsg(message) {
            addMsg('user', message);
            input.value = '';
            showTyping();
            fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        session_id: sessId.value || null,
                        message
                    })
                })
                .then(r => r.json()).then(d => {
                    hideTyping();
                    if (!d.session_id && d.message) {
                        addMsg('bot', '❌ ' + d.message);
                        return;
                    }
                    if (d.session_id) sessId.value = d.session_id;
                    if (d.is_connected_to_admin && !adminConnected) {
                        adminConnected = true;
                        addMsg('bot', '🟢 Anda terhubung dengan Admin.');
                    }
                    if (d.message) {
                        addMsg('bot', d.message);
                        // Update lastId to prevent duplicate messages from polling
                        if (d.message_id) {
                            lastId.value = d.message_id;
                        }
                    }
                }).catch(() => hideTyping());
        }

        function poll() {
            if (!sessId.value) return;
            fetch(`{{ route("chat.messages") }}?session_id=${encodeURIComponent(sessId.value)}&last_message_id=${lastId.value}`, {
                    credentials: 'same-origin'
                })
                .then(r => r.json()).then(d => {
                    (d.messages || []).forEach(m => {
                        // Bot messages sudah ditampilkan langsung dari respons sendMsg() —
                        // polling cukup tangani pesan admin saja, supaya tidak dobel.
                        if (m.sender_type === 'admin') addMsg(m.sender_type, m.message);
                        if (m.id > lastId.value) lastId.value = m.id;
                    });
                    if (d.is_connected_to_admin && !adminConnected) {
                        adminConnected = true;
                        addMsg('bot', '🟢 Anda terhubung dengan Admin.');
                    }
                    if (d.session_closed) {
                        stopPoll();
                        input.disabled = true;
                        addMsg('bot', 'Sesi chat telah ditutup oleh Admin.');
                    }
                }).catch(() => {});
        }

        function startPoll() {
            poll();
            pollTimer = setInterval(poll, 3000);
        }

        function stopPoll() {
            clearInterval(pollTimer);
            pollTimer = null;
        }

        toggle.addEventListener('click', openChat);
        close.addEventListener('click', closeChat);
        form.addEventListener('submit', e => {
            e.preventDefault();
            const msg = input.value.trim();
            if (!msg) return;
            if (awaitNim && !verified) {
                addMsg('user', msg);
                input.value = '';
                verifyNim(msg);
            } else if (verified) sendMsg(msg);
            else {
                addMsg('user', msg);
                input.value = '';
                addMsg('bot', 'Silakan masukkan NIM Anda terlebih dahulu.');
            }
        });
    })();
    (function() {
        const words = ['Favoritmu', 'Terpopuler', 'Terbaru', 'Pilihanmu'];
        let current = 0;

        function goTo(next) {
            if (next === current) return;
            const prev = current;
            current = next;
            const elPrev = document.getElementById('rw-' + prev);
            const elNext = document.getElementById('rw-' + next);
            elPrev.classList.remove('active');
            elPrev.classList.add('exit');
            elPrev.addEventListener('animationend', () => {
                elPrev.classList.remove('exit');
                elPrev.style.opacity = '0';
            }, {
                once: true
            });
            elNext.style.opacity = '';
            elNext.classList.add('active');
        }

        setInterval(() => goTo((current + 1) % words.length), 2800);
    })();
    </script>
</body>

</html>