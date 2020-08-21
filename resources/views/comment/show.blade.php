@extends('layouts.app')

@section('content')

<div id="app">
    <form>
        <input type="hidden" name="blog_id" class="hide" id="blog_id" value="{{$blog_id}}">
        <input type="hidden" name="user" class="hide" id="user" value="{{Auth::id()}}">
    </form>

    <show-comment :blog_id="{{$blog_id}}"></show-comment>
</div>


@endsection