@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Award</div>
                    <div class="card-body">
                        <form action="/edit-award" method="POST" enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name='id' value="{{ $awards->id }}">
                            <div class="form-group">
                                <label for="first_name">Project</label>
                                <select name="project_id" id="project_id" class="form-control">
                                    <option value="">Choose Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" data-catname="{{ $project->cat_name }}"
                                            <?php if ($awards->project_id == $project->id) {
                                                echo 'selected';
                                            } ?>>
                                            {{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Category</label>
                                <input type="text" class="form-control" id="cat" name="cat" readonly
                                    value="<?php
                                    foreach ($projects as $project) {
                                        if ($awards->project_id == $project->id) {
                                            echo $project->cat_name;
                                        }
                                    }
                                    ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_name">year</label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">Choose Year</option>
                                    <?php 
                                    $inserted_badge_arr = [];
                                    for ($i=2000; $i <= 2040; $i++) { 
                                        $award_arr_demo = [];
                                        $selected = '';
                                        foreach ($badges as $badge) {
                                            if ($badge->year == $i) {
                                                $award_arr_demo[$badge->id] = $badge->title;
                                                if($awards->badge_id == $badge->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        }
                                        $award_arr = json_encode($award_arr_demo);
                                        if($selected == 'selected'){
                                          $inserted_badge_arr = $award_arr_demo;
                                        }
                                    ?>
                                    <option value="<?php echo $i; ?>" data-badge='<?php echo $award_arr; ?>'
                                        <?php echo $selected; ?>>
                                        <?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="first_name">Choose Badge</label>
                                <select name="badge_id" class="form-control" id="badge">
                                    <?php
                                    foreach ($inserted_badge_arr as $id => $badge) {
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php if ($awards->badge_id == $id) {
                                        echo 'selected';
                                    } ?>><?php echo $badge; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function() {
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
