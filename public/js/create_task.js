var SmartMultiFiled = (function () {
    var rowcount, html, addBtn, tableBody;

    addBtn = $(".addNew"); //for add button
    rowcount = $("#autocomplete_table tbody tr").length + 1; //row count
    tableBody = $("#autocomplete_table tbody"); // table body
    $("#totalTransactNum").val(rowcount - 1);

    //getting the id
    function getId(element) {
        var id, idArr;
        id = element.attr("id");
        idArr = id.split("_");
        return idArr[idArr.length - 1];
    }

    //format of table
    function formHtml() {
        html = '<tr id="row_' + rowcount + '" class="text-center">';
        html +=
            '<td><button id="delete_' +
            rowcount +
            '" class="delete_row border border-secondary text-dark rounded" style="cursor:pointer;">  <i class="p-2 nav-icon fas fa-trash-alt"></i></button></td>';
        html +=
            '<td><input type="text" data-type="sub_task_title" name="sub_task_title[]" id="sub_task_title_' +
            rowcount +
            '" class="form-control autocomplete_txt" autocomplete="off"></td>';
        html +=
            '<td><textarea class="form-control autocomplete_txt" data-type="sub_task_desc" name="sub_task_desc[]" id="sub_task_desc_' +
            rowcount +
            '"autocomplete="off"></textarea></td>';
        html += "</tr>";
        rowcount++;
        return html;
    }

    // add new row
    function addNewRow() {
        tableBody.append(formHtml());
    }

    // deleting row
    function deleteRow() {
        var currentEle, rowNo;
        currentEle = $(this);
        rowNo = getId(currentEle);
        $("#row_" + rowNo).remove();
    }

    // Event Register
    function registerEvents() {
        addBtn.on("click", addNewRow);
        $(document).on("click", ".delete_row", deleteRow);
    }

    function init() {
        registerEvents();
    }

    return {
        init: init,
    };
})();

$(document).ready(function () {
    SmartMultiFiled.init();

    //Submitting the form
    $("#submitBtn").click(function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Confirmation",
            text: "Are you sure?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false,
        }).then(function (result) {
            if (result.value) {
                //
                $.ajax({
                    url: base_path + "/create_post_task",
                    method: "POST",
                    data: new FormData($('#create_task_form')[0]), // get all form field value in serialize form
                    processData: false, // add this line
                    contentType: false, // add this line
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ), // add CSRF token
                    },
                    success: function (response) {
                        // Handle success response
                        Swal.fire({
                            title: "Success!",
                            text: "Your task has been created.",
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        }).then(function () {
                            location.href = base_path + "/tasks"; // Reload the page or redirect as needed
                        });
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        Swal.fire({
                            title: "Error!",
                            text: "Failed to create task. Kindly check your inputs!",
                            icon: "error",
                            timer: 2500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    },
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

    //Drafting the form
    $("#draftBtn").click(function (e) {
        e.preventDefault();

        $.ajax({
            url: base_path + "/create_draft_task",
            method: "POST",
            data: new FormData($('#create_task_form')[0]), // get all form field value in serialize form
            processData: false, // add this line
            contentType: false, // add this line
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ), // add CSRF token
            },
            success: function (response) {
                // Handle success response
                Swal.fire({
                    title: "Success!",
                    text: "Your task has been created.",
                    icon: "success",
                    timer: 1500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                }).then(function () {
                    location.href = base_path + "/tasks"; // Reload the page or redirect as needed
                });
            },
            error: function (xhr, status, error) {
                // Handle error response
                Swal.fire({
                    title: "Error!",
                    text: "Failed to create task. Kindly check your inputs!",
                    icon: "error",
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                });
            },
        });

    });

});
