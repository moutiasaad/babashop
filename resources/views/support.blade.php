<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Support - Shaieb.store</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #f5f5f5;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            color: #0d47a1;
            margin: 5px 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        label .required {
            color: #d32f2f;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: inherit;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2196f3;
        }
        textarea {
            resize: vertical;
            min-height: 150px;
        }
        select {
            cursor: pointer;
            background: white;
        }
        .btn {
            background: #2196f3;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
        }
        .btn:hover {
            background: #1976d2;
        }
        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        .info-text {
            color: #666;
            font-size: 13px;
            margin-top: 5px;
        }
        .contact-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
        }
        .contact-info h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .contact-info p {
            margin: 8px 0;
            color: #555;
        }
        .contact-info a {
            color: #2196f3;
            text-decoration: none;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Centre de Support</h1>
        <p class="subtitle">Nous sommes là pour vous aider. Remplissez le formulaire ci-dessous et notre équipe vous répondra dans les plus brefs délais.</p>

        <div class="info-box">
            <p>📧 Temps de réponse moyen: 24-48 heures</p>
            <p>💬 Pour les urgences, contactez-nous directement par téléphone</p>
        </div>

        <div class="success-message" id="successMessage">
            Votre message a été envoyé avec succès! Notre équipe vous répondra dans les 24-48 heures.
        </div>

        <div class="error-message" id="errorMessage">
            Une erreur s'est produite. Veuillez réessayer.
        </div>

        <form id="supportForm">
            <div class="form-group">
                <label for="name">Nom complet <span class="required">*</span></label>
                <input type="text" id="name" name="name" required placeholder="Votre nom complet">
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" required placeholder="votre@email.com">
                <p class="info-text">Nous utiliserons cet email pour vous répondre</p>
            </div>

            <div class="form-group">
                <label for="phone">Numéro de téléphone <span class="required">*</span></label>
                <input type="tel" id="phone" name="phone" required placeholder="+216 XX XXX XXX">
            </div>

            <div class="form-group">
                <label for="subject">Sujet <span class="required">*</span></label>
                <select id="subject" name="subject" required>
                    <option value="">Sélectionnez un sujet</option>
                    <option value="order">Problème de commande</option>
                    <option value="payment">Problème de paiement</option>
                    <option value="delivery">Problème de livraison</option>
                    <option value="product">Question sur un produit</option>
                    <option value="account">Problème de compte</option>
                    <option value="refund">Demande de remboursement</option>
                    <option value="technical">Problème technique</option>
                    <option value="suggestion">Suggestion ou feedback</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="order_id">Numéro de commande (optionnel)</label>
                <input type="text" id="order_id" name="order_id" placeholder="Ex: #12345">
                <p class="info-text">Si votre demande concerne une commande spécifique</p>
            </div>

            <div class="form-group">
                <label for="message">Message <span class="required">*</span></label>
                <textarea id="message" name="message" required placeholder="Décrivez votre problème ou votre question en détail..."></textarea>
            </div>

            <button type="submit" class="btn" id="submitBtn">Envoyer le message</button>
        </form>

        <div class="contact-info">
            <h3>Autres moyens de nous contacter</h3>
            <p>📞 Téléphone: <a href="tel:+21612345678">+216 12 345 678</a></p>
            <p>📧 Email: <a href="mailto:support@shaieb.store">support@shaieb.store</a></p>
            <p>🕐 Horaires: Lundi - Samedi, 9h00 - 18h00</p>
        </div>
    </div>

    <script>
        document.getElementById('supportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');

            // Hide messages
            successMessage.style.display = 'none';
            errorMessage.style.display = 'none';

            // Disable button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Envoi en cours...';

            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                subject: document.getElementById('subject').value,
                order_id: document.getElementById('order_id').value,
                message: document.getElementById('message').value
            };

            fetch('/api/support-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successMessage.style.display = 'block';
                    document.getElementById('supportForm').reset();
                    window.scrollTo(0, 0);
                } else {
                    errorMessage.textContent = data.message || 'Une erreur s\'est produite. Veuillez réessayer.';
                    errorMessage.style.display = 'block';
                }
            })
            .catch(error => {
                errorMessage.style.display = 'block';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Envoyer le message';
            });
        });
    </script>
</body>
</html>
