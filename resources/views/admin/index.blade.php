@extends('admin.layouts.admin-dash-layout')
@section('title','Dashboard')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/0.2.0/Chart.min.js" type="text/javascript"></script>
<div class="container p-2">
  <div class="row">
    <div class="col-4">
      <div class="info-box bg-success p-3">
        <span class="info-box-icon"><i class="fas fa-child"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Anak</span>
          <span class="info-box-number"><h4>{{count($data)}}</h4></span>
        </div>
      </div>
    </div>
    <div class="col-4">
      <div class="info-box bg-primary p-3">
        <span class="info-box-icon"><i class="fas fa-user"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Jumlah Laki laki<span>
          <span class="info-box-number"><h4>{{$dataLakiLaki}}</h4></span>
        </div>
      </div>
    </div>
    <div class="col-4">
      <div class="info-box bg-warning p-3">
        <span class="info-box-icon"><i class="fas fa-user"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Jumlah Perempuan</span>
          <span class="info-box-number"><h4>{{$dataPerempuan}}</h4></span>
        </div>
      </div>
    </div>
    <div class="col-4">
      <div class="info-box bg-danger p-3">
        <span class="info-box-icon"><i class="fas fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Jumlah Laporan Bulanan</span>
          <span class="info-box-number"><h4>{{$dataStatus}}</h4></span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row">
  <div class="col-6">
        <div class="card card-success card-outline">
              <div class="card-header">
                <h3 class="card-title">Perbandingan Data Status Gizi Anak Gender</h3>
              </div>
              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <canvas id="donutChart" style="min-height: 400px; height: 400px; max-height: 400px; max-width: 100%; display: block; width: 487px;" width="487" height="250" class="chartjs-render-monitor"></canvas>
              </div>
            </div>
      </div>
    <div class="col-6">
        <div class="card card-success card-outline">
              <div class="card-header">
                <h3 class="card-title">Perbandingan Data Status Gizi Anak</h3>
              </div>
              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <canvas id="donutChart2" style="min-height: 400px; height: 400px; max-height: 400px; max-width: 100%; display: block; width: 487px;" width="487" height="250" class="chartjs-render-monitor"></canvas>
              </div>
            </div>
      </div>
    </div>

  <div class="row">
    
  <div class="col-8">
    <div class="card card-success card-outline">
      <div class="card-header">
        <h3 class="card-title">Perbandingan per Anak</h3>
      </div>
      <div class="card-body">
        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
          <canvas id="barChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 456px;" width="456" height="250" class="chartjs-render-monitor"></canvas>
        </div>
      </div>
    </div>
    
<!-- 
    <div class="card card-success card-outline">
      <div class="card-header">
        <h3 class="card-title">Perbandingan orangtua</h3>
      </div>
      <div class="card-body">
        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
          <canvas id="barChart2" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 456px;" width="456" height="250" class="chartjs-render-monitor"></canvas>
        </div>
      </div>
      </div> -->

    <!-- <div class="card card-success card-outline">
      <div class="card-header">
        <h3 class="card-title">Perbandingan per Anak</h3>
      </div>
      <div class="card-body">
        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
        <canvas id="areaChart"  style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 456px;" width="456" height="250" class="chartjs-render-monitor"></canvas>
        </div>
      </div>
    </div> -->
    <!--  list -->
    <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Latest Reports</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Name</th>
                      <th>Status</th>
                      <th>Tanggal</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($dataListReport as $d)
                      <tr>
                        <td><a href="pages/examples/invoice.html">{{ $loop->iteration }}</a></td>
                        <td>{{$d->name}}</td>
                        <td>
                          @if($d->status == "Baik")
                          <span class="badge badge-success">{{$d->status}}</span>
                          @elseif($d->status == "Buruk")
                          <span class="badge badge-danger">{{$d->status}}</span>
                          @elseif($d->status == "Kurang")
                          <span class="badge badge-warning">{{$d->status}}</span>
                          @elseif($d->status == "ResikoLebih")
                          <span class="badge badge-primary">{{$d->status}}</span>
                          @elseif($d->status == "Lebih")
                          <span class="badge badge-info">{{$d->status}}</span>
                          @elseif($d->status == "Obesitas")
                          <span class="badge badge-secondary">{{$d->status}}</span>
                          @endif
                        </td>
                        <td>
                          <div class="sparkbar" data-color="#00a65a" data-height="20">{{$d->updated_at}}</div>
                        </td>
                      </tr>
                    @endforeach
                    
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <!-- <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Tambah Laporan</a> -->
                <!-- <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">Lihat Semua</a> -->
              </div>
              <!-- /.card-footer -->
            </div>
    <!--  /list -->
  </div>
    <div class="col-4">
       <!-- /.card-header -->
       <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Name</th>
                      <th>Status</th>
                      <th>Tanggal</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($dataListReport as $d)
                      <tr>
                        <td><a href="pages/examples/invoice.html">{{ $loop->iteration }}</a></td>
                        <td>{{$d->name}}</td>
                        <td>
                          @if($d->status == "Baik")
                          <span class="badge badge-success">{{$d->status}}</span>
                          @elseif($d->status == "Buruk")
                          <span class="badge badge-danger">{{$d->status}}</span>
                          @elseif($d->status == "Kurang")
                          <span class="badge badge-warning">{{$d->status}}</span>
                          @elseif($d->status == "ResikoLebih")
                          <span class="badge badge-primary">{{$d->status}}</span>
                          @elseif($d->status == "Lebih")
                          <span class="badge badge-info">{{$d->status}}</span>
                          @elseif($d->status == "Obesitas")
                          <span class="badge badge-secondary">{{$d->status}}</span>
                          @endif
                        </td>
                        <td>
                          <div class="sparkbar" data-color="#00a65a" data-height="20">{{$d->updated_at}}</div>
                        </td>
                      </tr>
                    @endforeach
                    
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
      </div>
    </div>
   
   
    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- ChartJS -->
    <script src="../../plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE App -->
    <script>

