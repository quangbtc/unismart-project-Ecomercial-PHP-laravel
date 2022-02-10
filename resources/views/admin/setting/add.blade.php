@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm cài đặt
            </div>
            <div class="card-body">
                <form action='{{ route('setting.store') }}' method='POST' enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên cài đặt</label>
                        <input class="form-control" type="text" name="config" id="name">
                        @error('config')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="value">Giá trị cài đặt</label>
                        <input class="form-control" type="text" name="value" value="" id="value">
                        @error('value')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" name='btn_add' class="btn btn-primary" value='thêm mới'>Thêm cài đặt</button>
                </form>
            </div>
        </div>
    </div>
@endsection
