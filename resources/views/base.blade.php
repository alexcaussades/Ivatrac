<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> @yield("title") | IVATRAC</title>
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
<!-- Google Tag Manager -->
<script>
  (function(w, d, s, l, i) {
    w[l] = w[l] || [];
    w[l].push({
      'gtm.start': new Date().getTime(),
      event: 'gtm.js'
    });
    var f = d.getElementsByTagName(s)[0],
      j = d.createElement(s),
      dl = l != 'dataLayer' ? '&l=' + l : '';
    j.async = true;
    j.src =
      'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
    f.parentNode.insertBefore(j, f);
  })(window, document, 'script', 'dataLayer', 'GTM-NGSXDXBN');
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NMZLE46MQ2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NMZLE46MQ2');
</script>
<!-- End Google Tag Manager -->

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