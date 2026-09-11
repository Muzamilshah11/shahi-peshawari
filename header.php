<?php if (!isset($pageTitle)) $pageTitle = 'Shahi Peshawari'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($pageTitle) ?> | <?= SITE_NAME ?></title>
    <meta name="description" content="Premium handcrafted Peshawari Chappal - Shahi Peshawari">
    <meta name="theme-color" content="#0984e3">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --blue: #0984e3;
            --blue-dark: #0769b5;
            --blue-light: #e8f4fd;
            --white: #ffffff;
            --black: #1a1a2e;
            --grey-bg: #f4f6f8;
            --grey-border: #e0e0e0;
            --grey-text: #636e72;
            --green: #00b894;
            --red: #d63031;
            --gold: #d4a017;
            --radius: 12px;
            --radius-sm: 8px;
            --shadow: 0 2px 12px rgba(0,0,0,.08);
            --shadow-lg: 0 8px 30px rgba(0,0,0,.12);
            --nav-height: 64px;
            --transition: all .25s ease;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--grey-bg);
            color: var(--black);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            padding-top: var(--nav-height);
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }
        button { font-family: inherit; cursor: pointer; border: none; outline: none; }
        input, select, textarea { font-family: inherit; outline: none; }

        /* ═══════════════════════════════════════
           TOP NAVIGATION
           ═══════════════════════════════════════ */
        .top-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            height: var(--nav-height);
            background: rgba(255,255,255,.97);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,.06);
            display: flex; align-items: center;
            padding: 0 20px;
            gap: 16px;
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .top-nav.nav-hidden { transform: translateY(-100%); box-shadow: none; }
        .top-nav.nav-visible { transform: translateY(0); }
        .top-nav.scrolled { box-shadow: 0 2px 16px rgba(0,0,0,.1); }
        .nav-brand {
            font-size: 1.3rem; font-weight: 900; letter-spacing: -0.5px; flex-shrink: 0;
            text-decoration: none; display: flex; align-items: center;
        }
        .nav-brand .brand-shahi { color: var(--blue); }
        .nav-brand .brand-peshawari { color: var(--black); margin-left: 6px; }
        .mobile-brand { display: none; }
        .nav-search {
            flex: 1; max-width: 420px; position: relative;
        }
        .nav-search input {
            width: 100%; padding: 10px 16px 10px 40px;
            border: 2px solid var(--grey-border); border-radius: 50px;
            font-size: .85rem; background: var(--grey-bg);
            transition: var(--transition);
        }
        .nav-search input:focus {
            border-color: var(--blue); background: var(--white);
            box-shadow: 0 0 0 3px rgba(9,132,227,.1);
        }
        .nav-search .search-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: var(--grey-text); font-size: .85rem; pointer-events: none;
        }
        .nav-actions {
            display: flex; align-items: center; gap: 4px; flex-shrink: 0;
        }
        .nav-actions a {
            position: relative; width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-size: 1.1rem; color: var(--black);
            transition: var(--transition);
        }
        .nav-actions a:hover { background: var(--blue-light); color: var(--blue); }
        .cart-badge {
            position: absolute; top: 2px; right: 2px;
            background: var(--red); color: var(--white);
            font-size: .6rem; font-weight: 700;
            min-width: 18px; height: 18px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--white);
        }

        /* ═══════════════════════════════════════
           BOTTOM NAV (Mobile Only)
           ═══════════════════════════════════════ */
        .bottom-nav {
            display: none; position: fixed; bottom: 0; left: 0; right: 0;
            z-index: 1000; height: 64px;
            background: rgba(255,255,255,.97);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid rgba(0,0,0,.06);
            align-items: center; justify-content: space-around;
            padding: 0 8px;
        }
        .bottom-nav a {
            display: flex; flex-direction: column; align-items: center;
            gap: 2px; font-size: .62rem; font-weight: 500;
            color: var(--grey-text); transition: var(--transition);
            padding: 8px 14px; border-radius: var(--radius-sm);
            position: relative; text-decoration: none;
        }
        .bottom-nav a i { font-size: 1.15rem; }
        .bottom-nav a.active { color: var(--blue); }
        .bottom-nav .mobile-badge {
            position: absolute; top: 2px; right: 6px;
            background: var(--red); color: var(--white);
            font-size: .55rem; font-weight: 700;
            min-width: 16px; height: 16px; border-radius: 50%;
            display: none; align-items: center; justify-content: center;
        }

        /* ═══════════════════════════════════════
           CONTAINER
           ═══════════════════════════════════════ */
        .container { max-width: 1200px; margin: 0 auto; padding: 24px 20px; }

        /* ═══════════════════════════════════════
           HERO
           ═══════════════════════════════════════ */
        .hero {
            border-radius: var(--radius); padding: 48px 36px;
            color: var(--white); margin-bottom: 28px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; right: -50px; top: -50px;
            width: 220px; height: 220px;
            background: rgba(255,255,255,.07); border-radius: 50%;
        }
        .hero h1 { font-size: 2rem; font-weight: 800; margin-bottom: 10px; position: relative; z-index: 1; }
        .hero p { font-size: .95rem; opacity: .9; max-width: 500px; position: relative; z-index: 1; line-height: 1.7; }
        .hero-btn {
            display: inline-flex; align-items: center; gap: 8px;
            margin-top: 20px; padding: 12px 32px; background: var(--white);
            color: var(--blue); font-weight: 600; border-radius: 50px;
            transition: var(--transition); position: relative; z-index: 1;
        }
        .hero-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.2); }

        /* ═══════════════════════════════════════
           CATEGORIES
           ═══════════════════════════════════════ */
        .section-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 16px; }
        .categories-scroll {
            display: flex; gap: 10px; overflow-x: auto;
            padding-bottom: 12px; margin-bottom: 28px;
            scrollbar-width: none;
        }
        .categories-scroll::-webkit-scrollbar { display: none; }
        .cat-chip {
            flex-shrink: 0; padding: 10px 22px;
            background: var(--white); border: 2px solid var(--grey-border);
            border-radius: 50px; font-size: .85rem; font-weight: 500;
            cursor: pointer; transition: var(--transition); white-space: nowrap;
        }
        .cat-chip:hover { border-color: var(--blue); color: var(--blue); }
        .cat-chip.active {
            background: var(--blue); color: var(--white); border-color: var(--blue);
            box-shadow: 0 4px 14px rgba(9,132,227,.3);
        }

        /* ═══════════════════════════════════════
           PRODUCT GRID
           ═══════════════════════════════════════ */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: var(--white); border-radius: var(--radius);
            overflow: hidden; box-shadow: var(--shadow);
            transition: var(--transition);
        }
        .product-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .product-card .img-wrap {
            height: 220px; background: var(--grey-bg);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .product-card .img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: opacity .3s ease, transform .4s ease;
        }
        .product-card:hover .img-wrap img { transform: scale(1.06); }
        .product-card .img-wrap img.hover-swap { opacity: 0; }
        .product-card .card-body { padding: 16px; }
        .product-card .card-cat {
            font-size: .7rem; font-weight: 600; text-transform: uppercase;
            color: var(--blue); letter-spacing: .5px;
        }
        .product-card .card-title {
            font-size: .9rem; font-weight: 600; margin: 4px 0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .product-card .card-price {
            font-size: 1.05rem; font-weight: 700; color: var(--blue);
            display: flex; align-items: baseline; gap: 8px;
        }
        .product-card .card-price .old-price {
            font-size: .78rem; color: var(--grey-text);
            text-decoration: line-through; font-weight: 400;
        }
        .card-actions {
            display: none;
        }

        /* Heart / Quick Add Button */
        .heart-btn {
            position: absolute; top: 10px; right: 10px; z-index: 5;
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,.9); backdrop-filter: blur(4px);
            border: none; display: flex; align-items: center; justify-content: center;
            font-size: .95rem; color: var(--grey-text); cursor: pointer;
            transition: var(--transition); box-shadow: 0 2px 8px rgba(0,0,0,.1);
        }
        .heart-btn:hover { background: var(--red); color: var(--white); transform: scale(1.1); }
        .heart-btn.added { background: var(--red); color: var(--white); animation: heartPop .4s ease; }
        @keyframes heartPop {
            0% { transform: scale(1); }
            40% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }

        /* Buy Now Button on Card */
        .btn-buy-card {
            display: block; width: 100%; margin-top: 10px; padding: 10px;
            background: var(--green); color: var(--white);
            font-size: .82rem; font-weight: 600; text-align: center;
            border-radius: var(--radius-sm); border: none; cursor: pointer;
            transition: var(--transition);
        }
        .btn-buy-card:hover { background: #00a884; transform: translateY(-1px); }

        /* Modal Image Slider */
        .modal-slider {
            position: relative; width: 100%; aspect-ratio: 1; max-height: 320px;
            border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 16px;
            background: var(--grey-bg);
        }
        .modal-slider img {
            width: 100%; height: 100%; object-fit: cover;
            position: absolute; top: 0; left: 0;
            opacity: 0; transition: opacity .35s ease;
        }
        .modal-slider img.active { opacity: 1; }
        .slider-dots {
            position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);
            display: flex; gap: 6px; z-index: 5;
        }
        .slider-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(255,255,255,.5); border: none; cursor: pointer;
            transition: var(--transition);
        }
        .slider-dot.active { background: var(--white); transform: scale(1.2); }
        .slider-arrow {
            position: absolute; top: 50%; transform: translateY(-50%); z-index: 5;
            width: 32px; height: 32px; border-radius: 50%;
            background: rgba(255,255,255,.85); border: none;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; color: var(--black); cursor: pointer;
            transition: var(--transition); box-shadow: 0 2px 6px rgba(0,0,0,.15);
        }
        .slider-arrow:hover { background: var(--white); }
        .slider-arrow.prev { left: 8px; }
        .slider-arrow.next { right: 8px; }

        /* Category Sections */
        .category-section { margin-bottom: 32px; }
        .cat-section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .cat-section-header .section-title { margin-bottom: 0; }
        .view-all-link {
            font-size: .83rem; font-weight: 600; color: var(--blue);
            display: flex; align-items: center; gap: 6px;
            transition: var(--transition);
        }
        .view-all-link:hover { gap: 10px; }

        /* ═══════════════════════════════════════
           PRODUCT DETAIL PAGE
           ═══════════════════════════════════════ */
        .breadcrumb {
            display: flex; align-items: center; gap: 10px;
            font-size: .82rem; color: var(--grey-text);
            margin-bottom: 24px; flex-wrap: wrap;
        }
        .breadcrumb a { color: var(--grey-text); transition: var(--transition); }
        .breadcrumb a:hover { color: var(--blue); }
        .breadcrumb span { color: var(--black); font-weight: 500; }
        .breadcrumb i { font-size: .6rem; }

        .pd-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 40px;
            margin-bottom: 48px;
        }
        .pd-main-img {
            background: var(--white); border-radius: var(--radius);
            overflow: hidden; box-shadow: var(--shadow);
            aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
        }
        .pd-main-img img { width: 100%; height: 100%; object-fit: cover; }
        .pd-thumbs {
            display: flex; gap: 10px; margin-top: 12px;
        }
        .pd-thumb {
            width: 72px; height: 72px; border-radius: var(--radius-sm);
            overflow: hidden; border: 2px solid var(--grey-border);
            cursor: pointer; transition: var(--transition); padding: 0; background: var(--white);
        }
        .pd-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .pd-thumb.active, .pd-thumb:hover { border-color: var(--blue); }

        .pd-cat {
            font-size: .75rem; font-weight: 600; text-transform: uppercase;
            color: var(--blue); letter-spacing: .5px;
        }
        .pd-title { font-size: 1.6rem; font-weight: 700; margin: 8px 0 12px; }
        .pd-rating { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
        .stars { display: flex; gap: 2px; color: #f0a500; font-size: .9rem; }
        .rating-text { font-size: .82rem; color: var(--grey-text); }
        .pd-price {
            font-size: 1.8rem; font-weight: 800; color: var(--blue);
            display: flex; align-items: baseline; gap: 12px; margin-bottom: 20px;
        }
        .pd-old-price {
            font-size: 1rem; color: var(--grey-text);
            text-decoration: line-through; font-weight: 400;
        }
        .pd-discount {
            font-size: .78rem; font-weight: 600; color: var(--white);
            background: var(--red); padding: 3px 10px; border-radius: 50px;
        }
        .pd-desc { margin-bottom: 20px; }
        .pd-desc h3 { font-size: .95rem; font-weight: 600; margin-bottom: 8px; }
        .pd-desc p { font-size: .88rem; color: var(--grey-text); line-height: 1.7; }
        .pd-features {
            display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
            margin-bottom: 24px;
        }
        .pd-feature {
            font-size: .82rem; color: var(--grey-text);
            display: flex; align-items: center; gap: 8px;
        }
        .pd-feature i { color: var(--green); }

        .pd-qty-section {
            display: flex; align-items: center; gap: 16px;
            margin-bottom: 24px; flex-wrap: wrap;
        }
        .pd-qty-section h3 { font-size: .9rem; font-weight: 600; }
        .pd-qty-controls { display: flex; align-items: center; gap: 10px; }
        .pd-qty-controls .qty-btn {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--grey-bg); border: 1px solid var(--grey-border);
            font-size: .85rem; display: flex; align-items: center; justify-content: center;
            transition: var(--transition);
        }
        .pd-qty-controls .qty-btn:hover { background: var(--blue); color: var(--white); border-color: var(--blue); }
        .pd-qty-controls .qty-val { font-size: 1.1rem; font-weight: 700; min-width: 30px; text-align: center; }
        .pd-stock { font-size: .82rem; color: var(--green); font-weight: 500; }
        .pd-stock i { margin-right: 4px; }

        .pd-actions { display: flex; gap: 12px; }
        .pd-btn-cart, .pd-btn-buy, .pd-btn-confirm {
            flex: 1; padding: 14px; border: none; border-radius: var(--radius-sm);
            font-size: .9rem; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: var(--transition);
        }
        .pd-btn-cart { background: var(--blue); color: var(--white); }
        .pd-btn-cart:hover { background: var(--blue-dark); }
        .pd-btn-buy { background: var(--green); color: var(--white); }
        .pd-btn-buy:hover { background: #00a884; }
        .pd-btn-confirm { background: var(--blue); color: var(--white); width: 100%; margin-top: 8px; }
        .pd-btn-confirm:hover { background: var(--blue-dark); }
        .pd-btn-confirm:disabled { background: var(--grey-border); color: var(--grey-text); cursor: not-allowed; }

        .pd-similar { margin-top: 20px; }

        /* ═══════════════════════════════════════
           BUY MODAL
           ═══════════════════════════════════════ */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,.5); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-overlay.show { display: flex; }
        .modal-content {
            background: var(--white); border-radius: var(--radius);
            width: 100%; max-width: 560px; max-height: 90vh;
            overflow-y: auto; box-shadow: var(--shadow-lg);
        }
        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 24px; border-bottom: 1px solid var(--grey-border);
        }
        .modal-header h2 { font-size: 1.1rem; font-weight: 700; }
        .modal-header h2 i { color: var(--blue); margin-right: 8px; }
        .modal-close {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--grey-bg); display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: var(--grey-text); transition: var(--transition);
        }
        .modal-close:hover { background: var(--red); color: var(--white); }
        .modal-body { padding: 24px; }

        .modal-summary {
            background: var(--grey-bg); border-radius: var(--radius-sm);
            padding: 16px; margin-bottom: 20px;
        }
        .modal-product-info { display: flex; gap: 12px; align-items: center; margin-bottom: 12px; }
        .modal-thumb { width: 56px; height: 56px; border-radius: var(--radius-sm); object-fit: cover; }
        .modal-pname { font-size: .88rem; font-weight: 600; }
        .modal-pprice { font-size: .82rem; color: var(--grey-text); margin-top: 2px; }
        .modal-total {
            display: flex; justify-content: space-between;
            padding-top: 10px; border-top: 1px solid var(--grey-border);
            font-weight: 700; font-size: 1.05rem;
        }
        .modal-total span:last-child { color: var(--blue); }

        .payment-methods {
            display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
        }
        .pay-option { cursor: pointer; }
        .pay-option input { position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none; }
        .pay-box {
            padding: 12px; border: 2px solid var(--grey-border);
            border-radius: var(--radius-sm); text-align: center;
            transition: var(--transition); font-size: .82rem; font-weight: 500;
        }
        .pay-box i { display: block; font-size: 1.3rem; margin-bottom: 6px; color: var(--grey-text); }
        .pay-option.active .pay-box, .pay-option:hover .pay-box {
            border-color: var(--blue); background: var(--blue-light);
        }

        /* Size Selector */
        .size-selector { margin-bottom: 16px; }
        .size-selector label {
            display: block; font-size: .82rem; font-weight: 600;
            margin-bottom: 8px; color: var(--black);
        }
        .size-selector label .required { color: var(--red); }
        .size-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(72px, 1fr)); gap: 6px;
        }
        .size-option {
            position: relative; cursor: pointer;
        }
        .size-option input { display: none; }
        .size-box {
            padding: 10px 4px; border: 2px solid var(--grey-border);
            border-radius: var(--radius-sm); text-align: center;
            font-size: .78rem; font-weight: 500; transition: var(--transition);
            background: var(--white); line-height: 1.3;
        }
        .size-box .size-uk { display: block; font-weight: 700; color: var(--black); }
        .size-box .size-eu { display: block; font-size: .68rem; color: var(--grey-text); margin-top: 1px; }
        .size-option input:checked + .size-box {
            border-color: var(--blue); background: var(--blue-light); color: var(--blue);
        }
        .size-option input:checked + .size-box .size-uk { color: var(--blue); }
        .size-option:hover .size-box { border-color: var(--blue); }
        .size-error { font-size: .75rem; color: var(--red); margin-top: 4px; display: none; }
        /* ═══════════════════════════════════════
           ORDER SUCCESS PAGE
           ═══════════════════════════════════════ */
        .success-box {
            text-align: center; padding: 40px 20px; margin-bottom: 32px;
        }
        .success-icon { font-size: 4rem; color: var(--green); margin-bottom: 16px; }
        .success-box h1 { font-size: 1.6rem; font-weight: 700; margin-bottom: 8px; }
        .success-msg { font-size: .9rem; color: var(--grey-text); margin-bottom: 24px; }
        .order-id-display {
            display: inline-flex; align-items: center; gap: 12px;
            background: var(--white); border: 2px dashed var(--blue);
            border-radius: var(--radius); padding: 16px 24px;
        }
        .order-id-label { font-size: .78rem; color: var(--grey-text); text-transform: uppercase; letter-spacing: .5px; }
        .order-id-value { font-size: 1.4rem; font-weight: 800; color: var(--blue); letter-spacing: 1px; }
        .copy-btn {
            width: 36px; height: 36px; border-radius: 50%; background: var(--blue-light);
            border: none; color: var(--blue); cursor: pointer; transition: var(--transition);
        }
        .copy-btn:hover { background: var(--blue); color: var(--white); }

        /* Receipt */
        .receipt {
            background: var(--white); border-radius: var(--radius);
            box-shadow: var(--shadow); padding: 32px; margin-bottom: 32px;
            max-width: 600px; margin-left: auto; margin-right: auto;
            border: 1px solid var(--grey-border);
        }
        .receipt-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid var(--grey-border);
        }
        .receipt-brand { font-size: 1.4rem; font-weight: 800; color: var(--blue); }
        .receipt-brand span { color: var(--black); }
        .receipt-title { font-size: .85rem; font-weight: 600; color: var(--grey-text); text-transform: uppercase; letter-spacing: 1px; }
        .receipt-info, .receipt-customer {
            display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
            margin-bottom: 16px; font-size: .85rem;
        }
        .receipt-table {
            width: 100%; border-collapse: collapse; margin-bottom: 16px;
        }
        .receipt-table th {
            text-align: left; padding: 8px; font-size: .75rem;
            text-transform: uppercase; color: var(--grey-text);
            border-bottom: 2px solid var(--grey-border);
        }
        .receipt-table td { padding: 8px; font-size: .85rem; border-bottom: 1px solid var(--grey-border); }
        .receipt-totals { margin-top: 12px; }
        .receipt-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: .88rem; }
        .receipt-row.receipt-total { font-weight: 700; font-size: 1.05rem; border-top: 2px solid var(--grey-border); padding-top: 10px; margin-top: 6px; }
        .receipt-footer { text-align: center; margin-top: 20px; padding-top: 16px; border-top: 1px dashed var(--grey-border); font-size: .82rem; color: var(--grey-text); }

        /* Success Actions */
        .success-actions {
            display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;
            margin-bottom: 32px;
        }
        .sa-btn {
            padding: 12px 24px; border-radius: var(--radius-sm); font-size: .88rem;
            font-weight: 600; cursor: pointer; display: inline-flex; align-items: center;
            gap: 8px; transition: var(--transition); text-decoration: none;
        }
        .sa-primary { background: var(--blue); color: var(--white); border: none; }
        .sa-primary:hover { background: var(--blue-dark); }
        .sa-green { background: var(--green); color: var(--white); border: none; }
        .sa-green:hover { background: #00a884; }
        .sa-outline { background: transparent; border: 2px solid var(--grey-border); color: var(--black); }
        .sa-outline:hover { border-color: var(--blue); color: var(--blue); }

        /* ═══════════════════════════════════════
           TRACK ORDER PAGE
           ═══════════════════════════════════════ */
        .track-search {
            max-width: 500px; margin-bottom: 28px;
        }
        .track-tabs {
            display: flex; gap: 8px; margin-bottom: 12px;
        }
        .track-tab {
            flex: 1; padding: 10px; border: 2px solid var(--grey-border);
            border-radius: var(--radius-sm); background: var(--white);
            font-size: .85rem; font-weight: 500; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: var(--transition);
        }
        .track-tab:hover { border-color: var(--blue); color: var(--blue); }
        .track-tab.active { background: var(--blue); color: var(--white); border-color: var(--blue); }
        .track-form {
            display: flex; gap: 8px;
        }
        .track-form input {
            flex: 1; padding: 12px 14px; border: 2px solid var(--grey-border);
            border-radius: var(--radius-sm); font-size: .9rem;
        }
        .track-form input:focus { border-color: var(--blue); }
        .btn-track {
            padding: 12px 24px; background: var(--blue); color: var(--white);
            border: none; border-radius: var(--radius-sm); font-size: .88rem;
            font-weight: 600; cursor: pointer; display: flex; align-items: center;
            gap: 8px; transition: var(--transition);
        }
        .btn-track:hover { background: var(--blue-dark); }

        .pay-info-box {
            background: #fffbe6; border: 1px solid #ffe58f;
            border-radius: var(--radius); padding: 20px;
            font-size: .85rem; line-height: 1.6; margin-top: 12px;
            text-align: center;
        }
        .pay-info-box img {
            max-width: 100%; height: auto;
            box-shadow: 0 4px 16px rgba(0,0,0,.1);
        }
        .pay-info-box strong { display: block; margin-bottom: 6px; color: var(--black); }

        /* ═══════════════════════════════════════
           PRODUCT DETAIL RESPONSIVE
           ═══════════════════════════════════════ */
        @media (max-width: 768px) {
            .pd-grid { grid-template-columns: 1fr; gap: 24px; }
            .pd-title { font-size: 1.3rem; }
            .pd-price { font-size: 1.5rem; }
            .pd-features { grid-template-columns: 1fr; }
            .pd-actions { flex-direction: column; }
            .payment-methods { grid-template-columns: 1fr 1fr; }
            .modal-content { max-width: 100%; }
        }
        @media (max-width: 480px) {
            .pd-main-img { aspect-ratio: auto; max-height: 320px; }
            .pd-thumb { width: 56px; height: 56px; }
            .pd-title { font-size: 1.1rem; }
            .pd-price { font-size: 1.3rem; }
            .modal-body { padding: 16px; }
            .payment-methods { grid-template-columns: 1fr; }
        }

        /* Nav Tabs */
        .nav-tabs {
            display: flex; align-items: center; gap: 4px;
            overflow-x: auto; scrollbar-width: none;
            flex: 1; justify-content: center;
        }
        .nav-tabs::-webkit-scrollbar { display: none; }
        .nav-tab {
            flex-shrink: 0; padding: 8px 18px;
            font-size: .82rem; font-weight: 500;
            color: var(--grey-text); border-radius: 50px;
            transition: var(--transition); white-space: nowrap;
            text-decoration: none; cursor: pointer;
            background: none; border: none;
        }
        .nav-tab:hover { color: var(--blue); background: var(--blue-light); }
        .nav-tab.active { color: var(--blue); font-weight: 600; background: var(--blue-light); }

        /* ═══════════════════════════════════════
           CART
           ═══════════════════════════════════════ */
        .cart-item {
            display: flex; align-items: center; gap: 14px;
            background: var(--white); border-radius: var(--radius);
            padding: 16px; margin-bottom: 10px; box-shadow: var(--shadow);
        }
        .cart-item img { width: 75px; height: 75px; object-fit: cover; border-radius: var(--radius-sm); flex-shrink: 0; }
        .cart-item .item-info { flex: 1; min-width: 0; }
        .cart-item .item-name { font-weight: 600; font-size: .88rem; }
        .cart-item .item-price { color: var(--blue); font-weight: 700; font-size: .9rem; }
        .qty-controls { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .qty-btn {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--grey-bg); border: 1px solid var(--grey-border);
            font-size: .85rem; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            transition: var(--transition);
        }
        .qty-btn:hover { background: var(--blue); color: var(--white); border-color: var(--blue); }
        .qty-val { font-weight: 600; font-size: .9rem; min-width: 20px; text-align: center; }
        .btn-remove { background: none; color: var(--red); font-size: .95rem; padding: 4px; }
        .btn-remove:hover { transform: scale(1.15); }

        /* ═══════════════════════════════════════
           SUMMARY BOX
           ═══════════════════════════════════════ */
        .summary-box {
            background: var(--white); border-radius: var(--radius);
            padding: 22px; box-shadow: var(--shadow); position: sticky; top: 84px;
        }
        .summary-box h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 14px; }
        .summary-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: .88rem; }
        .summary-row.total {
            border-top: 2px solid var(--grey-border); margin-top: 8px;
            padding-top: 10px; font-weight: 700; font-size: 1.05rem;
        }
        .summary-row.total span:last-child { color: var(--blue); }
        .coupon-row { display: flex; gap: 8px; margin: 14px 0; }
        .coupon-row input {
            flex: 1; padding: 10px 12px; border: 2px solid var(--grey-border);
            border-radius: var(--radius-sm); font-size: .83rem;
        }
        .coupon-row input:focus { border-color: var(--blue); }
        .btn-coupon {
            padding: 10px 16px; background: var(--black); color: var(--white);
            border-radius: var(--radius-sm); font-size: .78rem; font-weight: 600;
        }
        .coupon-msg { font-size: .75rem; margin-bottom: 6px; }
        .coupon-msg.success { color: var(--green); }
        .coupon-msg.error { color: var(--red); }

        /* ═══════════════════════════════════════
           CHECKOUT FORM
           ═══════════════════════════════════════ */
        .checkout-form { margin-top: 24px; }
        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block; font-size: .78rem; font-weight: 600;
            margin-bottom: 5px; color: var(--grey-text);
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%; padding: 11px 12px; border: 2px solid var(--grey-border);
            border-radius: var(--radius-sm); font-size: .88rem;
            transition: var(--transition); background: var(--white);
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color: var(--blue); box-shadow: 0 0 0 3px rgba(9,132,227,.08);
        }
        .form-group textarea { resize: vertical; min-height: 70px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        /* ═══════════════════════════════════════
           MAP
           ═══════════════════════════════════════ */
        #map {
            width: 100%; height: 260px;
            border-radius: var(--radius); margin: 14px 0;
            border: 2px solid var(--grey-border);
        }

        /* ═══════════════════════════════════════
           BUTTONS
           ═══════════════════════════════════════ */
        .btn-primary {
            width: 100%; padding: 13px; background: var(--blue); color: var(--white);
            font-size: .95rem; font-weight: 600; border-radius: var(--radius-sm);
            transition: var(--transition);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-primary:hover { background: var(--blue-dark); }
        .btn-primary:disabled { background: var(--grey-border); color: var(--grey-text); cursor: not-allowed; }
        .btn-outline {
            padding: 10px 22px; border: 2px solid var(--blue); color: var(--blue);
            border-radius: var(--radius-sm); font-weight: 600; font-size: .83rem;
            background: transparent; transition: var(--transition);
        }
        .btn-outline:hover { background: var(--blue); color: var(--white); }

        /* ═══════════════════════════════════════
           ORDERS
           ═══════════════════════════════════════ */
        .order-card {
            background: var(--white); border-radius: var(--radius);
            padding: 20px; margin-bottom: 14px; box-shadow: var(--shadow);
        }
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .order-id { font-weight: 700; font-size: .88rem; }
        .order-date { font-size: .78rem; color: var(--grey-text); }
        .order-status { padding: 4px 12px; border-radius: 50px; font-size: .7rem; font-weight: 600; }
        .status-placed { background: #ffeaa7; color: #b7791f; }
        .status-processing { background: #dfe6e9; color: #636e72; }
        .status-shipped { background: #e8f4fd; color: #0984e3; }
        .status-delivered { background: #e6fff9; color: #00b894; }

        /* ═══════════════════════════════════════
           PROGRESS TRACKER
           ═══════════════════════════════════════ */
        .progress-tracker { display: flex; justify-content: space-between; position: relative; margin: 18px 0 10px; }
        .progress-tracker::before {
            content: ''; position: absolute; top: 18px; left: 30px; right: 30px;
            height: 3px; background: var(--grey-border);
        }
        .progress-step { display: flex; flex-direction: column; align-items: center; z-index: 1; flex: 1; }
        .step-circle {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--grey-bg); border: 3px solid var(--grey-border);
            display: flex; align-items: center; justify-content: center;
            font-size: .72rem; font-weight: 700; margin-bottom: 6px;
        }
        .step-circle.active { background: var(--blue); border-color: var(--blue); color: var(--white); }
        .step-circle.done { background: var(--green); border-color: var(--green); color: var(--white); }
        .step-label { font-size: .65rem; font-weight: 500; color: var(--grey-text); text-align: center; }
        .step-label.active { color: var(--blue); font-weight: 600; }
        .order-items { border-top: 1px solid var(--grey-border); padding-top: 12px; }
        .order-item-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: .83rem; }

        /* ═══════════════════════════════════════
           TOAST
           ═══════════════════════════════════════ */
        .toast {
            position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%);
            background: var(--black); color: var(--white);
            padding: 10px 24px; border-radius: 50px;
            font-size: .83rem; font-weight: 500;
            z-index: 9999; opacity: 0; transition: opacity .3s ease;
            pointer-events: none; white-space: nowrap;
        }
        .toast.show { opacity: 1; }

        /* ═══════════════════════════════════════
           EMPTY STATE
           ═══════════════════════════════════════ */
        .empty-state { text-align: center; padding: 50px 20px; }
        .empty-state i { font-size: 3rem; color: var(--grey-border); margin-bottom: 14px; }
        .empty-state h3 { font-size: 1.1rem; color: var(--grey-text); margin-bottom: 6px; }
        .empty-state p { font-size: .85rem; color: var(--grey-text); }

        /* ═══════════════════════════════════════
           USER MENU
           ═══════════════════════════════════════ */
        .user-menu { position: relative; display: inline-block; }
        .user-menu-btn { background: var(--blue); border: none; border-radius: 8px; padding: 8px 14px; display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--white); font-size: .8rem; font-weight: 600; transition: var(--transition); flex-shrink: 0; line-height: 1; }
        .user-menu-btn:hover { background: var(--blue-dark); }
        .user-dropdown { position: absolute; top: calc(100% + 8px); right: 0; background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow-lg); min-width: 220px; display: none; z-index: 100; overflow: hidden; }
        .user-dropdown.show { display: block; }
        .user-dropdown a, .user-dropdown button { display: flex; align-items: center; gap: 10px; padding: 12px 16px; font-size: .85rem; color: var(--black); transition: var(--transition); width: 100%; text-align: left; background: none; border: none; cursor: pointer; }
        .user-dropdown a:hover, .user-dropdown button:hover { background: var(--grey-bg); }
        .user-dropdown .signout-link { color: var(--red); border-top: 1px solid var(--grey-border); }
        .user-dropdown .ud-header { padding: 14px 16px; border-bottom: 1px solid var(--grey-border); background: var(--grey-bg); }
        .user-dropdown .ud-header img { width: 40px; height: 40px; border-radius: 50%; margin-bottom: 8px; }
        .user-dropdown .ud-header .ud-name { font-weight: 600; font-size: .9rem; }
        .user-dropdown .ud-header .ud-email { font-size: .78rem; color: var(--grey-text); }
        #loginBtn {
            color: var(--blue); font-size: 1.1rem;
            width: 36px; height: 36px; border-radius: 50%;
            background: transparent;
            border: 2px solid var(--blue);
            display: inline-flex; align-items: center; justify-content: center;
            transition: var(--transition); text-decoration: none; flex-shrink: 0;
        }
        #loginBtn:hover {
            background: var(--blue);
            color: var(--white);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(9,132,227,.3);
        }

        /* ═══════════════════════════════════════
           RESPONSIVE — TABLET
           ═══════════════════════════════════════ */
        @media (max-width: 768px) {
            .nav-search, .nav-tabs { display: none !important; }
            .nav-actions > a[href="track.php"],
            .nav-actions > a[href="cart.php"],
            #loginBtn, .user-menu-btn { display: none !important; }
            .nav-actions { gap: 8px; }
            .top-nav { justify-content: center; }
            .nav-brand { display: none !important; }
            .mobile-brand { display: block; position: absolute; left: 0; right: 0; text-align: center; font-size: 1.1rem; font-weight: 900; letter-spacing: -0.5px; pointer-events: none; }
            .mobile-brand .brand-shahi { color: var(--blue); }
            .mobile-brand .brand-peshawari { color: var(--black); margin-left: 4px; }
            .bottom-nav { display: flex; }
            body { padding-bottom: 64px; padding-top: var(--nav-height); }
            .hero { padding: 32px 20px; }
            .hero h1 { font-size: 1.4rem; }
            .hero p { font-size: .85rem; }
            .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .product-card .img-wrap { height: 150px; }
            .product-card .card-body { padding: 12px; }
            .product-card .card-title { font-size: .82rem; }
            .product-card .card-price { font-size: .92rem; }
            .card-actions { flex-direction: column; gap: 6px; }
            .btn-add-cart, .btn-buy-now { width: 100%; padding: 9px; font-size: .78rem; }
            .form-row { grid-template-columns: 1fr; }
            .cart-item { flex-wrap: wrap; gap: 10px; padding: 14px; }
            .cart-item img { width: 65px; height: 65px; }
            .summary-box { position: static; }
            #checkoutGrid { grid-template-columns: 1fr !important; }
            .container { padding: 16px 12px; }
            .order-header { flex-wrap: wrap; gap: 8px; }
            .cat-section-header { flex-wrap: wrap; gap: 8px; }
            .trust-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }

        /* ═══════════════════════════════════════
           RESPONSIVE — MOBILE
           ═══════════════════════════════════════ */
        @media (max-width: 480px) {
            .top-nav { height: 52px; }
            body { padding-top: 52px; }

            .product-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
            .product-card .img-wrap { height: 130px; }
            .product-card .card-body { padding: 10px; }
            .product-card .card-title { font-size: .75rem; }
            .product-card .card-price { font-size: .85rem; }
            .product-card .card-price .old-price { font-size: .7rem; }
            .card-actions { display: none; }
            .heart-btn { width: 32px; height: 32px; font-size: .85rem; top: 8px; right: 8px; }
            .cart-item { padding: 12px; gap: 8px; }
            .cart-item img { width: 55px; height: 55px; }
            .cart-item .item-name { font-size: .8rem; }
            .cart-item .item-price { font-size: .82rem; }
            .qty-btn { width: 28px; height: 28px; font-size: .8rem; }
            .qty-val { font-size: .82rem; }
            .container { padding: 12px 10px; }
            .hero { padding: 28px 16px; border-radius: var(--radius-sm); }
            .hero h1 { font-size: 1.25rem; }
            .hero p { font-size: .8rem; }
            .hero-btn { padding: 10px 24px; font-size: .85rem; }
            .section-title { font-size: 1.05rem; }
            .cat-chip { padding: 8px 16px; font-size: .78rem; }
            body { padding-top: var(--nav-height); }
            .trust-grid { grid-template-columns: 1fr 1fr !important; }
        }
    </style>
