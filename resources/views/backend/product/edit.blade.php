@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper">
<section class="content">
<div class="container">
  <div class="card">
    <div class="card-header">
      <h4>Edit Product</h4>
    </div>
    <div class="card-body">
      <form action="{{ route('product.update', $product->id) }}" method="POST">
        @csrf
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Category</label>
          <input type="text" name="category" value="{{ $product->category }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="number" name="price" value="{{ $product->price }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="number" name="quantity" value="{{ $product->quantity }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label>GST Status</label>
          <select name="gst_status" class="form-control" required>
    <option value="Included" {{ $product->gst_status == 'Included' ? 'selected' : '' }}>Included</option>
    <option value="Excluded" {{ $product->gst_status == 'Excluded' ? 'selected' : '' }}>Excluded</option>
</select>
        </div>

        <button type="submit" class="btn btn-success">Update Product</button>
      </form>
    </div>
  </div>
</div>
</section>
</div>
@endsection
