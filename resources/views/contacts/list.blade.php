@extends('layouts.app')
@section('title', 'Users')
@section('content')
    <div class="row">
        <div class="col-md-12">
                <div class="page-title-box">

                    <ol class="breadcrumb p-0 m-0">
                        <li> <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a></li>
                        <li class="active"> Contacts </li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="col-xs-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                    <div class="card">
                        <div class="card-block">

                    <div class="row">
                        <div class="col-xs-12 bg-white">

                            <div class="table-responsive">

                                <table id="load_datatable" class="table table-colored table-inverse table-hover table-striped table-bordered">
                                <thead class="bg-rose text-white">
                                <tr>
                                    <th>#</th>
                                    <th>list_name</th>
                                    <th>account_id</th>
                                    <th>account_title</th>
                                    <th>type</th>
                                    <th>list_id</th>
                                    <th>contact_id</th>
                                    <th>name</th>
                                    <th>email</th>
                                    <th>phone</th>
                                    <th>ip_address</th>
                                    <th>area_code</th>
                                    <th>country</th>
                                    <th>region</th>
                                    <th>longitude</th>
                                    <th>latitude</th>
                                    <th>ribed_at</th>
                                    <th>status</th>

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


    </div>
    <script>

        $(document).ready(function(){

            $('#load_datatable').DataTable({
                "pageLength":25,
                "order": [[0, 'desc']],
                processing: true,
                serverSide: true,
                "initComplete": function (settings, json) {

                },
                "drawCallback": function () {
                    //table_draw();
                    $("[name='status']").bootstrapSwitch();
                    changeStatus();
                },


                ajax: "{!! Route('load-contacts') !!}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'list_name', name : 'list_name'},
                    {data: 'account_id', name : 'account_id'},
                    {data: 'account_title', name : 'account_title'},
                    {data: 'type', name : 'type'},
                    {data: 'list_id', name : 'list_id'},
                    {data: 'contact_id', name : 'contact_id'},
                    {data: 'name', name : 'name'},
                    {data: 'email', name : 'email'},
                    {data: 'phone', name : 'phone'},
                    {data: 'ip_address', name : 'ip_address'},
                    {data: 'area_code', name : 'area_code'},
                    {data: 'country', name : 'country'},
                    {data: 'region', name : 'region'},
                    {data: 'longitude', name : 'longitude'},
                    {data: 'latitude', name : 'latitude'},
                    {data: 'subscribed_at', name : 'subscribed_at'},
                    {data: 'status', name : 'status'},

                    //{ data: 'updated_at', name: 'updated_at' }
                ]
            });

        });



        function addUser(){
            $("#con-close-modal").modal('show');
        }


        function editRow(x){

            $('#loading').show();

            if(x!=''){
                $.post("<?php echo url('user/loadEdit'); ?>", {id: x}, function(result){
                            if(result!='0'){
                                var data = JSON.parse(result);
                                var cat_array = data.email_category.split(',');
                                $.each(data, function(k,v){
                                    var ref = $("#add_user").find("#"+k);
                                    $(ref).val(v);
                                    $("#con-close-modal").modal('show');
                                });

                                $('#email_category > option').attr('selected',false);

                                $('#email_category > option').each(function() {

                                    $(this).attr('selected', false).removeClass('bg-gray');
                                    $(this).prop('selected', false).removeClass('bg-gray');


                                    for( var i = 0; i < cat_array.length; i++){
                                        if( cat_array[i] == $(this).val() ){
                                            $(this).prop('selected', 'selected').addClass('bg-gray');
                                        }
                                    }

                                });

                                var cat_array = data.email_category.split(',');

                                //console.log(cat_array_length);
                            }else{
                                swal("Error!", "Something went wrong.", "error");
                            }
                            $('#loading').hide();
                        }

                );
            }
        }


        function showStats(e){

            $('#loading').show();
            var user_id =   $(e).data('id');
            if( user_id != '' ){
                $.post("<?php echo route('user-stats'); ?>", {user_id: user_id}, function(result){
                            if(result!='0'){

                                $('#user_stat').html(result);

                                $('#user_stat').modal('show');
                                $('#loading').hide();

                            }else{
                                swal("Error!", "Something went wrong.", "error");
                            }
                            $('#loading').hide();
                        }
                );
            }


        }





    </script>

@endsection
