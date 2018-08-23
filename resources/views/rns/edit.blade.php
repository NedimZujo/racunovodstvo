@extends('layouts.app')

@section('content')

<div class="container-fluid">
  <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">

          <form method="POST" action="../update/{{$edit->id}}">
                    {{ csrf_field() }}
              <div class="container">
                <div class="row">
                  <div class="col-4">
                    <div class="switch-field">
                      <input type="radio" id="switch_left" name="type" value="Prihod" @if ($edit->type=="Prihod") checked @endif >
                      <label for="switch_left">Prihod</label>
                      <input type="radio" id="switch_right" name="type" value="Rashod" @if ($edit->type=="Rashod") checked @endif>
                      <label for="switch_right">Rashod</label>
                    </div>
                    
                    <div class="input-group mb-3">
                      <select type="text" class="form-control" name="user_id" placeholder="User name">
                        <option>Izaberi korisnika</option>
                          @foreach ($users as $user)
                        <option value="{{$user->id}}"  @if ($user->id==$edit->user_id) selected @endif >{{$user->name}}</option>
                          @endforeach
                      </select>
                    </div>
                 
                    <div class="input-group mb-3">
                      <input type="text" name="date" class="datepicker" id="datepicker" value="{{$edit->date}}">
                    </div>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" name="amount" placeholder="Amount" value="{{$edit->amount}}">
                    </div>
                    <div class="input-group mb-3">
                      <textarea type="text" name="description" placeholder="Description" cols="80" >{{$edit->description}}</textarea> 
                    </div> 
                      <a href="../home">
                        <button type="button" class="btn">Back</button>
                      </a>
                  
                      <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                    
                    <div class="col-8">
                    </div>
                           
                </div>
              </div> 
          </form>
        </div>
      </div>
  </div>
</div>

@stop