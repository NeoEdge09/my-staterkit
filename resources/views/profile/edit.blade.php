@extends('layout.master')
@section('title', 'Profile Settings')

@section('main-content')
    <div class="container-fluid">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="main-title">Profile Settings</h4>
                    <p class="text-secondary">Manage your account settings and security preferences</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Profile Information Card -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border shadow-sm">
                    <div class="card-header bg-light d-flex align-items-center">
                        <i class="ti ti-user me-2 fs-5"></i>
                        <h5 class="card-title mb-0">Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" class="app-form">
                            @csrf
                            @method('patch')

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div class="col-12">
                                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                                            <i class="ti ti-alert-circle me-2"></i>
                                            <div>
                                                Your email address is unverified.
                                                <form id="send-verification" method="post"
                                                    action="{{ route('verification.send') }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                                                        Click here to re-send verification email
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border shadow-sm">
                    <div class="card-header bg-light d-flex align-items-center">
                        <i class="ti ti-lock me-2 fs-5"></i>
                        <h5 class="card-title mb-0">Update Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}" class="app-form">
                            @csrf
                            @method('put')

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="col-12 mb-4">
                <div class="card border shadow-sm border-danger">
                    <div class="card-header bg-danger-subtle d-flex align-items-center">
                        <i class="ti ti-trash me-2 fs-5"></i>
                        <h5 class="card-title mb-0">Delete Account</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Before deleting your account, please download any data or information that you wish to retain.
                        </p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteAccountModal">
                            <i class="ti ti-trash me-1"></i>Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">
                        <i class="ti ti-alert-triangle me-2"></i>Delete Account
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                        <div class="mb-3">
                            <label for="delete_password" class="form-label">Please enter your password to confirm</label>
                            <input type="password" class="form-control" id="delete_password" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash me-1"></i>Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Show success message if status exists
            @if (session('status'))
                Toastify({
                    text: "{{ session('status') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    style: {
                        background: "rgb(var(--success),1)",
                    },
                }).showToast();
            @endif
        });
    </script>
@endpush
