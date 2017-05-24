<?php


$column_query = tep_db_query('select configuration_column AS cfgcol, configuration_title AS cfgtitle, configuration_value AS cfgvalue, configuration_key AS cfgkey, box_heading 
							  from ' . TABLE_THEME_CONFIGURATION . ' 
							  WHERE configuration_value = \'yes\' AND configuration_column = \'' . $myColumn  . '\' 
							  ORDER BY location');

while( $column = tep_db_fetch_array($column_query) ) 
{
	$column['cfgtitle'] = str_replace( array(' ', "'"), array( '_', '' ), $column['cfgtitle'] );
	$sFile = $column['cfgtitle'] . '.php';
  
	if( file_exists( DIR_WS_BOXES . $sFile ) ) 
	{
		switch( $sFile )
		{
			case 'categories.php':
				if( USE_CACHE == 'true' && empty( $SID ) )
					echo tep_cache_ . $column['cfgtitle'] . _box();
				else
					require( DIR_WS_BOXES . $sFile );
				break;

			case "manufacturers":
				if( USE_CACHE == 'true' && empty( $SID ) )
					echo tep_cache_ . $column['cfgtitle'] . _box();
				else
					require(DIR_WS_BOXES . $sFile );
			break;

			case "order_history":
				if( tep_session_is_registered( 'customer_id' ) )
					require( DIR_WS_BOXES . $sFile );
			break;

			default:
				require( DIR_WS_BOXES . $sFile );
			break;
		}
	}
}
?>