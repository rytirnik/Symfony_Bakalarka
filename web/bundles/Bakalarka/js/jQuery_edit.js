/**
 * Created by Nikey on 3.4.14.
 */

var oldSys = [];
var oldPCB1 = [];
var oldPCB2 = [];
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

function savePCB(event) {
    var save = $(event.target).attr('id') == 'SavePCB1';
    if(save) {
        $data =  $("#EditPcbForm").serializeJSON();
        if ($('#EditPcbForm input[id="form_Label"]').val() == "") {
            $("#EditPcbForm .submitMsg").remove();
            $("#EditPcbForm").append('<span class="submitMsg"> Vyplňte název desky </span>');
            return;
        }
        var val = $('#EditPcbForm input[id="form_Lifetime"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#EditPcbForm .submitMsg").remove();
            $("#EditPcbForm").append('<span class="submitMsg"> Vyplňte životnost (celé číslo) </span>');
            return;
        }

        $url = $("#EditPcbForm").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: 1},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#EditPcbForm").append('<span class="submitMsg"> Deska byla uložena. </span>');

            },
            error: function(data) {
                //alert("Error");
                $(".submitMsg").remove();
                $("#EditPcbForm").append('<span class="submitMsg"> Desku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    } else {
        $('#EditPcbForm input[id="form_Label"]').val(oldPCB1['Label']);
        $('#EditPcbForm input[id="form_Lifetime"]').val(oldPCB1['Lifetime']);
        $('#EditPcbForm select[id="form_SubstrateMaterial"]').val(oldPCB1['SubstrateMaterial'] );
        $('#EditPcbForm select[id="form_EquipType"]').val(oldPCB1['EquipType']);
        $("#EditPcbForm .submitMsg").remove();
    }

    $("#EditPcbForm input:not(:submit), #EditPcbForm select").attr('disabled', 'disabled');
    $('#SavePCB1').remove();
    $('#CancelPCB1').next('div').remove();
    $('#CancelPCB1').remove();
    $("#EditPCB1").show();
}

