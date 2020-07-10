<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Talisman</title>

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
    <div id="app">
      @if(Auth::check())
      {{-- props = :user 같이 component를 통해 보내는 데이터 --}}
      <mainapp :user="{{ Auth::user() }}"></mainapp>
      @else
      <mainapp :user="false"></mainapp>
      @endif
    </div>
  </body>
  <script src="{{ mix('/js/app.js') }}"></script>

</html>
