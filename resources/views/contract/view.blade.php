@extends('layouts.main')

@section('page-title')
    {{ __('Contract') }}
@endsection
@php
$contact_id = $contract->id;
$profile_pic=\App\Models\Utility::get_file(Auth::user()->getUserInfo->DefaultProfilePic());

$name = !empty(Auth::user()->first_name) ? Auth::user()->first_name : Auth::user()->company_name;

@endphp

@section('availabilitylink')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
@endsection

@push('pagescript')
    <!-- <script src="{{ asset('custom/libs/src/modernizr.js') }}"></script> -->
    <script src="{{ asset('custom/libs/src/jSignature.js') }}"></script>
    <script src="{{ asset('custom/libs/src/plugins/jSignature.CompressorBase30.js') }}"></script>
    <script src="{{ asset('custom/libs/src/plugins/jSignature.CompressorSVG.js') }}"></script>
    <script src="{{ asset('custom/libs/src/plugins/jSignature.UndoButton.js') }}"></script>
    <script src="{{ asset('custom/libs/src/plugins/signhere/jSignature.SignHere.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                // menubar: false,
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }',
                // menubar: 'edit insert view format',
            });
        }
        // cleanText = $(".pc-tinymce-2").code().replace(/<\/?[^>]+(>|$)/g, "");
    </script>
    <script src="{{ asset('custom/js/dropzone-amd-module.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = true;

        myDropzone = new Dropzone("#my-dropzone", {

            maxFiles: 20,
            maxFilesize: 209715200,
            parallelUploads: 1,
            // acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{ route('contract.attachments', [$contract->id]) }}",
            init: function() {
                this.on('complete', function() {

                });
            },
            // success: function(file, response) {
            //     if (response.is_success) {
            //         dropzoneBtn(file, response);
            //     } else {
            //          console.log(response.is_success);
            //         myDropzone.removeFile(file);

            //         show_toastr('{{ __('Error') }}', response.error, 'error');
            //     }
            // },
            success: function (file, response) {
                location.reload();
            if (response.is_success) {
            dropzoneBtn(file, response);

            // toastrs('{{__("success")}}', 'Attachment Create Successfully!');
            show_toastr('Success', '{{ __('Attachment Create Successfully!') }}', 'success')


            } else {
            myDropzone.removeFile(file);
            show_toastr('{{__("Error")}}', 'File type must be match with Storage setting.', 'error');
            }
            },
            // error: function(file, response) {
            //     myDropzone.removeFile(file);
            //     if (response.error) {

            //         show_toastr('{{ __('Error') }}', response.error, 'error');
            //     } else {

            //         show_toastr('{{ __('Error') }}', response.error, 'error');
            //     }
            // }

        });


        myDropzone.on("sending", function(file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("contract_id", {{ $contract->id }});
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn bg-primary mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='fas fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "action-btn bg-danger mx-1 mt-1 btn btn-sm d-inline-flex align-items-center");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{ __('Delete') }}");
            del.innerHTML = "<i class='ti ti-trash'></i>";

            del.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        success: function(response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                show_toastr('{{ __('Error') }}', response.error, 'error');
                            }
                        },
                        error: function(response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('{{ __('Error') }}', response.error, 'error');
                            } else {
                                show_toastr('{{ __('Error') }}', response.error, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.setAttribute('class', "text-center mt-10");
            html.appendChild(download);
            html.appendChild(del);

            file.previewTemplate.appendChild(html);
        }
    </script>

    <script type="text/javascript">
        $(document).on('click', '.attachment_create', function() {

            var form = $(this).parents('.attachment_cteate_frm');
            var contract_id = form.find('input[name="contract_id"]').val();
            var attachments = form.find('textarea[name="attachments"]').val();
            var u_url = form.find('input[name="u_url"]').val();
            var token = $('meta[name="csrf-token"]').attr('content');

            var data = {
                "_token": token,
                "comment_id": comment_id,
                "comments": comments,
            }

            $.ajax({
                url: u_url,
                method: 'any',
                data: data,

                success: function(data) {
                    if (data["status"] == 'success') {
                        show_toastr('Success', data["msg"], 'success');
                    } else {
                        show_toastr('Error', data["msg"], 'error');
                    }
                }
            });
            return;
        });


        $(document).on('click', '.comment_create', function() {

            var form = $(this).parents('.comment_cteate_frm');
            var contract_id = form.find('input[name="contract_id"]').val();
            var comments = form.find('textarea[name="comments"]').val();
            var u_url = form.find('input[name="u_url"]').val();
            var token = $('meta[name="csrf-token"]').attr('content');

            var data = {
                "_token": token,
                "comment_id": comment_id,
                "comments": comments,
            }

            $.ajax({
                url: u_url,
                method: 'any',
                data: data,

                success: function(data) {
                    if (data["status"] == 'success') {
                        show_toastr('Success', data["msg"], 'success');
                    } else {
                        show_toastr('Error', data["msg"], 'error');
                    }
                }
            });
            return;
        });

        $(document).on('click', '.note_create', function() {

            var form = $(this).parents('.note_cteate_frm');
            var contract_id = form.find('input[name="contract_id"]').val();
            var notes = form.find('textarea[name="notes"]').val();
            var u_url = form.find('input[name="u_url"]').val();
            var token = $('meta[name="csrf-token"]').attr('content');

            var data = {
                "_token": token,
                "comment_id": comment_id,
                "notes": notes,
            }

            $.ajax({
                url: u_url,
                method: 'any',
                data: data,

                success: function(data) {
                    if (data["status"] == 'success') {
                        show_toastr('Success', data["msg"], 'success');
                    } else {
                        show_toastr('Error', data["msg"], 'error');
                    }
                    // $('.rotas_location_change').trigger('change');
                }
            });
            // $('#commonModal').modal('toggle');
            return;
        });
        $(document).on("click", ".status", function() {

            var edit_status = $(this).attr('data-id');
            var url = $(this).attr('data-url');
            $.ajax({
                url: url,
                type: 'POST',
                data: {

                    "edit_status": edit_status,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    show_toastr('{{__("Success")}}', 'Status Update Successfully!', 'success');
                    location.reload();
                }

            });
        });
    </script>
@endpush

@section('content')
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('View Contract') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('contract.index') }}">{{ __('Contract') }}</a></li>
                                <li class="breadcrumb-item"> {{ $contract->contract_name }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-end d-flex align-items-center justify-content-end mb-4">
                            @if (\Auth::user()->type == 'company')
                                 @if(\Auth::user()->enable_chatgpt())
                                    <div class="text-end">
                                        <a href="#" class="w-auto m-1 btn btn-sm btn-primary btn-icon" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['contracts']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}">
                                            <i class="fas fa-robot"></i>{{ __(' Generate with AI') }}
                                        </a>
                                    </div>
                                @endif
                                {{-- <div class="btn btn-sm btn-primary btn-icon mx-1 my-2"> --}}

                                    <a href="{{route('contract.send',$contract->id)}}" class="w-auto m-1 btn btn-sm btn-primary btn-icon"  data-bs-toggle="tooltip" data-bs-original-title="{{__('Send Email')}}"  >
                                        <i class="ti ti-mail text-white"></i>
                                    </a>
                                {{-- </div> --}}

                                {{-- <div class="btn btn-sm btn-primary btn-icon mx-1 my-2"> --}}

                                    <a href="#" data-size="lg" data-url="{{route('contract.copy',$contract->id)}}"data-ajax-popup="true" data-title="{{__('Duplicate')}}" class="w-auto m-1 btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Duplicate')}}" ><i class="ti ti-copy text-white"></i></a>

                                {{-- </div> --}}
                            @endif
                            {{-- <div class="btn btn-sm btn-primary btn-icon mx-1 my-2"> --}}

                                <a href="{{route('contract.pdf.download',$contract->id)}}" target="_blank" class="w-auto m-1 btn btn-sm btn-primary btn-icon html2pdf" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Download')}}" >
                                    <i class="ti ti-download text-white"></i>
                                </a>




                            {{-- </div>  --}}
                            {{-- <div class="btn btn-sm btn-primary btn-icon mx-1 my-2"> --}}

                                <a href="{{route('contract.pdf',$contract->id)}}" target="_blank" class="w-auto m-1 btn btn-sm btn-primary btn-icon" title="{{__('Preview')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                    <i class="ti ti-eye"></i>
                                </a>
                            {{-- </div> --}}


                            @if(\Auth::user()->type == 'company')
                                    <a href="#" class="w-auto m-1 btn btn-sm btn-primary btn-icon" data-url="{{ route('contract.signature',$contract->id) }}" data-ajax-popup="true" data-title="{{__('Signature')}}" data-size="md" title="{{__('Signature')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                            @elseif(\Auth::user()->type == 'employee' && ($contract->edit_status == 'accept'))
                                    <a href="#" class="w-auto m-1 btn btn-sm btn-primary btn-icon" data-url="{{ route('contract.signature',$contract->id) }}" data-ajax-popup="true" data-title="{{__('Signature')}}" data-size="md" title="{{__('Signature')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                            @endif
                            @php
                                    $editstatus = App\Models\Contract::editstatus();
                            @endphp
                            @if(\Auth::user()->type == 'employee')
                                <ul class="list-unstyled mb-0 m-1">
                                    <li class="dropdown dash-h-item drp-language">
                                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                                            role="button" aria-haspopup="false" aria-expanded="false">
                                            <span class="drp-text hide-mob text-primary">{{ ucfirst($contract->edit_status) }}
                                                <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                                        </a>
                                        <div class="dropdown-menu dash-h-dropdown">
                                            @foreach ($editstatus as $k => $status)
                                                <a class="dropdown-item status" data-id="{{ $k }}"
                                                    data-url="{{ route('contract.status', $contract->id) }}"
                                                    href="#">{{ ucfirst($status) }}</a>
                                                @endforeach
                                        </div>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:60px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                <a href="#useradd-1"
                                    class="list-group-item list-group-item-action border-0">{{ __('General') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-2"
                                    class="list-group-item list-group-item-action border-0">{{ __('Attachment') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-3"
                                    class="list-group-item list-group-item-action border-0">{{ __('Comment') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                <a href="#useradd-4"
                                    class="list-group-item list-group-item-action border-0">{{ __('Notes') }} <div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div id="useradd-1">
                            <div class="row">
                                <div class="col-xl-7">
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="theme-avtar bg-primary">
                                                        <i class="ti ti-user-plus"></i>
                                                    </div>
                                                    <h6 class="mb-3 mt-2">{{ __('Attachment') }}</h6>
                                                    <h4 class="mb-3 mt-2"> {{ $attachmentcount }}</h4>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="theme-avtar bg-success">
                                                        <i class="ti ti-click"></i>
                                                    </div>
                                                    <h6 class="mb-3 mt-2">{{ __('Comment') }}</h6>
                                                    <h4 class="mb-3 mt-2"> {{ $commentscount }} </h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="theme-avtar bg-warning">
                                                        <i class="ti ti-file"></i>
                                                    </div>
                                                    <h6 class="mb-3 mt-2">{{ __('Notes') }}</h6>
                                                    <h4 class="mb-3 mt-2"> {{ $notescount }} </h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-5">
                                    <div class="card report_card total_amount_card">
                                        <div class="card-body pt-0" style="margin-bottom: -30px; margin-top: -10px;">
                                            <address class="mb-0 text-sm">
                                                <dl class="row mt-4 align-items-center">
                                                    <dt class="col-sm-4 h6 text-sm">{{ __('Name') }}</dt>
                                                    <dd class="col-sm-8 text-sm">
                                                        {{ $contract->contract_name }}
                                                    </dd>
                                                    <dt class="col-sm-4 h6 text-sm">{{ __('Employee') }}</dt>
                                                    <dd class="col-sm-8 text-sm">
                                                        {{ !empty($contract->employees) ? $contract->employees->first_name : '' }}
                                                        {{ !empty($contract->employees) ? $contract->employees->last_name : '' }}
                                                    </dd>
                                                    <dt class="col-sm-4 h6 text-sm">{{ __('Type') }}</dt>
                                                    <dd class="col-sm-8 text-sm">
                                                        {{ !empty($contract->types) ? $contract->types->name : '' }} </dd>

                                                        <dt class="col-sm-4 h6 text-sm">{{ __('Subject') }}</dt>
                                                        <dd class="col-sm-8 text-sm">
                                                            {{ !empty($contract->subject) ? $contract->subject : '' }} </dd>


                                                    <dt class="col-sm-4 h6 text-sm">{{ __('Value') }}</dt>
                                                    <dd class="col-sm-8 text-sm">
                                                        {{ Auth::user()->priceFormat($contract->value) }}</dd>


                                                    <dt class="col-sm-4 h6 text-sm">{{ __('Start Date') }}</dt>
                                                    <dd class="col-sm-8 text-sm">
                                                        {{ Auth::user()->dateFormat($contract->start_date) }}</dd>

                                                    <dt class="col-sm-4 h6 text-sm">{{ __('End Date') }}</dt>
                                                    <dd class="col-sm-8 text-sm">
                                                        {{ Auth::user()->dateFormat($contract->end_date) }}</dd>
                                                </dl>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Contract Description') }}</h5>
                                </div>
                                <div class="card-body p-2">
                                    {{ Form::open(['route' => ['contract.description', $contract->id]]) }}
                                    <div class="col-md-12">
                                        <div class="form-group mt-3">

                                            <textarea class="tox-target pc-tinymce-2" name="contract_description" id="pc_demo1" rows="8">{!! $contract->contract_description !!}</textarea>
                                        </div>
                                    </div>
                                    @if (\Auth::user()->type == 'company')
                                        <div class="col-md-12 text-end">
                                            <div class="form-group mt-3 me-3">
                                                {{ Form::submit(__('Add'), ['class' => 'btn  btn-primary']) }}
                                            </div>
                                        </div>
                                    @endif
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                        <div id="useradd-2">
                            <div class="row ">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Attachments') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            @if (\Auth::user()->type == 'company')
                                                <div class="">
                                                    <div class="col-md-12 dropzone browse-file" id="my-dropzone"></div>
                                                </div>
                                            @elseif(\Auth::user()->type == 'employee' && $contract->edit_status == 'accept')
                                                <div class="">
                                                    <div class="col-md-12 dropzone browse-file" id="my-dropzone"></div>
                                                </div>
                                            @endif

                                            @foreach ($contractattachemnts as $file)
                                                <div class="px-0 py-2">
                                                    <div class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <a href="{{(!empty($comment->clientcomments->profile_pic))?  \App\Models\Utility::get_file('uploads/profile_pic/'.$comment->clientcomments->profile_pic): asset(Storage::url("uploads/profile_pic/avatar.png"))}}" target="_blank">
                                                                    <img src="{{(!empty($comment->clientcomments->profile_pic))?  \App\Models\Utility::get_file('uploads/profile_pic/'.$comment->clientcomments->profile_pic): asset(Storage::url("uploads/profile_pic/avatar.png"))}}" class="img-fluid rounded-circle" width="30">
                                                                </a>
                                                            </div>
                                                            <div class="col ml-n2">
                                                                <p
                                                                class="d-block h6 text-sm font-weight-light mb-0 text-break">
                                                                {{ $file->file_name }}</p>
                                                            <small
                                                                class="d-block">{{ number_format(Storage::size($file->file_path.$file->file_name ) / 1048576, 2) . ' ' . __('MB') }}</small>
                                                            </div>
                                                            @php
                                                                 $logo=\App\Models\Utility::get_file('uploads/contract_attachments/');
                                                            @endphp
                                                            <div class="col-auto">
                                                                <div class="action-btn bg-warning me-2">
                                                                    <a class="mx-3 btn btn-sm align-items-center"
                                                                        href="{{ $logo.'/'.$file->file_name }}"
                                                                        download="">
                                                                        <i class="ti ti-download text-white"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Download') }}"></i>
                                                                    </a>
                                                                </div>
                                                                @if ((\Auth::user()->type == 'company' && $contract->edit_status == 'accept') || Auth::user()->id == $file->created_by)
                                                                    <div class="action-btn bg-danger me-2"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="{{ __('Delete') }}">
                                                                        {!! Form::open(['method' => 'GET', 'route' => ['attachments.destroy', $contract->id, $file->id]]) !!}
                                                                        <a href="#!"
                                                                            class="mx-3 btn btn-sm show_confirm">
                                                                            <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- </div> -->
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="useradd-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Comment') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-end pb-2">
                                        <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm" data-ajax-popup-over="true" id="grammarCheck" data-url="{{ route('grammar',['comment']) }}"
                                            data-bs-placement="top" data-title="{{ __('Grammar check with AI') }}">
                                            <i class="ti ti-rotate"></i> <span>{{__('Grammar check with AI')}}</span>
                                        </a>
                                    </div>
                                    @if (\Auth::user()->type == 'company')
                                        <div class="col-md-12 d-flex">
                                            <div class="form-group mb-0 form-send w-100">
                                                {{ Form::open(['route' => ['contract.comments', $contract->id], 'method' => 'any', 'class' => 'comment_ctrate_location comment_cteate_frm']) }}
                                                {{ Form::input('hidden', 'u_url', route('contract.comments', $contract->id)) }}
                                                {{ Form::input('hidden', 'contract_id', $contact_id) }}
                                                <div class="row">
                                                    <div class="col-12 d-flex">
                                                        <div class="form-group mb-0 form-send w-100"
                                                            id="contract-comments">
                                                            <textarea rows="1" class="form-control grammer_textarea_comment" name="comments" id="comments" data-toggle="autosize"
                                                                placeholder="Add a comment..." spellcheck="false"></textarea>
                                                            <grammarly-extension data-grammarly-shadow-root="true"
                                                                style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;"
                                                                class="cGcvT"></grammarly-extension>
                                                            <grammarly-extension data-grammarly-shadow-root="true"
                                                                style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;"
                                                                class="cGcvT"></grammarly-extension>
                                                        </div>
                                                        <button id="comment_submit"
                                                            class="btn btn-send comment_create"><i
                                                                class="f-16 text-primary ti ti-brand-telegram">
                                                            </i>
                                                        </button>
                                                    </div>
                                                </div>
                                                {{ Form::close() }}
                                            </div>
                                        </div>
                                    @elseif(\Auth::user()->type == 'employee' && $contract->edit_status == 'accept')
                                        <div class="col-md-12 d-flex">
                                            <div class="form-group mb-0 form-send w-100">
                                                {{ Form::open(['route' => ['contract.comments', $contract->id], 'method' => 'any', 'class' => 'comment_ctrate_location comment_cteate_frm']) }}
                                                {{ Form::input('hidden', 'u_url', route('contract.comments', $contract->id)) }}
                                                {{ Form::input('hidden', 'contract_id', $contact_id) }}
                                                <div class="row">
                                                    <div class="col-12 d-flex">
                                                        <div class="form-group mb-0 form-send w-100"
                                                            id="contract-comments">

                                                            <textarea rows="1" class="form-control" name="comments" id="comments" data-toggle="autosize"
                                                                placeholder="Add a comment..." spellcheck="false"></textarea>
                                                            <grammarly-extension data-grammarly-shadow-root="true"
                                                                style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;"
                                                                class="cGcvT"></grammarly-extension>
                                                            <grammarly-extension data-grammarly-shadow-root="true"
                                                                style="mix-blend-mode: darken; position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 1;"
                                                                class="cGcvT"></grammarly-extension>

                                                        </div>
                                                        <button id="comment_submit"
                                                            class="btn btn-send comment_create"><i
                                                                class="f-16 text-primary ti ti-brand-telegram">
                                                            </i>
                                                        </button>
                                                    </div>
                                                </div>
                                                {{ Form::close() }}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="list-group list-group-flush mb-0" id="comments">
                                        @foreach ($contractcomments as $comment)
                                            @php
                                                $user = \App\Models\User::find($comment->user_id);
                                            @endphp
                                            <div class="list-group-item ">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        {{-- <a href="#"
                                                            class="avatar avatar-sm rounded-circle ms-2">
                                                            <img class="wid-36 hei-35 avatar rounded-circle avatar-sm"
                                                                title="{{ $comment->clientcomments->name }}"
                                                                alt="Image placeholder"
                                                                @if (!empty($comment->clientcomments->profile_pic)) src="{{ asset(Storage::url('')) . '/' . $comment->clientcomments->profile_pic }}" @else  src="{{ asset(Storage::url('uploads/profile_pic')) . '/' . 'avatar.png' }}" @endif>
                                                        </a> --}}

                                                        <a href="{{(!empty($comment->clientcomments->profile_pic))?  \App\Models\Utility::get_file('uploads/profile_pic/'.$comment->clientcomments->profile_pic): asset(Storage::url("uploads/profile_pic/avatar.png"))}}" target="_blank">
                                                            <img src="{{(!empty($comment->clientcomments->profile_pic))?  \App\Models\Utility::get_file('uploads/profile_pic/'.$comment->clientcomments->profile_pic): asset(Storage::url("uploads/profile_pic/avatar.png"))}}" class="img-fluid rounded-circle" width="30">
                                                        </a>

                                                    </div>
                                                    <div class="col ml-n2">
                                                        <p
                                                            class="d-block h6 text-sm font-weight-light mb-0 text-break">
                                                            {{ $comment->comment }}</p>
                                                        <small
                                                            class="d-block">{{ $comment->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    @if ((\Auth::user()->type == 'company' && $contract->edit_status == 'accept') || Auth::user()->id == $comment->created_by)
                                                        <div class="col-auto">
                                                            <div class="action-btn bg-danger me-2"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Delete') }}">
                                                                {!! Form::open(['method' => 'GET', 'route' => ['comment.destroy', $contract->id, $comment->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm show_confirm">
                                                                    <i class="ti ti-trash text-white"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="useradd-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Note') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-end pb-2">
                                        <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm" data-ajax-popup-over="true" id="grammarCheck" data-url="{{ route('grammar',['note']) }}"
                                            data-bs-placement="top" data-title="{{ __('Grammar check with AI') }}">
                                            <i class="ti ti-rotate"></i> <span>{{__('Grammar check with AI')}}</span>
                                        </a>
                                    </div>

                                    <div class="activity" id="notes">
                                        <div class="">
                                            @if (\Auth::user()->type == 'company')
                                                <div class="col-12 d-flex">
                                                    <div class="form-group form-send w-100">
                                                        {{ Form::open(['route' => ['contract.notes', $contract->id], 'method' => 'any', 'class' => 'notes_ctrate_location notes_cteate_frm']) }}
                                                        {{ Form::input('hidden', 'u_url', route('contract.notes', $contract->id)) }}
                                                        {{ Form::input('hidden', 'contract_id', $contact_id) }}
                                                        <div class="row">
                                                            <div class="col-12 mb-0 d-flex">
                                                                <div class="form-group mb-0 form-send w-100" id="contract-note">
                                                                    <textarea rows="3" class="form-control grammer_textarea_note" name="notes" id="notes" data-toggle="autosize"
                                                                        placeholder="Add a Notes..." spellcheck="false"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 text-end">
                                                            <div class="form-group mt-3 me-3">
                                                                {{ Form::submit(__('Add'), ['class' => 'btn  btn-primary note_create']) }}
                                                            </div>
                                                        </div>
                                                        {{ Form::close() }}
                                                    </div>
                                                </div>
                                            @elseif(\Auth::user()->type == 'employee' && $contract->edit_status == 'accept')
                                                <div class="col-12 d-flex">
                                                    <div class="form-group form-send w-100">
                                                        {{ Form::open(['route' => ['contract.notes', $contract->id], 'method' => 'any', 'class' => 'notes_ctrate_location notes_cteate_frm']) }}
                                                        {{ Form::input('hidden', 'u_url', route('contract.notes', $contract->id)) }}
                                                        {{ Form::input('hidden', 'contract_id', $contact_id) }}
                                                        <div class="row">
                                                            <div class="col-12 mb-0 d-flex">
                                                                <div class="form-group mb-0 form-send w-100" id="contract-note">
                                                                    <textarea rows="3" class="form-control" name="notes" id="notes" data-toggle="autosize"
                                                                        placeholder="Add a Notes..." spellcheck="false"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 text-end">
                                                            <div class="form-group mt-3 me-3">
                                                                {{ Form::submit(__('Add'), ['class' => 'btn  btn-primary note_create']) }}
                                                            </div>
                                                        </div>
                                                        {{ Form::close() }}
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="list-group list-group-flush mb-0">
                                                @foreach ($contractnotes as $notes)
                                                    <div class="list-group-item px-0">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                {{-- <a href="#"
                                                                    class="avatar avatar-sm rounded-circle ms-2">
                                                                    <img class="wid-40 hei-40 avatar rounded-circle avatar-sm"
                                                                        title="{{ $notes->clientnotes->name }}"
                                                                        alt="Image placeholder"
                                                                        @if (!empty($notes->clientnotes->profile_pic)) src="{{ asset(Storage::url('')) . '/' . $notes->clientnotes->profile_pic }}" @else  src="{{ asset(Storage::url('uploads/profile_pic')) . '/' . 'avatar.png' }}" @endif>

                                                                </a> --}}
                                                                    <a href="{{(!empty($notes->clientnotes->profile_pic))?  \App\Models\Utility::get_file('uploads/profile_pic/'.$notes->clientnotes->profile_pic): asset(Storage::url("uploads/profile_pic/avatar.png"))}}" target="_blank">
                                                                        <img src="{{(!empty($notes->clientnotes->profile_pic))?  \App\Models\Utility::get_file('uploads/profile_pic/'.$notes->clientnotes->profile_pic): asset(Storage::url("uploads/profile_pic/avatar.png"))}}" class="img-fluid rounded-circle" width="30">
                                                                    </a>

                                                            </div>
                                                            <div class="col ml-n2">
                                                                <p
                                                                    class="d-block h6 text-sm font-weight-light mb-0 text-break">
                                                                    {{ $notes->notes }}</p>
                                                                <small
                                                                    class="d-block">{{ $notes->created_at->diffForHumans() }}</small>
                                                            </div>
                                                            @if ((\Auth::user()->type == 'company' && $contract->edit_status == 'accept') || Auth::user()->id == $notes->created_by)
                                                                <div class="col-auto">
                                                                    <div class="action-btn bg-danger me-2"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="{{ __('Delete') }}">
                                                                        {!! Form::open(['method' => 'GET', 'route' => ['notes.destroy', $contract->id, $notes->id]]) !!}
                                                                        <a href="#!"
                                                                            class="mx-3 btn btn-sm show_confirm">
                                                                            <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
