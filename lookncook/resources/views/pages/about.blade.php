@extends('layouts.master')
@section('title', 'About')


@section('content')
    @include('components.banner', [
        'miniTitle' => 'ABOUT-US',
        'title' => 'Our Story',
        'highlight' => 'Food Journey',
        'description' => 'We started our journey with a passion for delicious food and great service.'
    ])
    @include('components.about')


@endsection