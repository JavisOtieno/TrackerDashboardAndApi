@extends('layout')

@section('content')

<div class="side-app">

    <!-- CONTAINER -->
    <div class="main-container container-fluid">

  <!-- PAGE-HEADER -->
  <div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">RTMS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->








     <div class="row">
 
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Recent Visits</h3>
                    </div>
                </div>
                <div class="card-body p-0 mt-2">
                    <div class="m-2" >
                        {{-- <div id="world-map-markers1" class="worldh world-map h-250"></div> --}}
                        <div id="googleMap" class="worldh world-map h-500" ></div>
                    </div>
     
                </div>
            </div>
        </div>

    </div>

     


     <script>
        function myMap() {
            var recentVisits= @json($recentVisits);

        if(recentVisits[0]){
            var mapProp= {
            center:new google.maps.LatLng(Number(recentVisits[0]['outlet']['lat']),
            Number(recentVisits[0]['outlet']['long'])),
            zoom:10,
            };
        }else{
            var mapProp= {
            center:new google.maps.LatLng(-1.286389,36.817223),
            zoom:10,
            };
        }
        
        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

        if(recentVisits.length>0){
        var bounds = new google.maps.LatLngBounds();
        recentVisits.forEach(visit => {
            var lat1 = Number(visit['outlet']['lat']);
            var long1 = Number(visit['outlet']['long']);

            const myPosition = { lat: lat1, lng: long1 };
            console.log(myPosition);

            // var markerElement = new google.maps.marker.AdvancedMarkerElement({
            //         position: myPosition,
            //         map: map,
            //         title: 'Visit Marker', // Add a title if needed
            //     });

                // Extend the bounds to include this marker's position
                bounds.extend(myPosition);

                // var infoContent = "<div><h3>"+visit['outlet']['name']+"</h3>"+
                //     "<p>Latitude: "+lat1+"</p>"+
                //     "<p>Longitude: "+long1+"</p>"+
                //     "<p>Seller: "+visit['user']['name']+"</p>"+
                // "</div>";

                // Create an InfoWindow
                // var infoWindow = new google.maps.InfoWindow({
                //     content: infoContent
                // });
                //
                var infowindow = new google.maps.InfoWindow({
                    content: "<div><h3>"+visit['outlet']['name']+"</h3>"+
                        "<p>Seller: "+( visit['user']===null ? 'Deleted User': visit['user']['name'])+"</p>"+
                        "<p>Latitude: "+lat1+"</p>"+
                        "<p>Longitude: "+long1+"</p>"+
                        "</div>"
                });

                // Add a click event listener to the marker to open the InfoWindow
                // marker.addListener('click', function() {
                //     infoWindow.open(map, marker);
                // });
                
            
           
            var marker = new google.maps.Marker({position: myPosition,
                title: visit['outlet']['name'],
                label: String(visit.id),
                title: visit.outlet.name,
                zIndex: google.maps.Marker.MAX_ZINDEX + Number(visit.id)
            });
            google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });
            marker.setMap(map);
            
        });
        map.fitBounds(bounds);
        var lat1 = recentVisits[0]['outlet']['lat'];
        var long1 = recentVisits[0]['outlet']['long'];
        }
       // console.log(recentVisits);
        
        // const myPosition = { lat: -25.344, lng: 131.031 };
        // //console.log(myPosition);
        // const myPosition2 = { lat: Number(lat1), lng: Number(long1) };
        // //console.log(myPosition2);
        // var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
        // var marker = new google.maps.Marker({position: myPosition});
        // marker.setMap(map);
        // var marker2 = new google.maps.Marker({position: myPosition2});
        // marker2.setMap(map);
        }
        

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=
        AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=myMap"></script>
            {{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALLsNWwOC09xsRAqrK0S7dINi6BpNc7iw&callback=initMap"></script> --}}



    <?php
    // $string ='[';
    
    //             foreach ($salesPerMonth as $sale){
    //                 //echo $sale;
    //                 if($string=='['){ $string =$string.=''.$sale.''; }
    //                 else{
    //                 $string =$string.=','.$sale.'';
    //                 }
    //             }
                
    //             $string=$string.']';

    //             for ($i = 0; $i <= 11; $i++) {
    //             $months[] = date("M", strtotime( date( 'Y-m-01' )." -$i months"));
    //             }
    //             //echo "Before";
    //             //print_r($months);
    //             //echo "After";
    //             $months=array_reverse($months);
    //             //print_r($months);
    //             $stringMonths ='[';
    //             foreach ($months as $month){
    //                 //echo $sale;
    //                 if($stringMonths=='['){ $stringMonths =$stringMonths.='"'.$month.'"'; }
    //                 else{
    //                     $stringMonths =$stringMonths.=',"'.$month.'"';
    //                 }
                    
    //             }
    //             $stringMonths=$stringMonths.']';
    //             //echo $stringMonths;
                
    //             //echo $string;
    //             //print_r($visitsCount);
    //             $stringVisits ='[';
   
    //             foreach ($visitsCount as $visit){
    //                 //echo $sale;
    //                 if($stringVisits=='['){ $stringVisits =$stringVisits.=''.$visit.''; }
    //                 else{
    //                 $stringVisits =$stringVisits.=','.$visit.'';
    //             }
    //             }
                
    //             $stringVisits=$stringVisits.']';
    //             //echo $stringVisits;
                ?>

    
<!-- INTERNAL CHARTJS CHART JS-->
<script src="{{asset('assets/plugins/chart/Chart.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/chart/utils.js')}}"></script>
    
<script>
    // TRANSACTIONS

    var myCanvas = document.getElementById("transactions");
    myCanvas.height = "330";
    
    var myCanvasContext = myCanvas.getContext("2d");
    var gradientStroke1 = myCanvasContext.createLinearGradient(0, 80, 0, 280);
    gradientStroke1.addColorStop(0, 'rgba(232, 38, 70, 0.8)');
    //gradientStroke1.addColorStop(0, 'rgba(108, 95, 252, 0.8) ');
    //gradientStroke1.addColorStop(1, 'rgba(108, 95, 252, 0.2) ');
    gradientStroke1.addColorStop(1, 'rgba(232, 38, 70, 0.2) ');
    
    var gradientStroke2 = myCanvasContext.createLinearGradient(0, 80, 0, 280);
    gradientStroke2.addColorStop(0, 'rgba(5, 195, 251, 0.8)');
    gradientStroke2.addColorStop(1, 'rgba(5, 195, 251, 0.2) ');
    
    
    document.getElementById('transactions').innerHTML = '';
    var myChart;
    var dataSalesMonthGraph= @json($salesPerMonth);
    var dataMonthLabels= @json($months);
    //alert(dataSalesMonthGraph);
    myChart = new Chart(myCanvas, {
    //#E82646 switched from #05c3fb
        type: 'line',
        data: {
            labels: dataMonthLabels,
            type: 'line',
            datasets: [{
                label: 'Total Sales',
                data: dataSalesMonthGraph,
                backgroundColor: gradientStroke1,
                borderColor: "#E82646",
                pointBackgroundColor: '#fff',
                pointHoverBackgroundColor: gradientStroke1,
                pointBorderColor: "#E82646",
                pointHoverBorderColor: gradientStroke1,
                pointBorderWidth: 0,
                pointRadius: 0,
                pointHoverRadius: 0,
                borderWidth: 3,
                fill: 'origin',
                lineTension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        usePointStyle: false,
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: false,
                        drawBorder: false,
                        color: 'rgba(119, 119, 142, 0.08)'
                    },
                    ticks: {
                        autoSkip: true,
                        color: '#b0bac9'
                    },
                    scaleLabel: {
                        display: false,
                        labelString: 'Month',
                        fontColor: 'transparent'
                    }
                },
                y: {
                    ticks: {
                        min: 0,
                        max: 1050,
                        stepSize: 150,
                        color: "#b0bac9",
                    },
                    display: true,
                    grid: {
                        display: true,
                        drawBorder: false,
                        zeroLineColor: 'rgba(142, 156, 173,0.1)',
                        color: "rgba(142, 156, 173,0.1)",
                    },
                    scaleLabel: {
                        display: false,
                        labelString: 'sales',
                        fontColor: 'transparent'
                    }
                }
            },
            title: {
                display: false,
                text: 'Normal Legend'
            }
        }
    });
    function index(myVarVal, myVarVal1) {
        'use strict'
        let gradientStroke = myCanvasContext.createLinearGradient(0, 80, 0, 280);;
        gradientStroke.addColorStop(0, `rgba(${myVarVal}, 0.8)` || 'rgba(232, 95, 252, 0.8)');
        gradientStroke.addColorStop(1, `rgba(${myVarVal}, 0.2)` || 'rgba(232, 95, 252, 0.2) ');
    
        // gradientStroke.addColorStop(0, `rgba(${myVarVal}, 0.8)` || 'rgba(108, 95, 252, 0.8)');
        // gradientStroke.addColorStop(1, `rgba(${myVarVal}, 0.2)` || 'rgba(108, 95, 252, 0.2) ');
    
        myChart.data.datasets[0] = {
            label: 'Total Sales',
            data: dataSalesMonthGraph,
            backgroundColor: gradientStroke,
            borderColor: `rgb(${myVarVal})`,
            pointBackgroundColor: '#fff',
            pointHoverBackgroundColor: gradientStroke,
            pointBorderColor: `rgb(${myVarVal})`,
            pointHoverBorderColor: gradientStroke,
            pointBorderWidth: 0,
            pointRadius: 0,
            pointHoverRadius: 0,
            borderWidth: 3,
            fill: 'origin',
            lineTension: 0.3
        }
        myChart.update();
    
    }


        // RECENT ORDERS OPEN
        var myCanvas = document.getElementById("recentorders");
        var dataVisits= @json($visitsCount);
        var dataSalesGraph= @json($salesGraphCount);
    myCanvas.height = "225";
    new Chart(myCanvas, {
        type: 'bar',
        data: {
            labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            datasets: [{
                barThickness: 8,
                label: 'This Month',
                data: dataVisits,
                backgroundColor: '#61c9fc',
                borderWidth: 2,
                hoverBackgroundColor: '#61c9fc',
                hoverBorderWidth: 0,
                borderColor: '#61c9fc',
                hoverBorderColor: '#61c9fc',
                borderRadius: 10,
            }, {
                barThickness: 8,
                label: 'This Month',
                data: dataSalesGraph,
                backgroundColor: '#f38ff3',
                borderWidth: 2,
                hoverBackgroundColor: '#f38ff3',
                hoverBorderWidth: 0,
                borderColor: '#f38ff3',
                hoverBorderColor: '#f38ff3',
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            },
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        display: false
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            scales: {
                y: {
                    display: false,
                    grid: {
                        display: false,
                        drawBorder: false,
                        zeroLineColor: 'rgba(142, 156, 173,0.1)',
                        color: "rgba(142, 156, 173,0.1)",
                    },
                    scaleLabel: {
                        display: false,
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 25,
                        suggestedMin: 0,
                        suggestedMax: 100,
                        fontColor: "#8492a6",
                        userCallback: function (tick) {
                            return tick.toString() + '%';
                        }
                    },
                },
                x: {
                    display: false,
                    stacked: false,
                    ticks: {
                        beginAtZero: true,
                        fontColor: "#8492a6",
                    },
                    grid: {
                        color: "rgba(142, 156, 173,0.1)",
                        display: false
                    },

                }
            },
            legend: {
                display: false
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        }
    });
    // RECENT ORDERS CLOSED


        // CHARTJS SALES CHART OPEN
        var ctx = document.getElementById('saleschart').getContext('2d');
        var dataSalesGraph= @json($salesGraphCount);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                barPercentage: 0.1,
                barThickness: 6,
                barGap: 0,
                maxBarThickness: 8,
                minBarLength: 2,
                label: 'Total Sales',
                data: dataSalesGraph,
                backgroundColor: [
                    'rgba(5, 195, 251, 0.2)',
                    'rgba(5, 195, 251, 0.2)',
                    '#05c3fb',
                    'rgba(5, 195, 251, 0.2)',
                    'rgba(5, 195, 251, 0.2)',
                    '#05c3fb',
                    '#05c3fb'
                ],
                borderColor: [
                    'rgba(5, 195, 251, 0.5)',
                    'rgba(5, 195, 251, 0.5)',
                    '#05c3fb',
                    'rgba(5, 195, 251, 0.5)',
                    'rgba(5, 195, 251, 0.5)',
                    '#05c3fb',
                    '#05c3fb'
                ],
                pointBorderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 2,
                borderRadius: 10,
                borderWidth: 1
            },]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        display: false
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            responsive: true,
            scales: {
                x: {
                    categoryPercentage: 1.0,
                    barPercentage: 1.0,
                    barDatasetSpacing: 0,
                    display: false,
                    barThickness: 5,
                    barRadius: 0,
                    gridLines: {
                        color: 'transparent',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        fontSize: 2,
                        fontColor: 'transparent'
                    }
                },
                y: {
                    display: false,
                    ticks: {
                        display: false,
                    }
                }
            },
            title: {
                display: false,
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        }
    });

    // CHARTJS SALES CHART CLOSED

     // CHARTJS LEADS CHART  OPEN
     var ctx1 = document.getElementById('leadschart').getContext('2d');
     var dataVisits= @json($visitsCount);
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Date 1', 'Date 2', 'Date 3', 'Date 4', 'Date 5', 'Date 6', 'Date 7' ],
            datasets: [{
                label: 'Total Sales',
                data: dataVisits,
                backgroundColor: 'transparent',
                borderColor: '#f46ef4',
                borderWidth: '2.5',
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'transparent',
                lineTension: 0.3
            },]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        display: false
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        beginAtZero: true,
                        fontSize: 10,
                        fontColor: "transparent",
                    },
                    title: {
                        display: false,
                    },
                    grid: {
                        display: true,
                        color: 'transparent																																					',
                        drawBorder: false,
                    },
                    categoryPercentage: 1.0,
                    barPercentage: 1.0,
                    barDatasetSpacing: 0,
                    display: false,
                    barThickness: 5,
                },
                y: {
                    display: false,
                    ticks: {
                        display: false,
                    }
                }
            },
            title: {
                display: false,
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        }
    });
    // CHARTJS LEADS CHART CLOSED

      // PROFIT CHART OPEN
      var ctx2 = document.getElementById('profitchart').getContext('2d');
      var dataGraphOutlets= @json($outletsGraphCount);
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                barPercentage: 0.1,
                barThickness: 6,
                maxBarThickness: 8,
                minBarLength: 2,
                label: 'Total Sales',
                barGap: 0,
                barSizeRatio: 1,
                data: dataGraphOutlets,
                backgroundColor: '#4ecc48',
                borderColor: '#4ecc48',
                pointBackgroundColor: '#fff',
                pointHoverBackgroundColor: '#4ecc48',
                pointBorderColor: '#4ecc48',
                pointHoverBorderColor: '#4ecc48',
                pointBorderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 2,
                borderRadius: 10,
                borderWidth: 1
            },]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        display: false
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            responsive: true,
            scales: {
                x: {
                    categoryPercentage: 1.0,
                    barPercentage: 1.0,
                    barDatasetSpacing: 0,
                    display: false,
                    barThickness: 5,
                    gridLines: {
                        color: 'transparent',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        fontSize: 2,
                        fontColor: 'transparent'
                    }
                },
                y: {
                    display: false,
                    ticks: {
                        display: false,
                    }
                }
            },
            title: {
                display: false,
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        }
    });
    // PROFIT CHART CLOSED

    // COST CHART OPEN
    var ctx3 = document.getElementById('costchart').getContext('2d');
    var dataGraphProducts= @json($productsGraphCount);
    new Chart(ctx3, {
        type: 'line',
        data: {
            labels: ['Date 1', 'Date 2', 'Date 3', 'Date 4', 'Date 5', 'Date 6', 'Date 7'],
            datasets: [{
                label: 'Total Sales',
                data: dataGraphProducts,
                backgroundColor: 'transparent',
                borderColor: '#f7ba48',
                borderWidth: '2.5',
                pointBorderColor: 'transparent',
                pointBackgroundColor: 'transparent',
                lineTension: 0.3
            },]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        display: false
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            responsive: true,
            scales: {
                x: {
                    categoryPercentage: 1.0,
                    barPercentage: 1.0,
                    barDatasetSpacing: 0,
                    display: false,
                    barThickness: 5,
                    gridLines: {
                        color: 'transparent',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        fontSize: 2,
                        fontColor: 'transparent'
                    }
                },
                y: {
                    display: false,
                    ticks: {
                        display: false,
                    }
                }
            },
            title: {
                display: false,
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        }
    });
    // COST CHART CLOSED
</script>


<script>
        var ctx = document.getElementById("chartBar1").getContext('2d');
        var topSellerUserNames= @json($topSellerUserNames);
        var topSellerSalesCount= @json($topSellerSalesCount);

       // alert($topSellerUserNames);
        
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: topSellerUserNames,
            datasets: [{
                label: 'Sales',

                data: topSellerSalesCount,
                borderWidth: 2,
                backgroundColor: '#E04D53',
                borderColor: '#E04D53',
                borderWidth: 2.0,
                pointBackgroundColor: '#ffffff',

            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true
            },
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true,
                        stepSize: 150,
                        color: "#9ba6b5",
                    },
                    grid: {
                        color: 'rgba(119, 119, 142, 0.2)'
                    }
                },
                x: {
                    barPercentage: 0.4,
                    barValueSpacing: 0,
                    barDatasetSpacing: 0,
                    barRadius: 0,
                    ticks: {
                        display: true,
                        color: "#9ba6b5",
                    },
                    grid: {
                        display: false,
                        color: 'rgba(119, 119, 142, 0.2)'
                    }
                }
            },
            legend: {
                labels: {
                    fontColor: "#9ba6b5"
                },
            },
        }
    });
    </script>

    <script>

        
    /* Bar-Chart2*/
    var ctx = document.getElementById("chartBar2");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [{
                label: "Data1",
                data: [65, 59, 80, 81, 56, 55, 40],
                borderColor: "#6c5ffc",
                borderWidth: "0",
                backgroundColor: "#6c5ffc"
            }, {
                label: "Data2",
                data: [28, 48, 40, 19, 86, 27, 90],
                borderColor: "#05c3fb",
                borderWidth: "0",
                backgroundColor: "#05c3fb"
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    barPercentage: 0.4,
                    barValueSpacing: 0,
                    barDatasetSpacing: 0,
                    barRadius: 0,
                    ticks: {
                        color: "#9ba6b5",
                    },
                    grid: {
                        color: 'rgba(119, 119, 142, 0.2)'
                    }
                },
                y: {
                    ticks: {
                        beginAtZero: true,
                        color: "#9ba6b5",
                    },
                    grid: {
                        color: 'rgba(119, 119, 142, 0.2)'
                    },
                }
            },
            legend: {
                labels: {
                    color: "#9ba6b5"
                },
            },
        }
    });

    </script>

        



    @endsection
