@extends('layouts.app')
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!--  stats -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-box">
                            <h4 class="text-dark header-title m-t-0">Edit Contact</h4>
                            <div class="clearfix"></div>
                            <form class="m-t-20" id="AddAgentForm" action="{{ route('contact-store') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{$contact->id}}">
                                <div class="hidden form-alert"></div>

                                <div class="form-group">
                                    <label for="">Name* </label>
                                    <input type="text" name="name" value="{{ $contact->name }}" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="">Email* </label>
                                    <input type="email" name="email" value="{{ $contact->email }}" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="">phone* </label>
                                    <input type="text" name="phone" value="{{ $contact->phone }}" class="form-control" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    <select name="list" id="listSelect" data-title="select list" required class="selectpicker" data-live-search="true" data-style="select-with-transition">
                                        @foreach($list as $value)
                                            <option @if($value->id == $contact->list_id) selected @endif value="{{$value->id}}">{{$value->title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <a href="{{route('contact-list.index')}}" class="btn btn-default waves-effect" data-dismiss="modal">Go back</a>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- dataTables JS  -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap.js') }}"></script>

@endsection
