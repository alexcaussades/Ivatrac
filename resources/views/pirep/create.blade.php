@extends("metar-base")

@section("title", "Flight Plan System")


@include("navbar")

@section('content')

<div class="container">
    <h3 class="mt-2">Flight Plan System</h3>
</div>

<div class="container px-4 text-center mt-5">
    <h4 class="text-primary">INTERNATIONAL FLIGHT PLAN</h4>
    <hr>
    <form action="{{ Route("pirep.create")}}" method="POST">
        @csrf
        <input type="hidden" name="users_id" value="{{ Auth::user()->id }}">
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="number" class="text-primary d-flex justify-content-start">Identification :</label>
                    <input type="text" class="form-control" name="identification" id="Identification" placeholder="Identification">
                </div>
                <div class="col">
                    <label for="flightRules" class="text-primary d-flex justify-content-start">Flight Rules :</label>
                    <select class="form-control" id="flightRules" name="flightRules">
                        <option value="I" selected>IFR</option>
                        <option value="V">VFR</option>
                    </select>
                </div>
                <div class="col">
                    <label for="typeOfFlight" class="text-primary d-flex justify-content-start">Type of Flight :</label>
                    <select class="form-control" name="typeOfFlight">
                        <option value="S" selected>S - Scheduled Air Transport</option>
                        <option value="N">N - Non-Scheduled Air Transport</option>
                        <option value="G">G - General Aviation</option>
                        <option value="M">M - Military</option>
                        <option value="X">X - Other</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <label for="aircraftType" class="text-primary d-flex justify-content-start">Aircraft Type :</label>
                    <input type="text" class="form-control" name="aircraftType" id="aircraftType" placeholder="Aircraft Type">
                </div>
                <div class="col">
                    <label for="wakeTurbulence" class="text-primary d-flex justify-content-start">Wake Turbulence :</label>
                    <select class="form-control" name="wakeTurbulence" id="wakeTurbulence">
                        <option value="L">L - Ligth</option>
                        <option value="M" selected>M - Medium</option>
                        <option value="H">H - Heavy</option>
                    </select>
                </div>
                <div class="col">
                    <label for="equipment" class="text-primary d-flex justify-content-start">Equipment :</label>
                    <select class="form-select" name="equipment[]" multiple>
                        <option value="S">S - Standard (VHF, VOR, ILS)</option>
                        <option value="A">A - GBAS Ldg System</option>
                        <option value="B">B - LPV</option>
                        <option value="C">C - LORAN C</option>
                        <option value="D">D - DME</option>
                        <option value="E1">E1 - FMC WPR ACARS</option>
                        <option value="E2">E2 - D-FIS ACARS</option>
                        <option value="E3">E3 - PDC ACARS</option>
                        <option value="F">F - ADF</option>
                        <option value="G">G - GNSS (GPS)</option>
                        <option value="H">H - HF RTF</option>
                        <option value="I">I - Inertial Nav</option>
                        <option value="J1">J1 - CPDLC ATN VDL Mode 2</option>
                        <option value="J2">J2 - CPDLC FANS 1/A HFDL</option>
                        <option value="J3">J3 - CPDLC FANS 1/A VDL Mode 4</option>
                        <option value="J4">J4 - CPDLC FANS 1/A VDL Mode 2</option>
                        <option value="J5">J5 - CPDLC FANS 1/A SATCOM (INMARSAT)</option>
                        <option value="J6">J6 - CPDLC FANS 1/A SATCOM (MTSAT)</option>
                        <option value="J7">J7 - CPDLC FANS 1/A SATCOM (Iridium)</option>
                        <option value="K">K - MLS</option>
                        <option value="L">L - ILS</option>
                        <option value="M1">M1 - M1 - ATC RTF SATCOM (INMARSAT)</option>
                        <option value="M2">M2 - ATC RTF SATCOM (MTSAT)</option>
                        <option value="M3">M3 - ATC RTF SATCOM (Iridium)</option>
                        <option value="O">O - VOR</option>
                        <option value="P1">P1 - CPDLC RCP 400</option>
                        <option value="P2">P2 - CPDLC RCP 240</option>
                        <option value="P3">P3 - SATVOICE RCP 400</option>
                        <option value="R">R - PBN (PBN/ required in item 18)</option>
                        <option value="T">T - TACAN</option>
                        <option value="U">U - UHF RTF</option>
                        <option value="V">V - VHF RTF</option>
                        <option value="W">W - RVSM (FL290-FL410)</option>
                        <option value="X">X - MNPS</option>
                        <option value="Y">Y - 8.33KHz RADIO</option>
                        <option value="Z">Z - Others (specify in item 18 preceded by COM/ NAV/ or DAT/)</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <label for="departureAerodrome" class="text-primary d-flex justify-content-start">Departure Aerodrome :</label>
                    <input type="text" class="form-control" name="departureAerodrome" id="departureAerodrome" placeholder="Departure Aerodrome">
                </div>
                <div class="col">
                    <label for="departureTime" class="text-primary d-flex justify-content-start">Departure Time :</label>
                    <input type="time" class="form-control" name="departureTime" id="departureTime" placeholder="Departure Time">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <label for="departureTime" class="text-primary d-flex justify-content-start">Speed :</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <select name="speed" id="">
                                <option value="N" selected>N</option>
                                <option value="M">M</option>
                                <option value="K">K</option>
                            </select>
                        </span>
                        <input type="text" class="form-control" name="speednumber" placeholder="Cruise Speed" aria-label="Cruise Speed" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="col">
                    <label for="departureTime" class="text-primary d-flex justify-content-start">Level :</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <select name="level" id="">
                                <option value="F" selected>F</option>
                                <option value="A">A</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="VFR">VFR</option>
                            </select>
                        </span>
                        <input type="text" class="form-control" name="LevelFL" aria-label="Cruise Speed" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <label for="Route" class="text-primary d-flex justify-content-start">Route :</label>
                    <textarea class="form-control" name="route" id="Route" rows="3" placeholder="Route"></textarea>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <label for="destinationAerodrome" class="text-primary d-flex justify-content-start">Destination Aerodrome :</label>
                    <input type="text" class="form-control" name="destinationAerodrome" id="destinationAerodrome" placeholder="Destination Aerodrome">
                </div>
                <div class="col">
                    <label for="destinationTime" class="text-primary d-flex justify-content-start">Total EET :</label>
                    <input type="time" class="form-control" name="eet" id="destinationTime" placeholder="0000">
                </div>
                <div class="col">
                    <label for="destinationTime" class="text-primary d-flex justify-content-start">Alternate Aerodrome :</label>
                    <input type="text" class="form-control" name="Alternate" id="destinationTime" placeholder="Alternate Aerodrome">
                </div>
                <div class="col">
                    <label for="destinationTime" class="text-primary d-flex justify-content-start">2nd Alternate Aerodrome :</label>
                    <input type="text" class="form-control" name="Alternate2" id="destinationTime" placeholder="Alternate Aerodrome">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <label for="Route" class="text-primary d-flex justify-content-start">Other Information :</label>
                    <textarea class="form-control" name="Other" id="Route" rows="3" placeholder="DOF/230703"></textarea>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <label for="Route" class="text-primary d-flex justify-content-start">Endurance :</label>
                    <input type="time" class="form-control" name="endurance" id="destinationTime" placeholder="0000">
                </div>
                <div class="col">
                    <label for="Route" class="text-primary d-flex justify-content-start">Persons on Board :</label>
                    <input type="text" class="form-control" name="pob" id="destinationTime" placeholder="0000">
                </div>
                <div class="col">
                    <label for="Route" class="text-primary d-flex justify-content-start">Fuel on Board :</label>
                    <input type="text" class="form-control" name="fuel" id="destinationTime" placeholder="2640 KG">
                </div>
            </div>
            <hr>
            <div class="text-primary d-flex justify-content-end">
                <button type="submit" class="btn btn-success ms-3">Submit</button> <button type="reset" class="btn btn-outline-danger ms-3">Reset</button>
            </div>
    </form>
</div>

@endsection