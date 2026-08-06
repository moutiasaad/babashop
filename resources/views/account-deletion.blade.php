<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Suppression de compte - ShaiebExpo</title>
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
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #d32f2f;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        .warning-box p {
            color: #856404;
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
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input[type="email"]:focus,
        input[type="tel"]:focus,
        textarea:focus {
            outline: none;
            border-color: #d32f2f;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            margin: 20px 0;
        }
        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            margin-top: 4px;
        }
        .checkbox-group label {
            margin: 0;
            font-weight: normal;
        }
        .btn {
            background: #d32f2f;
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
            background: #b71c1c;
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
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Demande de suppression de compte</h1>

        <p style="margin-bottom: 20px;">
            Nous sommes désolés de vous voir partir. Si vous souhaitez supprimer votre compte, veuillez remplir le formulaire ci-dessous.
        </p>

        <div class="warning-box">
            <h3>⚠️ Attention</h3>
            <p>• La suppression de votre compte est <strong>irréversible</strong></p>
            <p>• Toutes vos données personnelles seront supprimées</p>
            <p>• Votre historique de commandes sera supprimé</p>
            <p>• Vous ne pourrez plus accéder à votre liste de souhaits</p>
        </div>

        <div class="success-message" id="successMessage">
            Votre demande de suppression de compte a été envoyée avec succès. Nous traiterons votre demande dans les 48 heures.
        </div>

        <div class="error-message" id="errorMessage">
            Une erreur s'est produite. Veuillez réessayer.
        </div>

        <form id="deletionForm">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required placeholder="votre@email.com">
                <p class="info-text">L'email associé à votre compte</p>
            </div>

            <div class="form-group">
                <label for="phone">Numéro de téléphone *</label>
                <input type="tel" id="phone" name="phone" required placeholder="+216 XX XXX XXX">
                <p class="info-text">Le numéro de téléphone associé à votre compte</p>
            </div>

            <div class="form-group">
                <label for="reason">Raison de la suppression (optionnel)</label>
                <textarea id="reason" name="reason" placeholder="Dites-nous pourquoi vous souhaitez supprimer votre compte..."></textarea>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="confirm" name="confirm" required>
                <label for="confirm">
                    Je comprends que cette action est irréversible et que toutes mes données seront définitivement supprimées.
                </label>
            </div>

            <button type="submit" class="btn" id="submitBtn">Envoyer la demande de suppression</button>
        </form>
    </div>

    <script>
        document.getElementById('deletionForm').addEventListener('submit', function(e) {
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
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                reason: document.getElementById('reason').value
            };

            fetch('/api/account-deletion-request', {
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
                    document.getElementById('deletionForm').reset();
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
                submitBtn.textContent = 'Envoyer la demande de suppression';
            });
        });
    </script>
</body>
</html>
