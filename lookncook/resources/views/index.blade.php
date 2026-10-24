@extends('layouts.master')
@section('title', 'Home')


@section('content')

@include('components.hero')

@include('components.categories')

@include('components.about')

@include('components.section_banner')


@include ('components.availability')


@include('components.contactus')
@include('components.testimonial')



@endsection