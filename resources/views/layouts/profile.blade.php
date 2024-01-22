@extends('layouts.app')
@section('page')
    Profile
@endsection
@section('content')
    <style>
        .img_input {
            position: relative;
            overflow: hidden;
        }

        input[name=profile] {
            display: none;
        }
    </style>
    <section class="section profile d-flex justify-content-center">
        <div class="col-xl-8">

            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">

                        <li class="nav-item">
                            <button class="nav-link active" data-toggle="tab" data-target="#profile-edit">Edit
                                Profile</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-toggle="tab" data-target="#profile-change-password">Change
                                Password</button>
                        </li>

                    </ul>
                    <div class="tab-content pt-2">

                        <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">

                            <!-- Profile Edit Form -->
                            <form action="{{ route('user.profile.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                        Image</label>
                                    <div class="col-md-8 col-lg-9">
                                        @if ($user->profile != null)
                                            <img src="{{ asset('/profile/') }}/{{ $user->profile }}" alt="Profile"
                                                class="rounded-circle Front_img" width="90" name="profile" />
                                        @else
                                            <img src="{{ asset('assets/images/man.png') }}" alt="Profile"
                                                class="rounded-circle Front_img" width="90" />
                                        @endif
                                        <div>
                                            <label for="profile" style="cursor: pointer;">
                                                <div class="img_input btn btn-sm" title="Upload new profile image">
                                                    <iconify-icon icon="mdi:upload" style="color: black;" width="30"
                                                        height="20"></iconify-icon><input type="file" name="profile"
                                                        id="profile" class="myfile" @if ($user->profile != null) is_file="1" @endif/></div>
                                            </label>
                                            <a type="button" class="btn btn-sm deleteButton"
                                                title="Remove my profile image"><iconify-icon
                                                    icon="fluent:delete-dismiss-24-filled" style="color: red;"
                                                    width="30" height="20"></iconify-icon></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Username</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="name" type="text" class="form-control" id="username"
                                            value="{{ $user->name }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="Email"
                                            value="{{ $user->email }}">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-info submit">Save Changes</button>
                                </div>
                            </form><!-- End Profile Edit Form -->

                        </div>

                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <!-- Change Password Form -->
                            <form action="{{ route('profile.password.update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">

                                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current
                                        Password</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-text" style="cursor: pointer;"><iconify-icon
                                                    icon="fa:eye-slash" id="togglePassword" style="color: black;"
                                                    width="20" height="20"></iconify-icon></span>
                                            <input type="password" name="currentpassword" class="form-control"
                                                id="currentPassword" value="{{ old('currentpassword') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                                        Password</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-text" style="cursor: pointer;"><iconify-icon
                                                    icon="fa:eye-slash" id="togglePassword1" style="color: black;"
                                                    width="20" height="20"></iconify-icon></span>
                                            <input type="password" name="newpassword" value="{{ old('newpassword') }}"
                                                class="form-control" id="newPassword">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="confirmpassword" class="col-md-4 col-lg-3 col-form-label">Confirm
                                        New
                                        Password</label>
                                    <div class="col-md-8">
                                        <input name="confirmpassword" value="{{ old('confirmpassword') }}"
                                            type="password" class="form-control" id="confirmpassword">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-info">Change Password</button>
                                </div>
                            </form><!-- End Change Password Form -->

                        </div>

                    </div><!-- End Bordered Tabs -->

                </div>
            </div>

        </div>
    </section>
@endsection
@push('custom-scripts')
    <script>
        var defaultImagePath = "{{ asset('assets/images/man.png') }}";
    </script>
    <script src="{{ asset('assets/js/custom/layouts/profile/index.js') }}"></script>
@endpush
