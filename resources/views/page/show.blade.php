@extends('layouts.public')

@section('title', $page->title)

@section('content')
    <h1>{{ $page->title }}</h1>
    <hr>
    <div>
        {!! $page->content !!}
    </div>
@endsection