<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de acceso</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #09090b;
            color: #f4f4f5;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
        }
        .container {
            max-width: 520px;
            margin: 40px auto;
            padding: 32px 24px;
            background-color: #18181b;
            border-radius: 16px;
            border: 1px solid #27272a;
        }
        .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand h1 {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.025em;
        }
        .card {
            background-color: #27272a;
            border: 1px solid #3f3f46;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin: 24px 0;
        }
        .token-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 0.25em;
            color: #6366f1;
            margin: 8px 0;
        }
        .text {
            font-size: 15px;
            line-height: 1.6;
            color: #a1a1aa;
            margin: 12px 0;
        }
        .button-wrap {
            text-align: center;
            margin: 28px 0;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 10px;
        }
        .footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid #27272a;
            font-size: 12px;
            color: #71717a;
            text-align: center;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand">
            <h1>Laravertex</h1>
        </div>

        <p class="text" style="color: #f4f4f5; font-size: 17px; font-weight: 600;">
            Tu código de acceso
        </p>

        <p class="text">
            Hemos recibido una solicitud de acceso para <strong>{{ $emailAddress }}</strong>. Ingresa el siguiente código en la pantalla de verificación:
        </p>

        <div class="card">
            <div class="token-code">{{ $tokenCode }}</div>
            <div class="text" style="font-size: 13px; color: #a1a1aa; margin: 0;">
                Válido por los próximos {{ $expiresInMinutes }} minutos
            </div>
        </div>

        <div class="button-wrap">
            <p class="text" style="margin-bottom: 14px;">O inicia sesión directamente con un solo clic:</p>
            <a href="{{ $verificationUrl }}" class="btn" target="_blank">Iniciar Sesión Ahora</a>
        </div>

        <div class="footer">
            Si no solicitaste este código, puedes ignorar este correo de manera segura. Nadie puede acceder a tu cuenta sin este código.
            <br><br>
            © {{ date('Y') }} Laravertex Starter Kit.
        </div>
    </div>
</body>
</html>
