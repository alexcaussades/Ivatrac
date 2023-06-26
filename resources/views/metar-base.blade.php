<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> @yield("title")</title>
  <!-- CSS only -->
  <link rel="stylesheet" href="{{ asset("asset/css/bootstrap.min.css") }} " type="text/css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" >
  <link rel="stylesheet" href="{{ asset("asset/css/style.css") }} " type="text/css">
</head>
@yield('navbar-metar')

<body>


  @yield('content')

  <!-- JavaScript Bundle with Popper -->
  <script src="{{ asset("asset/js/bootstrap.bundle.min.js") }}"></script>
  
</body>

</html>