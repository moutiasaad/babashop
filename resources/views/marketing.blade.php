<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index,follow">
    <title>ShaiebExpo — L'artisanat tunisien, livré chez vous</title>

    <meta name="description" content="ShaiebExpo rassemble les artisans et marques tunisiennes sur une seule application. Bijoux, décoration, cosmétique, maroquinerie — livrés dans les 24 gouvernorats, paiement à la livraison.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="ShaiebExpo — L'artisanat tunisien, livré chez vous">
    <meta property="og:description" content="Découvrez la sélection curated d'artisans et de marques tunisiennes. Livraison dans les 24 gouvernorats. Paiement à la livraison.">
    <meta property="og:site_name" content="ShaiebExpo">
    <meta property="og:url" content="https://babashop.store">
    <link rel="canonical" href="https://babashop.store">

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700;9..144,900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-canvas: #FAF6F0;
            --bg-card: #FFFFFF;
            --bg-subtle: #F1EBE1;
            --ink-primary: #1F1A17;
            --ink-muted: #8B7355;
            --accent: #B4442A;
            --accent-hover: #95321F;
            --accent-soft: #F3DDD5;
            --border: #E8DECF;
            --on-accent: #FDFAF5;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-canvas);
            color: var(--ink-primary);
            line-height: 1.55;
            font-size: 16px;
        }
        a { color: inherit; text-decoration: none; }

        /* ── Top nav ── */
        nav {
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .brand {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 700;
            font-size: 24px;
            color: var(--accent);
            letter-spacing: -0.3px;
        }
        .brand-dot { color: var(--ink-primary); }
        .nav-links {
            display: flex;
            gap: 28px;
            font-size: 14px;
            font-weight: 500;
            color: var(--ink-muted);
        }
        .nav-links a:hover { color: var(--accent); }

        /* ── Hero ── */
        .hero {
            max-width: 1000px;
            margin: 0 auto;
            padding: 48px 32px 96px;
            text-align: center;
        }
        .kicker {
            display: inline-block;
            background: var(--accent-soft);
            color: var(--accent);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        h1 {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 900;
            font-size: clamp(40px, 8vw, 72px);
            line-height: 1.05;
            letter-spacing: -2px;
            color: var(--ink-primary);
            margin-bottom: 24px;
        }
        h1 em {
            font-style: italic;
            color: var(--accent);
            font-weight: 700;
        }
        .lede {
            font-size: clamp(16px, 2.2vw, 20px);
            color: var(--ink-muted);
            max-width: 620px;
            margin: 0 auto 40px;
            line-height: 1.5;
        }

        /* ── Download buttons ── */
        .stores {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .store-btn {
            background: var(--ink-primary);
            color: var(--on-accent);
            padding: 14px 26px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 500;
            transition: transform 0.15s, background 0.15s;
        }
        .store-btn:hover { transform: translateY(-2px); background: var(--accent); }
        .store-btn svg { width: 26px; height: 26px; flex-shrink: 0; }
        .store-btn-sub { font-size: 10px; opacity: 0.8; display: block; letter-spacing: 0.5px; }
        .store-btn-main { font-size: 16px; font-weight: 600; display: block; }

        /* ── Section ── */
        section {
            max-width: 1100px;
            margin: 0 auto;
            padding: 80px 32px;
        }
        .section-kicker {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 12px;
            text-align: center;
        }
        h2 {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 700;
            font-size: clamp(28px, 5vw, 44px);
            line-height: 1.15;
            letter-spacing: -1px;
            color: var(--ink-primary);
            text-align: center;
            margin-bottom: 48px;
        }

        /* ── Features grid ── */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }
        .feature {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px 24px;
        }
        .feature-icon {
            width: 48px;
            height: 48px;
            background: var(--accent-soft);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .feature h3 {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 700;
            font-size: 20px;
            color: var(--ink-primary);
            margin-bottom: 10px;
            letter-spacing: -0.3px;
        }
        .feature p {
            font-size: 14px;
            color: var(--ink-muted);
            line-height: 1.55;
        }

        /* ── How it works ── */
        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
        }
        .step { text-align: center; padding: 0 12px; }
        .step-num {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 900;
            font-size: 56px;
            color: var(--accent);
            line-height: 1;
            margin-bottom: 12px;
        }
        .step h3 {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 700;
            font-size: 18px;
            color: var(--ink-primary);
            margin-bottom: 8px;
        }
        .step p { font-size: 14px; color: var(--ink-muted); }

        /* ── Callout ── */
        .callout {
            background: var(--accent);
            color: var(--on-accent);
            border-radius: 24px;
            padding: 56px 40px;
            text-align: center;
            margin: 32px auto;
            max-width: 1000px;
        }
        .callout h2 { color: var(--on-accent); margin-bottom: 20px; }
        .callout p {
            font-size: 17px;
            opacity: 0.92;
            max-width: 560px;
            margin: 0 auto 32px;
        }
        .callout .store-btn { background: var(--on-accent); color: var(--accent); }
        .callout .store-btn:hover { background: var(--ink-primary); color: var(--on-accent); }

        /* ── Footer ── */
        footer {
            background: var(--ink-primary);
            color: var(--on-accent);
            padding: 48px 32px 32px;
            margin-top: 40px;
        }
        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding-bottom: 32px;
            border-bottom: 1px solid rgba(253, 250, 245, 0.1);
        }
        .footer-brand { font-family: 'Fraunces', Georgia, serif; font-weight: 700; font-size: 22px; color: var(--accent-soft); margin-bottom: 12px; }
        .footer-brand .brand-dot { color: var(--on-accent); }
        .footer-tagline { font-size: 13px; opacity: 0.7; line-height: 1.6; max-width: 320px; }
        .footer-links h4 {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--accent-soft);
            margin-bottom: 16px;
        }
        .footer-links ul { list-style: none; }
        .footer-links li { margin-bottom: 10px; font-size: 14px; opacity: 0.85; }
        .footer-links li a:hover { color: var(--accent-soft); }
        .footer-bottom {
            max-width: 1100px;
            margin: 24px auto 0;
            font-size: 12px;
            opacity: 0.5;
            text-align: center;
        }

        @media (max-width: 640px) {
            .footer-inner { grid-template-columns: 1fr; }
            section { padding: 56px 24px; }
            .hero { padding: 24px 24px 64px; }
            .callout { padding: 40px 24px; border-radius: 20px; }
        }
    </style>
