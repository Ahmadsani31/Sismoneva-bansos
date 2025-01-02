@props(['title', 'links'])

<div class="col-lg-12 col-md-12 col-12">
    <!-- Page header -->
    <div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="mb-2 mb-lg-0">
                <h3 class="mb-0  fw-bold">{{ $title }}</h3>
            </div>
        </div>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($links as $label => $url)
                @if ($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $url }}">{{ $label }}</a>
                    </li>
                @endif
            @endforeach

        </ol>
    </nav>
</div>
