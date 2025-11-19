<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau message de contact</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; color: #333;">
    <h2 style="color: #f87171;">Nouveau message de contact</h2>

    <p><strong>Nom :</strong> {{ $prenom }} {{ $nom }}</p>
    <p><strong>Email :</strong> {{ $email }}</p>
    @if($tel)
        <p><strong>Téléphone :</strong> {{ $tel }}</p>
    @endif
    @if($addresse)
        <p><strong>Adresse :</strong> {{ $addresse }}</p>
    @endif
    <p><strong>Sujet :</strong> {{ $sujet }}</p>

    <hr style="margin: 1em 0;">

    <p><strong>Message :</strong></p>
    <p>{{ $content }}</p>
</body>
</html>
