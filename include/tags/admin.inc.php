<?php

Class admin extends TagLibrary {

    function dummy() {}

    /** Create Table for a n:n association
     * $data: this row id
     * $pars: tab1_name, tab2_name, tab1id_name, tab2id_name, assoc_name, columns
     */
    function show_many2many($name, $data, $pars) {
        $result_table = new Template("show_table.html");
        $result_table->setContent("table_name", ucfirst($pars["tab2_name"]));

        $columns = ["`".$pars['tab2_name']."`.id AS fid"];
        foreach (explode(";", $pars['columns']) as $column) {
            $result_table->setContent("column_name", $column);
            $columns[] = "`".$pars['tab2_name']."`.".$column;
        }
        $result_table->setContent("column_name", "");
        $column_names = implode(", ", $columns);

        global $dbh;
        $query = "SELECT DISTINCT {$column_names} FROM {$pars['tab1_name']} " .
            " JOIN {$pars['assoc_name']} ON {$pars['tab1_name']}.id = {$pars['tab1id_name']}_id " .
            " JOIN {$pars['tab2_name']} ON {$pars['tab2_name']}.id = {$pars['tab2id_name']}_id " .
            " WHERE {$pars['tab1_name']}.id = {$data}";
        $query_prepared = $dbh->prepare($query);
        $query_prepared->execute();

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $row_id = $row['fid'];
            unset($row['fid']);

            $table_row = "";
            foreach ($row as $item) {
                $table_row .= "<td>{$item}</td>";
            }
            $table_row .= "<td class='float-right'><a href='admin_show.php?table={$pars['tab2_name']}&id={$row_id}'><i class='fas fa-caret-square-right fa-2x mr-3'></i></a></td>";
            $table_row = "<tr>" . $table_row . "</tr>";

            $result_table->setContent("table_row", $table_row);
        }

        return $result_table->get();
    }


    /** Create Table for a 1:n association
     * $data: this row id
     * $pars: tab2_name, tab1id_name, columns
     */
    function show_one2many($name, $data, $pars) {
        $result_table = new Template("show_table.html");
        $result_table->setContent("table_name", ucfirst($pars["tab2_name"]));

        $columns = ["`".$pars['tab2_name']."`.id AS fid"];
        foreach (explode(";", $pars['columns']) as $column) {
            $result_table->setContent("column_name", $column);
            $columns[] = "`".$pars['tab2_name']."`.".$column;
        }
        $result_table->setContent("column_name", "");
        $column_names = implode(", ", $columns);

        global $dbh;
        $query = "SELECT DISTINCT {$column_names} FROM {$pars['tab2_name']} " .
            " WHERE {$pars['tab1id_name']}_id = {$data}";
        $query_prepared = $dbh->prepare($query);
        $query_prepared->execute();

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $row_id = $row['fid'];
            unset($row['fid']);

            $table_row = "";
            foreach ($row as $item) {
                $table_row .= "<td>{$item}</td>";
            }
            $table_row .= "<td class='float-right'><a href='admin_show.php?table={$pars['tab2_name']}&id={$row_id}'><i class='fas fa-caret-square-right fa-2x mr-3'></i></a></td>";
            $table_row = "<tr>" . $table_row . "</tr>";

            $result_table->setContent("table_row", $table_row);
        }

        return $result_table->get();
    }

    /** Get column value for a n:1 association
     * $data: foreign row id
     * $pars: tab2_name, column
     */
    function show_many2one($name, $data, $pars) {
        global $dbh;

        $column_name = "`".$pars['tab2_name']."`.".$pars['column'];
        $query = "SELECT {$column_name} FROM {$pars['tab2_name']} " .
            " WHERE id = {$data}";
        $query_prepared = $dbh->prepare($query);
        $query_prepared->execute();

        $result = $query_prepared->fetch();
        return $result[0];
    }


    /** Create Table for a 1:n association
     * between ordini and articolo_ordine
     * associated with articoli
     * $data: this row id
     */
    function show_articoliordine($name, $data, $pars) {
        $result_table = new Template("show_table.html");
        $result_table->setContent("table_name", "Articoli Ordinati" .
            "<div class='float-right ml-5'><a href='admin_create.php?table=articolo_ordine&order_id={$data}'><button type='submit' class='btn btn-primary'><i class='fas fa-plus'></i></button></a></div>");

        $columns = ["`articolo_ordine`.id",
            "`articoli`.nome",
            "`articolo_ordine`.quantita",
            "`articolo_ordine`.prezzo"];
        $result_table->setContent("column_name", "nome");
        $result_table->setContent("column_name", "quantita");
        $result_table->setContent("column_name", "prezzo");
        $result_table->setContent("column_name", "");

        $column_names = implode(", ", $columns);

        global $dbh;
        $query = "SELECT DISTINCT {$column_names} FROM `articolo_ordine` " .
            " JOIN `articoli` ON `articoli`.id = `articolo_ordine`.articolo_id ".
            " WHERE ordine_id = {$data}";
        $query_prepared = $dbh->prepare($query);
        $query_prepared->execute();

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $row_id = $row['id'];
            unset($row['id']);

            $table_row = "";
            foreach ($row as $item) {
                $table_row .= "<td>{$item}</td>";
            }
            // edit button
            $table_row .= "<td><a href='admin_edit.php?table=articolo_ordine&id={$row_id}'>Modifica</Modifica></a></td>";

            $table_row = "<tr>" . $table_row . "</tr>";

            $result_table->setContent("table_row", $table_row);
        }

        return $result_table->get();
    }


    /** Create Table for edit a n:n association
     * $data: this row id
     * $pars: tab1_name, tab2_name, tab1id_name, tab2id_name, assoc_name, columns
     */
    function edit_many2many($name, $data, $pars){
        $result_table = new Template("edit_table.html");
        $result_table->setContent("table_name", ucfirst($pars["tab2_name"]));

        $columns = [];
        $result_table->setContent("column_name", "checked");
        foreach (explode(";", $pars['columns']) as $column) {
            $result_table->setContent("column_name", $column);
            $columns[] = "`".$pars['tab2_name']."`.".$column;
        }
        $columns[] = "`".$pars['tab2_name']."`.id";
        $column_names = implode(", ", $columns);

        global $dbh;

        // get IDs already associated with the current row
        $query = "SELECT DISTINCT {$pars['tab2id_name']}_id FROM {$pars['assoc_name']} " .
            " WHERE {$pars['tab1id_name']}_id = {$data}";
        $query_prepared = $dbh->prepare($query);
        $query_prepared->execute();
        $assoc_ids = array_column($query_prepared->fetchAll(PDO::FETCH_ASSOC), $pars['tab2id_name']."_id");


        // get all tab2 elements
        $query = "SELECT DISTINCT {$column_names} FROM {$pars['tab2_name']} ";
        $query_prepared = $dbh->prepare($query);
        $query_prepared->execute();
        $tab2_all = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tab2_all as $row) {
            $table_row = "";

            // checkbox
            if (in_array($row['id'], $assoc_ids)) $table_row = "<td><a onclick='unset_many2many(this, ".json_encode(array_merge($pars,['tab1_id'=>$data, 'tab2_id'=>$row['id']])).")'><i class='edit-checked'></i></a></td>";
            else $table_row = "<td><a onclick='set_many2many(this, ".json_encode(array_merge($pars,['tab1_id'=>$data, 'tab2_id'=>$row['id']])).")'><i class='edit-crossed'></i></a></td>";
            unset($row['id']);

            foreach ($row as $item) {
                $table_row .= "<td>{$item}</td>";
            }
            $table_row = "<tr>" . $table_row . "</tr>";

            $result_table->setContent("table_row", $table_row);
        }

        return $result_table->get();
    }

    /**
     * returns a <select> with the rows of the defined user
     * $data: user ID
     * $pars: tab1_name, columns
     */
    function select_by_uid($name, $data, $pars){
        $columns = ["`".$pars['tab1_name']."`.id"];
        foreach (explode(";", $pars['columns']) as $column) {
            $columns[] = "`".$pars['tab1_name']."`.".$column;
        }
        $column_names = implode(", ", $columns);

        global $dbh;
        $query = "SELECT DISTINCT {$column_names} FROM {$pars['tab1_name']} " .
            " WHERE utente_id = {$data}";
        $query_prepared = $dbh->prepare($query);
        $query_prepared->execute();

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        $content = "";
        foreach ($result as $row) {
            $row_id = $row['id'];
            unset($row['id']);

            $select_row = implode(" | ", $row);
            $select_row = "<option value='{$row_id}'>" . $select_row . "</option>";
            $content .= $select_row;
        }

        return $content;
    }

}

?>