<script>
    $(document).ready(function () {

        $('.fc-prev-button span').click(function () {
            $.ajax({
                method: 'GET',
                url: '{{ url('/kalender-sub') }}/' + '{{ $cookieDate }}',
                success: function (data) {
                    $('#reloadCookie').empty();
                    $('#reloadCookie').append(data);
                }
            });
        });

        $('.fc-next-button span').click(function () {
            $.ajax({
                method: 'GET',
                url: '{{ url('/kalender-add') }}/' + '{{ $cookieDate }}',
                success: function (data) {
                    $('#reloadCookie').empty();
                    $('#reloadCookie').append(data);
                }
            });
        });

        $('.fc-today-button').click(function () {
            $.ajax({
                method: 'GET',
                url: '{{ url('/kalender-today') }}',
                success: function (data) {
                    $('#reloadCookie').empty();
                    $('#reloadCookie').append(data);
                }
            });
        });

    })
</script>

<script>

    function reload() {
        $.ajax({
            method: 'GET',
            url: '{{ url('/kalender-load') }}/' + '{{ $cookieDate }}',
            success: function (data) {
                $('#cal-script').empty();
                $('#cal-script').append(data);

                calendar.render();
                calendar.gotoDate('{{ $cookieDate }}')
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        calendar.render();
        calendar.gotoDate('{{ $cookieDate }}')
    });

</script>
