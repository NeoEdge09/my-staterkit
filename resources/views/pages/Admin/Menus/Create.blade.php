<div class="modal fade" id="{{ $page_settings['action']['create']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['create']['id_modal'] }}" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-800">
                <h1 class="modal-title fs-5 text-white" id="createModal">
                    <i class="ti ti-plus me-2"></i>Create New Menu
                </h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body app-form">
                <form id="{{ $page_settings['action']['create']['id_form'] }}">
                    @csrf
                    <div class="row">
                        <!-- Basic Information Card -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="ti ti-file-description me-2 fs-5"></i>
                                    <h5 class="card-title mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Menu Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter menu name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="icon" class="form-label">Icon Code</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-code"></i>
                                                </span>
                                                <input type="text" class="form-control" id="icon" name="icon"
                                                    placeholder="e.g., ti ti-home">
                                            </div>
                                            <small class="text-muted">Use Tabler Icons code</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Settings Card -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="ti ti-route me-2 fs-5"></i>
                                    <h5 class="card-title mb-0">Navigation Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="route" class="form-label">Route</label>
                                            <select class="select2 form-select" id="route" name="route">
                                                <option value="">-- Select Route --</option>
                                                @foreach ($routes as $route)
                                                    <option value="{{ $route['value'] }}">{{ $route['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="select2 form-select" id="role" name="role">
                                                <option value="">-- Select role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="permission" class="form-label">Permission</label>
                                            <select class="select2 form-select" id="permission" name="permission">
                                                <option value="">-- Select permission --</option>
                                                @foreach ($permissions as $permission)
                                                    <option value="{{ $permission->name }}">{{ $permission->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="parent_id" class="form-label">Parent Menu</label>
                                            <select class="select2 form-select" id="parent_id" name="parent_id">
                                                <option value="">-- Select Parent --</option>
                                                @foreach ($menus as $menu)
                                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="order" class="form-label">Display Order</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-sort-ascending"></i>
                                                </span>
                                                <input type="number" class="form-control" id="order" name="order"
                                                    placeholder="Menu order" min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cancel
                </button>
                <div>
                    <button type="button" class="btn btn-secondary" onclick="reset()">
                        <i class="ti ti-refresh me-1"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit">
                        <i class="ti ti-device-floppy me-1"></i>Save Menu
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#submit').click(function() {
                $("#submit").prop('disabled', true);
                $("#submit").html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...');
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
                        if (xhr.status === 403) {
                            Toastify({
                                text: "You don't have permission to perform this action",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                style: {
                                    background: "rgb(var(--danger),1)",
                                },
                            }).showToast();
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').remove();

                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                input.after('<div class="invalid-feedback">' + value[
                                    0] + '</div>');
                            });
                        } else {
                            alert("Terjadi kesalahan, silakan coba lagi.");
                        }
                        resetSubmitButton();
                    }
                });
            });
            $(document).on('input change', '.is-invalid', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
        });

        function reset() {
            $('#{{ $page_settings['action']['create']['id_form'] }}')[0].reset();
            $('.select2').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function resetSubmitButton() {
            $("#submit").prop('disabled', false);
            $('#submit').html('Submit');

        }
    </script>
@endpush
