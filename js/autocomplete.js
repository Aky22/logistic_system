// show auto-complete values 
$(document).on( "pageinit", ".container", function() {
  $( ".autocomplete" ).on( "listviewbeforefilter", function ( e, data ) {        
    var $ul = $(this);                        // $ul refers to the shell unordered list under the input box
    var dropdownContent = "" ;                // we use this value to collect the content of the dropdown
    $ul.html("") ;                            // clears value of set the html content of unordered list
    var $ul = $( this ),
                $input = $( data.input ),
                value = $input.val(),
                html = "";


    // on third character, trigger the drop-down
    if ( value /*&& value.length > 2*/ ) {
    // hard code some values... TO DO: replace with AJAX call



        $('.autocomplete').show();           
        $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading' ></span></div></li>" );
        $ul.listview( "refresh" );
        $.ajax({
            //url: "http://gd.geobytes.com/AutoCompleteCity",
            url: "include/employer_select.php",
            dataType: "jsonp",
            crossDomain: true,
            data: {
                q: $input.val()
            }
        })
        .then( function ( response ) {
          $.each(response, function( i, val ) {
            dropdownContent += "<li>" + val + "</li>";
            $ul.html( dropdownContent );
            $ul.listview( "refresh" );
            $ul.trigger( "updatelayout");  
          });
        });
    }
  });
});	

// click to select value of auto-complete
$( document).on( "click", ".autocomplete li", function() {      
  var selectedItem = $(this).html();
  $(this).parent().parent().find('input').val(selectedItem);
    
    $.ajax({
        url: "include/employer.php",
        dataType: "json",
        crossDomain: true,
        data: {
            q: selectedItem
        },
        success: function(data){
            $('#billingaddress').val(data['cegcim_cs']+' '+data["cegcim_sz"]);
            $('#shippingaddress').val(data['cegcim_cs']+' '+data["cegcim_sz"]);
            $('#contact').val(data['kapcs']);
            $('#tel').val(data['tel']);
        }
    })
    
  $('.autocomplete').hide();     
});