/**
 * Created by Nikey on 3.4.14.
 */


var oldRes = [];
var oldFuse = [];
var oldCap = [];
var oldConnection = [];
var oldConSoc = [];
var oldConGen = [];
var oldSwitch = [];
var oldFilter = [];
var oldRotElaps = [];
var oldTubeWave = [];
var oldDiodeLF = [];
var oldOpto = [];
var oldCrystal = [];
var oldTransistorBiLF = [];
var oldTransistorFetLF = [];
var oldInductive = [];
var oldMicrocircuit = [];
var oldDiodeRF = [];
var oldMemory = [];

//----------------------------------------------------------------------------------------------------------------------

function saveRes(event) {
    var save = $(event.target).attr('id') == 'SaveRes';
    var err = 0;
    if(save) {
        $data =  $("#formResE").serializeJSON();
        var val = $('#formResE input[id="resistorForm_MaxPower"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formResE .submitMsg").remove();
            $("#formResE").append('<span class="submitMsg"> Vyplňte maximální výkon (celé číslo) </span>');
            return;
        }
        val = $('#formResE input[id="resistorForm_Label"]').val();
        if ( val == "" ) {
            $("#formResE .submitMsg").remove();
            $("#formResE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#formResE input[id="resistorForm_DissipationPower"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formResE .submitMsg").remove();
            $("#formResE").append('<span class="submitMsg"> Vyplňte ztrátový výkon (desetinné číslo) </span>');
            return;
        }
        val = $('#formResE input[id="resistorForm_DPTemp"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formResE .submitMsg").remove();
            $("#formResE").append('<span class="submitMsg"> Vyplňte oteplení ZV (desetinné číslo) </span>');
            return;
        }

        $url = $("#formResE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "resistor"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formResE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formResE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $('#formResE input[id="resistorForm_Label"]').val(oldRes['Label']);
        $('#formResE input[id="resistorForm_Type"]').val(oldRes['Type']);
        $('#formResE input[id="resistorForm_Value"]').val(oldRes['Value']);
        $('#formResE select[id="resistorForm_Quality"]').val(oldRes['Quality']);
        $('#formResE select[id="resistorForm_Material"]').val(oldRes['Material']);
        $('#formResE select[id="resistorForm_Environment"]').val(oldRes['Environment']);
        $('#formResE input[id="resistorForm_MaxPower"]').val(oldRes['MaxPower']);
        $('#formResE input[id="resistorForm_VoltageOperational"]').val(oldRes['VoltageOperational']);
        $('#formResE input[id="resistorForm_CurrentOperational"]').val(oldRes['CurrentOperational']);
        $('#formResE input[id="resistorForm_DissipationPower"]').val(oldRes['DissipationPower']);
        $('#formResE input[id="resistorForm_DPTemp"]').val(oldRes['DPTemp']);
        $('#formResE input[id="resistorForm_PassiveTemp"]').val(oldRes['PassiveTemp']);
        $('#formResE input[id="resistorForm_Alternate"]').val(oldRes['Alternate']);
        $('#formResE input[id="resistorForm_CasePart"]').val(oldRes['CasePart']);
    }
    $("#formResE input:not(:submit), #formResE select").attr('disabled', 'disabled');
    $('#SaveRes').remove();
    $('#CancelRes').next('div').remove();
    $('#CancelRes').remove();
    $("#EditRes").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveCap(event) {
    var save = $(event.target).attr('id') == 'SaveCap';
    var err = 0;
    if(save) {
        $data =  $("#formCapE").serializeJSON();
        var val = $('#formCapE input[id="capacitorForm_Value"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formCapE .submitMsg").remove();
            $("#formCapE").append('<span class="submitMsg"> Vyplňte hodnotu (celé číslo) </span>');
            return;
        }
        val = $('#formCapE input[id="capacitorForm_Label"]').val();
        if ( val == "" ) {
            $("#formCapE .submitMsg").remove();
            $("#formCapE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#formCapE input[id="capacitorForm_VoltageMax"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formCapE .submitMsg").remove();
            $("#formCapE").append('<span class="submitMsg"> Vyplňte maximální napětí (desetinné číslo) </span>');
            return;
        }
        val = $('#formCapE input[id="capacitorForm_VoltageOperational"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formCapE .submitMsg").remove();
            $("#formCapE").append('<span class="submitMsg"> Vyplňte provozní napětí (desetinné číslo) </span>');
            return;
        }
        val = $('#formCapE input[id="capacitorForm_PassiveTemp"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formCapE .submitMsg").remove();
            $("#formCapE").append('<span class="submitMsg"> Vyplňte pasivní oteplení (celé číslo) </span>');
            return;
        }

        $url = $("#formCapE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "capacitor"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formCapE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formCapE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $('#formCapE input[id="capacitorForm_Label"]').val(oldCap['Label']);
        $('#formCapE input[id="capacitorForm_Type"]').val(oldCap['Type']);
        $('#formCapE input[id="capacitorForm_Value"]').val(oldCap['Value']);
        $('#formCapE select[id="capacitorForm_Quality"]').val(oldCap['Quality']);
        $('#formCapE select[id="capacitorForm_Material"]').val(oldCap['Material']);
        $('#formCapE select[id="capacitorForm_Environment"]').val(oldCap['Environment']);
        $('#formCapE input[id="capacitorForm_CasePart"]').val(oldCap['CasePart']);
        $('#formCapE input[id="capacitorForm_PassiveTemp"]').val(oldCap['PassiveTemp']);
        $('#formCapE input[id="capacitorForm_VoltageOperational"]').val(oldCap['VoltageOperational']);
        $('#formCapE input[id="capacitorForm_VoltageMax"]').val(oldCap['VoltageMax']);
        $('#formCapE input[id="capacitorForm_VoltageAC"]').val(oldCap['VoltageAC']);
        $('#formCapE input[id="capacitorForm_VoltageDC"]').val(oldCap['VoltageDC']);
        $('#formCapE input[id="capacitorForm_SerialResistor"]').val(oldCap['SerialResistor']);
    }
    $("#formCapE input:not(:submit), #formCapE select").attr('disabled', 'disabled');
    $('#SaveCap').remove();
    $('#CancelCap').next('div').remove();
    $('#CancelCap').remove();
    $("#EditCap").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveFuse(event) {
    var save = $(event.target).attr('id') == 'SaveFuse';
    var err = 0;
    if(save) {
        $data =  $("#formFuseE").serializeJSON();
        var val = $('#formFuseE input[id="fuseForm_Label"]').val();
        if ( val == "" ) {
            $("#formFuseE .submitMsg").remove();
            $("#formFuseE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }

        $url = $("#formFuseE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "fuse"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formFuseE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formFuseE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $('#formFuseE input[id="fuseForm_Label"]').val(oldFuse['Label']);
        $('#formFuseE input[id="fuseForm_Type"]').val(oldFuse['Type']);
        $('#formFuseE input[id="fuseForm_Value"]').val(oldFuse['Value']);
        $('#formFuseE select[id="fuseForm_Environment"]').val(oldFuse['Environment']);
        $('#formFuseE input[id="fuseForm_CasePart"]').val(oldFuse['CasePart']);
    }
    $("#formFuseE input:not(:submit), #formFuseE select").attr('disabled', 'disabled');
    $('#SaveFuse').remove();
    $('#CancelFuse').next('div').remove();
    $('#CancelFuse').remove();
    $("#EditFuse").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveConnection(event) {
    var save = $(event.target).attr('id') == 'SaveConnection';
    var err = 0;
    if(save) {
        $data =  $("#formConnectionE").serializeJSON();
        var val = $('#formConnectionE input[id="connectionForm_Label"]').val();
        if ( val == "" ) {
            $("#formConnectionE .submitMsg").remove();
            $("#formConnectionE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }

        $url = $("#formConnectionE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "connection"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formConnectionE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formConnectionE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $('#formConnectionE input[id="connectionForm_Label"]').val(oldConnection['Label']);
        $('#formConnectionE select[id="connectionForm_ConnectionType"]').val(oldConnection['ConnectionType']);
        $('#formConnectionE select[id="connectionForm_Environment"]').val(oldConnection['Environment']);
        $('#formConnectionE input[id="connectionForm_CasePart"]').val(oldConnection['CasePart']);
        $('#formConnectionE input[id="connectionForm_Type"]').val(oldConnection['Type']);
    }
    $("#formConnectionE input:not(:submit), #formConnectionE select").attr('disabled', 'disabled');
    $('#SaveConnection').remove();
    $('#CancelConnection').next('div').remove();
    $('#CancelConnection').remove();
    $("#EditConnection").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveConSoc(event) {
    var save = $(event.target).attr('id') == 'SaveConSoc';
    var err = 0;
    if(save) {
        $data =  $("#formConSocE").serializeJSON();
        var val = $('#formConSocE input[id="connectorSocForm_Label"]').val();
        if ( val == "" ) {
            $("#formConSocE .submitMsg").remove();
            $("#formConSocE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#formConSocE input[id="connectorSocForm_ActivePins"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formConSocE .submitMsg").remove();
            $("#formConSocE").append('<span class="submitMsg"> Vyplňte aktivní piny (celé číslo) </span>');
            return;
        }

        $url = $("#formConSocE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "conSoc"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formConSocE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formConSocE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $('#formConSocE input[id="connectorSocForm_Label"]').val(oldConSoc['Label']);
        $('#formConSocE select[id="connectorSocForm_ConnectorType"]').val(oldConSoc['ConnectorType']);
        $('#formConSocE select[id="connectorSocForm_Environment"]').val(oldConSoc['Environment']);
        $('#formConSocE select[id="connectorSocForm_Quality"]').val(oldConSoc['Quality']);
        $('#formConSocE input[id="connectorSocForm_CasePart"]').val(oldConSoc['CasePart']);
        $('#formConSocE input[id="connectorSocForm_ActivePins"]').val(oldConSoc['ActivePins']);
        $('#formConSocE input[id="connectorSocForm_Type"]').val(oldConSoc['Type']);
    }
    $("#formConSocE input:not(:submit), #formConSocE select").attr('disabled', 'disabled');
    $('#SaveConSoc').remove();
    $('#CancelConSoc').next('div').remove();
    $('#CancelConSoc').remove();
    $("#EditConSoc").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveConGen(event) {
    var save = $(event.target).attr('id') == 'SaveConGen';
    var err = 0;
    if(save) {
        $data =  $("#formConGenE").serializeJSON();
        var val = $('#formConGenE input[id="connectorGenForm_Label"]').val();
        if ( val == "" ) {
            $("#formConGenE .submitMsg").remove();
            $("#formConGenE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#formConGenE input[id="connectorGenForm_ContactCnt"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formConGenE .submitMsg").remove();
            $("#formConGenE").append('<span class="submitMsg"> Vyplňte počet kontaktů (celé číslo) </span>');
            return;
        }
        val = $('#formConGenE input[id="connectorGenForm_CurrentContact"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formConGenE .submitMsg").remove();
            $("#formConGenE").append('<span class="submitMsg"> Vyplňte proud na kontakt (desetinné číslo) </span>');
            return;
        }
        val = $('#formConGenE input[id="connectorGenForm_MatingFactor"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formConGenE .submitMsg").remove();
            $("#formConGenE").append('<span class="submitMsg"> Vyplňte počet spoj/rozpoj (celé číslo) </span>');
            return;
        }
        val = $('#formConGenE input[id="connectorGenForm_PassiveTemp"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formConGenE .submitMsg").remove();
            $("#formConGenE").append('<span class="submitMsg"> Vyplňte pasivní teplotu (celé číslo) </span>');
            return;
        }

        $url = $("#formConGenE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "conGen"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formConGenE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formConGenE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $('#formConGenE input[id="connectorGenForm_Label"]').val(oldConGen['Label']);
        $('#formConGenE select[id="connectorGenForm_ConnectorType"]').val(oldConGen['ConnectorType']);
        $('#formConGenE select[id="connectorGenForm_Environment"]').val(oldConGen['Environment']);
        $('#formConGenE select[id="connectorGenForm_Quality"]').val(oldConGen['Quality']);
        $('#formConGenE input[id="connectorGenForm_CasePart"]').val(oldConGen['CasePart']);
        $('#formConGenE input[id="connectorGenForm_Type"]').val(oldConGen['Type']);
        $('#formConGenE input[id="connectorGenForm_ContactCnt"]').val(oldConGen['ContactCnt']);
        $('#formConGenE input[id="connectorGenForm_CurrentContact"]').val(oldConGen['CurrentContact']);
        $('#formConGenE input[id="connectorGenForm_MatingFactor"]').val(oldConGen['MatingFactor']);
        $('#formConGenE input[id="connectorGenForm_PassiveTemp"]').val(oldConGen['PassiveTemp']);
    }
    $("#formConGenE input:not(:submit), #formConGenE select").attr('disabled', 'disabled');
    $('#SaveConGen').remove();
    $('#CancelConGen').next('div').remove();
    $('#CancelConGen').remove();
    $("#EditConGen").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveSwitch(event) {
    var save = $(event.target).attr('id') == 'SaveSwitch';
    var err = 0;
    if(save) {
        $data =  $("#formSwitchE").serializeJSON();
        var val = $('#formSwitchE input[id="switchForm_Label"]').val();
        if ( val == "" ) {
            $("#formSwitchE .submitMsg").remove();
            $("#formSwitchE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#formSwitchE input[id="switchForm_ContactCnt"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formSwitchE .submitMsg").remove();
            $("#formSwitchE").append('<span class="submitMsg"> Vyplňte počet kontaktů (celé číslo) </span>');
            return;
        }
        val = $('#formSwitchE input[id="switchForm_OperatingCurrent"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formSwitchE .submitMsg").remove();
            $("#formSwitchE").append('<span class="submitMsg"> Vyplňte pracovní proud (desetinné číslo) </span>');
            return;
        }
        val = $('#formSwitchE input[id="switchForm_RatedResistiveCurrent"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formSwitchE .submitMsg").remove();
            $("#formSwitchE").append('<span class="submitMsg"> Vyplňte maximální proud (desetinné číslo) </span>');
            return;
        }

        $url = $("#formSwitchE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "switch"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formSwitchE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formSwitchE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $('#formSwitchE input[id="switchForm_Label"]').val(oldSwitch['Label']);
        $('#formSwitchE select[id="switchForm_SwitchType"]').val(oldSwitch['SwitchType']);
        $('#formSwitchE select[id="switchForm_Environment"]').val(oldSwitch['Environment']);
        $('#formSwitchE select[id="switchForm_Quality"]').val(oldSwitch['Quality']);
        $('#formSwitchE input[id="switchForm_CasePart"]').val(oldSwitch['CasePart']);
        $('#formSwitchE input[id="switchForm_Type"]').val(oldSwitch['Type']);
        $('#formSwitchE input[id="switchForm_ContactCnt"]').val(oldSwitch['ContactCnt']);
        $('#formSwitchE input[id="switchForm_OperatingCurrent"]').val(oldSwitch['OperatingCurrent']);
        $('#formSwitchE input[id="switchForm_RatedResistiveCurrent"]').val(oldSwitch['RatedResistiveCurrent']);
        $('#formSwitchE select[id="switchForm_LoadType"]').val(oldSwitch['LoadType']);
    }
    $("#formSwitchE input:not(:submit), #formSwitchE select").attr('disabled', 'disabled');
    $('#SaveSwitch').remove();
    $('#CancelSwitch').next('div').remove();
    $('#CancelSwitch').remove();
    $("#EditSwitch").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveFilter(event) {
    var save = $(event.target).attr('id') == 'SaveFilter';
    var err = 0;
    if(save) {
        $data =  $("#formFilterE").serializeJSON();
        var val = $('#formFilterE input[id="filterForm_Label"]').val();
        if ( val == "" ) {
            $("#formFilterE .submitMsg").remove();
            $("#formFilterE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }

        $url = $("#formFilterE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "filter"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formFilterE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formFilterE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $('#formFilterE input[id="filterForm_Label"]').val(oldFilter['Label']);
        $('#formFilterE input[id="filterForm_Type"]').val(oldFilter['Type']);
        $('#formFilterE select[id="filterForm_Quality"]').val(oldFilter['Quality']);
        $('#formFilterE select[id="filterForm_FilterType"]').val(oldFilter['FilterType']);
        $('#formFilterE select[id="filterForm_Environment"]').val(oldFilter['Environment']);
        $('#formFilterE input[id="filterForm_CasePart"]').val(oldFilter['CasePart']);
    }
    $("#formFilterE input:not(:submit), #formFilterE select").attr('disabled', 'disabled');
    $('#SaveFilter').remove();
    $('#CancelFilter').next('div').remove();
    $('#CancelFilter').remove();
    $("#EditFilter").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveRotElaps(event) {
    var save = $(event.target).attr('id') == 'SaveRotElaps';
    var err = 0;
    if(save) {
        $data =  $("#formRotElapsE").serializeJSON();
        var val = $('#formRotElapsE input[id="rotDevElapsForm_Label"]').val();
        if ( val == "" ) {
            $("#formRotElapsE .submitMsg").remove();
            $("#formRotElapsE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#formRotElapsE input[id="rotDevElapsForm_TempMax"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formRotElapsE .submitMsg").remove();
            $("#formRotElapsE").append('<span class="submitMsg"> Vyplňte maximální teplotu (celé číslo) </span>');
            return;
        }
        val = $('#formRotElapsE input[id="rotDevElapsForm_TempOperational"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formRotElapsE .submitMsg").remove();
            $("#formRotElapsE").append('<span class="submitMsg"> Vyplňte provozní teplotu (celé číslo) </span>');
            return;
        }

        $url = $("#formRotElapsE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "rotElaps"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formRotElapsE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formRotElapsE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formRotElapsE input[id="rotDevElapsForm_Label"]').val(oldRotElaps['Label']);
        $('#formRotElapsE input[id="rotDevElapsForm_Type"]').val(oldRotElaps['Type']);
        $('#formRotElapsE select[id="rotDevElapsForm_DevType"]').val(oldRotElaps['DevType']);
        $('#formRotElapsE input[id="rotDevElapsForm_TempMax"]').val(oldRotElaps['TempMax']);
        $('#formRotElapsE input[id="rotDevElapsForm_TempOperational"]').val(oldRotElaps['TempOperational']);
        $('#formRotElapsE select[id="rotDevElapsForm_Environment"]').val(oldRotElaps['Environment']);
        $('#formRotElapsE input[id="rotDevElapsForm_CasePart"]').val(oldRotElaps['CasePart']);
    }
    $("#formRotElapsE input:not(:submit), #formRotElapsE select").attr('disabled', 'disabled');
    $('#SaveRotElaps').remove();
    $('#CancelRotElaps').next('div').remove();
    $('#CancelRotElaps').remove();
    $("#EditRotElaps").show();
}
//----------------------------------------------------------------------------------------------------------------------

function saveTubeWave(event) {
    var save = $(event.target).attr('id') == 'SaveTubeWave';
    var err = 0;
    if(save) {
        $data =  $("#formTubeWaveE").serializeJSON();
        var val = $('#formTubeWaveE input[id="tubeWaveForm_Label"]').val();
        if ( val == "" ) {
            $("#formTubeWaveE .submitMsg").remove();
            $("#formTubeWaveE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#formTubeWaveE input[id="tubeWaveForm_Power"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#formTubeWaveE .submitMsg").remove();
            $("#formTubeWaveE").append('<span class="submitMsg"> Vyplňte výkon (celé číslo) </span>');
            return;
        }
        if ( val < 10 || val > 40000) {
            $("#formTubeWaveE .submitMsg").remove();
            $("#formTubeWaveE").append('<span class="submitMsg"> Výkon musí být v rozsahu <10-40000> </span>');
            return;
        }
        val = $('#formTubeWaveE input[id="tubeWaveForm_Frequency"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formTubeWaveE .submitMsg").remove();
            $("#formTubeWaveE").append('<span class="submitMsg"> Vyplňte frekvenci (desetinné číslo) </span>');
            return;
        }
        if ( val < 0.1 || val > 18) {
            $("#formTubeWaveE .submitMsg").remove();
            $("#formTubeWaveE").append('<span class="submitMsg"> Výkon musí být v rozsahu <0.1-18> </span>');
            return;
        }

        $url = $("#formTubeWaveE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "tubeWave"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formTubeWaveE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formTubeWaveE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formTubeWaveE input[id="tubeWaveForm_Label"]').val(oldTubeWave['Label']);
        $('#formTubeWaveE input[id="tubeWaveForm_Type"]').val(oldTubeWave['Type']);
        $('#formTubeWaveE input[id="tubeWaveForm_Power"]').val(oldTubeWave['Power']);
        $('#formTubeWaveE input[id="tubeWaveForm_Frequency"]').val(oldTubeWave['Frequency']);
        $('#formTubeWaveE select[id="tubeWaveForm_Environment"]').val(oldTubeWave['Environment']);
        $('#formTubeWaveE input[id="tubeWaveForm_CasePart"]').val(oldTubeWave['CasePart']);
    }
    $("#formTubeWaveE input:not(:submit), #formTubeWaveE select").attr('disabled', 'disabled');
    $('#SaveTubeWave').remove();
    $('#CancelTubeWave').next('div').remove();
    $('#CancelTubeWave').remove();
    $("#EditTubeWave").show();
}

//----------------------------------------------------------------------------------------------------------------------
function saveDiodeLF(event) {
    var save = $(event.target).attr('id') == 'SaveDiodeLF';
    var err = 0;
    if(save) {
        $data =  $("#formDiodeLFE").serializeJSON();
        var val = $('#formDiodeLFE input[id="diodeLFForm_Label"]').val();
        if ( val == "" ) {
            $("#formDiodeLFE .submitMsg").remove();
            $("#formDiodeLFE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formDiodeLFE input[id="diodeLFForm_VoltageRated"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formDiodeLFE .submitMsg").remove();
            $("#formDiodeLFE").append('<span class="submitMsg"> Vyplňte max. napětí (desetinné číslo) </span>');
            return;
        }
        var val = $('#formDiodeLFE input[id="diodeLFForm_VoltageApplied"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formDiodeLFE .submitMsg").remove();
            $("#formDiodeLFE").append('<span class="submitMsg"> Vyplňte provozní napětí (desetinné číslo) </span>');
            return;
        }

        $url = $("#formDiodeLFE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "diodeLF"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formDiodeLFE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formDiodeLFE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formDiodeLFE input[id="diodeLFForm_Label"]').val(oldDiodeLF['Label']);
        $('#formDiodeLFE input[id="diodeLFForm_Type"]').val(oldDiodeLF['Type']);
        $('#formDiodeLFE select[id="diodeLFForm_Application"]').val(oldDiodeLF['Application']);
        $('#formDiodeLFE select[id="diodeLFForm_Quality"]').val(oldDiodeLF['Quality']);
        $('#formDiodeLFE select[id="diodeLFForm_Environment"]').val(oldDiodeLF['Environment']);
        $('#formDiodeLFE input[id="diodeLFForm_CasePart"]').val(oldDiodeLF['CasePart']);
        $('#formDiodeLFE input[id="diodeLFForm_VoltageRated"]').val(oldDiodeLF['VoltageRated']);
        $('#formDiodeLFE input[id="diodeLFForm_VoltageApplied"]').val(oldDiodeLF['VoltageApplied']);
        $('#formDiodeLFE select[id="diodeLFForm_ContactConstruction"]').val(oldDiodeLF['ContactConstruction']);
        $('#formDiodeLFE input[id="diodeLFForm_DPTemp"]').val(oldDiodeLF['DPTemp']);
        $('#formDiodeLFE input[id="diodeLFForm_PassiveTemp"]').val(oldDiodeLF['PassiveTemp']);
    }
    $("#formDiodeLFE input:not(:submit), #formDiodeLFE select").attr('disabled', 'disabled');
    $('#SaveDiodeLF').remove();
    $('#CancelDiodeLF').next('div').remove();
    $('#CancelDiodeLF').remove();
    $("#EditDiodeLF").show();
}

//----------------------------------------------------------------------------------------------------------------------

function saveOpto(event) {
    var save = $(event.target).attr('id') == 'SaveOpto';
    var err = 0;
    if(save) {
        $data =  $("#formOptoE").serializeJSON();
        var val = $('#formOptoE input[id="optoForm_Label"]').val();
        if ( val == "" ) {
            $("#formOptoE .submitMsg").remove();
            $("#formOptoE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formOptoE input[id="optoForm_DPTemp"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formOptoE .submitMsg").remove();
            $("#formOptoE").append('<span class="submitMsg"> Vyplňte oteplení ztrátovým výkonem (desetinné číslo) </span>');
            return;
        }
        var val = $('#formOptoE input[id="optoForm_PassiveTemp"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formOptoE .submitMsg").remove();
            $("#formOptoE").append('<span class="submitMsg"> Vyplňte pasivní oteplení (desetinné číslo) </span>');
            return;
        }

        $url = $("#formOptoE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "optoelectronics"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formOptoE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formOptoE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formOptoE input[id="optoForm_Label"]').val(oldOpto['Label']);
        $('#formOptoE input[id="optoForm_Type"]').val(oldOpto['Type']);
        $('#formOptoE select[id="optoForm_Application"]').val(oldOpto['Application']);
        $('#formOptoE select[id="optoForm_Quality"]').val(oldOpto['Quality']);
        $('#formOptoE select[id="optoForm_Environment"]').val(oldOpto['Environment']);
        $('#formOptoE input[id="optoForm_CasePart"]').val(oldOpto['CasePart']);
        $('#formOptoE input[id="optoForm_DPTemp"]').val(oldOpto['DPTemp']);
        $('#formOptoE input[id="optoForm_PassiveTemp"]').val(oldOpto['PassiveTemp']);
    }
    $("#formOptoE input:not(:submit), #formOptoE select").attr('disabled', 'disabled');
    $('#SaveOpto').remove();
    $('#CancelOpto').next('div').remove();
    $('#CancelOpto').remove();
    $("#EditOpto").show();
}

//----------------------------------------------------------------------------------------------------------------------

function saveCrystal(event) {
    var save = $(event.target).attr('id') == 'SaveCrystal';
    var err = 0;
    if(save) {
        $data =  $("#formCrystalE").serializeJSON();
        var val = $('#formCrystalE input[id="crystalForm_Label"]').val();
        if ( val == "" ) {
            $("#formCrystalE .submitMsg").remove();
            $("#formCrystalE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formCrystalE input[id="crystalForm_Frequency"]').val();
        if ( val == "" || !($.isNumeric(val))) {
            $("#formCrystalE .submitMsg").remove();
            $("#formCrystalE").append('<span class="submitMsg"> Vyplňte frekvenci (kladné desetinné číslo) </span>');
            return;
        }

        $url = $("#formCrystalE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "crystal"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formCrystalE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formCrystalE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formCrystalE input[id="crystalForm_Label"]').val(oldCrystal['Label']);
        $('#formCrystalE input[id="crystalForm_Type"]').val(oldCrystal['Type']);
        $('#formCrystalE input[id="crystalForm_Frequency"]').val(oldCrystal['Frequency']);
        $('#formCrystalE select[id="crystalForm_Quality"]').val(oldCrystal['Quality']);
        $('#formCrystalE select[id="crystalForm_Environment"]').val(oldCrystal['Environment']);
        $('#formCrystalE input[id="crystalForm_CasePart"]').val(oldCrystal['CasePart']);
    }
    $("#formCrystalE input:not(:submit), #formCrystalE select").attr('disabled', 'disabled');
    $('#SaveCrystal').remove();
    $('#CancelCrystal').next('div').remove();
    $('#CancelCrystal').remove();
    $("#EditCrystal").show();
}

//----------------------------------------------------------------------------------------------------------------------

function saveTransistorBiLF(event) {
    var save = $(event.target).attr('id') == 'SaveTransistorBiLF';
    var err = 0;
    if(save) {
        $data =  $("#formTransistorBiLFE").serializeJSON();
        var val = $('#formTransistorBiLFE input[id="transistorBiLFForm_Label"]').val();
        if ( val == "" ) {
            $("#formTransistorBiLFE .submitMsg").remove();
            $("#formTransistorBiLFE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formTransistorBiLFE input[id="transistorBiLFForm_TempDissipation"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formTransistorBiLFE .submitMsg").remove();
            $("#formTransistorBiLFE").append('<span class="submitMsg"> Vyplňte oteplení ztrátovým výkonem (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formTransistorBiLFE input[id="transistorBiLFForm_TempPassive"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formTransistorBiLFE .submitMsg").remove();
            $("#formTransistorBiLFE").append('<span class="submitMsg"> Vyplňte pasivní oteplení (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formTransistorBiLFE input[id="transistorBiLFForm_PowerRated"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formTransistorBiLFE .submitMsg").remove();
            $("#formTransistorBiLFE").append('<span class="submitMsg"> Vyplňte jmenovitý výkon (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formTransistorBiLFE input[id="transistorBiLFForm_VoltageCE"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formTransistorBiLFE .submitMsg").remove();
            $("#formTransistorBiLFE").append('<span class="submitMsg"> Vyplňte napětí CE (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formTransistorBiLFE input[id="transistorBiLFForm_VoltageCEO"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formTransistorBiLFE .submitMsg").remove();
            $("#formTransistorBiLFE").append('<span class="submitMsg"> Vyplňte napětí CEO (kladné desetinné číslo) </span>');
            return;
        }

        $url = $("#formTransistorBiLFE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "transistorBiLF"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formTransistorBiLFE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formTransistorBiLFE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formTransistorBiLFE input[id="transistorBiLFForm_Label"]').val(oldTransistorBiLF['Label']);
        $('#formTransistorBiLFE input[id="transistorBiLFForm_Type"]').val(oldTransistorBiLF['Type']);
        $('#formTransistorBiLFE select[id="transistorBiLFForm_Application"]').val(oldTransistorBiLF['Application']);
        $('#formTransistorBiLFE select[id="transistorBiLFForm_Quality"]').val(oldTransistorBiLF['Quality']);
        $('#formTransistorBiLFE select[id="transistorBiLFForm_Environment"]').val(oldTransistorBiLF['Environment']);
        $('#formTransistorBiLFE input[id="transistorBiLFForm_CasePart"]').val(oldTransistorBiLF['CasePart']);
        $('#formTransistorBiLFE input[id="transistorBiLFForm_TempDissipation"]').val(oldTransistorBiLF['TempDissipation']);
        $('#formTransistorBiLFE input[id="transistorBiLFForm_TempPassive"]').val(oldTransistorBiLF['TempPassive']);
        $('#formTransistorBiLFE input[id="transistorBiLFForm_PowerRated"]').val(oldTransistorBiLF['PowerRated']);
        $('#formTransistorBiLFE input[id="transistorBiLFForm_VoltageCE"]').val(oldTransistorBiLF['VoltageCE']);
        $('#formTransistorBiLFE input[id="transistorBiLFForm_VoltageCEO"]').val(oldTransistorBiLF['VoltageCEO']);
    }
    $("#formTransistorBiLFE input:not(:submit), #formTransistorBiLFE select").attr('disabled', 'disabled');
    $('#SaveTransistorBiLF').remove();
    $('#CancelTransistorBiLF').next('div').remove();
    $('#CancelTransistorBiLF').remove();
    $("#EditTransistorBiLF").show();
}

//----------------------------------------------------------------------------------------------------------------------

function saveTransistorFetLF(event) {
    var save = $(event.target).attr('id') == 'SaveTransistorFetLF';
    var err = 0;
    if(save) {
        $data =  $("#formTransistorFetLFE").serializeJSON();
        var val = $('#formTransistorFetLFE input[id="transistorFetLFForm_Label"]').val();
        if ( val == "" ) {
            $("#formTransistorFetLFE .submitMsg").remove();
            $("#formTransistorFetLFE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formTransistorFetLFE input[id="transistorFetLFForm_TempDissipation"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formTransistorFetLFE .submitMsg").remove();
            $("#formTransistorFetLFE").append('<span class="submitMsg"> Vyplňte oteplení ztrátovým výkonem (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formTransistorFetLFE input[id="transistorFetLFForm_TempPassive"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formTransistorFetLFE .submitMsg").remove();
            $("#formTransistorFetLFE").append('<span class="submitMsg"> Vyplňte pasivní oteplení (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formTransistorFetLFE input[id="transistorFetLFForm_PowerRated"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formTransistorFetLFE .submitMsg").remove();
            $("#formTransistorFetLFE").append('<span class="submitMsg"> Vyplňte jmenovitý výkon (kladné desetinné číslo) </span>');
            return;
        }

        $url = $("#formTransistorFetLFE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "transistorFetLF"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formTransistorFetLFE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formTransistorFetLFE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formTransistorFetLFE input[id="transistorFetLFForm_Label"]').val(oldTransistorFetLF['Label']);
        $('#formTransistorFetLFE input[id="transistorFetLFForm_Type"]').val(oldTransistorFetLF['Type']);
        $('#formTransistorFetLFE select[id="transistorFetLFForm_Technology"]').val(oldTransistorFetLF['Technology']);
        $('#formTransistorFetLFE select[id="transistorFetLFForm_Application"]').val(oldTransistorFetLF['Application']);
        $('#formTransistorFetLFE select[id="transistorFetLFForm_Quality"]').val(oldTransistorFetLF['Quality']);
        $('#formTransistorFetLFE select[id="transistorFetLFForm_Environment"]').val(oldTransistorFetLF['Environment']);
        $('#formTransistorFetLFE input[id="transistorFetLFForm_CasePart"]').val(oldTransistorFetLF['CasePart']);
        $('#formTransistorFetLFE input[id="transistorFetLFForm_TempDissipation"]').val(oldTransistorFetLF['TempDissipation']);
        $('#formTransistorFetLFE input[id="transistorFetLFForm_TempPassive"]').val(oldTransistorFetLF['TempPassive']);
        $('#formTransistorFetLFE input[id="transistorFetLFForm_PowerRated"]').val(oldTransistorFetLF['PowerRated']);
    }
    $("#formTransistorFetLFE input:not(:submit), #formTransistorFetLFE select").attr('disabled', 'disabled');
    $('#SaveTransistorFetLF').remove();
    $('#CancelTransistorFetLF').next('div').remove();
    $('#CancelTransistorFetLF').remove();
    $("#EditTransistorFetLF").show();
}

//----------------------------------------------------------------------------------------------------------------------

function saveInductive(event) {
    var save = $(event.target).attr('id') == 'SaveInductive';
    var err = 0;
    if(save) {
        $data =  $("#formInductiveE").serializeJSON();
        var val = $('#formInductiveE input[id="InductiveForm_Label"]').val();
        if ( val == "" ) {
            $("#formInductiveE .submitMsg").remove();
            $("#formInductiveE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formInductiveE input[id="inductiveForm_TempDissipation"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formInductiveE .submitMsg").remove();
            $("#formInductiveE").append('<span class="submitMsg"> Vyplňte oteplení ztrátovým výkonem (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formInductiveE input[id="inductiveForm_TempPassive"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formInductiveE .submitMsg").remove();
            $("#formInductiveE").append('<span class="submitMsg"> Vyplňte pasivní oteplení (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formInductiveE input[id="inductiveForm_PowerLoss"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formInductiveE .submitMsg").remove();
            $("#formInductiveE").append('<span class="submitMsg"> Vyplňte ztrátový výkon (kladné desetinné číslo) </span>');
            return;
        }

        $url = $("#formInductiveE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "inductive"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formInductiveE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formInductiveE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formInductiveE input[id="inductiveForm_Label"]').val(oldInductive['Label']);
        $('#formInductiveE input[id="inductiveForm_Type"]').val(oldInductive['Type']);
        $('#formInductiveE select[id="inductiveForm_DevType"]').val(oldInductive['DevType']);
        $('#formInductiveE select[id="inductiveForm_Description"]').val(oldInductive['Description']);
        $('#formInductiveE select[id="inductiveForm_Quality"]').val(oldInductive['Quality']);
        $('#formInductiveE select[id="inductiveForm_Environment"]').val(oldInductive['Environment']);
        $('#formInductiveE input[id="inductiveForm_CasePart"]').val(oldInductive['CasePart']);
        $('#formInductiveE input[id="inductiveForm_TempDissipation"]').val(oldInductive['TempDissipation']);
        $('#formInductiveE input[id="inductiveForm_TempPassive"]').val(oldInductive['TempPassive']);
        $('#formInductiveE input[id="inductiveForm_PowerLoss"]').val(oldInductive['PowerLoss']);
        $('#formInductiveE input[id="inductiveForm_Surface"]').val(oldInductive['Surface']);
        $('#formInductiveE input[id="inductiveForm_Weight"]').val(oldInductive['Weight']);
    }
    $("#formInductiveE input:not(:submit), #formInductiveE select").attr('disabled', 'disabled');
    $('#SaveInductive').remove();
    $('#CancelInductive').next('div').remove();
    $('#CancelInductive').remove();
    $("#EditInductive").show();
}

//----------------------------------------------------------------------------------------------------------------------

function saveMicrocircuit(event) {
    var save = $(event.target).attr('id') == 'SaveMicrocircuit';
    var err = 0;
    if(save) {
        $data =  $("#formMicrocircuitE").serializeJSON();
        var val = $('#formMicrocircuitE input[id="microcircuitForm_Label"]').val();
        if ( val == "" ) {
            $("#formMicrocircuitE .submitMsg").remove();
            $("#formMicrocircuitE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formMicrocircuitE input[id="microcircuitForm_TempDissipation"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formMicrocircuitE .submitMsg").remove();
            $("#formMicrocircuitE").append('<span class="submitMsg"> Vyplňte oteplení ztrátovým výkonem (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formMicrocircuitE input[id="microcircuitForm_TempPassive"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formMicrocircuitE .submitMsg").remove();
            $("#formMicrocircuitE").append('<span class="submitMsg"> Vyplňte pasivní oteplení (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formMicrocircuitE input[id="microcircuitForm_PinCount"]').val();
        if ( val == "" || !($.isNumeric(val)) || Math.floor(val) != val || val < 0) {
            $("#formMicrocircuitE .submitMsg").remove();
            $("#formMicrocircuitE").append('<span class="submitMsg"> Vyplňte počet vývodů (kladné celé číslo) </span>');
            return;
        }
        var val = $('#formMicrocircuitE input[id="microcircuitForm_GateCount"]').val();
        if ( val == "" || !($.isNumeric(val)) || Math.floor(val) != val || val < 0) {
            $("#formMicrocircuitE .submitMsg").remove();
            $("#formMicrocircuitE").append('<span class="submitMsg"> Vyplňte počet hradel (kladné celé číslo) </span>');
            return;
        }
        var val = $('#formMicrocircuitE input[id="microcircuitForm_ProductionYears"]').val();
        if ( val == "" || !($.isNumeric(val)) || Math.floor(val) != val || val < 0) {
            $("#formMicrocircuitE .submitMsg").remove();
            $("#formMicrocircuitE").append('<span class="submitMsg"> Vyplňte dobu výroby (kladné celé číslo) </span>');
            return;
        }

        $url = $("#formMicrocircuitE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "microcircuit"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formMicrocircuitE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formMicrocircuitE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formMicrocircuitE input[id="microcircuitForm_Label"]').val(oldMicrocircuit['Label']);
        $('#formMicrocircuitE input[id="microcircuitForm_Type"]').val(oldMicrocircuit['Type']);
        $('#formMicrocircuitE select[id="microcircuitForm_Application"]').val(oldMicrocircuit['Application']);
        $('#formMicrocircuitE select[id="microcircuitForm_Description"]').val(oldMicrocircuit['Description']);
        $('#formMicrocircuitE select[id="microcircuitForm_Quality"]').val(oldMicrocircuit['Quality']);
        $('#formMicrocircuitE select[id="microcircuitForm_Environment"]').val(oldMicrocircuit['Environment']);
        $('#formMicrocircuitE select[id="microcircuitForm_Technology"]').val(oldMicrocircuit['Technology']);
        $('#formMicrocircuitE select[id="microcircuitForm_PackageType"]').val(oldMicrocircuit['PackageType']);
        $('#formMicrocircuitE input[id="microcircuitForm_CasePart"]').val(oldMicrocircuit['CasePart']);
        $('#formMicrocircuitE input[id="microcircuitForm_TempDissipation"]').val(oldMicrocircuit['TempDissipation']);
        $('#formMicrocircuitE input[id="microcircuitForm_TempPassive"]').val(oldMicrocircuit['TempPassive']);
        $('#formMicrocircuitE input[id="microcircuitForm_PinCount"]').val(oldMicrocircuit['PinCount']);
        $('#formMicrocircuitE input[id="microcircuitForm_GateCount"]').val(oldMicrocircuit['GateCount']);
        $('#formMicrocircuitE input[id="microcircuitForm_ProductionYears"]').val(oldMicrocircuit['ProductionYears']);
    }
    $("#formMicrocircuitE input:not(:submit), #formMicrocircuitE select").attr('disabled', 'disabled');
    $('#SaveMicrocircuit').remove();
    $('#CancelMicrocircuit').next('div').remove();
    $('#CancelMicrocircuit').remove();
    $("#EditMicrocircuit").show();
}

//----------------------------------------------------------------------------------------------------------------------

function saveDiodeRF(event) {
    var save = $(event.target).attr('id') == 'SaveDiodeRF';
    var err = 0;
    if(save) {
        $data =  $("#formDiodeRFE").serializeJSON();
        var val = $('#formDiodeRFE input[id="diodeRFForm_Label"]').val();
        if ( val == "" ) {
            $("#formDiodeRFE .submitMsg").remove();
            $("#formDiodeRFE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formDiodeRFE input[id="diodeRFForm_TempDissipation"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formDiodeRFE .submitMsg").remove();
            $("#formDiodeRFE").append('<span class="submitMsg"> Vyplňte oteplení ztrátovým výkonem (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formDiodeRFE input[id="diodeRFForm_TempPassive"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formDiodeRFE .submitMsg").remove();
            $("#formDiodeRFE").append('<span class="submitMsg"> Vyplňte pasivní oteplení (kladné desetinné číslo) </span>');
            return;
        }


        $url = $("#formDiodeRFE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "diodeRF"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formDiodeRFE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formDiodeRFE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formDiodeRFE input[id="diodeRFForm_Label"]').val(oldDiodeRF['Label']);
        $('#formDiodeRFE input[id="diodeRFForm_Type"]').val(oldDiodeRF['Type']);
        $('#formDiodeRFE select[id="diodeRFForm_Application"]').val(oldDiodeRF['Application']);
        $('#formDiodeRFE select[id="diodeRFForm_Quality"]').val(oldDiodeRF['Quality']);
        $('#formDiodeRFE select[id="diodeRFForm_Environment"]').val(oldDiodeRF['Environment']);
        $('#formDiodeRFE select[id="diodeRFForm_DiodeType"]').val(oldDiodeRF['DiodeType']);
        $('#formDiodeRFE input[id="diodeRFForm_CasePart"]').val(oldDiodeRF['CasePart']);
        $('#formDiodeRFE input[id="diodeRFForm_TempDissipation"]').val(oldDiodeRF['TempDissipation']);
        $('#formDiodeRFE input[id="diodeRFForm_TempPassive"]').val(oldDiodeRF['TempPassive']);
        $('#formDiodeRFE input[id="diodeRFForm_PowerRated"]').val(oldDiodeRF['PowerRated']);

    }
    $("#formDiodeRFE input:not(:submit), #formDiodeRFE select").attr('disabled', 'disabled');
    $('#SaveDiodeRF').remove();
    $('#CancelDiodeRF').next('div').remove();
    $('#CancelDiodeRF').remove();
    $("#EditDiodeRF").show();
}
//----------------------------------------------------------------------------------------------------------------------
function disableEEPROM () {
    $("#memoryForm_ECC").attr('disabled', 'disabled');
    $("#memoryForm_EepromOxid").attr('disabled', 'disabled');
    $("#memoryForm_CyclesCount").attr('disabled', 'disabled');
}
function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}
function saveMemory(event) {
    var save = $(event.target).attr('id') == 'SaveMemory';
    var err = 0;
    if(save) {
        $data =  $("#formMemoryE").serializeJSON();
        var val = $('#formMemoryE input[id="memoryForm_Label"]').val();
        if ( val == "" ) {
            $("#formMemoryE .submitMsg").remove();
            $("#formMemoryE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        var val = $('#formMemoryE input[id="memoryForm_TempDissipation"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formMemoryE .submitMsg").remove();
            $("#formMemoryE").append('<span class="submitMsg"> Vyplňte oteplení ztrátovým výkonem (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formMemoryE input[id="memoryForm_TempPassive"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formMemoryE .submitMsg").remove();
            $("#formMemoryE").append('<span class="submitMsg"> Vyplňte pasivní oteplení (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formMemoryE input[id="memoryForm_MemorySize"]').val();
        if ( val == "" || !($.isNumeric(val)) || val < 0) {
            $("#formMemoryE .submitMsg").remove();
            $("#formMemoryE").append('<span class="submitMsg"> Vyplňte velikost paměti (kladné desetinné číslo) </span>');
            return;
        }
        var val = $('#formMemoryE input[id="memoryForm_PinCount"]').val();
        if ( val == "" || !($.isNumeric(val)) || Math.floor(val) != val || val < 0) {
            $("#formMemoryE .submitMsg").remove();
            $("#formMemoryE").append('<span class="submitMsg"> Vyplňte počet vývodů (kladné celé číslo) </span>');
            return;
        }
        var val = $('#formMemoryE input[id="memoryForm_CyclesCount"]').val();
        var memType = $("#memoryForm_MemoryType").val();
        if (memType == "EEPROM" && (val == "" || !($.isNumeric(val)) || Math.floor(val) != val || val < 0)) {
            $("#formMemoryE .submitMsg").remove();
            $("#formMemoryE").append('<span class="submitMsg"> Vyplňte počet zápisů (kladné celé číslo) </span>');
            return;
        }
        var val = $('#formMemoryE input[id="memoryForm_ProductionYears"]').val();
        if ( val == "" || !($.isNumeric(val)) || Math.floor(val) != val || val < 0) {
            $("#formMemoryE .submitMsg").remove();
            $("#formMemoryE").append('<span class="submitMsg"> Vyplňte dobu výroby (kladné celé číslo) </span>');
            return;
        }

        $url = $("#formMemoryE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: "memory"},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#formMemoryE").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                $("#lamPart").text(Number(data.Lam).toExponential(3));
                $("#labelPart").text(data.Label);
            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#formMemoryE").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $(".submitMsg").remove();
        $('#formMemoryE input[id="memoryForm_Label"]').val(oldMemory['Label']);
        $('#formMemoryE input[id="memoryForm_Type"]').val(oldMemory['Type']);
        $('#formMemoryE select[id="memoryForm_Quality"]').val(oldMemory['Quality']);
        $('#formMemoryE select[id="memoryForm_Environment"]').val(oldMemory['Environment']);
        $('#formMemoryE select[id="memoryForm_PackageType"]').val(oldMemory['PackageType']);
        $('#formMemoryE input[id="memoryForm_CasePart"]').val(oldMemory['CasePart']);
        $('#formMemoryE input[id="memoryForm_TempDissipation"]').val(oldMemory['TempDissipation']);
        $('#formMemoryE input[id="memoryForm_TempPassive"]').val(oldMemory['TempPassive']);
        $('#formMemoryE input[id="memoryForm_PinCount"]').val(oldMemory['PinCount']);
        $('#formMemoryE input[id="memoryForm_CyclesCount"]').val(oldMemory['CyclesCount']);
        $('#formMemoryE input[id="memoryForm_ProductionYears"]').val(oldMemory['ProductionYears']);
        $('#formMemoryE input[id="memoryForm_MemorySize"]').val(oldMemory['MemorySize']);
        $('#formMemoryE select[id="memoryForm_EepromOxid"]').val(oldMemory['EepromOxid']);
        $('#formMemoryE select[id="memoryForm_ECC"]').val(oldMemory['ECC']);

        var newMemoryDesc = $('#formMemoryE select[id="memoryForm_MemoryType"]').val();
        if(oldMemory['Description'] != newMemoryDesc) {
            $("#memoryForm_MemoryType").empty();
            if(oldMemory['Description'] == 'MOS') {
                $("#memoryForm_MemoryType").append(decodeHtml(memoryMosChoices));
            }
            else {
                $("#memoryForm_MemoryType").append(decodeHtml(memoryBipolarChoices));
            }
        }

        $('#formMemoryE select[id="memoryForm_MemoryType"]').val(oldMemory['MemoryType']);
        $('#formMemoryE select[id="memoryForm_Description"]').val(oldMemory['Description']);
    }
    $("#formMemoryE input:not(:submit), #formMemoryE select").attr('disabled', 'disabled');
    $('#SaveMemory').remove();
    $('#CancelMemory').next('div').remove();
    $('#CancelMemory').remove();
    $("#EditMemory").show();
}



//----------------------------------------------------------------------------------------------------------------------
jQuery(document).ready(function($) {

    $("#EditRes").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formResE input:not(:submit), #formResE select").removeAttr('disabled');
        $this = $(this);
        $("#EditRes").hide();
        oldRes['Label'] = $('#formResE input[id="resistorForm_Label"]').val();
        oldRes['Type'] = $('#formResE input[id="resistorForm_Type"]').val();
        oldRes['Value'] = $('#formResE input[id="resistorForm_Value"]').val();
        oldRes['Quality'] = $('#formResE select[id="resistorForm_Quality"]').val();
        oldRes['Material'] = $('#formResE select[id="resistorForm_Material"]').val();
        oldRes['Environment'] = ($('#formResE select[id="resistorForm_Environment"]').val());
        oldRes['MaxPower'] = $('#formResE input[id="resistorForm_MaxPower"]').val();
        oldRes['VoltageOperational'] = $('#formResE input[id="resistorForm_VoltageOperational"]').val();
        oldRes['CurrentOperational'] = $('#formResE input[id="resistorForm_CurrentOperational"]').val();
        oldRes['DissipationPower'] = $('#formResE input[id="resistorForm_DissipationPower"]').val();
        oldRes['DPTemp'] = $('#formResE input[id="resistorForm_DPTemp"]').val();
        oldRes['PassiveTemp'] = $('#formResE input[id="resistorForm_PassiveTemp"]').val();
        oldRes['Alternate'] = $('#formResE input[id="resistorForm_Alternate"]').val();
        oldRes['CasePart'] = $('#formResE input[id="resistorForm_CasePart"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveRes')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveRes)
        ;
        $("#formResE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelRes')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveRes)
        ;
        $("#formResE .submitHandle").append(cancel);

        $("#formResE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditFuse").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formFuseE input:not(:submit), #formFuseE select").removeAttr('disabled');
        $this = $(this);
        $("#EditFuse").hide();
        oldFuse['Label'] = $('#formFuseE input[id="fuseForm_Label"]').val();
        oldFuse['Type'] = $('#formFuseE input[id="fuseForm_Type"]').val();
        oldFuse['Value'] = $('#formFuseE input[id="fuseForm_Value"]').val();
        oldFuse['Environment'] = ($('#formFuseE select[id="fuseForm_Environment"]').val());
        oldFuse['CasePart'] = $('#formFuseE input[id="fuseForm_CasePart"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveFuse')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveFuse)
        ;
        $("#formFuseE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelFuse')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveFuse)
        ;
        $("#formFuseE .submitHandle").append(cancel);

        $("#formFuseE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditConnection").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formConnectionE input:not(:submit), #formConnectionE select").removeAttr('disabled');
        $this = $(this);
        $("#EditConnection").hide();
        oldConnection['Label'] = $('#formConnectionE input[id="connectionForm_Label"]').val();
        oldConnection['ConnectionType'] = $('#formConnectionE select[id="connectionForm_ConnectionType"]').val();
        oldConnection['Environment'] = ($('#formConnectionE select[id="connectionForm_Environment"]').val());
        oldConnection['CasePart'] = $('#formConnectionE input[id="connectionForm_CasePart"]').val();
        oldConnection['Type'] = $('#formConnectionE input[id="connectionForm_Type"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveConnection')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveConnection)
        ;
        $("#formConnectionE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelConnection')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveConnection)
        ;
        $("#formConnectionE .submitHandle").append(cancel);

        $("#formConnectionE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditConSoc").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formConSocE input:not(:submit), #formConSocE select").removeAttr('disabled');
        $this = $(this);
        $("#EditConSoc").hide();
        oldConSoc['Label'] = $('#formConSocE input[id="connectorSocForm_Label"]').val();
        oldConSoc['ConnectorType'] = $('#formConSocE select[id="connectorSocForm_ConnectorType"]').val();
        oldConSoc['Environment'] = ($('#formConSocE select[id="connectorSocForm_Environment"]').val());
        oldConSoc['Quality'] = ($('#formConSocE select[id="connectorSocForm_Quality"]').val());
        oldConSoc['CasePart'] = $('#formConSocE input[id="connectorSocForm_CasePart"]').val();
        oldConSoc['ActivePins'] = $('#formConSocE input[id="connectorSocForm_ActivePins"]').val();
        oldConSoc['Type'] = $('#formConSocE input[id="connectorSocForm_Type"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveConSoc')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveConSoc)
        ;
        $("#formConSocE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelConSoc')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveConSoc)
        ;
        $("#formConSocE .submitHandle").append(cancel);

        $("#formConSocE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditConGen").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formConGenE input:not(:submit), #formConGenE select").removeAttr('disabled');
        $this = $(this);
        $("#EditConGen").hide();
        oldConGen['Label'] = $('#formConGenE input[id="connectorGenForm_Label"]').val();
        oldConGen['ConnectorType'] = $('#formConGenE select[id="connectorGenForm_ConnectorType"]').val();
        oldConGen['Environment'] = ($('#formConGenE select[id="connectorGenForm_Environment"]').val());
        oldConGen['Quality'] = ($('#formConGenE select[id="connectorGenForm_Quality"]').val());
        oldConGen['Type'] = ($('#formConGenE input[id="connectorGenForm_Type"]').val());
        oldConGen['CasePart'] = $('#formConGenE input[id="connectorGenForm_CasePart"]').val();
        oldConGen['ContactCnt'] = $('#formConGenE input[id="connectorGenForm_ContactCnt"]').val();
        oldConGen['CurrentContact'] = ($('#formConGenE input[id="connectorGenForm_CurrentContact"]').val());
        oldConGen['MatingFactor'] = ($('#formConGenE input[id="connectorGenForm_MatingFactor"]').val());
        oldConGen['PassiveTemp'] = ($('#formConGenE input[id="connectorGenForm_PassiveTemp"]').val());

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveConGen')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveConGen)
        ;
        $("#formConGenE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelConGen')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveConGen)
        ;
        $("#formConGenE .submitHandle").append(cancel);

        $("#formConGenE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditSwitch").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formSwitchE input:not(:submit), #formSwitchE select").removeAttr('disabled');
        $this = $(this);
        $("#EditSwitch").hide();
        oldSwitch['Label'] = $('#formSwitchE input[id="switchForm_Label"]').val();
        oldSwitch['SwitchType'] = $('#formSwitchE select[id="switchForm_SwitchType"]').val();
        oldSwitch['Environment'] = ($('#formSwitchE select[id="switchForm_Environment"]').val());
        oldSwitch['Quality'] = ($('#formSwitchE select[id="switchForm_Quality"]').val());
        oldSwitch['Type'] = ($('#formSwitchE select[id="switchForm_Type"]').val());
        oldSwitch['CasePart'] = $('#formSwitchE input[id="switchForm_CasePart"]').val();
        oldSwitch['ContactCnt'] = $('#formSwitchE input[id="switchForm_ContactCnt"]').val();
        oldSwitch['LoadType'] = ($('#formSwitchE select[id="switchForm_LoadType"]').val());
        oldSwitch['OperatingCurrent'] = ($('#formSwitchE input[id="switchForm_OperatingCurrent"]').val());
        oldSwitch['RatedResistiveCurrent'] = ($('#formSwitchE input[id="switchForm_RatedResistiveCurrent"]').val());

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveSwitch')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveSwitch)
        ;
        $("#formSwitchE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelSwitch')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveSwitch)
        ;
        $("#formSwitchE .submitHandle").append(cancel);

        $("#formSwitchE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditFilter").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formFilterE input:not(:submit), #formFilterE select").removeAttr('disabled');
        $this = $(this);
        $("#EditFilter").hide();
        oldFilter['Label'] = $('#formFilterE input[id="filterForm_Label"]').val();
        oldFilter['Type'] = $('#formFilterE input[id="filterForm_Type"]').val();
        oldFilter['FilterType'] = $('#formFilterE select[id="filterForm_FilterType"]').val();
        oldFilter['Quality'] = $('#formFilterE select[id="filterForm_Quality"]').val();
        oldFilter['Environment'] = ($('#formFilterE select[id="filterForm_Environment"]').val());
        oldFilter['CasePart'] = $('#formFilterE input[id="filterForm_CasePart"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveFilter')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveFilter)
        ;
        $("#formFilterE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelFilter')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveFilter)
        ;
        $("#formFilterE .submitHandle").append(cancel);

        $("#formFilterE .submitHandle").append('<div class="cleaner"></div>');

    });

//----------------------------------------------------------------------------------------------------------------------

    $("#EditRotElaps").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formRotElapsE input:not(:submit), #formRotElapsE select").removeAttr('disabled');
        $this = $(this);
        $("#EditRotElaps").hide();
        oldRotElaps['Label'] = $('#formRotElapsE input[id="rotDevElapsForm_Label"]').val();
        oldRotElaps['Type'] = $('#formRotElapsE input[id="rotDevElapsForm_Type"]').val();
        oldRotElaps['DevType'] = $('#formRotElapsE select[id="rotDevElapsForm_DevType"]').val();
        oldRotElaps['TempMax'] = $('#formRotElapsE input[id="rotDevElapsForm_TempMax"]').val();
        oldRotElaps['TempOperational'] = $('#formRotElapsE input[id="rotDevElapsForm_TempOperational"]').val();
        oldRotElaps['Environment'] = ($('#formRotElapsE select[id="rotDevElapsForm_Environment"]').val());
        oldRotElaps['CasePart'] = $('#formRotElapsE input[id="rotDevElapsForm_CasePart"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveRotElaps')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveRotElaps)
        ;
        $("#formRotElapsE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelRotElaps')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveRotElaps)
        ;
        $("#formRotElapsE .submitHandle").append(cancel);

        $("#formRotElapsE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditTubeWave").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formTubeWaveE input:not(:submit), #formTubeWaveE select").removeAttr('disabled');
        $this = $(this);
        $("#EditTubeWave").hide();
        oldTubeWave['Label'] = $('#formTubeWaveE input[id="tubeWaveForm_Label"]').val();
        oldTubeWave['Type'] = $('#formTubeWaveE input[id="tubeWaveForm_Type"]').val();
        oldTubeWave['Power'] = $('#formTubeWaveE input[id="tubeWaveForm_Power"]').val();
        oldTubeWave['Frequency'] = $('#formTubeWaveE input[id="tubeWaveForm_Frequency"]').val();
        oldTubeWave['Environment'] = ($('#formTubeWaveE select[id="tubeWaveForm_Environment"]').val());
        oldTubeWave['CasePart'] = $('#formTubeWaveE input[id="tubeWaveForm_CasePart"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveTubeWave')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveTubeWave)
        ;
        $("#formTubeWaveE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelTubeWave')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveTubeWave)
        ;
        $("#formTubeWaveE .submitHandle").append(cancel);

        $("#formTubeWaveE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditDiodeLF").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formDiodeLFE input:not(:submit), #formDiodeLFE select").removeAttr('disabled');
        $this = $(this);
        $("#EditDiodeLF").hide();
        oldDiodeLF['Label'] = $('#formDiodeLFE input[id="diodeLFForm_Label"]').val();
        oldDiodeLF['Type'] = $('#formDiodeLFE input[id="diodeLFForm_Type"]').val();
        oldDiodeLF['Quality'] = $('#formDiodeLFE select[id="diodeLFForm_Quality"]').val();
        oldDiodeLF['Application'] = $('#formDiodeLFE select[id="diodeLFForm_Application"]').val();
        oldDiodeLF['Environment'] = ($('#formDiodeLFE select[id="diodeLFForm_Environment"]').val());
        oldDiodeLF['CasePart'] = $('#formDiodeLFE input[id="diodeLFForm_CasePart"]').val();
        oldDiodeLF['VoltageRated'] = $('#formDiodeLFE input[id="diodeLFForm_VoltageRated"]').val();
        oldDiodeLF['VoltageApplied'] = $('#formDiodeLFE input[id="diodeLFForm_VoltageApplied"]').val();
        oldDiodeLF['ContactConstruction'] = $('#formDiodeLFE select[id="diodeLFForm_ContactConstruction"]').val();
        oldDiodeLF['DPTemp'] = $('#formDiodeLFE input[id="diodeLFForm_DPTemp"]').val();
        oldDiodeLF['PassiveTemp'] = $('#formDiodeLFE input[id="diodeLFForm_PassiveTemp"]').val();


        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveDiodeLF')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveDiodeLF)
        ;
        $("#formDiodeLFE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelDiodeLF')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveDiodeLF)
        ;
        $("#formDiodeLFE .submitHandle").append(cancel);

        $("#formDiodeLFE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditCap").click(function(e) {
        e.preventDefault();
        $("#formCapE input:not(:submit), #formCapE select").removeAttr('disabled');
        $this = $(this);
        $("#EditCap").hide();
        oldCap['Label'] = $('#formCapE input[id="capacitorForm_Label"]').val();
        oldCap['Type'] = $('#formCapE input[id="capacitorForm_Type"]').val();
        oldCap['Value'] = $('#formCapE input[id="capacitorForm_Value"]').val();
        oldCap['Quality'] = $('#formCapE select[id="capacitorForm_Quality"]').val();
        oldCap['Material'] = $('#formCapE select[id="capacitorForm_Material"]').val();
        oldCap['Environment'] = ($('#formCapE select[id="capacitorForm_Environment"]').val());
        oldCap['CasePart'] = $('#formCapE input[id="capacitorForm_CasePart"]').val();
        oldCap['PassiveTemp'] = $('#formCapE input[id="capacitorForm_PassiveTemp"]').val();
        oldCap['VoltageMax'] = $('#formCapE input[id="capacitorForm_VoltageMax"]').val();
        oldCap['VoltageOperational'] = $('#formCapE input[id="capacitorForm_VoltageOperational"]').val();
        oldCap['VoltageAC'] = $('#formCapE input[id="capacitorForm_VoltageAC"]').val();
        oldCap['VoltageDC'] = $('#formCapE input[id="capacitorForm_VoltageDC"]').val();
        oldCap['SerialResistor'] = $('#formCapE input[id="capacitorForm_SerialResistor"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveCap')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveCap)
        ;
        $("#formCapE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelCap')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveCap)
        ;
        $("#formCapE .submitHandle").append(cancel);

        $("#formCapE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditOpto").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formOptoE input:not(:submit), #formOptoE select").removeAttr('disabled');
        $this = $(this);
        $("#EditOpto").hide();
        oldOpto['Label'] = $('#formOptoE input[id="optoForm_Label"]').val();
        oldOpto['Type'] = $('#formOptoE input[id="optoForm_Type"]').val();
        oldOpto['Quality'] = $('#formOptoE select[id="optoForm_Quality"]').val();
        oldOpto['Application'] = $('#formOptoE select[id="optoForm_Application"]').val();
        oldOpto['Environment'] = ($('#formOptoE select[id="optoForm_Environment"]').val());
        oldOpto['CasePart'] = $('#formOptoE input[id="optoForm_CasePart"]').val();
        oldOpto['DPTemp'] = $('#formOptoE input[id="optoForm_DPTemp"]').val();
        oldOpto['PassiveTemp'] = $('#formOptoE input[id="optoForm_PassiveTemp"]').val();


        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveOpto')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveOpto)
        ;
        $("#formOptoE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelOpto')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveOpto)
        ;
        $("#formOptoE .submitHandle").append(cancel);

        $("#formOptoE .submitHandle").append('<div class="cleaner"></div>');

    });

    //----------------------------------------------------------------------------------------------------------------------

    $("#EditCrystal").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formCrystalE input:not(:submit), #formCrystalE select").removeAttr('disabled');
        $this = $(this);
        $("#EditCrystal").hide();
        oldCrystal['Label'] = $('#formCrystalE input[id="crystalForm_Label"]').val();
        oldCrystal['Type'] = $('#formCrystalE input[id="crystalForm_Type"]').val();
        oldCrystal['Quality'] = $('#formCrystalE select[id="crystalForm_Quality"]').val();
        oldCrystal['Frequency'] = $('#formCrystalE input[id="crystalForm_Frequency"]').val();
        oldCrystal['Environment'] = ($('#formCrystalE select[id="crystalForm_Environment"]').val());
        oldCrystal['CasePart'] = $('#formCrystalE input[id="crystalForm_CasePart"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveCrystal')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveCrystal)
        ;
        $("#formCrystalE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelCrystal')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveCrystal)
        ;
        $("#formCrystalE .submitHandle").append(cancel);

        $("#formCrystalE .submitHandle").append('<div class="cleaner"></div>');

    });
//----------------------------------------------------------------------------------------------------------------------

    $("#EditTransistorBiLF").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formTransistorBiLFE input:not(:submit), #formTransistorBiLFE select").removeAttr('disabled');
        $this = $(this);
        $("#EditTransistorBiLF").hide();
        oldTransistorBiLF['Label'] = $('#formTransistorBiLFE input[id="transistorBiLFForm_Label"]').val();
        oldTransistorBiLF['Type'] = $('#formTransistorBiLFE input[id="transistorBiLFForm_Type"]').val();
        oldTransistorBiLF['Quality'] = $('#formTransistorBiLFE select[id="transistorBiLFForm_Quality"]').val();
        oldTransistorBiLF['Application'] = $('#formTransistorBiLFE select[id="transistorBiLFForm_Application"]').val();
        oldTransistorBiLF['Environment'] = ($('#formTransistorBiLFE select[id="transistorBiLFForm_Environment"]').val());
        oldTransistorBiLF['CasePart'] = $('#formTransistorBiLFE input[id="transistorBiLFForm_CasePart"]').val();
        oldTransistorBiLF['TempDissipation'] = $('#formTransistorBiLFE input[id="transistorBiLFForm_TempDissipation"]').val();
        oldTransistorBiLF['TempPassive'] = $('#formTransistorBiLFE input[id="transistorBiLFForm_TempPassive"]').val();
        oldTransistorBiLF['PowerRated'] = $('#formTransistorBiLFE input[id="transistorBiLFForm_PowerRated"]').val();
        oldTransistorBiLF['VoltageCE'] = $('#formTransistorBiLFE input[id="transistorBiLFForm_VoltageCE"]').val();
        oldTransistorBiLF['VoltageCEO'] = $('#formTransistorBiLFE input[id="transistorBiLFForm_VoltageCEO"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveTransistorBiLF')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveTransistorBiLF)
        ;
        $("#formTransistorBiLFE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelTransistorBiLF')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveTransistorBiLF)
        ;
        $("#formTransistorBiLFE .submitHandle").append(cancel);

        $("#formTransistorBiLFE .submitHandle").append('<div class="cleaner"></div>');

    });

    //----------------------------------------------------------------------------------------------------------------------

    $("#EditTransistorFetLF").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formTransistorFetLFE input:not(:submit), #formTransistorFetLFE select").removeAttr('disabled');
        $this = $(this);
        $("#EditTransistorFetLF").hide();
        oldTransistorFetLF['Label'] = $('#formTransistorFetLFE input[id="transistorFetLFForm_Label"]').val();
        oldTransistorFetLF['Type'] = $('#formTransistorFetLFE input[id="transistorFetLFForm_Type"]').val();
        oldTransistorFetLF['Technology'] = $('#formTransistorFetLFE select[id="transistorFetLFForm_Technology"]').val();
        oldTransistorFetLF['Quality'] = $('#formTransistorFetLFE select[id="transistorFetLFForm_Quality"]').val();
        oldTransistorFetLF['Application'] = $('#formTransistorFetLFE select[id="transistorFetLFForm_Application"]').val();
        oldTransistorFetLF['Environment'] = ($('#formTransistorFetLFE select[id="transistorFetLFForm_Environment"]').val());
        oldTransistorFetLF['CasePart'] = $('#formTransistorFetLFE input[id="transistorFetLFForm_CasePart"]').val();
        oldTransistorFetLF['TempDissipation'] = $('#formTransistorFetLFE input[id="transistorFetLFForm_TempDissipation"]').val();
        oldTransistorFetLF['TempPassive'] = $('#formTransistorFetLFE input[id="transistorFetLFForm_TempPassive"]').val();
        oldTransistorFetLF['PowerRated'] = $('#formTransistorFetLFE input[id="transistorFetLFForm_PowerRated"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveTransistorFetLF')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveTransistorFetLF)
        ;
        $("#formTransistorFetLFE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelTransistorFetLF')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveTransistorFetLF)
        ;
        $("#formTransistorFetLFE .submitHandle").append(cancel);

        $("#formTransistorFetLFE .submitHandle").append('<div class="cleaner"></div>');

    });

    //----------------------------------------------------------------------------------------------------------------------

    $("#EditInductive").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formInductiveE input:not(:submit), #formInductiveE select").removeAttr('disabled');
        $this = $(this);
        $("#EditInductive").hide();
        oldInductive['Label'] = $('#formInductiveE input[id="inductiveForm_Label"]').val();
        oldInductive['Type'] = $('#formInductiveE input[id="inductiveForm_Type"]').val();
        oldInductive['DevType'] = $('#formInductiveE select[id="inductiveForm_DevType"]').val();
        oldInductive['Quality'] = $('#formInductiveE select[id="inductiveForm_Quality"]').val();
        oldInductive['Description'] = $('#formInductiveE select[id="inductiveForm_Description"]').val();
        oldInductive['Environment'] = ($('#formInductiveE select[id="inductiveForm_Environment"]').val());
        oldInductive['CasePart'] = $('#formInductiveE input[id="inductiveForm_CasePart"]').val();
        oldInductive['TempDissipation'] = $('#formInductiveE input[id="inductiveForm_TempDissipation"]').val();
        oldInductive['TempPassive'] = $('#formInductiveE input[id="inductiveForm_TempPassive"]').val();
        oldInductive['PowerLoss'] = $('#formInductiveE input[id="inductiveForm_PowerLoss"]').val();
        oldInductive['Surface'] = $('#formInductiveE input[id="inductiveForm_Surface"]').val();
        oldInductive['Weight'] = $('#formInductiveE input[id="inductiveForm_Weight"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveInductive')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveInductive)
        ;
        $("#formInductiveE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelInductive')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveInductive)
        ;
        $("#formInductiveE .submitHandle").append(cancel);

        $("#formInductiveE .submitHandle").append('<div class="cleaner"></div>');

    })

//----------------------------------------------------------------------------------------------------------------------

    $("#EditMicrocircuit").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formMicrocircuitE input:not(:submit), #formMicrocircuitE select").removeAttr('disabled');
        $this = $(this);
        $("#EditMicrocircuit").hide();
        oldMicrocircuit['Label'] = $('#formMicrocircuitE input[id="microcircuitForm_Label"]').val();
        oldMicrocircuit['Type'] = $('#formMicrocircuitE input[id="microcircuitForm_Type"]').val();
        oldMicrocircuit['Application'] = $('#formMicrocircuitE select[id="microcircuitForm_Application"]').val();
        oldMicrocircuit['Quality'] = $('#formMicrocircuitE select[id="microcircuitForm_Quality"]').val();
        oldMicrocircuit['Description'] = $('#formMicrocircuitE select[id="microcircuitForm_Description"]').val();
        oldMicrocircuit['Environment'] = ($('#formMicrocircuitE select[id="microcircuitForm_Environment"]').val());
        oldMicrocircuit['Technology'] = ($('#formMicrocircuitE select[id="microcircuitForm_Technology"]').val());
        oldMicrocircuit['PackageType'] = ($('#formMicrocircuitE select[id="microcircuitForm_PackageType"]').val());
        oldMicrocircuit['CasePart'] = $('#formMicrocircuitE input[id="microcircuitForm_CasePart"]').val();
        oldMicrocircuit['TempDissipation'] = $('#formMicrocircuitE input[id="microcircuitForm_TempDissipation"]').val();
        oldMicrocircuit['TempPassive'] = $('#formMicrocircuitE input[id="microcircuitForm_TempPassive"]').val();
        oldMicrocircuit['PinCount'] = $('#formMicrocircuitE input[id="microcircuitForm_PinCount"]').val();
        oldMicrocircuit['GateCount'] = $('#formMicrocircuitE input[id="microcircuitForm_GateCount"]').val();
        oldMicrocircuit['ProductionYears'] = $('#formMicrocircuitE input[id="microcircuitForm_ProductionYears"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveMicrocircuit')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveMicrocircuit)
        ;
        $("#formMicrocircuitE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelMicrocircuit')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveMicrocircuit)
        ;
        $("#formMicrocircuitE .submitHandle").append(cancel);

        $("#formMicrocircuitE .submitHandle").append('<div class="cleaner"></div>');

    });

//----------------------------------------------------------------------------------------------------------------------

    $("#EditDiodeRF").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formDiodeRFE input:not(:submit), #formDiodeRFE select").removeAttr('disabled');
        $this = $(this);
        $("#EditDiodeRF").hide();
        oldDiodeRF['Label'] = $('#formDiodeRFE input[id="diodeRFForm_Label"]').val();
        oldDiodeRF['Type'] = $('#formDiodeRFE input[id="diodeRFForm_Type"]').val();
        oldDiodeRF['Application'] = $('#formDiodeRFE select[id="diodeRFForm_Application"]').val();
        oldDiodeRF['Quality'] = $('#formDiodeRFE select[id="diodeRFForm_Quality"]').val();
        oldDiodeRF['Environment'] = ($('#formDiodeRFE select[id="diodeRFForm_Environment"]').val());
        oldDiodeRF['DiodeType'] = ($('#formDiodeRFE select[id="diodeRFForm_DiodeType"]').val());
        oldDiodeRF['CasePart'] = $('#formDiodeRFE input[id="diodeRFForm_CasePart"]').val();
        oldDiodeRF['TempDissipation'] = $('#formDiodeRFE input[id="diodeRFForm_TempDissipation"]').val();
        oldDiodeRF['TempPassive'] = $('#formDiodeRFE input[id="diodeRFForm_TempPassive"]').val();
        oldDiodeRF['PowerRated'] = $('#formDiodeRFE input[id="diodeRFForm_PowerRated"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveDiodeRF')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveDiodeRF)
        ;
        $("#formDiodeRFE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelDiodeRF')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveDiodeRF)
        ;
        $("#formDiodeRFE .submitHandle").append(cancel);

        $("#formDiodeRFE .submitHandle").append('<div class="cleaner"></div>');

    });

//----------------------------------------------------------------------------------------------------------------------

    $("#EditMemory").click(function(e) {
        e.preventDefault();
        $(".submitMsg").remove();
        $("#formMemoryE input:not(:submit), #formMemoryE select").removeAttr('disabled');
        var memType = $("#memoryForm_MemoryType").val();
        if(memType != "EEPROM") {
            disableEEPROM();
        }
        $this = $(this);
        $("#EditMemory").hide();
        oldMemory['Label'] = $('#formMemoryE input[id="memoryForm_Label"]').val();
        oldMemory['Type'] = $('#formMemoryE input[id="memoryForm_Type"]').val();
        oldMemory['MemoryType'] = $('#formMemoryE select[id="memoryForm_MemoryType"]').val();
        oldMemory['Quality'] = $('#formMemoryE select[id="memoryForm_Quality"]').val();
        oldMemory['Description'] = $('#formMemoryE select[id="memoryForm_Description"]').val();
        oldMemory['Environment'] = ($('#formMemoryE select[id="memoryForm_Environment"]').val());
        oldMemory['PackageType'] = ($('#formMemoryE select[id="memoryForm_PackageType"]').val());
        oldMemory['CasePart'] = $('#formMemoryE input[id="memoryForm_CasePart"]').val();
        oldMemory['TempDissipation'] = $('#formMemoryE input[id="memoryForm_TempDissipation"]').val();
        oldMemory['TempPassive'] = $('#formMemoryE input[id="memoryForm_TempPassive"]').val();
        oldMemory['PinCount'] = $('#formMemoryE input[id="memoryForm_PinCount"]').val();
        oldMemory['CyclesCount'] = $('#formMemoryE input[id="memoryForm_CyclesCount"]').val();
        oldMemory['MemorySize'] = $('#formMemoryE input[id="memoryForm_MemorySize"]').val();
        oldMemory['ProductionYears'] = $('#formMemoryE input[id="memoryForm_ProductionYears"]').val();
        oldMemory['EepromOxid'] = ($('#formMemoryE select[id="memoryForm_EepromOxid"]').val());
        oldMemory['ECC'] = ($('#formMemoryE select[id="memoryForm_ECC"]').val());

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveMemory')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveMemory)
        ;
        $("#formMemoryE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelMemory')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveMemory)
        ;
        $("#formMemoryE .submitHandle").append(cancel);

        $("#formMemoryE .submitHandle").append('<div class="cleaner"></div>');

    });

});