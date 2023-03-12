<div id="replaceUsersTable">
    <table class="striped">
        <thead class="bg-dark white-text">
        <tr>
            <td></td>
            <td>Naam</td>
            <td>Email</td>
        </tr>
        </thead>
        <tbody>
        @if($users->count() < 1)
            <tr>
                <td colspan="99" class="center">Geen gebruikers gevonden...</td>
            </tr>
        @else
            @foreach($users as $user)
                <tr onclick="window.location.href='{{ url('/gebruikers/bekijken/'.$user->id) }}'" class="hoverable clickable">
                    <td><img src="{{ $user->getProfileImage() }}" alt="" class="circle userImage"></td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
