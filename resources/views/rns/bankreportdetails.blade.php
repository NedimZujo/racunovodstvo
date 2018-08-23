@extends('layouts.app')

@section('content')



<div class="container-fluid">
	<div class="row mb-2">
	<div class="col-12">
	<nav class="navbar navbar-expand-lg navbar-light bg-light rounded bg-light shadow">
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample09">
		          <ul class="navbar-nav mr-auto">
		            
		            <li class="nav-item ">
		              <a class="nav-link" href="../home">Pare na ruke</a>
		            </li>
		            <li class="nav-item">
		              <a class="nav-link" href="../banka">Banka</a>
		            </li>
		             <li class="nav-item dropdown active">
		              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Izvještaji</a>
		              <div class="dropdown-menu">
		                <a class="dropdown-item" href="../bankreports">Bankovni</a>
		                <a class="dropdown-item" href="../reports">Pare na ruke</a>
                
            		</li>		            
		          </ul>
		          
		        </div>
      </nav>
  </div>
  </div>
	<div class="row">
		
	
		<div class="col-12">
			<div class="tab-content" id="v-pills-tabContent">
			    <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
				  	<table class="table table-hover bg-light shadow">
						<thead>
							<tr>
								
								<th>Mjesec</th>
								<th>Prihod</th>
								<th>Rashod</th>
								<th>Total</th>
								
							</tr>
						</thead>
						<tbody>
						@foreach($mjesecni_array as $mjesec => $mjesecni2)
							<tr>
								<td>{{App\Helpers\DateTimeHelper::getLocalMonthName($mjesec)}}</td>
								<td>{{isset($mjesecni2["prihod"]) ? $prihod=$mjesecni2["prihod"] : $prihod=0}}</td>
								<td>{{isset($mjesecni2["rashod"]) ? $rashod=$mjesecni2["rashod"] : $rashod=0 }}</td>
								<td>{{$prihod-$rashod}}</td>
								

							</tr>
						@endforeach
					</tbody>
					</table>
				</div>
			  <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
			  	<table class="table table-hover bg-light shadow">
					<thead>
						<tr>
							
							<th>Korisnik</th>
							<th>Prihod</th>
							<th>Rashod</th>
							<th>Total</th>
							
						</tr>
					</thead>
					<tbody>
						@foreach($korisnici as $korisnik)
							<tr>
								<td>{{$korisnik->user}}</td>
								<td>{{$korisnik->Prihod}}</td>
								<td>{{$korisnik->Rashod}}</td>
								<td><b>{{$korisnik->Prihod-$korisnik->Rashod}}</b></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			  </div>
			</div>
		</div>
	</div>
</div>



@stop