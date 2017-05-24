/**
 * Archivo con funciones generales que se utilizan comunmente en todos los proyectos y no suelen tocarse
 *
 * - Tabs simples para mostrar informacion en pestañas, usado frecuentemente en el product_info
 * - Comentarios usado en product_info y puntación con estrellas en el comentario
 * - Busqueda autocomplete usado en el buscador general de la web. Suele ser el que se encuentra en la cabecera de la web
 * - Filtro y paginacion por post, utilizado cuando el formulario de ordenar y filtrar del product_listing es mediante POST
 * - Paginacion por AJAX, cuando el listado de productos se realiza mediante ajax
 * - Si el filtro del product_listing tiene para mostrar X resultados, realizamos que el combobox cuando se seleccione envie el form
 * - Busqueda avanzada, filtro de fabricantes, categorias, paginacion, etc
 * - Implementacion del metodo "help" para que los inputs se le añadan un valor predeterminado
 */

// Variables
var vdxFranky, vdxLib, sBusquedaAvanzadaHtml, vdxSliderRangeBusqueda, tmKey;
var rqAjax = new Request();
var bBusquedaCambiada = false;

function getCookie(name)
{
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else
    {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
        end = dc.length;
        }
    }
    return unescape(dc.substring(begin + prefix.length, end));
}
 

