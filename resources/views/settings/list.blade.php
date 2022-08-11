@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">

            <section class="tabs-section">
                <div class="tabs-section-nav tabs-section-nav-icons">
                    <div class="tbl">
                        <ul class="nav" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active show" href="#tabs-1-tab-2" role="tab" data-toggle="tab" aria-selected="false">
									<span class="nav-link-in">
										<span class="glyphicon glyphicon-envelope"></span>
										Email Settings
									</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div><!--.tabs-section-nav-->

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active show" id="tabs-1-tab-2">
                        <h4 class="text-dark header-title m-t-0">Email Settings</h4>

                        <div class="clearfix"></div>
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
                    </div><!--.tab-pane-->

                </div><!--.tab-content-->
            </section>


        </div>
    </div>



@endsection

@section('script')

    <script>
        $(document).ready(function(){
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
        });
    </script>
@endsection
