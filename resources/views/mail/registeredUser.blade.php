<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvel utilisateur inscrit</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
            color: #1f2937;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 25px 30px;
        }

        h1 {
            color: #f87171;
            font-size: 22px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        p {
            line-height: 1.6;
            font-size: 15px;
            margin: 10px 0;
        }

        .highlight {
            font-weight: 600;
            color: #111827;
        }

        .info-box {
            background-color: #f1f5f9;
            border-left: 4px solid #f87171;
            padding: 12px 16px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            text-align: center;
        }

        a.button {
            display: inline-block;
            background-color: #f87171;
            color: #FFF;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 15px;
            transition: background-color 0.2s ease;
        }

        a.button:hover {
            background-color: #f87171;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nouvel utilisateur inscrit</h1>

        <p>Bonjour,</p>

        <p>
            Un nouvel utilisateur vient de s'inscrire sur la plateforme de
            <span class="highlight">l'Association de soccer STARS</span>.
        </p>

        <div class="info-box">
            <p><strong>Prénom :</strong> {{ $prenomUser }}</p>
            <p><strong>Nom :</strong> {{ $nomUser }}</p>
            <p><strong>Adresse e-mail :</strong> {{ $emailUser }}</p>
            <p><strong>ID utilisateur :</strong> #{{ $idUser }}</p>
        </div>

        <p>Vous pouvez consulter ce compte dans votre espace d'administration.</p>

        <a href="{{ url('/users/search?page=1&perPage=10&search='.$emailUser.'&from=email') }}" class="button">
            Voir le profil de l'utilisateur
        </a>

        <div class="footer">
            <p>Ce message a été généré automatiquement par la plateforme STARS.</p>
            <p>© {{ date('Y') }} Association de soccer STARS</p>
        </div>
    </div>
</body>
</html>
