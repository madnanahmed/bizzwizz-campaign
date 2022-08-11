@extends('layouts.app')
@section('content')
<!--  stats -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <h4 class="text-dark header-title m-t-0"> Send Bulk Messages </h4>
            </div>

            <form action="" method="post" id="bulksmsForm">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Campaign Title</label>
                        <input type="text" value="" name="title" class="form-control" autocomplete="off" required>
                    </div>

                    <div class="form-group cats">
                        <p for="">Select list</p>
                        <div class="btn-group" data-toggle="buttons">
                            @foreach( $category as $value)
                                <label for="cat_{{$value->id}}" class="btn">
                                    <input type="checkbox" id="cat_{{$value->id}}" name="category_ids[]" value="{{$value->id}}">
                                    {{ $value->title }}  &nbsp;&nbsp;<span class="badge badge-warning" title="total leads" data-toggle="tooltip">{{ \App\Contacts::where('list_id', $value->id)->count() }} </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group type hidden">
                        <p for="">Select Message Type</p>
                        <div class="btn-group" data-toggle="buttons">

                            <!--<label for="is_message" class="btn">
                                <input type="checkbox" id="is_message" name="is_message" value="1">
                                SMS
                            </label>-->

                            <label for="is_email" class="btn" style="display: none">
                                <input type="checkbox" id="is_email" name="is_email" checked value="1">
                                Email
                            </label>

                        </div>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="email_container">
                        <div class="form-group">
                            <label for="">Email Subject </label>
                            <input type="text" name="email_subject" id="email_subject" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Compose Email </label>
                            <textarea id="email_body" class="form-control"></textarea>
                        </div>

                    </div>

                    <div class="form-group" style="margin-top:10px ">
                        <button class="btn btn-success"> <i class="fa fa-send-o"></i> Send <i class="fa fa-spin fa-spinner hidden"></i></button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
@section('script')
    <script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/ckeditor/ckeditor.js')}}"></script>

    <script>
        $(document).ready(function(){
            // ck editor int
            var element = CKEDITOR.replace('email_body',{
                allowedContent: true,
                height:300
            });


            $('#is_message').change(function () {
                if( $(this).is(':checked') ){
                    $('.sms_container').removeClass('hidden');
                    $('#textarea').prop('required', true);
                }else{
                    $('.sms_container').addClass('hidden');
                    $('#textarea').prop('required', false);
                }
            });

            $('#is_email').change(function () {
                if( $(this).is(':checked') ){
                    $('.email_container').removeClass('hidden');
                    $('#email_subject').prop('required', true);

                }else{
                    $('.email_container').addClass('hidden');
                    $('#email_subject').prop('required', false);
                }
            });


            $('textarea#textarea').maxlength({
                alwaysShow: true
            });

            $('#personalize span').click(function () {
                var vl = $(this).attr('data-val');
                var $txt = $("#textarea");
                var caretPos = $txt[0].selectionStart;
                var textAreaTxt = $txt.val();
                $txt.val(textAreaTxt.substring(0, caretPos) + vl + textAreaTxt.substring(caretPos) );
            })

            $("#bulksmsForm").submit(function(){

                if(! $('.cats input:checkbox').is(':checked')){
                    swal('Error', 'Please select list', 'error');
                    return false;
                }

                if(! $('.type input:checkbox').is(':checked')){
                    swal('Error', 'Please select type', 'error');
                    return false;
                }

                if( $('#is_email').is(':checked') ){
                    if(CKEDITOR.instances.email_body.getData() == ''){
                        swal('Error', 'Compose email', 'error');
                        return false;
                    }
                }

                $('#bulksmsForm button.fa-spin').removeClass('hidden');
                var data = new FormData(this);

                var email_content = CKEDITOR.instances.email_body.getData();

                data.append('email_body', email_content);

                $.ajax({
                    url: "<?php  echo route('campaign.store'); ?>",
                    data: data,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(result){

                        if(result.success == true){
                            swal('Success', result.message, 'success');
                            window.location.href='{{route('campaign.index')}}';
                        }
                        if(result.success == false){
                            swal({
                                title: "Error!",
                                text: result.message,
                                type: "error"
                            });
                        }
                        $('#bulksmsForm button.fa-spin').addClass('hidden');
                    }
                });
                return false;
            });
        });
    </script>
@endsection
