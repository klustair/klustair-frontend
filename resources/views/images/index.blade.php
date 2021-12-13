@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Images</h1>
@stop

@section('content')
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row d-flex align-items-stretch">


            @foreach ($images as $image)
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">

              <div class="card bg-light card-outline card-secondary">
                <div class="card-header text-muted border-bottom-0">
                <!--<img src="/img/distro/{{ $image['distro'] }}.png" width="40px" alt="" class="img-fluid">-->
                <b>{{ $image['fulltag'] }}</b>
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-10">
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="far fa-lg fa-folder"></i></span>age: <b>{{ $image['age'] }}d</b></li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-calendar-plus"></i></span>created: {{ $image['created_at'] }}</li>
                        <li class="small"><span class="fa-li"><i class="far fa-lg fa-calendar-check"></i></span>lanalyzed: {{ $image['analyzed_at'] }}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-weight"></i></span>image size: {{ $image['image_size'] }}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-layer-group"></i></span>layer count: {{ $image['layer_count'] }}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-microchip"></i></span>arch: {{ $image['arch'] }}</li>
                        <li class="small"><span class="fa-li"><i class="far fa-lg fa-image"></i></span>distro: {{ $image['distro'] }} {{ $image['distro_version'] }}</li>
                      </ul>
                    </div>
                    <div class="col-2 text-center">
                      <img src="/img/distro/{{ $image['distro'] }}.png" alt="" class="img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="report/{{ $image['report_uid'] }}/image/{{ $image['uid'] }}" class="btn btn-sm btn-primary">
                      <i class="fas fa-bug"></i> View Vulnerabilities
                    </a>
                  </div>
                </div>
              </div>
            </div>
            @endforeach

          </div>
        </div>
        <!-- /.card-body -->
      </div>

@stop

@include('footer')