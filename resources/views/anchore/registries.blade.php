@extends('adminlte::page')

@section('title', 'Regsitries')

@section('content_header')
    <h1>Regsitries</h1>
@stop

@section('content')

<div class="row">

@foreach ($registries as $registry)
        <div class="col-md-4">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle" 
                    @if ($registry['registry_verify'] != 1)
                        style="border-color: #dc3545" 
                    @else
                        style="border-color: #28a745" 
                    @endif
                    src="https://raw.githubusercontent.com/docker-library/docs/c350af05d3fac7b5c3f6327ac82fe4d990d8729c/docker/logo.png" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $registry['registry_name'] }}</h3>

                <p class="text-muted text-center">{{ $registry['registry_type'] }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Username</b> <a class="float-right">{{ $registry['registry_user'] }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Created</b> <a class="float-right">{{ $registry['created_at'] }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Updated</b> <a class="float-right">{{ $registry['last_updated'] }}</a>
                  </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block"><b>Edit</b></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->


        </div>
@endforeach
</div>

@stop