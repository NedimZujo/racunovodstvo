@extends('layouts.app')

@section('content')

	<div class="container-fluid">
	  <div class="row">
	    <div class="col-md-12">
		 <nav class="navbar navbar-expand-lg navbar-light bg-light rounded bg-light shadow">
		        
		        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
		          <span class="navbar-toggler-icon"></span>
		        </button>

		        <div class="collapse navbar-collapse" id="navbarsExample09">
		          <ul class="navbar-nav mr-auto">
		            
		            <li class="nav-item ">
		              <a class="nav-link" href="home">Pare na ruke</a>
		            </li>
		            <li class="nav-item">
		              <a class="nav-link" href="banka">Banka</a>
		            </li>
		             <li class="nav-item dropdown active">
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
	      <div class="table-responsive">
			  	<table class="table table-hover bg-light shadow">
					<thead>
						<tr>
							
							<th>Godina</th>
							<th>Prihod</th>
							<th>Rashod</th>
							<th>Total</th>
							<th>Mjesečni/Individualni izvještaj</th>
						</tr>
					</thead>
						<tbody>
							@foreach($amounts as $amount)
								<tr>
									<td class="text-left">{{$amount->year}}</td>
									<td class="text-left">{{number_format($amount->Prihod, 2, ',', '.')}}</td>
									<td class="text-left">{{number_format($amount->Rashod, 2, ',', '.')}}</td>
									<td class="text-left"><b>{{number_format($amount->Prihod-$amount->Rashod, 2, ',', '.')}}</b></td>
									<td> <a href="reportdetails/{{$amount->year}}">
								       <div class="btn btn-success">Detalji</div>
								       </a>
								  	</td>
								</tr>
							@endforeach
						</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

			  
			    






@stop