<?php

class Oraclegw extends Controller {

	function Oraclegw()
	{
		parent::Controller();
	}

        //
	function index()
	{

            /*
             *
             * Problemi di connessione con la classe DB di codeigniter
             * Risolvo connetendomi direttamente con le librerie standard oci php
             *
             *
             *
             *
             *
             */
            ////Esempio di Lettura Tabella
            $conn = oci_connect('missioni', 'missioni', '//127.0.0.1/XE');
            $query = 'select test from test';

            $stid = oci_parse($conn, $query);
            oci_execute($stid, OCI_DEFAULT);
            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
              foreach ($row as $item) {
                  echo 'valori';
                  echo $item." ";
              }
              echo "<br>\n";
            }

            oci_free_statement($stid);
            oci_close($conn);

            //Esempio di Scrittura su Tabella
            $stid = oci_parse($conn, 'INSERT INTO test (test) VALUES (:bv)');
            oci_bind_by_name($stid, ':bv', $i, 10);
            for ($i = 1; $i <= 5; ++$i) {
                    oci_execute($stid, OCI_DEFAULT);  // use OCI_DEFAULT for PHP <= 5.3.1
                }
                oci_commit($conn);
                oci_close($conn);
        }
}

?>
