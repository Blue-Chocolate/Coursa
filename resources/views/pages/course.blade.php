@extends('layouts.app')
@section('title', $course->title)
@section('content')
    <livewire:course.course-detail :course="$course" />
@endsection