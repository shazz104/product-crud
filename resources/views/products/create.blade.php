<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body>

<div class="container panel panel-default ">
        <h2 class="panel-heading">Add/Edit Product</h2>
    <form id="productForm">
      @csrf
      <input type="hidden" name="product-id" class="form-control" id="product-id" value={{ $product->id ?? ""}}>
        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Enter Name" id="name" value={{ $product->name ?? ""}}>
            <span class="text-danger" id="name-error"></span>
        </div>

        <div class="form-group">
            <input type="text" name="description" class="form-control" placeholder="Enter Description" id="description" value={{ $product->description ?? ""}}>
            <span class="text-danger" id="description-error"></span>
        </div>
    

        <div class="form-group">
            <input type="text" name="price" class="form-control" placeholder="Enter Price" id="price" value={{ $product->price ?? ""}}>
            <span class="text-danger" id="price-error"></span>
        </div>

        <div class="form-group">
            <label>Select</label>
            <select id="categories" name="categories[]" multiple class="form-control" >
              @php
                  $catIds = isset($product) ? $product->categories->pluck('id')->toArray() : [];
              @endphp
              @forelse ($categories as $category)
                <option value={{$category->id}} 
                  @if (in_array($category->id,$catIds))
                      selected
                  @endif
                >{{$category->name ?? ""}}</option>
              @empty
                <option value="">No Data Found</option>
              @endforelse
            </select>
            <span class="text-danger" id="categories-error"></span>

        </div>

        <div class="form-group">
          <span class="text-danger" id="all-error"></span>
          <span class="text-success" id="success-message"></span>

        </div>
       
        <div class="form-group">
            <button class="btn btn-sm btn-success" id="submit">Submit</button>
            <a href="./" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Back</a>
        </div>
    </form>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

   <script type="text/javascript">

    $('#productForm').on('submit',function(e){
        e.preventDefault();

        let name = $('#name').val();
        let description = $('#description').val();
        let price = $('#price').val();
        let categories = $('#categories').val();
        let id = $('#product-id').val();
        var url = "/products";
        var type = "POST";
        if (id !== ""){
          url = "/products/" + id;
          type = "PUT";
        
        }
        $.ajax({
          url: url,
          type:type,
          data:{
            "_token": "{{ csrf_token() }}",
            name:name,
            description:description,
            price:price,
            categories:categories,
           
          },
          success:function(response){
            console.log(response);
            if (response) {
              $('#success-message').text(response.success); 
              $("#productForm")[0].reset(); 
              window.location.href = '/products';
            }
          },
          error: function(response) {
            $('#name-error').text(response.responseJSON.errors.name);
            $('#description-error').text(response.responseJSON.errors.description);
            $('#price-error').text(response.responseJSON.errors.price);
            $('#categories-error').text(response.responseJSON.errors.categories);
            $('#all-error').text(response.responseJSON.errors.all-error);
           }
         });
        });
      </script>
 </body>
</html>