@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-block">
            <div class="card-header card-header-text" data-background-color="rose">
                <h3 class="card-title"> <i class="fa fa-download"></i> Contacts</h3>
            </div>
            <div class="card-content">
                <br>
                <a download href="{{asset('assets/example.csv')}}" class="pull-right btn btn-sm btn-rose"><i class="fa fa-download"></i> Download sample file</a>
                <br>

                <div class="card-content">

                    <section class="tabs-section">
                        <div class="tabs-section-nav tabs-section-nav-inline">
                            <ul class="nav" role="tablist">
                                <li class="active show nav-item">
                                    <a class="nav-link active show" href="#importTab" role="tab" data-toggle="tab" aria-selected="true">Contacts</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#addTab" class="nav-link" data-toggle="tab" role="tab">Add Contacts</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#manageListTab" class="nav-link" data-toggle="tab" role="tab">Manage list</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#settingsTab" class="nav-link" data-toggle="tab" role="tab">Settings</a>
                                </li>
                                <li></li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="importTab">
                            <div class="file_error"></div>

                            <div class="col-md-6" style="background: #ebebeb; padding-bottom: 10px; margin-top: 5px">
                                <h3> Add Contacts via csv </h3>
                                <button type="button" class="btn btn-primary btn-xs pull-right m-b-10 m-t-10" title="Add new List" data-toggle="tooltip" onclick="addCategory()" ><i class="fa fa-plus"></i> List<div class="ripple-container"></div></button>

                                <form action="" id="importCsvForm">
                                    <input type="hidden" name="type" value="import">
                                    <div class="form-group">
                                        <select name="list" id="listSelect" data-title="select list" required class="selectpicker" data-live-search="true" data-style="select-with-transition">
                                            @foreach($list as $value)
                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="">
                                        <label for="">Select CSV file </label>
                                        <input type="file" name="file" class="btn btn-rose" accept=".csv">
                                        <small><i class="fa fa-warning text-danger"></i> CSV file type is acceptable only. </small>
                                    </div>

                                    <div class=" m-t-30">
                                        <button type="submit"  class="btn btn-success btn-update"> <i class="fa fa-download"></i> Import </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <div class="tab-pane" id="addTab">

                            <div class="col-md-6" style="background: #ebebeb; padding-bottom: 10px; margin-top: 5px">
                                <h3> Add Contacts </h3>
                                <form action="" id="addContactForm">
                                    <input type="hidden" name="type" value="add">
                                    <div class="form-group">
                                        <label for="">Name:</label>
                                        <input type="text" autocomplete="off" name="name" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Email:</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Phone:</label>
                                        <input type="text" name="phone" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">select contact list:</label>
                                        <select name="list" id="listSelect" data-title="select list" required class="selectpicker" data-live-search="true" data-style="select-with-transition">
                                            @foreach($list as $value)
                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class=" m-t-30">
                                        <button type="submit"  class="btn btn-success btn-update"> <i class="fa fa-save"></i> Save </button>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <div class="tab-pane" id="manageListTab">
                            <h3> Contact Lists </h3>
                            <div class="material-datatables">
                                <table id="load_datatable_list" class="table table-colored table-inverse table-hover table-striped" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>List Title</th>
                                        <th>Created At</th>
                                        <th>Action</th>
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

                        <div class="tab-pane" id="settingsTab">
                            <div class="file_error"></div>

                            <div class="col-md-6" style="background: #ebebeb; padding-bottom: 10px; margin-top: 5px">
                                <h3>Email Settings </h3>

                                <form id="settingsForm">
                                    <input type="hidden" name="id" value="{{ isset($email_settings->id)? $email_settings->id : '' }}" id="email_setting_id">
                                    <div class="form-group">
                                        <label for="">From Email</label>
                                        <input type="text" name="from_email" autocomplete="off" class="form-control" value="{{ isset($email_settings->from_email)? $email_settings->from_email : '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="">From Name</label>
                                        <input type="text" name="from_name" autocomplete="off" class="form-control" value="{{ isset($email_settings->from_name)? $email_settings->from_name : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Reply To</label>
                                        <input type="text" name="reply_to" autocomplete="off" class="form-control" value="{{ isset($email_settings->reply_to)? $email_settings->reply_to : '' }}">
                                    </div>

                                    <div class=" m-t-30">
                                        <button type="submit"  class="btn btn-success btn-update"> <i class="fa fa-save"></i> Save </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-block">
                    <h4>Contacts</h4>
                    <button class="btn btn-xs btn-rose pull-right m-b-5" data-toggle="tooltip" title="Refresh Record" onclick="refreshTable()"><i class="fa fa-refresh"></i> </button>
                    <br><br>

                <div class="card-content">
                    <div class="material-datatables m-t-20">
                        <table id="load_datatable" class="table table-colored table-inverse table-hover table-striped m-t-20" style="width: 100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>List name</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Action</th>
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
    </div>


    <!-- Add new category modal -->
    <div id="add_listModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">Add New List</h4>
                </div>
                <form method="post" id="ListForms" action="#">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="add_list">
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-md-2 control-label">List Name:</label>
                            <div class="col-md-8">
                                <input type="text" autocomplete="off" name="title" id="listName" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success replace_now">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- edit list -->
    <div id="edit_listModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">Edit List</h4>
                </div>
                <form method="post" id="editListForms" action="#">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="update_list">
                    <input type="hidden" id="editId" name="id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">List Name:</label>
                            <div class="col-md-8">
                                <input type="text" autocomplete="off" name="title" id="editlistName" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success replace_now">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        function editList(e) {
            var id = $(e).attr('data-id');
            if(id!=''){
                $.get('load_list',
                    {id:id},
                    function (data) {
                    if(data.msg == 1){
                        $('#editlistName').val(data.rec);
                        $('#editId').val(data.id);
                        $('#edit_listModal').modal('show');
                    }
                });
            }
        }

        // load add category modal
        function addCategory(){
            $("#add_listModal").modal('show');
        }
        $(document).ready(function () {

            // edit list
            $('#editListForms').submit(function () {
                var data = new FormData(this);
                $('#loading').show();
                $.ajax({
                    url: "<?php  echo route('contact-list.store'); ?>",
                    data: data,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(result){
                        if(result.msg==1){
                            $('#load_datatable_list').DataTable().ajax.reload();
                            $('#edit_listModal').modal('hide');

                            swal( 'Success!', 'List Edited successfully!', 'success');
                        }else{
                            swal( 'Success!', 'To update make change!', 'success');

                        }
                        window.location.href='';
                    }
                });
                return false;
            });

            $('#ListForms').submit(function () {
                var data = new FormData(this);

                $('#loading').show();

                $.ajax({
                    url: "<?php  echo route('contact-list.store'); ?>",
                    data: data,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(result){
                        var category = $('#listName').val();
                        $('.selectpicker').append('<option selected value="'+result.list_id+'">'+category+'</option>').selectpicker('refresh');
                        $("#add_listModal").modal('hide');
                        window.location.href='';
                    }
                });
                return false;
            });

            /*  */
            $('#settingsForm').submit(function () {
                var data = new FormData(this);


                $.ajax({
                    url: "<?php  echo route('save-email-settings'); ?>",
                    data: data,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(result){
                        if(result.msg ==1) {
                            $("#email_setting_id").val(result.id);
                            swal( 'Success!', 'Email settings saved!', 'success');

                        }
                    }
                });
                return false;
            });

           $('#importCsvForm').submit(function () {
               $('.file_error').html('');
               $('#loading').show();
               var data = new FormData(this);
               $.ajax({
                   url: "<?php  echo route('contact-list.store'); ?>",
                   data: data,
                   contentType: false,
                   processData: false,
                   type: 'POST',
                   success: function(result){
                    if(result.msg == 1){
                        swal( 'Success!', 'Contacts imported successfully.', 'success');
                        refreshTable();
                        $('#importCsvForm')[0].reset();
                    }else{
                        $.each(result.error, function( index, element ) {
                            $('.file_error').append('<li class="text-danger">'+element+'</li>');
                        });
                    }
                       window.location.href='';
                   }
               });
               return false;
           });

           /*  add contact */
            $('#addContactForm').submit(function () {
                $('.file_error').html('');
                $('#loading').show();
                var data = new FormData(this);
                $.ajax({
                    url: "<?php  echo route('contact-list.store'); ?>",
                    data: data,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(result){
                        if(result.msg == 1){
                            swal( 'Success!', 'Contacts imported successfully.', 'success');

                            refreshTable();
                            $('#addContactForm')[0].reset();
                        }else{
                            $.each(result.error, function( index, element ) {
                                $('.file_error').append('<li class="text-danger">'+element+'</li>');
                            });
                        }
                        window.location.href='';
                    }
                });
                return false;
            });


                $('#load_datatable').DataTable({
                    "pageLength":25,
                    "order": [[0, 'desc']],
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    "initComplete": function (settings, json) {
                    },

                    ajax: "{!! Route('load-contacts') !!}",
                    columns: [
                        {data: 'id', name: 'contacts.id'},
                        {data: 'title', name : 'contacts_list.title'},
                        {data: 'name', name : 'name'},
                        {data: 'email', name : 'email'},
                        {data: 'phone', name : 'phone'},

                        {data: 'action', name : 'action'},
                        //{ data: 'updated_at', name: 'updated_at' }
                    ]
                });



                $('#load_datatable_list').DataTable({
                    "pageLength":25,
                    "order": [[0, 'desc']],
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    "initComplete": function (settings, json) {
                    },

                    ajax: "{!! Route('load-lists') !!}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'title', name : 'title'},
                        {data: 'created_at', name : 'created_at'},
                        {data: 'action', name : 'action'},
                        //{ data: 'updated_at', name: 'updated_at' }
                    ]
                });
        });

    </script>
@endsection