window.addEvent( "domready", function()
{
	// Librerias
	vdxLib = new dxLib();
    vdxFranky = new dxFranky();

	// Tabs
    $$( "div.tab .tab-top a" ).each( function(i)
    {
        i.addEvent( "click", function()
        {
            $( i.getParent().getElement(".actv").removeClass( "actv" ).getProperty("rel") + "-0" ).setStyle( "display", "none" );
            $( i.addClass( "actv" ).getProperty("rel") + "-0" ).setStyle( "display", "" );
        } );
    });

	// Comentarios
	var nPuntoComentario = 0;

	$$( "#cmtr-wrte-ratg .cmtr-ratg" ).addEvents(
	{
		"click": function()
		{
			nPuntoComentario = $("rating").get( "value" );
		},

		"mouseenter": function()
		{
			nPuntoComentario = 0;
		},

		"mousemove": function(e)
		{
			nPosicion = e.page.x - this.getPosition().x;

			if( nPosicion >= 0 && nPosicion <= 16 )
			{
				this.setStyle( "backgroundPosition", "0px -16px" );
				$("rating").set( "value", 1 );
			}

			if( nPosicion >= 16 && nPosicion <= 32 )
			{
				this.setStyle( "backgroundPosition", "0px -32px" );
				$("rating").set( "value", 2 );
			}

			if( nPosicion >= 32 &&nPosicion <= 48 )
			{
				this.setStyle( "backgroundPosition", "0px -48px" );
				$("rating").set( "value", 3 );
			}

			if( nPosicion >= 48 &&nPosicion <= 64 )
			{
				this.setStyle( "backgroundPosition", "0px -64px" );
				$("rating").set( "value", 4 );
			}

			if( nPosicion >= 64 &&nPosicion <= 80 )
			{
				this.setStyle( "backgroundPosition", "0px -80px" );
				$("rating").set( "value", 5 );
			}
		},

        "mouseleave": function()
        {
			if( nPuntoComentario == 0 )
			{
				this.setStyle( "backgroundPosition", "0px 0px" );
				$("rating").set( "value", 0 );
			}
        }
	});

    // Busqueda autocomplete
    new Autocompleter.Request.HTML( $('buscar'), 'search_autocomplete.php',
    {
        "width": "500",
        overflow : true,
        "injectChoice": function(choice)
        {
            var text = choice.getElement("h6");
            var value = text.innerHTML;
            choice.inputValue = value;
            text.set('html', this.markQueryValue(value));
            this.addChoiceEvents(choice);
        },
        "onSelection" : function(e)
        {
            $("form-srch").submit();
        }
    });

	// Filtro y paginacion por post
	if( $("fltr") && $("fltr").getProperty( "method" ) == "post" )
	{
		$$(".pgnc").getElements( "a" ).each( function(elmt)
		{
			elmt.addEvent( "click", function(e)
			{
				e.stop();

				$("fltr").setProperty( "action", $("fltr").getProperty( "action" ).replace( /\?page=\d+$/g, "" ) + "?page=" + this.getProperty( "href" ).replace( /^.+=/g, "" ) ).submit();
			});
		});
	}

	// Paginacion ajax
	paginarAjax();

	// Si contiene numero el filtro para mostrar X productos
	if( $("numero") )
	{
		$("numero").addEvent( "change", function()
		{
			// Si no mostramos todos enviamos el formulario
			if( this.getProperty( "value" ) != "*" )
				this.form.submit();
			// Por el contrario realizamos un redirect especial
			else if( document.location.href == document.location.href.replace( /\?(.+)$/g, "" ) )
				document.location.href = document.location.href + "?numero=*";
			else
				document.location.href = document.location.href.replace( /&?page=(\d)+|&?numero=(\d)+/g, "" ).replace( "numero=-1", "" ).replace( /\?/g, "?numero=*&" );
		});
	}

	/* Inicio, busqueda avanzada */
	if( $("asrch") )
	{
		// Creamos el fondo para poder salir del desplegable cuando realizamos click fuera
		new Element( "div", { id: "asrch-bg" } ).injectInside( document.body ).addEvent("click", fnBusquedaAvanzadaClickCerrarForm);

		/* Inicio, mostrar box de busqueda avanzada cuando realizamos click */
		$("asrch-advc").addEvent( "click", function()
		{
			$( "asrch-advc-box" ).setStyle( "display", "block" ).fade( "in" );
			$("asrch-bg").setStyle("display","block");
		});
		/* Fin, mostrar box de busqueda avanzada cuando realizamos click */

		// Ocultamos con opacity la capa contenedora, mostrandola con block para que se posicionen bien los elementos interiores
		$("asrch-advc-box").setStyle( "opacity", 0 );
		$("asrch-advc-box").setStyle( "display", "block" );

		// Añadir eventos
		fnBusquedaAvanzadaEventos();

		// Ocultamos con display none y le devolvemos la opacidad
		$("asrch-advc-box").setStyle( "display", "none" );
	}
	/* Fin, busqueda avanzada */

	// Si no no encontramos en el resultado de busqueda avanzada llamamos al rango de precio, desde hasta
	if( $("advanced_search") )
	{
		new dxSliderRange( $("slde-rnge"), $("slde-rnge-izqd"), $( "slde-rnge-bg" ), {
			input_desde: $("precio_desde"),
			input_hasta: $("precio_hasta")
		}, $("slde-rnge-drch"));
	}
});


// Funcion que comprueba si el formulario de busqueda contiene 4 o mas caracteres
var fnCheckAdvanceSearch = function(elmt)
{
	if( elmt.getElement( 'input[name="buscar"]' ).get( "value" ).length <= 1 )
	{
		alert( "Introduzca mínimo 2 caracteres a buscar" );
		return false;
	}

	return true;
}



Element.implement({
	help: function(sTexto)
	{
		this.addEvents(
		{
			'focus' : function()
			{
			   this.setProperty( "value", (this.getProperty( "value" ) == sTexto ? "" : this.getProperty( "value" ) ) );
			},
			'blur' : function()
			{
				this.setProperty( "value", (this.getProperty( "value" ) == "" ? sTexto : this.getProperty( "value" ) ) );
			}
		});

		if( this.getProperty( "value" ) == "" )
			this.setProperty( "value", sTexto );
	}
});



