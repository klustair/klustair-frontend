@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  <h1>Policies</h1>
@stop

@section('content')

<div class="row">

  @foreach($policies as $bundle)
  <div class="col-md-12">
    <div class="card">

      <div class="card-header">
        <h5 class="card-title">{{ $bundle['name'] }}</h5>
      </div>

      <div class="card-body">
            <div class="row">
              <!-- mappings -->
              <div class="col-12 col-md-12 col-lg-4 d-flex align-items-stretch">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>name</th>
                      <th>image</th>
                      <th>registry</th>
                      <th>repository</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bundle['policybundle']['mappings'] as $mapping)
                    <tr>
                      <td>{{ $mapping['name'] }}</td>
                      <td>{{ $mapping['image']['type'] }}:{{ $mapping['image']['value'] }}</td>
                      <td>{{ $mapping['registry'] }}</td>
                      <td>{{ $mapping['repository'] }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>


              <!-- policies -->
              <div class="col-12 col-md-12 col-lg-8 d-flex align-items-stretch">

                @foreach($bundle['policybundle']['policies'] as $policy)
                <!--<h3>{{ $policy['name'] }}</h1><br>{{ $policy['comment'] }}<br>-->
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>gate</th>
                      <th>action</th>
                      <th>trigger</th>
                      <th>params</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- rules -->
                    @foreach($policy['rules'] as $rule)
                    <tr>
                      <td>{{ $rule['gate'] }}</td>
                      <td>{{ $rule['action'] }}</td>
                      <td>{{ $rule['trigger'] }}</td>
                      <td>
                      <ul>
                        @foreach($rule['params'] as $param)
                        <li>{{ $param['name'] }}='{{ $param['value'] }}'</li>
                        @endforeach
                      </ul>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                @endforeach
              </div>
            </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

@stop