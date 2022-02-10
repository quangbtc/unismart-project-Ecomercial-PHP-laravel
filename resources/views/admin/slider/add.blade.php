@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm slider
            </div>
            <div class="card-body">
                <form action='{{route('slider.store')}}' method='POST' enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên loại slider</label>
                        <input class="form-control" type="text" name="name" id="name">
                        @error('name')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>  
                      <div class="form-group">
                        <label for="image_name">Tên hình slider</label>
                        <input class="form-control" type="text" name="image_name" value="" id="image_name">
                        @error('image_name')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>  
                      <div class="form-group">
                        <label for="image">Hình slider</label>
                        <input class="form-control-file" type="file" name="file" id="image">
                     
                        @error('file')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>                                            
                    <button type="submit" name='btn_add' class="btn btn-primary" value='thêm mới'>Thêm slider</button>
                </form>
            </div>
        </div>
    </div>
@endsection
