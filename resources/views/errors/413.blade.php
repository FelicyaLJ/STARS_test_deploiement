@extends('layouts.error')

@section('title', 'Données trop volumineuses')
@section('code', '413')
@section('message', "La requête dépasse la taille maximale autorisée.")
