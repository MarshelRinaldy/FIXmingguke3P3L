@extends('layout')

@section('content')
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            /* This will be used if the image fails to load */
            margin: 0;
            padding: 0;
            background-image: url('../assets/images/bgcake2.jpg');
            background-size: cover;
            /* Ensure the image covers the entire background */
            background-repeat: no-repeat;
            /* Prevent the image from repeating */
            background-position: center center;
            /* Center the image */
        }


        .navbar {
            background-color: #000000;
            padding: 1rem;
            color: white;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 300;
        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .container {
            flex: 1;
            min-width: 400px;
            padding: 2rem;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .container:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 2.25rem;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #2c3e50;
            font-weight: 300;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }

        .spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
        }

        .spinner div {
            width: 50px;
            height: 50px;
            border: 6px solid #ff9d2e;
            border-top: 6px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="spinner" id="spinner">
        <div></div>
    </div>

    <div class="navbar">
        <h1 style="color: white" class="text-center">Sales Dashboard</h1>
    </div>

    <div class="content-wrapper">
        <div class="container">
            <h1>Chart Bar Hasil Penjualan Produk</h1>
            <canvas id="chartPenjualanBulanan"></canvas>
        </div>

        <div class="container">
            <h1>Chart Line Hasil Penjualan Produk</h1>
            <canvas id="chartPenjualanBulananLine"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var spinner = document.getElementById('spinner');

            var allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October',
                'November', 'December'
            ];

            @if (!empty($labels) && !empty($data) && is_array($labels) && is_array($data) && count($labels) === count($data))
                var monthlyData = {};
                allMonths.forEach(function(month) {
                    monthlyData[month] = 0;
                });

                @foreach ($labels as $index => $month)
                    monthlyData['{{ $month }}'] = {{ $data[$index] }};
                @endforeach

                var chartData = allMonths.map(function(month) {
                    return monthlyData[month];
                });

                var ctx = document.getElementById('chartPenjualanBulanan').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: allMonths,
                        datasets: [{
                            label: 'Jumlah Uang',
                            data: chartData,
                            backgroundColor: 'rgba(52, 152, 219, 0.2)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                var ctxLine = document.getElementById('chartPenjualanBulananLine').getContext('2d');
                var chartLine = new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: allMonths,
                        datasets: [{
                            label: 'Jumlah Uang',
                            data: chartData,
                            backgroundColor: 'rgba(52, 152, 219, 0.2)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            @else
                var ctx = document.getElementById('chartPenjualanBulanan').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: allMonths,
                        datasets: [{
                            label: 'No Data',
                            data: new Array(allMonths.length).fill(0),
                            backgroundColor: 'rgba(0, 0, 0, 0.2)',
                            borderColor: 'rgba(0, 0, 0, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                var ctxLine = document.getElementById('chartPenjualanBulananLine').getContext('2d');
                var chartLine = new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: allMonths,
                        datasets: [{
                            label: 'No Data',
                            data: new Array(allMonths.length).fill(0),
                            backgroundColor: 'rgba(0, 0, 0, 0.2)',
                            borderColor: 'rgba(0, 0, 0, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            @endif

            spinner.style.display = 'none';
        });
    </script>
@endsection
