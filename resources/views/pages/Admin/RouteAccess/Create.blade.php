<div class="modal fade" id="{{ $page_settings['action']['create']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['create']['id_modal'] }}" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-800">
                <h1 class="modal-title fs-5 text-white" id="createModal">
                    <i class="ti ti-plus me-2"></i>Create New Route Access
                </h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body app-form">
                <form id="{{ $page_settings['action']['create']['id_form'] }}">
                    @csrf
                    <div class="row">
                        <!-- Route Information Card -->
                        <div class="col-12 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="ti ti-route me-2 fs-5"></i>
                                    <h5 class="card-title mb-0">Route Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="route_name" class="form-label">Route Name</label>
                                            <select class="select2 form-select" id="route_name" name="route_name">
                                                <option value="">-- Select Route --</option>
                                                @foreach ($routes as $route)
                                                    <option value="{{ $route['value'] }}">{{ $route['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Access Control Card -->
                        <div class="col-12">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="ti ti-shield-lock me-2 fs-5"></i>
                                    <h5 class="card-title mb-0">Access Control</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="select2 form-select" id="role" name="role">
                                                <option value="">-- Select Role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="permission" class="form-label">Permission</label>
                                            <select class="select2 form-select" id="permission" name="permission">
                                                <option value="">-- Select Permission --</option>
                                                @foreach ($permissions as $permission)
                                                    <option value="{{ $permission->id }}">{{ $permission->name }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                    <button type="button" class="btn btn-primary" id="submit">
                        <i class="ti ti-device-floppy me-1"></i>Save Route Access
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                dropdownParent: $('#{{ $page_settings['action']['create']['id_modal'] }}'),
                width: '100%'
            });

            $('#submit').click(function() {
                $("#submit").prop('disabled', true);
                $("#submit").html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...');

                let formData = {
                    route_name: $('#route_name').val(),
                    role: $('#role').val(),
                    permission: $('#permission').val(),
                    _token: $('input[name="_token"]').val()
                };

                $.ajax({
                    url: "{{ $page_settings['action']['create']['route'] }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $('#{{ $page_settings['action']['create']['id_modal'] }}').modal(
                            'hide');
                        $('#{{ $page_settings['action']['create']['id_form'] }}')[0].reset();
                        $('.select2').val('').trigger('change');
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
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').remove();

                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                input.after('<div class="invalid-feedback">' + value[
                                    0] + '</div>');
                            });
                        } else if (xhr.status === 403) {
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
                        } else {
                            alert("An error occurred, please try again.");
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
            $("#submit").html('<i class="ti ti-device-floppy me-1"></i>Save Route Access');
        }
    </script>
@endpush
