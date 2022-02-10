@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật thông tin khách hàng
        </div>
        <div class="card-body">
            <form action="{{route('customer.update',$customer->id)}}" method='post'>
                @csrf
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input class="form-control" type="text" name="full_name" id="name" value="{{$customer->full_name}}">
                    @error('name')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{$customer->email}}" >
                     @error('email')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input class="form-control" type="text" name="phone" value="{{$customer->phone}}" id="phone">
                     @error('phone')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                 <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <input class="form-control" type="text" value="{{$customer->address}}" name="address" id="address">
                     @error('address')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                     <div class="form-group">
                    <label for="note">Ghi chú</label>
                    <textarea class="form-control" value="" id="note" name="note">
                        {{$customer->note}}
                    </textarea>
                     @error('address')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>

                <button type="submit" name='btn_update' class="btn btn-primary" value='thêm mới'>Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection