<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private function pruneOldBackgrounds(string $default, int $keep = 5): void
    {
        $files = collect(Storage::disk('public')->files('bg'))
            ->filter(fn($path) => basename($path) !== $default)
            ->sortByDesc(fn($path) => Storage::disk('public')->lastModified($path))
            ->values();

        $files->slice($keep)->each(function ($oldFile) {
            Storage::disk('public')->delete($oldFile);
        });
    }

    public function edit()
    {
        $background = Settings::get('background_image', 'default.jpg');
        $existingBackgrounds = collect(Storage::disk('public')->files('bg'))
            ->map(fn($path) => basename($path))
            ->filter(fn($file) => Str::endsWith(strtolower($file), ['.jpg', '.jpeg', '.png', '.webp']))
            ->values();

        return view('admin.settings', compact('background', 'existingBackgrounds'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'background' => 'nullable|image|max:4096',
            'existing_background' => 'nullable|string',
        ]);

        $current = Settings::get('background_image');
        $default = 'default.jpg';

        // Cas 1: Nouveau fond d'écran
        if ($request->hasFile('background')) {
            $path = $request->file('background')->store('bg', 'public');
            $filename = basename($path);

            Settings::set('background_image', $filename);

            $this->pruneOldBackgrounds($default, 5);

            return back()->with('success', 'Paramètres modifiés avec succès.');
        }

        // Cas 2: Choisir un fond d'écran récent
        if ($request->filled('existing_background')) {
            $filename = basename($request->existing_background);

            if ($filename === $default || Storage::disk('public')->exists("bg/$filename")) {
                Settings::set('background_image', $filename);
                return back()->with('success', 'Paramètres modifiés avec succès.');
            }

            return back()->with('error', 'Selected background not found.');
        }

        return back()->with('success', 'Aucun changement effectué.');
    }


}
