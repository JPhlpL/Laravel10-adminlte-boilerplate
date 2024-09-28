<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $(document).ready(function() {

        // Putting search in each headers
        $('#role-table tfoot tr th:not(:last-child)')
            .each(function() {
                var title = $('#role-table thead th').eq($(this).index()).text();
                $(this).html('<input type="text"/>');
                $('input').css('border-radius', '5px');
            });

        var role_table = $("#role-table").DataTable({
            responsive: true,
            processing: true,
            autoWidth: false,
            serverSide: true,
            pageLength: 5,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]],
            ajax: "{{ route('users.roles.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'users_count',
                    name: 'users_count',
                    className: "text-center"
                },
                {
                    data: 'permissions_count',
                    name: 'permissions_count',
                    className: "text-center"
                },
                {
                    data: 'action',
                    name: 'action',
                    bSortable: false,
                    className: "text-center"
                }
            ],
            //! For Searching
            initComplete: function() {
                var r = $('#role-table tfoot tr');
                r.find('th').each(function() {
                    $(this).css('padding', 8);
                });
                $('#role-table thead').append(r);
                $('#search_0').css('text-align', 'center');

                // Apply the search
                this.api().columns().every(function() {
                    var that = this;

                    $('input', this.footer()).on('keyup change clear', function() {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                    });
                })
            }
        });

        $('body').on('click', '#btnDel', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "Confirmation",
                text: "Are you sure you want to delete ?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    //
                    var route = "{{ route('users.roles.destroy', ':id') }}";
                    route = route.replace(':id', id);
                    $.ajax({
                        url: route,
                        type: "delete",
                        success: function(res) {
                            // Handle success response
                            Swal.fire({
                                title: "Success!",
                                text: "Data is now deleted. Thank you!",
                                icon: "success",
                                timer: 500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            }).then(function() {
                                $("#role-table").DataTable().ajax.reload();
                            });
                        },
                        error: function(res) {
                            Swal.fire({
                                title: "Error!",
                                text: "Please check your inputs! Thank you.",
                                icon: "error",
                                timer: 500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            }).then(function() {
                                $("#role-table").DataTable().ajax.reload();
                            });
                        }
                    });
                }
                //
                else if (result.value === "") {
                    Swal.fire("Cancelled", "Cancel", "error");
                    return false;
                }
            });
            return false;
        });

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

        // For Create
        var create_roletbl = $('#create-tblrole').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.permissions.index') }}",
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

        // POST create role
        $('#createrolebtn').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Confirmation",
                text: "Are you sure you want to submit?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    var formData = new FormData($('#role-create-form')[0]); // Get form data
                    $.ajax({
                        url: "{{ route('users.roles.store') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            // Handle success response
                            Swal.fire({
                                title: "Success!",
                                text: "Your role has been created.",
                                icon: "success",
                                timer: 500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            }).then(function() {
                                // Reload the page or redirect to a new page
                                window.location.href = "{{ url('/users/roles/') }}";

                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            var errorResponse = JSON.parse(jqXHR.responseText);
                            Swal.fire({
                                title: "Error!",
                                text: errorResponse.message,
                                icon: "error",
                                timer: 500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            }).then(function() {
                                // Reload the page or redirect to a new page
                                window.location.href = "{{ url('/users/roles/') }}";
                            });
                        }
                    });
                }
            });
        });

    });
</script>
