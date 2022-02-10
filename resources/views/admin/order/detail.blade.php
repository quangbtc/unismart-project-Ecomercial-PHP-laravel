@extends('layouts.admin')
@section('content')
  <div id="main-content-wp" class="list-product-page">
    <div class="wrap clearfix">
        <div id="content" class="detail-exhibition fl-right">
            <div class="section" id="info">
                <div class="order-section-head">
                    <h3 class="section-title">Thông tin đơn hàng</h3>
                </div>
               <div class="wp-info">
                    <ul class="list-order">
                    <li>
                        <h3 class="title">Mã đơn hàng</h3>
                        <span class="detail">{{$order->code}}</span>
                    </li>
                    <li>
                        <h3 class="title">Địa chỉ nhận hàng</h3>
                        @if ($order)
                            <span class="detail">{{$order->customer->address}}</span>
                        @endif
                    </li>
                    <li>
                        <h3 class="title">Thông tin vận chuyển</h3>
                       @if ($order)
                            <span class="detail">{{$order->payment}}</span>
                       @endif
                    </li>
                </ul>
                 <ul class="list-info">
                    <li>
                        <h3 class="title">Tên khách hàng</h3>
                       @if($order)
                        <span class="detail">{{$order->customer->full_name}}</span>
                       @endif
                    </li>
                    <li>
                        <h3 class="title">Số điện thoại</h3>
                       @if($order)
                            <span class="detail">{{$order->customer->phone}}</span>
                       @endif
                    </li>
                    <li>
                        <h3 class="title">Ngày đặt hàng</h3>
                       @if($order)
                        <span class="detail">{{$order->created_at}}</span>
                       @endif
                    </li>
                </ul>
               </div>
            </div>
            <div class="section">
                <div class="order-section-head">
                    <h3 class="section-title">Sản phẩm đơn hàng</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped info-exhibition">
                        <thead>
                            <tr>
                                <td class="thead-text">STT</td>
                                <td class="thead-text">Ảnh sản phẩm</td>
                                <td class="thead-text">Tên sản phẩm</td>
                                <td class="thead-text">Đơn giá</td>
                                <td class="thead-text">Số lượng</td>
                                <td class="thead-text">Thành tiền</td>
                                <td class="thead-text">Ngày đặt</td>
                            </tr>
                        </thead>
                        <tbody>
                           @if($order->count())
                            @foreach ($order->products as $product)
                                 <tr>
                                <td class="thead-text">{{($loop->iteration)}}</td>
                                <td class="thead-text">
                                    <div class="thumb">
                                        <img src="{{url("$product->thumb")}}" alt="">
                                    </div>
                                </td>
                                <td class="thead-text">{{$product->title}}</td>
                                <td class="thead-text">{{number_format($product->pivot->price,0,'','.').' đ'}}</td>
                                <td class="thead-text">{{$product->pivot->qty}}</td>
                                <td class="thead-text">{{number_format($product->pivot->sub_total,'0','','.').' đ'}}</td>
                                <td class="thead-text">{{$product->pivot->created_at}}</td>
                            </tr>
                            @endforeach
                           @endif
                           
                          
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="section">
                <h3 class="section-title">Giá trị đơn hàng</h3>
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <span class="total-fee">Tổng số lượng</span>
                            <span class="total">Tổng đơn hàng</span>
                        </li>
                        <li>
                            <span class="total-fee">{{$order->products->count()}}sản phẩm</span>
                            <span class="total">{{number_format($order->total,0,'','.').' đ'}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection