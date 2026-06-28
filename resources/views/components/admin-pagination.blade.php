@if ($paginator->hasPages())
    <div class="mt-4 pt-3 border-top">
        {{ $paginator->links() }}
    </div>
@endif
