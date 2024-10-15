@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Award</div>
                    <div class="card-body">
                        <form action="{{ route('save-award') }}" method="POST" enctype='multipart/form-data'>
                            @csrf
                            <div class="form-group">
                                <label for="first_name">Project</label>
                                <select name="project_id" id="project_id" class="form-control selectpicker"
                                    data-show-subtext="true" data-live-search="true">
                                    <option value="">Choose Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" data-catname="{{ $project->cat_name }}">
                                            {{ $project->projektname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Category</label>
                                <input type="text" class="form-control" id="cat" name="cat" readonly>
                            </div>
                            <div class="form-group">
                                <label for="last_name">year</label>
                                <select name="year" id="year" class="form-control" style="height: 34px">
                                    <option value="">Choose Year</option>
                                    <?php for ($i=2000; $i <= 2040; $i++) {
                                        $awards = [];
                                        foreach ($badges as $badge) {
                                            if ($badge->year == $i) {
                                                $awards[$badge->id] = $badge->title;
                                            }
                                        }
                                        $awards = json_encode($awards);
                                    ?>
                                    <option value="<?php echo $i; ?>" data-badge='<?php echo $awards; ?>'><?php echo $i; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="first_name">Choose Badge</label>
                                <select name="badge_id" class="form-control" id="badge" style="height: 34px">
                                    <option value="">Choose Badge</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css"
        rel="stylesheet" />
    <script>
        jQuery(document).ready(function() {
            $("button").removeClass("btn-default");
            jQuery(document).on('change', '#project_id', function() {
                let catName = jQuery('option:selected', this).attr('data-catname')
                jQuery('#cat').val(catName)
            });
            jQuery(document).on('change', '#year', function() {
                let awards = jQuery.parseJSON(jQuery('option:selected', this).attr('data-badge'))
                jQuery('#badge').empty()
                jQuery.each(awards, function(key, value) {
                    jQuery('#badge').append('<option value="' + key + '">' + value + '</option>')
                });
            });
        });
    </script>
@endsection
