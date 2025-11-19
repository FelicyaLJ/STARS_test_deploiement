<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande d'adhésion</title>
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
            background-color: #f1f5f9;
            border-left: 4px solid #f87171;
            padding: 12px 16px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nouvelle inscription à l'activité {{$activiteName}}</h1>

        <p>
            <span class="highlight">{{ $prenomUser }} {{ $nomUser }}</span> souhaite s'inscrire à l'activité
            <span class="highlight">« {{ $activiteName }} »</span> au coût de <span class="highlight">{{ $prix }}</span>$.
        </p>

        <a href="mailto:{{ $emailUser }}">{{ $emailUser }}</a>
        <a href={{url('/inscriptions/gestionDemandes/' . $idDemande)}}>Voir la demande.</a>

    </div>
</body>
</html>

