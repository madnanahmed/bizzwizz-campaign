@extends('layouts.app')
@section('content')
<!--  stats -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <h2>User Agents</h2>
            <div>
                <button data-toggle="tooltip" data-title="Refresh table record" class="m-b-5 m-l-5 pull-right btn btn-primary refreshTbl"><i class="fa fa-refresh"></i></button>
                <button data-toggle="modal" data-target="#addAgnetModal" class="btn btn-default pull-right"><i class="fa fa-user-plus"></i> Add New </button>
            </div>

            <div class="md-clear"><br><br></div>
            <div class="table-responsive">
                <table id="load_datatable" class="table table-striped table-bordered add-manage-table table demo footable-loaded footable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Twilio Number</th>

                        <th>Sheets</th>
                        <th>Dated</th>
                        <th width="180">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="4">No Record found yet.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
    <!-- create  modal -->
    <div id="addAgnetModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="font-icon-close-2"></i>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add Agent</h4>
                </div>

                <form id="AddAgentForm" action="{{ route('search-number') }}" method="post">
                    <div class="modal-body">

                        <ol class="hidden form-alert"></ol>
                        <div class="form-group">
                            <label for="">Name* </label>
                            <input type="text" name="name" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="">Email* </label>
                            <input type="email" name="email" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="">Password* </label>
                            <input type="text" name="password" class="form-control" autocomplete="off" required>
                        </div>

                        <div class="form-group">
                            <label for="">Twilio phone Number* </label>

                            <select name="twilio_number" id="" class="form-control" required>
                                @foreach($phone_numbers as $number)
                                <option value="{{$number->number}}">{{$number->number}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">phone* </label>
                            <input type="text" name="phone" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="">address </label>
                            <input type="text" name="address" class="form-control" autocomplete="off">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('script')

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
                                text: "Agent added successfully!",
                                type: "success"
                            });

                            $('#addAgnetModal').modal('hide');

                            refreshTable();
                        }
                        if(result.err == 1){
                            console.log('ok');
                            $.each( result.msg ,function( index, element ) {
                                $('.form-alert').append('<li class="text-danger">'+element+'</li>');
                            });
                            $('.form-alert').removeClass('hidden');
                        }
                    }
                });
                return false;
            });

            $('#load_datatable').DataTable({
                "pageLength":25,
                //"order": [[3, 'desc']],
                processing: true,
                serverSide: true,
                "initComplete": function (settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
                },
                ajax: "{{url('agents-list')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'twilio_number', name: 'twilio_number'},
                    {data: 'total_sheets', name: 'total_sheets'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                    //{ data: 'updated_at', name: 'updated_at' }
                ]
            });
        });
    </script>
@endsection