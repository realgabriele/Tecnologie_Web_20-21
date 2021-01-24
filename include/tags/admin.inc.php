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

        $columns = [];
        foreach (explode(";", $pars['columns']) as $column) {
            $result_table->setContent("column_name", $column);
            $columns[] = "`".$pars['tab2_name']."`.".$column;
        }
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
            $table_row = "";
            foreach ($row as $item) {
                $table_row .= "<td>{$item}</td>";
            }
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

        $columns = [];
        foreach (explode(";", $pars['columns']) as $column) {
            $result_table->setContent("column_name", $column);
            $columns[] = "`".$pars['tab2_name']."`.".$column;
        }
        $column_names = implode(", ", $columns);

        global $dbh;
        $query = "SELECT DISTINCT {$column_names} FROM {$pars['tab2_name']} " .
            " WHERE {$pars['tab1id_name']}_id = {$data}";
        $query_prepared = $dbh->prepare($query);
        $query_prepared->execute();

        $result = $query_prepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $table_row = "";
            foreach ($row as $item) {
                $table_row .= "<td>{$item}</td>";
            }
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


}

?>