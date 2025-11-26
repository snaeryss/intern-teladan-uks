<script>
    $(document).ready(function () {
        setTimeout(function () {
            @if(session()->has('success'))
            Swal.fire({
                title: "Success!",
                html: "{!! session()->get('success') !!}",
                icon: "success",
                allowOutsideClick: false,
                showCancelButton: 0,
            });
            @endif
            @if(session()->has('error'))
            Swal.fire({
                title: "Fail!",
                html: "{!! session()->get('error') !!}",
                icon: "error",
                allowOutsideClick: false,
                showCancelButton: 0,
            });
            @endif
            @if(session()->has('info'))
            Swal.fire({
                title: "Info!",
                html: "{!! session()->get('info') !!}",
                icon: "info",
                showCancelButton: 0,
            });
            @endif
            @if(session()->has('warning'))
            Swal.fire({
                title: "Peringatan!",
                html: "{!! session()->get('warning') !!}",
                icon: "warning",
                showCancelButton: 0,
            });
            @endif
            @if(session()->has('errors'))
            @php
                $message = collect(session()->get('errors')->messages())
                    ->map(fn($messages, $field) => $field . ' : ' . implode(', ', $messages))
                    ->implode('<br>');
            @endphp
            Swal.fire({
                title: "Galat!",
                html: "{!! $message !!}",
                icon: "warning",
                showCancelButton: 0,
            });
            @endif
        }, 1000);
    });
</script>
