@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                        <h5>Danh mục sản phẩm</h5>
                        <div class="form-search form-inline">
                            <form action="{{ route('product_cat.list') }}" method='get'>
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
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'non_active']) }}" class="text-primary">Vô
                                hiệu hoá<span class="text-muted">({{ $count[1] }})</span></a>

                        </div>
                        <form action="{{route('product_cat.action')}}" method="post">
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
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="checkall" id="check_all">
                                        </th>
                                        <th scope="col">#</th>
                                        <th scope="col">Tên danh mục</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Tác vụ</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $t = 0;
                                    @endphp
                                    @foreach ($list_cat as $cat)
                                        @php
                                            $t++;
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" name='list_check[]' value='{{ $cat->id }}'>
                                            </td>
                                            <th scope="row">{{ $t }}</th>
                                            <td>{{ $cat->cat_title }}</td>
                                            <td>
                                                @php
                                                    echo str_repeat('===', $cat['level']);
                                                @endphp {{ $cat->slug }}</td>
                                            <td>{{ $cat->status }}</td>
                                            <td>{{ $cat->created_at }}</td>
                                            <td>


                                              
                                                    <a href="{{ route('product_cat.edit', $cat->id) }}"
                                                        class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                            class="fa fa-edit"></i></a>
                                                    <a href="{{ route('product_cat.delete', $cat->id) }}"
                                                        class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                        onclick="return confirm('Bạn có muốn xoá danh mục này này? Nếu bạn chọn xoá thì sẽ xoá tất cả các danh mục con tồn tại')"
                                                        data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                            class="fa fa-trash"></i></a>


                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </form>

                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
