<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<base href="">
		<meta charset="utf-8" />
		<title>Bahana Group</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="noindex, nofollow">

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
        <!-- Bootstrap Core CSS -->
        <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel='stylesheet prefetch'
            href='https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css'>
        <link rel='stylesheet prefetch'
            href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>

        <!-- This is Sidebar menu CSS -->
        <link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">

        <link href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet">

        <!-- This is a Animation CSS -->
        <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <!-- color CSS you can use different color css from css/colors folder -->
        <!-- We have chosen the skin-blue (default.css) for this starter
        page. However, you can choose any other skin from folder css / colors .
        -->
        <link href="{{ asset('css/colors/default.css') }}" id="theme" rel="stylesheet">
        <link href="{{ asset('plugins/froiden-helper/helper.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
        <link href="{{ asset('css/custom-new.css') }}" rel="stylesheet">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
        <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/tagify-master/dist/tagify.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
      
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-page--loading-enabled kt-page--loading kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-menu kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--left kt-aside--fixed kt-page--loading">

        <div class="row">
            <div class="col-md-12">
        
                <div class="panel panel-inverse">
                    <div class="panel-heading"> @lang('modules.employees.updateTitle')
                        [ {{ $userDetail->name }} ]
                        @php($class = ($userDetail->status == 'active') ? 'label-custom' : 'label-danger')
                        <span class="label {{$class}}">{{ucfirst($userDetail->status)}}</span>
                    </div>
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                            {!! Form::open(['id'=>'updateEmployee','class'=>'ajax-form','method'=>'POST','action'=>['IframeController@updateEmployee',$userDetail->id]]) !!}
                            <div class="form-body">
                                <label>Orang Kepercayaan</label>
                                <button type="button" id="tambah-orang-kepercayaan" class="btn btn-sm btn-primary pull-right">Tambah</button>
                                @if (!empty($user_orang_kepercayaan))
                                    <div id="container-orang-kepercayaan">
                                        <div class="col-md-12" style="border: solid 1px #eee;padding:10px;margin-bottom: 15px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Pilih User</label>
                                                    <select id="team_id" name="user_orang_kepercayaan[]" id="orang_kepecayaan" class="form-control">
                                                        <option value="">Pilih User</option>
                                                        @foreach ($listEmployee as $item)
                                                            <option value="{{$item->id}}" {{$user_orang_kepercayaan[0]==$item->id?'selected':''}}>{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>  
                                                <div class="col-md-12">
                                                    <label>@lang('app.subcompany')</label>
                                                    <div class="form-group">
                                                        @forelse($subcompanys as $subcompany)
                                                            <?php
                                                                $selected=false;
                                                            ?>
                                                            @if (isset($sub_company_orang_kepercayaan[$user_orang_kepercayaan[0]]))
                                                                @if ($sub_company_orang_kepercayaan[$user_orang_kepercayaan[0]])
                                                                    @foreach ($sub_company_orang_kepercayaan[$user_orang_kepercayaan[0]] as $item)
                                                                        <?php
                                                                            if($item==$subcompany->id){
                                                                                $selected=true;
                                                                            }
                                                                        ?>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        
                                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                                <input id="orang_kepercayaan_subcompany_{{$subcompany->id}}" name="sub_company_orang_kepercayaan[0][]" value="{{$subcompany->id}}" type="checkbox" class="checkbox-sub-company" {{$selected?'checked':''}}>
                                                                <label for="orang_kepercayaan_subcompany_{{$subcompany->id}}" class="label-sub-company">{{$subcompany->name}}</label>
                                                            </div>
                                                        @empty
                                                            @lang('app.noDataFound')
                                                        @endforelse()
                                                    </div>
                                                </div><br>
                                                <div class="col-md-12 active-or-not">
                                                    <label>Aktif?</label>
                                                    <input id="active" name="active[0][]" type="checkbox" {{$active[$user_orang_kepercayaan[0]]==1?'checked':'' }}>
                                                    {{-- <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_on_orang_kepercayaan" name="is_on_orang_kepercayaan[]"/> @lang('modules.employees.yes')
                                                    </div> --}}
                                                </div>
                                            </div><br>
                                        </div><br>
                                    </div>
                                    <div id="container-clone-orang-kepercayaan">
                                        <?php
                                            $idx=0;
                                            $counter=0;
                                        ?>
                                        @foreach ($user_orang_kepercayaan as $val)
                                            @if ($idx==0)
                                                <?php
                                                    $idx++;
                                                    continue;
                                                ?>
                                            @endif
                                            <div id="container-orang-kepercayaan">
                                                <div class="col-md-12" style="border: solid 1px #eee;padding:10px;margin-bottom: 15px;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="">Pilih User</label>
                                                            <select id="team_id" name="user_orang_kepercayaan[]" id="orang_kepecayaan" class="form-control">
                                                                <option value="">Pilih User</option>
                                                                @foreach ($listEmployee as $item)
                                                                    <option value="{{$item->id}}" {{$item->id==$val?'selected':''}}>{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>  
                                                        <div class="col-md-12">
                                                            <label>@lang('app.subcompany')</label>
                                                            <div class="form-group">
                                                                @forelse($subcompanys as $subcompany)
                                                                    <?php
                                                                        $selected=false;
                                                                    ?>
                                                                    @if (isset($sub_company_orang_kepercayaan[$val]))
                                                                        @if ($sub_company_orang_kepercayaan[$val])
                                                                            @foreach ($sub_company_orang_kepercayaan[$val] as $val2)
                                                                                <?php
                                                                                    if($val2==$subcompany->id){
                                                                                        $selected=true;
                                                                                    }
                                                                                ?>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                    <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                                        <input id="orang_kepercayaan_subcompany_{{$subcompany->id}}_{{$counter}}" name="sub_company_orang_kepercayaan[{{$idx}}][]" value="{{$subcompany->id}}" type="checkbox" class="checkbox-sub-company" {{$selected?'checked':''}}>
                                                                        <label for="orang_kepercayaan_subcompany_{{$subcompany->id}}_{{$counter}}" class="label-sub-company">{{$subcompany->name}}</label>
                                                                    </div>
                                                                @empty
                                                                    @lang('app.noDataFound')
                                                                @endforelse()
                                                            </div>
                                                        </div><br>
                                                        <div class="col-md-12 active-or-not">
                                                            <label>Aktif?</label>
                                                            <input id="active" name="active[{{$idx}}][]" type="checkbox" {{$active[$val]==1?'checked':'' }}>
                                                            {{-- <div class="switchery-demo">
                                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_on_orang_kepercayaan" name="is_on_orang_kepercayaan[]"/> @lang('modules.employees.yes')
                                                            </div> --}}
                                                        </div>
                                                    </div><br>
                                                </div><br>
                                            </div>
                                            <?php
                                                $idx++;
                                                $counter++;
                                            ?>
                                        @endforeach
                                    </div>
                                @else
                                    <div id="container-orang-kepercayaan">
                                        <div class="col-md-12" style="border: solid 1px #eee;padding:10px;margin-bottom: 15px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Pilih User</label>
                                                    <select id="team_id" name="user_orang_kepercayaan[]" id="orang_kepecayaan" class="form-control">
                                                        <option value="">Pilih User</option>
                                                        @foreach ($listEmployee as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>  
                                                <div class="col-md-12">
                                                    <label>@lang('app.subcompany')</label>
                                                    <div class="form-group">
                                                        @forelse($subcompanys as $subcompany)
                                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                                <input id="orang_kepercayaan_subcompany_{{$subcompany->id}}" name="sub_company_orang_kepercayaan[0][]" value="{{$subcompany->id}}" type="checkbox" class="checkbox-sub-company">
                                                                <label for="orang_kepercayaan_subcompany_{{$subcompany->id}}" class="label-sub-company">{{$subcompany->name}}</label>
                                                            </div>
                                                        @empty
                                                            @lang('app.noDataFound')
                                                        @endforelse()
                                                    </div>
                                                </div><br>
                                                <div class="col-md-12 active-or-not">
                                                    <label>Aktif?</label>
                                                    <input id="active" name="active[0][]" type="checkbox">
                                                    {{-- <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_on_orang_kepercayaan" name="is_on_orang_kepercayaan[]"/> @lang('modules.employees.yes')
                                                    </div> --}}
                                                </div>
                                            </div><br>
                                        </div><br>
                                    </div>
                                    <div id="container-clone-orang-kepercayaan"></div>
                                @endif
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"><i
                                                class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-default">@lang('app.back')</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- .row -->
        
        {{--Ajax Modal--}}
        <div class="modal fade bs-modal-md in" id="departmentModel" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md" id="modal-data-application">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                    </div>
                    <div class="modal-body">
                        Loading...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn blue">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js'></script>

<!-- Sidebar menu plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<!--Slimscroll JavaScript For custom scroll-->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('plugins/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>

{{--sticky note script--}}
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/icheck/icheck.init.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup-init.js') }}"></script>

<script>
    $('.notificationSlimScroll').slimScroll({
        height: '250'
        , position: 'right'
        , color: '#dcdcdc'
        , });
    $('body').on('click', '.timer-modal', function(){
        var url = '{{ route('admin.all-time-logs.show-active-timer')}}';
        $('#modelHeading').html('Active Timer');
        $.ajaxModal('#projectTimerModal',url);
    });

    $('.datepicker, #start-date, #end-date').on('click', function(e) {
        e.preventDefault();
        $(this).attr("autocomplete", "off");
    });

    function addOrEditStickyNote(id)
    {
        var url = '';
        var method = 'POST';
        if(id === undefined || id == "" || id == null) {
            url =  '{{ route('admin.sticky-note.store') }}'
        } else{

            url = "{{ route('admin.sticky-note.update',':id') }}";
            url = url.replace(':id', id);
            var stickyID = $('#stickyID').val();
            method = 'PUT'
        }

        var noteText = $('#notetext').val();
        var stickyColor = $('#stickyColor').val();
        $.easyAjax({
            url: url,
            container: '#responsive-modal',
            type: method,
            data:{'notetext':noteText,'stickyColor':stickyColor,'_token':'{{ csrf_token() }}'},
            success: function (response) {
                $("#responsive-modal").modal('hide');
                getNoteData();
            }
        })
    }

    // FOR SHOWING FEEDBACK DETAIL IN MODEL
    function showCreateNoteModal(){
        var url = '{{ route('admin.sticky-note.create') }}';

        $("#responsive-modal").removeData('bs.modal').modal({
            remote: url,
            show: true
        });

        $('#responsive-modal').on('hidden.bs.modal', function () {
            $(this).find('.modal-body').html('Loading...');
            $(this).data('bs.modal', null);
        });

        return false;
    }

    // FOR SHOWING FEEDBACK DETAIL IN MODEL
    function showEditNoteModal(id){
        var url = '{{ route('admin.sticky-note.edit',':id') }}';
        url  = url.replace(':id',id);

        $("#responsive-modal").removeData('bs.modal').modal({
            remote: url,
            show: true
        });

        $('#responsive-modal').on('hidden.bs.modal', function () {
            $(this).find('.modal-body').html('Loading...');
            $(this).data('bs.modal', null);
        });

        return false;
    }

    function selectColor(id){
        $('.icolors li.active ').removeClass('active');
        $('#'+id).addClass('active');
        $('#stickyColor').val(id);

    }


    function deleteSticky(id){

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted Sticky Note!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.sticky-note.destroy',':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        $('#stickyBox_'+id).hide('slow');
                        $("#responsive-modal").modal('hide');
                        getNoteData();
                    }
                });
            }
        });
    }


    //getting all chat data according to user
    function getNoteData(){

        var url = "{{ route('admin.sticky-note.index') }}";

        $.easyAjax({
            type: 'GET',
            url: url,
            messagePosition: '',
            data:  {},
            container: ".noteBox",
            error: function (response) {

                //set notes in box
                $('#sticky-note-list').html(response.responseText);
            }
        });
    }
