<table class="striped">
    <thead class="secondary white-text">
    <tr>
        <td></td>
        <td>Makelaar</td>
        <td>Adres</td>
        <td>Email</td>
        <td>Telefoonnummer</td>
        <td>Website</td>
        <td>Totaalprijs deze maand</td>
    </tr>
    </thead>
    <tbody>
    @forelse($customers as $customer)
        <tr class="hoverable">
            <td class="cursor" onclick="goToMakelaar('{{ $customer->id }}')"></td>
            <td class="cursor" onclick="goToMakelaar('{{ $customer->id }}')">{{ ucfirst($customer->company_name) }}</td>
            <td class="cursor" onclick="goToMakelaar('{{ $customer->id }}')">{{ $customer->fullAddress() }}</td>
            <td class="cursor" onclick="goToMakelaar('{{ $customer->id }}')">{{ $customer->company_email }}</td>
            <td class="cursor" onclick="goToMakelaar('{{ $customer->id }}')">{{ $customer->company_phone }}</td>
            <td class="cursor"><a href="{{ $customer->company_website }}" target="_blank">{{ $customer->company_website }}</a></td>
            <td class="cursor" onclick="goToMakelaar('{{ $customer->id }}')">€ {{ $customer->getTotalProductPriceThisMonth() }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="99" class="center">Geen makelaars gevonden...</td>
        </tr>
    @endforelse
    </tbody>
</table>