function savePCB2(event) {
    event.preventDefault();
    var save = $(event.target).attr('id') == 'SavePCB2';

    if(save) {
        var val = $('#EditPcbForm2 input[id="form_Layers"]').val();
        if ( val != "" && Math.floor(val) != val && !($.isNumeric(val))) {
            $("#EditPcbForm.submitMsg").remove();
            $("#EditPcbForm2").append('<span class="submitMsg"> Počet vrstev musí být celé číslo </span>');
            return;
        }
        val = $('#EditPcbForm2 input[id="form_SolderingPointAuto"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#EditPcbForm2 .submitMsg").remove();
            $("#EditPcbForm2").append('<span class="submitMsg"> Zadejte počet bodů pájení automaticky (celé číslo) </span>');
            return;
        }
        val = $('#EditPcbForm2 input[id="form_SolderingPointHand"]').val();
        if ( val == "" || Math.floor(val) != val  || !($.isNumeric(val))) {
            $("#EditPcbForm2 .submitMsg").remove();
            $("#EditPcbForm2").append('<span class="submitMsg"> Zadejte počet bodů pájení ručně (celé číslo) </span>');
            return;
        }

        $data =  $("#EditPcbForm2").serializeJSON();
        $url = $("#EditPcbForm2").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: 2},
            success:    function(data){
                //alert("ok");
                $lam1 = parseFloat($("#PCBlam1").text()) + parseFloat(data.Lam);
                $("#PCBlam1").text($lam1);
                $("#PCBlam2").text(data.Lam);

                $("#EditPcbForm2 .submitMsg").remove();
                $("#EditPcbForm2").append('<span class="submitMsg"> Deska byla uložena. </span>');

            },
            error: function(data) {
                //alert("Error");
                $("#EditPcbForm2 .submitMsg").remove();
                $("#EditPcbForm2").append('<span class="submitMsg"> Desku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    } else {
        $('#EditPcbForm2 input[id="form_Layers"]').val(oldPCB2['Layers']);
        $('#EditPcbForm2 input[id="form_SolderingPointAuto"]').val(oldPCB2['SolderingPointAuto']);
        $('#EditPcbForm2 input[id="form_SolderingPointHand"]').val(oldPCB2['SolderingPointHand']);
        $('#EditPcbForm2 select[id="form_Quality"]').val(oldPCB2['Quality']);
        $("#EditPcbForm2 .submitMsg").remove();
    }

    $("#EditPcbForm2 input:not(:submit), #EditPcbForm2 select").attr('disabled', 'disabled');
    $('#SavePCB2').remove();
    $('#CancelPCB2').next('div').remove();
    $('#CancelPCB2').remove();
    $("#EditPCB2").show();
}

function saveSystem(event) {
    var save = $(event.target).attr('id') == 'SaveSystem';
    var err = 0;
    if(save) {
        $data =  $("#systemFormE").serializeJSON();
        var val = $('#systemFormE input[id="form_Title"]').val();
        if ( val == "" ) {
            $("#systemFormE .submitMsg").remove();
            $("#systemFormE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#systemFormE input[id="form_Temp"]').val();
        if ( val == "" && Math.floor(val) != val && !($.isNumeric(val))) {
            $("#systemFormE .submitMsg").remove();
            $("#systemFormE").append('<span class="submitMsg"> Vyplňte teplotu (celé číslo) </span>');
            return;
        }

        $url = $("#systemFormE").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data},
            success:    function(data){
                //alert("ok");
                $(".submitMsg").remove();
                $("#systemFormE").append('<span class="submitMsg"> Systém byl uložen. </span>');

                $("#labelPart").text(data.Title);

            },
            error: function(data) {
                //alert("Error");
                err = 1;
                $(".submitMsg").remove();
                $("#systemFormE").append('<span class="submitMsg"> Systém se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });

    }
    if (err || !save) {
        $('#systemFormE input[id="form_Title"]').val(oldSys['Title']);
        $('#systemFormE input[id="form_Temp"]').val(oldSys['Temp']);
        $('#systemFormE textarea[id="form_Note"]').val(oldSys['Note']);
        $('#systemFormE select[id="form_Environment"]').val(oldSys['Environment']);

    }
    $("#systemFormE input:not(:submit), #systemFormE select, #systemFormE textarea").attr('disabled', 'disabled');
    $('#SaveSystem').remove();
    $('#CancelSytems').next('div').remove();
    $('#CancelSystem').remove();
    $("#EditSys").show();
}

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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

                $("#lamPart").text(data.Lam);
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

function deleteSTM(event) {
    event.preventDefault();
    var $this = $(event.target);

    var postData = $this.parent().next('td').text();

    var delSMTURL = $this.attr('href');
    jQuery.ajax({
        url:        delSMTURL,
        data:       {id: postData},
        success:    function(data){
            $row = $this.parent().parent();
            $table = $row.parent().parent();

            if($table.children('tr').length == 1) {
                $table.remove();
            }
            else {
                $row.remove();
            }

            var $lam = parseFloat($("#PCBlam1").text()) - parseFloat(data.Lam);
            $("#PCBlam1").text($lam);

            alert("Záznam SMT smazán");

        },
        error: function(data) {
            alert("Záznam SMT nesmazán");
        },
        dataType:   'json',
        type:       'POST'
    });

}


jQuery(document).ready(function($) {
    $("#EditPcbForm input:not(:submit), #EditPcbForm select").attr('disabled', 'disabled');
    $("#EditPcbForm2 input:not(:submit), #EditPcbForm2 select").attr('disabled', 'disabled');
    $("#formResE input:not(:submit), #formResE select").attr('disabled', 'disabled');
    $("#formCapE input:not(:submit), #formCapE select").attr('disabled', 'disabled');
    $("#formFuseE input:not(:submit), #formFuseE select").attr('disabled', 'disabled');
    $("#formConnectionE input:not(:submit), #formConnectionE select").attr('disabled', 'disabled');
    $("#formConSocE input:not(:submit), #formConSocE select").attr('disabled', 'disabled');
    $("#formConGenE input:not(:submit), #formConGenE select").attr('disabled', 'disabled');
    $("#systemFormE input:not(:submit), #systemFormE select, #systemFormE textarea").attr('disabled', 'disabled');
    $("#formSwitchE input:not(:submit), #formSwitchE select").attr('disabled', 'disabled');
    $("#formFilterE input:not(:submit), #formFilterE select").attr('disabled', 'disabled');
    $("#formRotElapsE input:not(:submit), #formRotElapsE select").attr('disabled', 'disabled');
    $("#formTubeWaveE input:not(:submit), #formTubeWaveE select").attr('disabled', 'disabled');

    $("#EditPCB1").click(function(e) {
        e.preventDefault();
        $("#EditPcbForm input:not(:submit), #EditPcbForm select").removeAttr('disabled');
        $this = $(this);
        $("#EditPCB1").hide();
        oldPCB1['Label'] = $('#EditPcbForm input[id="form_Label"]').val();
        oldPCB1['Lifetime'] = $('#EditPcbForm input[id="form_Lifetime"]').val();
        oldPCB1['SubstrateMaterial'] = ($('#EditPcbForm select[id="form_SubstrateMaterial"]').val());
        oldPCB1['EquipType'] = ($('#EditPcbForm select[id="form_EquipType"]').val());

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SavePCB1')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(savePCB)
        ;
        $("#EditPcbForm .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelPCB1')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(savePCB)
        ;
        $("#EditPcbForm .submitHandle").append(cancel);
        $("#EditPcbForm .submitHandle").append('<div class="cleaner"></div>');
    });

    $("#EditPCB2").click(function(e) {
        e.preventDefault();
        $("#EditPcbForm2 input:not(:submit), #EditPcbForm2 select").removeAttr('disabled');
        $this = $(this);
        $("#EditPCB2").hide();
        oldPCB2['Layers'] = $('#EditPcbForm2 input[id="form_Layers"]').val();
        oldPCB2['SolderingPointAuto'] = $('#EditPcbForm2 input[id="form_SolderingPointAuto"]').val();
        oldPCB2['SolderingPointHand'] = ($('#EditPcbForm2 input[id="form_SolderingPointHand"]').val());
        oldPCB2['Quality'] = ($('#EditPcbForm2 select[id="form_Quality"]').val());

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SavePCB2')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(savePCB2)
        ;
        $("#EditPcbForm2 .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelPCB2')
            .attr('type','button')
            .attr('class','cancel')
            .val('Zrušit')
            .click(savePCB2)
        ;
        $("#EditPcbForm2 .submitHandle").append(cancel);

        $("#EditPcbForm2 .submitHandle").append('<div class="cleaner"></div>');

    });

    $("#EditPCB3").click(function(e) {
        e.preventDefault();
        var val = $('#PcbForm3 input[id="form_Height"]').val();
        if ( val != "" && Math.floor(val) != val && !($.isNumeric(val))) {
            $("#PcbForm3 .submitMsg").remove();
            $("#PcbForm3").append('<span class="submitMsg"> Délka musí být celé číslo </span>');
            return;
        }
        val = $('#PcbForm3 input[id="form_Width"]').val();
        if ( val != "" && Math.floor(val) != val && !($.isNumeric(val))) {
            $("#PcbForm3 .submitMsg").remove();
            $("#PcbForm3").append('<span class="submitMsg"> Šířka musí být celé číslo </span>');
            return;
        }
        val = $('#PcbForm3 input[id="form_TempDissipation"]').val();
        if ( val != "" && Math.floor(val) != val && !($.isNumeric(val))) {
            $("#PcbForm3 .submitMsg").remove();
            $("#PcbForm3").append('<span class="submitMsg"> Oteplení ZV musí být celé číslo  </span>');
            return;
        }
        val = $('#PcbForm3 input[id="form_Cnt"]').val();
        if ( val == "" || Math.floor(val) != val || !($.isNumeric(val))) {
            $("#PcbForm3 .submitMsg").remove();
            $("#PcbForm3").append('<span class="submitMsg"> Zadejte počet součástek (celé číslo)  </span>');
            return;
        }

        $data =  $("#PcbForm3").serializeJSON();
        $url = $("#PcbForm3").attr('action');
        jQuery.ajax({
            url:        $url,
            data:       {formData: $data, mode: 3},
            success:    function(data){
                //alert("ok");
                $lam1 = parseFloat($("#PCBlam1").text()) + parseFloat(data.Lam);
                $("#PCBlam1").text($lam1);

                $(".submitMsg").remove();
                $("#PcbForm3").append('<span class="submitMsg"> Deska byla uložena. </span>');

                if($("#SMTTable").length == 0) {
                    $("#content").append('<h2> Uložené součástky se SMT vývody </h2>'+
                        '<table id="SMTTable" class = "systems part">' +
                        '<thead> <tr> '+
                        '<td> Lambda </td> '+
                        '<td> Typy Vývodů </td>'+
                        '<td> Materiál pouzdra </td>'+
                        '<td> Délka </td>'+
                        '<td> Šířka </td>'+
                        '<td> Oteplení ZV </td>'+
                        '<td> Počet součástek </td>'+
                        '<td></td> <td></td>' +
                        '</tr> </thead> <tbody> </tbody> </table>');
                }

                $table = "<tr> <td >" + data.Lam + "</td>";

                if (data.LeadConfig == 1)
                    $table += "<td> Leadless </td>";
                else if (data.LeadConfig == 150)
                    $table += "<td> J/S Lead  </td>";
                else
                    $table += "<td> Gull Wing </td>";

                 if (data.TCEPackage == 7)
                     $table += "<td> Plastic </td>";
                 else
                     $table += "<td> Ceramic </td>";

                //<a class = "delSTM" href=' + delSmtURL + '> Smazat </a>
                var del = document.createElement('a');

                $(del)
                    .attr('class','delSTM')
                    .attr('href', delSmtURL)
                    .click(deleteSTM)
                ;
                $(del).text("Smazat");

                $table += "<td >" + data.Height + "</td>"+
                    "<td >" + data.Width + "</td>"+
                    "<td >" + data.TempDissipation + "</td>"+
                    "<td >" + data.Cnt + "</td>" +
                    '<td></td><td class = "tableBtn" ></td>' +
                    '<td class="hidden">' + data.idSmt + '</td> </tr>';

                $("#SMTTable").children('tbody').append($table);
                $("#SMTTable tbody tr:last-child td.tableBtn").append(del);


            },
            error: function(data) {
                //alert("Error");
                $(".submitMsg").remove();
                $("#PcbForm3").append('<span class="submitMsg"> Desku se nepodařilo uložit. </span>')
            },
            dataType:   'json',
            type:       'POST'
        });
    });


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


    $("#EditSys").click(function(e) {
        e.preventDefault();
        $("#systemFormE input:not(:submit), #systemFormE select, #systemFormE textarea").removeAttr('disabled');
        $this = $(this);
        $("#EditSys").hide();
        oldSys['Title'] = $('#systemFormE input[id="form_Title"]').val();
        oldSys['Temp'] = $('#systemFormE input[id="form_Temp"]').val();
        oldSys['Note'] = $('#systemFormE textarea[id="form_Note"]').val();
        oldSys['Environment'] = $('#systemFormE select[id="form_Environment"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id','SaveSystem')
            .attr('class','save')
            .attr('type','button')
            .val('Uložit')
            .click(saveSystem)
        ;
        $("#systemFormE .submitHandle").append(save);
        $(cancel)
            .attr('id','CancelSystem')
            .attr('class','cancel')
            .attr('type','button')
            .val('Zrušit')
            .click(saveSystem)
        ;
        $("#systemFormE .submitHandle").append(cancel);
        $("#systemFormE .submitHandle").append('<div class="cleaner"></div>');

    });

});