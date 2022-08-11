@extends('layouts.app')
@section('content')
<!--  stats -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <h4 class="title"> Campaigns </h4>
            <div>
                <button data-toggle="tooltip" data-title="Refresh table record" class="m-b-5 m-l-5 pull-right btn btn-primary refreshTbl"><i class="fa fa-refresh"></i></button>
                <a href="{{ route('campaign.create') }}" class="btn btn-default pull-right"><i class="fa fa-user-plus"></i> Create New </a>
            </div>

            <div class="md-clear"><br><br></div>
            <div class="table-responsive">
                <table id="load_datatable" class="table table-striped table-bordered add-manage-table table demo footable-loaded footable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>List</th>
                        <th>Type</th>
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
@endsection

@section('script')

    <script>
        $(document).ready(function(){

            $('#load_datatable').DataTable({
                "pageLength":25,
                //"order": [[3, 'desc']],
                processing: true,
                serverSide: true,
                "initComplete": function (settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
                },
                ajax: "{{url('get-campaigns')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', title: 'Title'},
                    {data: 'category', name: 'category'},
                    {data: 'type', name: 'type'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
