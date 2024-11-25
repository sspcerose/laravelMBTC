@extends('layout.layout')

@include('layouts.adminNav')

<body class="font-inter">

    <div class="lg:pl-20 md:pr-5">

        <h1 class="font-semibold pt-28 px-4 text-4xl"><span></span></h1>
        <h1 class="text-black p-4 font-extrabold text-4xl">Dashboard</h1>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-8">
            <div id="chart1" class="bg-white border border-gray-700 p-4 rounded-2xl shadow-md"></div>
            <div id="chart2" class="bg-white border border-gray-700 p-4 rounded-2xl shadow-md"></div>
            <div id="chart3" class="bg-white  border border-gray-700 p-4 rounded-2xl shadow-md"></div>
        </div>

        <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div id="chart3" class="bg-white p-4 rounded-2xl shadow-md"></div>
            <div id="chart2" class="bg-white p-4 rounded-2xl shadow-md"></div>
        </div> -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        
<div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-8">
<div id="chart4" class="bg-white  border border-gray-700 p-4 rounded-2xl shadow-md"></div>
</div>

<div class="bg-neutral-200 p-4 lg:p-8 rounded-3xl m-4">
    <div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-2 gap-4 lg:gap-8 mb-8">
        <!-- Total Bookings Card -->
        <div class="bg-blue-500 text-white p-3 rounded-2xl lg:h-40 shadow-md w-full max-w-xs">
            <h2 class="text-lg font-bold mb-2">Total Bookings</h2>
            <p class="text-lg lg:text-4xl font-semibold">{{ $totalBookings }}</p>
        </div>
        
        <!-- Registered Users Card -->
        <div class="bg-green-500 text-white p-3 rounded-2xl lg:h-40 shadow-md w-full max-w-xs">
            <h2 class="text-lg font-bold mb-2">Trips Today</h2>
            <p class="text-lg lg:text-4xl font-semibold">{{ $scheduleCount }}</p>
        </div>
        
        <!-- Total Members Card -->
        <div class="bg-green-500 text-white p-3 rounded-2xl lg:h-40 shadow-md w-full max-w-xs">
            <h2 class="text-lg font-bold mb-2">Total Active Members</h2>
            <p class="text-lg lg:text-4xl font-semibold">{{ $activeMembersCount }} / {{ $totalMembers }} </p>
        </div>

        <!-- Duplicate Card for Total Members (adjusted) -->
        <div class="bg-blue-500 text-white p-3 rounded-2xl lg:h-40 shadow-md w-full max-w-xs">
            <h2 class="text-lg font-bold mb-2">Paid Monthly Dues</h2>
            <p class="text-lg lg:text-4xl font-semibold">{{ $paidMembersCount }} / {{ $activeMembersCount }}</p>
        </div>
    </div>
</div>
</div>

        
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
    var options = {
        series: [{
            name: "Bookings",
            data:  @json(array_values($bookingData)),
            
            
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#28a745'] // Set the data label color to green
            }
        },
        stroke: {
            curve: 'smooth',
            colors: ['#28a745']
        },
        title: {
            text: 'MONTHLY RESERVATION',
            align: 'left'
            // offsetY: 330,
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            }
        },
        xaxis: {
            position: 'top',
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();

    var options = {
        series: [{
            name: "Bookings",
            data: @json($destinationCounts)
        }],
        chart: {
            height: 350,
            type: 'bar',
            zoom: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: 'MOST BOOKED DESTINATION',
            align: 'left'
        },
        xaxis: {
            categories: @json($destinationNames)
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart2"), options);
    chart.render();


    var options = {
          series: [{
          name: 'Total Sales',
          data: @json(array_values($salesData))
        }],
          chart: {
          height: 350,
          type: 'bar',
        },
        plotOptions: {
        bar: {
            borderRadius: 10,
            dataLabels: {
                position: 'top', 
            },
           
        }
    },
        colors: ['#228B22'],
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return '₱' + val;
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        //   position: 'top',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
                return '₱' + val;
            }
          }
        
        },
        title: {
          text: 'MONTHLY SALES',
          floating: true,
        //   offsetY: 330,
          align: 'left',
          style: {
            color: '#444'
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();

        var options = {
    series: [{
        name: 'Current Year',
        type: 'area',
        data: @json(array_values($bookingData))
    }, {
        name: 'Last Year',
        type: 'line',
        data: @json(array_values($lastBookingData))
    }],
    title: {
            text: 'TWO-YEAR RESERVATION METRICS',
            align: 'left'
            // offsetY: 330,
        },
    chart: {
        height: 350,
        type: 'line',
    },
    stroke: {
        curve: 'smooth'
    },
    fill: {
        type: 'solid',
        opacity: [0.35, 1],
    },
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 
    markers: {
        size: 0
    },
    yaxis: [{
        title: {
            text: 'Bookings',
        },
    }],
    tooltip: {
        shared: true,
        intersect: false,
        y: {
            formatter: function (y) {
                if (typeof y !== "undefined") {
                    return y.toFixed(0) + " bookings";
                }
                return y;
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#chart4"), options);
chart.render();

</script>

</body>

