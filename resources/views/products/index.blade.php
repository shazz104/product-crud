<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5" style="max-width: 550px">
        <div class="container-fluid">
            <h1>Products</h1>
            <div class="container panel panel-default ">
                <form id="searchForm" action='./'>
                    @if (auth()->user()->role->name == 'Admin')
                    <div class="form-group">
                        <a href="products/create" class="btn btn-success btn-sm active" role="button" aria-pressed="true">Create</a>
                    </div>
                    @endif
                 
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search" id="search">
                    </div>
            
                    <div class="form-group">
                        <label>Sort By:</label>
                        <select id="sortBy" name="sortBy" class="form-control" >
                            <option value="1">Price: High to Low</option>
                            <option value="0">Price: Low to High</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Filter Categories</label>
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
                       <input type="submit" class="btn btn-primary btn-sm" value="Apply Filter">
                       <input type="reset" class="btn btn-primary btn-sm" id="btnReset" value="Reset Filter">
                    </div>
                
                  
                </form>
        </div>
        </div>

        <div id="data-wrapper">
            <!-- Results -->
        </div>
        <!-- Data Loader -->
        <div class="auto-load text-center">
            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="#000"
                    d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                        from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
            </svg>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        var ENDPOINT = "{{ url('/') }}";
        var page = 1;
        var queryString = location.search;
        infinteLoadMore(page);
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                infinteLoadMore(page);
            }
        });
        $('#btnReset').click(function () {
            $('#searchForm').submit();
        });
        function infinteLoadMore(page) {
            $.ajax({
                    url: ENDPOINT + "/products"+queryString+"&page=" + page,
                    datatype: "html",
                    type: "get",
                    beforeSend: function () {
                        $('.auto-load').show();
                    }
                })
                .done(function (response) {
                    if (response.length == 0) {
                        $('.auto-load').html("No more data!!!");
                        return;
                    }
                    $('.auto-load').hide();
                    $("#data-wrapper").append(response);
                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log('Oops ! Something went wrong');
                });
        }

    
    </script>
</body>
</html>