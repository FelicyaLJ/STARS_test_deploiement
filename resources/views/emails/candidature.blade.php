@component('mail::message')
# Nouvelle candidature reçue

Une nouvelle personne a appliqué à un poste.

---

### **Informations personnelles**
- **Nom :** {{ $nom }}
- **Prénom :** {{ $prenom }}
- **Courriel :** {{ $email }}
@if ($tel)
- **Téléphone :** {{ $tel }}
@endif
@if ($adresse)
- **Adresse :** {{ $adresse }}
@endif

---

### **Poste visé**
**{{ $poste }}**

---

### **Lettre de motivation / Message**
{{ $messageCandidature }}

---

@component('mail::button', ['url' => config('app.url')])
Aller au site
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
