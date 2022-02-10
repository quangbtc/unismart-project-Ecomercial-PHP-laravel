@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="checkout-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="?page=home" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="" title="">Thanh toán</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <form action="{{ route('cart.restore') }}" method="POST">
            @csrf
            <div id="wrapper" class="wp-inner clearfix">
                <div class="section" id="customer-info-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin khách hàng</h1>
                    </div>
                    <div class="section-detail">

                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="fullname">Họ tên</label>
                                <input type="text" name="full_name" @if (session('user_login')) value="{{ $customer->full_name }}" @endif
                                    id="fullname">
                            </div>
                            <div class="form-col fl-right">
                                <label for="email">Email</label>
                                <input type="email" name="email" @if (session('user_login')) value="{{ $customer->email }}" @endif id="email">
                            </div>
                        </div>
                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="address" @if (session('user_login')) value="{{ $customer->address }}" @endif id="address">
                            </div>
                            <div class="form-col fl-right">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" name="phone" @if (session('user_login')) value="{{ $customer->phone }}" @endif id="phone">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <label for="notes">Ghi chú</label>
                                <textarea name="note"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="section" id="order-review-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin đơn hàng</h1>
                    </div>
                    <div class="section-detail">
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <td>Sản phẩm</td>
                                    <td>Tổng</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Cart::content() as $row)
                                    <tr class="cart-item">
                                        <td class="product-name">{{ $row->name }}<strong
                                                class="product-quantity text-danger">x {{ $row->qty }}</strong></td>
                                        <td class="product-total">{{ number_format($row->total, 0, '', '.') . ' đ' }}</td>
                                    </tr>
                                    <input type="hidden" name="product_id[]" value="{{ $row->id }}">
                                    <input type="hidden" name="sub_total[]"
                                        value="{{ number_format($row->total, 0, '', '') }}">
                                    <input type="hidden" name="qty[]" value="{{ $row->qty }}">
                                    <input type="hidden" name="price[]"
                                        value="{{ number_format($row->price, 0, '', '') }}">
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <td>Tổng đơn hàng:</td>
                                    <td><strong class="total-price">{{ Cart::total() }} đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div id="payment-checkout-wp">
                            <ul id="payment_methods">
                                <li>
                                    <input type="radio" id="direct-payment" name="payment" checked value="COD">
                                    <label for="direct-payment">Thanh toán tại nhà</label>
                                </li>
                                <li>
                                    <input type="radio" id="payment-home" name="payment" value="ATM">
                                    <label for="payment-home">Thanh toán online</label>
                                </li>
                            </ul>
                        </div>
                        <div class="place-order-wp clearfix">
                            <input type="submit" name="btn_order" id="order-now" value="Đặt hàng">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
