<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>Verrification your Email</title>
</head>

<body>
    <div class="container">
        <div class="card text-white bg-success mt-2">
            <div class="card-body">
                <h4 class="card-title d-flex text-center">verrify your account</h4>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <h4 class="card-title">Your Email is {{$user->email}}</h4>
                <p class="card-text">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{env("APP_URL")}}auth/verif-email/{{$user->remember_token}}" class="btn btn-success">Verrify your account</a>
                        </div>
                    </div>
                </p>
            </div>
        </div>
    </div>
</body>

</html>

