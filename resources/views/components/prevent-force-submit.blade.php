<script>
    $(document).ready(function () {
        let forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(_) {
                let buttonSubmit = form.querySelector('.btn-submit');
                buttonSubmit.disabled = true;
                buttonSubmit.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Please wait...';
            });
        });
    })
</script>
