@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật quyền thành viên
        </div>
        <div class="card-body">
            <form action="{{route('role.update',$role->id)}}" method='post'>
                @csrf
                <div class="form-group">
                    <label for="name">Quyền thành viên</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{$role->name}}">
                    @error('name')
                        <small class='text-danger'>{{$message}}</small>
                    @enderror
                </div>
                 @foreach($permissions as $permission)
                         <div class="form-check">
                            <input class="form-check-input" {{$list_permission->contains($permission->id)?'checked':''}} type="checkbox" value="{{$permission->id}}" id="{{$permission->display_name}}" name='permission[]'>
                        <label class="form-check-label" for="{{$permission->display_name}}">
                            {{$permission->display_name}}
                        </label>
                         </div>
                        @endforeach

                <button type="submit" name='btn_update' class="btn btn-primary" value='thêm mới'>Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection