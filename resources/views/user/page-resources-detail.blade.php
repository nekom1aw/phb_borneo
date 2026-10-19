@extends('layouts.user')
@section('title', __('Resources'))
@section('content')
    <livewire:user.page-konten-detail kategori="resources" :id="$id" />
@endsection
