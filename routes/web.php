<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

use App\Http\Controllers\FBController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\CoutInscriptionController;
use App\Http\Controllers\DemandeAdhesionController;
use App\Http\Controllers\DemandeInscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\TerrainController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ExerciceController;
use App\Http\Controllers\CategorieExerciceController;
use App\Http\Controllers\CategorieEvenementController;
use App\Http\Controllers\CategorieFAQController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PartenaireController;

// ROUTES SANS AUTHENTIFICATION

Route::controller(PublicationController::class)->group(function () {
    Route::get('/', 'index')->name('accueil');
});

Route::post('/accept-cookies', function () {
    $cookieValue = json_encode(['status' => 'accepted']);
    return response()->json(['status' => 'accepted'])
        ->cookie('cookie_consent', $cookieValue, 60 * 24 * 365);
});

Route::post('/reject-cookies', function () {
    $cookieValue = json_encode(['status' => 'rejected']);
    return response()->json(['status' => 'rejected'])
        ->cookie('cookie_consent', $cookieValue, 60 * 24 * 365);
});

Route::view('/notre-club', 'notre-club')->name('notre.club');
Route::view('/contactez-nous', 'contact-form')->name('contactez.nous');

Route::controller(CandidatureController::class)->group(function() {
    Route::get('/candidatures', 'index')->name('candidatures.index');
});

Route::controller(PosteController::class)->group(function() {
    Route::get('/salaires', 'index_salaire')->name('salaires');
});

Route::controller(EvenementController::class)->group(function() {
    Route::prefix('evenements')->group(function () {
        Route::get('', 'show')->name('evenements.list');
        Route::get('/by-date', 'getEvenementsByDate')->name('evenements_by_date');
        Route::get('/reserved-dates', 'getMonthEvenements')->name('evenements_by_month');
        Route::get('/search', 'search')->name('evenements.search');
        Route::post('/render', 'render')->name('evenements.render');
    });
});

Route::controller(CategorieFAQController::class)->group(function() {
    Route::prefix('faq')->group(function() {
        Route::get('', 'index')->name('faq.list');
    });
});

Route::controller(MailController::class)->group(function() {
    Route::post('/contactez-nous', 'send')->name('send.mail');
    Route::post('/candidatures', 'send_candidature')->name('send_candidature.mail');
});

Route::get('/membres-ca', [UserController::class, 'getMembresCA'])->name('api.membres.ca');
Route::get('/partenaires', [PartenaireController::class, 'index'])->name('api.partenaires.list');


