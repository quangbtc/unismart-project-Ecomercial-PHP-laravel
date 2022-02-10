@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm quyền người dùng
            </div>
            <div class="card-body">
                <form action='{{ route('role.store') }}' method='POST'>
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên quyền</label>
                        <input class="form-control" type="text" name="name" id="name">
                        @error('name')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                   
                        @foreach($permissions as $permission)
                         <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{$permission->id}}" id="flexCheckChecked" name='permission[]'>
                        <label class="form-check-label" for="flexCheckChecked">
                            {{$permission->display_name}}
                        </label>
                         </div>
                        @endforeach
                        
                   
                    <button type="submit" name='btn_add' class="btn btn-primary" value='thêm mới'>Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
