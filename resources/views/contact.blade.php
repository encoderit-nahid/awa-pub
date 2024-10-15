@extends('layouts.vote-app')

@section('content')

@if(session()->has('alert-success'))
    <div class="alert alert-success">
        {{ session()->get('alert-success') }}
    </div>
@endif
<style type="text/css">
</style>
<input type = "hidden" name = "ajax_token" value = "{{csrf_token()}}">
    <div class="message-container mt-4 pt-4">
        Kontakt<br/><br/>

        <a href="mailto:office@austrianweddingaward.at">office@austrianweddingaward.at</a><br/>
        <a href="http://www.austrianweddingaward.at" target="_blank">http://www.austrianweddingaward.at</a><br/>

    </div>
@endsection
