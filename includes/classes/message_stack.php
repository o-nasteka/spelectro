<?php
  class messageStack extends tableBox {

// class constructor
    function __construct() {
      global $messageToStack;

      $this->messages = array();

      if (tep_session_is_registered('messageToStack')) {
        for ($i=0, $n=sizeof($messageToStack); $i<$n; $i++) {
          $this->add($messageToStack[$i]['id'], $messageToStack[$i]['text'], $messageToStack[$i]['type'], $messageToStack[$i]['nuevo']);
        }
        tep_session_unregister('messageToStack');
      }
    }

// class methods
    function add($id, $message, $type = 'error', $nuevo = false) {
		// Si es nuevo la forma de mostrar mensajes insertamos los mensajes en grupo de errores en vez de crear una lista de arrays
		if( $nuevo && isset( $this->messages[$id] ) )
		{
			$this->messages[$id]['text'] = $this->messages[$id]['text'] . '<br/>- ' . $message;
		}
		elseif( $nuevo )
		{
		  if ($type == 'error') {
			$this->messages[$id] = array( 'class' =>'eror', 'params' => 'class="messageStackError"', 'id' => $id, 'text' => '- ' . $message);
		  } elseif ($type == 'warning') {
			$this->messages[$id] = array( 'class' =>'wrng', 'params' => 'class="messageStackWarning"', 'id' => $id, 'text' => '- ' . $message);
		  } elseif ($type == 'success') {
			$this->messages[$id] = array( 'class' => 'crrt', 'params' => 'class="messageStackSuccess"', 'id' => $id, 'text' => '- ' . $message);
		  } else {
			$this->messages[$id] = array( 'class' => 'eror', 'params' => 'class="messageStackError"', 'id' => $id, 'text' => '- ' . $message);
		  }
		}
		else
		{
		  if ($type == 'error') {
			$this->messages[] = array( 'class' =>'eror', 'params' => 'class="messageStackError"', 'id' => $id, 'text' => '- ' . $message);
		  } elseif ($type == 'warning') {
			$this->messages[] = array( 'class' =>'wrng', 'params' => 'class="messageStackWarning"', 'id' => $id, 'text' => '- ' . $message);
		  } elseif ($type == 'success') {
			$this->messages[] = array( 'class' => 'crrt', 'params' => 'class="messageStackSuccess"', 'id' => $id, 'text' => '- ' . $message);
		  } else {
			$this->messages[] = array( 'class' => 'eror', 'params' => 'class="messageStackError"', 'id' => $id, 'text' => '- ' . $message);
		  }
		}
    }

	// Metodo provisional para insertar mensajes mediante session. No es el definitivo ya que oscommerce utiliza el metodo add_session para insertar mensajes en session
	// actualmente y no se puede remplazar hasta qu no estemos seguro de que todo el proyecto utilice este metodo
    function addSession($id, $message, $type = 'error') {
      global $messageToStack;

      if (!tep_session_is_registered('messageToStack')) {
        tep_session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('id' => $id, 'text' => $message, 'type' => $type, 'nuevo' => true);
    }
	
    function add_session($id, $message, $type = 'error') {
      global $messageToStack;

      if (!tep_session_is_registered('messageToStack')) {
        tep_session_register('messageToStack');
        $messageToStack = array();
      }

      $messageToStack[] = array('id' => $id, 'text' => $message, 'type' => $type, 'nuevo' => false);
    }

    function reset() {
      $this->messages = array();
    }

    function output($id) {
      $this->table_data_parameters = 'class="messageBox"';

      $output = array();
      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['id'] == $id) {
          $output[] = $this->messages[$i];
        }
      }

      return $this->tableBox($output);
    }
	
	// Metodo provisional para mostrar mensajes con estilo. No es el definitivo ya que oscommerce utiliza el metodo ouput para mostrar mensajes 
	// actualmente y no se puede remplazar hasta qu no estemos seguro de que todo el proyecto utilice este metodo
	function show($id)
	{
		if( is_array( $id ) )
		{
			$this->messages['aux'] = array( 'class' => $id['class'], 'text' => $id['text'] );
			
			$id = 'aux';
		}
	
		return ('<div class="msje msje-' . $this->messages[$id]['class'] . '">
			<div class="msje-icon"></div>
			' . $this->messages[$id]['text'] . '
		</div>');
	}
	
	// Metodo provisional para comprobar si existe el mensaje. No es el definitivo ya que oscommerce utiliza el metodo size para comprobar mensajes 
	// actualmente y no se puede remplazar hasta qu no estemos seguro de que todo el proyecto utilice este metodo
	function check($id)
	{
		if( isset( $this->messages[$id] ) && count( $this->messages[$id] ) > 0 )
			return true;
		else
			return false;
	}

    function size($id) {
      $count = 0;

      for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['id'] == $id) {
          $count++;
        }
      }

      return $count;
    }
  }
?>
