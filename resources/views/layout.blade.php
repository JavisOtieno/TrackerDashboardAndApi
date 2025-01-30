@include('components/header')
@include('components/header-extension-main')

    @yield('content')

    @include('components/footer-main')

    {{-- <script>
        
      // WORLD MAP MARKER
      $('#world-map-markers1').vectorMap({
          map: 'world_mill_en',
          scaleColors: ['#6c5ffc', '#e82646', '#05c3fb'],
  
          normalizeFunction: 'polynomial',
  
          hoverOpacity: 0.7,
  
          hoverColor: false,
  
          regionStyle: {
  
              initial: {
  
                  fill: '#edf0f5'
              }
          },
          markerStyle: {
              initial: {
                  r: 6,
                  'fill': '#6c5ffc',
                  'fill-opacity': 0.9,
                  'stroke': '#fff',
                  'stroke-width': 9,
                  'stroke-opacity': 0.2
              },
  
              hover: {
                  'stroke': '#fff',
                  'fill-opacity': 1,
                  'stroke-width': 1.5
              }
          },
          backgroundColor: 'transparent',
          markers: [{
              latLng: [1.3, -101.38],
              name: 'USA',
          }, {
              latLng: [22.5, 1.51],
              name: 'India'
          }, {
              latLng: [50.02, 80.55],
              name: 'Bahrain'
          }, {
              latLng: [3.2, 73.22],
              name: 'Maldives'
          }, {
              latLng: [35.88, 14.5],
              name: 'Malta'
          },]
      });
  
      </script> --}}

    {{-- <script>
        function myMap() {
        var mapProp= {
          center:new google.maps.LatLng(51.508742,-0.120850),
          zoom:5,
        };
        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
        }
        myMap();
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY&callback=myMap"></script>

<script> --}}
  
  {{-- //var table = $('#responsive-datatable').DataTable();
// $('#nameInput').on('keyup', function() {
//         table.column(1).search(this.value).draw(); // Column index 0 for 'Name'
//     });

//     $('#phoneInput').on('keyup', function() {
//         table.column(2).search(this.value).draw(); // Column index 1 for 'Position'
//     }); --}}
</script>

