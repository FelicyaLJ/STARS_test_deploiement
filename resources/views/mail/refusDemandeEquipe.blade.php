<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande refusée</title>
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
            color: #ef4444;
            font-size: 20px;
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

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            text-align: center;
        }

        .raison-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 12px 16px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .btn {
            display: inline-block;
            background-color: #ef4444;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">

        <h1>Votre demande a été refusée</h1>

        <p>
            Bonjour <span class="highlight">{{ $user->prenom }} {{ $user->nom }}</span>,
        </p>

        <p>
            Nous vous informons que votre demande pour rejoindre l'équipe
            <span class="highlight">« {{ $equipe->nom_equipe }} »</span> a malheureusement été refusée.
        </p>

        <p><strong>Motif éventuel :</strong></p>
        <div class="raison-box">
            <p>
                L'administrateur de l'équipe a choisi de ne pas accepter votre demande.
                <br>
                (Aucun détail supplémentaire n'a été fourni.)
            </p>
        </div>

        <p>
            Vous pouvez toujours consulter d'autres équipes ou créer la vôtre depuis votre espace membre.
        </p>

        <div class="footer">
            Cet email est généré automatiquement — merci de ne pas répondre.
        </div>
    </div>
</body>
</html>
