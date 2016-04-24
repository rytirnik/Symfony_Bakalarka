/**
 * Created by Nikey on 24.4.2016.
 */

var oldSys = [];
var oldPCB1 = [];
var oldPCB2 = [];

//----------------------------------------------------------------------------------------------------------------------
function saveSystem(event) {
    var save = $(event.target).attr('id') == 'SaveSystem';
    var err = 0;
    if(save) {
        $data =  $("#systemFormE").serializeJSON();
        var val = $('#systemFormE input[id="sysForm_Title"]').val();
        if ( val == "" ) {
            $("#systemFormE .submitMsg").remove();
            $("#systemFormE").append('<span class="submitMsg"> Vyplňte název </span>');
            return;
        }
        val = $('#systemFormE input[id="sysForm_Temp"]').val();
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
        $('#systemFormE input[id="sysForm_Title"]').val(oldSys['Title']);
        $('#systemFormE input[id="sysForm_Temp"]').val(oldSys['Temp']);
        $('#systemFormE textarea[id="sysForm_Note"]').val(oldSys['Note']);
        $('#systemFormE select[id="sysForm_Environment"]').val(oldSys['Environment']);

    }
    $("#systemFormE input:not(:submit), #systemFormE select, #systemFormE textarea").attr('disabled', 'disabled');
    $('#SaveSystem').remove();
    $('#CancelSytems').next('div').remove();
    $('#CancelSystem').remove();
    $("#EditSys").show();
}

//----------------------------------------------------------------------------------------------------------------------
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
                if(data.url)
                    window.document.location = data.url;
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
//----------------------------------------------------------------------------------------------------------------------
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
                //$lam1 = parseFloat($("#PCBlam1").text()) + parseFloat(data.Lam);
                $("#PCBlam1").text(data.SumLam);
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

//----------------------------------------------------------------------------------------------------------------------
function deleteSTM(event) {
    event.preventDefault();
    var $this = $(event.target);

    var postData = $this.parent().next('td').text();

    var delSMTURL = $this.attr('href');
    jQuery.ajax({
        url: delSMTURL,
        data: {id: postData},
        success: function (data) {
            $row = $this.parent().parent();
            $table = $row.parent().parent();

            if ($table.children('tr').length == 1) {
                $table.remove();
            }
            else {
                $row.remove();
            }

            var $lam = parseFloat($("#PCBlam1").text()) - parseFloat(data.Lam);
            $("#PCBlam1").text($lam);

            alert("Záznam SMT smazán");

        },
        error: function (data) {
            alert("Záznam SMT nesmazán");
        },
        dataType: 'json',
        type: 'POST'
    });
}

//----------------------------------------------------------------------------------------------------------------------
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
    $("#formDiodeLFE input:not(:submit), #formDiodeLFE select").attr('disabled', 'disabled');
    $("#formOptoE input:not(:submit), #formOptoE select").attr('disabled', 'disabled');
    $("#formCrystalE input:not(:submit), #formCrystalE select").attr('disabled', 'disabled');
    $("#formTransistorBiLFE input:not(:submit), #formTransistorBiLFE select").attr('disabled', 'disabled');
    $("#formTransistorFetLFE input:not(:submit), #formTransistorFetLFE select").attr('disabled', 'disabled');
    $("#formInductiveE input:not(:submit), #formInductiveE select").attr('disabled', 'disabled');
    $("#formMicrocircuitE input:not(:submit), #formMicrocircuitE select").attr('disabled', 'disabled');
    $("#formDiodeRFE input:not(:submit), #formDiodeRFE select").attr('disabled', 'disabled');



//----------------------------------------------------------------------------------------------------------------------
    $("#EditSys").click(function (e) {
        e.preventDefault();
        $("#systemFormE input:not(:submit), #systemFormE select, #systemFormE textarea").removeAttr('disabled');
        $this = $(this);
        $("#EditSys").hide();
        oldSys['Title'] = $('#systemFormE input[id="sysForm_Title"]').val();
        oldSys['Temp'] = $('#systemFormE input[id="sysForm_Temp"]').val();
        oldSys['Note'] = $('#systemFormE textarea[id="sysForm_Note"]').val();
        oldSys['Environment'] = $('#systemFormE select[id="sysForm_Environment"]').val();

        var save = document.createElement('input');
        var cancel = document.createElement('input');
        $(save)
            .attr('id', 'SaveSystem')
            .attr('class', 'save')
            .attr('type', 'button')
            .val('Uložit')
            .click(saveSystem)
        ;
        $("#systemFormE .submitHandle").append(save);
        $(cancel)
            .attr('id', 'CancelSystem')
            .attr('class', 'cancel')
            .attr('type', 'button')
            .val('Zrušit')
            .click(saveSystem)
        ;
        $("#systemFormE .submitHandle").append(cancel);
        $("#systemFormE .submitHandle").append('<div class="cleaner"></div>');
    });

//----------------------------------------------------------------------------------------------------------------------
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

//----------------------------------------------------------------------------------------------------------------------
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

//----------------------------------------------------------------------------------------------------------------------

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

});


