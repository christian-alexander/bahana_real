<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<base href="">
		<meta charset="utf-8" />
		<title>Bahana Group | Surat Permintaan Kapal</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

		<!--end::Fonts -->
		
		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="{{asset('public/css/laravel-mix/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="{{asset('public/iframe/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('public/iframe/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('public/iframe/assets/css/kstyles.css')}}" rel="stylesheet" type="text/css" />

		<link type="text/css" href="{{asset('public/vendor/signature/css/jquery-ui.css')}}" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{asset('public/vendor/signature/css/jquery.signature.css')}}">

		<link href="{{asset('public/css/laravel-mix/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('public/css/laravel-mix/style.bundle.css')}}" rel="stylesheet" type="text/css" />

		{{-- <link type="text/css" href="{{asset('public/vendor/signature/css/jquery-ui.css')}}" rel="stylesheet">  --}}

		<link rel="stylesheet" type="text/css" href="{{ asset('public/vendor/datatables/DataTables-1.10.22/css/jquery.dataTables.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('public/vendor/datatables/Scroller-2.0.3/css/scroller.dataTables.css')}}">

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="{{asset('public/iframe/assets/media/logos/favicon.ico')}}" />
		<style>
			.kbw-signature { 
				width: 100%; 
				height: 180px;
			}
			#signaturePad canvas{
				width: 100% !important;
				height: auto;
			}
			.wrapper-scroll{
				overflow-x: auto;
			}
		</style>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-page--loading-enabled kt-page--loading kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-menu kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--left kt-aside--fixed kt-page--loading">

		<div class="kt-container  kt-grid__item kt-grid__item--fluid">
			<div class="row">
				<div class="col-md-6 margin-auto">

					<!--begin::Portlet-->
					<div class="kt-portlet" >
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
                                    @yield('title')
								</h3>
							</div>
							<div>
								@yield('after-title')
							</div>
						</div>
						<!--begin::Form-->
                        @yield('body')
						<!--end::Form-->
					</div>
				</div>
			</div>
		</div>

					<!--end::Portlet-->

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#591df1",
						"light": "#ffffff",
						"dark": "#282a3c",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>

		<!-- end::Global Config -->
		<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
		<script src="{{ asset('public/js/laravel-mix/jquery.min.js') }}"></script>



		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="{{asset('public/iframe/assets/plugins/global/plugins.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('public/js/laravel-mix/scripts.bundle.js')}}" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="{{asset('public/iframe/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script>
		<script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
        <script src="{{asset('public/iframe/assets/plugins/custom/gmaps/gmaps.js')}}" type="text/javascript"></script>
        <script src="{{asset('public/iframe/assets/js/pages/crud/forms/widgets/select2.js')}}" type="text/javascript"></script>
				<script src="{{asset('public/iframe/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js')}}" type="text/javascript"></script>
				<script src="{{asset('public/iframe/assets/js/pages/crud/forms/widgets/bootstrap-timepicker.js')}}" type="text/javascript"></script>
        {{-- <script src="{{asset('public/iframe/assets/plugins/custom/gmaps/gmaps.js')}}" type="text/javascript"></script> --}}
        <script src="{{asset('public/js/laravel-mix/select2.js')}}" type="text/javascript"></script>

		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<script src="{{asset('public/js/laravel-mix/dashboard.js')}}" type="text/javascript"></script>

		{{-- <script type="text/javascript" src="{{asset('public/js/laravel-mix/jquery-ui.min.js')}}"></script> --}}

		<script src="{{asset('public/vendor/signature/js/signature_pad.umd.js')}}"></script>
  		<script src="{{asset('public/js/laravel-mix/app.js')}}"></script>

		<script type="text/javascript" src="{{asset('public/vendor/datatables/DataTables-1.10.22/js/jquery.dataTables.js')}}"></script>
		<script type="text/javascript" src="{{asset('public/vendor/datatables/Scroller-2.0.3/js/dataTables.scroller.js')}}"></script>
		<script type="text/javascript" src="{{asset('public/vendor/datatables/Scroller-2.0.3/js/scroller.dataTables.js')}}"></script>

        <!--end::Page Scripts -->
        @yield('script')

		<script>
			$('.my-select2').select2();
			$('#my-select2').select2();
		</script>
	</body>

	<!-- end::Body -->
</html>
