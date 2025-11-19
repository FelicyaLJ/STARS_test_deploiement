<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Requête pour rejoindre une équipe</title>
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

        .btn-accept {
            background-color: #f87171;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-refuse {
            background-color: #ef4444;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Requête pour rejoindre l'équipe</h1>

        <p>
            <span class="highlight">{{ $prenomUser }} {{ $nomUser }}</span> souhaite rejoindre l'équipe
            <span class="highlight">« {{ $equipeName }} »</span>.
        </p>

        <p><strong>Raison de la requête :</strong></p>
        <div class="raison-box">
            <p>{{ $raison }}</p>
        </div>

        <form action="{{ url('/equipes/addUser/' . $idUser . '/' . $idEquipe) }}" method="POST" style="display:inline;">
            @csrf
            @method('PUT')

            <button type="submit" class="btn-accept"
                onmouseover="this.style.backgroundColor='#f75f5f';"
                onmouseout="this.style.backgroundColor='#f87171';">
                Accepter la demande
            </button>
        </form>

        <form action="{{ url('/equipes/refuserDemande/' . $idUser . '/' . $idEquipe) }}" method="POST" style="display:inline; margin-left: 10px;">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn-refuse"
                onmouseover="this.style.backgroundColor='#dc2626';"
                onmouseout="this.style.backgroundColor='#ef4444';"
            >
                Refuser la demande
            </button>
        </form>



    </div>
</body>
</html>

