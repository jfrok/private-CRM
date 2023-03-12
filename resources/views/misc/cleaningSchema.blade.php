@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        Gebruikers <a href="#" onclick="calculate(userArray)" class="right">Opnieuw</a>
                    </div>
                    <small>Gebruikers toevoegen</small>
                    <input type="text" id="addName" onchange="addName($(this).val())">
                    <table class="striped" id="appendToTable">

                    </table>
                </div>
            </div>
        </div>
        <div class="col s12 m8">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        Rooster
                    </div>
                    <table class="striped">
                        <tr>
                            <th></th>
                            <th>Maandag</th>
                            <th>Dinsdag</th>
                            <th>Woensdag</th>
                            <th>Donderdag</th>
                            <th>Vrijdag</th>
                        </tr>
                        <tr class="afwassen">
                            <th>Afwassen*</th>
                            <th class="grey-text monday"><input type="text"></th>
                            <th class="grey-text tuesday"><input type="text"></th>
                            <th class="grey-text wednesday"><input type="text"></th>
                            <th class="grey-text thursday"><input type="text"></th>
                            <th class="grey-text friday"><input type="text"></th>
                        </tr>
                        <tr class="stofzuigen">
                            <th>Stofzuigen*</th>
                            <th class="grey-text monday"><input type="text"></th>
                            <th class="grey-text tuesday"><input type="text"></th>
                            <th class="grey-text wednesday"><input type="text"></th>
                            <th class="grey-text thursday"><input type="text"></th>
                            <th class="grey-text friday"><input type="text"></th>
                        </tr>
                        <tr class="bureau-afnemen">
                            <th>Bureau afnemen*</th>
                            <th class="grey-text monday"><input type="text" value="Iedereen"></th>
                            <th class="grey-text tuesday"><input type="text" value="Iedereen"></th>
                            <th class="grey-text wednesday"><input type="text" value="Iedereen"></th>
                            <th class="grey-text thursday"><input type="text" value="Iedereen"></th>
                            <th class="grey-text friday"><input type="text" value="Iedereen"></th>
                        </tr>
                        <tr class="dweilen">
                            <th>Dweilen*</th>
                            <th class="grey-text monday"><input type="text"></th>
                            <th class="grey-text tuesday"><input type="text"></th>
                            <th class="grey-text wednesday"><input type="text"></th>
                            <th class="grey-text thursday"><input type="text"></th>
                            <th class="grey-text friday"><input type="text"></th>
                        </tr>
                        <tr class="wc-schoonmaken">
                            <th>Wc Schoonmaken*</th>
                            <th class="grey-text monday"><input type="text"></th>
                            <th class="grey-text tuesday"><input type="text"></th>
                            <th class="grey-text wednesday"><input type="text"></th>
                            <th class="grey-text thursday"><input type="text"></th>
                            <th class="grey-text friday"><input type="text" value="Martin"></th>
                        </tr>
                    </table>
                    <span>*Afwassen = Alles afwassen. Maar ook het schoonmaken van het keukenblok en koffiemachine! (Laat het droog en schoon achter).</span>
                    <br>
                    <span>*Stofzuigen = Stofzuigen van alle kamers (dus ook de keuken en wc).</span>
                    <br>
                    <span>*Bureau afnemen = Dagelijks een doekje over je bureau. Op vrijdag ook alle deuren etc. even afnemen.</span>
                    <br>
                    <span>*Dweilen = Even met de dweil over de vloer na het stofzuigen (ook de wc).</span>
                    <br>
                    <span>*WC Schoonmaken = Moet helaas ook gebeuren.</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        let userArray = [];
        function addName(name) {
            $('#addName').val('');
            userArray.push(name);
            $('#appendToTable').empty();
            for (i = 0; i < userArray.length; ++i) {
                $('#appendToTable').append(
                    '<tr><td>'+userArray[i]+'</td></tr>'
                );
            }
            calculate(userArray);
        }

        function calculate(arr) {
            let firstShift = arr.slice(0);
            let secondShift = [];

            //Handle afwassen
            let afwassenMonday = firstShift[Math.floor(Math.random() * firstShift.length)];
            let index = firstShift.indexOf(afwassenMonday);
            firstShift.splice(index, 1);
            $('.afwassen .monday input').val(afwassenMonday);
            secondShift.push(afwassenMonday);

            if(arr.length >= 1) {
                let afwassenTuesday = firstShift[Math.floor(Math.random() * firstShift.length)];
                let index = firstShift.indexOf(afwassenTuesday);
                firstShift.splice(index, 1);
                $('.afwassen .tuesday input').val(afwassenTuesday);
                secondShift.push(afwassenTuesday);
            }

            if(arr.length >= 2) {
                let afwassenWednesday = firstShift[Math.floor(Math.random() * firstShift.length)];
                let index = firstShift.indexOf(afwassenWednesday);
                firstShift.splice(index, 1);
                $('.afwassen .wednesday input').val(afwassenWednesday);
                secondShift.push(afwassenWednesday);
            }

            if(arr.length >= 3) {
                let afwassenThursday = firstShift[Math.floor(Math.random() * firstShift.length)];
                let index = firstShift.indexOf(afwassenThursday);
                firstShift.splice(index, 1);
                $('.afwassen .thursday input').val(afwassenThursday);
                secondShift.push(afwassenThursday);
            }

            if(arr.length >= 4) {
                let afwassenFriday = firstShift[Math.floor(Math.random() * firstShift.length)];
                let index = firstShift.indexOf(afwassenFriday);
                firstShift.splice(index, 1);
                $('.afwassen .friday input').val(afwassenFriday);
                secondShift.push(afwassenFriday);
            }

            //Handle stofzuigen
            if(arr.length >= 5) {
                let stofzuigenTuesday = firstShift[Math.floor(Math.random() * firstShift.length)];
                let index = firstShift.indexOf(stofzuigenTuesday);
                firstShift.splice(index, 1);
                $('.stofzuigen .tuesday input').val(stofzuigenTuesday);
                secondShift.push(stofzuigenTuesday);
            }

            if(arr.length >= 6) {
                let stofzuigenFriday = firstShift[Math.floor(Math.random() * firstShift.length)];
                let index = firstShift.indexOf(stofzuigenFriday);
                firstShift.splice(index, 1);
                $('.stofzuigen .friday input').val(stofzuigenFriday);
                secondShift.push(stofzuigenFriday);
            }

            let dweilenVrijdag = secondShift[Math.floor(Math.random() * secondShift.length)];
            let indexs = secondShift.indexOf(dweilenVrijdag);
            secondShift.splice(indexs, 1);
            $('.dweilen .friday input').val(dweilenVrijdag);
            firstShift.push(dweilenVrijdag);
        }
    </script>
@endsection
