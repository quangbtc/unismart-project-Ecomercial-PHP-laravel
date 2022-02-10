  <div class="sidebar fl-left">
        <div class="section" id="category-product-wp">
            <div class="section-head">
                <h3 class="section-title">Danh mục Sản phẩm</h3>
            </div>
            <div class="secion-detail">
                <ul class="list-item">
                   
                        @foreach ($parent_cat as $cate_parent)
                            <li>
                                <a href="{{route('client.product.category.list',['slug'=>$cate_parent->slug,'id'=>$cate_parent->id])}}" title="">{{ $cate_parent->cat_title }}</a>
                               @include('client.inc.menu_child_product',['cate_parent'=>$cate_parent])                            
                            </li>
                        @endforeach    
                </ul>
            </div>
        </div>
         <div class="section" id="selling-wp">
                <div class="section-head">
                    <h3 class="section-title">Sản phẩm mới nhất</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @if($new_products)
                        @foreach ($new_products as $product )
                             <li class="clearfix">
                            <a href="{{route('client.product.detail',['slug'=>$product->slug,'id'=>$product->id])}}" title="" class="thumb fl-left">
                                <img src="{{url("$product->thumb")}}" alt="">
                            </a>
                            <div class="info fl-right">
                                <a href="{{route('client.product.detail',['slug'=>$product->slug,'id'=>$product->id])}}" title="" class="product-name">{{$product->title}}</a>
                                <div class="price">
                                    <span class="new">{{number_format($product->sale_price,'0','','.').' đ'}}</span>
                                    <span class="old">{{number_format($product->old_price,'0','','.').' đ'}}</span>
                                </div>
                                <a href="{{route('cart.add',$product->id)}}" title="" class="buy-now">Mua ngay</a>
                            </div>
                        </li>
                        @endforeach
                        @endif
                       
                       
                    </ul>
                </div>
            </div>
        <div class="section" id="banner-wp">
            <div class="section-detail">
                <a href="?page=detail_blog_product" title="" class="thumb">
                    <img src="{{ asset('client/images/banner.png') }}" alt="">
                </a>
            </div>
        </div>
    </div>
