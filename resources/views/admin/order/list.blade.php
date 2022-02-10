@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách đơn hàng</h5>
                <div class="form-search form-inline">
                    <form action="{{ route('order.list') }}" method='get'>
                        <input type="text" class="form-control form-search" name='keyword'
                            value='{{ request()->input('keyword') }}' placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'success']) }}" class="text-primary">Đã giao hàng
                    
                    <span class="text-muted">({{ $count[0] }})</span></a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'delivering']) }}" class="text-primary">Đang giao
                        hàng
                        <span class="text-muted">({{ $count[2] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'processing']) }}" class="text-primary">Chờ thanh
                        toán
                        <span class="text-muted">({{ $count[1] }})</span></a>

                </div>
                <form action="{{ route('order.action') }}" method="post">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name='act'>
                            <option value=''>Chọn</option>
                            @foreach ($list_act as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach

                        </select>
                        <input type="submit" name="btn-apply" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead class="thead-dark">
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Mã đơn hàng</th>
                                <th scope="col">Tên khách hàng</th>
                                <th scope="col">Tổng tiền</th>
                                <th scope="col">Thanh toán</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày đặt hàng</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->count() > 0)
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($orders as $order)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name='list_check[]' value='{{ $order->id }}'>
                                        </td>
                                        <th scope="row">{{ $t }}</th>
                                        <td>{{$order->code}}</td>
                                        <td>{{ $order->customer->full_name }}</td>
                                        <td>{{number_format($order->total,0,'','.').' đ' }}</td>
                                        <td>{{ $order->payment}}</td>
                                        <td class="badge {{$order->status=='Thành công'?'badge-success':''}}
                                            {{$order->status=='Chờ xử lý'?'badge-warning':''}}
                                            {{$order->status=='Đang giao hàng'?'badge-primary':''}}
                                        ">{{ $order->status }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>
                                            <a href="{{route('order.detail',$order->id)}}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Detail"><i
                                                   class="fa fa-eye"></i></a>

                                            <a href="{{route('order.delete',$order->id)}}"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                onclick="return confirm('Bạn có muốn xoá đơn hàng này?')"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan='7' class='bg-white'>Không tìm thấy khách hàng nào </td>
                                </tr>
                            @endif



                        </tbody>
                    </table>
                </form>

                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
