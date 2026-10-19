@extends('layouts.user')
@section('title', __('Event'))
@section('content')
    <livewire:user.page-konten-detail kategori="event" :id="$id" />
@endsection
