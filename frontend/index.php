<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SkillStreet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; }

        /* NAV */
        .navbar { background-color: #2E4057; padding: 1rem 2rem; }
        .navbar-brand { color: #fff !important; font-weight: 700; font-size: 1.4rem; letter-spacing: 0.5px; }
        .nav-link { color: #cdd8e3 !important; font-size: 0.95rem; }
        .nav-link:hover { color: #048A81 !important; }
        .btn-nav { background: #048A81; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-size: 0.9rem; }
        .btn-nav:hover { background: #036b65; color: #fff; }
        .btn-nav-outline { border: 1.5px solid #fff; color: #fff !important; padding: 7px 20px; border-radius: 6px; font-size: 0.9rem; }
        .btn-nav-outline:hover { background: rgba(255,255,255,0.1); }

/* HERO */
.hero {
    position: relative;
    min-height: 88vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: #ffffff;
}
.hero-image {
    position: absolute;
    right: 0; top: 0;
    width: 62%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.hero-overlay {
    position: absolute;
    right: 0; top: 0;
    width: 62%;
    height: 100%;
    background: linear-gradient(to right, #ffffff 0%, #ffffff 5%, transparent 55%);
}
.hero-content {
    position: relative;
    z-index: 2;
    padding: 0 5%;
    max-width: 52%;
}
.hero-eyebrow {
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    color: #048A81;
    text-transform: lowercase;
    margin-bottom: 1rem;
}
.hero-title {
    font-size: clamp(2.4rem, 4vw, 3.6rem);
    font-weight: 800;
    color: #2E4057;
    line-height: 1.15;
    margin-bottom: 1.4rem;
    text-transform: lowercase;
}
.hero-title span { color: #2E4057; }
.hero-sub {
    font-size: 1.05rem;
    color: #4a5568;
    line-height: 1.7;
    margin-bottom: 2.2rem;
    max-width: 420px;
}
.hero-buttons { display: flex; gap: 14px; flex-wrap: wrap; }
.btn-primary-hero {
    background: #048A81;
    color: #fff;
    border: none;
    padding: 14px 32px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
    text-transform: lowercase;
}
.btn-primary-hero:hover { background: #036b65; color: #fff; }
.btn-outline-hero {
    background: transparent;
    color: #2E4057;
    border: 1.5px solid #2E4057;
    padding: 13px 32px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    text-transform: lowercase;
}
.btn-outline-hero:hover { border-color: #048A81; color: #048A81; background: transparent; }
        /* STATS BAR */
        .stats-bar {
            background: #2E4057;
            padding: 1.2rem 0;
        }
        .stat-item { text-align: center; color: #fff; }
        .stat-number { font-size: 1.6rem; font-weight: 800; color: #048A81; }
        .stat-label { font-size: 0.8rem; color: #a8bbc8; letter-spacing: 0.05em; }

        /* FEATURES */
        .features { padding: 80px 0; background: #f8f9fa; }
        .section-label {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            color: #048A81;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: #2E4057;
            margin-bottom: 0.5rem;
        }
        .section-sub { color: #6c757d; font-size: 1rem; margin-bottom: 3rem; }

        .feature-card {
            background: #fff;
            border-radius: 14px;
            padding: 2.2rem 1.8rem;
            height: 100%;
            border: 1px solid #e8edf2;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.09); }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 4px;
            height: 100%;
            background: #048A81;
            border-radius: 4px 0 0 4px;
        }
        .feature-icon-wrap {
            width: 52px; height: 52px;
            background: #EAF6F5;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
        }
        .feature-icon-wrap svg { width: 26px; height: 26px; stroke: #048A81; fill: none; stroke-width: 2; }
        .feature-card h5 { font-weight: 700; color: #2E4057; font-size: 1.1rem; margin-bottom: 0.6rem; }
        .feature-card p { color: #6c757d; font-size: 0.93rem; line-height: 1.65; margin: 0; }

        /* FOOTER */
        .footer { background: #2E4057; color: #a8bbc8; text-align: center; padding: 1.5rem; font-size: 0.88rem; }

        @media (max-width: 768px) {
            .hero-image, .hero-overlay { width: 100%; }
            .hero-overlay { background: rgba(26,42,58,0.82); }
            .hero-content { max-width: 100%; padding: 3rem 1.5rem; }
            .hero { min-height: 100vh; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="index.php">SkillStreet</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"
                style="border-color:rgba(255,255,255,0.3)">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="sessions.php">Browse Sessions</a></li>
                <li class="nav-item"><a class="nav-link" href="community.php">Community</a></li>
                <li class="nav-item"><a class="nav-link btn-nav-outline ms-2" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link btn-nav ms-1" href="register.php">Get Started</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <img src="/skillstreet/assets/hero.png" alt="SkillStreet" class="hero-image">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <p class="hero-eyebrow">SkillStreet</p>
        <h1 class="hero-title">discover skills in<br>your <span>neighborhood</span></h1>
        <p class="hero-sub">connect with people around you. learn from neighbors who know their craft. teach what you love and earn doing it.</p>
        <div class="hero-buttons">
            <a href="register.php" class="btn-primary-hero">register</a>
            <a href="login.php" class="btn-outline-hero">login</a>
        </div>
    </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="container">
        <div class="row g-3">
            <div class="col-4 stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Active Learners</div>
            </div>
            <div class="col-4 stat-item">
                <div class="stat-number">120+</div>
                <div class="stat-label">Local Teachers</div>
            </div>
            <div class="col-4 stat-item">
                <div class="stat-number">30+</div>
                <div class="stat-label">Skills Available</div>
            </div>
        </div>
    </div>
</div>

<!-- FEATURES -->
<!-- FEATURES -->
<section class="features">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <!-- CHANGE THIS PATH TO YOUR IMAGE -->
                <img src="/skillstreet/assets/drawings.jpg" alt="SkillStreet Community"
                     style="width:100%;border-radius:16px;object-fit:cover;">
            </div>
            <div class="col-md-6">
                <p class="section-label">why skillstreet</p>
                <h2 class="section-title" style="font-size:1.8rem;">everything you need to learn locally</h2>
                <p class="section-sub">a platform built around community</p>

                <div class="mb-4">
                    <h6 style="color:#2E4057;font-weight:700;margin-bottom:0.3rem;">learn new skills</h6>
                    <p style="color:#6c757d;font-size:0.93rem;line-height:1.65;margin:0;">browse sessions taught by people in your neighborhood across cooking, technology, arts, fitness, languages, and more.</p>
                </div>

                <div class="mb-4">
                    <h6 style="color:#2E4057;font-weight:700;margin-bottom:0.3rem;">teach what you know</h6>
                    <p style="color:#6c757d;font-size:0.93rem;line-height:1.65;margin:0;">create sessions, set your own schedule and pricing, and share your expertise with learners right in your community.</p>
                </div>

                <div class="mb-4">
                    <h6 style="color:#2E4057;font-weight:700;margin-bottom:0.3rem;">build community</h6>
                    <p style="color:#6c757d;font-size:0.93rem;line-height:1.65;margin:0;">post to the neighborhood feed, propose skill swaps, request help, and grow your local network</p>
                </div>

                <a href="register.php" class="btn-primary-hero" style="display:inline-block;margin-top:0.5rem;">get started</a>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<div class="footer">
    &copy; 2026 SkillStreet &mdash; Connecting neighborhoods through knowledge.
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>