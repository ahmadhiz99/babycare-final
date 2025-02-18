@extends('users.layouts.user-dash-layout')
@section('title','Detail Artikel')
@section('content')
<div class="container p-2">

<div class="container">
  
  <div class="row mx-3" >
            <div class="card bg-dark pb-1">
              <div class="card-header justify-content-between">
                <h3 class="card-title">{{ $data->title }}</h3>
                @if($data->category == "Baik")
                <span style="font-size:10px;" class="badge badge-success mx-2 mt-1 card-title">{{ $data->category }}</span>
                @elseif($data->category == "Kurang")
                <span style="font-size:10px;" class="badge badge-danger mx-2 mt-1 card-title">{{ $data->category }}</span>
                @elseif($data->category == "Lebih")
                <span style="font-size:10px;" class="badge badge-warning mx-2 mt-1 card-title">{{ $data->category }}</span>
                @else
                <span style="font-size:10px;" class="badge badge-primary mx-2 mt-1 card-title">{{ $data->category }}</span>
                @endif
              </div>
              <div class="card-body bg-light d-flex justify-content-center">
              {{ $data->content }}
              </div>
            </div>


</div>

           

          <!-- <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 btn">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Likes</span>
                <span class="info-box-number">41,410</span>
              </div>
            </div>
          </div> -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>



      
</div>
@endsection
