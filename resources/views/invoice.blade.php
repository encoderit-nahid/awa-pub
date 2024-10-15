@extends('layouts.app')

@section('content')
    @if (session()->has('alert-success'))
        <div class="alert alert-success">
            {{ session()->get('alert-success') }}
        </div>
    @endif


    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <form action="{{ url('/invoice') }}" method="get">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="form-group">
                            <label for="sel1">Select Category:</label>
                            <select class="form-control" id="sel1" name="search_category" style="height: 30px;">
                                <option>All</option>
                                @foreach ($all_cats as $cat)
                                    <option value="{{ $cat->id }}" @if ($cat->id == request('search_category')) selected @endif>
                                        {{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <input style="margin-top: 3px;" type="text" placeholder="Name or email" aria-label="Search"
                                aria-describedby="basic-addon2" name="search" class="form-control"
                                value="{{ request('search') }}">
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-secondary">Search</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Invoice Download</div>

                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Download Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $single_user)
                                    <tr>
                                        <td>{{ $single_user->id }}</td>
                                        <td>{{ $single_user->vorname }} {{ $single_user->name }}</td>
                                        <td>{{ $single_user->email }}</td>
                                        <td> <a href="{{ url('/downlaod/pdf/' . $single_user->id) }}" download="download">
                                                <button class="btn btn-primary">Download</button></a> <button
                                                class="btn btn-primary send" data-id="{{ $single_user->id }}"
                                                data-email="{{ $single_user->email }}"
                                                data-name="{{ $single_user->vorname }} {{ $single_user->name }}">Sending
                                                Invoice</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-12">
                                {{ $users->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- medium modal -->
    <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span><b>Send Email with invoice</b></span>
                    <span style="text-align: center; margin: auto; color: green; font-weight: bold;"
                        id="msg">Successfully mail sent</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="mediumBody">
                    <div style="margin: 5px; padding: 5x">
                        <input type="hidden" class="form-control user_id" name="user_id" aria-label="ID" value=""
                            readonly aria-describedby="basic-addon1">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">From</span>
                            </div>
                            <input type="text" class="form-control from_user" placeholder="from_user@example.com"
                                name="form" aria-label="From" value="office@austrianweddingaward.at" readonly
                                aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon2">To</span>
                            </div>
                            <input type="text" class="form-control to_user" placeholder="to_user@example.com"
                                name="to" aria-label="To" value="" readonly aria-describedby="basic-addon1">
                            <input type="hidden" class="form-control to_name" name="to_name" aria-label="To" value=""
                                readonly aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">Subject</span>
                            </div>
                            <input type="text" class="form-control subject"
                                placeholder="Rechnung Austrian Wedding Award [name of the client]" name="subject"
                                aria-label="Subject" value="" readonly aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Email Templates</label>
                            <select class="status-change-dd form-control" id="template">
                                <option value="0">Template without attachment</option>
                                <option value="1">Template with attachment</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Email Body</span>
                            </div>
                            <textarea class="form-control template" name="template" aria-label="With textarea" readonly>
#Liebe/r Einreicher/in,
Vielen Dank, dass du beim Award teilgenommen hast. Finde bitte anbei die Rechnung für deine Einreichung/en. 
Vielen Dank für dein Vertrauen.
Dein AWA - Team
E: office@austrianweddingaward.at
W: www.austrianweddingaward.at
                            </textarea>
                            <textarea class="form-control template_1" name="template_1" hidden="hidden" aria-label="With textarea">
#Liebe/r Einreicher/in,
Vielen Dank, dass du beim Award teilgenommen hast. Finde bitte anbei die Rechnung für deine Einreichung/en. 
Vielen Dank für dein Vertrauen.
Dein AWA - Team
E: office@austrianweddingaward.at
W: www.austrianweddingaward.at
                            </textarea>
                            <textarea class="form-control template_2" name="template_2" hidden="hidden" aria-label="With textarea">
#Liebe/r Einreicher/in,
Vielen Dank, dass du beim Award teilgenommen hast. Finde bitte anbei die Rechnung für deine Einreichung/en. 
Vielen Dank für dein Vertrauen.
Dein AWA - Team
E: office@austrianweddingaward.at
W: www.austrianweddingaward.at
                            </textarea>
                        </div>
                        <div class="formbold-input-file">
                            <div class="formbold-filename-wrapper">
                                <label for="upload" class="formbold-input-label">
                                    <svg width="12" height="12" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_1670_1531)">
                                            <path
                                                d="M12.3568 6.4644L7.64349 11.1786C7.5639 11.2554 7.50041 11.3474 7.45674 11.4491C7.41307 11.5507 7.39008 11.6601 7.38912 11.7707C7.38815 11.8814 7.40924 11.9911 7.45114 12.0935C7.49304 12.1959 7.55492 12.289 7.63316 12.3672C7.71141 12.4455 7.80445 12.5073 7.90686 12.5492C8.00928 12.5912 8.11901 12.6122 8.22966 12.6113C8.34031 12.6103 8.44966 12.5873 8.55133 12.5436C8.653 12.5 8.74495 12.4365 8.82182 12.3569L13.536 7.64356C14.0049 7.17468 14.2683 6.53875 14.2683 5.87565C14.2683 5.21255 14.0049 4.57661 13.536 4.10773C13.0671 3.63885 12.4312 3.37544 11.7681 3.37544C11.105 3.37544 10.469 3.63885 10.0002 4.10773L5.28599 8.8219C4.89105 9.20701 4.57652 9.6667 4.36062 10.1743C4.14473 10.6819 4.03178 11.2274 4.02832 11.779C4.02487 12.3306 4.13097 12.8774 4.34049 13.3877C4.55 13.8979 4.85876 14.3615 5.24884 14.7516C5.63892 15.1416 6.10256 15.4503 6.61287 15.6597C7.12318 15.8692 7.67 15.9752 8.2216 15.9717C8.77321 15.9681 9.31862 15.8551 9.82621 15.6391C10.3338 15.4232 10.7934 15.1086 11.1785 14.7136L15.8927 10.0002L17.071 11.1786L12.3568 15.8927C11.8151 16.4344 11.172 16.8641 10.4643 17.1573C9.75649 17.4505 8.99791 17.6014 8.23182 17.6014C7.46574 17.6014 6.70716 17.4505 5.99939 17.1573C5.29162 16.8641 4.64853 16.4344 4.10682 15.8927C3.56512 15.351 3.13542 14.7079 2.84225 14.0002C2.54908 13.2924 2.39819 12.5338 2.39819 11.7677C2.39819 11.0016 2.54908 10.2431 2.84225 9.5353C3.13542 8.82753 3.56512 8.18443 4.10682 7.64273L8.82182 2.9294C9.60767 2.17041 10.6602 1.75043 11.7527 1.75992C12.8451 1.76942 13.8902 2.20762 14.6627 2.98015C15.4353 3.75269 15.8735 4.79774 15.883 5.89023C15.8925 6.98271 15.4725 8.03522 14.7135 8.82106L10.0002 13.5369C9.76794 13.7691 9.49226 13.9532 9.18887 14.0788C8.88548 14.2045 8.56032 14.2691 8.23195 14.2691C7.90357 14.269 7.57843 14.2043 7.27507 14.0786C6.97171 13.9529 6.69607 13.7687 6.46391 13.5365C6.23174 13.3043 6.04759 13.0286 5.92196 12.7252C5.79633 12.4218 5.7317 12.0966 5.73173 11.7683C5.73177 11.4399 5.79649 11.1148 5.92219 10.8114C6.04788 10.508 6.2321 10.2324 6.46432 10.0002L11.1785 5.28606L12.3568 6.4644Z"
                                                fill="#07074D"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1670_1531">
                                                <rect width="20" height="20" fill="white"></rect>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    Attach files
                                </label>
                                <span class="formbold-filename">
                                    AWA-Invoice-2025.pdf
                                </span>
                            </div>
                        </div>
                        <button style="margin-top: 3px; float: right" type="button"
                            class="btn btn-primary send-mail">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="ajax_token" value="{{ csrf_token() }}">
@endsection
<style type="text/css">
    textarea.template {
        text-align: left !important;
        min-height: 160px;
        padding-left: 5px;
        padding-top: 5px;
        overflow: hidden;
        resize: none;
    }

    .formbold-input-file {
        margin-top: 20px;
    }

    .formbold-filename-wrapper {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }

    .formbold-filename {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        line-height: 16px;
        color: gray;
        cursor: pointer;
        border: 1px solid #536387;
        border-radius: 10px;
        padding: 3px 15px;
        background: #f8f8f8;
        width: 180px;
    }

    .formbold-filename svg {
        cursor: pointer;
    }
</style>
<script src="https://code.jquery.com/jquery-1.5.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var token = $('input[name="ajax_token"]').val();
        $('.formbold-input-file').hide();
        $('#msg').hide();
        $(document).on('click', '.send', function(event) {
            let name = $(this).data("name");
            let email = $(this).data("email");
            let id = $(this).data("id");
            $('.to_user').val(email);
            $('.to_name').val(name);
            $('.user_id').val(id);
            $('.subject').val('Rechnung Austrian Wedding Award ' + name);
            $('#mediumModal').modal("show");
        });
        $(document).on('change', '#template', function(event) {
            if ($('#template').val() == 1) {
                $('.formbold-input-file').show();
                $(".template").text($(".template_2").val());
            } else {
                $('.formbold-input-file').hide();
                $(".template").text($(".template_1").val());
            }
        });
        $(document).on('click', '.send-mail', function(event) {
            $(".send-mail").attr("disabled", true);
            let id = $('.user_id').val();
            let name = $('.to_name').val();
            let from = $('.from_user').val();
            let to = $('.to_user').val();
            let subject = $('.subject').val();
            let template = $('#template').val();
            let body = $('.template').val();
            $.ajax({
                url: "{{ url('/send-invoice') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data: {
                    'id': id,
                    'name': name,
                    'from': from,
                    'to': to,
                    'subject': subject,
                    'template': template,
                    'body': body
                },
                success: function(response) {
                    console.log(response);
                    $('#msg').show();
                    $("#msg").fadeOut(3000);
                    $('.formbold-input-file').hide();
                    $("#template").val("0");
                    $(".send-mail").removeAttr("disabled");
                    $('#mediumModal').modal("hide");
                }
            });
        });
    });
</script>
