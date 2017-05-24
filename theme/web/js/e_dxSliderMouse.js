/**
 * Libreria para mostrar un slider que se usa mediante el raton
 */
 
var dxSliderMouse = new Class(
{
    Implements: Options,

	// Opciones
	options:
	{
		periodical: null,
		right: null,
		left: null,
		position: null,
		botonRepresentativo: 0
	}, 

	// Propiedades
	// Elemento donde se realiza el control deslizante
	sId: null,
	// Array con las coordenadas y tamaño del elemento
	aElementInfo: null,
	// Array de accesorios donde viene los parametros a modificar cuado el control deslizante es horizontal o vertical
	aAccesorios: null,
	// Limite
	nLimit: null,
	// Posicion donde se encuentra el raton en pantalla X e Y
	aPage: null,
	// Timer para ir deslizando los elementos en la capa deslizadora
	tmMover: null,
	// Cambia el estado si el raton se encuentra arriba o a salido
	bMove: false,
	// Elemento lista
	dmUl: null,
	// Ancho de la lista
	nWidthUl: null,

	// Constructor, argumento capa identificadora donde se realizara el control deslizante
	initialize: function(sId, options)
    {
		// Si no existe retornamos
		if( ! $(sId) )
			return false;

		// Opciones
        this.setOptions( this.opti, options );
		tmPeriodical = ($type( options.periodical ) ? options.periodical : null );
		dmRight = ($type( options.right ) ? options.right : null );
		dmLeft = ($type( options.left ) ? options.left : null );
		sPosition = ($type( options.position ) ? options.position : null );
			
		// Elemento control deslizante
		this.sId = $(sId);
		
		// Elemento ul
		this.dmUl = this.sId.getElement( "ul" );

		// Botones izquierda derecha
		if( dmLeft )
		{
			this.dmLeft = $(dmLeft);
			this.dmRight = $(dmRight);
		
			// Añadimos el ancho de la lista
			// Obtenemos el tamaño del 1º elemento
			nWidth = this.dmUl.getElement("li").getSize().x;
			// Obtenemos el numero de elementos que existen
			nElementos = this.dmUl.getElements("li").length;
			// Obtenemos ancho
			nWidth += this.dmUl.getElement("li").getStyle( "marginRight" ).toInt();
			nWidth += this.dmUl.getElement("li").getStyle( "marginLeft" ).toInt();
			// Asignamos ancho
			this.nWidthUl = nWidth * nElementos;
			this.dmUl.setStyle( "width", this.nWidthUl );

			// Ancho del elemento que muestra la lista
			this.nWidthId = this.sId.getSize().x;
			// Maximo que podemos mover
			this.nMax = (this.nWidthUl - this.nWidthId - this.dmUl.getElement("li").getStyle( "marginRight" ).toInt()) * -1;
			
			// Tiempo para el timer
			this.tmPeriodical = tmPeriodical;
		}
		
		// Ocultamos el scroll
		this.sId.setStyle( "overflow", "hidden" );

		// Evento scroll
		this.sId.addEvent( "scroll", this.scroll.bind(this) );
		
		// Comprobamos si el elemento es mas alto que ancho para verificar si sera horizontal o vertical
		// Obtenemos cordenadas y tamaño el elemento
		this.aElementInfo = this.sId.getCoordinates();
		
		// Escogemos con que variables jugaremos al ser horizontal o vertical
		this.aAccesorios = (sPosition == "vertical" ? ["top","bottom","height","y"] : ["left","right","width","x"] );
	
		// Calculamos cual es el limite del scroll
		nAuxPos = 0;

		// Recorremos toda la lista
		this.sId.getElements( "li" ).each( function(elmt)
		{
			// Obtenemos coordenadas y tamaño
			aux = elmt.getCoordinates();

			// Nos quedamos con la posicion mas alta
			if( aux[this.aAccesorios[1]] > nAuxPos )
				nAuxPos = aux[this.aAccesorios[1]];
		}.bind(this));

		// Limite
		this.nLimit = this.aElementInfo[this.aAccesorios[2]] + this.aElementInfo[this.aAccesorios[0]] - nAuxPos;

		// Evento al mover el raton
		document.addEvent( "mousemove", this.mousemove.bind(this) );
	
		if( dmRight )
		{
			// Comienzo
			this.sMove = "right";
		
			// Periodical
			this.periodicalMove = (function(){this.moveAuto()}.bind(this)).periodical(this.tmPeriodical);

			// Eventos	
			this.sId.getParent().addEvents(
			{
				"mouseenter": function()
				{
					$clear( this.periodicalMove );
				}.bind(this),
				"mouseleave": function()
				{
					this.periodicalMove = (function(){this.moveAuto()}.bind(this)).periodical(this.tmPeriodical);
				}.bind(this)
			});
			
			// Eventos izquierda y derecha
			this.dmRight.addEvent( "click", function(){ this.move(this.nWidthId); }.bind(this) );
			this.dmLeft.addEvent( "click", function(){ this.move(this.nWidthId * -1); }.bind(this) );
		}
	},

	moveAuto: function()
	{
        if( this.sMove == "right" )
            this.dmRight.fireEvent( "click" );
        else
			this.dmLeft.fireEvent( "click" );
	},
	
	move: function(nPos)
	{
		// Posicion donde se encuentra la lista
		nLeft = this.dmUl.getStyle( "left" ).toInt();

		// Posicion donde moveremos
		nLeft = nLeft - nPos;

		// Si nos pasamos al movernos sera el maximo la posicion
		if( nLeft < this.nMax )
		{
			nLeft = this.nMax;
			this.sMove = "left";
		}

		// Si nos pasamos por al izquierda sera 0 la posicion inicial
		if( nLeft > 0 )
		{
			nLeft = 0;
			this.sMove = "right";
		}
		
		// Solo realizamos el efecto de movimiento cuando nos moveremos a una posicion diferente
		if( nLeft != this.dmUl.getStyle( "left" ).toInt() && !this.efctMove )
		{
			// Movemos
			this.efctMove = new Fx.Morph( this.dmUl,
			{
				duration: 1000,
				transition: Fx.Transitions.Back.easeInOut
			} ).start(
			{
				"left" : nLeft
			} );

			// Una vez completado el efecto asignamos que hemos terminado
			this.efctMove.addEvent( "onComplete", function()
			{
				this.efctMove = false;
			}.bind(this));
		}
	},	
	
	mousemove: function(e)
	{
		// Si el raton se esta moviendo encima de la capa deslizante
		if( e.page.x > this.aElementInfo.left + (this.options.botonRepresentativo * -1) && e.page.x < this.aElementInfo.right + this.options.botonRepresentativo && e.page.y > this.aElementInfo.top + (this.options.botonRepresentativo * -1) && e.page.y < this.aElementInfo.bottom + this.options.botonRepresentativo )
		{
			// Guardamos la posicion donde se encuentra el raton
			this.aPage = e.page;
			
			if( ! this.bMove )
			{
				this.bMove = true;

				this.tmMover = function()
				{
					this.sId.fireEvent( "scroll" );
				}.periodical( 50,this );
			}
		}
		// Por el contrario limpiamos el timer si se esta usando
		else
		{
			if( this.bMove )
			{
				this.bMove = false;
				$clear( this.tmMover );
			}
		}
	},	
	
	// Argumentos: 
	// nPos: Posicion del elemento que queremos ver en pantalla 
	// bTransicion: true = Movimiento con efecto | false = Mmovimiento con efecto
	scroll: function(nPos,bTransicion)
	{
		// Obtenemos la posicion de la lista
		var aUlInfo = this.dmUl.getPosition();

		// Efecto para mover la lista
		var fxUl = this.dmUl.set( "tween", { property: this.aAccesorios[0] } ).get( "tween" );
		
		// Variables del accesorio
		var top_left = this.aAccesorios[0];
		var height_width = this.aAccesorios[2];
		var y_x = this.aAccesorios[3];

		// Si nos han pasado por argumento una posicion la mostramos
		if( $chk(nPos) )
		{
			// Obtenemos las coordenadas y tamaño del elemento
			var aLiInfo = this.sId.getElements("li")[nPos].getCoordinates();

			// Calculamos
			nResult = this.aElementInfo[top_left] + (this.aElementInfo[height_width] / 2) - (aLiInfo[height_width] / 2) - aLiInfo[top_left];
			nResult = (aUlInfo[y_x] - this.aElementInfo[top_left] + nResult).limit( this.nLimit, 0 );

			// Si hemos seleccionado transicion con efecto o no
			if( bTransicion )
				fxUl.start( nResult );
			else
				fxUl.set( nResult );
		}
		else
		{
			// Calculamos
			var G = this.aElementInfo[height_width] / 3;
			var J =- 0.2;
			var nResult = 0;
			
			if( this.aPage[y_x] < (this.aElementInfo[top_left] + G) )
				nResult = (this.aPage[y_x]-this.aElementInfo[top_left]-G)*J;
			else
			{
				if(this.aPage[y_x]>(this.aElementInfo[top_left]+this.aElementInfo[height_width]-G))
					nResult = (this.aPage[y_x]-this.aElementInfo[top_left]-this.aElementInfo[height_width] + G) * J;
			}

			// Si obtenemos resultado movemos el slider
			if(nResult)
			{
				nResult = ( aUlInfo[y_x] - this.aElementInfo[top_left] + nResult ).limit( this.nLimit,0 );
				fxUl.set( nResult );
			}
		}
	}
});