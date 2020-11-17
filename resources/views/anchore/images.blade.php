@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Image</h1>
@stop

@section('content')
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row d-flex align-items-stretch">


            @foreach ($images as $image)
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">

              @switch($image['analysis_status'])
                @case('not_analyzed')
              <div class="card bg-light card-outline card-secondary">
                <div class="card-header text-muted border-bottom-0">
                  <i class="fas fa-lg fa-question-circle text-info"></i>
                  @break
                @case('analyzing')
              <div class="card bg-light card-outline card-secondary">
                <div class="card-header text-muted border-bottom-0">
                  <i class="fas fa-lg fa-running"></i>
                  @break
                @case('analyzed')
              <div class="card bg-light card-outline card-success">
                <div class="card-header text-muted border-bottom-0">
                  <i class="fas fa-lg fa-check-circle text-success" alt="analyzed"></i>
                  @break
                @case('analysis_failed')
              <div class="card bg-light card-outline card-danger">
                <div class="card-header text-muted border-bottom-0">
                  <i class="fas fa-lg fa-exclamation-circle text-danger"></i>
                  @break
              @endswitch
                <b>{{ $image['image_detail'][0]['repo'] }}:{{ $image['image_detail'][0]['tag'] }}</b>
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-10">
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="far fa-lg fa-folder"></i></span>registry: {{ $image['image_detail'][0]['registry'] }}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-calendar-plus"></i></span>tag detected at: {{ $image['image_detail'][0]['tag_detected_at'] }}</li>
                        <li class="small"><span class="fa-li"><i class="far fa-lg fa-calendar-check"></i></span>last updated: {{ $image['image_detail'][0]['last_updated'] }}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-weight"></i></span>image size: {{ $image['image_content']['metadata']['image_size'] }}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-layer-group"></i></span>layer count: {{ $image['image_content']['metadata']['layer_count'] }}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-microchip"></i></span>arch: {{ $image['image_content']['metadata']['arch'] }}</li>
                        <li class="small"><span class="fa-li"><i class="far fa-lg fa-image"></i></span>distro: {{ $image['image_content']['metadata']['distro'] }} {{ $image['image_content']['metadata']['distro_version'] }}</li>
                      </ul>
                    </div>
                    <div class="col-2 text-center">
                      <img src="/img/distro/{{ $image['image_content']['metadata']['distro'] }}.png" alt="" class="img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <a href="#" class="btn btn-sm bg-teal">
                      <i class="fas fa-redo"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-primary">
                      <i class="fas fa-bug"></i> View Vulnerabilities
                    </a>
                  </div>
                </div>
                @if($image['analysis_status'] == 'analyzing')
                <div class="overlay">
                  <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                </div>
                @endif
              </div>
            </div>
            @endforeach

          </div>
        </div>
        <!-- /.card-body -->
      </div>

@stop

@include('footer')