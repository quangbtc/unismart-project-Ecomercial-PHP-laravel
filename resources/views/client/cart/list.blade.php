@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="cart-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="?page=home" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="" title="">Đơn hàng</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="wrapper" class="wp-inner clearfix">
            <div class="section" id="info-cart-wp">
                 @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
                <div class="section-detail table-responsive">
                    @if ($order->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>Mã đơn hàng</td>
                                    <td>Tên khách hàng</td>
                                    <td>Tổng tiền</td>
                                    <td>Thanh toán</td>
                                    <td>Trạng thái</td>
                                    <td>Ngày đặt</td>
                                    <td>Ghi chú</td>
                                    <td>Chi tiết</td>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{$order->code}}</td>
                                    <td>
                                        {{ $order->customer->full_name }}
                                    </td>
                                    <td>
                                        {{ number_format($order->total, 0, '', '.') . ' đ' }}
                                    </td>
                                    <td>{{ $order->payment }}</td>
                                    <td>
                                        {{ $order->status }}
                                    </td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->note }}</td>
                                    <td>
                                        <a href="{{route('cart.detail',$order->id)}}" title="" class="detail"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                </div>
            </div>
        </div>
    </div>
@endsection
