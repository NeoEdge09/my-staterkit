<div class="modal fade" id="{{ $page_settings['action']['create']['id_modal'] }}"
    data-modal-id="{{ $page_settings['action']['create']['id_modal'] }}" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-800">
                <h1 class="modal-title fs-5 text-white" id="createModal">Tambah {{ $page_settings['title'] }}</h1>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body app-form">
                <form id="{{ $page_settings['action']['create']['id_form'] }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="module" class="form-label">Module Name</label>
                                <input type="text" class="form-control" id="module" name="module" required>
                                <small class="form-text text-muted">Example: users, roles, settings</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="guard_name" class="form-label">Guard Name</label>
                                <select class="form-select" name="guard_name" required>
                                    <option value="web">web</option>
                                    <option value="api">api</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Permission Types</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="all_permissions"
                                            name="permission_type" value="all" checked>
                                        <label class="form-check-label" for="all_permissions">All Permissions</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="custom_permissions"
                                            name="permission_type" value="custom">
                                        <label class="form-check-label" for="custom_permissions">Custom
                                            Selection</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="permission_checkboxes" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">Select Permissions</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="view">
                                        <label class="form-check-label">View</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="create">
                                        <label class="form-check-label">Create</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="edit">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="delete">
                                        <label class="form-check-label">Delete</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-light-light" data-bs-dismiss="modal">Close</button>
                <div>
                    <button type="button" class="btn btn-light-secondary" onclick="reset()">Reset</button>
                    <button type="submit" class="btn btn-light-primary" id="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('input[name="permission_type"]').change(function() {
                if ($(this).val() === 'custom') {
                    $('#permission_checkboxes').show();
                } else {
                    $('#permission_checkboxes').hide();
                }
            });

            // Form submission handler
            $('#submit').click(function(e) {
                e.preventDefault();
                $("#submit").prop('disabled', true);
                $("#submit").html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...');

                const module = $('#module').val();
                const guardName = $('select[name="guard_name"]').val();
                const permissionType = $('input[name="permission_type"]:checked').val();

                let permissions = [];
                if (permissionType === 'all') {
                    permissions = ['view', 'create', 'edit', 'delete'];
                } else {
                    permissions = $('input[name="permissions[]"]:checked').map(function() {
                        return $(this).val();
                    }).get();
                }

                $.ajax({
                    url: "{{ $page_settings['action']['create']['route'] }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        module: module,
                        guard_name: guardName,
                        permissions: permissions
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
