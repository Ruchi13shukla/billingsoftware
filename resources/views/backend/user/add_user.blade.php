@extends('backend.layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-lg-1"></div>

            <div class="col-lg-sm">
                {{-- ---card start--- --}}
                <div class="card">
                    <div class="card-header"> 
                        <h6 class="card-title">Add Product</h6>
                    </div> 

                    {{-- ---start card body--- --}}
                    <div class="card-body"> 
                        <form role="form" action="{{ URL::to('/insert-product') }}" method="post">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-sm-6 col-form-label">Product Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" placeholder="Enter Product Name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="category" class="col-sm-6 col-form-label">Category</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="category" placeholder="Enter Category" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="price" class="col-sm-6 col-form-label">Price</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="price" step="0.01" placeholder="Enter Price" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="quantity" class="col-sm-6 col-form-label">Quantity</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="quantity" placeholder="Enter Quantity" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="gst_status" class="col-sm-6 col-form-label">GST Status</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="gst_status" required>
                                        <option value="">-- Select GST Status --</option>
                                        <option value="Included">Included</option>
                                        <option value="Excluded">Excluded</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                    {{-- ---end card body--- --}}

                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                    </form>    
                </div>
                {{-- ---card end--- --}}
            </div>

            <div class="col-lg-1"></div>
        </div>
    </section>
</div>

@endsection