<!-- Firebase SDK (must load before page scripts) -->
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
<script>
const firebaseConfig = {
    apiKey:            "<?= FIREBASE_API_KEY ?>",
    authDomain:        "<?= FIREBASE_AUTH_DOMAIN ?>",
    databaseURL:       "<?= FIREBASE_DATABASE_URL ?>",
    projectId:         "<?= FIREBASE_PROJECT_ID ?>",
    storageBucket:     "<?= FIREBASE_STORAGE_BUCKET ?>",
    messagingSenderId: "<?= FIREBASE_MESSAGING_SENDER_ID ?>",
    appId:             "<?= FIREBASE_APP_ID ?>"
};
firebase.initializeApp(firebaseConfig);
const db = firebase.database();
</script>
</head>
<body>

<!-- ═══════════════════════════════════════
     TOP NAVIGATION
     ═══════════════════════════════════════ -->
<nav class="top-nav">
    <a href="index.php" class="nav-brand"><span class="brand-shahi">SHAHI</span><span class="brand-peshawari">PESHAWARI</span></a>
    <div class="mobile-brand"><span class="brand-shahi">SHAHI</span><span class="brand-peshawari">PESHAWARI</span></div>
    <div class="nav-tabs">
        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        $isHome      = ($currentPage === 'index.php');
        $isShop      = ($currentPage === 'shop.php');
        $isPremium   = ($currentPage === 'premium.php');
        $isNewDesign = ($currentPage === 'new_design.php');
        $isCasual    = ($currentPage === 'casual.php');
        ?>
        <a href="index.php"        class="nav-tab <?= $isHome      ? 'active' : '' ?>">Home</a>
        <a href="shop.php"         class="nav-tab <?= $isShop      ? 'active' : '' ?>">Shop</a>
        <a href="premium.php"      class="nav-tab <?= $isPremium   ? 'active' : '' ?>">Premium</a>
        <a href="new_design.php"   class="nav-tab <?= $isNewDesign ? 'active' : '' ?>">New Design</a>
        <a href="casual.php"       class="nav-tab <?= $isCasual    ? 'active' : '' ?>">Casual</a>
    </div>
    <div class="nav-search">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="globalSearch" placeholder="Search Peshawari Chappal...">
    </div>
    <div class="nav-actions" style="display:flex;align-items:center;gap:12px;flex-shrink:0">
        <a href="track.php" title="Track Order" style="color:var(--black);font-size:1.05rem;padding:6px;border-radius:8px;transition:var(--transition)"><i class="fas fa-truck"></i></a>
        <a href="cart.php" title="Cart" style="color:var(--black);font-size:1.05rem;padding:6px;border-radius:8px;transition:var(--transition);position:relative">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-badge" id="cartBadge">0</span>
        </a>
        <div class="user-menu" id="userMenu">
            <button class="user-menu-btn" id="userMenuBtn" onclick="toggleUserMenu()" style="display:none">
                <i class="fas fa-user-circle"></i> <span id="udNameShort">User</span>
            </button>
            <div class="user-dropdown" id="userDropdown">
                <div style="padding:12px 16px;border-bottom:1px solid var(--grey-border)">
                    <div style="font-weight:600;font-size:.85rem" id="udName">Guest</div>
                    <div style="font-size:.75rem;color:var(--grey-text)" id="udEmail"></div>
                </div>
                <a href="orders.php"><i class="fas fa-box"></i> My Orders</a>
                <a href="track.php"><i class="fas fa-truck"></i> Track Order</a>
                <button class="signout-link" onclick="doLogout()"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
            </div>
        </div>
        <a href="auth.php" id="loginBtn" title="Login"><i class="fas fa-user-circle"></i></a>
    </div>
