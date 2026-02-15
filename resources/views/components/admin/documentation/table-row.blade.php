@props([
    'doc' => null,
])

<tr>
    <td class="px-4 py-3">
        <span class="fw-bold text-secondary">#{{ $doc->id }}</span>
    </td>
    <td class="px-4 py-3">
        <div>
            <a href="{{ route('admin.documentation.edit', $doc->id) }}"
                class="fw-bold text-dark text-decoration-none d-block mb-1">
                {{ Str::limit($doc->title, 60) }}
            </a>
            <small class="text-secondary">{{ Str::limit($doc->excerpt ?? strip_tags($doc->content), 80) }}</small>
        </div>
    </td>
    <td class="px-4 py-3">
        <span class="badge bg-light text-secondary border fw-normal px-2 py-1">
            {{ $doc->category }}
        </span>
    </td>
    <td class="px-4 py-3">
        <small class="text-secondary">{{ $doc->updated_at->diffForHumans() }}</small>
    </td>
    <td class="px-4 py-3 text-end">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('documentation.view', $doc->slug) }}" target="_blank"
                class="btn btn-icon"
                data-bs-toggle="tooltip" title="View">
                <span class="material-icons-outlined">visibility</span>
            </a>
            <a href="{{ route('admin.documentation.edit', $doc->id) }}"
                class="btn btn-icon"
                data-bs-toggle="tooltip" title="Edit">
                <span class="material-icons-outlined">edit</span>
            </a>
            <button type="button"
                class="btn btn-icon btn-icon-danger"
                data-bs-toggle="modal"
                data-bs-target="#deleteDocModal{{ $doc->id }}"
                data-bs-toggle="tooltip" title="Delete">
                <span class="material-icons-outlined">delete</span>
            </button>
        </div>
    </td>
</tr>
