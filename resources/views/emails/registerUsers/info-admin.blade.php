<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>New Register users</title>
</head>

<body>
    <div class="container">
        <div class="card text-white bg-success mt-2">
            <div class="card-body">
                <h4 class="card-title d-flex text-center">New Register users</h4>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <h4 class="card-title">Information on the users !</h4>
                <p class="card-text">
                    <div class="row">
                        <li>Full name: {{ $user->name}}</li>
                        <li>VID: {{ $user->vid}}</li>
                        <li>Email: {{ $user->email}}</li>
                    </div>
                </p>
            </div>
        </div>
    </div>
</body>

</html>