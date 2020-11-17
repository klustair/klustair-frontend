@extends('adminlte::page')

@section('title', 'Images')

@section('content_header')
    <h1>Images</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <div class="row">
          <div class="col-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Reports</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
<div class="list-group" id="myList" role="tablist">
@foreach ($reports as $report)
    <a class="list-group-item list-group-item-action" data-toggle="list" href="#home" role="tab">{{ $report->checktime }}</a>
@endforeach
</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pods</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
              <div class="list-group" id="myList" role="tablist">
@foreach ($pods as $pod)
    <a class="list-group-item list-group-item-action" data-toggle="list" href="#home" role="tab">{{$pod->podname}}</a>
@endforeach
</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Images</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
              <div class="list-group" id="myList" role="tablist">
@foreach ($images as $image)
    <a class="list-group-item list-group-item-action" data-toggle="list" href="#home" role="tab">{{$image->fulltag}}</a>
@endforeach
</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          
        </div>
@stop

@include('footer')