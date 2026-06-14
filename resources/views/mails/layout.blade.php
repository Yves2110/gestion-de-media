<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gestion Media')</title>
</head>
<body style="margin:0;padding:0;background:#f5f5f5;font-family:Montserrat,Arial,sans-serif;color:#333;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f5f5f5;padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#eb3349,#f45c43);padding:24px 32px;text-align:center;">
                            <p style="margin:0;color:#fff;font-size:20px;font-weight:700;">Gestion Media</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px 24px;font-size:12px;color:#888;text-align:center;border-top:1px solid #eee;">
                            Cet e-mail a été envoyé automatiquement, merci de ne pas y répondre.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
