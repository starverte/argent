<?php
/**
 * Defines class Argent_Invoice and related functions
 *
 * @author Matt Beall
 */

/**
 * Invoice class
 *
 * Connects to database and creates invoice object.
 *
 * @author Matt Beall
 * @since 0.0.1
 */
class Argent_Invoice {

  /**
   * @var int $invoice_id The ID of the invoice
   */
  public $invoice_id;

  /**
   * @var int $inv_client_id The ID of the client being billed
   */
  public $inv_client_id;

  /**
   * @var string $inv_date The Unix timestamp of the invoice creation date and time
   */
  public $inv_date;

  /**
   * @var string $inv_due_date The Unix timestamp of the invoice due date and time
   */
  public $inv_due_date;

  /**
   * @var float $inv_amount_due The total amount due
   */
  public $inv_amount_due;

  /**
   * @var string $inv_status The status of the invoice
   */
  public $inv_status = '';

  /**
   * @var int $inv_payment_id The ID of the payment that clears the invoice
   */
  public $inv_payment_id;

  /**
   * Construct Argent_Invoice object
   *
   * Takes PDO and constructs Argent_Invoice class
   *
   * @since 0.0.1
   *
   * @param  object $invs The PHP Data Object
   */
  public function __construct( $invs ) {
    foreach ( $invs as $inv ) {
      get_class($inv);
      foreach ( $inv as $key => $value )
        $this->$key = $value;
    }
  }

  /**
   * Execute query
   *
   * Attempt to connect to database and execute SQL query
   * If successful, return results.
   *
   * @since 0.0.1
   *
   * @uses ArgentDB::connect()
   * @throws PDOException if connection or query cannot execute
   *
   * @param  string $query The SQL query to be executed
   * @return object        Data retrieved from database
   * @var    string $conn  The PHP Data Object
   */
  public static function query( $query ) {
    global $ArgentDB;
    $conn = $ArgentDB->connect();
    try {
      $query = $conn->query($query);
      do {
        if ($query->columnCount() > 0) {
            $results = $query->fetchAll(PDO::FETCH_OBJ);
        }
      }
      while ($query->nextRowset());

      $conn = null;
      
      return $results;
    }
    catch (PDOException $e) {
      $conn = null;
      die ('Query failed: ' . $e->getMessage());
    }
  }

  /**
   * Get invoice information from database
   *
   * Prepare and execute query to select invoice from database
   *
   * @since 0.0.1
   *
   * @uses self::query()
   *
   * @param  int    $invoice_id The primary key of the invoice being retrieved from the database
   * @return object         Data retrieved from database
   * @var    string $conn   The PHP Data Object for the connection
   */
  public static function get_instance( $invoice_id ) {
    global $ArgentDB;

    $invoice_id = (int) $invoice_id;

    if ( ! $invoice_id )
      return false;

    $_invoice = self::query("SELECT * FROM invoices WHERE invoice_id = $invoice_id LIMIT 1");

    return new Argent_Invoice ( $_invoice );
  }

  /**
   * Get invoice information from database
   *
   * Prepare and execute query to select invoice from database
   *
   * @since 0.0.1
   *
   * @uses self::query()
   *
   * @param  int    $invoice_id The primary key of the invoice being retrieved from the database
   * @return object         Data retrieved from database
   * @var    string $conn   The PHP Data Object for the connection
   */
  public static function get_instances( $filter = '1 = 1' ) {
    global $ArgentDB;

    $invoices = self::query("SELECT * FROM invoices WHERE $filter");

    return $invoices;
  }

  /**
   * Insert invoice into database
   *
   * Prepare and execute query to register invoice in invoices table
   *
   * @since 0.0.1
   *
   * @uses ArgentDB::insert()
   * @uses _text()
   *
   * @param int    $inv_client_id  The ID of the client being billed
   * @param int    $inv_date       The date the invoice is created
   * @param int    $inv_due_date   The date that the invoice is due
   * @param float  $inv_amount_due The total amount due
   * @param string $inv_status     The status of the invoice
   * @param int    $inv_payment_id The ID of the payment that clears the invoice
   *
   * @todo Test
   */
  public static function new_instance( $inv_client_id, $inv_date, $inv_due_date, $inv_amount_due, $inv_status = 'new', $inv_payment_id = null ) {
    global $ArgentDB;

    $inv_client_id  = (int) $inv_client_id;
    $inv_date       = date_format( date_create( $inv_date ), 'Y-m-d H:i:s' );
    $inv_due_date   = date_format( date_create( $inv_due_date ), 'Y-m-d H:i:s' );
    $inv_amount_due = number_format( $inv_amount_due, 2 );
    $inv_status     = _text( $inv_status );
    $inv_payment_id = !empty( $inv_payment_id ) ? (int) $inv_payment_id : null;

    $ArgentDB->insert('invoices', 'inv_client_id,inv_date,inv_due_date,inv_amount_due,inv_status,inv_payment_id', "$inv_client_id, '$inv_date', '$inv_due_date', '$inv_amount_due', '$inv_status', $inv_payment_id");
  }

