<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> @yield("title") | Alexcaussades - IVAO - INFO</title>
  <!-- CSS only -->
  @if (ENV('APP_ENV') == 'local')
  <link rel="stylesheet" href="{{ asset("asset/css/bootstrap.min.css") }} " type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset("asset/css/style.css") }} " type="text/css">
  @else
  <link rel="stylesheet" href="{{ asset("public/asset/css/bootstrap.min.css") }} " type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset("public/asset/css/style.css") }} " type="text/css">
  @endif
</head>
@yield('navbar')

<body>


  @yield('content')

  <!-- JavaScript Bundle with Popper -->
  <script src="{{ asset("asset/js/bootstrap.bundle.min.js") }}"></script>
</body>

</html>