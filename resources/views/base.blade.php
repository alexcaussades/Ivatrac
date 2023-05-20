<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield("title") | Site de test</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="{{ asset("asset/css/bootstrap.min.css") }} " type="text/css">
</head>
  @yield('navbar')
<body>


    @yield('content')

    <!-- JavaScript Bundle with Popper -->
    <script src="{{ asset("asset/js/bootstrap.bundle.min.js") }}"></script>
</body>

</html>