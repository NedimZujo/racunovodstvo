<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <script src="https://use.fontawesome.com/d071c8ed0d.js"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> 
     <script>
        
 $( document ).ready(function() {
    $('#myTable').DataTable(
        {
            "paging":false,
            "info":false,
"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
               
           
 
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 4 ).footer() ).html(
                pageTotal
            );
        }
    } );


    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
    });

});
  </script>
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample07">
          <ul class="navbar-nav mr-auto">
            
            <li class="nav-item">
              <a class="nav-link" href="../../../rnsbaza/index.php">Porodice</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../../rnsbaza/rns-clanovi-aktivni.php">Članarine</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="../home">Računovodstvo</a>
            </li>
            
          </ul>
          
        </div>
      </div>
    </nav>
    <!-- <header id="top" class="rnsnavbar" role="banner"> -->
        <!-- <a class="porodice" href="index.php">Porodice</a>  <a class="clanarine" href="rns-clanovi-aktivni.php">Clanarine</a>  -->

    <!-- </header> -->
     @if (Session::has('poruka'))

        <div class="alert {{ Session::get('css_klasa_poruke', 'alert-success')}} alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>{{Session::get('message','Uspjeh!')}}</strong> {{Session::get('poruka')}} 
        </div>
        @endif
    
    <div id="app">


        <main class="py-4">
            @yield('content')

        </main>

                            
    </div>
</body>

</html>