  /**
   * Update invoice in database
   *
   * Prepare and execute query to register invoice in invoices table
   *
   * @since 0.0.1
   *
   * @uses ArgentDB::update()
   * @uses _text()
   *
   * @param int    $invoice_id     The ID of the invoice
   * @param int    $inv_client_id  The ID of the client being billed
   * @param int    $inv_date       The date the invoice is created
   * @param int    $inv_due_date   The date that the invoice is due
   * @param float  $inv_amount_due The total amount due
   * @param string $inv_status     The status of the invoice
   * @param int    $inv_payment_id The ID of the payment that clears the invoice
   *
   * @todo Test
   */
  public static function set_instance( $invoice_id, $inv_client_id = null, $inv_date = null, $inv_due_date = null, $inv_amount_due = null, $inv_status = null, $inv_payment_id = null ) {
    global $ArgentDB;
    
    $invoice_id    = (int) $invoice_id;
    $inv_client_id = !empty($inv_client_id) ? (int) $inv_client_id : $_invoice->inv_client_id;
    
    if (!empty($inv_date)) :
      $inv_date = date_format( date_create( $inv_date ), 'Y-m-d H:i:s' );
    else :
      $inv_date = $_invoice->inv_date;
    endif;
    
    if (!empty($inv_due_date)) :
      $inv_due_date = date_format( date_create( $inv_due_date ), 'Y-m-d H:i:s' );
    else :
      $inv_due_date = $_invoice->inv_date;
    endif;
    
    $inv_amount_due = !empty($inv_amount_due) ? number_format( $inv_amount_due, 2 ) : $_invoice->inv_amount_due;
    $inv_status     = !empty($inv_status)     ? _text( $inv_status )                : $_invoice->inv_status;
    $inv_payment_id = !empty($inv_payment_id) ? (int) $inv_payment_id               : $_invoice->inv_payment_id;

    $ArgentDB->update('invoices', 'inv_client_id,inv_date,inv_due_date,inv_amount_due,inv_status,inv_payment_id', "$inv_client_id,'$inv_date','$inv_due_date','$inv_amount_due','$inv_status','$inv_payment_id'", "invoice_id = $invoice_id");
  }
}

/**
 * Create invoice
 *
 * @since 0.0.1
 *
 * @uses Argent_Invoice::new_instance() Constructs Argent_Invoice class and gets class object
 *
 * @param int    $inv_client_id  The ID of the client being billed
 * @param string $inv_date       The date the invoice is created
 * @param string $inv_due_date   The date that the invoice is due
 * @param float  $inv_amount_due The total amount due
 */
function create_invoice( $inv_client_id, $inv_date, $inv_due_date, $inv_amount_due ) {
  $inv = Argent_Invoice::new_instance( $inv_client_id, $inv_date, $inv_due_date, $inv_amount_due );
  return $inv;
}

/**
 * Update invoice
 *
 * @since 0.0.1
 *
 * @uses Argent_Invoice::set_instance() Constructs Argent_Invoice class and gets class object
 *
 * @param int    $invoice_id     The ID of the invoice
 * @param int    $inv_client_id  The ID of the client being billed
 * @param string $inv_date       The date the invoice is created
 * @param string $inv_due_date   The date that the invoice is due
 * @param float  $inv_amount_due The total amount due
 * @param string $inv_status     The status of the invoice
 * @param int    $inv_payment_id The ID of the payment that clears the invoice
 */
function update_invoice( $invoice_id, $inv_client_id = null, $inv_date = null, $inv_due_date = null, $inv_amount_due = null, $inv_status = null, $inv_payment_id = null ) {
  return Argent_Invoice::set_instance( $invoice_id, $inv_client_id, $inv_date, $inv_due_date, $inv_amount_due, $inv_status, $inv_payment_id );
}

