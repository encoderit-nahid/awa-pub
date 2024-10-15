@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Badge</div>
                    <div class="card-body">
                        <form action="/save-badge" method="POST" enctype='multipart/form-data'>
                            @csrf
                            <div class="form-group">
                                <label for="cat_id">Choose Category</label>
                                <select name="cat_id" id="cat_id" class="form-control">
                                    @foreach ($cats as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Title</label>
                                <input type="text" class="form-control" id="last_name" name="title">
                            </div>
                            <div class="form-group">
                                <label for="last_name">year</label>
                                <select name="year" id="" class="form-control">
                                    <?php for ($i=2000; $i <= 2040; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($i == date('Y')) {
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
                                <img src="" alt="" class="preview-image" style="width: 100px;">
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
