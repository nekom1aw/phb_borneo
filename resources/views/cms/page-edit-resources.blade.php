@extends('layouts.admin')
@section('title', 'Ubah Publikasi')
@section('content')
    <livewire:cms.konten-form kategori="resources" :id="$id" />
@endsection
