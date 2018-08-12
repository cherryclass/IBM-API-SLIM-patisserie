$( document ).ready(function() {
$('#btn-insert-gateau').click(function(){ 
var id=$('#id').val();
var nom=$('#nom').val();
$.ajax({ 
      type: "POST",
      contentType: 'application/json; charset=utf-8',
      url: "http://localhost/api-gateau/gateau?id="+id+"&nom="+nom,
     success: function(data){
          //gateau modifié
         alert("gateau enregistré");
      }
 });
});
/*$('#btn-get-gateau').click(function(){ 
    var idx=$('#idx').val();
      $.ajax({ 
      
      type: "GET",
      url: "http://localhost/api-gateau/gateau/"+idx,
      success: function(data){ 

       	$("#result").html(data);
      }
    });
  });
*/



$('#btn-get-gateau').click(function(){ 
    var idx=$('#idx').val();
    $.getJSON( "http://localhost/api-gateau/gateau/"+idx, function( data ) {
		$('#result').html(data[0].nom);
		
    });
  });

$('#btn-get-gateaux').click(function(){ 
      $('#table-gateau').bootstrapTable({
            url: 'http://localhost/api-gateau/gateaux',
            columns: [{
                field: 'id',
                title: 'Item ID'
            }, {
                field: 'nom',
                title: 'Item Name'
            },]
        });

      /*$.ajax({ 
      type: "GET",
      url: "http://localhost/api-gateau/gateaux",
      success: function(data){  
        $("#result2").html(data);
        
      }
    });*/
  });
 }); 