$(function () {

  //Random color
  function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}


      //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = document.getElementById("barChart").getContext("2d")
  
   var barChartData= {
        labels  : [
          @foreach ($data as $d)
            '{{$d->name}}',
          @endforeach
        ],
        datasets:[
          {
            label               : ['Jumlah Monitoring'],
            backgroundColor     : 'rgba(60,101,118,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
            @foreach ($datareports as $d)
            '{{$d}}',
            @endforeach
          ]
          },

        ],
   }

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

    //line
      var areaChartCanvas = document.getElementById("areaChart").getContext("2d")
      var areaChartData = {
        labels  : [
           @foreach ($data as $d)
            '{{$d->name}}',
              @endforeach
        ],
        datasets:[
          {
            label               : ['age'],
            backgroundColor     : 'rgba(60,141,188,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : [
            @foreach ($data as $d)
              '{{$d->age}}',
            @endforeach
          ] 
          },
          {
            label               : ['weight'],
            backgroundColor     : 'rgba(210, 214, 222, 1)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : [
              @foreach ($data as $d)
                '{{$d->weight}}',
              @endforeach
          ]
          },
          {
            label               : ['length'],
            backgroundColor     : 'rgba(60,101,118,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
            @foreach ($data as $d)
              '{{$d->length}}',
            @endforeach
          ]
          },

        ],
      }

      var areaChartOptions = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines : {
              display : false,
            }
          }],
          yAxes: [{
            gridLines : {
              display : false,
            }
          }]
        }
      }

      // This will get the first returned node in the jQuery collection.
      new Chart(areaChartCanvas, {
        type: 'line',
        data: areaChartData,
        options: areaChartOptions
      })
    })
    </script>
    <script>
        var pieData = [
            {
                value: 20,
                color:"#878BB6"
            },
            {
                value : 40,
                color : "#4ACAB4"
            },
            {
                value : 10,
                color : "#FF8153"
            },
            {
                value : 30,
                color : "#FFEA88"
            }
        ];
        // Get the context of the canvas element we want to select
        var countries= document.getElementById("countries").getContext("2d");
        new Chart(countries).Pie(pieData);
    </script>

    
<script>
  
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = document.getElementById("donutChart").getContext("2d")
    var donutData        = {
      labels: [
          'Laki-laki',
          'perempuan',
      ],
      datasets: [
        {
          data: [{{$dataGender['laki']}},{{$dataGender['perempuan']}}],
          backgroundColor : ['#3c8dbc','#f56954'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })
</script>

<script>
  
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = document.getElementById("donutChart2").getContext("2d")
    var donutData        = {
      labels: [
          'Buruk',
          'Kurang',
          'Baik',
          'ResikoLebih',
          'Lebih',
          'Obesitas',
      ],
      datasets: [
        {
          data: [
            {{$dataBuruk}},{{$dataKurang}},{{$dataBaik}},
            {{$dataResikoLebih}},{{$dataLebih}},{{$dataObesitas}}
        ],
          backgroundColor : [
            '#d9534f','#f0ad4e','#5cb85c',
            '#0275d8','#5bc0de','#292b2c'
        ],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })
</script>

<script>
    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = document.getElementById("barChart2").getContext("2d")
  
   var barChartData= {
        labels  : [
          @foreach ($dataparent as $d)
            '{{$d->name}}',
          @endforeach
        ],
        datasets:[
          {
            label               : ['Jumlah Monitoring'],
            backgroundColor     : 'rgba(60,101,118,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
            @foreach ($datareportsparent as $d)
            '{{$d}}',
            @endforeach
          ]
          },

        ],
   }
   
   var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })
    </script>


<script src="../../dist/js/adminlte.min.js"></script>


@endsection
