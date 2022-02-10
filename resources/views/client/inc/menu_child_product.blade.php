 @if ($cate_parent->child->count())
    
         <ul class="sub-menu">
              @foreach ($cate_parent->child as $children)
             <li> <a href="{{route('client.product.category.list',['slug'=>$children->slug,'id'=>$children->id])}}" title="">{{ $children->cat_title }}</a>
            @if ($children->child->count())
            @include('client.inc.menu_child_product',['cate_parent'=>$children])     
            @endif
             @endforeach
            </li>
         </ul>
    

 @endif
