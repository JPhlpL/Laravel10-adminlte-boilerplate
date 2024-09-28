


@php
    switch(request()->segment(1)){
        case 'home':
            $title = 'Home';
            break;
        case 'permission':
            $title = 'Permissions';
            break;
        case 'tasks':
            $title = 'My Task';
            break;
        case 'create_task':
            $title = 'Create Task';
            break;
        case 'view_task':
            $taskNumber = request()->segment(2);
            $title = 'View Task: ' . $taskNumber;
            break;
        case 'users':
            switch(request()->segment(2)){
                case 'permissions':
                    $title = 'Permissions';
                    break;
                case 'roles':
                    switch(request()->segment(3)){
                        case 'create':
                            $title = 'Create';
                            break;
                        case 'edit':
                            $title = 'edit';
                            break;
                        default:
                            $title = 'Role';
                            break;
                }
            }
            break;
        default:
            $title = '';
            break;
    }
@endphp


@section('title', $title)

@section('content_header')
    {{-- <h1>{{ $title }}</h1> --}}

    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">
                {{ $title }}

              </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#" onclick="history.back()"><i class="fas fa-arrow-alt-circle-left text-dark"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ url('/home') }}"><i class="fas fa-home text-dark"></i></a></li>
                <li class="breadcrumb-item active">
                    {{ $title }}
                </li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </div>
@stop
