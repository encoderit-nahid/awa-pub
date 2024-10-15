@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Badge</div>
                    <div class="card-body">
                        <form action="/edit-badge" method="POST" enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name="id" value="{{ $badges->id }}">
                            <div class="form-group">
                                <label for="first_name">Choose Category</label>
                                <select name="cat_id" id="" class="form-control">
                                    @foreach ($cats as $cat)
                                        <option value="{{ $cat->id }}" <?php if ($badges->cat_id == $cat->id) {
                                            echo 'selected';
                                        } ?>>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Title</label>
                                <input type="text" class="form-control" id="last_name" name="title"
                                    value="{{ $badges->title }}">
                            </div>
                            <div class="form-group">
                                <label for="last_name">year</label>
                                <select name="year" id="" class="form-control">
                                    <?php for ($i=2000; $i <= 2040; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($i == $badges->year) {
                                        echo 'selected';
                                    } ?>><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Image</label>
                                <input type="file" class="form-control" name="image" id=""
                                    onchange="readURL(this);">
                            </div>
                            <div style="margin:20px 0">
                                <img src="{{ url("storage/". $badges->image) }}" alt="" class="preview-image" style="width: 100px;">
                            </div>
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.preview-image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
