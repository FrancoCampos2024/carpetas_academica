@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav class="d-flex justify-items-center justify-content-between">

            <div class="flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">

                <div>
                    <ul class="pagination">

                        <!-- Inicio -->
                        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                            <button type="button" class="page-link" wire:click="gotoPage(1)" x-on:click="{{ $scrollIntoViewJsSnippet }}">&laquo;</button>
                        </li>

                        <!-- Anterior -->
                        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                            <button type="button" class="page-link" wire:click="previousPage" x-on:click="{{ $scrollIntoViewJsSnippet }}">&lsaquo;</button>
                        </li>

                        <!-- Páginas -->
                        @for ($i = max($paginator->currentPage() - 2, 1); $i <= min($paginator->currentPage() + 2, $paginator->lastPage()); $i++)
                            <li class="page-item {{ $i == $paginator->currentPage() ? 'active' : '' }}">
                                <button type="button" class="page-link" wire:click="gotoPage({{ $i }})" x-on:click="{{ $scrollIntoViewJsSnippet }}">{{ $i }}</button>
                            </li>
                        @endfor

                        <!-- Siguiente -->
                        <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                            <button type="button" class="page-link" wire:click="nextPage" x-on:click="{{ $scrollIntoViewJsSnippet }}">&rsaquo;</button>
                        </li>

                        <!-- Fin -->
                        <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
                            <button type="button" class="page-link" wire:click="gotoPage({{ $paginator->lastPage() }})" x-on:click="{{ $scrollIntoViewJsSnippet }}">&raquo;</button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif
</div>
