<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Laravel Project Manager</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ url('/') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- List of link and script -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/85fa870d74.js" crossorigin="anonymous"></script>
</head>

<body style="background-image: url({{ asset('image/mountain.avif') }}); background-repeat: no-repeat;
      background-size: cover;">

    <div class="container">
        <!-- Big title in the body area -->
        <h2 class="text-center mt-5 mb-3">Laravel Project Manager</h2>
        <!-- Make the header of the table -->
        <div class="card">
            <div class="card-header">
                <!-- Edit the style of button -->
                <button type="button" class="btn btn-success" onclick="createProject()">
                    Create New Project
                </button>
            </div>
            <div class="card-body">
                <div id="alert-div">

                </div>
                <table class="table table-striped table-hover" id="projects_table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="240px">Action</th>
                        </tr>
                    </thead>
                    <!-- Style of the body table s-->
                    <tbody id="projects-table-body">

                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- project form modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="form-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Title of the modal -->
                    <h5 class="modal-title">Project Form</h5>
                    <!-- Button in the modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Give an alert block -->
                    <div id="error-div"></div>
                    <form>
                        <input type="hidden" name="update_id" id="update_id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary mt-3" id="save-project-btn">Save
                            Project</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- view project modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="view-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Project Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <b>Name:</b>
                    <p id="name-info"></p>
                    <b>Description:</b>
                    <p id="description-info"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" id="close-delete-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="no-delete-btn">No</button>
                    <button type="button" class="btn btn-danger" id="yes-delete-btn">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" id="close-edit-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to edit?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="no-edit-btn">No</button>
                    <button type="button" class="btn btn-danger" id="yes-edit-btn">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript">
    $(function() {
        var baseUrl = $('meta[name=app-url]').attr("content");
        // Make the url
        let url = baseUrl + '/projects';
        // create a datatable
        $('#projects_table').DataTable({
            processing: true, // Make a loading features
            serverSide: true, // use serverside
            ajax: url,
            "order": [
                [0, "desc"]
            ],
            columns: // Define the columns in the tables
                [{
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'action'
                    },
                ],

        });
    });

    // reload the data not the entire page
    function reloadTable() {
        /*
            reload the data on the datatable
        */
        $('#projects_table').DataTable().ajax.reload();
    }

    /*
        check if form submitted is for creating or updating
    */

    // case if the val is empty or not for updating case
    // and it will be triggered everytime we tap the button that we define on the html part
    $("#save-project-btn").click(function(event) {
        event.preventDefault(); // prevent the defaul behaviour to handle (avoid the overlapping)
        if ($("#update_id").val() == null || $("#update_id").val() == "") {
            storeProject();
        } else {
            updateProject();
        }
    })

    /*
        show modal for creating a record and 
        empty the values of form and remove existing alerts
    */
    // Make sure to empty all the alert that will distract the create process
    function createProject() {
        $("#alert-div").html("");
        $("#error-div").html("");
        $("#update_id").val("");
        $("#name").val("");
        $("#description").val("");
        $("#form-modal").modal('show');
    }

    /*
        submit the form and will be stored to the database
    */

    // Store the project
    function storeProject() {
        $("#save-project-btn").prop('disabled', true); // disable the button, so there is no multiple reaction
        let url = $('meta[name=app-url]').attr("content") + "/projects";
        let data = {
            name: $("#name").val(),
            description: $("#description").val(),
        }; // The data will be sent
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // For security
            },
            url: url, // The end target
            type: "POST",
            data: data,
            success: function(response) {
                $("#save-project-btn").prop('disabled', false);
                let successHtml =
                    '<div class="alert alert-success" role="alert"><b>Project Created Successfully</b></div>';
                $("#alert-div").html(successHtml);
                $("#name").val("");
                $("#description").val("");
                reloadTable();
                $("#form-modal").modal('hide');
            },
            error: function(response) {
                // The project if there is an error
                $("#save-project-btn").prop('disabled', false);
                if (typeof response.responseJSON.errors !== 'undefined') {
                    let errors = response.responseJSON.errors;
                    let descriptionValidation = "";
                    if (typeof errors.description !== 'undefined') {
                        descriptionValidation = '<li>' + errors.description[0] + '</li>';
                    }
                    let nameValidation = "";
                    if (typeof errors.name !== 'undefined') {
                        nameValidation = '<li>' + errors.name[0] + '</li>';
                    }

                    let errorHtml = '<div class="alert alert-danger" role="alert">' +
                        '<b>Validation Error!</b>' +
                        '<ul>' + nameValidation + descriptionValidation + '</ul>' +
                        '</div>';
                    // Error alert table
                    $("#error-div").html(errorHtml);
                }
            }
        });
    }


    /*
        edit record function
        it will get the existing value and show the project form
    */
    function editProject(id) {
        let url = $('meta[name=app-url]').attr("content") + "/projects/" + id;
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                let project = response.project;
                $("#alert-div").html("");
                $("#error-div").html("");
                $("#update_id").val(project.id);
                $("#name").val(project.name);
                $("#description").val(project.description);
                $("#form-modal").modal('show');
            },
            error: function(response) {
                console.log(response.responseJSON)
            }
        });
    }

    /*
        sumbit the form and will update a record
    */
    function updateProject() {
        $("#save-project-btn").prop('disabled', true);
        let url = $('meta[name=app-url]').attr("content") + "/projects/" + $("#update_id").val();
        let data = {
            id: $("#update_id").val(),
            name: $("#name").val(),
            description: $("#description").val(),
        };

        $("#editModal").modal('show');

        $("#yes-edit-btn").one("click", function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "PUT",
                data: data,
                success: function(response) {
                    $("#save-project-btn").prop('disabled', false);
                    let successHtml =
                        '<div class="alert alert-success" role="alert"><b>Project Updated Successfully</b></div>';
                    $("#alert-div").html(successHtml);
                    $("#name").val("");
                    $("#description").val("");
                    reloadTable();
                    $("#form-modal").modal('hide');
                },
                error: function(response) {
                    $("#save-project-btn").prop('disabled', false);
                    if (typeof response.responseJSON.errors !== 'undefined') {
                        let errors = response.responseJSON.errors;
                        let descriptionValidation = "";
                        if (typeof errors.description !== 'undefined') {
                            descriptionValidation = '<li>' + errors.description[0] + '</li>';
                        }
                        let nameValidation = "";
                        if (typeof errors.name !== 'undefined') {
                            nameValidation = '<li>' + errors.name[0] + '</li>';
                        }

                        let errorHtml = '<div class="alert alert-danger" role="alert">' +
                            '<b>Validation Error!</b>' +
                            '<ul>' + nameValidation + descriptionValidation + '</ul>' +
                            '</div>';
                        $("#error-div").html(errorHtml);
                    }
                },
                complete: function() {
                    $("#editModal").modal('hide');
                    $("form-modal").modal('hide');
                }
            });
        })
        $("#no-edit-btn, #close-edit-btn").one("click", function() {
            $("#editModal").modal('hide');
            $("#save-project-btn").prop('disabled', false);
        });
    }

    /*
        get and display the record info on modal
    */
    function showProject(id) {
        $("#name-info").html("");
        $("#description-info").html("");
        let url = $('meta[name=app-url]').attr("content") + "/projects/" + id + "";
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                let project = response.project;
                $("#name-info").html(project.name);
                $("#description-info").html(project.description);
                $("#view-modal").modal('show');

            },
            error: function(response) {
                console.log(response.responseJSON)
            }
        });
    }

    /*
        delete record function
    */
    function destroyProject(id) {
        // Store the URL and data in variables for later use
        let url = $('meta[name=app-url]').attr("content") + "/projects/" + id;
        let data = {
            name: $("#name").val(),
            description: $("#description").val(),
        };

        // Show the modal
        $("#deleteModal").modal('show');

        // Attach a one-time event handler to the "Yes" button
        $("#yes-delete-btn").one("click", function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "DELETE",
                data: data,
                success: function(response) {
                    let successHtml =
                        '<div class="alert alert-success" role="alert"><b>Project Deleted Successfully</b></div>';
                    $("#alert-div").html(successHtml);
                    reloadTable();
                },
                error: function(response) {
                    console.log(response.responseJSON);
                },
                complete: function() {
                    // Hide the modal whether the AJAX request was successful or not
                    $("#deleteModal").modal('hide');
                }
            });
        });

        // If the "No" or "Close" button is clicked, simply hide the modal
        $("#no-delete-btn, #close-delete-btn").one("click", function() {
            $("#deleteModal").modal('hide');
        });
    }
    </script>
</body>

</html>