// ROUTES AVEC AUTHENTIFICATION
Route::middleware(['auth'])->group(function () {
    Route::controller(ProfileController::class)->group(function() {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});


// ROUTES AVEC AUTHENTIFICATION VÉRIFIÉE
Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(PosteController::class)->group(function() {
        Route::middleware(PermissionManageSalaires::class)->group(function () {
            Route::get('/postes/{id}/edit', 'edit')->name('postes.edit');
            Route::put('/postes/modification/{id}', 'update')->name('postes.update');
            Route::post('/enregistrementPoste', 'store')->name('postes.store');
            Route::delete('/suppression/poste/{id}', 'destroy')->name('postes.destroy');
        });
    });

    Route::get('/joueurs/search', [UserController::class, 'searchPlayers']);

    Route::controller(EquipeController::class)->group(function() {
        Route::get('/equipes', 'index')->name('equipes.index');

        Route::get('/equipes/{id}/joueurs', 'getJoueurs')->name('equipes.getjoueur');
        Route::get('/equipes/filtrer', 'filtrer')->name('equipes.filtrer');
        Route::get('/equipe/search', 'search')->name('equipe_search');
        Route::post('/equipe/render', 'render')->name('equipe.render');
        Route::post('/equipes/{equipe}/rejoindre', 'envoyerDemandeRejoindre')->name('equipes.envoyerDemandeRejoindre');
        Route::delete('/equipes/{equipe}/quitter', 'quitterEquipe')->name('equipes.quitter');

        Route::middleware(PermissionEquipes::class)->group(function () {
            Route::post('/enregistrementEquipe', 'store')->name('equipes.store');
            Route::delete('/equipes/suppression/{id}', 'destroy')->name('equipes.destroy');
            Route::get('/equipes/{id}/edit', 'edit')->name('equipes.edit');
            Route::put('/equipes/modification/{id}', 'update')->name('equipes.update');
            Route::put('/equipes/addUser/{idMembre}/{idForum}', 'addUser')->name('equipes.addUser');
            Route::delete('/equipes/refuserDemande/{idMembre}/{idEquipe}', 'refuserDemande')->name('equipes.refuser');

            Route::post('/equipes/{equipe}/joueurs/create', 'createBlankJoueur')->name('equipes.createjoueur');
            Route::post('/equipes/{equipe}/joueurs/email', 'ajouterJoueurParEmail')->name('equipes.joueurs.email');
            Route::delete('/equipes/{equipe}/joueurs/{user}', 'deleteBlankJoueur')->name('equipes.deletejoueur');
        });
    });


    Route::controller(PublicationController::class)->group(function () {

        Route::prefix('accueil')->group(function () {
            Route::middleware(PermissionManageActualites::class)->group(function () {
                Route::post('/create', 'store')->name('actualite_create');
                Route::delete('/delete', 'destroy')->name('actualite_delete');
                Route::post('/edit', 'edit')->name('actualite_edit');
            });
        });

    });

    Route::controller(TerrainController::class)->group(function () {
        Route::get('/terrain', 'index')->name('terrain');
        //Route::get('/fetch/terrain', 'index')->name('fetch_terrain');

        Route::prefix('terrains')->group(function () {
            Route::get('/by-date', 'getTerrainsByDate')->name('terrains_by_date');
            Route::get('/reserved-dates', 'getMonthReservations')->name('terrains_by_month');

            Route::middleware(PermissionManageTerrains::class)->group(function () {
                Route::post('/create', 'create')->name('terrain_create_api');
                Route::delete('/delete', 'destroy')->name('terrain_delete_api');
                Route::post('/edit', 'edit')->name('terrain_edit_api');
            });
        });
    });


    Route::controller(UserController::class)->group(function() {
        Route::middleware(PermissionManageUsersOrForums::class)->group(function () {
            Route::get('/users/search', 'search')->name('users.search');
        });
    });

    // ADMIN
    Route::middleware(['password.confirm'])->group(function () {
        // Permission de gérer les utilisateurs
        Route::controller(UserController::class)->group(function() {
            Route::middleware(PermissionManageUsers::class)->group(function () {
                Route::get('/users', 'index')->name('users.list');
                Route::post('/users/add', 'store')->name('users.store');
                Route::patch('/users/update', 'update')->name('users.update');
                Route::delete('/users/delete', 'destroy')->name('users.destroy');
            });
        });

        // Permission de gérer les rôles
        Route::controller(RoleController::class)->group(function () {
            Route::middleware(PermissionManageRoles::class)->group(function () {
                Route::get('/roles', 'index')->name('roles.list');
                Route::get('/roles/fetch', 'fetch')->name('roles.fetch');
                Route::post('/roles/add', 'store')->name('roles.store');
                Route::patch('/roles/update', 'update')->name('roles.update');
                Route::delete('/roles/delete', 'destroy')->name('roles.destroy');
            });
        });

        // Permission de gérer les paramètres de l'application
        Route::controller(SettingsController::class)->name('admin.')->group(function() {
            Route::middleware(PermissionManageSettings::class)->group(function () {
                Route::get('/settings', 'edit')->name('settings.edit');
                Route::post('/settings', 'update')->name('settings.update');
            });
        });
    });


    // Permission de gérer les événements
    Route::controller(EvenementController::class)->group(function() {
        Route::middleware(PermissionManageEvents::class)->group(function () {
            Route::prefix('evenements')->group(function () {
                Route::post('/create', 'create')->name('evenements.create.api');
                Route::delete('/delete', 'destroy')->name('evenements.delete.api');
                Route::post('/edit', 'edit')->name('evenements.edit.api');
                Route::get('/terrains/disponibles', 'getAvailableTerrains')->name('evenements.terrains.disponibles');
            });
        });
    });

    // Permission de gérer les catégories d'événements
    Route::controller(CategorieEvenementController::class)->group(function () {
        Route::middleware(PermissionManageEventCategories::class)->group(function () {
            Route::prefix('evenements/categorie')->group(function () {
                Route::post('/add', 'store')->name('evenement.categorie.store');
                Route::patch('/update', 'update')->name('evenement.categorie.update');
                Route::delete('/delete', 'destroy')->name('evenement.categorie.delete');
                Route::post('/render', 'render')->name('evenement.categorie.render');
            });
        });
    });

    Route::controller(PartenaireController::class)->group(function() {
        Route::prefix('partenaires')->group(function() {
            Route::post('/create', 'create')->name('partenaire_create_api');
            Route::delete('/delete', 'destroy')->name('partenaire_delete_api');
            Route::post('/edit', 'edit')->name('partenaire_edit_api');
        });
    });


    Route::controller(ForumController::class)->group(function() {
        Route::get('/forums', 'index')->name('forums');
        Route::post('/exercice/forums/store', 'store')->name('exercice.forums.store');
        Route::post('/forums/{forum}/adhesion', 'envoyerDemandeAdhesion')->name('forums.envoyerDemandeAdhesion');

        // Permission de gérer les forums
        Route::middleware(PermissionManageForums::class)->group(function () {
            Route::put('/forums/update/{id}', 'update')->name('forums.update');
            Route::put('/forums/addUser/{idMembre}/{idForum}', 'addUser')->name('forums.addUser');
            Route::post('/forums/store', 'store')->name('forums.store');
            Route::delete('/forums/destroy', 'destroy')->name('forums.destroy');
        });

        Route::get('/forums/{id}/messages', [ForumController::class, 'getMessages'])->name('forums.messages');
    });

    Route::controller(MessageController::class)->group(function() {
        Route::post('/message/store', 'store')->name('message.store');
        Route::post('/messages/{message}/signalement', 'signaler')->name('messages.signaler');

        // Permission de gérer les messages
        Route::middleware(PermissionManageMessages::class)->group(function () {
            Route::delete('/message/destroy', 'destroy')->name('message.destroy');
        });
    });

    Route::controller(CategorieExerciceController::class)->group(function(){
        Route::middleware(PermissionConsultationEntrainements::class)->group(function(){
            Route::get('/exercices', 'index')->name('exercices');
        });

        Route::middleware(PermissionManageEntrainements::class)->group(function(){
            Route::post('/exercices/categorie/store', 'store')->name('exercices.categorie.store');
            Route::delete('/exercices/categorie/destroy', 'destroy')->name('exercices.categorie.destroy');
            Route::put('/exercices/categorie/update/{id}', 'update')->name('exercices.categorie.update');
        });
    });

    Route::controller(ExerciceController::class)->group(function(){
        Route::middleware(PermissionManageEntrainements::class)->group(function(){
            Route::post('/exercice/store', 'store')->name('exercice.store');
            Route::delete('/exercice/destroy', 'destroy')->name('exercice.destroy');
            Route::put('/exercice/update', 'update')->name('exercice.update');
        });
    });

    //Gestion des FAQS et des catégories
    Route::controller(CategorieFAQController::class)->group(function() {
        Route::prefix('faq/categorie')->group(function() {
            Route::middleware(PermissionManageFAQ::class)->group(function () {
                Route::post('/create', 'create')->name('categorie_faq_create_api');
                Route::delete('/delete', 'destroy')->name('categorie_faq_delete_api');
                Route::post('/edit', 'edit')->name('categorie_faq_edit_api');
            });
        });
    });

    //Gestion des FAQS et des catégories
    Route::controller(FAQController::class)->group(function() {
        Route::prefix('faq')->group(function() {
            Route::middleware(PermissionManageFAQ::class)->group(function () {
                Route::post('/create', 'create')->name('faq_create_api');
                Route::delete('/delete', 'destroy')->name('faq_delete_api');
                Route::post('/edit', 'edit')->name('faq_edit_api');
            });
        });
    });

    Route::controller(InscriptionController::class)->group(function(){
        Route::get('/inscriptions', 'index')->name('inscription.index');
        Route::post('/activites/{activite}/inscription', 'envoyerDemandeAdhesion')->name('activites.envoyerDemandeAdhesion');
        Route::post('/inscription/store', 'store')->name('inscription.store');
        Route::get('/inscription/show', 'show')->name('inscription.show');


        Route::middleware(PermissionManageInscriptions::class)->group(function () {
            Route::delete('/inscriptions/destroy', 'destroy')->name('inscriptions.destroy');
        });


    });

    Route::controller(CoutInscriptionController::class)->group(function(){
        Route::get('/tableau_inscriptions', 'index')->name('cout.index');
        Route::middleware(PermissionManageCout::class)->group(function(){
            Route::put('/cout/update', 'update')->name('cout.update');
        });
    });

    Route::controller(DemandeInscriptionController::class)->group(function(){
        Route::get('/inscriptions/demandes', 'index')->name('inscriptions.demandes.index');
        Route::middleware(PermissionManageDemandes::class)->group(function(){
            Route::get('/inscriptions/gestionDemandes/{id?}', 'show')->name('inscriptions.demandes.all');
            Route::delete('/inscriptions/demandes/suppression', 'destroy')->name('inscriptions.demandes.suppression');
        });
        Route::delete('/inscriptions/demandes/annulation', 'destroy')->name('inscriptions.demandes.annulation');
    });

    Route::controller(DemandeAdhesionController::class)->group(function(){
        Route::get('/forums/demandes', 'index')->name('forums.demandes.index');
        Route::middleware(PermissionManageDemandes::class)->group(function(){
            Route::get('/forums/gestionDemandes/{id?}', 'show')->name('forums.demandes.all');
            Route::delete('/forums/demandes/suppression', 'destroy')->name('forums.demandes.suppression');
        });
        Route::delete('/forums/demandes/annulation', 'destroy')->name('forums.demandes.annulation');
    });
        // TODO : Add complex routes here and wrap them with permission middlewares
});



Route::controller(FBController::class)->group(function(){
    Route::get('/facebook/page', 'showPosts')->name('facebook.show');
});

require __DIR__.'/auth.php';
