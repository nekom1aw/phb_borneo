@extends('layouts.user')
@section('title', __('Blog'))
@section('content')
    <livewire:user.page-konten-detail kategori="blog" :id="$id" />
@endsection
