@extends('layouts.master')
@section('title')
    <title>Application Users</title>
@endsection
@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
    @include('layouts.inventoryMenu')
@endsection

@section('content')
   
    @include('partials.flashmessage')

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="thumbnail">
                    <img src="http://tech.firstpost.com/wp-content/uploads/2014/09/Apple_iPhone6_Reuters.jpg" alt="" class="img-responsive">
                    <div class="caption">
                        <h4 class="pull-right">$700.99</h4>
                        <h4><a href="#">Mobile Product</a></h4>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    </div>
                    <div class="ratings">
                        <p>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            (15 reviews)
                        </p>
                    </div>
                    <div class="space-ten"></div>
                    <div class="btn-ground text-center">
                        <button type="button" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add To Cart</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#product_view"><i class="fa fa-search"></i> Quick View</button>
                    </div>
                    <div class="space-ten"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="thumbnail">
                    <img src="http://tech.firstpost.com/wp-content/uploads/2014/09/Apple_iPhone6_Reuters.jpg" alt="" class="img-responsive">
                    <div class="caption">
                        <h4 class="pull-right">$700.99</h4>
                        <h4><a href="#">Mobile Product</a></h4>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    </div>
                    <div class="ratings">
                        <p>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            (15 reviews)
                        </p>
                    </div>
                    <div class="space-ten"></div>
                    <div class="btn-ground text-center">
                        <button type="button" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add To Cart</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#product_view"><i class="fa fa-search"></i> Quick View</button>
                    </div>
                    <div class="space-ten"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="thumbnail">
                    <img src="http://tech.firstpost.com/wp-content/uploads/2014/09/Apple_iPhone6_Reuters.jpg" alt="" class="img-responsive">
                    <div class="caption">
                        <h4 class="pull-right">$700.99</h4>
                        <h4><a href=  "#">Mobile Product</a></h4>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    </div>
                    <div class="ratings">
                        <p>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            (15 reviews)
                        </p>
                    </div>
                    <div class="space-ten"></div>
                    <div class="btn-ground text-center">
                        <button type="button" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add To Cart</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#product_view"><i class="fa fa-search"></i> Quick View</button>
                    </div>
                    <div class="space-ten"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade product_view" id="product_view">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                    <h3 class="modal-title">HTML5 is a markup language</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 product_img">
                            <img src="http://img.bbystatic.com/BestBuy_US/images/products/5613/5613060_sd.jpg" class="img-responsive">
                        </div>
                        <div class="col-md-6 product_content">
                            <h4>Product Id: <span>51526</span></h4>
                            <div class="rating">
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                (10 reviews)
                            </div>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <h3 class="cost"><span class="glyphicon glyphicon-usd"></span> 75.00 <small class="pre-cost"><span class="glyphicon glyphicon-usd"></span> 60.00</small></h3>
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <select class="form-control" name="select">
                                        <option value="" selected="">Color</option>
                                        <option value="black">Black</option>
                                        <option value="white">White</option>
                                        <option value="gold">Gold</option>
                                        <option value="rose gold">Rose Gold</option>
                                    </select>
                                </div>
                                <!-- end col -->
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <select class="form-control" name="select">
                                        <option value="">Capacity</option>
                                        <option value="">16GB</option>
                                        <option value="">32GB</option>
                                        <option value="">64GB</option>
                                        <option value="">128GB</option>
                                    </select>
                                </div>
                                <!-- end col -->
                                <div class="col-md-4 col-sm-12">
                                    <select class="form-control" name="select">
                                        <option value="" selected="">QTY</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                    </select>
                                </div>
                                <!-- end col -->
                            </div>
                            <div class="space-ten"></div>
                            <div class="btn-ground">
                                <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
                                <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-heart"></span> Add To Wishlist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')

<script>
    


</script>

@endpush
