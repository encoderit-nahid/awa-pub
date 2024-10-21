@extends('layouts.app')

@section('content')
    <div class="container">
        <!--- Success message --->
        @if(session()->has('success'))
            <div class="alert alert-success" style="color: green">
                {{ session()->get('success') }}
            </div>
            <br>
        @endif


        <!---- Error message --->
        @if(session()->has('error'))
            <div class="alert alert-danger" style="color: red">
                {{ session()->get('error') }}
            </div>
        @endif

        <form action="{{ route('assign-jury-category') }}" method="POST">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-6">
                    <div>
                        <label for="category_id">Category:</label>
                    </div>
                    <select
                            class="selectpicker"
                            data-live-search="true"
                            style="width: 100% !important; font-size: 16px!important;"
                            id="category_id"
                            name="cat_id"
                    >
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    @if($category->id == request('cat_id', null)) selected @endif
                            >{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <div>
                        <label for="jury_id">Jury:</label>
                    </div>
                    <select
                            class="selectpicker"
                            multiple
                            data-live-search="true"
                            style="width: 100%; font-size: 16px!important;"
                            id="jury_id"
                            name="jury_ids[]"
                    >
                        @foreach($juries as $jury)
                            <option value="{{ $jury->id }}" @if(in_array($jury->id, $juryIds ?? [])) selected @endif>{{ $jury->name }} | {{ $jury->email }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
            <div>
                <button type="submit" class="btn btn-primary">Assign</button>
            </div>
        </form>
    </div>
@endsection


@section('additional-js')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#category_id').on('change', function () {
                let categoryId = $(this).val();

                if (categoryId) {
                    const url = "{{ route('get-jury-category') }}";
                    window.location.href = url + '?cat_id=' + categoryId;
                }
            });
        });
    </script>
@endsection