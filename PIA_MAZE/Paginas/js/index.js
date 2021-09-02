$(document).ready(function(){

 if ($(window).width() > 800){			//si el tamaño de la ventana es mayor a 800px
		$('header .titulo').css({			//acceder al header y sus textos, ejecutar el metodo css y crear un objeto
			opacity: 0,						//empezará opacidad en 0
			marginTop: 0					//margen top 0
		});

		$('header .titulo').animate({ 		//animando la opacidad
			opacity: 1,						//pasar opacidad de 0 a 1
			marginTop: '62px'				//mover el margen a -52px
		},1500);							//tiempo que va a tardar(1.5s)
	}
							//tiempo que va a tardar(1.5s)

});


