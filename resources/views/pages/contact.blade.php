@extends('layouts.master')
@section('title', 'Contact')


@section('content')
    @include('components.banner', [
        'miniTitle' => 'CONTACT US',
        'title' => 'Get In Touch',
        'highlight' => 'We’re Here to Help',
        'description' => 'Have questions, feedback, or want to talk with us? Our team is ready to assist you anytime.'
    ])
    @include('components.contactus')
    @include('components.review_form')
@endsection
    


