@extends('layouts.error')

@section('title', $title ?? 'Erreur')
@section('code', $code ?? 'Erreur')

@section('message')
    {{ $message ?? "Une erreur inattendue s'est produite." }}
@endsection
