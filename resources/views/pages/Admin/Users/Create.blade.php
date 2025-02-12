<div class="modal fade" id="{{ $page_settings['action']['create']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['create']['id_modal'] }}" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-800">
                <h1 class="modal-title fs-5 text-white" id="createModal">Create New User</h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body app-form">
                <form id="{{ $page_settings['action']['create']['id_form'] }}">
                    @csrf
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">User Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label">Confirm
                                                Password</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">Roles</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach ($roles as $role)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="roles[]"
                                                        id="role_{{ $role->id }}" value="{{ $role->name }}">
                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                        {{ ucfirst($role->name) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Direct Permissions -->
                        <div class="col-12">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Direct Permissions</h5>
                                    <div class="form-check mb-0">
                                        <input type="checkbox" class="form-check-input" id="checkPermissionAll"
                                            value="1">
                                        <label class="form-check-label" for="checkPermissionAll">Select All</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @php $i = 1; @endphp
                                    @foreach ($permission_groups as $group)
                                        <div class="permission-group mb-4">
                                            @php
                                                $permissions = App\Traits\User::getpermissionsByGroupName($group->name);
                                                $j = 1;
                                            @endphp
                                            <div class="permission-group-header mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="{{ $i }}Management" value="{{ $group->name }}"
                                                        onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)">
                                                    <label class="form-check-label fw-bold"
                                                        for="{{ $i }}Management">
                                                        {{ ucfirst($group->name) }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div
                                                class="permission-group-body role-{{ $i }}-management-checkbox">
                                                <div class="row g-3">
                                                    @foreach ($permissions as $permission)
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input permission-checkbox"
                                                                    name="permissions[]"
                                                                    id="checkPermission{{ $permission->id }}"
                                                                    value="{{ $permission->name }}">
                                                                <label class="form-check-label"
                                                                    for="checkPermission{{ $permission->id }}">
                                                                    {{ str_replace($group->name . ' ', '', $permission->name) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @php $i++; @endphp
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <div>
                    <button type="button" class="btn btn-secondary" onclick="reset()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="submit">
                        <i class="fa-solid fa-save me-1"></i> Save User
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Form submission
            $('#submit').click(function(e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                $(this).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...');

                let formData = $('#{{ $page_settings['action']['create']['id_form'] }}').serialize();

                $.ajax({
                    url: "{{ $page_settings['action']['create']['route'] }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#{{ $page_settings['action']['create']['id_modal'] }}').modal(
                            'hide');
                        $('#{{ $page_settings['action']['create']['id_form'] }}')[0].reset();
                        $('#{{ $table_settings['id'] }}').DataTable().ajax.reload();

                        Toastify({
                            text: response.message,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            style: {
                                background: "rgb(var(--success),1)",
                            },
                        }).showToast();

                        resetSubmitButton();
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                        resetSubmitButton();
                    }
                });
            });

            // Permission handling
            $("#checkPermissionAll").click(function() {
                $('.permission-checkbox').prop('checked', $(this).is(':checked'));
                $('.permission-group input[type="checkbox"]').prop('checked', $(this).is(':checked'));
            });

            $('.permission-group-header input[type="checkbox"]').click(function() {
                const groupClass = $(this).closest('.permission-group')
                    .find('.permission-group-body')
                    .attr('class')
                    .split(' ')[1];

                $('.' + groupClass + ' input[type="checkbox"]').prop('checked', $(this).is(':checked'));
                updateSelectAllState();
            });

            $('.permission-checkbox').click(function() {
                const groupHeader = $(this).closest('.permission-group-body')
                    .siblings('.permission-group-header')
                    .find('input[type="checkbox"]');

                const totalPermissions = $(this).closest('.permission-group-body')
                    .find('.permission-checkbox').length;
                const checkedPermissions = $(this).closest('.permission-group-body')
                    .find('.permission-checkbox:checked').length;

                groupHeader.prop('checked', totalPermissions === checkedPermissions);
                updateSelectAllState();
            });
        });

        function updateSelectAllState() {
            const totalPermissions = $('.permission-checkbox').length;
            const checkedPermissions = $('.permission-checkbox:checked').length;
            $("#checkPermissionAll").prop('checked', totalPermissions === checkedPermissions);
        }

        function reset() {
            $('#{{ $page_settings['action']['create']['id_form'] }}')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function resetSubmitButton() {
            $("#submit").prop('disabled', false);
            $("#submit").html('<i class="fa-solid fa-save me-1"></i> Save User');
        }

        function handleAjaxError(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.each(xhr.responseJSON.errors, function(key, value) {
                    let input = $('[name="' + key + '"]');
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                });
            } else {
                Toastify({
                    text: "An error occurred. Please try again.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    style: {
                        background: "rgb(var(--danger),1)",
                    },
                }).showToast();
            }
        }
    </script>
@endpush
