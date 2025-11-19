<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CoutInscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try{
            $file = Storage::disk('public')->files('cout_inscription')[0];
        }catch(\Exception $e){
            $file=NULL;
        }

        return view('inscription/cout', [
            'coutFile' => $file
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(coutInscription $coutInscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(coutInscription $coutInscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        \Log::info('Update tableau data:', $request->all());
        try{
            $validation = Validator::make($request->all(), [
                'image' => 'nullable|image|max:5120',
            ]);
            if ($validation->fails()){
                return response()->json(['errors' => $validation->errors()], 400);
            }

            $validated = $validation->validated();

            $files = Storage::disk('public')->files('cout_inscription');
            try{
            $oldFilePath = $files[0] ?? null;
            } catch (\Exception $e){
                $oldFilePath = null;
            }
            $newFile = $request->file('image');
            $newHash = md5_file($newFile->getRealPath());
            $same=false;
            if ($oldFilePath){
                $oldHash = md5_file(Storage::disk('public')->path($oldFilePath));
                $same = ($oldHash === $newHash);
            }

            if (!$same) {
                $ext = $newFile->getClientOriginalExtension();
                $filename = "tableau_cout." . $ext;
                if ($oldFilePath){
                    Storage::disk('public')->delete($oldFilePath);
                }
                Storage::disk('public')->putFileAs('cout_inscription', $newFile, $filename);
            }
             $newPath = 'storage/cout_inscription/' . $filename;
            return response()->json([
                'message' => 'Tableau mis à jour avec succès.',
                'newImage' => asset($newPath)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la mise à jour du tableau: ' . $e->getMessage()], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(coutInscription $coutInscription)
    {
        //
    }
}
