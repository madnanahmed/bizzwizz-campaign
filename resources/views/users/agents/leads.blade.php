@extends('layouts.app')
@section('content')
<!--  stats -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">

                <div class="row bg-in">
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="text-dark header-title m-t-0">Add Contact</h4>
                            <div class="clearfix"></div>
                            <form class="m-t-20" id="AddAgentForm" action="{{ route('contact-store') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{@$contact->id}}">
                                <div class="hidden form-alert"></div>

                                <div class="form-group">
                                    <label for="">Name* </label>
                                    <input type="text" name="name"  class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="">Email* </label>
                                    <input type="email" name="email"  class="form-control" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <label for="">Contact List</label>
                                    <select name="list_id" id="listSelect" data-title="select list" required class="form-control" >
                                        @foreach($category as $value)
                                            <option @if($value->id == @$contact->list_id) selected @endif value="{{$value->id}}">{{$value->title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>

                <h4 class="text-dark header-title m-t-0"> All Contacts </h4>
            <div class="md-clear"><br><br></div>
                <button data-toggle="tooltip" data-title="Refresh table record" class="m-b-5 pull-right btn btn-primary refreshTbl" title="" style="margin-left: 5px"><i class="fa fa-refresh"></i></button>
                <select name="class_id" class="btn btn-blue pull-right" id="search_class">
                    <?php
                    $category = \App\Categories::where('status', 1)->get();
                    ?>
                    <option value="">Select list</option>
                    @foreach( $category as $cat )
                        <option value="{{$cat->id}}">{{ $cat->title }}</option>
                    @endforeach
                </select>

                <br><br>
                <div class="table-responsive">
                <table id="load_datatable" class="table table-striped table-bordered add-manage-table table demo footable-loaded footable">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>List</td>
                        <td>List_id</td>
                        <td>Date</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')

    <script>
        $(document).ready(function(){

           var table = $('#load_datatable').DataTable({
                "pageLength":10,
               "columnDefs": [
                   {
                       "targets": [ 4 ],
                       "visible": false,
                       "searchable": true
                   }
               ],
                processing: true,
                serverSide: true,
                "initComplete": function (settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
                },
                ajax: "{{url('get-all-leads')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'category', name: 'category'},
                    {data: 'list_id', name: 'list_id'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
           });

           $('#search_class').change(function(){
                //table.columns( 5 ).search( this.value ).draw();
           });

        });
    </script>
@endsection
