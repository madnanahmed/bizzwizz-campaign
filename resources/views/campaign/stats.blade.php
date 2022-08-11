@extends('layouts.app')
@section('content')
<!--  stats -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <h4 class="title"> {{ ucfirst($campaign->title) }} Campaign's Logs </h4>
                <div>
                    <button data-toggle="tooltip" data-title="Refresh table record" class="m-b-5 m-l-5 pull-right btn btn-primary refreshTbl"><i class="fa fa-refresh"></i></button>

                </div>
            <div class="md-clear"><br><br></div>
            <div class="table-responsive">
                <table id="load_datatable" class="table table-striped table-bordered add-manage-table table demo footable-loaded footable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th width="200">Campaign Title</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Opened</th>
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
                <hr>
                <div class="col-md-12">
                    <h4 class="title"> {{ ucfirst($campaign->title) }} Campaign's stats </h4>
                    <div id="donut_chart"></div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/raphael/raphael-min.js') }}"></script>
    <script>

        function show_bug(id){

            if(id!=''){
                $.post('{{ url('campaign-error') }}', {id:id},
                    function (res) {
                   swal('Error Message!', res.message, 'warning');
                })
            }
        }


        $(document).ready(function(){

            var $donutData = [
                {label: "Successful", value: {{ $success }} },
                {label: "Error", value: {{ $error }} },

            ];

            Morris.Donut({
                element: 'donut_chart',
                data: $donutData,
                resize: true, //defaulted to true
                colors: ['green', 'red']
            });




            $('#load_datatable').DataTable({
                "pageLength":25,
                //"order": [[3, 'desc']],
                processing: true,
                serverSide: true,
                "initComplete": function (settings, json) {
                    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
                },
                ajax: "{{url('get-campaigns-stats?id='.$campaign->id)}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', title: 'title'},
                    {data: 'leads_name', name: 'leads_name'},
                    {data: 'to_email', name: 'to_email'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'status'},
                    {data: 'is_open', name: 'is_open'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
