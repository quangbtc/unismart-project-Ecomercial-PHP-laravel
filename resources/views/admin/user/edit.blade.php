@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật thông tin người dùng
        </div>
        <div class="card-body">
            <form action="{{route('user.update',$user->id)}}" method='post'>
                @csrf
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{$user->name}}">
                    @error('name')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{$user->email}}" disabled >
                     @error('email')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input class="form-control" type="password" name="password" id="password">
                     @error('password')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                 <div class="form-group">
                    <label for="password-confirm">Xác nhận mật khẩu</label>
                    <input class="form-control" type="password" name="password_confirmation" id="password-confirm">
                     @error('password_confirmation')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Nhóm quyền</label>
                    <select class="form-control" id="" name="roles[]" multiple="multiple">          
                        @foreach ($roles as $role )
                             <option {{$list_role_user->contains($role->id)?'selected':''}} value="{{$role->id}} ">{{$role->name}}</option>
                        @endforeach
                    
                    </select>
                </div>

                <button type="submit" name='btn_update' class="btn btn-primary" value='thêm mới'>Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection