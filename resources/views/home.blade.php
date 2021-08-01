@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mt-4">Lista Twoich ofert obozowych</h2>
        <div id="app">
            <camp-list></camp-list>
        </div>
        <a class="float-right mt-5 btn-lg btn btn-success" href="/create-camp">Dodaj nową ofertę obozową</a>
    </div>
@endsection

@section('style')
    <style>
        .overflow-auto{
            max-height: 70vh;
        }
    </style>
@endsection

