@extends('layouts.app')
@push('title')
    <title>USER UPDATE</title>
@endpush
@section('index')
    USER
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            USER CREATE
        </div>
        <div class="card-body">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input name="name" value="{{ $user->name }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input name="email" value="{{ $user->email }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <label for="password">
                            Password</label>
                        <div class="input-group">
                            <span class="input-group-text" style="cursor: pointer;"><iconify-icon icon="fa:eye-slash"
                                    id="togglePassword" style="color: black;" width="20"
                                    height="20"></iconify-icon></span>
                            <input type="password" name="password" value="{{ old('password') }}" class="form-control"
                                id="password">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <label for="confirmpassword">
                            Confirm Password</label>
                        <div class="input-group">
                            <input type="password" name="confirm_password" class="form-control" id="confirmpassword">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            @php
                                $item = json_decode($user->role_ids);
                            @endphp
                            <label for="">Role`s</label>
                            <select name="role[]" class="form-select" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $item && in_array($role->id, $item) ? 'selected' : '' }}>
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" class="form-control myfile" name="profile">
                        </div>
                    </div>
                    <div class="col-sm-1 mt-4" style="align-item: left;">

                        <img src="{{ asset('assets/images/man.png') }}" id="blah" style="width: 60px; height: 60px;"
                            class="Front_img" />
                    </div>

                </div>
                <br>
                <div class="row px-3">

                    <div class="mr-auto mt-2"><a href="{{ route('user.index') }}">Go Back</a></div>

                    <div><button type="submit" class="btn btn-info">save</button></div>

                </div>
            </form>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        var defaultImagePath = "{{ asset('assets/images/man.png') }}";
    </script>
    <script src="{{ asset('assets/js/custom/users/user/index.js') }}"></script>
@endpush
