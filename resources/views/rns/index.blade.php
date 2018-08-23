@extends('layouts.app')

@section('content')



<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-md-12">

  <nav class="navbar navbar-expand-lg navbar-light bg-light rounded" class="bg-light shadow">
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample09">
          <ul class="navbar-nav mr-auto">
            
            <li class="nav-item active">
              <a class="nav-link" href="home">Pare na ruke</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="banka">Banka</a>
            </li>
             <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Izvještaji</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="bankreports">Bankovni</a>
                <a class="dropdown-item" href="reports">Pare na ruke</a>
                
            </li>
            
            
            
          </ul>
          
        </div>
      </nav>
    </div>
  </div> 

  
  <div class="row">
    <div class="col-md-12">

      <nav class="navbar navbar-light bg-light justify-content-between bg-light shadow style="width: 100%;border:1px solid #ddd">
        
       

        <form class="form-inline" method="POST" action="search">
          {{ csrf_field() }}
          <div class="form-group" >
            <label class="sr-only" for="From">From:</label>
           <input type="text" value="{{$datefrom}}" name="datefrom" class="datepicker form-control mr-sm-2" autocomplete="off">
          
            <label class="sr-only" for="exampleTo">To:</label>
            <input type="text" value="{{$dateto}}" name="dateto" class="datepicker form-control mr-sm-2" autocomplete="off">
          
            
              <button type="submit"  class="btn btn-secondary my-2 my-sm-0" style="margin-right:10px"><i class="fa fa-search"></i> Filter</button>
            
          <a href="home">           
                       <button class="btn btn-secondary my-2 my-sm-0" type="button"> <i class="fa fa-refresh"></i> <?php echo date('Y') ?></button>
                      </a>
                      </div>
        </form>

      </nav>
    </div>
  </div>


     

  <div class="row">
    <div class="col-md-12">

<!-- Button trigger modal -->
      <div class="modalContainer">
          <button type="button" class="btn btn-primary defaultBtn" data-toggle="modal" data-target="#exampleModal">
          Unos prihoda/rashoda
          </button>
          @if ((date('Y-m-d')>=date('Y-12-25')) && (date('Y-m-d')<=date('Y-12-31')))
          <a href="transfersredstava">           
            <button class="btn btn-secondary my-2 my-sm-0" type="button">Transfer sredstava </button>
          </a>
          @endif
      </div>


      <!-- Modal -->
      <div class="modal fade " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Unos prihoda/rashoda</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" action="home">
                {{ csrf_field() }}
                <div class="switch-field">
                  <input type="radio" id="switch_left" name="type" value="Prihod" checked/>
                  <label for="switch_left">Prihod</label>
                  <input type="radio" id="switch_right" name="type" value="Rashod" />
                  <label for="switch_right">Rashod</label>
                </div>
                <div class="input-group mb-3">
                  <select type="text" class="form-control" name="user_id" placeholder="User name" required>
                    <option value="">Izaberi korisnika</option>
                      @foreach ($users as $user)
                    <option value="{{$user->id}}" >{{$user->name}}</option>
                      @endforeach
                  </select>
             
                </div>
                <div class="input-group mb-3">
                  <input type="text" name="date" class="datepicker" id="datepicker" placeholder="Date" style="padding-left:15px"autocomplete="off" required>
                </div>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" name="amount" placeholder="Amount" autocomplete="off" required>
                </div>
                <div class="input-group mb-3">
                  <textarea type="text" name="description" placeholder="Description" cols="80"></textarea> 
                </div>  
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" value="Save" class="btn btn-primary"> 
              </form>
      		  </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<br>

  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive bg-light shadow">
      <table id="myTable" class="display" style="width:100%">
        <thead>
                  
          <tr>
            
            <th>Korisnik</th>
            <th>Tip</th>
            <th>Datum</th>
            <th>Opis</th>
            <th>Iznos</th>
            <th>Akcija</th>
          </tr>
        </thead>
        <tbody>
      @php
      $total=0;
      $totalPrihod=0;
      $totalRashod=0;
      @endphp


      @foreach($amounts as $amount)

        @if($amount->type=='Prihod')
          @php
           $klasaType='text-success';
           $predznak='';
           $total=$total+$amount->amount;
           $totalPrihod=$totalPrihod+$amount->amount;
          @endphp

        @else
         @php 
          $klasaType='text-danger';
          $predznak='-';
          $total=$total-$amount->amount;
          $totalRashod=$totalRashod-$amount->amount;
          @endphp
        @endif

              <tr>
                <td>{{$amount->name}}</td>
                <td>{{$amount->type}}</td>
                <td>{{$amount->date}}</td>
                <td>{{$amount->description}}</td>
                <td class="bold {{$klasaType}}">{{$predznak.$amount->amount }}</td>
                
            
                <td>
          @if ($amount->transfer==0)
                 <form class="form-inline" method="POST" action="edit/{{$amount->id}}">
                     {{ csrf_field() }} 
                                             
                               
                    <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Uredi</button>
                 </form>
                  @endif
                </td>
              </tr>
                
         @endforeach
        
                
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align:right">Total (filter):</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
             <td></td>
             <td></td>
             <td></td>
             <td class="text-right text-success"><b>Total prihod (ukupni):</b></td>
             <td class="bold text-success">{{$totalPrihod}}</td>
             <td></td>
           </tr>

           <tr>
             <td></td>
             <td></td>
             <td></td>
             <td class="text-right text-danger"><b>Total rashod (ukupni):</b></td>
             <td class="bold text-danger">{{$totalRashod}}</td>
             <td></td>
           </tr>

           <tr>
             <td></td>
             <td></td>
             <td></td>
             <td class="text-right"><b>Total (ukupni):</b></td>
             <td class="bold">{{$total}}</td>
             <td></td>
           </tr>
        </tfoot>
    </table>
      </div>  
      </div>
  </div>
</div>

<script>

</script>

@stop