/**
 * Get the Argent_Invoice class
 *
 * @since 0.0.1
 *
 * @uses Argent_Invoice::get_instance() Constructs Argent_Invoice class and gets class object
 *
 * @param  int    $invoice_id The ID of the invoice to get
 * @return object $inv The Argent_Invoice class with the invoice's data
 */
function get_invoice( $invoice_id ) {
  $invoice_id = (int) $invoice_id;
  return Argent_Invoice::get_instance( $invoice_id );
}

/**
 * Get the Argent_Invoice class
 *
 * @since 0.0.1
 *
 * @uses Argent_Invoice::get_instance() Constructs Argent_Invoice class and gets class object
 *
 * @param  int    $invoice_id The ID of the invoice to get
 * @return object $inv The Argent_Invoice class with the invoice's data
 */
function get_invoices( $filter = '1 = 1' ) {
  global $ArgentDB;
  $_invoices = $ArgentDB->query("SELECT invoice_id FROM invoices WHERE $filter ORDER BY invoice_id");
  $invoices = array();
  foreach ($_invoices as $invoice) 
    array_push($invoices, Argent_Invoice::get_instance( $invoice->invoice_id ));
  return $invoices;
}

/**
 * Get specific data from a invoice object
 *
 * @since 0.0.1
 *
 * @param  object $inv The Argent_Invoice class containing the data for a invoice
 * @param  string $key The name of the field to be retrieved
 * @return mixed       The value of the data retreived
 */
function get_invoice_data( $inv, $key ) {
  if (!empty($inv))
    return $inv->$key;
  else
    echo 'ERROR: There is no data in the invoice object.';
    die;
}

/**
 * Get the client associated with the invoice
 *
 * @since 0.0.1
 *
 * @uses get_invoice_data()
 *
 * @param object $inv           The Argent_Invoice class containing the data for the invoice
 * @var   int    $inv_client_id The ID of the client being billed
 *
 * @return object The Argent_Client class containing the data for the client associated with the invoice
 */
function get_invoice_client( $inv ) {
  $inv_client_id = get_invoice_data( $inv , 'inv_client_id' );
  return get_client( $inv_client_id );
}

/**
 * Get the date the invoice was created
 *
 * @since 0.0.1
 *
 * @uses get_invoice_data()
 *
 * @param object $inv      The Argent_Invoice class containing the data for the invoice
 * @var   string $inv_date The invoice creation date and time
 *
 * @return string Formatted invoice creation date
 */
function get_invoice_date( $inv, $format = 'F j, Y' ) {
  $inv_date = date_create(get_invoice_data( $inv , 'inv_date' ));
  return date_format( $inv_date, $format );
}

/**
 * Get the date the invoice payment is due
 *
 * @since 0.0.1
 *
 * @uses get_invoice_data()
 *
 * @param object $inv          The Argent_Invoice class containing the data for the invoice
 * @var   string $inv_due_date The invoice due date (and time)
 *
 * @return string Formatted invoice creation date
 */
function get_invoice_due_date( $inv, $format = 'F j, Y' ) {
  $inv_due_date = date_create(get_invoice_data( $inv , 'inv_due_date' ));
  return date_format( $inv_due_date, $format );
}

/**
 * Get the amount due on the invoice
 *
 * @since 0.0.1
 *
 * @uses get_invoice_data()
 *
 * @param object $inv            The Argent_Invoice class containing the data for the invoice
 * @var   string $inv_amount_due The amount due of the invoice
 *
 * @return string Formatted decimal amount due
 */
function get_invoice_amount_due( $inv ) {
  $inv_amount_due = get_invoice_data( $inv , 'inv_amount_due' );
  return number_format( $inv_amount_due, 2 );
}

/**
 * Get the date the invoice payment is due
 *
 * @since 0.0.1
 *
 * @uses get_invoice_data()
 *
 * @param object $inv The Argent_Invoice class containing the data for the invoice
 *
 * @return string The status of the invoice
 */
function get_invoice_status( $inv ) {
  return get_invoice_data( $inv , 'inv_status' );
}

/**
 * Get the payment associated with the invoice
 *
 * @since 0.0.1
 *
 * @uses get_invoice_data()
 *
 * @param object $inv            The Argent_Invoice class containing the data for the invoice
 * @var   int    $inv_payment_id The ID of the payment that cleared the invoice
 *
 * @return object The Argent_Client class containing the data for the payment associated with the invoice
 */
function get_invoice_payment( $inv ) {
  $inv_payment_id = get_invoice_data( $inv , 'inv_payment_id' );
  return get_payment( $inv_payment_id );
}

