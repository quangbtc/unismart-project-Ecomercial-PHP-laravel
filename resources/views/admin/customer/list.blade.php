@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách khách hàng</h5>
                <div class="form-search form-inline">
                    <form action="{{ route('customer.list') }}" method='get'>
                        <input type="text" class="form-control form-search" name='keyword'
                            value='{{ request()->input('keyword') }}' placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $count[1] }})</span></a>

                </div>
                <form action="{{ route('customer.action') }}" method="post">
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
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Địa chỉ</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($customers->total() > 0)
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($customers as $customer)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name='list_check[]' value='{{ $customer->id }}'>
                                        </td>
                                        <th scope="row">{{ $t }}</th>
                                        <td>{{ $customer->full_name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->created_at }}</td>
                                        <td>
                                            <a href="{{route('customer.edit',$customer->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>

                                            <a href="{{route('customer.delete',$customer->id)}}" class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                onclick="return confirm('Bạn có muốn xoá khách hàng này?')"
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

                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
