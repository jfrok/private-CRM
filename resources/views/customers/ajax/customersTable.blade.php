<div id="replaceCustomersTable">
    <table class="striped">
        <thead class="bg-dark white-text">
            <tr>
                <td></td>
                <td>Naam</td>
                <td>Hoofdlocatie</td>
                <td>Eigenaar</td>
            </tr>
        </thead>
        <tbody>
            @if($customers->count() < 1)
                <tr>
                    <td colspan="99" class="center">Geen klanten gevonden...</td>
                </tr>
            @else
                @foreach($customers as $customer)
                    <tr onclick="window.location.href='{{ url('/klanten/bekijken/'.$customer->id) }}'" class="hoverable clickable">
                        @if($customer->is_company == true)
                            <td><i class="material-icons">store</i></td>
                        @else
                            <td><i class="material-icons">person</i></td>
                        @endif
                        <td>{{ $customer->company_name }}</td>
                        @if($customer->hasMainLocation() == true)
                            <td>{{ $customer->getMainLocation()->address . ', ' . $customer->getMainLocation()->zip_code . ' ' . $customer->getMainLocation()->place  }}</td>
                        @else
                            <td>Geen hoofdlocatie...</td>
                        @endif
                        @if($customer->hasOwner() == true)
                            <td>{{ $customer->getOwner()->first_name . ' ' . $customer->getMainLocation()->last_name . ', ' . $customer->getMainLocation()->phone  }}</td>
                        @else
                            <td>Geen eigenaar...</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
