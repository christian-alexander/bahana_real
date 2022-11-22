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

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

		<!--end::Layout Skins -->
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
          
  
#legend {
  font-family: Arial, sans-serif;
  background: #fff;
  padding: 10px;
  margin: 10px 0;
  border: 3px solid #000;
    overflow: scroll;
    height: 400px;
}

#legend h3 {
  margin-top: 0;
}

#legend a {
  cursor: default;
}

#legend div {
  margin-bottom:5px;
}

.btn{
  display: block;
  cursor: pointer;
  padding: 5px 10px;
  border: 3px solid #000;
  margin-top: 10px;
  text-decoration: none;
  color: #000; text-transform: uppercase;
  font-size: 22px; text-align: center;
  background: #ececec;
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
						
						<!--begin::Form-->
                        @if (!empty($arr_tracker) && $arr_tracker != '[]')
                            <div id="address-map-container" style="width:100%;height:600px; ">
                                <div style="width: 100%; height: 100%" id="map"></div>
                            </div>

                            <a href="{{route('report.tracker.findIframeFilter',$_SERVER['QUERY_STRING'])}}" class="btn">Tekan di sini untuk Filter</a>

                            <div id="legend"><h3>Track History</h3></div>
                        @else
                            <h2><center>Tidak ada data</center></h2>
                        @endif
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
    <script type="text/javascript" defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"></script>


    <script>  
        @if (!empty($arr_tracker))
        var tracker = "{{$arr_tracker}}";
            var trackerSort = "{{$tracker_sort}}";
            var label = "{{$arr_label}}";
            tracker = JSON.parse(tracker.replace(/&quot;/g,'"'));
            trackerSort = JSON.parse(trackerSort.replace(/&quot;/g,'"'));
            label = JSON.parse(label.replace(/&quot;/g,'"'));
            var map;
      		function initMap() {
              map = new google.maps.Map(document.getElementById("map"), {
                  zoom: 12,
                  center: tracker[0],
                  mapTypeId: "terrain"
              });

              const flightPlanCoordinates = tracker;
              const flightPath = new google.maps.Polyline({
                  path: flightPlanCoordinates,
                  geodesic: true,
                  strokeColor: "#FF0000",
                  strokeOpacity: 1.0,
                  strokeWeight: 2
              });
              flightPath.setMap(map);
              var count = 1;
              const legend = document.getElementById("legend");
              for (var i = 0; i < label.length; i++) {
                // if(i%25==0){
                  new google.maps.Marker({
                    position: tracker[i],
                    label: count.toString(),
                    title:label[i],
                    map:map,
                  });
                  const div = document.createElement("div");

                  var str_manual = (trackerSort[i].is_manual == '1') ? ' (Lacak Manual)' : '';

                    if (trackerSort[i].type != null) {
                        if (trackerSort[i].type=='clock_in') {
                            div.innerHTML = '<a onclick="changeCenter('+tracker[i].lat+','+tracker[i].lng+')">' + count + '.' + label[i] + '( Clock In )'+str_manual+' ('+trackerSort[i].type_lacak+')</a>';
                        }else if(trackerSort[i].type=='clock_out'){
                            div.innerHTML = '<a onclick="changeCenter('+tracker[i].lat+','+tracker[i].lng+')">' + count + '.' + label[i] + '( Clock Out )'+str_manual+' ('+trackerSort[i].type_lacak+')</a>';
                        }else{
                            div.innerHTML = '<a onclick="changeCenter('+tracker[i].lat+','+tracker[i].lng+')">' + count + '.' + label[i] + '( '+trackerSort[i].type+' )'+str_manual+' ('+trackerSort[i].type_lacak+')</a>';
                        }
                    }else{
                        div.innerHTML = '<a onclick="changeCenter('+tracker[i].lat+','+tracker[i].lng+')">' + count + '.' + label[i] + str_manual+' ('+trackerSort[i].type_lacak+')</a>';
                    }
                  legend.appendChild(div);
                  
                  
                  count++;
                // }
                
              }
              
                // map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
              
                // Define the symbol, using one of the predefined paths ('CIRCLE')
              // supplied by the Google Maps JavaScript API.
              const lineSymbol = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 4,
                strokeColor: "#900",
              };
              // Create the polyline and add the symbol to it via the 'icons' property.
              const line = new google.maps.Polyline({
                path: tracker,
                icons: [
                  {
                    icon: lineSymbol,
                    offset: "100%",
                  },
                ],
                map: map,
              });
              animateCircle(line);
              
            }
      		// Use the DOM setInterval() function to change the offset of the symbol
            // at fixed intervals.
            function animateCircle(line) {
              let count = 0;
              window.setInterval(() => {
                count = (count + 1) % 200;
                const icons = line.get("icons");
                icons[0].offset = count / 2 + "%";
                line.set("icons", icons);
              }, 20);
            }
      		function changeCenter(lat, long){
                  map.setCenter(new google.maps.LatLng(lat,long));
                  map.setZoom(18);
            }
        @endif

        // var showing = 'all';

        // $(document).ready(function(){
        //   $('.btn').click(function(e){
        //     e.preventDefault();
            
        //     if(showing == 'is_manual'){
        //       $('#legend div').hide();
        //       $('#legend div').each(function(){
        //         $(this).find("a:contains(Lacak Manual)").parent('div').show();
        //       });

        //       showing = 'all'
        //     }else{
        //       $('#legend div').show();
        //       showing = 'is_manual'
        //     }
        //   });
        // });

    </script>
      
        @yield('script')
	</body>

	<!-- end::Body -->
</html>
