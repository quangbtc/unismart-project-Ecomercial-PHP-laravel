@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách quyền tác vụ thành viên</h5>
                <div class="form-search form-inline">
                    <form action="{{ route('permission.list') }}" method='get'>
                        <input type="text" class="form-control form-search" name='keyword'
                            value='{{ request()->input('keyword') }}' placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích hoạt<span
                            class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Vô hiệu hoá<span
                            class="text-muted">({{ $count[1] }})</span></a>

                </div>
                <form action="{{route('permission.action')}}" method="post">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name='act'>
                            <option value=''>Chọn</option>
                            @foreach ( $list_act as $k=>$v )
                                <option value="{{$k}}">{{$v}}</option>
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
                                <th scope="col">Tên quyền</th>
                                {{-- <th scope="col">Username</th> --}}
                                <th scope="col">Tên hiển thị</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($permissions->total() > 0)
                                @php
                                    $t = 0;
                                @endphp
                                @foreach ($permissions as $permission)
                                    @php
                                        $t++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name='list_check[]' value='{{$permission->id}}' >
                                        </td>
                                        <th scope="row">{{ $t }}</th>
                                        <td>{{ $permission->name }}</td>
                                        {{-- <td>phancuong</td> --}}
            
                                        <td>{{$permission->display_name}}</td>
                                        <td>{{ $permission->created_at }}</td>
                                        <td>
                                            <a href="{{route('permission.edit',$permission->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                        
                                                <a href="{{route('permission.delete',$permission->id)}}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    onclick="return confirm('Bạn có muốn xoá quyền này?')"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                        
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan='7' class='bg-white'>Không tìm thấy bản ghi nào</td>
                                </tr>
                            @endif



                        </tbody>
                    </table>
                </form>

                {{ $permissions->links() }}
            </div>
        </div>
    </div>
@endsection
