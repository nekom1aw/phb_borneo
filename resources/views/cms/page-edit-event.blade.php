@extends('layouts.admin')
@section('title', 'Ubah Kegiatan')
@section('content')
    <livewire:cms.konten-form kategori="event" :id="$id" />
@endsection