</head>
<body>

<nav>
    <a href="/" class="brand">ShaiebExpo</a>
    <div class="nav-links">
        <a href="#features">Fonctionnalités</a>
        <a href="#how">Comment ça marche</a>
        <a href="/support">Support</a>
    </div>
</nav>

<div class="hero">
    <span class="kicker">Marketplace tunisien</span>
    <h1>L'artisanat tunisien,<br><em>livré chez vous.</em></h1>
    <p class="lede">
        Bijoux, cosmétique naturelle, décoration, maroquinerie — une sélection resserrée
        d'artisans et de marques locales, livrés partout en Tunisie. Paiement à la livraison,
        sans mot de passe à créer.
    </p>
    <div class="stores">
        <a href="#" class="store-btn" aria-label="Télécharger sur l'App Store">
            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
            </svg>
            <span>
                <span class="store-btn-sub">Télécharger sur</span>
                <span class="store-btn-main">App Store</span>
            </span>
        </a>
        <a href="#" class="store-btn" aria-label="Disponible sur Google Play">
            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.6 2.4c-.3.3-.5.7-.5 1.2v16.8c0 .5.2.9.5 1.2l9.4-9.6-9.4-9.6zm10.8 8.4L5.8 2.2 16.4 8.4l-2 2.4zm2.6 2.6l3.6 2.1c.8.5.8 1.6 0 2.1l-3.9 2.3-2.4-2.4 2.7-4.1zM5.8 21.8l8.6-8.6 2 2.4-10.6 6.2z"/>
            </svg>
            <span>
                <span class="store-btn-sub">Disponible sur</span>
                <span class="store-btn-main">Google Play</span>
            </span>
        </a>
    </div>
