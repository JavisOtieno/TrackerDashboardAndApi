<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Locations Map</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Locations map</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->


<div class="col-xl-12">

    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">Locations Map</h3>
            <a type="button" href="/add-location" class="btn btn-danger col-md-3" style="margin-left: auto;margin-right:20px;margin-top:10px;margin-bottom:10px;color:white;">Add Location</a>
        </div> --}}


        <div class="card-body">
            <div>
                <div id="googleMapLocations" class="worldh world-map h-500" ></div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

    <!-- MODAL EFFECTS -->
    <div class="modal fade" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Delete</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to delete?</h6>
                    <strong id="itemDetails">Details</strong>
                </div>
                <div class="modal-footer">
                    <a id="confirmDelete" class="btn btn-danger" style="color: white">Delete</a> <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

            <!-- Modal -->
            <div class="modal fade" id="largemodal">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Map</h5>
                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <iframe id="mapEmbededForNow" style="width:100%;height:400px;" src = "https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d4084493.7307095355!2d36.50569875426991!3d-1.3175850003645804!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2ske!4v1715327211380!5m2!1sen!2ske"></iframe>
                            <!--<div id="googleMap" style="width:100%;height:400px;"></div>-->
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    
                </div>
            </div>
    
<script>
    function deleteItem(id,name,sku,pieces){
        //alert(id);
        var itemDetails = document.getElementById("itemDetails");
        itemDetails.innerHTML = "Location Id: "+id+"<br/> Location Name: "+name;
        var confirmDeleteLink = document.getElementById('confirmDelete'); //or grab it by tagname etc
        confirmDeleteLink.href = "/deletelocation/"+id;
    }
    function embedMap(lat,long){
        //alert(id);
        var mapEmbedFrame = document.getElementById("mapEmbededForNow");
        mapEmbedFrame.src = "https://maps.google.com/maps?q="+lat+","+long+"&hl=es;z=14&amp&output=embed";
        //var confirmDeleteLink = document.getElementById('confirmDelete'); //or grab it by tagname etc
        //confirmDeleteLink.href = "/deletesale/"+id;
    }
  </script>

  

  
    <script src="https://maps.googleapis.com/maps/api/js?key=
    AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=myMap2"></script> --}}

    <script>
        let map;
        let markers = [];  // Store marker references
    
        function myMap2() {
            var locations = @json($locations);
    
            console.log("locations 0");
            console.log(locations[0]);
            console.log("locations 0");
    
            var mapProp = {
                center: new google.maps.LatLng(-1.286389, 36.817223),
                zoom: 10,
            };
    
            document.addEventListener("DOMContentLoaded", function() {
                var googleMapsDiv = document.getElementById("googleMapLocations");
                map = new google.maps.Map(googleMapsDiv, mapProp);
    
                // $('#userFilter').select2();
    
                // Populate user filter dropdown
                // populateUserFilter(visits);
    
                // Initial map update with all visits
                updateMap(locations);
    
                $('#userFilter, #locationFilter, #fromDate, #toDate').on('change', function() {
                applyFilters(locations);
                });
                
    
    
            });
        }
    
        function applyFilters(locations) {
    
                    var selectedLocation = $('#locationFilter').val();
                    var selectedUser = $('#userFilter').val();
                    var fromDate = $('#fromDate').val();
                    var toDate = $('#toDate').val();
                    
                   // var filteredVisits = selectedUser ? visits.filter(visit => visit.user.name === selectedUser ) : visits;
                    var filteredLocations = locations.filter(location => {
                                const userMatches = selectedUser ? location.user.name === selectedUser : true;
                                const locationMatches = selectedLocation ? location.name === selectedLocation : true;
                                        // Convert visit.created_at to date object
                                var locationDate = new Date(location.created_at);
    
                                // Filter by date range
                                const fromDateMatches = fromDate ? locationDate >= new Date(fromDate) : true;
                                const toDateMatches = toDate ? locationDate <= new Date(toDate) : true;
                                return userMatches && locationMatches && fromDateMatches && toDateMatches;
                            });
                            
                    updateMap(filteredLocations);
    
        }
    
        function populateUserFilter(locations) {
            const userFilter = document.getElementById("userFilter");
            const users = [...new Set(locations.map(location => location.user.name))];
    
            // Add an option for 'All Users'
            const allUsersOption = document.createElement('option');
            allUsersOption.value = '';
            allUsersOption.text = 'All Users';
            userFilter.add(allUsersOption);
    
            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user;
                option.text = user;
                userFilter.add(option);
            });
        }
    
        function updateMap(locations) {
            clearMarkers();
    
            if (locations.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                locations.forEach(location => {
                    var lat1 = Number(location['lat']);
                    var long1 = Number(location['long']);
                    const myPosition = { lat: lat1, lng: long1 };
    
    
                    var date = new Date(location.created_at);
                    var hours = date.getHours();
                    var minutes = date.getMinutes();
                    var day = date.getDate();
                    var month = date.getMonth() + 1; // Months are zero-based, so add 1
                    var year = date.getFullYear();
    
                    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
                    // Get month name from array
                    var monthName = monthNames[month];
    
                    var locationDate = day + '-' + monthName + '-' + year;
    
                    // Pad single digit minutes with a leading zero
                    minutes = minutes < 10 ? '0' + minutes : minutes;
    
                    hours = hours < 10 ? '0' + hours : hours;
    
                    var locationTime = hours + ':' + minutes;
    
                    const infowindow = new google.maps.InfoWindow({
                        content: "<div><h3>" + 
                            // location['name'] + 
                            "</h3>" +
                            "<p>Seller: " 
                                // +(location.user === null ? 'Deleted User' : location.user['name']) 
                                + "</p>" +
                            "<p>Latitude: " + lat1 + "</p>" +
                            "<p>Longitude: " + long1 + "</p>" +
                            "<p><strong>Created:</strong> "+locationDate+" "+locationTime+"</p>"+
                            "View: <a target='_blank' href='/editlocation/" + location.id + "'>View Location Details</a>" +
                        
                            "</div>"
                    });
    
                    var marker = new google.maps.Marker({
                        position: myPosition,
                        // title: location['name'],
                        label: String(location.id),
                        title: location.name,
                        zIndex: google.maps.Marker.MAX_ZINDEX + Number(location.id)
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open(map, marker);
                    });
                    marker.setMap(map);
    
                    markers.push(marker);  // Add marker to the array
    
                    bounds.extend(myPosition);
                });
    
                map.fitBounds(bounds);
            }
        }
    
        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));  // Remove each marker from the map
            markers = [];  // Clear the markers array
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=myMap2"></script>
    
    