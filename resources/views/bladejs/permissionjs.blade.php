<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $(document).ready(function() {
        // Putting search in each headers
        $('#permission-table tfoot th') //Exclude First and Last Column
            .each(function() {
                var title = $('#permission-table thead th').eq($(this).index()).text();
                $(this).html('<input type="text"/>');
                $('input').css('border-radius', '5px');
            });

        var table = $("#permission-table").DataTable({
            responsive: true,
            processing: true,
            autoWidth: false,
            serverSide: true,
            ajax: "{{ route('users.permissions.index') }}",
            columns: [{
                    data: "id",
                    name: "id"
                },
                {
                    data: "name",
                    name: "name"
                },
                {
                    data: "guard_name",
                    name: "guard_name"
                },
                {
                    data: "action",
                    name: "action"
                },
            ],
            //! For Searching
            initComplete: function() {
                var r = $('#permission-table tfoot tr');
                r.find('th').each(function() {
                    $(this).css('padding', 8);
                });
                $('#permission-table thead').append(r);
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

        //!Delete
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
                    var route = "{{ route('users.permissions.destroy', ':id') }}";
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
                                $("#permission-table").DataTable().ajax
                                    .reload();
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
                                $("#permission-table").DataTable().ajax
                                    .reload();
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

        // For Select Row
        $('body').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var route = "{{ route('users.permissions.edit', ':id') }}";
            route = route.replace(':id', id);

            $.ajax({
                url: route,
                type: "GET",
                dataType: 'json',
                success: function(response) {

                    var permission = response.permission;
                    $('#id').val(permission.id);
                    $('#edit-name').val(permission.name);
                    $('#edit-modal').modal('show');

                },
                error: function(error) {
                    console.error(error);
                    // Handle error here
                }
            });
        });

        //POST
        $("#permission-btn").click(function(e) {
            e.preventDefault();
            var name = $('#name').val();
            var route = "{{ route('users.permissions.store') }}";
            $.ajax({
                url: route,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name
                },
                success: function(response) {
                    // Handle success response
                    Swal.fire({
                        title: "Success!",
                        text: "Your task has been created.",
                        icon: "success",
                        timer: 500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    }).then(function() {
                        $("#permission-table").DataTable().ajax.reload();
                        $("#name").val('');
                    });
                },

                error: function(xhr, status, error) {
                    // Handle error response
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to create task. Kindly check your inputs!",
                        icon: "error",
                        timer: 500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                },
            });
        });

        //! Patch
        $('#update-permission').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Confirmation",
                text: "Are you sure you you want to update?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    //
                    var id = $('#id').val();
                    var name = $('#edit-name').val();
                    var route = "{{ route('users.permissions.update', ':id') }}";
                    route = route.replace(':id', id);

                    $.ajax({
                        url: route,
                        type: "PATCH",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: name
                        },
                        success: function(response) {
                            $('#edit-modal').modal('hide');

                            // Handle success response
                            Swal.fire({
                                title: "Success!",
                                text: "Your task has been created.",
                                icon: "success",
                                timer: 500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            }).then(function() {
                                $("#permission-table").DataTable().ajax
                                    .reload();
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            var errorResponse = JSON.parse(jqXHR.responseText);
                            console.log(errorResponse.errors.name[0])
                            if (errorResponse.errors.name[0] === 'The name has already been taken.') {
                                Swal.fire({
                                    title: "Error!",
                                    text: "The name has already been taken.",
                                    icon: "error",
                                    timer: 500,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                }).then(function() {
                                    $("#permission-table").DataTable().ajax
                                        .reload();
                                });
                            }
                            else if(errorResponse.errors.name[0] === 'The name field is required.'){
                                Swal.fire({
                                    title: "Error!",
                                    text: "The name field is required.",
                                    icon: "error",
                                    timer: 500,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                }).then(function() {
                                    $("#permission-table").DataTable().ajax
                                        .reload();
                                });
                            }
                            else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Please check your inputs! Thank you.",
                                    icon: "error",
                                    timer: 500,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                }).then(function() {
                                    $("#permission-table").DataTable().ajax
                                        .reload();
                                });
                            }
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

    });
</script>
