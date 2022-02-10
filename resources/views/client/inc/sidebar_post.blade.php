    <div class="sidebar fl-left">
        <div class="section" id="category-product-wp">
            <div class="section-head">
                <h3 class="section-title">Danh mục tin tức</h3>
            </div>
            <div class="secion-detail">
                <ul class="list-item">
                   
                        @foreach ($parent_cat as $cate_parent)
                            <li>
                                <a href="{{route('client.post.category.list',['slug'=>$cate_parent->slug,'id'=>$cate_parent->id])}}" title="">{{ $cate_parent->cat_title }}</a>
                               @include('client.inc.menu_child_post',['cate_parent'=>$cate_parent])                            
                            </li>
                        @endforeach    
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
