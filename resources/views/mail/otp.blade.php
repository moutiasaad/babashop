<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre code Babashop</title>
</head>
<body style="font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f5f5f5; padding: 24px; margin: 0; color: #1a1a1a;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <tr>
            <td style="text-align: center; padding-bottom: 24px; border-bottom: 1px solid #eee;">
                <h1 style="margin: 0; font-size: 24px; font-weight: 700;">Babashop</h1>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 24px; padding-bottom: 12px; font-size: 15px;">
                Bonjour{{ $user && $user->fullname ? ' ' . $user->fullname : '' }},
            </td>
        </tr>
        <tr>
            <td style="padding-bottom: 20px; font-size: 15px; line-height: 1.5;">
                Voici votre code de vérification. Il expire dans <strong>5&nbsp;minutes</strong>.
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding: 12px 0 24px;">
                <div style="display: inline-block; padding: 18px 36px; background: #f0f0f0; border-radius: 8px; font-size: 30px; letter-spacing: 8px; font-weight: 700;">
                    {{ $otp }}
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 12px; font-size: 12px; color: #888; line-height: 1.5;">
                Si vous n'avez pas demandé ce code, vous pouvez ignorer cet e-mail en toute sécurité.
            </td>
        </tr>
    </table>
</body>
</html>