var paginarAjax = function()
{
	if( $("bton-ver-mas") )
	{
		$("bton-ver-mas").addEvent( "click", function(e)
		{
			new Event(e).stop();

			// Si se esta ejecutando ajax
			if( ! rqAjax.running )
            {
				var dmBotonMasProductos = $("bton-ver-mas");

                var dmMasProductos = new Element( "div",
                {
                    styles :
                    {
						"width"     : "100%",
						"float"     : "left",
                        "height"    : 20,
                        "background" : "url('theme/web/images/general/load-smll.gif') center no-repeat"
                    }
                } ).inject( dmBotonMasProductos, "before" );

                ajax(
                {
                    evalScripts : false,
                    success : function(e, elements, html, js)
                    {
                        dmBotonMasProductos.destroy();

                        dmMasProductos.set(
                        {
                            "styles" :
                            {
                                "height"    : "auto",
                                "background" : ""
                            },
                            "html" : html
                        } );

                        $exec(js);

                        paginarAjax();
                    }
                }).send({ url : this.getProperty( "href" ) } );
            }
		} );
	}
};


var ajax = function(arg)
{
    arg.method = ($type( arg.method ) ? arg.method : "get" );
    arg.evalScripts = ($type( arg.evalScripts ) ? arg.evalScripts : true );
    arg.type = ($type( arg.type ) ? arg.type : "HTML" );
    arg.success = ($type( arg.success ) ? arg.success : function(){} );

    eval( "rqAjax = new Request" + (arg.type ? "." + arg.type : "") + "( { update : arg.update, evalScripts : arg.evalScripts, method : arg.method,onFailure : function(){alert( \"La petición no puede llevarse acabo intentalo de nuevo o pasado unos minutos.\" )},onSuccess : arg.success } );" );

    if( Browser.Engine.trident )
        rqAjax.setHeader( "If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT" );

    return rqAjax;
};


var formComentario = function()
{
    var dmForm = $("cmtr-form");
    var dmBg = $("cmtr-wrte-bg");
	var dmLoad = $("cmtr-wrte-load");

	dmBg.setStyle( "display", "block" );
	dmLoad.setStyle( "display", "block" );

	new Request.HTML(
	{
		url         : dmForm.getProperty( 'action' ),
		method      : "post",
		evalScripts : true,
		data        : dmForm,
		onFailure   : function(xhr)
		{
			if( xhr.status == 500 || xhr.status == 404 )
				alert( "Error: No se a podido procesar la petición, intentalo de nuevo más tarde." );

		},
		onSuccess : function(e, elements, html, js)
		{
			$("cmtr-wrte-ajax").set( "html", html );
			$exec( js );
			dmBg.setStyle( "display", "none" );
			dmLoad.setStyle( "display", "none" );
		}
	} ).send();
};


