@extends('components.layouts.app')
@section('title', $lesson->title)
@section('content')
    <livewire:lesson.lesson-player :lesson="$lesson" :course="$course" />
@endsection