</script>


<script>
    $('.mark-notification-read').click(function () {
        console.log('hello from read notification');
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            type: 'POST',
            url: '{{ route("mark-notification-read") }}',
            data: {'_token': token},
            success: function (data) {
                if (data.status == 'success') {
                    $('.top-notifications').remove();
                    $('.top-notification-count').html('0');
                    $('#top-notification-dropdown .notify').remove();
                }
            }
        });

    });

    $('.show-all-notifications').click(function () {
        var url = '{{ route('show-all-member-notifications')}}';
        $('#modelHeading').html('View Unread Notifications');
        $.ajaxModal('#projectTimerModal', url);
    });

    $('.submit-search').click(function () {
        $(this).parent().submit();
    });

    $(function () {
        $('.selectpicker').selectpicker();
    });

    $('.language-switcher').change(function () {
        var lang = $(this).val();
        $.easyAjax({
            url: '{{ route("admin.settings.change-language") }}',
            data: {'lang': lang},
            success: function (data) {
                if (data.status == 'success') {
                    window.location.reload();
                }
            }
        });
    });

//    sticky notes script
    var stickyNoteOpen = $('#open-sticky-bar');
    var stickyNoteClose = $('#close-sticky-bar');
    var stickyNotes = $('#footer-sticky-notes');
    var viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    var stickyNoteHeaderHeight = stickyNotes.height();

    $('#sticky-note-list').css('max-height', viewportHeight-150);

    stickyNoteOpen.click(function () {
        $('#sticky-note-list').toggle(function () {
            $(this).animate({
                height: (viewportHeight-150)
            })
        });
        stickyNoteClose.toggle();
        stickyNoteOpen.toggle();
    })

    stickyNoteClose.click(function () {
        $('#sticky-note-list').toggle(function () {
            $(this).animate({
                height: 0
            })
        });
        stickyNoteOpen.toggle();
        stickyNoteClose.toggle();
    })



    $('body').on('click', '.right-side-toggle', function () {
        $(".right-sidebar").slideDown(50).removeClass("shw-rside");
    })


    function updateOnesignalPlayerId(userId) {
        $.easyAjax({
            url: '{{ route("member.profile.updateOneSignalId") }}',
            type: 'POST',
            data:{'userId':userId, '_token':'{{ csrf_token() }}'},
            success: function (response) {
            }
        })
    }

    $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "inherit" );
    });

    $('.table-responsive').on('hide.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "auto" );
    })

    $('#mobile-filter-toggle').click(function () {
        $('.filter-section').toggle();
    })

    $('#sticky-note-toggle').click(function () {
        $('#footer-sticky-notes').toggle();
        $('#sticky-note-toggle').hide();
    })

    $(document).ready(function () {
        //Side menu active hack
        setTimeout(function(){
            var getActiveMenu = $('#side-menu  li.active li a.active').length;
        // console.log(getActiveMenu);
            if(getActiveMenu > 0) {
                $('#side-menu  li.active li a.active').parent().parent().parent().find('a:first').addClass('active');
            }

         }, 200);

    })

    $('body').on('click', '.toggle-password', function() {
        var $selector = $(this).parent().find('input.form-control');
        $(this).toggleClass("fa-eye fa-eye-slash");
        var $type = $selector.attr("type") === "password" ? "text" : "password";
        $selector.attr("type", $type);
    });

    var currentUrl = '{{ request()->route()->getName() }}';
    $('body').on('click', '.filter-section-close', function() {
        localStorage.setItem('filter-'+currentUrl, 'hide');

        $('.filter-section').toggle();
        $('.filter-section-show').toggle();
        $('.data-section').toggleClass("col-md-9 col-md-12")
    });

    $('body').on('click', '.filter-section-show', function() {
        localStorage.setItem('filter-'+currentUrl, 'show');

        $('.filter-section-show').toggle();
        $('.data-section').toggleClass("col-md-9 col-md-12")
        $('.filter-section').toggle();
    });

    var currentUrl = '{{ request()->route()->getName() }}';
    var checkCurrentUrl = localStorage.getItem('filter-'+currentUrl);
    if (checkCurrentUrl == "hide") {
        $('.filter-section-show').show();
        $('.data-section').removeClass("col-md-9")
        $('.data-section').addClass("col-md-12")
        $('.filter-section').hide();
    } else if (checkCurrentUrl == "show") {
        $('.filter-section-show').hide();
        $('.data-section').removeClass("col-md-12")
        $('.data-section').addClass("col-md-9")
        $('.filter-section').show();
    }
