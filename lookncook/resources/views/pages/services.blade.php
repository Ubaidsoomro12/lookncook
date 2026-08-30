@extends('layouts.master')

@section('title', 'Services')

@section('content')

    @include('components.banner', [
        'miniTitle' => 'OUR SERVICES',
        'title' => 'We Offer',
        'highlight' => 'Best Food Experience',
        'description' => 'We provide high-quality food services including delicious meals, catering solutions, event food arrangements, and customized menu options for every occasion.'
    ])

    @include ('components.services')
    @include ('components.availability')


@endsection