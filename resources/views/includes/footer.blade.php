@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="{{ URL::asset('/css/admin_custom.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@stop


@section('js')
    <script>
        var base_path = "{{ url('/') }}"
    </script>
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (request()->segment(1) == 'create_task')
        <script src="{{ URL::asset('/js/create_task.js') }}"></script>
    @elseif(request()->segment(1) == 'tasks')
        <script src="{{ URL::asset('/js/table_task.js') }}"></script>
    @elseif(request()->segment(1) == 'view_task')
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <script src="{{ URL::asset('/js/view_task.js') }}"></script>
    @elseif(request()->segment(1) == 'users')
        @if (request()->segment(2) == 'permissions')
            @include('bladejs.permissionjs')
        @elseif (request()->segment(2) == 'roles')
            @if(is_numeric(request()->segment(3)))
                @include('bladejs.roleshowjs')
            @else
                @include('bladejs.rolejs')
            @endif
        @endif
    @endif
@stop

@section('plugins.Datatables', true)
