function viewTaskUrl(action, id) {
    var url = base_path + "/" + action + "/" + id;
    return url;
}

Dropzone.autoDiscover = false;
$(document).ready(function () {
    const urlSegments = window.location.pathname.split("/");
    const task_num = urlSegments[5]; //NEED TO CHANGE THIS

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: base_path + "/display_task",
        type: "POST",
        data: {
            task_num: task_num,
        },
        dataType: "json",
        success: function (result) {
            $("#task_title").val(result.task_title);
            $("#task_desc").val(result.task_desc);
        },
        error: function (xhr, status, error) {
            if (xhr.status === 401) {
                window.location.href = "/login";
            } else {
                console.log(error);
            }
        },
    });

    //
    var subtaskTable = $("#subtask_table").DataTable({
        responsive: true,
        processing: true,
        autoWidth: false,
        serverSide: true,
        ajax: base_path + "/subtasks/" + task_num,
        columns: [
            { data: "sub_task_num", name: "sub_task_num" },
            { data: "sub_task_title", name: "sub_task_title" },
            { data: "sub_task_desc", name: "sub_task_desc" },
            {
                data: "sub_task_status",
                name: "sub_task_status",
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
                data: "sub_created_at",
                name: "sub_created_at",
                render: function (data, type, row) {
                    return moment(data).format("MM-DD-YYYY HH:mm A");
                },
            },
            {
                data: "sub_updated_at",
                name: "sub_updated_at",
                render: function (data, type, row) {
                    return moment(data).format("MM-DD-YYYY HH:mm A");
                },
            },
            {
                data: "sub_task_status",
                name: "sub_task_status",
                render: function (data, type, row) {

                    switch(row.sub_task_status){
                        case 'draft':
                            return (
                                '<div class="btn-group-vertical" style="width:100%" role="group" aria-label="Task Actions">' +
                                    '<label class="bg-secondary border rounded px-2" style="font-size:15px;">' + data + '</label>' +
                                '</div>'
                            );
                            break;
                        default:
                            return (
                                '<div class="btn-group-vertical" style="width:100%" role="group" aria-label="Task Actions">' +
                                    '<a href="' +
                                    viewTaskUrl("sub_todo", row.suid) +
                                    '" id="todo-button" name="todo-button" class="btn btn-danger mt-1 subtask-action-btn">To Do</a>' +
                                    '<a href="' +
                                    viewTaskUrl("sub_inprogress", row.suid) +
                                    '" id="inprogress-button" name="inprogress-button" class="btn btn-warning mt-1 subtask-action-btn">In-progress</a>' +
                                    '<a href="' +
                                    viewTaskUrl("sub_done", row.suid) +
                                    '" id="done-button" name="done-button" class="btn btn-success mt-1 subtask-action-btn">Done</a>' +
                                "</div>"
                            );
                            break;
                    }


                },
            },
        ],
    });

    //Attach Table
    var attachTable = $("#attach_table").DataTable({
        responsive: true,
        processing: true,
        autoWidth: false,
        serverSide: true,
        ajax: base_path + "/attach_table/" + task_num,
        columns: [
            { data: "task_attach_name", name: "task_attach_name" },
            { data: "task_attach_filesize", name: "task_attach_filesize" },
            {
                data: "sub_created_at",
                name: "sub_created_at",
                render: function (data, type, row) {
                    return moment(data).format("MM-DD-YYYY HH:mm A");
                },
            },
            {
                    data: null,
                    render: function (data, type, row) {
                    return (
                        '<div class="btn-group-vertical" style="width:100%" role="group" aria-label="Task Actions">' +
                            '<a href="' + viewTaskUrl('delete_attach',row.task_attach_name) + '" id="del-button" name="del-button" class="btn btn-danger mt-1 del-button">Delete</a>' +
                        '</div>'
                    );
                },
            },
        ],
    });

       //!For Btn in subtask
       $(document).on("click", ".subtask-action-btn", function (e) {
        e.preventDefault(); // Prevent default link behavior

        var actionUrl = $(this).attr("href"); // Get the action URL from the button's href attribute

        // Send AJAX request to update subtask status
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: actionUrl,
            type: "GET", // Use GET or POST based on your route definition
            dataType: "json",
            success: function (response) {
                // Check if the response indicates success
                if (response.success) {
                    // Reload the DataTable after successful update
                    subtaskTable.ajax.reload();

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

    //! For Btn in delete
    $(document).on("click", ".del-button", function (e) {
        e.preventDefault(); // Prevent default link behavior

        var actionUrl = $(this).attr("href"); // Get the action URL from the button's href attribute

        // Send AJAX request to update subtask status
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: actionUrl,
            type: "GET", // Use GET or POST based on your route definition
            dataType: "json",
            success: function (response) {
                // Check if the response indicates success
                if (response.success) {
                    // Reload the DataTable after successful update
                    attachTable.ajax.reload();

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
                        title: "Attachment already deleted!",
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

        // Initialize Dropzone with the desired configuration
        Dropzone.options.myDropzone = {
            acceptedFiles: 'image/*',
            url: base_path + '/view_task/' + task_num + '/dropzone/store', // adjust the URL to your server-side endpoint
            method: "post",
            init: function() {
                this.on("addedfile", function(file) {
                    // Upload the file
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
                        title: "Attachments are now uploaded!",
                    })
                    attachTable.ajax.reload();
                    myDropzone.processQueue();
                });

                this.on("error", function(file, errorMessage) {
                    Swal.fire({
                        icon: "error",
                        title: "Error uploading file",
                        text: errorMessage,
                    });
                });

                this.on("rejectedfile", function(file) {
                    Swal.fire({
                        icon: "error",
                        title: "File not accepted",
                        text: "Only image files are allowed",
                    });
                });
            }
        };

        // Create a new Dropzone instance
        var myDropzone = new Dropzone("#myDropzone");
});
