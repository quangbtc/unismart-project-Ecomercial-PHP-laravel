@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Sửa cài đặt
            </div>
            <div class="card-body">
                <form action='{{ route('setting.update',$setting->id) }}' method='POST' enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên cài đặt</label>
                        <input class="form-control" type="text" name="config" value="{{$setting->config}}" id="name">
                        @error('config')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="value">Giá trị cài đặt</label>
                        <input class="form-control" type="text" name="value" value="{{$setting->value}}" id="value">
                        @error('value')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" name='btn_edit' class="btn btn-primary" value='thêm mới'>Sửa cài đặt</button>
                </form>
            </div>
        </div>
    </div>
@endsection
