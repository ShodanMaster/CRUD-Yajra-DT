<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('sweetalert/sweetalert2.min.css')}}">
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="createModalLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" id="categoryForm">

            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="name" required>
                    <span id="nameError" class="text-danger error-message"></span>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="saveBtn"></button>
            </div>
        </form>
      </div>
    </div>
</div>

    <div class="containet mt-5">
        <div class="d-flex justify-content-between">
            <h1 class="text-danger">Category</h1>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                Create
            </button>
        </div>
    </div>

    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('sweetalert/sweetalert2.min.js')}}"></script>
    <script src="{{asset('bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('#createModalLabel').html('Create Category');
            $('#saveBtn').html('Save Category');
            var form = $('#categoryForm')[0];
            $('#saveBtn').click(function(e){
                e.preventDefault();

                $('.error-message').html('');

                // console.log('clicked');
                // var name = $('#name').val();
                // console.log(name);

                var formData = new FormData(form);
                // console.log(formData);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{route('store')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            $('#createModal').modal('hide');
                            $('#categoryForm')[0].reset();
                        });
                    },
                    error: function(error){
                        if(error){
                            console.log( error.responseJSON.errors.name);
                            $('#nameError').html(error.responseJSON.errors.name);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
