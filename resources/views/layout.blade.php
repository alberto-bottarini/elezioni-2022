<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  @yield('meta')
</head>
<body class="px-2 md:px-0">

  <div class="bg-sky-800 text-white p-4">
    <div class="container mx-auto">
        <h1 class="text-2xl">
          <svg class="h-8 w-8 fill-white inline-block mr-2 " version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.003 512.003" xml:space="preserve">
          <g>
            <path d="M505.538,64.857l-149.333-64c-4.373-1.877-9.515-0.597-12.523,3.136l-42.667,53.333c-2.027,2.517-2.795,5.803-2.091,8.96
              c0.661,3.008,2.624,5.483,5.291,6.955l-134.016,50.261c-11.243,4.224-20.864,12.224-27.029,22.507l-44.288,73.813
              c-9.429,15.723-10.176,34.901-1.963,51.285l3.115,6.229H53.335c-5.888,0-10.667,4.779-10.667,10.667V490.67h-32
              c-5.888,0-10.667,4.779-10.667,10.667c0,5.888,4.779,10.667,10.667,10.667h362.667c5.888,0,10.667-4.779,10.667-10.667
              c0-5.888-4.779-10.667-10.667-10.667h-32V341.337c0-0.811-0.299-1.515-0.448-2.261c20.011-13.547,42.88-34.453,62.827-66.752
              c33.899-54.827,52.949-98.965,60.779-118.827l1.195,0.597c4.565,2.283,9.451,3.413,14.336,3.413c5.824,0,11.627-1.6,16.789-4.821
              c9.515-5.867,15.189-16.043,15.189-27.221V74.649C512.002,70.382,509.463,66.542,505.538,64.857z M320.002,490.67h-256v-192
              h53.483c0.256,0,0.512,0.021,0.789,0h84.565c-9.621,5.845-21.44,13.205-36.395,22.72c-11.371,7.232-19.157,18.453-21.931,31.637
              c-2.795,13.184-0.213,26.645,7.317,37.909c9.536,14.357,25.259,22.165,41.344,22.165c8.704,0,17.536-2.304,25.536-7.061
              l73.301-43.968c4.203-1.024,14.507-3.989,27.989-10.709V490.67z M178.391,250.393c16.32-6.997,38.144-19.669,49.216-32.576
              c8.512,4.992,21.909,11.413,38.656,14.699c-11.904,14.741-23.851,32.661-28.907,45.355c-0.875-0.235-1.728-0.533-2.688-0.533
              H190.85C188.588,266.734,183.362,257.369,178.391,250.393z M385.623,261.102c-42.069,68.053-99.115,80.363-99.669,80.469
              c-1.195,0.256-2.368,0.704-3.413,1.323l-74.752,44.843c-13.013,7.829-29.781,4.053-38.229-8.619
              c-4.267-6.421-5.739-14.101-4.16-21.632c1.6-7.509,6.037-13.931,12.523-18.048c57.323-36.501,67.669-40.512,67.456-40.747
              c5.909,0,10.645-4.267,10.645-10.155c1.088-6.677,20.523-37.909,39.573-56.96c3.051-3.051,3.968-7.637,2.304-11.627
              c-1.643-3.989-5.547-6.592-9.856-6.592c-33.195,0-57.088-18.795-57.323-18.987c-3.2-2.539-7.488-3.733-11.2-1.941
              c-3.712,1.771-5.973,4.843-5.973,8.939c-3.776,9.152-44.715,32.299-53.547,33.301c-4.395,0-8.299,2.667-9.899,6.763
              c-1.6,4.096-0.533,8.747,2.688,11.733c0.128,0.128,11.307,10.752,15.872,24.171h-44.757l-7.872-15.787
              c-4.928-9.835-4.501-21.333,1.173-30.763l44.288-73.813c3.712-6.187,9.472-10.965,16.213-13.504l152.363-57.131l115.264,57.621
              C438.274,161.987,419.436,206.382,385.623,261.102z M490.668,125.507c0,5.376-3.541,8.128-5.056,9.067s-5.589,2.859-10.368,0.469
              L325.911,60.377l29.355-36.672l135.403,58.027V125.507z" stroke="white" stroke-width="10"/>
          </g>
        </svg>
        Elezioniamo 2022</h1>
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