/**
 * Created by Nikey on 3.4.14.
 */

function init_tabs() {
    if (!$('ul.tabs').length) {
        return;
    }

    $('div.tab_content_wrap').each(function() {
        $(this).find('div.tab_content:first').show();
    });

    $('ul.tabs a').click(function() {
        if (!$(this).hasClass('current')) {
            $(this).addClass('current').parent('li').siblings('li').find('a.current').removeClass('current');
            $($(this).attr('href')).show().siblings('div.tab_content').hide();
        }

        this.blur();
        return false;
    });
}


jQuery(document).ready(function($) {
    init_tabs();
    $("#slide1").hide();
    $("#slide2").hide();
    $("#rozbal1").hover(function() {
        $(this).css("cursor", "pointer");
    });
    $("#rozbal2").hover(function() {
        $(this).css("cursor", "pointer");
    });

    $("#rozbal1").click(function(e){
        e.preventDefault();
        if($("#slide1").is(':visible'))
           // $(this).attr('value','Rozbal');
            $(this).text("+ Přidat součástky s drátovými vývody");
        else
            //$(this).attr('value','Zabal');
            $(this).text("- Odebrat součástky s drátovými vývody");
        $("#slide1").slideToggle('slow');
    });
    $("#rozbal2").click(function(e){
        e.preventDefault();
        if($("#slide2").is(':visible'))
            $(this).text("+ Přidat součástky se SMT vývody");
        else
            $(this).text("- Odebrat součástky se SMT vývody");
        $("#slide2").slideToggle('slow');
    });

    $(".tableLink").click(function() {
        window.document.location = $(this).attr("href");
    });

    $(".tableLink").hover(function() {
        $(".tableLink").css("cursor", "pointer");
    });

    $(".delPart").click(function(e) {
        e.preventDefault();
        var $this = $(this).parent();
        var postData = $this.next('td').text();
        jQuery.ajax({
            url:        delPartURL,
            data:       {id: postData},
            success:    function(data){
                $parent = $this.parent();
                if($parent.parent().children().length == 1)
                    $parent.parent().parent().remove();
                else
                    $parent.remove();
                var $cnt = parseInt($("#PartsCnt").text()) -1;
                $("#PartsCnt").text($cnt);

                var $l = parseFloat($("#Lam").text()) - parseFloat(data.lam);
                $("#Lam").text($l);

                alert("Soucastka smazana");
            },
            error: function(data) {
                alert("Soucastka nesmazana");
            },
            dataType:   'json',
            type:       'POST'
        });
    });

    $(".delDesk").click(function(e) {
        e.preventDefault();
        var postData = $(this).parent().next('td').text();
        var $this = $(this);
        jQuery.ajax({
            url:        delDeskURL,
            data:       {id: postData},
            success:    function(data){
                $parent = $this.parent().parent().parent().parent();
                $var = 0;
                $parts = 0;

                $pp = $parent.parent();
                if($parent.parent().children('.desk').length == 1) {
                    $var = 1;
                }
                if (($parts = $parent.next('table.part').length) > 0) {
                    $parent.next('table.part').remove();
                }
                $parent.remove();

                var $cnt = parseInt($("#PCBcnt").text()) -1;
                $("#PCBcnt").text($cnt);

                var $cntParts = parseInt($("#PartsCnt").text()) - $parts;
                $("#PartsCnt").text($cntParts);

                $("#Lam").text(parseFloat(data.lamS));

                if($var == 1)
                    $pp.append("<p> V systému nejsou žádné desky. </p>");

                alert("Deska smazana");

            },
            error: function(data) {
                alert("Deska nesmazana");
            },
            dataType:   'json',
            type:       'POST'
        });
    });

    $(".delSTM").click(function(e) {
        e.preventDefault();
        var postData = $(this).parent().next('td').text();
        var $this = $(this);
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
    });


    $('table.tablesorter').each(function() {
        var aktStranka = 0;
        var pocetNaStrance = 15;
        var $tabulka = $(this);

        var strankuj = function() {
            $tabulka.find('tbody tr').hide()
                .slice(aktStranka * pocetNaStrance,
                    (aktStranka + 1) * pocetNaStrance)
                .show();
        };
        strankuj();

        var pocetRadku = $tabulka.find('tbody tr').length;
        var pocetStranek = Math.ceil(pocetRadku / pocetNaStrance);
        var $ovladac = $('<div class="ovladac"></div>');

        for (var stranka = 0; stranka < pocetStranek; stranka++) {
            $('<span class="cislo"></span>').text(stranka + 1)
                .bind('click', {novaStranka: stranka}, function(event) {
                    aktStranka = event.data['novaStranka'];
                    strankuj();
                    $(this).addClass('aktOdkaz')
                        .siblings().removeClass('aktOdkaz');
                }).appendTo($ovladac);
        }
        $tabulka.after($ovladac);
        $ovladac.find('span.cislo:first').addClass('aktOdkaz');
    });

    function decodeHtml(html) {
        var txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
    }

   $("#inductiveForm_DevType").change(function() {
        //var decoded = decodeHtml(coilsDescOptions);
        //alert(decoded);
        $("#inductiveForm_Description").empty();
        $("#inductiveForm_Quality").empty();
        var chosenType = $(this).val();
        if(chosenType == 'Coils') {
            $("#inductiveForm_Description").append(decodeHtml(coilsDescOptions));
            $("#inductiveForm_Quality").append(decodeHtml(coilsQualityOptions));
        }
        else {
            $("#inductiveForm_Description").append(decodeHtml(transDescOptions));
            $("#inductiveForm_Quality").append(decodeHtml(transQualityOptions));
        }
        $("#inductiveForm_Description option[value='Worst case']").prop("selected", "selected");
        $("#inductiveForm_Quality option[value='Lower']").prop("selected", "selected");

    });

   var lastChosen = $("#diodeRFForm_DiodeType").val();
   if(lastChosen == "Schottky")
       $("#diodeRFForm_Quality option[value='Plastic']").remove();

   $("#diodeRFForm_DiodeType").change(function() {
        var chosenType = $(this).val();
       //alert(chosenType);
        if(chosenType == "Schottky" && lastChosen != 'Schottky') {
            $("#diodeRFForm_Quality option[value='Plastic']").remove();
        }
        else if (chosenType != "Schottky" && lastChosen == "Schottky") {
            $("#diodeRFForm_Quality").append('<option value="Plastic"> Plastic </option>');
        }

        lastChosen = chosenType;
   });

    function disableEEPROM () {
        $("#memoryForm_ECC").attr('disabled', 'disabled');
        $("#memoryForm_EepromOxid").attr('disabled', 'disabled');
        $("#memoryForm_CyclesCount").attr('disabled', 'disabled');
    }

    var memType = $("#memoryForm_MemoryType").val();
    if(memType != "EEPROM") {
        disableEEPROM();
    }

    $("#memoryForm_Description").change(function() {
        //var decoded = decodeHtml(coilsDescOptions);
        //alert(decoded);
        $("#memoryForm_MemoryType").empty();

        var chosenType = $(this).val();
        if(chosenType == 'MOS') {
            $("#memoryForm_MemoryType").append(decodeHtml(memoryMosChoices));
        }
        else {
            $("#memoryForm_MemoryType").append(decodeHtml(memoryBipolarChoices));
            disableEEPROM();
        }

    });

    $("#memoryForm_MemoryType").change(function() {
        var chosenType = $(this).val();
        if(chosenType == "EEPROM") {
            $("#memoryForm_ECC").removeAttr('disabled');
            $("#memoryForm_EepromOxid").removeAttr('disabled');
            $("#memoryForm_CyclesCount").removeAttr('disabled');
        }
        else {
            disableEEPROM();
        }
    });

    $(".lam").each(function(){
        var val = $(this).text();
        //alert((Number(val)).toExponential(3));
        $(this).text((Number(val)).toExponential(3));
    });


});