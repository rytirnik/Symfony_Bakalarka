/**
 * Created by Nikey on 3.4.14.
 */


jQuery(document).ready(function($) {

    var validator = $("#formRes").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            else {
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[Type]": {
                maxlength: 64
            },
            "form[Value]": {
                digits: true
            },
            "form[MaxPower]": {
                number: true
            },
            "form[VoltageOperational]": {
                number: true
            },
            "form[CurrentOperational]": {
                number: true
            },
            "form[DissipationPower]": {
                number: true
            },
            "form[DPTemp]": {
                number: true
            },
            "form[PassiveTemp]": {
                number: true
            },
            "form[Alternate]": {
                number: true
            }
        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            },
            "form[Value]": {
                digits: "Neplatné celé číslo"
            },
            "form[MaxPower]": {
                required: "Povinné pole 'Maximální výkon' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            },
            "form[VoltageOperational]": {
                number: "Neplatné desetinné číslo"
            },
            "form[CurrentOperational]": {
                number: "Neplatné desetinné číslo"
            },
            "form[DissipationPower]": {
                required: "Povinné pole 'Ztrátový výkon' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            },
            "form[DPTemp]": {
                required: "Povinné pole 'Oteplení ZV' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            },
            "form[PassiveTemp]": {
                required: "Povinné pole 'Pasivní oteplení' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            },
            "form[Alternate]": {
                number: "Neplatné desetinné číslo"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formRes").serializeJSON();
            jQuery.ajax({
                url:        newResURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#ResTable").length == 0) {
                        $("#tab_01").append('<h2> Uložené resistory </h2>'+
                            '<table id="ResTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                                    '<td> Označeni </td> '+
                                    '<td> Lambda </td>'+
                                    '<td> Prostředí </td>'+
                                    '<td> Materiál </td>'+
                                    '<td> Kvalita </td>'+
                                    '<td> Maximální výkon </td>'+
                                    '<td> Ztrátový výkon </td>'+
                                    '<td> Oteplení ZV </td>'+
                                    '<td> Pasivní oteplení </td>'+
                                '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#ResTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Material + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Quality + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.MaxPower + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.DissipationPower + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.DPTemp + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.PassiveTemp + "</td> </tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);
                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formRes").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formRes").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validator.resetForm();
        }
    });


    var validatorC = $("#formCap").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            else {
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[Type]": {
                maxlength: 64
            },
            "form[Value]": {
                number: true
            },
            "form[VoltageMax]": {
                number: true
            },
            "form[VoltageDC]": {
                number: true
            },
            "form[VoltageAC]": {
                number: true
            },
            "form[VoltageOperational]": {
                number: true
            },
            "form[SerialResistor]": {
                number: true
            },
            "form[PassiveTemp]": {
                digits: true
            }
        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            },
            "form[Value]": {
                required: "Povinné pole 'Hodnota' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            },
            "form[VoltageMax]": {
                required: "Povinné pole 'Maximální napětí' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            },
            "form[VoltageDC]": {
                number: "Neplatné desetinné číslo"
            },
            "form[VoltageAC]": {
                number: "Neplatné desetinné číslo"
            },
            "form[VoltageOperational]": {
                required: "Povinné pole 'Pracovní napětí' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            },
            "form[SerialResistor]": {
                number: "Neplatné desetinné číslo"
            },
            "form[PassiveTemp]": {
                required: "Povinné pole 'Pasivní oteplení' (celé číslo)",
                digits: "Neplatné desetinné číslo"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formCap").serializeJSON();
            jQuery.ajax({
                url:        newCapURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#CapTable").length == 0) {
                        $("#tab_02").append('<h2> Uložené kondenzátory </h2>'+
                            '<table id="CapTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Hodnota </td>'+
                            '<td> Prostředí </td>'+
                            '<td> Materiál </td>'+
                            '<td> Kvalita </td>'+
                            '<td> Maximální napětí </td>'+
                            '<td> Provozní napětí </td>'+
                            '<td> Pasivní oteplení </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#CapTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Value + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Material + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Quality + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.VoltageMax + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.VoltageOperational + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.PassiveTemp + "</td> </tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);
                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formCap").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formCap").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorC.resetForm();
        }
    });

    var validatorFuse = $("#formFuse").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            else {
                //alert(error.text());
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[Type]": {
                maxlength: 64
            },
            "form[CasePart]": {
                maxlength: 64
            },
            "form[Value]": {
                number: true
            }
        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            },
            "form[Value]": {
                required: "Povinné pole 'Hodnota' (desetinné číslo)",
                number: "Neplatné celé číslo"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formFuse").serializeJSON();
            jQuery.ajax({
                url:        newFuseURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#FuseTable").length == 0) {
                        $("#tab_03").append('<h2> Uložené pojistky </h2>'+
                            '<table id="FuseTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Hodnota </td>'+
                            '<td> Typ </td>'+
                            '<td> Pouzdro </td>'+
                            '<td> Prostředí </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#FuseTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Value + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Type + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.CasePart + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td></tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);
                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formFuse").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formFuse").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorFuse.resetForm();
        }
    });

    var validatorConnection = $("#formConnection").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<span class="validErr">' + error.text() + '</span>' );
            else {
                element.parent().children('span').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[CasePart]": {
                maxlength: 64
            }

        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formConnection").serializeJSON();
            jQuery.ajax({
                url:        newConnectionURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#ConnectionTable").length == 0) {
                        $("#tab_04").append('<h2> Uložené spojení </h2>'+
                            '<table id="ConnectionTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Popis </td>'+
                            '<td> Typ </td>'+
                            '<td> Pouzdro </td>'+
                            '<td> Prostředí </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#ConnectionTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>" + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.ConnectionType + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Type + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.CasePart + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td></tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });


                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);

                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formConnection").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formConnection").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorConnection.resetForm();
        }
    });

    var validatorConSoc = $("#formConSoc").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            else {
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[CasePart]": {
                maxlength: 64
            },
            "form[ActivePins]": {
                digits: true,
                min: 1
            }

        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            },
            "form[ActivePins]": {
                required: "Povinné pole 'Activní piny' (celé číslo)",
                digits: "Neplatné celé číslo"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formConSoc").serializeJSON();
            jQuery.ajax({
                url:        newConSocURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#ConSocTable").length == 0) {
                        $("#tab_05").append('<h2> Uložené konektory, sokety </h2>'+
                            '<table id="ConSocTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Aktivní piny </td>'+
                            '<td> Popis </td>'+
                            '<td> Kvalita </td>'+
                            '<td> Typ </td>'+
                            '<td> Pouzdro </td>'+
                            '<td> Prostředí </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#ConSocTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>" + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.ActivePins + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.ConnectorType + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Quality + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Type + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.CasePart + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td></tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);
                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formConSoc").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formConSoc").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorConSoc.resetForm();
        }
    });

    var validatorConGen = $("#formConGen").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            else {
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[CasePart]": {
                maxlength: 64
            },
            "form[ContactCnt]": {
                digits: true,
                min: 1
            },
            "form[CurrentContact]": {
                number: true
            },
            "form[MatingFactor]": {
                digits: true
            },
            "form[PassiveTemp]": {
                digits: true
            }

        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            },
            "form[CurrentContact]": {
                required: "Povinné pole 'Proud na kontakt' (desetinné číslo)",
                digits: "Neplatné desetinné číslo"
            },
            "form[MatingFactor]": {
                required: "Povinné pole 'Počet spoj/rozpoj' (celé číslo)",
                digits: "Neplatné celé číslo"
            },
            "form[PassiveTemp]": {
                required: "Povinné pole 'Pasivní teplota' (celé číslo)",
                digits: "Neplatné celé číslo"
            },
            "form[ContactCnt]": {
                required: "Povinné pole 'Activní piny' (celé číslo)",
                digits: "Neplatné celé číslo"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formConGen").serializeJSON();
            jQuery.ajax({
                url:        newConGenURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#ConGenTable").length == 0) {
                        $("#tab_06").append('<h2> Uložené konektory, obecné </h2>'+
                            '<table id="ConGenTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Popis </td>'+
                            '<td> Kvalita </td>'+
                            '<td> Počet kontaktů </td>'+
                            '<td> Proud na kontakt </td>'+
                            '<td> Spoj/rozpoj </td>'+
                            '<td> Pasivní oteplení </td>'+
                            '<td> Prostředí </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#ConGenTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>" + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.ConnectorType + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Quality + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.ContactCnt + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.CurrentContact + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.MatingFactor + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.PassiveTemp + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td></tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);
                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formConGen").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formConGen").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorConGen.resetForm();
        }
    });


    var validatorSwitch = $("#formSwitch").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            else {
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[CasePart]": {
                maxlength: 64
            },
            "form[ContactCnt]": {
                digits: true,
                min: 0
            },
            "form[OperatingCurrent]": {
                number: true
            },
            "form[RatedResistiveCurrent]": {
                number: true
            }
        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            },
            "form[ContactCnt]": {
                required: "Povinné pole 'Počet kontaktů' (celé číslo)",
                digits: "Neplatné desetinné číslo"
            },
            "form[OperatingCurrent]": {
                required: "Povinné pole 'Pracovní proud' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            },
            "form[RatedResistiveCurrent]": {
                required: "Povinné pole 'Maximální proud' (desetinné číslo)",
                number: "Neplatné desetinné číslo"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formSwitch").serializeJSON();
            jQuery.ajax({
                url:        newSwitchURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#SwitchTable").length == 0) {
                        $("#tab_07").append('<h2> Uložené spínače </h2>'+
                            '<table id="SwitchTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Popis </td>'+
                            '<td> Kvalita </td>'+
                            '<td> Počet kontaktů </td>'+
                            '<td> Pracovní proud </td>'+
                            '<td> Maximální proud </td>'+
                            '<td> Typ zátěže </td>'+
                            '<td> Prostředí </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#SwitchTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>" + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.SwitchType + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Quality + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.ContactCnt + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.OperatingCurrent + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.RatedResistiveCurrent + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.LoadType + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td></tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);
                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formSwitch").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formSwitch").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorSwitch.resetForm();
        }
    });


    var validatorFilter = $("#formFilter").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            else {
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[CasePart]": {
                maxlength: 64
            },
            "form[Type]": {
                maxlength: 64
            }

        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formFilter").serializeJSON();
            jQuery.ajax({
                url:        newFilterURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#FilterTable").length == 0) {
                        $("#tab_08").append('<h2> Uložené filtry </h2>'+
                            '<table id="FilterTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Popis </td>'+
                            '<td> Kvalita </td>'+
                            '<td> Typ </td>'+
                            '<td> Pouzdro </td>'+
                            '<td> Prostředí </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#FilterTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>" + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.FilterType + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Quality + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Type + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.CasePart + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td></tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);

                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formFilter").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formFilter").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorFilter.resetForm();
        }
    });


    var validatorRotElaps = $("#formRotElaps").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            else {
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[CasePart]": {
                maxlength: 64
            },
            "form[Type]": {
                maxlength: 64
            },
            "form[TempOperational]": {
                digits: true
            },
            "form[Tempmax]": {
                digits: true
            }

        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            },
            "form[TempOperational]": {
                required: "Povinné pole 'Provozní teplota' (celé číslo)"
            },
            "form[TempMax]": {
                required: "Povinné pole 'Maxmální teplota' (celé číslo)"
            }
        },
        submitHandler: function(form) {
            $data =  $("#formRotElaps").serializeJSON();
            jQuery.ajax({
                url:        newRotElapsURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#RotElapsTable").length == 0) {
                        $("#tab_09").append('<h2> Uložené měřiče motohodin </h2>'+
                            '<table id="RotElapsTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Popis </td>'+
                            '<td> Provozní teplota </td>'+
                            '<td> Maximální teplota </td>'+
                            '<td> Typ </td>'+
                            '<td> Pouzdro </td>'+
                            '<td> Prostředí </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#RotElapsTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>" + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.DevType + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.TempOperational + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.TempMax + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Type + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.CasePart + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td></tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);

                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formRotElaps").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formRotElaps").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorRotElaps.resetForm();
        }
    });

    var validatorTubeWave = $("#formTubeWave").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2) {
                element.parent().append('<p class="validErr">' + error.text() + '</p>' );
            }
            else {
                element.parent().children('p').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 64
            },
            "form[CasePart]": {
                maxlength: 64
            },
            "form[Type]": {
                maxlength: 64
            },
            "form[Power]": {
                digits: true,
                min: 10,
                max: 40000
            },
            "form[Frequency]": {
                number: true,
                min: 0.1,
                max: 18
            }

        },
        messages: {
            "form[Label]": {
                required: "Povinné pole 'Název'"
            },
            "form[Power]": {
                required: "Povinné pole 'Výkon' (celé číslo)",
                digits: "Neplatné celé číslo",
                min: "Hodnota musí být v rozsahu 10-40000",
                max: "Hodnota musí být v rozsahu 10-40000"
            },
            "form[Frequency]": {
                required: "Povinné pole 'Frekvence' (desetinné číslo)",
                number: "Neplatné desetinné číslo",
                min: "Hodnota musí být v rozsahu 0.1-18",
                max: "Hodnota musí být v rozsahu 0.1-18"
            }
        },
        submitHandler: function(form) {
            $(".validErr").remove();
            $data =  $("#formTubeWave").serializeJSON();
            jQuery.ajax({
                url:        newTubeWaveURL,
                data:       {formData: $data, id: idPCB },
                success:    function(data){
                    //alert("ok");
                    if($("#TubeWaveTable").length == 0) {
                        $("#tab_10").append('<h2> Uložené permaktrony </h2>'+
                            '<table id="TubeWaveTable" class = "systems part newPart systemsHover">' +
                            '<thead> <tr> '+
                            '<td> Název </td> '+
                            '<td> Lambda </td>'+
                            '<td> Výkon </td>'+
                            '<td> Frekvence </td>'+
                            '<td> Typ </td>'+
                            '<td> Pouzdro </td>'+
                            '<td> Prostředí </td>'+
                            '</tr> </thead> <tbody> </tbody> </table>');
                    }

                    $detURl = detailURL.substring(0, detailURL.lastIndexOf("-1"));
                    $detURl = $detURl.concat(data.idP);

                    $("#TubeWaveTable").children('tbody').append("<tr>" +
                        "<td class='tableLink' href='" + $detURl + "'>" + data.Label + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Lam + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Power + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Frequency + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Type + "</td>"+
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.CasePart + "</td>" +
                        "<td class='tableLink' href='" + $detURl + "'>"  + data.Environment + "</td></tr>");

                    $(".tableLink").click(function() {
                        window.document.location = $(this).attr("href");
                    });

                    $(".tableLink").hover(function() {
                        $(".tableLink").css("cursor", "pointer");
                    });

                    $sysL = parseFloat($("#SysLam").text()) + parseFloat(data.Lam);

                    $("#SysLam").text($sysL);

                    $(".submitMsg").remove();
                    $("#formTubeWave").append('<span class="submitMsg"> Součástka byla uložena. </span>');

                },
                error: function(data) {
                    //alert("Error");
                    $(".submitMsg").remove();
                    $("#formTubeWave").append('<span class="submitMsg"> Součástku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorTubeWave.resetForm();
        }
    });

    var validatorPCB = $("#pcbForm").validate( {
        errorPlacement: function(error, element) {
            if(element.parent().children().length == 2)
                element.parent().append('<span class="validErr">' + error.text() + '</span>' );
            else {
                element.parent().children('span').text(error.text());
            }
            $(".submitMsg").remove();
        },
        rules: {
            "form[Label]": {
                maxlength: 30
            },
            "form[Lifetime]": {
                digits: true,
                min: 1
            },
            "form[Layers]": {
                digits: true,
                min: 1,
                max: 18
            },
            "form[SolderingPointAuto]": {
                digits: true,
                min: 0
            },
            "form[SolderingPointHand]": {
                digits: true,
                min: 0
            },
            "form[Height]": {
                digits: true,
                min: 0
            },
            "form[Width]": {
                digits: true,
                min: 0
            },
            "form[TempDissipation]": {
                digits: true,
                min: 0
            },
            "form[Cnt]": {
                digits: true,
                min: 1
            }
        },
        messages: {
            "form[Label]": {
                required: "Povinné pole"
            },
            "form[Lifetime]": {
                required: "Povinné pole (celé číslo)",
                digits: "Neplatné celé číslo"
            },
            "form[Layers]": {
                required: "Povinné pole (celé číslo 1-18)",
                digits: "Neplatné celé číslo"
            },
            "form[Quality]": {
                required: "Povinné pole"
            },
            "form[Cnt]": {
                required: "Povinné pole (celé číslo)",
                digits: "Neplatné celé číslo"
            },
            "form[SolderingPointAuto]": {
                required: "Povinné pole (celé číslo)",
                digits: "Neplatné celé číslo"
            },
            "form[SolderingPointHand]": {
                required: "Povinné pole (celé číslo)",
                digits: "Neplatné celé číslo"
            }
        },
        submitHandler: function(form) {
            $data =  $("#pcbForm").serializeJSON();
            $wire = false;
            $smt = false;
            if($("#slide1").is(':visible')) $wire=true;
            if($("#slide2").is(':visible')) $smt=true;

            jQuery.ajax({
                url:        newPCBURL,
                data:       {formData: $data, id: idS, wire: $wire, smt: $smt },
                success:    function(data){
                    //alert("ok");

                    $(".submitMsg").remove();
                    $("#pcbForm").append('<span class="submitMsg"> Deska byla uložena. </span>');

                    //alert(data.url);
                    window.document.location = data.url;

                },
                error: function(data) {
                    // alert("Error");
                    $(".submitMsg").remove();
                    $("#pcbForm").append('<span class="submitMsg"> Desku se nepodařilo uložit. </span>')
                },
                dataType:   'json',
                type:       'POST'
            });
            $(".validErr").remove();
            validatorPCB.resetForm();
        }
    });




});