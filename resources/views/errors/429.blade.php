@extends('layouts.error')

@section('title', 'Trop de requêtes')
@section('code', '429')

@section('message')
    Vous avez effectué trop de requêtes en peu de temps.
    Merci de patienter quelques instants avant de réessayer.
@endsection
