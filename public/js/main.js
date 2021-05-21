var url = 'http://dawjavi.insjoaquimmir.cat/mbalague/curso2021/PRFinal/proyecto-laravel/public/';
window.addEventListener("load", function(){
	
	//Boton del clikc en forma de "puño"
	$('.btn-like').css('cursor', 'pointer');
	$('.btn-dislike').css('cursor', 'pointer');
	
	// Botón de like
	function like(){
		$('.btn-like').unbind('click').click(function(){
			console.log('like');
			//Cuando damos click convertimos en clase dislike y borramos la clase like
			$(this).addClass('btn-dislike').removeClass('btn-like');

			//Cambiar atributo a corazon rojo
			$(this).attr('src', url+'/img/heart-red.png');
			
			//Peticion AJAX para guardar en la BD el like o el dislike
			$.ajax({
				url: url+'/like/'+$(this).data('id'),
				type: 'GET',
				success: function(response){
					if(response.like){
						console.log('Has dado like a la publicacion');
					}else{
						console.log('Error al dar like');
					}
				}
			});
			
			dislike();
		});
	}
	like();
	
	// Botón de dislike
	function dislike(){
		$('.btn-dislike').unbind('click').click(function(){
			console.log('dislike');
			$(this).addClass('btn-like').removeClass('btn-dislike');
			$(this).attr('src', url+'/img/heart-black.png');
			
			//AJAX
			$.ajax({
				url: url+'/dislike/'+$(this).data('id'),
				type: 'GET',
				success: function(response){
					if(response.like){
						console.log('Has dado dislike a la publicacion');
					}else{
						console.log('Error al dar dislike');
					}
				}
			});
			
			like();
		});
	}
	dislike();
	
	/*
	// BUSCADOR
	$('#buscador').submit(function(e){
		$(this).attr('action',url+'/gente/'+$('#buscador #search').val());
	});
	*/
});