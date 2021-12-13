@extends('adminlte::page')

@section('title', 'Images')

@section('content_header')
    <p>
@stop

@section('content')
@csrf
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">

            <table id="vulnlist" class="table table-condensed">
                <thead>
                <tr>
                    <th width="35px"></th>
                    <th>Image</th>
                    <th>Arch</th>
                    <th>Age</th>
                    <th>Layers</th>
                </tr>
                </thead>
                <tbody>
                @foreach($images as $image)
                    <tr>
                        <td><img src="/img/distro/{{ $image['distro'] }}.png" alt="{{ $image['distro'] }}" class="img-fluid" width="20px"> </td>
                        <td><a href="report/{{ $image['report_uid'] }}/image/{{ $image['uid'] }}">{{ $image['fulltag'] }}</a></td>
                        <td>{{ $image['arch'] }}</td>
                        <td>{{ $image['age'] }}d</td>
                        <td>{{ $image['layer_count'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>

@stop

@include('footer')