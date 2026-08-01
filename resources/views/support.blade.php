<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Support — Babashop</title>

    <meta name="description" content="Centre d'aide Babashop : FAQ (commandes, livraison, paiement, retours), contact e-mail et formulaire de support pour la Tunisie.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Support — Babashop">
    <meta property="og:site_name" content="Babashop">
    <link rel="canonical" href="https://babashop.store/support">

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

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
            --success: #2F7A4F;
            --danger: #B33636;
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
        a { color: var(--accent); text-decoration: none; font-weight: 500; }
        a:hover { text-decoration: underline; }

        nav {
            padding: 24px 32px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .brand {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 700;
            font-size: 22px;
            color: var(--accent);
            letter-spacing: -0.3px;
        }
        .brand-dot { color: var(--ink-primary); }

        .wrap {
            max-width: 780px;
            margin: 0 auto;
            padding: 24px 24px 96px;
        }
        .kicker {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: 12px;
        }
        h1 {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 700;
            font-size: clamp(32px, 6vw, 44px);
            line-height: 1.1;
            letter-spacing: -1px;
            color: var(--ink-primary);
            margin-bottom: 16px;
        }
        .lede {
            font-size: 17px;
            color: var(--ink-muted);
            margin-bottom: 40px;
            max-width: 620px;
        }

        h2 {
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 700;
            font-size: 26px;
            line-height: 1.2;
            letter-spacing: -0.5px;
            color: var(--ink-primary);
            margin-top: 56px;
            margin-bottom: 20px;
        }

        /* ── Contact strip ── */
        .contact-strip {
            background: var(--accent-soft);
            border-left: 3px solid var(--accent);
            border-radius: 0 14px 14px 0;
            padding: 20px 24px;
            margin-bottom: 32px;
        }
        .contact-strip strong {
            display: block;
            font-family: 'Fraunces', Georgia, serif;
            font-weight: 700;
            font-size: 17px;
            color: var(--ink-primary);
            margin-bottom: 6px;
        }
        .contact-strip p { font-size: 14px; color: var(--ink-primary); margin-bottom: 4px; }

        /* ── FAQ ── */
        .faq-item {
            border: 1px solid var(--border);
            background: var(--bg-card);
            border-radius: 14px;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .faq-question {
            padding: 18px 22px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 15px;
            color: var(--ink-primary);
            transition: background 0.15s;
            user-select: none;
        }
        .faq-question:hover { background: var(--bg-subtle); }
        .faq-toggle {
            width: 20px; height: 20px;
            display: inline-flex; align-items: center; justify-content: center;
            color: var(--accent);
            font-weight: 700;
            font-size: 18px;
            transition: transform 0.2s;
        }
        .faq-item.open .faq-toggle { transform: rotate(45deg); }
        .faq-answer {
            padding: 0 22px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease-out, padding 0.25s ease-out;
            font-size: 14px;
            color: var(--ink-muted);
            line-height: 1.6;
        }
        .faq-item.open .faq-answer { padding: 0 22px 20px; max-height: 500px; }

        /* ── Form ── */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
        }
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 13px;
            color: var(--ink-primary);
            letter-spacing: 0.2px;
        }
        label .req { color: var(--danger); }
        input[type="text"], input[type="email"], input[type="tel"], select, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            background: var(--bg-card);
            color: var(--ink-primary);
            transition: border-color 0.15s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
        }
        textarea { resize: vertical; min-height: 140px; }
        .hint { font-size: 12px; color: var(--ink-muted); margin-top: 6px; }
        .btn {
            background: var(--accent);
            color: var(--on-accent);
            padding: 14px 28px;
            border: none;
            border-radius: 999px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            width: 100%;
        }
        .btn:hover:not(:disabled) { background: var(--accent-hover); transform: translateY(-1px); }
        .btn:disabled { background: var(--ink-muted); cursor: not-allowed; opacity: 0.7; }

        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }
        .alert-success { background: #EAF5EE; border: 1px solid var(--success); color: var(--success); }
        .alert-error   { background: #FBECEC; border: 1px solid var(--danger); color: var(--danger); }

        .foot {
            margin-top: 64px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--ink-muted);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .foot a { color: var(--ink-muted); font-weight: 400; }

        @media (max-width: 560px) {
            .form-card { padding: 24px 20px; }
            .wrap { padding: 16px 20px 64px; }
        }
    </style>
</head>
<body>

<nav>
    <a href="/" class="brand">babashop<span class="brand-dot">.</span></a>
</nav>

<div class="wrap">

    <div class="kicker">Support Babashop</div>
    <h1>Une question ? On répond.</h1>
    <p class="lede">
        Trouvez la réponse à votre question dans la FAQ ci-dessous, ou écrivez-nous
        directement. On revient vers vous sous 24 à 48 heures ouvrées.
    </p>

    <div class="contact-strip">
        <strong>Nous joindre directement</strong>
        <p>📧 <a href="mailto:support@babashop.store">support@babashop.store</a></p>
        <p>🕐 Lundi – Samedi, 9 h – 18 h (heure de Tunis)</p>
    </div>

    <h2>Questions fréquentes</h2>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Comment passer une commande ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            Depuis l'application, parcourez le catalogue, ajoutez les articles à votre panier,
            puis touchez « Procéder au paiement ». Vous choisirez ensuite votre gouvernorat de
            livraison, saisirez l'adresse et validerez. Aucun paiement n'est demandé pendant la
            commande — vous réglez au moment de la livraison.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Quels modes de paiement acceptez-vous ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            Le mode principal est le <strong>paiement à la livraison</strong> (COD) en espèces,
            en dinar tunisien, directement au livreur. Le paiement par carte bancaire via
            prestataire agréé est en cours de déploiement.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Combien coûte la livraison et sous quels délais ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            Les frais de livraison et le délai estimé (1 à 5 jours ouvrés selon le gouvernorat)
            sont affichés dans votre panier <strong>avant</strong> validation de la commande, en
            fonction du gouvernorat que vous sélectionnez. Nous livrons dans les 24 gouvernorats
            tunisiens.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Comment suivre ma commande ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            Depuis l'onglet <em>Commandes</em> de l'application, touchez la commande souhaitée pour
            voir son statut en temps réel : <em>En attente → Confirmée → En préparation →
            En livraison → Livrée</em>. Vous êtes également notifié à chaque changement d'étape
            si vous avez activé les notifications.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Puis-je modifier ou annuler ma commande ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            L'annulation est possible tant que la commande est au statut <em>En attente</em> ou
            <em>Confirmée</em>. Passé ce stade, écrivez-nous à support@babashop.store avec votre
            numéro de commande et nous ferons de notre mieux pour intervenir avant l'expédition.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Que faire si un article est endommagé ou incorrect ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            Refusez le colis à la livraison ou contactez-nous dans les 48 heures suivant la
            réception. Nous organisons le retour à nos frais et procédons soit à un remplacement,
            soit à un remboursement selon votre préférence.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Comment me connecter sans mot de passe ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            Babashop utilise une authentification par <strong>code à usage unique par e-mail</strong>.
            Saisissez votre adresse e-mail, recevez un code à 6 chiffres, et connectez-vous. Aucun
            mot de passe à créer ni à retenir. Le code expire au bout de 5 minutes.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Comment supprimer mon compte ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            Directement depuis l'application : <em>Compte → Supprimer mon compte</em>. La
            suppression est effective sous 72 heures. Vos commandes passées sont conservées de
            manière anonymisée pour respecter les obligations comptables tunisiennes. Détails
            dans notre <a href="/privacy-policy">politique de confidentialité</a>.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
            <span>Vendez-vous mes données personnelles ?</span>
            <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
            Jamais. Nous ne partageons vos données qu'avec le livreur (nom, téléphone, adresse
            pour la livraison) et le prestataire de paiement si vous choisissez la carte
            bancaire. Aucun partage avec des annonceurs ou courtiers en données. Voir
            la <a href="/privacy-policy">politique de confidentialité</a>.
        </div>
    </div>

    <h2>Écrivez-nous</h2>
    <p class="lede" style="margin-bottom: 24px;">
        Si votre question ne figure pas dans la FAQ, remplissez le formulaire ci-dessous.
    </p>

    <div class="form-card">
        <div class="alert alert-success" id="successMessage">
            Merci — votre message est bien parti. Nous répondons sous 24 à 48 heures.
        </div>
        <div class="alert alert-error" id="errorMessage">
            Une erreur s'est produite. Vérifiez votre saisie ou réessayez dans un instant.
        </div>

        <form id="supportForm">
            <div class="form-group">
                <label for="name">Nom complet <span class="req">*</span></label>
                <input type="text" id="name" name="name" required placeholder="Ex. Ahmed Ben Ali">
            </div>

            <div class="form-group">
                <label for="email">Adresse e-mail <span class="req">*</span></label>
                <input type="email" id="email" name="email" required placeholder="vous@exemple.tn">
                <p class="hint">Nous répondons à cette adresse.</p>
            </div>

            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" placeholder="+216 XX XXX XXX">
            </div>

            <div class="form-group">
                <label for="subject">Sujet <span class="req">*</span></label>
                <select id="subject" name="subject" required>
                    <option value="">Sélectionnez un sujet…</option>
                    <option value="order">Question sur une commande</option>
                    <option value="delivery">Livraison</option>
                    <option value="payment">Paiement</option>
                    <option value="product">Question sur un produit</option>
                    <option value="account">Compte / connexion</option>
                    <option value="refund">Retour ou remboursement</option>
                    <option value="technical">Problème technique dans l'app</option>
                    <option value="suggestion">Suggestion / retour d'expérience</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="order_id">Numéro de commande (facultatif)</label>
                <input type="text" id="order_id" name="order_id" placeholder="Ex. #0567">
            </div>

            <div class="form-group">
                <label for="message">Votre message <span class="req">*</span></label>
                <textarea id="message" name="message" required placeholder="Décrivez votre demande en quelques lignes."></textarea>
            </div>

            <button type="submit" class="btn" id="submitBtn">Envoyer le message</button>
        </form>
    </div>

    <div class="foot">
        <span>© 2026 Babashop</span>
        <span>
            <a href="/">Accueil</a>&nbsp;·&nbsp;
            <a href="/privacy-policy">Confidentialité</a>&nbsp;·&nbsp;
            <a href="/account-deletion">Suppression de compte</a>
        </span>
    </div>

</div>

<script>
    function toggleFaq(el) {
        el.parentElement.classList.toggle('open');
    }

    document.getElementById('supportForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = document.getElementById('submitBtn');
        const ok  = document.getElementById('successMessage');
        const err = document.getElementById('errorMessage');
        ok.style.display  = 'none';
        err.style.display = 'none';
        btn.disabled = true;
        btn.textContent = 'Envoi en cours…';

        const payload = {
            name:     document.getElementById('name').value,
            email:    document.getElementById('email').value,
            phone:    document.getElementById('phone').value,
            subject:  document.getElementById('subject').value,
            order_id: document.getElementById('order_id').value,
            message:  document.getElementById('message').value,
        };

        fetch('/api/support-request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                ok.style.display = 'block';
                document.getElementById('supportForm').reset();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                err.textContent = data.message || 'Une erreur s\'est produite. Veuillez réessayer.';
                err.style.display = 'block';
            }
        })
        .catch(() => {
            err.textContent = 'Impossible d\'envoyer le message. Vérifiez votre connexion.';
            err.style.display = 'block';
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Envoyer le message';
        });
    });
</script>

</body>
</html>
