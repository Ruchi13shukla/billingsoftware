@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper">
<section class="content">
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">All Products</h3>
        </div>
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Serial</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>GST</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody> 
          @foreach($products as $product)
  <tr>
      <td>{{ $loop->iteration }}</td> <!-- Serial number -->
      <td>{{ $product->name }}</td>
      <td>{{ $product->category }}</td>
      <td>{{ $product->price }}</td>
      <td>{{ $product->quantity }}</td>
      <td>{{ $product->gst_status }}</td>
      <td>
          <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
          <a href="{{ route('product.delete', $product->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
      </td>
  </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>Serial</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>GST</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
</div>
@endsection
