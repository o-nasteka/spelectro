/**
 * Libreria para mostrar un slider dentro de una caja
 */

var dxSliderBox = new Class(
{
    initialize: function(dmSlide, dmRight, dmLeft, tmPeriodical, dmPaginador, sMovimiento)
    {
        if( dmSlide )
        {
			// Comprobamos si existe el boton derecha e izquierda, si no existe lo creamos para que no de error
			if( dmRight == null )
				this.dmRight = new Element( "a" );
			else
				this.dmRight = dmRight;

			if( dmRight == null )
				this.dmLeft = new Element( "a" );
			else
				this.dmLeft = dmLeft;
				
            this.dmSlide = dmSlide;
            this.sMovimiento = sMovimiento;

            this.dmDivs = dmSlide.getChildren();
            this.nDivs = this.dmDivs.length;
            this.nMarginR = this.dmDivs[0].getStyle( "margin-right" ).toInt();
            this.nMarginL = this.dmDivs[0].getStyle( "margin-left" ).toInt();
            this.nMarginT = this.dmDivs[0].getStyle( "margin-top" ).toInt();
            this.nMarginB = this.dmDivs[0].getStyle( "margin-bottom" ).toInt();

            this.nLeft = 0;
            this.periodicalMove = null;
            this.sMove = 'left';
            this.tmPeriodical = tmPeriodical;
            this.dmPaginador = dmPaginador;
            this.nPage = 1;


            if( sMovimiento == 'vertical' )
            {
                this.nAncho = this.dmDivs[0].getSize().y + this.nMarginT + this.nMarginB;
                this.nAnchoSlide = this.nAncho * this.nDivs;
            }
            else
            {
                this.nAncho = this.dmDivs[0].getSize().x + this.nMarginR + this.nMarginL;
                this.nAnchoSlide = this.nAncho * this.nDivs;
            }

            // Agrandamos el slider
            if( sMovimiento == 'vertical' )
                dmSlide.setStyle( "height", this.nAnchoSlide );
            else
                dmSlide.setStyle( "width", this.nAnchoSlide );

            // Periodical
            this.periodicalMove = (function(){this.moveAuto()}.bind(this)).periodical(this.tmPeriodical);

            // Eventos
            dmSlide.addEvents(
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

            this.dmRight.addEvent( "click", this.right.bind(this) ).cloneEvents( dmSlide );
            this.dmLeft.addEvent( "click", this.left.bind(this) ).cloneEvents( dmSlide );

            // Si contiene paginador
            if( dmPaginador )
            {
                // Creamos el paginador con el tamaño de divs que existen
                for( nCont = 1; nCont <= this.nDivs; nCont++ )
                {
                    dmAnchor = new Element( "a", { "href" : "javascript:void(0);", "rel" : nCont, "title" : "Slide " + nCont, "html" : nCont, "class" : (nCont == 1 ? "actv" : "") } );

                    dmAnchor.addEvent( "click", this.page.pass( nCont, this ) );

                    dmAnchor.injectInside( dmPaginador );
                }
            }
        }
    },

    page: function()
    {
        if( !this.efctMove )
        {
            nRel = arguments[0].toInt();

            if( nRel != this.nPage )
            {
                if( nRel > this.nPage )
                    this.sMove = "left";
                else
                    this.sMove = "righ";

                this.nPage = nRel;
                this.nLeft = ((nRel * this.nAncho) * (-1) + this.nAncho);

                this.move(true);
            }
        }
    },

    moveAuto: function()
    {
        if( this.sMove == 'left' )
            this.right();
        else
            this.left();
    },

    right: function()
    {
        if( !this.efctMove )
        {
            if( this.nLeft > (this.nAnchoSlide*-1) + this.nAncho )
            {
                this.sMove = "left";
                this.nLeft -= this.nAncho;
                this.move(false);
            }
            else
                this.sMove = "right";
        }
    },

    left: function()
    {
        if( !this.efctMove )
        {
            if( !this.efctMove && this.nLeft < 0 )
            {
                this.sMove = "right";
                this.nLeft += this.nAncho;
                this.move(false);
            }
            else
                this.sMove = "left";
        }
    },

    move: function(bPage)
    {
        // Si contiene paginador
        if( this.dmPaginador )
        {
            // Solo modificamos el paginador si el evento se ha realizado en los botones de desplazamiento
            if( ! bPage )
                this.nPage = ( this.sMove == "left" ? this.nPage + 1 : this.nPage - 1 );

            // Limpiamos las clases del paginador y añadior la clase a la plagina donde nos encontramos
            this.dmPaginador.getElements( "a" ).removeClass( "actv" );
            this.dmPaginador.getElement( 'a[rel="' + this.nPage + '"]' ).addClass( "actv" );
        }

        this.efctMove = new Fx.Morph( this.dmSlide,
        {
            duration: 600,
            transition: Fx.Transitions.Back.easeInOut
        } ).start(
        {
            "left" : (this.sMovimiento == 'vertical' ? 0 : this.nLeft),
            "top" : (this.sMovimiento == 'vertical' ? this.nLeft : 0)
        } );

        this.efctMove.addEvent( "onComplete", function()
        {
            this.efctMove = false;
        }.bind(this));
    }
});