</script>
        <!-- end::Global Config -->
        <script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
        <script src="{{ asset('plugins/tagify-master/dist/tagify.js') }}"></script>
        <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
        <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
        <script data-name="basic">
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            $('.js-switch').each(function() {
                new Switchery($(this)[0], $(this).data());

            });
            (function(){
                $("#department").select2({
                    formatNoMatches: function () {
                        return "{{ __('messages.noRecordFound') }}";
                    }
                });
                $("#designation").select2({
                    formatNoMatches: function () {
                        return "{{ __('messages.noRecordFound') }}";
                    }
                });
                $("#subcompany").select2({
                    formatNoMatches: function () {
                        return "{{ __('messages.noRecordFound') }}";
                    }
                });
                $("#cabang").select2({
                    formatNoMatches: function () {
                        return "{{ __('messages.noRecordFound') }}";
                    }
                });
                $("#wilayah").select2({
                    formatNoMatches: function () {
                        return "{{ __('messages.noRecordFound') }}";
                    }
                });

        // Chainable event listeners
                    tagify.on('add', onAddTag)
                        .on('remove', onRemoveTag)
                        .on('input', onInput)
                        .on('invalid', onInvalidTag)
                        .on('click', onTagClick);

        // tag added callback
                    function onAddTag(e){
                        tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
                    }

        // tag remvoed callback
                    function onRemoveTag(e){
                    }

        // on character(s) added/removed (user is typing/deleting)
                    function onInput(e){
                    }

        // invalid tag added callback
                    function onInvalidTag(e){
                    }

        // invalid tag added callback
                    function onTagClick(e){
                    }

                })()
        </script>
        <script>
            $('#office_end_time, #office_start_time, #halfday_mark_time').timepicker({
                @if($global->time_format == 'H:i')
                showMeridian: false
                @endif
            });
            $("#joining_date, .date-picker,  #end_date").datepicker({
                    todayHighlight: true,
                    autoclose: true,
                    weekStart:'{{ $global->week_start }}',
                    format: '{{ $global->date_picker_format }}',
                });

                $('#save-form').click(function () {
                    $.easyAjax({
                        url: '{{route('iframe.updateEmployee', [$userDetail->id])}}',
                        container: '#updateEmployee',
                        type: "POST",
                        redirect: true,
                        file: (document.getElementById("image").files.length == 0) ? false : true,
                        data: $('#updateEmployee').serialize()
                    })
                });

                $('#department-setting').on('click', function (event) {
                    event.preventDefault();
                    var url = '{{ route('admin.teams.quick-create')}}';
                    $('#modelHeading').html("@lang('messages.manageDepartment')");
                    $.ajaxModal('#departmentModel', url);
                });

                $('#designation-setting').on('click', function (event) {
                    event.preventDefault();
                    var url = '{{ route('admin.designations.quick-create')}}';
                    $('#modelHeading').html("@lang('messages.manageDepartment')");
                    $.ajaxModal('#departmentModel', url);
                });

                $('#cabang-setting').on('click', function (event) {
                    event.preventDefault();
                    var url = '{{ route('admin.cabang.quick-create')}}';
                    $('#modelHeading').html("@lang('messages.manageDepartment')");
                    $.ajaxModal('#departmentModel', url);
                });
                $('#wilayah-setting').on('click', function (event) {
                    event.preventDefault();
                    var url = '{{ route('admin.wilayah.quick-create')}}';
                    $('#modelHeading').html("@lang('messages.manageDepartment')");
                    $.ajaxModal('#departmentModel', url);
                });
                $('#subcompany-setting').on('click', function (event) {
                    event.preventDefault();
                    var url = '{{ route('admin.subcompany.quick-create')}}';
                    $('#modelHeading').html("@lang('messages.manageDepartment')");
                    $.ajaxModal('#departmentModel', url);
                });
                $("#option_employee").on('change', function(){
                    var status = $(this).is(':checked');
                    if (status) {
                        $("#container_option_employee").show();
                        var list = $("#all_option_employee").val();
                    }else{
                        $("#container_option_employee").hide();
                    }
                })
                $("#tambah-orang-kepercayaan").on('click', function(){
                    var element = $("#container-orang-kepercayaan").clone();
                    element.children('div').prepend("<button type='button' class='delete-me btn btn-sm btn-danger pull-right'>Delete</>");
                    var input = element.find(".checkbox-sub-company");
                    var label = element.find(".label-sub-company");
                    var counter = $("#container-clone-orang-kepercayaan #container-orang-kepercayaan").length+1;
                    element.find(".active-or-not input").attr('name',`active[${counter}][]`)
                    $.each(input, function( index, value ) {
                        $(value).attr('id',$(value).attr('id')+'_'+counter)
                        $(value).attr('name',`sub_company_orang_kepercayaan[${counter}][]`)
                    });
                    $.each(label, function( index, value ) {
                        $(value).attr('for',$(value).attr('for')+'_'+counter)
                    });
                    $("#container-clone-orang-kepercayaan").append(element);
                })
                $(document).on('click',".delete-me", function(){
                    $(this).parent().remove();
                })
        </script>
      
        @yield('script')
	</body>

	<!-- end::Body -->
</html>
