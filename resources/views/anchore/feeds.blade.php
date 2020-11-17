@extends('adminlte::page')

@section('title', 'Feeds')

@section('content_header')
    <h1>Feeds</h1>
@stop

@section('content')
<div class="row">


@foreach ($feeds as $feed)
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">{{ $feed['name'] }}</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">

                <div class="card-body p-0">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th style="width: 20px">#</th>
                        <th>Name</th>
                        <th>Last Sync</th>
                        <th style="width: 40px">Vulnerabilities</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($feed['groups'] as $group)
                        <tr>
                        <td>
                        @if ($group['enabled'] != 1)
                            <span style="color: #dc3545"><i class="far fa-circle"></i></span>
                        @else
                            <span style="color: #28a745"><i class="far fa-check-circle"></i></span>
                        @endif
                        </td>
                        <td>{{ $group['name'] }}</td>
                        <td>{{ $group['last_sync'] }}</td>
                        <td><span class="badge bg-danger">{{ $group['record_count'] }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>


                </div>
              </div>
            </div>
            <!-- /.card -->
        </div>
@endforeach

</div>

@stop

@include('footer')