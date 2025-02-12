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
    <link rel="stylesheet" href="{{asset('datatable/dataTables.dataTables.min.css')}}">
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
            <input type="hidden" id="nameId" name="nameId">
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
        <div class="d-flex justify-content-between  mb-3">
            <h1 class="text-danger">Category</h1>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-sm btn-primary createButton" data-bs-toggle="modal" data-bs-target="#createModal">
                Create
            </button>
        </div>
        <table class="table" id="categoryTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('datatable/dataTables.min.js')}}"></script>
    <script src="{{asset('sweetalert/sweetalert2.min.js')}}"></script>
    <script src="{{asset('bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script>
        $(document).ready(function () {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('getcategories')}}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data : 'name'},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
            });


            $('#saveBtn').click(function(e){
                e.preventDefault();

                $('.error-message').html('');

                // console.log('clicked');
                // var name = $('#name').val();
                // console.log(name);

                var form = $('#categoryForm')[0];
                var formData = new FormData(form);
                // console.log(formData);

                $('#saveBtn').attr('disabled', true);
                $('#saveBtn').html('Saving...');

                $.ajax({
                    type: "POST",
                    url: "{{route('store')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            $('#saveBtn').html('Save Category');
                            $('#saveBtn').attr('disabled', false);
                            $('#createModal').modal('hide');
                            $('#categoryForm')[0].reset();
                        });
                    },
                    error: function(error){
                            $('#saveBtn').html('Save Category');
                            $('#saveBtn').attr('disabled', false);
                        if(error){
                            console.log( error.responseJSON.errors.name);
                            $('#nameError').html(error.responseJSON.errors.name);
                        }
                        table.reload();
                    }
                });
            });

            $('body').on('click', '.createButton',function(e){
                e.preventDefault();


                $('#createModalLabel').html('Create Category');
                $('#saveBtn').html('Save Category');
                $('#categoryForm')[0].reset();

            });

            $('body').on('click', '.editButton',function(e){
                e.preventDefault();

                var id = $(this).data('id');
                var name = $(this).data('name');
                console.log(id, name);

                $('.error-message').html('');

                $('#createModalLabel').html('Edit Category');
                $('#saveBtn').html('Update Category');
                $('#nameId').val(id);
                $('#name').val(name);

            });

            $('body').on('click', '.deleteButton', function(e) {
                e.preventDefault();

                var id = $(this).data('id');
                var name = $(this).data('name');

                Swal.fire({
                    title: 'Are you sure delete ' + name + ' ?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append('id', id);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('delete') }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                table.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                });
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                });
            });

        });
    </script>
</body>
</html>
