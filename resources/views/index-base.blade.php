<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> @yield("title") IVAO - Tracker</title>
  <meta name="description" content="Find on this site, all metar, platform information, your friends, your flight plans save, then redownload them">
  <meta name="keywords" content="ivao, information, website, unofficial, pirep, fpl, metar, plateform, friend, metar, follows">
    <meta name="author" content="Alexcaussades">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="google" content="notranslate">
    <meta property="og:title" content="Alexcaussades - IVAO - INFO">
    <meta property="og:description" content="Find on this site, all metar, platform information, your friends, your flight plans save, then redownload them">
    <meta property="og:site_name" content="Alexcaussades - IVAO - INFO">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:locale:alternate" content="fr_FR">
    <meta property="og:locale:alternate" content="en_GB">
    <meta property="og:locale:alternate" content="fr_BE">
    <meta property="og:locale:alternate" content="en_BE">
    <meta property="og:locale:alternate" content="fr_LU">
    <meta property="og:locale:alternate" content="en_LU">
    <meta property="og:locale:alternate" content="fr_CH">
    <meta property="og:locale:alternate" content="en_CH">
    <meta property="og:locale:alternate" content="fr_CA">
    <meta property="og:locale:alternate" content="en_CA">
    <meta property="og:locale:alternate" content="fr_MC">
    <meta property="og:locale:alternate" content="en_MC">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Alexcaussades - IVAO - INFO">
    <meta name="twitter:description" content="Find on this site, all metar, platform information, your friends, your flight plans save, then redownload them">
    <meta name="twitter:creator" content="alexcaussades">
    
  <!-- CSS only -->
  @if (ENV('APP_ENV') == 'local')
  <link rel="stylesheet" href="{{ asset("asset/css/bootstrap.min.css") }} " type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&family=Oswald" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset("asset/css/style.css") }} " type="text/css">
  <link rel="stylesheet" href="{{ asset("asset/css/index.css") }} " type="text/css">
  @else
  <link rel="stylesheet" href="{{ asset("public/asset/css/bootstrap.min.css") }} " type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&family=Oswald" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset("public/asset/css/style.css") }} " type="text/css">
  @endif
</head>
@yield('navbar')

<body>


  @yield('content')

  <!-- JavaScript Bundle with Popper -->
  @if (ENV('APP_ENV') == 'local')
  <script src="{{ asset("asset/js/bootstrap.bundle.min.js") }}"></script>
  @else
  <script src="{{ asset("public/asset/js/bootstrap.bundle.min.js") }}"></script>
  @endif
</body>

</html>