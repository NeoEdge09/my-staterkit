<div class="app-dropdown">
    <button class="btn btn-lg btn-light-primary border-0 icon-btn" type="button" data-bs-toggle="dropdown"
        data-bs-auto-close="true" aria-expanded="false">
        <i class="ti ti-dots"></i>
    </button>
    <ul class="dropdown-menu">
        @foreach ($actions as $action)
            <li>
                <a href="{{ $action['route'] ?? 'javascript:void(0)' }}" class="dropdown-item {{ $action['class'] }}"
                    data-id="{{ $action['data-id'] }}">
                    <i class="{{ $action['icon'] }} pe-2"></i>
                    <span>{{ $action['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
