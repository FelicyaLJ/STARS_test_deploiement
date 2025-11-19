<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification d’adresse e-mail</title>
    <style>
        body {
            background-color: #000;
            color: #fefefe;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: rgba(255,255,255,0.05);
            border: 1px solid #f87171;
            border-radius: 12px;
            margin: auto;
            padding: 30px;
            text-align: center;
        }
        h1 {
            color: #f87171;
        }
        a.button {
            display: inline-block;
            background-color: #f87171;
            color: #FFF;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: 600;
        }
        a.button:hover {
            background-color: #f87171;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vérifiez votre adresse courriel</h1>
        <p>Bonjour {{ $notifiable->prenom ?? 'utilisateur' }},</p>
        <p>Merci de vous être inscrit sur l’Association de Soccer STARS.</p>
        <p>Veuillez cliquer sur le bouton ci-dessous pour confirmer votre adresse e-mail :</p>

        <a href="{{ $url }}" class="button">Vérifier mon adresse</a>

        <p style="margin-top:20px; font-size:14px; color:#ccc;">
            Ce lien expirera dans 60 minutes.
        </p>
    </div>
</body>
</html>
