/**
 * Libreria para facilitar el uso de metodos de mootools
 */

var dxLib = new Class(
{
	Implements: Options,

	options:
	{
		load: $("load-bg")
	},

    initialize: function(options)
    {
		// Objeto request para peticiones ajax
		this.rqAjax = new Request.HTML();
		
		// Implementamos opciones
		this.setOptions( this.options, options );
	},

	ajaxForm: function(e,arg)
	{
		// Detenemos el evento
		if( e ) e.stop();

		// Opciones
		arg.form = ($type( arg.form ) ? arg.form : null );
		arg.success = ($type( arg.success ) ? arg.success : function(){} );
		arg.url = ($type( arg.url ) ? arg.url : arg.form.getProperty( "action" ) );
		arg.method = ($type( arg.method ) ? arg.method : "post" );
		arg.load = ($type( arg.load ) ? arg.load : null );
		
		// Realizamos la peticion ajax
		this.ajax({
			data: arg.form,
			success: arg.success,
			url: arg.url,
			method: arg.method
		});
	},

	// Retorna true o false si se puede realizar la peticion ajax
	ajax: function(arg)
	{
		// Si no se esta procesando ninguna petición ajax

		if( ! this.rqAjax.running )
		{
			// Opciones
			arg.method = ($type( arg.method ) ? arg.method : "post" );
			arg.evalScripts = ($type( arg.evalScripts ) ? arg.evalScripts : true );
			arg.type = ($type( arg.type ) ? arg.type : "HTML" );
			arg.success = ($type( arg.success ) ? arg.success : null );
			arg.data = ($type( arg.data ) ? arg.data : "" );
			arg.url = ($type( arg.url ) ? arg.url : "" );
			arg.load = ($type( arg.load ) ? arg.load : this.options.load );
			arg.showLoad = ($type( arg.showLoad ) ? true : false );

			// Mostramos loadding
			if( arg.showLoad )
				arg.load.setStyle( "display", "block" );

			// Añadimos las opciones
			this.rqAjax.setOptions(
			{
				url: arg.url,
				data: arg.data,
				update: arg.update,
				evalScripts: arg.evalScripts,
				method: arg.method,
				onFailure: function()
				{
					alert( "La petición no puede llevarse acabo intentalo de nuevo o pasado unos minutos.");
				},
				onSuccess: function(e, elements, html, js)
				{
					// Recreamos el objeto ajax
					this.rqAjax = new Request.HTML();

					// Ocultamos loadding
					if( arg.showLoad )
						arg.load.setStyle( "display", "none" );

					// Llamamos a la fucion success
					if( arg.success != null )
					{
						arg.success = arg.success.pass( {e: e, elements: elements, html: html, js: js} );
						arg.success();
					}
				}.bind(this)
			} );

			// No guardamos en cache ninguna peticion
			if( Browser.Engine.trident )
				this.rqAjax.setHeader( "If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT" );

			// Realizamos la peticion
			this.rqAjax.send();

			return true;
		}
		else
			return false;
	}
} );