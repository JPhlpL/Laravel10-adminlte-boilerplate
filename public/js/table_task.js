function viewTaskUrl(action,param) {
    var csrfToken = '{{ csrf_token }}'; // assume this is a server-side generated CSRF token
    var url = base_path + "/" + action + "/" + param;
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
    return url;
}

$(document).ready(function () {

    // Putting search in each headers
    $('#tbltask tfoot th').not("#tbltask th:last-child") //Exclude First and Last Column
    .each(function() {
        var title = $('#tbltask thead th').eq($(this).index()).text();
        $(this).html('<input type="text"/>');
        $('input').css('border-radius', '5px');
    });

    var taskTable = $("#tbltask").DataTable({
        responsive: true,
        processing: true,
        autoWidth: false,
        serverSide: true,
        ajax: base_path + "/tasks",
        columns: [
            { data: "id", name: "id" },
            { data: "task_num", name: "task_num" },
            { data: "task_title", name: "task_title" },
            { data: "task_desc", name: "task_desc" },
            { data: "task_status", name: "task_status" ,
                render: function (data, type, row) {
                    switch(data){
                        case 'To-Do':
                            return '<label class="bg-danger border rounded px-2" style="font-size:15px;">' + data + '</label>';
                            break;
                        case 'In Progress':
                            return '<label class="bg-warning border rounded px-2" style="font-size:15px;">' + data + '</label>';
                            break;
                        case 'Done':
                            return '<label class="bg-success border rounded px-2" style="font-size:15px;">' + data + '</label>';
                            break;
                        default:
                            return '<label class="bg-secondary border rounded px-2" style="font-size:15px;">' + data + '</label>';
                            break;
                    }
                },
            },
            {
                data: "created_at",
                name: "created_at",
                render: function (data, type, row) {
                    return moment(data).format("MM-DD-YYYY HH:mm A");
                },
            },
            {
                data: "updated_at",
                name: "updated_at",
                render: function (data, type, row) {
                    return moment(data).format("MM-DD-YYYY HH:mm A");
                },
            },
          {
                data: null,
                render: function (data, type, row) {
                    const csrfToken = '{{ csrf_token }}';

                    switch(row.task_status){
                        case 'draft':
                            return (
                                '<div class="btn-group-vertical" style="width:100%" role="group" aria-label="Task Actions">' +
                                    '<a href="' + viewTaskUrl('view_task',row.task_num) + '" id="edit-button" name="edit-button" class="btn btn-primary mt-1">View</a>' +
                                '</div>'
                            );
                            break;
                        default:

                            if (row.task_status === 'To-Do') {
                                color = 'bg-danger';
                            } else if (row.task_status === 'In Progress') {
                                color = 'bg-warning';
                            } else if (row.task_status === 'Done') {
                                color = 'bg-success';
                            } else {
                                color = 'bg-secondary';
                            }

                            let taskStatusSelect = '<select class="form-control task-status-select ' + color + '" data-id="' + row.id + '">' +
                                                        '<option value=""> ---------------- </option>' +
                                                        '<option class="bg-danger" value="todo" ' + (row.task_status === 'To-Do'? 'selected' : '') + '>To Do</option>' +
                                                        '<option class="bg-warning" value="inprogress" ' + (row.task_status === 'In Progress'? 'selected' : '') + '>In Progress</option>' +
                                                        '<option class="bg-success" value="done" ' + (row.task_status === 'Done'? 'selected' : '') + '>Done</option>' +
                                                    '</select>';


                                    return (
                                        '<div class="btn-group-vertical" style="width:100%" role="group" aria-label="Task Actions">' +
                                            '<a href="' + viewTaskUrl('view_task',row.task_num) + '" id="edit-button" name="edit-button" class="btn btn-primary mb-2">View</a>' +
                                            taskStatusSelect +
                                        '</div>'
                                    );
                                break;
                    }
                },
            },
        ],
        //! For Searching
        initComplete: function() {
            var r = $('#tbltask tfoot tr');
            r.find('th').each(function() {
                $(this).css('padding', 8);
            });
            $('#tbltask thead').append(r);
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

    $(document).on("change", ".task-status-select", function (e) {
        e.preventDefault(); // Prevent default form submission

        var select = $(this);
        var id = select.data("id");
        var status = select.val();

        // Send AJAX request to update subtask status
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: viewTaskUrl('task_' + status, id),
            type: "GET", // Use GET or POST based on your route definition
            dataType: "json",
            success: function (response) {
                // Check if the response indicates success
                if (response.success) {
                    // Reload the DataTable after successful update
                    taskTable.ajax.reload();

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Subtask Status Updated!",
                    });
                } else {
                    // Handle other responses if needed
                    console.error("Unexpected response:", response);
                }
            },
            error: function (xhr, status, error) {
                // Handle error response if needed
                console.error(xhr.responseText);
            },
        });
    });



});
