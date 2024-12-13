<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/validator/13.7.0/validator.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">User Form</h2>
        
        <!-- Form to submit user data -->
        <form id="userForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" >
                <div class="invalid-feedback" id="nameError"></div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" >
                <div class="invalid-feedback" id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone Number" >
                <div class="invalid-feedback" id="phoneError"></div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" placeholder="Enter Description" ></textarea>
                <div class="invalid-feedback" id="descriptionError"></div>
            </div>

            <div class="form-group">
                <label for="role_id">Role</label>
                <select class="form-control" name="role_id" id="role_id" >
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="roleError"></div>
            </div>

            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <input type="file" class="form-control-file" name="profile_image" id="profile_image" accept="image/*">
                <div class="invalid-feedback" id="profileImageError"></div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h2 class="mt-5">Users List</h2>
        <table class="table table-bordered" id="usersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Description</th>
                    <th>Role</th>
                    <th>Profile Image</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            // Submit Form via AJAX
            $('#userForm').on('submit', function (e) {
                e.preventDefault();

                resetErrors();

                let isValid = true;
                if (!$('#name').val()) {
                    isValid = false;
                    $('#name').addClass('is-invalid');
                    $('#nameError').text('Name is required.');
                }

                if (!$('#email').val() || !validator.isEmail($('#email').val())) {
                    isValid = false;
                    $('#email').addClass('is-invalid');
                    $('#emailError').text('Valid email is required.');
                }

                let phonePattern = /^[6-9]\d{9}$/;
                if (!$('#phone').val() || !phonePattern.test($('#phone').val())) {
                    isValid = false;
                    $('#phone').addClass('is-invalid');
                    $('#phoneError').text('Valid Indian phone number is required.');
                }

                if (!$('#description').val()) {
                    isValid = false;
                    $('#description').addClass('is-invalid');
                    $('#descriptionError').text('Description is required.');
                }

                if (!$('#role_id').val()) {
                    isValid = false;
                    $('#role_id').addClass('is-invalid');
                    $('#roleError').text('Role is required.');
                }

                if (!isValid) return;

                let formData = new FormData(this);
                $.ajax({
                    url: '/api/users',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        alert(response.message);
                        fetchUsers();
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        alert(Object.values(errors).join('\n'));
                    }
                });
            });

            // Fetch and display users
            function fetchUsers() {
                $.ajax({
                    url: '/api/users',
                    type: 'GET',
                    success: function (users) {
                        let rows = '';
                        users.forEach(user => {
                            rows += `<tr>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.phone}</td>
                                <td>${user.description}</td>
                                <td>${user.role.name}</td>
                                <td><img src="/storage/${user.profile_image}" width="50" /></td>
                            </tr>`;
                        });
                        $('#usersTable tbody').html(rows);
                    }
                });
            }

            fetchUsers();

            // Reset form input errors
            function resetErrors() {
                $('#name, #email, #phone, #description, #role_id').removeClass('is-invalid');
                $('#nameError, #emailError, #phoneError, #descriptionError, #roleError').text('');
            }
        });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>