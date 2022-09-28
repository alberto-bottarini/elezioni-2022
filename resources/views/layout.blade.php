<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative&family=Inter:wght@300;400;700&display=swap" rel="stylesheet"> 
</head>
<body>

  <div class="bg-blue-800 text-white p-4">
    <div class="container mx-auto">
        <h1 class="text-2xl">Elezioni 2022</h1>
    </div>
  </div>

  <div class="container mx-auto my-4">
    <div class="flex flex-wrap">
        <div class="md:w-2/3 w-full">
            @yield('content')
        </div>
    </div>
  </div>

  <div class="bg-blue-800 text-white p-4">
    <div class="container mx-auto">
        <div class="text-sm">
            Io sono il footer
        </div>
    </div>
  </div>

</body>
</html>