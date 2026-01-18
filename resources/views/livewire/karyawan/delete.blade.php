<div>
    <form method="post" class="d-inline me-2" wire:submit.prevent="deleteUser()">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger ml-3">
            <i class="material-icons text-white icon-18pt">delete</i> 
        </button>
    </form>
</div>
