@php
$config = [
    'appName' => config('app.name'),
    'locale' => $locale = app()->getLocale(),
    'locales' => config('app.locales'),
    'githubAuth' => config('services.github.client_id'),
];
$appJs = mix('dist/js/app.js');
$appCss = mix('dist/css/app.css');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>

    <meta property="og:title" content="MY LOVE QUEST | Prnlight Novels">
    <meta property="og:description" content="A gospel romance and must- read for all matured singles aspiring to be in a romantic relationship leading to marriage.">
    <meta property="og:image" content="{{asset('images/cover.jpg')}}">
    <meta property="og:url" content="https://penlight.emmanueltaiwo.com/">
    <meta name="twitter:card" content="summary_large_image">

  <link rel="stylesheet" href="{{ (str_starts_with($appCss, '//') ? 'http:' : '').$appCss }}">
  <link rel="stylesheet" href="{{asset('css/all.css')}}">
</head>
<body>
  <div id="app"></div>

  <script>
    window.config = @json($config);
  </script>

  <script src="{{ (str_starts_with($appJs, '//') ? 'http:' : '').$appJs }}"></script>
</body>
</html>
