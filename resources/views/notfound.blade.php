<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Page is not found</title>

    <link rel="stylesheet" href="/css/grid.min.css">
    <link rel="stylesheet" href="/css/all.css">

    <script>
      (function(){
            window.Laravel = {
                csrfToken: '{{ csrf_token() }}'
            };
        })();
    </script>

  </head>

  <body>
    <h1>The Page you are looking for is not found</h1>
  </body>

</html>
