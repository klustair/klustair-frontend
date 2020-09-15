@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Subscriptions</h1>
@stop

@section('content')
<div class="row">


        <div class="col-md-12">
            <div class="card">
            <!--
              <div class="card-header">
                <h5 class="card-title">Subscriptions</h5>
              </div>
            -->
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">

                <div class="card-body p-0">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>Image</th>
                        <th style="width: 120px">policy eval</th>
                        <th style="width: 120px">tag update</th>
                        <th style="width: 140px">vuln update</th>
                        <th style="width: 160px">analysis update</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($subscriptions as $image => $subscription)
                        <tr>
                        <td>{{ $image }}</td>
                        <td class="text-center">
                        @if ($subscription['policy_eval']['active'] != 1)
                            <span style="color: #dc3545"><i class="far fa-circle"></i></span>
                        @else
                            <span style="color: #28a745"><i class="far fa-check-circle"></i></span>
                        @endif
                        </td>

                        <td class="text-center">
                        @if ($subscription['tag_update']['active'] != 1)
                            <span style="color: #dc3545"><i class="far fa-circle"></i></span>
                        @else
                            <span style="color: #28a745"><i class="far fa-check-circle"></i></span>
                        @endif
                        </td>

                        <td class="text-center">
                        @if ($subscription['vuln_update']['active'] != 1)
                            <span style="color: #dc3545"><i class="far fa-circle"></i></span>
                        @else
                            <span style="color: #28a745"><i class="far fa-check-circle"></i></span>
                        @endif
                        </td>

                        <td class="text-center">
                        @if ($subscription['analysis_update']['active'] != 1)
                            <span style="color: #dc3545"><i class="far fa-circle"></i></span>
                        @else
                            <span style="color: #28a745"><i class="far fa-check-circle"></i></span>
                        @endif
                        </td>
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

</div>

@stop