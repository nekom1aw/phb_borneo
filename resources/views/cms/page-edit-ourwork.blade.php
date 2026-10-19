@extends('layouts.admin')
@section('title', 'Ubah Karya Kami')
@section('content')
    <livewire:cms.konten-form kategori="photo" :id="$id" />
@endsection
