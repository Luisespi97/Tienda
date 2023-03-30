@extends('layouts.app')

@section('content')


    <h1>Create a Product</h1>
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @method('POST')
        @csrf
        
        <div class="form-row">
            <label >Title</label>
            <input class="form-control" type="text" name="title" value="{{old('title')}}" required>
        </div>
        <div class="form-row">
            <label >Description</label>
            <input class="form-control" type="text" name="description" value="{{old('description')}}" required>
        </div>
        <div class="form-row">
            <label >Price</label>
            <input class="form-control" type="number" min="1.00" step="0.01" name="price" 
                value="{{old('price')}}" required>
        </div>
        <div class="form-row">
            <label >Stock</label>
            <input class="form-control" type="number" min="0" name="stock" value="{{old('stock')}}" required>
        </div>
        <div class="form-row">
            <label >Status</label>
            <select class="custom-select" name="status" value="" required>
            <option value="" selected>Select ...</option>
            <option {{old('status') == 'available' ? 'select' : ''}} value="available" selected>Available</option>
            <option {{old('status')== 'unavailable' ? 'select' : ''}} value="unavailable" selected>Unavailable</option>
            </select>
        </div>
        <div class="form-row">
            <label class="col-md-4 col-form-label text-md-right">
                {{ __('Images') }}
            </label>
                <div class="custom-file">
                    <input type="file" accept="image/*" name="images[]" class="custom-file-input" multiple>
                    <label class="custom-file-label">
                        Product image...
                    </label>
                </div>
        </div>
        <div class="form-row">
            <button type="submit" class="btn btn-primary btn-lg mt-3">Create Product</button>
        </div>
    </form>
    
    
    
@endsection   