<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

// Rediriger la page d'accueil vers la liste des contacts
Route::get('/', function () {
    return redirect()->route('contacts.index');
});

Route::get('/contacts/{id}', [ContactController::class, 'show']);

// Route pour afficher la liste des contacts
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

// Route pour afficher le formulaire de création de contact
Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');

// Route pour sauvegarder un nouveau contact
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');

// Route pour afficher les détails d'un contact
Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');

// Route pour afficher le formulaire d'édition d'un contact
Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');

// Route pour mettre à jour un contact
Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');

// Route pour supprimer un contact
Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
