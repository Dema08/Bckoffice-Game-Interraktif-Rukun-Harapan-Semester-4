@foreach ($mapel as $m)
<div class="modal fade" id="modalDelete-{{ $m->id }}" tabindex="-1" aria-labelledby="modalDeleteLabel-{{ $m->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross-sm"></em>
            </a>
            <div class="modal-body modal-body-md text-center">
                <em class="icon ni ni-alert-circle-fill text-danger" style="font-size: 48px;"></em>
                <h5 class="modal-title mt-3">Delete Confirmation</h5>
                <p>Are you sure you want to delete the course "<strong>{{ $m->nama_mapel }}</strong>"? This action cannot be undone.</p>

                <form action="{{ route('mata_pelajaran.destroy', $m->id) }}" method="POST" class="pt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