var fnBusquedaAvanzadaEventos = function()
{
	// Si aun no hemos cambiado algo obtenemos el html
	if( ! bBusquedaCambiada )
	{
		bBusquedaCambiada = true;
		sBusquedaAvanzadaHtml = $("asrch-advc-box").get("html");
	}

	/* Inicio, evento quitar filtros mediante tags */
	if( $("asrch-tags") )
	{
		$("asrch-tags").getElements( "a" ).addEvent( "click", function(e)
		{
			// Detenemos evento
			e.stop();

			if( this.getProperty( "rel" ) != "orden" && $("precio_desde") )
			{
				/*$("precio_desde").set( "value", "" );
				$("precio_hasta").set( "value", "" );*/
			}

			switch( this.getProperty( "rel" ) )
			{
				case "precio":
					if( $("precio_desde") )
					{
						$("precio_desde").set( "value", "" );
						$("precio_hasta").set( "value", "" );
						$("precio_anterior").set( "value", "" );
					}
				break;
				case "categoria":
					if( $("categoria") )
						$("categoria").set( "value", "" );
				break;

				case "fabricante":
					if( $("fabricante") )
						$("fabricante").set( "value", "" );
				break;

				case "orden":
					$("order").selectedIndex = 0;
					this.destroy();
					return;
				break;
			}

			// Recargamos el formulario
			fnBusquedaAvanzadaRecargar( e );
		});
	}
	/* Fin, evento quitar filtros mediante tags */

	/* Inicio, evento cuando pulsamos sobre un anchor de la lista de categoria o fabricante */
	if( $("asrch-fltr-ul-ctgr") )
	{
		$("asrch-fltr-ul-ctgr").getElements( "a" ).addEvent( "click", function(e)
		{
			$("categoria").set( "value", this.getProperty( "rel" ) );

			// Recargamos el formulario
			fnBusquedaAvanzadaRecargar( e );
		});
	}

	if( $("asrch-fltr-ul-fbct") )
	{
		$("asrch-fltr-ul-fbct").getElements( "a" ).addEvent( "click", function(e)
		{
			$("fabricante").set( "value", this.getProperty( "rel" ) );

			// Recargamos el formulario
			fnBusquedaAvanzadaRecargar( e );
		});
	}
	/* Fin, evento cuando pulsamos sobre un anchor de la lista de categoria o fabricante */


	/* Inicio, inputs desde hasta, escribir cantidad */
	$$("#precio_desde, #precio_hasta").addEvents({
		"keyup": function(e)
		{
			if( e.key == "tab" || e.key == "left" || e.key == "right" )
				return;

			clearTimeout( tmKey );
			tmKey = setTimeout( function(){ fnBusquedaAvanzadaKeyUpPrecios(this, e) }.bind(this), 600 );
		}
	});
	/* Fin, inputs desde hasta, escribir cantidad */


	// Boton cerrar formulario o pulsar nuevamente en el boton
	$$("#asrch-clse, #asrch-advc-box .asrch-advc-achr").addEvent( "click", fnBusquedaAvanzadaClickCerrarForm );

	// Inicio, hover en botones de los formularios, no funciona en CSS para IE
	$$(".form-sbmt, .form-bton").addEvents( {
		"mouseenter": function(){ this.setStyle( "opacity", "1" ) },
		"mouseleave": function(){ this.setStyle( "opacity", "0.9" ) }
	}).setStyle( "opacity", "0.9");
	// Fin, hover en botones de los formularios, no funciona en CSS para IE

	/* Inicio, slider de categoria y fabricante */
	new dxSliderMouse( "asrch-fltr-ul-ctgr", { position : "vertical", botonRepresentativo : 7 } );
	new dxSliderMouse( "asrch-fltr-ul-fbct", { position : "horizontal", botonRepresentativo : 7 } );
	/* Fin, slider de categoria y fabricante */

	// Rango de precio, desde hasta
	vdxSliderRangeBusqueda = new dxSliderRange( $("slde-rnge"), $("slde-rnge-izqd"), $( "slde-rnge-bg" ), {
		input_desde: $("precio_desde"),
		input_hasta: $("precio_hasta"),
		completeDrag: function(e)
		{
			$("precio_anterior").set( "value", $("precio_desde").get("value") + "_" + $("precio_hasta").get("value") );
			// Recargamos el formulario
			fnBusquedaAvanzadaRecargar( e );
		}
	}, $("slde-rnge-drch"));
}

