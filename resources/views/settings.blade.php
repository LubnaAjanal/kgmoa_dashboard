@extends('layouts.main')

<style>
    @media (max-width: 768px) {
        .profile-card {
            margin-top: 30px !important;
        }
    }

    .profile-card {
        width: auto;
        height: auto;
        background-color: #ffffff;
        box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.15);
    }

    .profile_image img {
        width: 100px;
        height: 100px;
        max-width: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .profile-image img {
            width: 80px;
            height: 80px;
        }
    }

    .user_details h3 {
        margin-top: 10px;
    }
</style>

@section('content')
    <div class="container card">
        <h3 class="text-center mt-3">Update Profile</h3>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mt-3" id="profileTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" role="tab"
                    aria-controls="personal" aria-selected="true">
                    Personal Information
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" role="tab"
                    aria-controls="security" aria-selected="false">
                    Security
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Personal Information Tab -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <div class="profile-card">
                    <div class="profile_image text-center">
                        <img src="{{ asset('images/full-profile.jpg') }}" alt="profile" />
                    </div>
                    <div class="user_details text-center">
                        <h3> {{ session('name') ?? 'Guest' }} </h3> 
                        <h6>{{ session('email') ?? '@guestmail' }}</h6>
                        <h6>{{ session('role') ?? 'Guest' }}</h6> 

                        <a href="#" class="text-decoration-none edit-profile">
                            <button type="button" class="btn btn-outline-primary my-3">Edit Profile</button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                <form id="forgotPassword">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="old_password"><span class="text-danger">*</span> Old Password</label>
                        <input type="password" class="form-control" id="old_password" name="old_password"
                            placeholder="Enter your old password">
                    </div>
                    <div class="form-group my-4">
                        <label for="new_password"><span class="text-danger">*</span> New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password"
                            placeholder="Enter your new password">
                    </div>
                    <div class="form-group my-4">
                        <label for="confirm_password"><span class="text-danger">*</span> Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            placeholder="Confirm your new password">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
