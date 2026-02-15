@props([
    'faq' => null,
])

<tr>
    <td class="px-4 py-3">
        <span class="fw-bold text-secondary">#{{ $faq->id }}</span>
    </td>
    <td class="px-4 py-3">
        <div>
            <span class="fw-bold text-dark d-block mb-1">{{ Str::limit($faq->question, 60) }}</span>
            <small class="text-secondary">{{ Str::limit(strip_tags($faq->answer), 80) }}</small>
        </div>
    </td>
    <td class="px-4 py-3">
        <span class="badge badge-soft-{{ $faq->is_active ? 'success' : 'secondary' }} fw-medium px-2 py-1">
            {{ $faq->is_active ? 'Active' : 'Inactive' }}
        </span>
    </td>
    <td class="px-4 py-3">
        <span class="badge bg-light text-secondary border fw-normal px-2 py-1">
            {{ $faq->category }}
        </span>
    </td>
    <td class="px-4 py-3">
        <small class="text-secondary">{{ $faq->updated_at->diffForHumans() }}</small>
    </td>
    <td class="px-4 py-3 text-end">
        <div class="d-flex gap-2 justify-content-end">
            <button type="button"
                class="btn btn-icon"
                data-bs-toggle="modal"
                data-bs-target="#previewModal{{ $faq->id }}"
                data-bs-toggle="tooltip" title="Preview">
                <span class="material-icons-outlined">visibility</span>
            </button>
            <button type="button"
                class="btn btn-icon"
                data-bs-toggle="modal"
                data-bs-target="#editModal{{ $faq->id }}"
                data-bs-toggle="tooltip" title="Edit">
                <span class="material-icons-outlined">edit</span>
            </button>
            <button type="button"
                class="btn btn-icon btn-icon-danger"
                data-bs-toggle="modal"
                data-bs-target="#deleteModal{{ $faq->id }}"
                data-bs-toggle="tooltip" title="Delete">
                <span class="material-icons-outlined">delete</span>
            </button>
        </div>
    </td>
</tr>
