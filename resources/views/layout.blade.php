<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="px-2 md:px-0">

  <div class="bg-sky-800 text-white p-4">
    <div class="container mx-auto">
        <h1 class="text-2xl">Elezioni 2022</h1>
    </div>
  </div>

  <div class="container mx-auto my-4">
    <div class="flex flex-wrap">
        <div class="w-full">
            @yield('content')
        </div>
    </div>
  </div>

  <footer class="bg-sky-800 text-white p-4">
    <div class="container mx-auto">
        <div class="text-sm">
            Elezioni 2022 è un progetto di <a href="https://www.albertobottarini.com" target="_blank">Alberto Bottarini</a>
            <br>
            I dati visualizzati sono stati ottenuti grazie al lavoro di <a href="https://github.com/ondata/elezioni-politiche-2022" target="_blank">OnData</a>
         </div>
    </div>
  </footer>

  @vite('resources/js/app.js')
</body>
</html>