</nav>

<!-- ═══════════════════════════════════════
     BOTTOM NAVIGATION (Mobile Only)
     ═══════════════════════════════════════ -->
<div class="bottom-nav">
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
        <i class="fas fa-home"></i> Home
    </a>
    <a href="cart.php" class="<?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>">
        <i class="fas fa-shopping-cart"></i> Cart
        <span class="mobile-badge" id="mobileCartBadge">0</span>
    </a>
    <a href="track.php" class="<?= basename($_SERVER['PHP_SELF']) == 'track.php' ? 'active' : '' ?>">
        <i class="fas fa-truck"></i> Track
    </a>
    <a href="auth.php" id="mobileLoginBtn" class="<?= basename($_SERVER['PHP_SELF']) == 'auth.php' ? 'active' : '' ?>">
        <i class="fas fa-user"></i> Login
    </a>
    <a href="#" id="mobileUserBtn" style="display:none" onclick="document.getElementById('userDropdown').classList.toggle('show'); return false;">
        <i class="fas fa-user-circle"></i> <span id="mobileUserName">User</span>
    </a>
</div>

<!-- ═══════════════════════════════════════
     MOBILE HEADER SCROLL BEHAVIOR
     ═══════════════════════════════════════ -->
<script>
(function() {
    const nav = document.querySelector('.top-nav');
    if (!nav) return;
    let lastY = 0, ticking = false;

    function onScroll() {
        const y = window.pageYOffset || document.documentElement.scrollTop;
        const isMobile = window.innerWidth <= 768;
        if (!isMobile) { nav.classList.remove('nav-hidden','nav-visible','scrolled'); return; }

        if (y > 60) nav.classList.add('scrolled'); else nav.classList.remove('scrolled');

        if (y > lastY && y > 80) {
            nav.classList.add('nav-hidden');
            nav.classList.remove('nav-visible');
        } else if (y < lastY) {
            nav.classList.remove('nav-hidden');
            nav.classList.add('nav-visible');
        }
        lastY = y;
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) { requestAnimationFrame(onScroll); ticking = true; }
    }, { passive: true });
})();
</script>

