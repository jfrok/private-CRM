<div id="productPricesModal" class="modal modal-fixed-footer roundedModal" style="height: 80%; min-height: 80%">
    <form method="post" action="{{ route('keysoftware.update.products') }}">
        @csrf
        <div class="modal-content">
            <style>
                td {
                    padding: 7.5px;
                }
            </style>
            <h6 class="modal-title">Prijzen Bewerken</h6>
            <table class="striped">
                <thead class="secondary white-text">
                <tr>
                    <td></td>
                    <td>Product</td>
                    <td>Kadaster Prijs</td>
                    <td>Planviewer Prijs</td>
                    <td>Totaal Prijs Per Call</td>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $pr)
                    <tr>
                        <td class="cursor p-5"></td>
                        <td class="cursor p-5">{{ $pr->name }}</td>
                        <td class="cursor p-5"><input required name="product[{{ $pr->id }}][kadaster_price]" style="height: 2rem !important;" type="number" step=".001" value="{{ $pr->kadaster_price }}"></td>
                        <td class="cursor p-5"><input required name="product[{{ $pr->id }}][planviewer_price]" style="height: 2rem !important;" type="number" step=".001" value="{{ $pr->planviewer_price }}"></td>
                        <td class="cursor p-5"><input required name="product[{{ $pr->id }}][total_price]" style="height: 2rem !important;" type="number" step=".001" value="{{ $pr->total_price }}"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect waves-green btn-flat">Sluiten</a>
            <button type="submit" class="waves-effect waves-green btn-flat white-text">Updaten</button>
        </div>
    </form>
</div>
