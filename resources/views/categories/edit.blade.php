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

                        <form id="AddAgentForm" action="{{ route('categories.store') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $categories->id }}" name="id">
                            <div class="modal-body">
                                <ol class="hidden form-alert"></ol>

                                <div class="form-group">
                                    <label for=""> Title </label>
                                    <input type="text" name="title" value="{{$categories->title}}" class="form-control">
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
