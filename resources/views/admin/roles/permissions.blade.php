@can('roles.permissions')
@extends('admin.layouts.app')

@section('title', 'Manage Permissions - ' . $role->name)

@section('content')
<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Role Permissions
                            <span class="badge badge-primary ml-2">{{ $role->name }}</span>
                        </h4>
                        <div class="card-header-action">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Roles
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST" id="permissions-form">
                            @csrf
                            @method('PUT')

                            {{-- Main Select / Unselect All --}}
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="main-select-all">
                                    <label class="custom-control-label font-weight-bold" for="main-select-all">
                                        Select / Unselect All Permissions
                                    </label>
                                </div>
                            </div>

                            @php
                                $grouped = [];
                                foreach ($permissions as $permission) {
                                    $cleanName = str_replace(['.', '_', '-'], ' ', $permission->name);
                                    $parts = explode(' ', $cleanName);
                                    $module = ucfirst($parts[0] ?? 'General');
                                    $grouped[$module][] = $permission;
                                }
                                ksort($grouped);
                            @endphp

                            @forelse($grouped as $module => $modulePermissions)
                                @php $moduleKey = strtolower(str_replace(' ', '-', $module)); @endphp

                                <div class="mb-4 permission-section" data-section="{{ $moduleKey }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="font-weight-bold text-uppercase text-muted mb-0">
                                            {{ $module }}
                                            <span class="badge badge-light">{{ count($modulePermissions) }}</span>
                                        </h6>

                                        {{-- Section Select All --}}
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input section-select-all"
                                                   id="section-{{ $moduleKey }}"
                                                   data-section="{{ $moduleKey }}">
                                            <label class="custom-control-label small" for="section-{{ $moduleKey }}">
                                                Select All
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        @foreach($modulePermissions as $permission)
                                            @php
                                                $displayName = ucwords(str_replace(['.', '_', '-'], ' ', $permission->name));
                                                $isChecked = in_array($permission->name, $rolePermissions ?? []);
                                            @endphp

                                            <div class="col-md-4 col-sm-6 mb-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                           class="custom-control-input permission-checkbox"
                                                           id="perm-{{ $permission->id }}"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}"
                                                           data-section="{{ $moduleKey }}"
                                                           {{ $isChecked ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm-{{ $permission->id }}">
                                                        {{ $displayName }}
                                                        <br>
                                                        <small class="text-muted">{{ $permission->name }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr>
                            @empty
                                <div class="text-center text-muted py-5">
                                    <p>No permissions found.</p>
                                </div>
                            @endforelse

                            <div class="text-right mt-4">
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Permissions
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {

    const mainSelectAll = document.getElementById('main-select-all');
    const sectionSelectAlls = document.querySelectorAll('.section-select-all');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

    /* =========================
       MAIN SELECT ALL
    ========================= */
    if (mainSelectAll) {
        mainSelectAll.addEventListener('change', function () {

            permissionCheckboxes.forEach(function (checkbox) {
                checkbox.checked = mainSelectAll.checked;
            });

            sectionSelectAlls.forEach(function (checkbox) {
                checkbox.checked = mainSelectAll.checked;
                checkbox.indeterminate = false;
            });
        });
    }

    /* =========================
       SECTION SELECT ALL
    ========================= */
    sectionSelectAlls.forEach(function (sectionCheckbox) {

        sectionCheckbox.addEventListener('change', function () {

            const section = this.getAttribute('data-section');

            const sectionPermissions = document.querySelectorAll(
                '.permission-checkbox[data-section="' + section + '"]'
            );

            sectionPermissions.forEach(function (checkbox) {
                checkbox.checked = sectionCheckbox.checked;
            });

            updateMainCheckbox();
        });
    });

    /* =========================
       INDIVIDUAL PERMISSION
    ========================= */
    permissionCheckboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            updateSectionCheckbox(this.getAttribute('data-section'));
            updateMainCheckbox();

        });

    });

    /* =========================
       UPDATE SECTION CHECKBOX
    ========================= */
    function updateSectionCheckbox(section) {

        const sectionPermissions = document.querySelectorAll(
            '.permission-checkbox[data-section="' + section + '"]'
        );

        const sectionCheckbox = document.querySelector(
            '.section-select-all[data-section="' + section + '"]'
        );

        if (!sectionCheckbox || sectionPermissions.length === 0) {
            return;
        }

        let checkedCount = 0;

        sectionPermissions.forEach(function (checkbox) {
            if (checkbox.checked) {
                checkedCount++;
            }
        });

        sectionCheckbox.checked =
            checkedCount === sectionPermissions.length;

        sectionCheckbox.indeterminate =
            checkedCount > 0 &&
            checkedCount < sectionPermissions.length;
    }

    /* =========================
       UPDATE MAIN CHECKBOX
    ========================= */
    function updateMainCheckbox() {

        if (!mainSelectAll || permissionCheckboxes.length === 0) {
            return;
        }

        let checkedCount = 0;

        permissionCheckboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                checkedCount++;
            }
        });

        mainSelectAll.checked =
            checkedCount === permissionCheckboxes.length;

        mainSelectAll.indeterminate =
            checkedCount > 0 &&
            checkedCount < permissionCheckboxes.length;
    }

    /* =========================
       INITIAL STATE
    ========================= */
    sectionSelectAlls.forEach(function (checkbox) {
        updateSectionCheckbox(
            checkbox.getAttribute('data-section')
        );
    });

    updateMainCheckbox();

});
</script>
@else
    @php
        abort(404);
    @endphp
@endcan
