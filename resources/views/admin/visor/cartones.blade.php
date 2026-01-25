<div class="row g-2">

@for($i = 1; $i <= 12; $i++)
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-dark text-white">
                Cartón #{{ $i }}
            </div>
            <div class="card-body text-center" style="font-size: 14px;">
                <div class="row">
                    @for($n = 1; $n <= 27; $n++)
                        <div class="col-4 border small">
                            {{ rand(1,90) }}
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endfor

</div>
