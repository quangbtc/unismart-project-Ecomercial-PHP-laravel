@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Sửa quyền tác vụ
        </div>
        <div class="card-body">
            <form action='{{route('permission.update',$permission->id)}}' method='POST'>
                @csrf
                <div class="form-group">
                    <label for="name">Tên quyền tác vụ</label>
                    <input class="form-control" type="text" name="name" value="{{$permission->name}}" id="name">
                    @error('name')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                 <div class="form-group">
                    <label for="name">Tên hiển thị</label>
                    <input class="form-control" type="text" value="{{$permission->display_name}}" name="display_name" id="display_name">
                    @error('display_name')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                <button type="submit" name='btn_add' class="btn btn-primary" value='thêm mới'>Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection