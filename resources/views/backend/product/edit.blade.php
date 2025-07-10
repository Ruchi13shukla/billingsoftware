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
            @method('PUT')
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
          <label>Cost Price</label>
          <input type="number" name="cost_price" value="{{ $product->cost_price }}" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Quantity</label>
          <input type="number" name="quantity" value="{{ $product->quantity }}" class="form-control" required>
        </div>

        <div class="form-group">
    <label for="gst_status">GST Status</label>
    <select name="gst_status" id="gst_status" class="form-control" onchange="toggleGstPercentage()">
        <option value="Included" {{ $product->gst_status === 'Included' ? 'selected' : '' }}>Included</option>
        <option value="Excluded" {{ $product->gst_status === 'Excluded' ? 'selected' : '' }}>Excluded</option>
        <option value="Non-GST" {{ $product->gst_status === 'Non-GST' ? 'selected' : '' }}>Non-GST</option>
    </select>
</div>



    <div class="form-group">
              <label>GST Percentage</label>
              <input type="number" name="gst_percentage" step="0.01" value="{{ $product->gst_percentage }}" class="form-control">
            </div>                                           
        <button type="submit" class="btn btn-success">Update Product</button>
      </form>
    </div>
  </div>
</div>
</section>
</div>
@endsection

<script>
  function toggleGstPercentage() {
    const gstStatus = document.getElementById('gst_status').value;
    const gstPercentageInput = document.querySelector('input[name="gst_percentage"]').parentElement;
    
    if (gstStatus === 'Non-GST') {
      gstPercentageInput.style.display = 'none';
    } else {
      gstPercentageInput.style.display = 'block';
    }
  }


  document.addEventListener('DOMContentLoaded', toggleGstPercentage);
</script>

