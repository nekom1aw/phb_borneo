@extends('layouts.user')
@section('title', __('Our Work'))
@section('content')
    <livewire:user.page-konten-detail kategori="photo" :id="$id" />
@endsection
