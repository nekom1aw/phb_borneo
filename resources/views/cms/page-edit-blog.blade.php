@extends('layouts.admin')
@section('title', 'Ubah Blog')
@section('content')
    <livewire:cms.konten-form kategori="blog" :id="$id" />
@endsection
