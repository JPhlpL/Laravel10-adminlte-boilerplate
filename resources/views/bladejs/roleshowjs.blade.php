<script>
    function toast_notif(title, icon) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1000,
        });

        Toast.fire({
            icon: icon,
            title: title
        });
    }


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $(document).ready(function() {
        //check uncheck all function
        $('[name="all_permission"]').on('click', function() {
            if ($(this).is(":checked")) {
                $.each($('.permission'), function() {
                    if ($(this).val() != "dashboard") {
                        $(this).prop('checked', true);
                    }
                });
            } else {
                $.each($('.permission'), function() {
                    if ($(this).val() != "dashboard") {
                        $(this).prop('checked', false);
                    }
                });
            }
        });
        var table = $('#show-tblrole').DataTable({
            reponsive: true,
            processing: true,
            serverSide: true,
            pageLength: 5,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
            ajax: "{{ route('users.permissions.index', ['role_id' => $role->id]) }}",
            columns: [{
                    data: 'chkBox',
                    name: 'chkBox',
                    orderable: false,
                    searchable: false,
                    className: 'text-center chckbox-perm'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'guard_name',
                    name: 'guard_name'
                },
            ],
            order: [
                [0, "desc"]
            ]
        });

        // Submit form using jQuery
        $('#role-update-form').submit(function(e) {
            e.preventDefault(); // prevent the form from submitting normally
            // Get the form data
            var formData = new FormData(this);
            // Append the CSRF token to the form data
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toast_notif("Permission is now updated. Thank you!", "success")
                },
                error: function(data) {
                    // Handle errors
                    toast_notif("Kindly check again your inputs!", "error")
                }
            });
        });


    });
</script>
