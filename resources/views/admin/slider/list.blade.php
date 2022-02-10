@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách slider</h5>
                <div class="form-search form-inline">
                    <form action="" method='get'>
                        <input type="text" class="form-control form-search" name='keyword'
                            value='{{ request()->input('keyword') }}' placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="" class="text-primary">Tác vụ<span </span></a>
                </div>
                <form action="{{ route('slider.action') }}" method="post">
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
                                <th scope="col">Tên slider</th>
                                {{-- <th scope="col">Username</th> --}}
                                <th scope="col">Tên hình slider</th>
                                <th scope="col">Hình slider</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($sliders->total() > 0)
                                @foreach ($sliders as $slider)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name='list_check[]' value='{{ $slider->id }}'>
                                        </td>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $slider->name }}</td>
                                        {{-- <td>phancuong</td> --}}
                                        <td>{{ $slider->image_name }}</td>
                                        <td><img width="auto" height="80px" src="{{ url("$slider->image") }}" alt=""></td>
                                        <td>
                                            <a href="" class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>

                                            <a href="{{ route('slider.delete', $slider->id) }}"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                onclick="return confirm('Bạn có muốn xoá slider này?')"
                                                data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan='7' class='bg-white'>Không có slider nào</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $sliders->links() }}
            </div>
        </div>
    </div>
@endsection
