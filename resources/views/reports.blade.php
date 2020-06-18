@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <h1>Reports from {{$checktime->toDateTime()->format('r')}}</h1>
@stop

@section('content')
<p>List of checked pods</p>
<div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-info">
              <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pods Checked</span>
                <span class="info-box-number">41,410</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  70% Increase in 30 Days
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-success">
              <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Namespaces Checked</span>
                <span class="info-box-number">41,410</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  70% Increase in 30 Days
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-warning">
              <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Images Checked</span>
                <span class="info-box-number">41,410</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  70% Increase in 30 Days
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-danger">
              <span class="info-box-icon"><i class="fas fa-comments"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Comments</span>
                <span class="info-box-number">41,410</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  70% Increase in 30 Days
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>



@foreach ($pods as $pod)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><a href="/pod/{{ $pod['metadata']['uid'] }}">{{ $pod['metadata']['name'] }}</a></h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
            @foreach ($pod['containers'] as $container)
                <div class="row">
                    <div class="col">
                        <table>
                            <tbody>
                            <tr>
                                <th>image :</th>
                                <td>{{ $container['image'] }}</td>
                            </tr>
                            <tr>
                                <th>namespace :</th>
                                <td>{{ $pod['metadata']['namespace'] }}</td>
                            </tr>
                            <tr>
                                <th>creation :</th>
                                <td>{{ $pod['metadata']['creationTimestamp'] }}</td>
                            </tr>
                            <tr>
                                <th>started :</th>
                                <td>{{ $container['state']['running']['startedAt'] }}</td>
                            </tr>
                            <tr>
                                <th>imagePullPolicy :</th>
                                <td>{{ $container['imagePullPolicy'] }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div  class="col-md-auto">
                    @foreach ($container['checkresults'] as $checkresult)
                        <span class="badge badge-pill badge-{{$error[$checkresult['result']]}}" style="width:60px">{{$error[$checkresult['result']]}}</span> {{$checkresult['description']}} </br>
                    @endforeach
                    </div>
                    <div class="col col-lg-3">
                            @foreach ($container['vulnsum'] as $severity => $vulnsum)
                            <div class="p-1 mb-1 {{$vulnseverity[$severity]}} rounded">{{$severity}} : {{$vulnsum['total']}}/{{$vulnsum['fixed']}}</div>
                            @endforeach
                    </div>
                </div>
            @endforeach
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>

    @endforeach
@stop