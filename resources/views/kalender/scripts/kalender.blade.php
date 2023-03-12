<div class="card col s12">
    <div class="card-content">
        <div id='calendar'></div>
    </div>
</div>

<div id="reloadCookie">
    @include('kalender.scripts.cookie')
</div>

<script>

    $(document).ready(function () {

        $.ajax({
            method: 'GET',
            url: '{{ url('/kalender-filter') }}/' + $('#eventGebruiker').val(),
            success: function (data) {
                $('#cal-script').empty();
                $('#cal-script').append(data);

                calendar.render();
            }
        });

    })

</script>

<script>

    var calendarEl = document.getElementById('calendar');
    var elems = document.querySelectorAll('.modal');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            center: 'timeGridWeek,listWeek,timeGridDay,dayGridMonth'
        },

        initialView: 'timeGridWeek',

        locale: 'nl',
        height: 608,
        nowIndicator: true,
        selectable: true,

        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5],
        },

        weekends: false,
        slotMinTime: "08:00:00",
        slotMaxTime: "18:00:00",

        googleCalendarApiKey: 'AIzaSyAe7WxD4hV3Di0BV2ieqzZvWeOE1k8HSic',

        eventSources: [
            {
                googleCalendarId: 'agendaskytzonline@gmail.com',
                backgroundColor: '{{ \App\Models\User::find(2)->color }}',
                borderColor: '{{ \App\Models\User::find(2)->color }}',
            },
            {
                googleCalendarId: '5ubh5ecf6bttch05in1l1k4224@group.calendar.google.com',
                backgroundColor: '#54B8A2',
                borderColor: '#54B8A2',
            }
        ],

        events: [
                @foreach(\App\Models\Events::all() as $event)
            {
                id: '{{ $event->id }}',
                title: "{!! $event->titel !!}",

                @if($event->tijd_vanaf == null)
                start: '{{ $event->datum_vanaf }}',
                end: '{{ $event->datum_tot }}',
                allDay: true,
                @else
                start: '{{ $event->datum_vanaf }}T{{ $event->tijd_vanaf }}',
                end: '{{ $event->datum_tot }}T{{ $event->tijd_tot }}',
                allDay: false,
                @endif

                    @if($event->user_id != 0)
                backgroundColor: '{{ \App\Models\User::find($event->user_id)->color }}',
                borderColor: '{{ \App\Models\User::find($event->user_id)->color }}',
                @else
                backgroundColor: '#260089',
                borderColor: '#260089',
                @endif

                extendedProps: {
                    user: '{{ $event->user_name }}',
                    project: '{{ $event->project_id }}',
                    customer: '{{ $event->customer_id }}',
                },
            },
            @endforeach
        ],

        eventDataTransform: function (event) {
            event.url = "";
            return event;
        },

        eventClick: function (info) {
            const event = calendar.getEventById(info.event.id);

            // Bewerk form
            document.querySelector('input[name="titel_edit"]').value = event.title;

            $('#eventEdit').val(event.extendedProps.project);
            $('#eventEdit').trigger('change');

            $('#eventEdit2').val(event.extendedProps.customer);
            $('#eventEdit2').trigger('change');

            $('#eventEdit3').val(event.extendedProps.user);
            $('#eventEdit3').trigger('change');

            document.querySelector('input[name="datum_vanaf_edit"]').value = moment(event.start).format('YYYY-MM-DD');
            document.querySelector('input[name="datum_tot_edit"]').value = moment(event.end).format('YYYY-MM-DD');
            document.querySelector('input[name="tijd_vanaf_edit"]').value = moment(event.start).format('HH:mm');
            document.querySelector('input[name="tijd_tot_edit"]').value = moment(event.end).format('HH:mm');
            document.querySelector('input[name="id"]').value = event.id;

            document.querySelector('input[name="event_id"]').value = event.id;

            if (event.allDay === true) {
                document.getElementById('allDay2').checked = true;
                document.querySelector('input[name="tijd_vanaf_edit"]').disabled = true;
                document.querySelector('input[name="tijd_tot_edit"]').disabled = true;
            }

            // Uren form
            if (event.extendedProps.project > 0 && moment(event.end).format('YYYY-MM-DD') === moment(event.start).format('YYYY-MM-DD') && event.extendedProps.user !== "Iedereen") {
                $('#eventEdit4').val(event.extendedProps.project);
                $('#eventEdit4').trigger('change');

                document.querySelector('input[name="titel_uren"]').value = event.title;
                document.querySelector('input[name="datum_vanaf_uren"]').value = moment(event.start).format('YYYY-MM-DD');
                document.querySelector('input[name="tijd_vanaf_uren"]').value = moment(event.start).format('HH:mm');
                document.querySelector('input[name="tijd_tot_uren"]').value = moment(event.end).format('HH:mm');
                document.querySelector('input[name="user_name_uren"]').value = event.extendedProps.user;

                $('#invalid').hide();
            } else {
                $('#eventEdit4').val(event.extendedProps.project);
                $('#eventEdit4').trigger('change');

                document.querySelector('input[name="titel_uren"]').value = event.title;
                document.querySelector('input[name="datum_vanaf_uren"]').value = moment(event.start).format('YYYY-MM-DD');
                document.querySelector('input[name="tijd_vanaf_uren"]').value = '09:00';
                document.querySelector('input[name="tijd_tot_uren"]').value = '15:00';
                document.querySelector('input[name="user_name_uren"]').value = event.extendedProps.user;
                $('#invalid').hide();
            }

            $('#editEventModal').modal('open');

            if (event.allDay === true) {
                document.getElementById('allDay2').checked = true;
            }
        },

        dateClick: function (info) {
            var endDate = moment(info.date).add(1, 'hours');

            document.querySelector('input[name="titel"]').value = '';
            document.querySelector('input[name="datum_vanaf"]').value = moment(info.date).format('YYYY-MM-DD');
            document.querySelector('input[name="datum_tot"]').value = moment(info.date).format('YYYY-MM-DD');
            document.querySelector('input[name="tijd_vanaf"]').value = moment(info.date).format('HH:mm');
            document.querySelector('input[name="tijd_tot"]').value = moment(endDate).format('HH:mm');

            $('#createEventModal').modal('open');
        }
    });

</script>