<!-- ═══════════════════════════════════════
     USER AUTH STATE + MENU TOGGLE
     ═══════════════════════════════════════ -->
<script>
function toggleUserMenu() {
    var dd = document.getElementById('userDropdown');
    dd.classList.toggle('show');
}
document.addEventListener('click', function(e) {
    var menu = document.getElementById('userMenu');
    if (menu && !menu.contains(e.target)) {
        document.getElementById('userDropdown').classList.remove('show');
    }
});
function doLogout() {
    firebase.auth().signOut().then(function() {
        localStorage.removeItem('shahi_user');
        window.location.href = 'index.php';
    });
}
firebase.auth().onAuthStateChanged(function(user) {
    var loginBtn = document.getElementById('loginBtn');
    var userMenuBtn = document.getElementById('userMenuBtn');
    var mobileLoginBtn = document.getElementById('mobileLoginBtn');
    var mobileUserBtn = document.getElementById('mobileUserBtn');
    if (user) {
        var displayName = user.displayName || user.email.split('@')[0];
        if (loginBtn) loginBtn.style.display = 'none';
        if (userMenuBtn) { userMenuBtn.style.display = 'flex'; }
        document.getElementById('udName').textContent = displayName;
        document.getElementById('udNameShort').textContent = displayName;
        document.getElementById('udEmail').textContent = user.email || '';
        if (mobileLoginBtn) mobileLoginBtn.style.display = 'none';
        if (mobileUserBtn) { mobileUserBtn.style.display = ''; document.getElementById('mobileUserName').textContent = displayName; }
        localStorage.setItem('shahi_user', JSON.stringify({
            uid: user.uid, name: displayName,
            email: user.email || '', photo: user.photoURL || ''
        }));
    } else {
        if (loginBtn) loginBtn.style.display = '';
        if (userMenuBtn) userMenuBtn.style.display = 'none';
        if (mobileLoginBtn) mobileLoginBtn.style.display = '';
        if (mobileUserBtn) mobileUserBtn.style.display = 'none';
        localStorage.removeItem('shahi_user');
    }
});
</script>
