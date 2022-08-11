@extends('layouts.app')
@section('title', 'Users')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="col-xs-12">
                <div class="page-title-box">

                    <ol class="breadcrumb p-0 m-0">
                        <li> <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Home</a></li>
                        <li class="active">Lists </li>
                    </ol>
                    <div class="clearfix"></div>
                    <br>
                </div>
            </div>

            <div class="col-md-7">
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

                        <h4 class="text-dark header-title m-t-0"> Lists </h4>
                        <button data-toggle="modal" data-target="#searchModal" class="btn btn-success pull-right"> Add Lists</button>
                        <div class="clearfix"></div>
                        <br>
                        <div class="table-responsive">

                            <table id="load_datatable" class="table  table-hover table-striped table-bordered">
                                <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if( count($categories) > 0 )
                                    @foreach($categories as $category )
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->title }}</td>
                                            <td>{{ $category->created_at }}</td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ route('categories.edit', $category->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                                <button class="btn btn-danger" onclick="deleteRow(this)" data-obj="categories" data-id="{{$category->id}}"><i class="fa fa-trash"></i> Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">No Record found yet.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search modal -->
    <div id="searchModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="font-icon-close-2"></i>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Add/Edit Lists</h4>
                </div>
                <form id="AddAgentForm" action="{{ route('categories.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <ol class="hidden form-alert"></ol>

                        <div class="form-group">
                            <label for=""> Title </label>
                            <input type="text" name="title" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
