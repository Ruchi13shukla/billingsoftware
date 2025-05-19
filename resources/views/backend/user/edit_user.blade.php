@extends('backend.layouts.app')
@section('content')

<div class="content-wrapper">

<section class="content">

<div class="row">
    <div class="col-lg-1">
    </div>

    <div class="col-lg-sm">

{{-- ---card start--- --}}

<div class="card">
<div class="card-header"> 
    <h6 class="card-title">  
        Edit User </h6>
</div> 
{{-- ---start card body--- --}}
<div class="card-body"> 

<form role="form" action="{{URL::to('/update-user/' .$edit->id)}}" method="post">
    @csrf

<div class="form-group row">
    <label for="name" class="col-sm-6 col-form-label"> User name </label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" placeholder="Enter your name" 
         value="{{$edit->name}}">
</div>
</div>
   

<div class="form-group row">
    <label for="name" class="col-sm-6 col-form-label"> User email </label>
    <div class="col-sm-10">
        <input type="email" class="form-control" name="email" placeholder="Enter Email Address" 
        value="{{$edit->email}}">
</div>
</div>

<div class="form-group row">
    <label for="name" class="col-sm-6 col-form-label"> Description</label>
    <div class="col-sm-10">
     <input type="text" class="form-control" name="description" placeholder="Enter  Description"
        value="{{$edit->description}}">
</div>
</div>



<div class="form-group row">
    <label for="name" class="col-sm-6 col-form-label"> Password </label>
    <div class="col-sm-10">
        <input type="password" class="form-control" name="password" placeholder="Enter Password" 
        value="{{$edit->password}}">
        
</div>
</div>

<div class="form-group row">
    <label for="name" class="col-sm-6 col-form-label"> User Role Type </label>
    <div class="col-sm-10">
    <select class="form-control" id="exampleformControlSelect1" name="role" required>
        <option value="Admin" {{'Admin' ==$edit->role ? 'selected' :''}}>Admin </option>
        <option value="User" {{'User' ==$edit->role ? 'selected' :''}}>User </option>
        <option value="Customer" {{'Customer' ==$edit->role ? 'selected' :''}}>Customer </option>
    </select>    
</div>
</div>

<div class="form-group row">
    <label for="name" class="col-sm-6 col-form-label">Due Date </label>
    <div class="col-sm-10">
        <input  name="date" id="date" type="text" class="form-control" placeholder="dd/mm/yyyy"
        value="{{$edit->date}}">
        
</div>
</div>

</div>


</div>{{-- ---end card body--- --}}

<div class="card-footer">
    <button type="submit" class="btn btn-info"> Submit </button>
</div>
</form>    
</div>
</div>{{-- ---card end--- --}}

    </div>

    <div class="col-lg-1">
    </div>
</div>
</section>
</div>
@endsection