var fnBusquedaAvanzadaKeyUpPrecios = function(el, e)
{
	// Variables
	var sId = el.getProperty( "id" );

	if( sId == "precio_desde" )
	{
		if( el.get("value").toInt() <= vdxSliderRangeBusqueda.options.start )
		{
			vdxSliderRangeBusqueda.dmMin.getElement("span").set("html", el.get("value") + "&nbsp;€" );
			vdxSliderRangeBusqueda.dmMin.setStyles({
				"left": vdxSliderRangeBusqueda.knob.getStyle("left").toInt() - (vdxSliderRangeBusqueda.dmMin.getSize().x / 2) + (vdxSliderRangeBusqueda.knob.getSize().x / 2)
			});
			vdxSliderRangeBusqueda.dmMin.getElement("div").setStyle("left", (vdxSliderRangeBusqueda.dmMin.getSize().x / 2) - (vdxSliderRangeBusqueda.dmMin.getElement("div").getSize().x / 2));
			$("precio_anterior").set( "value", $("precio_desde").get("value") + "_" + $("precio_hasta").get("value") );
			return;
		}

		if( el.get("value").toInt() <= vdxSliderRangeBusqueda.options.end )
		{
			vdxSliderRangeBusqueda.setMin( el.get("value").toInt() );
			vdxSliderRangeBusqueda.setMax( $("precio_hasta").get("value").toInt() );
		}
		else
		{
			alert( "El precio desde debe estar comprendido entre " + vdxSliderRangeBusqueda.options.start + " € y " + vdxSliderRangeBusqueda.options.end + " €" );
			el.set("value", vdxSliderRangeBusqueda.options.start )
			vdxSliderRangeBusqueda.setMin( vdxSliderRangeBusqueda.options.start );
			vdxSliderRangeBusqueda.setMax( vdxSliderRangeBusqueda.options.end );
			return;
		}
	}
	else
	{
		if( el.get("value").toInt() <= vdxSliderRangeBusqueda.options.end && el.get("value").toInt() >= vdxSliderRangeBusqueda.options.start )
		{
			vdxSliderRangeBusqueda.setMax( el.get("value").toInt() );
		}
		else
		{
			alert( "El precio hasta debe estar comprendido entre " + vdxSliderRangeBusqueda.options.start + " € y " + vdxSliderRangeBusqueda.options.end + " €" );
			el.set("value", vdxSliderRangeBusqueda.options.end )
			vdxSliderRangeBusqueda.setMax( vdxSliderRangeBusqueda.options.end );
			return;
		}
	}

	if( $("precio_desde").get("value").toInt() > $("precio_hasta").get("value").toInt() )
	{
		alert( "El precio desde no puede ser superior al precio hasta" );
		vdxSliderRangeBusqueda.setMin( vdxSliderRangeBusqueda.options.start );
		vdxSliderRangeBusqueda.setMax( vdxSliderRangeBusqueda.options.end );
		return;
	}

	if( $("precio_hasta").get("value").toInt() < $("precio_desde").get("value").toInt() )
	{
		alert( "El precio hasta no puede ser inferior al precio desde" );
		vdxSliderRangeBusqueda.setMin( vdxSliderRangeBusqueda.options.start );
		vdxSliderRangeBusqueda.setMax( vdxSliderRangeBusqueda.options.end );
		return;
	}

	$("precio_anterior").set( "value", $("precio_desde").get("value") + "_" + $("precio_hasta").get("value") );
	fnBusquedaAvanzadaRecargar();
}

var fnBusquedaAvanzadaClickCerrarForm = function(e)
{
	// Detenemos evento
	e.stop();

	// Cerramos
	$( "asrch-advc-box" ).fade( "out" );

	$( "asrch-advc-box" ).get('tween').addEvent( "complete", function(e)
	{
		$("asrch-advc-box").set( "html", sBusquedaAvanzadaHtml );
		bBusquedaCambiada = false;
		fnBusquedaAvanzadaEventos();
		$( "asrch-advc-box" ).setStyle( "display", "none" );
		$( "asrch-advc-box" ).get('tween').removeEvents( "complete" );
		$( "asrch-bg" ).setStyle( "display", "none" );
	});
}

var fnBusquedaAvanzadaRecargar = function(e)
{
	$("asrch-load").setStyle( "height",	$("asrch-advc-box").getSize().y - 8 );
	$("asrch-load").setStyle( "opacity", 0.8 );

	vdxLib.ajaxForm(e, {
		form: $( "asrch" ),
		url: "advanced_search_ajax.php",
		method: "get",
		success: function(arg)
		{
			// Cambiamos el html por el resultante
			$("asrch-advc-box").set( "html", arg.html );

			// Recargar eventos
			fnBusquedaAvanzadaEventos();
		}
	});
}
