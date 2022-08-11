@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-block">
                    <h4 class="text-dark header-title m-t-0">Agents</h4>
                    <div class="clearfix"></div>
                    <form class="m-t-20" id="AddAgentForm" action="{{ route('agents.store') }}" method="post">
                        <input type="hidden" name="id" value="{{$agent->id}}">
                        <div class="hidden form-alert"></div>

                        <div class="form-group">
                            <label for="">Name* </label>
                            <input type="text" name="name" value="{{ $agent->name }}" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="">Email* </label>
                            <input type="email" name="email" value="{{ $agent->email }}" class="form-control" autocomplete="off">
                        </div>
                        <!--<div class="form-group">
                            <label for="">Password* </label>
                            <input type="text" name="password" value="{{ $agent->password }}" class="form-control" autocomplete="off">
                        </div>-->

                        <div class="form-group">
                            <label for="">Twilio phone Number* </label>

                            <select name="twilio_number" id="" class="form-control">
                                <option value="">Select twilio number</option>
                                @foreach($phone_numbers as $number)
                                    <option @if($agent->twilio_number == $number) selected @endif value="{{$number}}">{{$number}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="">phone* </label>
                            <input type="text" name="phone" value="{{ $agent->phone }}" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="">address </label>
                            <input type="text" name="address" value="{{ $agent->address }}" class="form-control" autocomplete="off">
                        </div>
                        <div class="modal-footer">
                            <a href="{{route('agents.index')}}" class="btn btn-default waves-effect" data-dismiss="modal">Go back</a>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <!-- dataTables JS  -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap.js') }}"></script>
    <script>
        $(document).ready(function(){
            // ajax submit form
            $("#AddAgentForm").submit(function(){
                $('.form-alert').html('');
                var data = new FormData(this);

                $.ajax({
                    url: "<?php  echo route('agents.store'); ?>",
                    data: data,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(result){

                        if(result.message == '1'){
                            swal({
                                title: "Success!",
                                text: "Agent updated successfully!",
                                type: "success"
                            });

                            $('#addAgnetModal').modal('hide');

                            refreshTable();
                        }
                        if(result.err == 1){
                            console.log('ok');
                            $.each( result.msg ,function( index, element ) {
                                $('.form-alert').append('<p class="text-danger">'+element+'</p>');
                            });
                            $('.form-alert').removeClass('hidden');
                        }
                    }
                });
                return false;
            });

        });
    </script>
@endsection