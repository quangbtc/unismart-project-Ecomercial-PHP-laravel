@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix blog-page">
    <div class="wp-inner">
        <div class="secion" id="breadcrumb-wp">
            <div class="secion-detail">
                <ul class="list-item clearfix">
                    <li>
                        <a href="" title="">Trang chủ</a>
                    </li>
                    <li>
                        <a href="" title="">Bài viết</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-content fl-right">
            <div class="section" id="list-blog-wp">
                <div class="section-head clearfix">
                    <h3 class="section-title">Blog</h3>
                </div>
                <div class="section-detail">
                    <ul class="list-item">
                        @if(isset($posts))
                            @foreach ($posts as $post)
                                 <li class="clearfix">
                            <a href="{{route('client.post.detail',$post->id)}}" title="" class="thumb fl-left">
                                <img width="250px" height="150px" src="{{url("$post->thumb")}}" alt="">
                            </a>
                            <div class="info fl-right">
                                <a href="{{route('client.post.detail',$post->id)}}" title="" class="title">{{$post->title}}</a>
                                <span class="create-date">{{$post->created_at}}</span>
                                <p class="desc">{!!$post->short_desc!!}</p>
                            </div>
                        </li>
                            @endforeach
                        
                        @endif
                       
                       
                    </ul>
                </div>
            </div>
            {{-- <div class="section" id="paging-wp">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="" title="">1</a>
                        </li>
                        <li>
                            <a href="" title="">2</a>
                        </li>
                        <li>
                            <a href="" title="">3</a>
                        </li>
                    </ul>
                </div>
            </div> --}}
            {{$posts->links()}}
        </div>
    @include('client.inc.sidebar_post')
    </div>
</div>
@endsection