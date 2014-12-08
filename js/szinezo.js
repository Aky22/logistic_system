var m_ruhakod="Xy000000";
var m_modellkod="F";
var m_anyagtip="j";
var m_foszinkod="02";
var m_elutoszinkod="03";	
var m_csikszinkod="05";
var modellek = [ "A", "B", "C", "E", "F", "G", "I"  ];
var szinektomb = [ "01", "02", "03", "04", "05", "06", "07", "09",  "11",  "13", "14", "15", "16",  "18",  "20", "21", "22", "23", "24", "25",  "28", "29", "_1", "_2", "_3"  ];

    


$(document).ready(function() {
	

	
     if (navigator.userAgent.match(/msie/i) ){
        alert('Kérem használjon Chrome, FireFox vagy Opera Böngészőt a weblap megtekintéséhez!');
      }


$("#szinek td").on( "click", function() {
	

	var kattszinkod = $(this).text();
	var kattreszkod = $(this).parent().parent().parent().attr('id');
	
	for ( var i = 0; i < modellek.length; i = i + 1 ) {
	$('#'+ modellek[ i ] + 'm' + (kattreszkod.charAt(1)) ).css({'background-image': 'url("http://revanstexmunkaruha.hu/revanstexmunkaruha_hu/userfiles/rotates/szinezo/' + modellek[i] + '/' + kattszinkod +'.jpg")'});	}
	
	if (kattreszkod.charAt(1)=="0"){
		m_foszinkod=kattszinkod;
	}
	if (kattreszkod.charAt(1)=="1"){
		m_elutoszinkod=kattszinkod;
	}
	if (kattreszkod.charAt(1)=="2"){
		m_csikszinkod=kattszinkod;
	}
	
	$("#" + kattreszkod +" td").each(function(i){
		$(this).css({"border-radius":"3px"});
	});
	
	$(this).css({"border-radius":"25px"});
	
	
	
	ruhakodbeir();
	
	
		
});

valaszt("I");
anyagokfel(); 


	
	for ( var i = 0; i < modellek.length; i = i + 1 ) {
	$('#'+ modellek[ i ] + 'm' + ("0") ).css({'background-image': 'url("http://revanstexmunkaruha.hu/revanstexmunkaruha_hu/userfiles/rotates/szinezo/' + modellek[i] + '/' + m_foszinkod +'.jpg")'});	}
	for ( var i = 0; i < modellek.length; i = i + 1 ) {
	$('#'+ modellek[ i ] + 'm' + ("1") ).css({'background-image': 'url("http://revanstexmunkaruha.hu/revanstexmunkaruha_hu/userfiles/rotates/szinezo/' + modellek[i] + '/' + m_elutoszinkod +'.jpg")'});	}
for ( var i = 0; i < modellek.length; i = i + 1 ) {
	$('#'+ modellek[ i ] + 'm' + ("2") ).css({'background-image': 'url("http://revanstexmunkaruha.hu/revanstexmunkaruha_hu/userfiles/rotates/szinezo/' + modellek[i] + '/' + m_csikszinkod +'.jpg")'});	}


	
	ruhakodbeir();


});

$('#anyagmenu div').on("click", function(){
   anyagokfel(); 
});




function anyagokfel(){
	$(".szinsor").hide();
	$("#anyag").show();
	
}



function anyagv(anyagtip){
	
	m_anyagtip=anyagtip;
	$("#anyag").hide();
	$("#"+m_anyagtip + "0").show();
	$("#"+m_anyagtip + "1").show();
	$("#"+m_anyagtip + "2").show();
	$("#ruhakod").val("" + m_modellkod + m_anyagtip +"-"+m_foszinkod+"-"+m_elutoszinkod + "-" + m_csikszinkod);
	ruhakodbeir();
	
}	
	
  

function valaszt(valasztottm){
	
	 m_modellkod= valasztottm;
		ruhakodbeir();
	$(".ruhak").each(function(i){
	$(this).hide();
	});
			
	$('#'+m_modellkod+'ma').show();
	
}

function ruhakodbeir(){
	var rk = "" + m_modellkod + m_anyagtip +"-"+m_foszinkod+"-"+m_elutoszinkod + "-" + m_csikszinkod;
	
	
	$("#ruhakod").val("" + m_modellkod + m_anyagtip +"-"+m_foszinkod+"-"+m_elutoszinkod + "-" + m_csikszinkod);
}


