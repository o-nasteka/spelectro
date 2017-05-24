/**
 * Archivo con funciones personalizadas para el proyecto
 */


window.addEvent( "domready", function()
{

	$$(".bnr-top a").addEvent( "click", function(e)
	{
		e = new Event(e).stop();
		$$("body").toggleClass("showContactForm");
		return false;
	});

	$('enviarContactForm').addEvent('click', function(e) {
		e = new Event(e).stop();
		var el = e.target;
    	var url = el.getAttribute('data-url');

		vdxLib = new dxLib();
		vdxLib.ajaxForm(e, {
			form: $( "contact_us_ajax" ),
			url: url,
			method: "post",
			success: function(arg)
			{
				alert('Se ha enviado correctamente el formulario.')
				$$("body").toggleClass("showContactForm");
			}
		});

	});

	$("CloseContactForm").addEvent( "click", function(event)
	{
		event.preventDefault();
		$$("body").toggleClass("showContactForm");
		return false;
	});
//cookie_control

	if( getCookie(dx_cookie_name) == null )
{
	$("dx-coki").setStyles({"display": "block", "opacity" : 0});
	$("dx-coki").set("tween", {duration: 1500}).fade("in");

	$("dx-coki-clse").addEvent( "click", function()
	{
		$("dx-coki").fade("out");
	});

	$("dx-coki-acpt").addEvent( "click", function()
	{
		document.cookie = dx_cookie_name + '=1; expires=Wed, 01 Jan 2050 12:00:00 GMT; path=/';
		$("dx-coki").fade("out");
	});
}
	vdxLib = new dxLib();

	// Google +1
	if( $("gogl-dnox") )
	{
		$("gogl-dnox").addEvent( "mousemove", function(e)
		{
			var dmG1 = $("___plusone_0");

			dmG1.setStyles( {"position" : "absolute", "top" : e.page.y - 10, "left" : e.page.x - 10 } );
		} );
	}
	// help
	if($("form-lgin-name"))$("form-lgin-name").help( "E-mail" );
	if($("form-lgin-pass"))$("form-lgin-pass").help( "******" );
	$("buscar").help( buscarLang );

    // Carrito
	if( $("cbcr-crrt") )
	{
		var dmUl = null;

		$("cbcr-crrt").addEvents(
		{
			"mouseenter": function()
			{
				$( "cbcr-crrt-cntd" ).setStyle( "display", "block" ).fade( "in" );
			},
			"mouseleave": function()
			{
				$( "cbcr-crrt-cntd" ).fade( "out" );
			}
		});
	}

    // Menu Español
    $$("#cbcr-menu-cntd-3 li").addEvents(
    {
        "mouseenter": function()
        {
            dmUl = this.getElement( "ul" );

            if( dmUl != null )
            {
                dmUl.setStyle( "display", "block" ).fade( "in" );
                this.addClass( "actv" );
                $$(".bt-info-3").setStyle( "background-position", "-315px -440px" );
            }
        },
        "mouseleave": function()
        {
            dmUl = this.getElement( "ul" );

            if( dmUl != null )
            {
                dmUl.fade( "out" );
                this.removeClass( "actv" );
                $$(".bt-info-3").setStyle( "background-position", "-315px -500px" );
            }
        }
    });


		if($('prdt-info-comt')){
			$('prdt-info-comt').addEvent('click', function(event) {
				event = new Event(event).stop();

        $( $$( "div.tab .tab-top a" ).getParent().getElement(".actv").removeClass( "actv" ).getProperty("rel") + "-0" ).setStyle( "display", "none" );
        $( $( "cmtr-hdng" ).addClass( "actv" ).getProperty("rel") + "-0" ).setStyle( "display", "" );

				var scroll = $('cmtr-hdng').getPosition();
				window.scrollTo(scroll.x, scroll.y);
			});
		}

    // Sliders

    // Productos destacados
	new dxSliderBox( $("dstc-sldr"), $("dstc-drch"), $("dstc-izqd"), 6000, $("dstc-pgnd") );
	new dxSliderBox( $("nvds-sldr"), $("nvds-drch"), $("nvds-izqd"), 6000, null );
	new dxSliderBox( $("ofts-sldr"), $("ofts-sldr-drch"), $("ofts-sldr-izqd"), 6000, null );

    // Cambiar de vista
    if( $("chng-vsta") || ($("asrch-vsta") && $("asrch-vsta").getElement("a")) )
    {
        $$("#chng-vsta, #asrch-vsta a").addEvent( "click", function(e)
        {
            if( e ) new Event(e).stop();

            if( this.getProperty( "class" ) == "chng-vsta-hrzt" )
            {
                $$( ".prdct-hrzt" ).removeClass( "prdct-hrzt" ).addClass( "prdct-vrtl" );
                this.removeClass( "chng-vsta-hrzt" ).addClass( "chng-vsta-vrtl" );
            }
            else
            {
                $$( ".prdct-vrtl" ).removeClass( "prdct-vrtl" ).addClass( "prdct-hrzt" );
                this.removeClass( "chng-vsta-vrtl" ).addClass( "chng-vsta-hrzt" );
            }

            new Request( {
                url: "cambiar_vista.php?c=" + this.getProperty( "class" ),
                method: 'get'
            }).send();
        });
    }


    // Menú en acordeón //

	// Variables de selección
	var cPath = "";
	var c = "";

	// Obtenemos la categoría
	var url = document.location.href;
	if( url.match( /(-c-)[0-9_]/i ) )
	{
		url = url.split( "-c-" );
		url = url[1].split( ".html" );
		cPath = url[0];
		var c = cPath.split( "_" );
	}

	// Cerramos los contenedores
	$$("#web-izqd li[class=subcat-cntd]").each(function(el)
	{
		// Obtenemos el primer UL
		var ul = el.getElements( "ul" )[0];

		// Añadimos su alto real
		ul.setProperty( "hant", ul.getSize().y );

		// Restamos el alto real de sus hijos
		ul.getElements( "li[class=subcat-cntd]" ).each(function(el2)
		{
			if( el2.getParent() == ul )
			{
				// Obtenemos los UL de los hijos
				var ul2 = el2.getElements( "ul" )[0];

				// Restamos el alto
				ul.setProperty( "hant", ul.getProperty( "hant" ).toInt() - ul2.getSize().y + 1 );
			}
		});
	});
	// Minimizamos todos los UL
	$$("#web-izqd li[class=subcat-cntd] ul").setStyle( "height", 0 );

	// Si estamos en un filtro
	if( c.length > 0 )
	{
		// Abrimos el contenedor seleccionado
		$$("#web-izqd a").each(function(a)
		{
			// Buscamos el enlace por su cPath
			if( a.getProperty( "href" ).match( new RegExp( "(-c-)(" + cPath + ")(\.html)$", "i" ) ) )
			{
				// Obtenemos el padre
				var p = a.getParent();
				var h = 0;

				// Mientras tengamos padre
				while( p != null )
				{
					// Si es un UL
					if( p.get("tag") == "ul" && p.getProperty( "id" ) != "ctgrs" && p.getProperty( "hant" ) != null )
					{
						// Ampliamos
						p.morph({ 'height': p.getProperty( "hant" ).toInt() + h });
						// Guardamos el alto anterior
						h = h + p.getProperty( "hant" ).toInt();
					}

					// Obtenemos el padre
					p = p.getParent();
				}

				return true;
			}
		});

		// Desactivamos todo
		$$("#web-izqd a[class=actv]").setProperty( "class", "" );

		// Activamos el elemento actual
		$$("#web-izqd a").each(function(a)
		{
			// Si lo encontramos lo activamos
			if( a.getProperty( "href" ).match( new RegExp( "(-c-)(" + c[0] + ")(\.html)$", "i" ) ) )
				a.setProperty( "class", "actv" );

			return true;
		});

	}

	// Evento click del menu
	$$("#web-izqd .subcat-cntd a h3").addEvent("click", function(e)
	{
		e.stopPropagation();
	});

	$$("#web-izqd a").addEvent("click", function()
	{
		// Vars
		var li = null;
		var h = this.href;
		var b = false;
		var c = true;
		var r = true;

		// Recorremos los li
		$$("#web-izqd li").each(function(el)
		{
			if( c == true )
			{
				// Si el siguiente elemento li es un contenedor
				if( b == true )
				{
					if( el.getProperty( "class" ) == "subcat-cntd" )
					{
						// Obtenemos el UL
						var ul = el.getElements( "ul" )[0];

						// Ampliamos
						if( ul.getSize().y == 0 )
						{
							// Ampliamos el UL
							ul.morph({ 'height': ul.getProperty( "hant" ) });

							// Ampliamos sus padres UL
							var par = ul.getParent().getParent()
							while( par != null && par.getProperty( "id" ) != "ctgrs" )
							{
								par.morph({ 'height': par.getSize().y + ul.getProperty( "hant" ).toInt() });
								var par = par.getParent().getParent()
							}

							// Si clicamos en un menú padre
							if( el.getParent().getProperty( "id" ) == "ctgrs" )
							{
								// Desactivamos todo
								$$("#web-izqd a[class=actv]").setProperty( "class", "" );

								// Activamos el elemento actual
								li.getElements( "a" )[0].setProperty( "class", "actv" );

								// Reducimos todos los menús principales
								$$("#web-izqd li[class=subcat-cntd]").each(function(el2)
								{
									// Si cumple condiciones
									if( el2.getParent().getProperty( "id" ) == "ctgrs" && el2.getElements( "ul" )[0].getSize().y > 0 )
									{
										// Cerramos sus hijos UL
										el2.getElements( "ul" ).morph({ 'height': 0 });
									}
								});
							}
						}
						// Reducimos
						else
						{
							// Reducimos el UL
							ul.morph({ 'height': 0 });

							// Reducimos sus padres UL
							var par = ul.getParent().getParent()
							while( par != null && par.getProperty( "id" ) != "ctgrs" )
							{
								par.morph({ 'height': par.getSize().y - ul.getSize().y });
								var par = par.getParent().getParent()
							}

							// Cerramos sus hijos UL
							ul.getElements( "ul" ).morph({ 'height': 0 });
						}

						b = false;
						r = false;
					}
					else
						c = false;
				}

				// Buscamos el li anterior al anchor clicado
				if( el.getProperty( "class" ) != "subcat-cntd" && el.getElements( "a[href=" + h + "]" ).length > 0 )
				{
					// Guardamos el li
					li = el;
					// Activamos semro
					b = true;
				}
			}
		});

		return r;
	});
});
