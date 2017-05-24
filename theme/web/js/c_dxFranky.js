/**
 * Libreria para mostrar el lightbox
 */

var dxFranky = new Class(
{
    Implements: Options,

    opti:
    {
        id       : "mdal",
        rel      : "frky",
        zind     : 60000,
        imgLoad  : "theme/web/images/general/load-smll.gif",
        img404   : "theme/web/images/general/mdl-404.gif",
        mdalStyl : { "width" : 380, "height": 170 },
        bgOvrl   : "#e7e7e7",
        show     : false,
        modal    : false,
        timeMove : 800,
        efctMove : Fx.Transitions.Back.easeInOut,
        timeRsiz : 800,
        efctRsiz : Fx.Transitions.Back.easeInOut,
        timeOvrl : 200,
        efctOvrl : Fx.Transitions.linear,
        opctOvrl : 0.6,
        timeClse : 400,
        efctClse : Fx.Transitions.linear
    },

    initialize: function(options)
    {
        // Opciones
        this.setOptions( this.opti, options );

        // Creamos los elementos primarios
        // Elemento overlay para el fondo
        this.ovrl = new Element('div',
        {
            'styles':
            {
                'position'   : 'absolute',
                'top'        : 0,
                'left'       : 0,
                'opacity'    : 0,
                'height'     : 0,
                'width'      : 0,
                'z-index'    : this.options.zind,
                'background' : this.options.bgOvrl
            }
        } );

        // Contenedor de la ventana modal
        this.mdal = new Element( "div",
        {
            "id"     : this.options.id,
            "styles" :
            {
                "z-index"  : this.options.zind + 1,
                "display"  : "none",
                "position" : "absolute"
            }
        } );

        // Boton para cerrar la ventana modal
        this.mdalClse = new Element( "div",
        {
            "id" : this.options.id + "-clse"
        } );

        this.tble = new Element ( "table", { "html" : '<tr><td id="' + this.options.id + '-top-left"></td><td id="' + this.options.id + '-top"></td><td id="' + this.options.id + '-top-drch"></td></tr><tr><td id="' + this.options.id + '-left"></td><td id="' + this.options.id + '-cntr"></td><td id="' + this.options.id + '-drch"></td></tr><tr><td id="' + this.options.id + '-btom-left"></td><td id="' + this.options.id + '-btom"></td><td id="' + this.options.id + '-btom-drch"></td></tr>' } );

        // Pintamos los elementos
        this.mdalClse.injectInside( this.mdal );
        this.tble.injectInside( this.mdal );
        this.ovrl.injectInside( document.body );
        this.mdal.injectInside( document.body );

        // Creamos los elementos secundarios
        // Div que contendra el contenido del modalbox
        this.cntdMdal = new Element( "div", { "id" : this.options.id + "-cntd" } );

        // Div para el caption y la navegacion
        this.mdalCntdBtom = new Element( "div", { "id" : this.options.id + "-cntd-btom" } ).setStyle( "display", "none" );

        // Caption
        this.mdalCaption = new Element( "b" );

        // Div de navegacion hacia atras
        this.mdalPrev = new Element( "div", { "id" : this.options.id + "-prev" } ).setStyle( "display", "none" );

        // Div de navegacion hacia delante
        this.mdalNext = new Element( "div", { "id" : this.options.id + "-next" } ).setStyle( "display", "none" );

        // Pintamos los elementos
        this.mdalPrev.injectInside( this.mdalCntdBtom );
        this.mdalCaption.injectInside( this.mdalCntdBtom );
        this.mdalNext.injectInside( this.mdalCntdBtom );
        this.cntdMdal.injectInside( $(this.options.id + "-cntr") );
        this.mdalCntdBtom.injectInside( $(this.options.id + "-cntr") );

        // Añ¡¤©mos los eventos
        this.mdalClse.addEvent( "click", function()
        {
            this.close();
        }.bind( this ) );

        this.ovrl.addEvent( "click", function()
        {
            if( !this.options.modal )
                this.close();
        }.bind( this ) );

        document.addEvent( "keydown", function(event)
        {
            if( this.options.show )
                if( event.key == "esc")
                    this.close();

            if( event.key == "left" && this.mdalPrev.getStyle( "display" ) != "none" && this.imgImage.bLoad )
                this.mdalPrev.fireEvent( "click", event );

            if( event.key == "right" && this.mdalNext.getStyle( "display" ) != "none" && this.imgImage.bLoad )
                this.mdalNext.fireEvent( "click", event );
        }.bind( this ) );

        window.addEvents(
        {
            "resize" : function()
            {
                if( this.options.show )
                    this.positionBox();
                else
                    this.resetBox();
            }.bind(this),

            "scroll" : function()
            {
                if( this.options.show )
                    this.positionBox();
            }.bind(this)
        } );

        // Eventos de navegacion
        this.mdalPrev.addEvent( "click", this.navigator.pass( this.mdalPrev, this ) );
        this.mdalNext.addEvent( "click", this.navigator.pass( this.mdalNext, this ) );

        // Posicionamos el modalbox en su posicion de partida
        this.resetBox();

        // Creamos todos los eventos para los anchor's que usen modalbox
        this.converterAnchors();
    },

    // Metodo que remplaza el evento click de los anchors para usar el modalbox
    converterAnchors: function()
    {
        this.navegador = [];

        $$( "a" ).each( function(elmt)
        {
            if( elmt.getProperty( "rel" ) && elmt.getProperty( "rel" ).test( "^" + this.options.rel ) )
            {
                elmt.addEvent( "click", function()
                {
                    elmt.blur();
                    this.show( elmt );

                    return false;
                }.bind( this ) );

                // Si es un grupo de imagenes
                if( elmt.getProperty('rel').match( /\[g[0-9]+]/ ) )
                {
                    nGrupo = elmt.getProperty('rel').replace( /frky\[|]/g, '' );

                    if( ! $type( this.navegador[nGrupo] ) )
                        this.navegador[nGrupo] = [];

                    elmt.index = this.navegador[nGrupo].length;

                    this.navegador[nGrupo].include( elmt );
                }
            }
        }.bind( this ) );
    },

    show: function(elmt)
    {
        // Posicionamos el modalbox en su posicion de partida
        this.resetBox();

        // Ocultamos todos los objetos que puedan interferir en el modalbox
        $$( "select", "object", "embed").each( function(elmt)
        {
            elmt.style.Visibility = "hidden";
        } );

        // Mostramos el loading
        this.cntdMdal.setStyle( "background", "url('" + this.options.imgLoad + "') no-repeat center center" );

        // Mostramos el overlay
        this.ovrl.setStyles(
        {
            "height"  : window.getScrollHeight(),
            "width"   : window.getScrollWidth(),
            "display" : ""
        } );

        this.efctOverlay = new Fx.Tween( this.ovrl,
        {
            property   : "opacity",
            duration   : this.options.timeOvrl,
            transition : this.options.efctOvrl
        } ).start( this.options.opctOvrl );

        // Mostramos caption
        this.showCaption( elmt );

        this.nGrupo = "";

        // Comprobamos si hay que mostrar la navegacion
        if( elmt.getProperty('rel').match( /\[g[0-9]+]/ ) )
        {
            this.mdalPrev.setStyle( "display", "" );
            this.mdalNext.setStyle( "display", "" );

            this.nGrupo = elmt.getProperty( "rel" ).replace( /frky\[|]/g, '' );
        }

        // Activamos de que el modalbox esta visible
        this.options.show = true;

        // Mostramos el modalbox y lo posicionamos en el centro de la pantalla
        this.mdal.setStyle( "display", "" );
        this.positionBox();

        // Mostramos el contenido
        this.showContent( elmt );
    },

    showContent: function(elmt)
    {
        // Comprobamos si la url tiene parametros que puedan ser opciones nuevas
        if( elmt.href.contains( "?" ) )
        {
            // Convertimos los parametros de URL a array
            var aParam = this.parseQuery( elmt.href.match(/\?(.+)/)[1] );

            // Cambiamos las propiedades segun los parametros
            this.options.modal = aParam['modal'];
        }

        // Cuando termine el efecto de posicionamiento realizamos las peticiones
        this.efctMoveBox.addEvent( "onComplete", function()
        {
            // Imagen
            if ( elmt.href.match( /\.(jpe?g|png|gif|bmp)/gi ) )
                this.requestImage( elmt.href, elmt.index );
            else // Petició® ¡jax
            {
                this.requestAjax(
                {
                    'url'    : elmt.href,
                    'method' : 'get'
                } );
            }
        }.bind( this ) );
    },

    navigator: function(elmt)
    {
        // Variables
        var nSize   = this.navegador[this.nGrupo].length - 1;
        var nResult = this.imgImage.index  + (elmt.getProperty( "id" ) == this.options.id + "-prev" ? -1 : +1 );

        if( nResult > nSize )
            nResult = 0;

        if( nResult < 0 )
            nResult = nSize;

        // Vaciamso el contenido del modalbox
        this.cntdMdal.empty();

        // Mostramos el caption
        this.showCaption( this.navegador[this.nGrupo][nResult] );

        // Mostramos el loading
        this.cntdMdal.setStyle( "background", "url('" + this.options.imgLoad + "') center center no-repeat" );

        // Cargamos la imagen
        this.requestImage( this.navegador[this.nGrupo][nResult].href, this.navegador[this.nGrupo][nResult].index );
    },

    // Metodo que se encarga de cargar las imagenes dentro del modalbox
    requestImage: function(url, index)
    {
        // Creamos la instancia de la nueva imagen
        this.imgImage = new Image();

        // Le añ¡¤©mos el index
        this.imgImage.index = index;

        // Estado de la imagen: no cargada
        this.imgImage.bLoad = false;

        // Cuando se haya cargado la imagen
        this.imgImage.onload = function()
        {
            // Redimensionamos la imagen si esta es mayor que la ventana
            var winSize = window.getSize();
            winSize.x -= 150;
            winSize.y -= 150;

            if( this.imgImage.width > winSize.x )
            {
                this.imgImage.height = this.imgImage.height * (winSize.x / this.imgImage.width);
                this.imgImage.width = winSize.x;

                if( this.imgImage.height > winSize.y )
                {
                    this.imgImage.width = this.imgImage.width * (winSize.y / this.imgImage.height);
                    this.imgImage.height = winSize.y;
                }
            }
            else if( this.imgImage.height > winSize.y )
            {
                this.imgImage.width = this.imgImage.width * (winSize.y / this.imgImage.height);
                this.imgImage.height = winSize.y;

                if( this.imgImage.width > winSize.x )
                {
                    this.imgImage.height = this.imgImage.height * (winSize.x / this.imgImage.width);
                    this.imgImage.width = winSize.x;
                }
            }

            // Redimensionamos el modalbox con los nuevos valores
            this.options.mdalStyl['widthR'] = this.imgImage.width + 40; /*+40 por los bordes y margenes del modalbox*/
            this.options.mdalStyl['heightR'] = this.imgImage.height;

            this.resizeBox();

            // Una vez redimensionado añ¡¤©mos la imagen
            this.efctResizeBox1.addEvent( "onComplete", function()
            {
                this.cntdMdal.setStyle( "background", "none" );
                this.cntdMdal.set( "html", '<img src="' + this.imgImage.src + '" width="' + this.imgImage.width + '" height="' + this.imgImage.height + '"/>' );

                // Estado de la imagen: cargada
                this.imgImage.bLoad = true;
            }.bind( this ) );
        }.bind( this );

        this.imgImage.onerror = function()
        {
            this.requestImage( this.options.img404, 0 );
        }.bind(this);

        this.imgImage.src = url;
    },

    // Metodo que se encarga de realizar las peticiones ajax dentro del modalbox
    requestAjax: function(arg)
    {
        arg.method      = ($type( arg.method ) ? arg.method : 'post' );
        arg.evalScripts = ($type( arg.evalScripts ) ? arg.evalScripts : false );
        arg.data        = ($type( arg.data ) ? arg.data : null );


        this.ajax = new Request.HTML(
        {
            url         : arg.url,
            method      : arg.method,
            evalScripts : arg.evalScripts,
            data        : arg.data,
            onFailure   : function(xhr)
            {
                // Comprobamos si existe error 500 o 400
                if( xhr.status == 500 || xhr.status == 404 )
                    this.requestImage( this.options.img404, 0 );
            }.bind( this ),
            onSuccess : function(e, elements, html, js)
            {
                // Creamos un elemento auxiliar para volcar la informacion obtenida por ajax para obtener su tama?o y asi redimensionar el modalbox
                var aux = new Element( "div", { "html" : html } ).setStyles( { "float" : "left", "position" : "absolute", "left" : "-9999em" } );
                aux.injectInside( document.body );

                // Nuevo ancho y alto para el modalbox
                this.options.mdalStyl['widthR'] = aux.getSize().x + 40; /*+40 por los bordes y margenes del modalbox*/
                this.options.mdalStyl['heightR'] = aux.getSize().y;

                // Eliminamos la capa auxiliar
                aux.destroy();

                // Redimensionamos el modalbox con los nuevos valores
                this.resizeBox();

                // Una vez se complete la redimension mostramos el contenido
                this.efctResizeBox1.addEvent( "onComplete", function()
                {
                    this.cntdMdal.setStyle( "background", "none" );
                    this.cntdMdal.set( "html", html );
                    $exec( js );
                }.bind( this ) );
            }.bind( this )
        } );

        if( Browser.Engine.trident )
            this.ajax.setHeader( "If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT" );

        this.ajax.send();
    },

    showCaption: function(elmt)
    {
        // Variables
        var cptn = (elmt.title || elmt.name || "");

        if( cptn )
        {
            this.mdalCaption.set( "html", cptn );
            this.mdalCntdBtom.setStyle( "display", "" );
        }

        return false;
    },

    // Metodo que cierra el modalbox y su overlay
    close: function()
    {
        // Mostramos todos los objetos que puedan interferir en el modalbox
        $$( "select", "object", "embed").each( function(elmt)
        {
                elmt.style.Visibility = "visible";
        } );

        // Cancelamos los efectos y el ajax que pueda estar en uso
        if( this.efctResizeBox1 )
            this.efctResizeBox1.cancel();

        if( this.efctMoveBox )
            this.efctMoveBox.cancel();

        if( this.ajax )
            this.ajax.cancel();

        // Cerramos el overlay
        this.efctOverlay = new Fx.Tween( this.ovrl,
        {
            property   : "opacity",
            duration   : this.options.timeOvrl,
            transition : this.options.efctOvrl,
            onComplete : function()
            {
                this.ovrl.setStyles(
                {
                    "height"  : 0,
                    "width"   : 0,
                    "display" : "none"
                } );

                // Eliminamos todo el contenido del modalbox
                this.cntdMdal.empty();
            }.bind( this )
        } ).start( 0 );

        // Ocultamos el modalbox
        this.mdal.setStyle( "display", "none" );

        // Activamos de que el modalbox se ha cerrado
        this.options.show = false;
    },

    // Metodo que resetea los valores para empezar de nuevo a mostrar modalbox
    resetBox: function()
    {
        // Posiciona el modalbox en el centro y cambia en ancho por defecto
        this.mdal.setStyles(
        {
            "top"     : -this.options.mdalStyl['height'] - 80,
            "left"    : ( window.getScroll().x + ( window.getSize().x - this.options.mdalStyl['width'] ) / 2 ).toInt(),
            "width"   : this.options.mdalStyl['width']
        } );

        // Ocultamos la barra de titulo
        this.mdalCntdBtom.setStyle( "display", "none" );

        // Ocultamos la navegacion
        this.mdalPrev.setStyle( "display", "none" );
        this.mdalNext.setStyle( "display", "none" );

        // Cambia el alto del modalbox por defecto
        this.cntdMdal.setStyles( { "height" : this.options.mdalStyl['height'] } );

        // Cambia en ancho y alto del modalbox por defecto
        this.options.mdalStyl['widthR']  = this.options.mdalStyl['width'];
        this.options.mdalStyl['heightR'] = this.options.mdalStyl['height'];
    },

    // Metodo que posiciona el modalbox en el centro de la pantalla con efecto
    positionBox: function()
    {
        // Variables
        var winSize = window.getSize();
        var winScll = window.getScroll();


        if( this.efctMoveBox )
            this.efctMoveBox.cancel();

        //Opera Bug
        if (Browser.Engine.presto)
            this.ovrl.set( "styles", {"height": 0, "width": 0} );

        // Redimensionamos el overlay
        this.ovrl.setStyles(
        {
            "height": window.getScrollHeight(),
            "width": winSize.x - (Browser.Engine.presto || Browser.Engine.webkit ? 15 : 0)
        } );

        // Efecto que hace que se mueva el modalbox al centro de la pantalla
        this.efctMoveBox = new Fx.Morph( this.mdal,
        {
            duration: this.options.timeMove,
            transition: this.options.efctMove
        } ).start(
        {
            "left" : (winScll.x + ((winSize.x - this.options.mdalStyl['widthR']) / 2)).toInt(),
            "top"  : (winScll.y + ((winSize.y - (this.options.mdalStyl['heightR'] + 80)) / 2)).toInt()
        } );
    },

    // Metodo que redimensiona el modalbox
    resizeBox: function()
    {
        // Realizamos el efecto de movimiento mientras realizamos tambien el efecto resize
        this.positionBox();

        if( this.efctResizeBox1 )
            this.efctResizeBox1.cancel();

        // Redimensionamos el ancho y el alto
        this.efctResizeBox1 = new Fx.Morph( this.mdal,
        {
            duration   : this.options.timeRsiz,
            transition : this.options.efctRsiz
        } ).start( { "width" : this.options.mdalStyl['widthR'] } );

        this.efctResizeBox1 = new Fx.Morph( this.cntdMdal,
        {
            duration   : this.options.timeRsiz,
            transition : this.options.efctRsiz
        } ).start( { "height" : this.options.mdalStyl['heightR'] } );
    },

    // Metodo que devuelve un array con los valores pasados en el query
    parseQuery: function(query)
    {
        if( !query ) return {};

        // Variables
        var params = {};
        var pairs = query.split(/[;&]/);

        for( var i = 0; i < pairs.length; i++ )
        {
            var pair = pairs[i].split('=');

            if( !pair || pair.length != 2 )
                continue;

            params[unescape( pair[0] )] = unescape( pair[1] ).replace(/\+/g, ' ');
        }

        return params;
    }
} );