</div>

<section id="features">
    <div class="section-kicker">Ce que ShaiebExpo apporte</div>
    <h2>Pensé pour la Tunisie,<br>fait pour vous.</h2>
    <div class="features">
        <div class="feature">
            <div class="feature-icon">✦</div>
            <h3>Sélection curated</h3>
            <p>Une centaine de produits soigneusement choisis auprès d'artisans et de petites marques tunisiennes — pas de bazar sans fin.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">◈</div>
            <h3>Livraison 24 gouvernorats</h3>
            <p>Délais et frais affichés avant validation. Livraison par transporteur agréé, du Cap Bon au Sud.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">◉</div>
            <h3>Paiement à la livraison</h3>
            <p>Réglez en espèces au livreur, en dinar tunisien. Aucune carte bancaire n'est requise.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">✉</div>
            <h3>Sans mot de passe</h3>
            <p>Connexion par code à usage unique reçu par e-mail. Rien à retenir, rien à réinitialiser.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">☰</div>
            <h3>Mode invité</h3>
            <p>Parcourez le catalogue et remplissez votre panier sans créer de compte. On ne vous demande rien.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">◍</div>
            <h3>Suivi en temps réel</h3>
            <p>De la préparation à la remise en main propre — chaque étape est visible dans votre onglet Commandes.</p>
        </div>
    </div>
</section>

<section id="how">
    <div class="section-kicker">Trois étapes</div>
    <h2>De l'idée à la porte.</h2>
    <div class="steps">
        <div class="step">
            <div class="step-num">01</div>
            <h3>Parcourez</h3>
            <p>Découvrez les bijoux, la déco, la cosmétique ou la maroquinerie sélectionnés du moment.</p>
        </div>
        <div class="step">
            <div class="step-num">02</div>
            <h3>Commandez</h3>
            <p>Ajoutez au panier, choisissez votre gouvernorat, entrez votre adresse — c'est tout.</p>
        </div>
        <div class="step">
            <div class="step-num">03</div>
            <h3>Recevez</h3>
            <p>Le livreur vous appelle. Vous inspectez, vous payez en espèces, vous profitez.</p>
        </div>
    </div>
</section>

<section>
    <div class="callout">
        <h2>Prêt à essayer ?</h2>
        <p>Téléchargez ShaiebExpo gratuitement et commandez votre premier produit local en quelques minutes.</p>
        <div class="stores">
            <a href="#" class="store-btn" aria-label="App Store">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                </svg>
                <span>
                    <span class="store-btn-sub">Télécharger sur</span>
                    <span class="store-btn-main">App Store</span>
                </span>
            </a>
            <a href="#" class="store-btn" aria-label="Google Play">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.6 2.4c-.3.3-.5.7-.5 1.2v16.8c0 .5.2.9.5 1.2l9.4-9.6-9.4-9.6z"/>
                </svg>
                <span>
                    <span class="store-btn-sub">Disponible sur</span>
                    <span class="store-btn-main">Google Play</span>
                </span>
            </a>
        </div>
    </div>
</section>

<footer>
    <div class="footer-inner">
        <div>
            <div class="footer-brand">ShaiebExpo</div>
            <p class="footer-tagline">
                Marketplace pour l'artisanat et les marques tunisiennes.
                Livraison dans les 24 gouvernorats. Paiement à la livraison.
            </p>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="footer-links">
                <h4>Application</h4>
                <ul>
                    <li><a href="#features">Fonctionnalités</a></li>
                    <li><a href="#how">Comment ça marche</a></li>
                    <li><a href="/support">Support</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Légal</h4>
                <ul>
                    <li><a href="/privacy-policy">Confidentialité</a></li>
                    <li><a href="/account-deletion">Suppression de compte</a></li>
                    <li><a href="mailto:contact@babashop.store">contact@babashop.store</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">© 2026 ShaiebExpo — Tous droits réservés.</div>
</footer>

</body>
</html>
