@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="home-page clearfix">
        <div class="wp-inner">
            @include('client.inc.sidebar_product')
            <div class="main-content fl-right">
                <div class="section" id="slider-wp">
                    <div class="section-detail">
                        @if ($sliders)
                            @foreach ($sliders as $item)
                                <div class="item">
                                    <img src="{{ url("$item->image") }}" alt="">
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
                <div class="section" id="support-wp">
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-1.png') }}">
                                </div>
                                <h3 class="title">Miễn phí vận chuyển</h3>
                                <p class="desc">Tới tận tay khách hàng</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-2.png') }}">
                                </div>
                                <h3 class="title">Tư vấn 24/7</h3>
                                <p class="desc">1900.9999</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-3.png') }}">
                                </div>
                                <h3 class="title">Tiết kiệm hơn</h3>
                                <p class="desc">Với nhiều ưu đãi cực lớn</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-4.png') }}">
                                </div>
                                <h3 class="title">Thanh toán nhanh</h3>
                                <p class="desc">Hỗ trợ nhiều hình thức</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <img src="{{ asset('client/images/icon-5.png') }}">
                                </div>
                                <h3 class="title">Đặt hàng online</h3>
                                <p class="desc">Thao tác đơn giản</p>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="section" id="list-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Tìm kiếm</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            @if ($products->count() > 0)
                                @foreach ($products as $product)

                                    <li>
                                        <a href="{{ route('client.product.detail', ['slug' => $product->slug, 'id' => $product->id]) }}"
                                            title="" class="thumb">
                                            <img src="{{ url("$product->thumb") }}">
                                        </a>
                                        <a href="{{ route('client.product.detail', ['slug' => $product->slug, 'id' => $product->id]) }}"
                                            title="" class="product-name">{{ $product->title }}</a>
                                        <div class="price">
                                            <span
                                                class="new">{{ number_format($product->sale_price, '0', '', '.') . ' đ' }}</span>
                                            <span
                                                class="old">{{ number_format($product->old_price, '0', '', '.') . ' đ' }}</span>
                                        </div>
                                        <div class="action clearfix">
                                            <a href="{{ route('cart.add', $product->id) }}" title="Thêm giỏ hàng"
                                                class="add-cart fl-left">Thêm giỏ hàng</a>
                                            <a href="{{ route('cart.add', $product->id) }}" title="Mua ngay"
                                                class="buy-now fl-right">Mua ngay</a>
                                        </div>
                                    </li>


                                @endforeach
                            @else
                                <div class="alert alert-warning">Không tìm thấy dữ liệu</div>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
